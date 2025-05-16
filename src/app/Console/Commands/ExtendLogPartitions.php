<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExtendLogPartitions extends Command
{
    protected $signature = 'zetth:extend-partition {table} {--years=1}';
    protected $description = 'Extend future monthly partitions for a log table';

    public function handle()
    {
        $table = $this->argument('table');
        $years = (int) $this->option('years');
        $start = Carbon::now()->startOfMonth();
        $end = (clone $start)->addYears($years)->startOfMonth();

        $partitions = [];

        while ($start < $end) {
            $label = 'p' . $start->format('Ym');
            $next = (clone $start)->addMonth();
            $value = $next->format('Y-m-d');
            $partitions[] = "PARTITION {$label} VALUES LESS THAN ('{$value}')";
            $start->addMonth();
        }

        $sql = "ALTER TABLE {$table} ADD PARTITION (\n" . implode(",\n", $partitions) . "\n);";
        $this->line("🧩 Extending partitions with SQL:\n{$sql}");

        try {
            DB::statement($sql);
            $this->info("✅ Extended partitions for {$table}");
        } catch (\Exception $e) {
            $this->error("❌ Failed to extend partitions: " . $e->getMessage());
        }

        return 0;
    }
}
