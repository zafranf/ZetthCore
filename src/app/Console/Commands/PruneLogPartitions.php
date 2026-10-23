<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneLogPartitions extends Command
{
    protected $signature = 'zetth:prune-partition {table} {--months=6 : Simpan data N bulan terakhir} {--dry-run : Tampilkan rencana tanpa mengeksekusi}';

    protected $description = 'Drop old partitions from a log table based on created_at monthly partitioning';

    public function handle()
    {
        $table = $this->argument('table');
        $months = (int) $this->option('months');
        $dryRun = (bool) $this->option('dry-run');

        /* daftar partisi NYATA dari information_schema — jangan menebak label bulan */
        $partitions = DB::select(
            'select PARTITION_NAME name from information_schema.PARTITIONS
             where TABLE_SCHEMA = database() and TABLE_NAME = ? and PARTITION_NAME is not null
             order by PARTITION_ORDINAL_POSITION',
            [$table]
        );

        if (empty($partitions)) {
            $this->warn("⚠️  Table '{$table}' does not exist or is not partitioned. Skipping.");
            return 1;
        }

        $cutoff = now()->subMonths($months)->startOfMonth()->format('Ym');
        $drop = [];

        foreach ($partitions as $partition) {
            if ($partition->name === 'pmax') {
                continue;
            }

            /* label partisi: p202611 -> 202611 */
            $label = substr($partition->name, 1);

            if ($label < $cutoff) {
                $drop[] = $partition->name;
            }
        }

        if (empty($drop)) {
            $this->info("✅ {$table}: tidak ada partisi lebih lama dari {$cutoff} (simpan {$months} bulan).");
            return 0;
        }

        /* jangan pernah menyisakan tabel tanpa partisi */
        if (count($drop) === count($partitions)) {
            $this->error("❌ {$table}: semua partisi akan terhapus, dibatalkan.");
            return 1;
        }

        $sql = "ALTER TABLE {$table} DROP PARTITION " . implode(', ', $drop) . ";";
        $this->line("🛠️ {$table}: drop " . count($drop) . " partisi (< {$cutoff})");
        $this->line("   {$sql}");

        if ($dryRun) {
            $this->comment('Dry-run, tidak dieksekusi.');
            return 0;
        }

        try {
            $started = microtime(true);
            DB::statement($sql);
            $this->info(sprintf('✅ Dropped partitions: %s (%.1fs)', implode(', ', $drop), microtime(true) - $started));
        } catch (\Exception $e) {
            $this->error("❌ Failed to drop partitions: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
