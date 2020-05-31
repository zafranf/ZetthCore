<?php

namespace ZetthCore\Http\Controllers\Log;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\ErrorLog;

class ErrorController extends AdminController
{
    private $current_url;
    private $page_title;

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

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Daftar Galat',

            'logs' => $this->log_viewer->all(),
            'folders' => $this->log_viewer->getFolders(),
            'current_folder' => $this->log_viewer->getFolderName(),
            'folder_files' => $folderFiles,
            'files' => $this->log_viewer->getFiles(true),
            'current_file' => $this->log_viewer->getFileName(),
            'standardFormat' => true,
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
