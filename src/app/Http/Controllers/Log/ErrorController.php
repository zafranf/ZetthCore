<?php

namespace ZetthCore\Http\Controllers\Log;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\ErrorLog;

class ErrorController extends AdminController
{
    private $current_url;
    private $page_title;
    private $log_viewer;

    /* Batas aman parse, terukur di memory_limit 128 MB:
       10 MB -> ~91 MB RAM, 15 MB -> ~123 MB. Di atas ambang ini file dibaca
       dari ekornya saja. */
    const LOG_TAIL_THRESHOLD = 5 * 1024 * 1024;
    const LOG_TAIL_BYTES = 1024 * 1024;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = _url(adminPath() . '/log/errors');
        $this->page_title = 'Catatan Galat';
        $this->breadcrumbs[] = [
            'page' => 'Catatan',
            'icon' => '',
            'url' => _url(adminPath() . '/log/activities'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Galat',
            'icon' => '',
            'url' => $this->current_url,
        ];

        $this->log_viewer = new \Rap2hpoutre\LaravelLogViewer\LaravelLogViewer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        /* set breadcrumbs */
        $this->breadcrumbs[] = [
            'page' => 'Daftar',
            'icon' => '',
            'url' => '',
        ];

        /* folder & files */
        $folderFiles = [];
        if ($r->input('f')) {
            $this->log_viewer->setFolder(urldecode(_decrypt($r->input('f'))));
            $folderFiles = $this->log_viewer->getFolderFiles(true);
        }
        if ($r->input('l')) {
            $this->log_viewer->setFile(urldecode(_decrypt($r->input('l'))));
        }

        if ($early_return = $this->earlyReturn($r)) {
            return $early_return;
        }

        $limit = (int) $r->get('limit', 10000);
        $page = max((int) $r->get('page', 1), 1);
        $offset = ($page - 1) * $limit;

        /* all() menyalin setiap entri log ke array: terukur 10 MB -> ~91 MB RAM,
           15 MB -> ~123 MB, sementara memory_limit PHP 128 MB. File besar dibaca
           dari ekornya supaya halaman tetap terbuka (bukan fatal 500). */
        $currentName = $this->log_viewer->getFileName();
        $currentLog = $currentName ? $this->log_viewer->pathToLogFile($currentName) : null;
        $fileSize = ($currentLog && is_file($currentLog)) ? filesize($currentLog) : 0;

        $tailMode = false;
        $tailTemp = null;

        if ($fileSize > self::LOG_TAIL_THRESHOLD) {
            $tailTemp = $this->tailCopy($currentLog, self::LOG_TAIL_BYTES);

            if ($tailTemp) {
                $this->log_viewer->setFile($tailTemp);
                $tailMode = true;
            }
        }

        $logs = $this->log_viewer->all();

        if ($tailTemp) {
            @unlink($tailTemp);
            $this->log_viewer->setFile($currentName); // kembalikan label + link download ke file asli
        }

        /* all() mengembalikan null bila file log melebihi batas (default 50 MB).
           View sudah menangani null dengan pesan + link download, jadi jangan
           dipaksa jadi array di sini. */
        $total = is_array($logs) ? count($logs) : 0;
        $logs = is_array($logs) ? array_slice($logs, $offset, $limit) : null;

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Daftar Galat',

            'folders' => $this->log_viewer->getFolders(),
            'current_folder' => $this->log_viewer->getFolderName(),
            'folder_files' => $folderFiles,
            'files' => $this->log_viewer->getFiles(true),
            'current_file' => $this->log_viewer->getFileName(),
            'logs' => $logs,
            'standardFormat' => true,
            'next_offset' => $offset + $limit,
            'has_more' => ($offset + $limit) < $total,
            'tail_mode' => $tailMode,
            'tail_bytes' => self::LOG_TAIL_BYTES,
            'file_size' => $fileSize,
        ];

        if ($r->wantsJson()) {
            return $data;
        }

        if (is_array($data['logs']) && count($data['logs']) > 0) {
            $firstLog = reset($data['logs']);
            if (!$firstLog['context'] && !$firstLog['level']) {
                $data['standardFormat'] = false;
            }
        }

        return view('zetthcore::AdminSC.log.error', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\ErrorLog  $error
     * @return \Illuminate\Http\Response
     */
    public function show(ErrorLog $error)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\ErrorLog  $error
     * @return \Illuminate\Http\Response
     */
    public function edit(ErrorLog $error)
    {
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\ErrorLog  $error
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, ErrorLog $error)
    {
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\ErrorLog  $error
     * @return \Illuminate\Http\Response
     */
    public function destroy(ErrorLog $error)
    {
        abort(403);
    }

    /**
     * @return bool|mixed
     * @throws \Exception
     */
    private function earlyReturn(Request $r)
    {
        if ($r->input('f')) {
            $this->log_viewer->setFolder(_decrypt($r->input('f')));
        }

        if ($r->input('dl')) {
            return $this->download($this->pathFromInput($r, 'dl'));
        } elseif ($r->has('clean')) {
            app('files')->put($this->pathFromInput($r, 'clean'), '');
            return $this->redirect(url()->previous());
        } elseif ($r->has('del')) {
            app('files')->delete($this->pathFromInput($r, 'del'));
            return $this->redirect($r->url());
        } elseif ($r->has('delall')) {
            $files = ($this->log_viewer->getFolderName())
                ? $this->log_viewer->getFolderFiles(true)
                : $this->log_viewer->getFiles(true);
            foreach ($files as $file) {
                app('files')->delete($this->log_viewer->pathToLogFile($file));
            }
            return $this->redirect($r->url());
        }
        return false;
    }

    /**
     * @param string $input_string
     * @return string
     * @throws \Exception
     */
    private function pathFromInput(Request $r, $input_string)
    {
        return $this->log_viewer->pathToLogFile(_decrypt($r->input($input_string)));
    }

    /**
     * Salin sebagian ekor file log ke file sementara (di folder logs) agar bisa
     * diparse tanpa menghabiskan memori. Return path sementara atau null.
     *
     * @param string $file
     * @param int $bytes
     * @return string|null
     */
    private function tailCopy($file, $bytes)
    {
        if (!$file || !is_file($file) || !is_readable($file)) {
            return null;
        }

        $size = filesize($file);
        $handle = @fopen($file, 'rb');

        if (!$handle) {
            return null;
        }

        $start = max(0, $size - $bytes);
        fseek($handle, $start);
        $data = fread($handle, $bytes);
        fclose($handle);

        if ($data === false || $data === '') {
            return null;
        }

        /* baris pertama hampir pasti terpotong kalau file lebih besar dari ekor */
        if ($start > 0) {
            $newline = strpos($data, PHP_EOL);

            if ($newline !== false) {
                $data = substr($data, $newline + strlen(PHP_EOL));
            }
        }

        $temp = storage_path('logs/.tail-' . \Illuminate\Support\Str::random(12) . '.log');

        if (@file_put_contents($temp, $data) === false) {
            return null;
        }

        return $temp;
    }

    /**
     * @param $to
     * @return mixed
     */
    private function redirect($to)
    {
        if (function_exists('redirect')) {
            return redirect($to);
        }

        return app('redirect')->to($to);
    }

    /**
     * @param string $data
     * @return mixed
     */
    private function download($data)
    {
        if (function_exists('response')) {
            return response()->download($data);
        }

        // For laravel 4.2
        return app('\Illuminate\Support\Facades\Response')->download($data);
    }
}
