<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExtendLogPartitions extends Command
{
    protected $signature = 'zetth:extend-partition {table} {--months=3 : Berapa bulan ke depan yang disiapkan} {--dry-run : Tampilkan SQL tanpa mengeksekusi}';

    protected $description = 'Siapkan partisi bulanan ke depan untuk tabel log (reorganize pmax)';

    public function handle()
    {
        $table = $this->argument('table');
        $months = max(1, (int) $this->option('months'));
        $dryRun = (bool) $this->option('dry-run');

        $partitions = DB::select(
            'select PARTITION_NAME name from information_schema.PARTITIONS
             where TABLE_SCHEMA = database() and TABLE_NAME = ? and PARTITION_NAME is not null',
            [$table]
        );

        if (empty($partitions)) {
            $this->warn("⚠️  Table '{$table}' does not exist or is not partitioned. Skipping.");
            return 1;
        }

        $exists = array_column($partitions, 'name');

        /* pmax WAJIB ada: tanpa itu kita tak bisa menambah partisi baru di belakangnya */
        if (!in_array('pmax', $exists, true)) {
            $this->error("❌ {$table}: partisi pmax tidak ada — tambahkan manual sebelum extend.");
            return 1;
        }

        $new = [];
        $start = now()->startOfMonth();

        /* bulan berjalan + N bulan ke depan */
        for ($i = 0; $i <= $months; $i++) {
            $month = (clone $start)->addMonths($i);
            $label = 'p' . $month->format('Ym');

            if (in_array($label, $exists, true)) {
                continue;
            }

            $boundary = (clone $month)->addMonth()->startOfMonth()->format('Y-m-d');
            $new[] = "PARTITION {$label} VALUES LESS THAN ('{$boundary}')";
        }

        if (empty($new)) {
            $this->info("✅ {$table}: partisi {$months} bulan ke depan sudah lengkap.");
            return 0;
        }

        /* ADD PARTITION tidak bisa dipakai setelah pmax -> reorganize pmax */
        $sql = "ALTER TABLE {$table} REORGANIZE PARTITION pmax INTO (\n  "
            . implode(",\n  ", $new)
            . ",\n  PARTITION pmax VALUES LESS THAN (MAXVALUE)\n);";

        $this->line("🧩 {$table}: tambah " . count($new) . " partisi");
        $this->line($sql);

        if ($dryRun) {
            $this->comment('Dry-run, tidak dieksekusi.');
            return 0;
        }

        try {
            $started = microtime(true);
            DB::statement($sql);
            $this->info(sprintf('✅ Extended partitions for %s (%.1fs)', $table, microtime(true) - $started));
        } catch (\Exception $e) {
            $this->error('❌ Failed to extend partitions: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
