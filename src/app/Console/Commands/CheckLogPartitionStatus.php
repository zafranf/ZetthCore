<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckLogPartitionStatus extends Command
{
    protected $signature = 'zetth:check-partition {table}';
    protected $description = 'Check last available partition in a partitioned log table';

    public function handle()
    {
        $table = $this->argument('table');

        try {
            $partitions = DB::table('information_schema.PARTITIONS')
                ->select('PARTITION_NAME')
                ->where('TABLE_SCHEMA', DB::getDatabaseName())
                ->where('TABLE_NAME', $table)
                ->orderBy('PARTITION_NAME', 'desc')
                ->pluck('PARTITION_NAME');

            if ($partitions->isEmpty()) {
                $this->warn("⚠️  Table '{$table}' is not partitioned or has no partitions.");
                return 1;
            }

            $this->info("✅ Table '{$table}' has " . $partitions->count() . " partitions.");
            $this->line("📦 Last partition: " . $partitions->first());
        } catch (\Exception $e) {
            $this->error("❌ Failed to check partition status: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
