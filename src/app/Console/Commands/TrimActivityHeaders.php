<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use ZetthCore\Jobs\ActivityLog;

class TrimActivityHeaders extends Command
{
    protected $signature = 'zetth:trim-activity-headers
        {--chunk=1000 : Jumlah baris per batch}
        {--apply : Tulis perubahan (default hanya menghitung)}';

    protected $description = 'Backfill kolom activity_logs.headers: sisakan hanya header yang diizinkan (buang cookie/authorization)';

    public function handle()
    {
        $chunk = max(100, (int) $this->option('chunk'));
        $apply = (bool) $this->option('apply');

        $scanned = 0;
        $changed = 0;
        $bytesBefore = 0;
        $bytesAfter = 0;
        $lastId = 0;
        $started = microtime(true);

        $this->info(($apply ? 'Mengubah' : 'Menghitung') . ' baris activity_logs (chunk ' . $chunk . ')...');

        while (true) {
            $rows = DB::table('activity_logs')
                ->select('id', 'headers')
                ->where('id', '>', $lastId)
                ->orderBy('id')
                ->limit($chunk)
                ->get();

            if ($rows->isEmpty()) {
                break;
            }

            foreach ($rows as $row) {
                $lastId = $row->id;
                $scanned++;

                if ($row->headers === null || $row->headers === '') {
                    continue;
                }

                $decoded = json_decode($row->headers, true);

                if (!is_array($decoded) || empty($decoded)) {
                    continue;
                }

                $filtered = ActivityLog::filterHeaders($decoded);

                /* semua key memang diizinkan -> tidak perlu ditulis ulang */
                if (count($filtered) === count($decoded)) {
                    continue;
                }

                $new = json_encode($filtered);

                $bytesBefore += strlen($row->headers);
                $bytesAfter += strlen($new);
                $changed++;

                if ($apply) {
                    DB::table('activity_logs')->where('id', $row->id)->update(['headers' => $new]);
                }
            }

            if ($scanned % 50000 < $chunk) {
                $this->line(sprintf('  %d baris dipindai, %d diubah (%.0fs)', $scanned, $changed, microtime(true) - $started));
            }
        }

        $this->info(sprintf(
            'Selesai: %d baris dipindai, %d %s dalam %.0fs',
            $scanned,
            $changed,
            $apply ? 'diubah' : 'akan diubah',
            microtime(true) - $started
        ));

        if ($changed) {
            $this->line(sprintf(
                'Ukuran headers: %.1f MB -> %.1f MB (hemat %.0f%%)',
                $bytesBefore / 1048576,
                $bytesAfter / 1048576,
                (1 - ($bytesAfter / max($bytesBefore, 1))) * 100
            ));
        }

        if (!$apply) {
            $this->comment('Dry-run. Jalankan ulang dengan --apply untuk menulis.');
        }

        return 0;
    }
}
