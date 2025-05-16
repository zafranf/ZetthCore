<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PruneLogPartitions extends Command
{
    protected $signature = 'zetth:prune-partition {table} {--months=6}';
    protected $description = 'Drop old partitions from a log table based on created_at monthly partitioning';

    public function handle()
    {
        $table = $this->argument('table');
        $months = (int) $this->option('months');

        // Cek apakah table ada dan ter-partisi
        $partitioned = DB::table('information_schema.PARTITIONS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->limit(1)
            ->exists();

        if (!$partitioned) {
            $this->warn("⚠️  Table '{$table}' does not exist or is not partitioned. Skipping.");
            return 1;
        }

        // Hitung cutoff bulan
        $cutoff = Carbon::now()->subMonths($months)->startOfMonth();
        $yearMonthLimit = (int) $cutoff->format('Ym');

        $partitionsToDrop = [];

        $currentYear = (int) now()->format('Y');
        $startYear = 2024;
        $endYear = $currentYear + 1; // biar jangkau partisi tahun depan
        for ($y = $startYear; $y <= $endYear; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                $label = sprintf('p%04d%02d', $y, $m);
                $compare = (int) ($y . str_pad($m, 2, '0', STR_PAD_LEFT));

                if ($compare < $yearMonthLimit) {
                    $partitionsToDrop[] = $label;
                }
            }
        }

        if (empty($partitionsToDrop)) {
            $this->info("✅ No partitions to drop.");
            return 0;
        }

        $sql = "ALTER TABLE {$table} DROP PARTITION " . implode(', ', $partitionsToDrop) . ";";
        $this->line("🛠️ Executing: {$sql}");

        try {
            DB::statement($sql);
            $this->info("✅ Dropped partitions: " . implode(', ', $partitionsToDrop));
        } catch (\Exception $e) {
            $this->error("❌ Failed to drop partitions: " . $e->getMessage());
        }

        return 0;
    }
}
