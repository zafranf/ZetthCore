<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TrimLogPartitions extends Command
{
    protected $signature = 'zetth:trim-partitions
        {table? : Nama tabel log (kosong = visitor_logs dan activity_logs)}
        {--keep-months=3 : Jumlah bulan ke depan yang dipertahankan sebagai partisi nyata}
        {--apply : Benar-benar drop partisi (default hanya menampilkan rencana)}';

    protected $description = 'Ringkas partisi tabel log: buang partisi lampau yang kosong dan partisi masa depan yang jauh (dry-run default)';

    public function handle()
    {
        $tables = $this->argument('table')
            ? [$this->argument('table')]
            : ['visitor_logs', 'activity_logs'];

        $keepMonths = max(1, (int) $this->option('keep-months'));
        $apply = (bool) $this->option('apply');

        /* batas: partisi dengan label bulan > batas ini dianggap "jauh di depan" */
        $horizon = now()->startOfMonth()->addMonths($keepMonths)->format('Ym');
        $currentMonth = now()->format('Ym');

        foreach ($tables as $table) {
            $partitions = DB::select(
                'select PARTITION_NAME name from information_schema.PARTITIONS
                 where TABLE_SCHEMA = database() and TABLE_NAME = ? and PARTITION_NAME is not null
                 order by PARTITION_ORDINAL_POSITION',
                [$table]
            );

            if (empty($partitions)) {
                $this->warn("⚠️  Tabel {$table} tidak ada atau tidak terpartisi. Dilewati.");
                continue;
            }

            $drop = [];

            foreach ($partitions as $partition) {
                if ($partition->name === 'pmax') {
                    continue;
                }

                $label = substr($partition->name, 1);
                $isBeyondHorizon = $label > $horizon;
                $isPastMonth = $label < $currentMonth;

                if (!$isBeyondHorizon && !$isPastMonth) {
                    continue;
                }

                /* hanya partisi kosong yang boleh dibuang */
                $rows = DB::selectOne("select count(*) c from {$table} partition ({$partition->name})")->c;

                if ($rows > 0) {
                    $this->line("  lewati {$partition->name}: masih berisi {$rows} baris");
                    continue;
                }

                $drop[] = $partition->name;
            }

            $remaining = count($partitions) - count($drop);
            $this->info("{$table}: " . count($partitions) . ' partisi → drop ' . count($drop) . ", sisa {$remaining}");

            if (empty($drop)) {
                continue;
            }

            $preview = array_slice($drop, 0, 8);
            $this->line('  ' . implode(', ', $preview) . (count($drop) > 8 ? ', ... (+' . (count($drop) - 8) . ')' : ''));

            if (!$apply) {
                continue;
            }

            $started = microtime(true);
            DB::statement("ALTER TABLE {$table} DROP PARTITION " . implode(', ', $drop));
            $this->info(sprintf('  ✅ di-drop dalam %.1fs', microtime(true) - $started));
        }

        if (!$apply) {
            $this->comment('Dry-run. Jalankan ulang dengan --apply untuk mengeksekusi.');
        }

        return 0;
    }
}
