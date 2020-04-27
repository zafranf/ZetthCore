<?php
namespace ZetthCore\Traits;

trait MainTrait
{
    public function getTemplate()
    {
        return app('site')->template->slug ?? 'WebSC';
    }

    public function visitorLog()
    {
        /* set variable */
        $ip = _server('REMOTE_ADDR') ?? null;
        $page = _server('REQUEST_URI') ?? '/';
        $referrer = _server('HTTP_REFERER') ?? null;
        $referrer = str_replace(url('/'), "", $referrer);
        $agent = new \Jenssegers\Agent\Agent();
        $ua = $agent->getUserAgent() ?? null;
        $browser = $agent->browser() ?? null;
        $browser_version = !is_null($browser) ? $agent->version($browser) : null;
        $device = null;
        if ($agent->isPhone()) {
            $device = 'phone';
        } else if ($agent->isTablet()) {
            $device = 'tablet';
        } else if ($agent->isDesktop()) {
            $device = 'desktop';
        }
        $device_name = $device != '' ? $agent->device() : null;
        $os = $agent->platform() ?? null;
        $os_version = !is_null($os) ? $agent->version($os) : null;
        $is_robot = $agent->isRobot() ? 'yes' : 'no';
        $robot_name = bool($is_robot) ? $agent->robot() : null;
        $site_id = app('site')->id;

        /* save log */
        $id = md5(session()->getId() . $ip . $page . $referrer . $ua . $browser . $browser_version . $device . $device_name . $os . $os_version . $is_robot . $robot_name . $site_id);
        \ZetthCore\Models\VisitorLog::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'ip' => $ip,
                'page' => $page,
                'referral' => $referrer,
                'agent' => $ua,
                'browser' => $browser,
                'browser_version' => $browser_version,
                'device' => $device,
                'device_name' => $device_name,
                'os' => $os,
                'os_version' => $os_version,
                'is_robot' => $is_robot,
                'robot_name' => $robot_name,
                'count' => \DB::raw('count+1'),
                'site_id' => $site_id,
            ]
        );
    }

    public function activityLog($description)
    {
        /* Filter password */
        $sensor = 'xxx';
        if (isset($_POST['password'])) {
            $_POST['password'] = $sensor;
        }
        if (isset($_POST['password_confirmation'])) {
            $_POST['password_confirmation'] = $sensor;
        }
        if (isset($_POST['user_password'])) {
            $_POST['user_password'] = $sensor;
        }
        if (isset($_POST['_token'])) {
            $_POST['_token'] = $sensor;
        }

        $act = new \ZetthCore\Models\ActivityLog;
        $act->description = $description;
        $act->method = \Request::method();
        $act->path = \Request::path();
        $act->ip = \Request::server('REMOTE_ADDR');
        $act->get = json_encode($_GET);
        $act->post = json_encode($_POST);
        $act->files = json_encode($_FILES);
        $act->user_id = app('user')->id ?? null;
        $act->site_id = app('site')->id;
        $act->save();
    }

    public function errorLog($e)
    {
        $log = [
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'message' => substr($e->getMessage(), 0, 191),
            'path' => !app()->runningInConsole() ? \Request::path() : null,
            'params' => !app()->runningInConsole() ? json_encode(\Request::all()) : [],
            'trace' => json_encode($e->getTrace()),
        ];
        if (isset($e->data)) {
            $log['data'] = $e->data;
        }

        if ($e->getMessage() && $this->checkDBConnection() && (app()->bound('site') || class_exists('site'))) {
            $date = carbon_query()->format('Y-m-d');
            $err = \ZetthCore\Models\ErrorLog::updateOrCreate(
                [
                    'id' => md5($log['code'] . $log['file'] . $log['line'] . $log['path'] . $log['message'] . $date),
                    'site_id' => app('site')->id,
                ],
                [
                    'code' => $log['code'],
                    'file' => $log['file'],
                    'line' => $log['line'],
                    'path' => $log['path'],
                    'message' => $log['message'],
                    'params' => $log['params'],
                    'trace' => $log['trace'],
                    'data' => $log['data'] ?? null,
                    'count' => \DB::raw('count+1'),
                ]
            );

            /* save time histories */
            $histories = !empty($err->time_history) ? json_decode($err->time_history) : [];
            $histories[] = date("Y-m-d H:i:s");
            $err->time_history = json_encode($histories);
            $err->save();
        }

        /* return error */
        $error = ($e->getCode() != 0) ? $e->getMessage() : 'Error :(';
        if (env('APP_DEBUG')) {
            $error = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }

        return $error;
    }

    /**
     * [_upload_file description]
     * @param  array  $par [description]
     * @return [type]      [description]
     */
    public function uploadFile($file, $path = null, $name = null)
    {
        /* set folder path */
        $folder = public_path($path ?? '/assets/images/');

        /* checking folder */
        if (!is_dir($folder)) {
            $folder = storage_path('app/public');
            $folders = explode('/', trim($par['folder'], '/'));
            foreach ($folders as $foldr) {
                $folder .= '/' . $foldr;
                if (!is_dir($folder)) {
                    mkdir($folder);
                }
            }
        }

        /* set variable */
        $filename = $file->getFileName();
        $type = $file->getClientMimeType();
        $ext = $file->getClientOriginalExtension();
        $size = $file->getClientSize();

        /* process upload */
        if (strpos($type, 'image') !== false) {
            return $this->uploadImage($par);
        } else {
            /* save file */
            $file->move($folder, ($name ?? ($filename . "." . $ext)));

            return $folder . '/' . ($name ?? ($filename . "." . $ext));
        }
    }

    /**
     * [uploadImage description]
     * @param  [type] $par [description]
     * @return [type]      [description]
     */
    public function uploadImage($file, $path = null, $name = null, $optimation = false)
    {
        /* set folder path */
        $folder = public_path($path ?? '/assets/images/');

        /* folder check */
        if (!is_dir($path)) {
            $folder = storage_path('app/public');
            $folders = explode('/', trim($path, '/'));
            foreach ($folders as $foldr) {
                $folder .= '/' . $foldr;
                if (!is_dir($folder)) {
                    mkdir($folder);
                }
            }
        }

        /* set variable */
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $headers = get_headers($file, 1);
            $info = pathinfo($file);
            $filename = $info['filename'];
            $type = $headers['Content-Type'];
            $ext = $info['extension'];
            $size = $headers['Content-Length'];
        } else {
            $filename = $file->getFileName();
            $type = $file->getClientMimeType();
            $ext = $file->getClientOriginalExtension();
            $size = $file->getClientSize();
        }

        /* preparing image file */
        $img = \Image::make($file);

        /* save image */
        $saveas = $folder . '/' . ($name ?? ($filename . '.' . $ext));
        $img->save($saveas);

        /* image optimation */
        if ($optimation) {
            $this->uploadImageOptimation($par);
        }

        return $saveas;
    }

    /**
     * [uploadImageOptimation description]
     * @param  [type] $par [description]
     * @return [type]      [description]
     * source: https://gist.github.com/ianmustafa/b8ab7dfd490ff2081ac6d29d828727db
     */
    public function uploadImageOptimation($par)
    {
        /* set 2 menit */
        // ini_set('max_execution_time', 60 * 2);

        /* dimensi dan blur gambar, (w)idth, (h)eight, (b)lur */
        $imageconfig = array(
            'thumbnail' => array(
                'w' => 100,
                'h' => 75,
                'b' => 2,
            ),
            'small' => array(
                'w' => 200,
                'h' => 150,
                'b' => 4,
            ),
            'medium' => array(
                'w' => 400,
                'h' => 300,
                'b' => 8,
            ),
            'large' => array(
                'w' => 800,
                'h' => 600,
                'b' => 16,
            ),
            'opengraph' => array(
                'w' => 1200,
                'h' => 630,
                'b' => 18,
            ),
        );

        /* siapkan image */
        $file = storage_path('app/public/' . $par['folder'] . $par['name'] . "." . $par['ext']);

        /* optimasi gambar */
        foreach ($imageconfig as $suffix => $config) {
            /* folder check */
            $nfolder = storage_path('app/public/' . $par['folder'] . $suffix);
            if (!is_dir($nfolder)) {
                mkdir($nfolder);
            }

            /* file save location */
            $save = $nfolder . "/" . $par['name'] . '.' . $par['ext'];

            /* hitung aspek rasio gambar */
            $or = $config['w'] / $config['h'];

            /* clone objek gambar dasar untuk dijadikan gambar utama,
            lalu ubah ukurannya */
            $mainimage = \Image::make($file);
            $mainimage->resize($config['w'], $config['h'], function ($constraint) {
                $constraint->aspectRatio();
            });
            $w = $mainimage->width();
            $h = $mainimage->height();
            $r = $w / $h;

            /* jika rasio gambar tidak sesuai dengan dimensi target,
            kita bisa membuat gambar latar blur untuk mengisi ruang kosong di sekitar gambar */
            if ($r != $or) {
                /* buat kanvas baru */
                $compimage = \Image::canvas($config['w'], $config['h'], '#fff');
                $compimage->encode('jpg');

                /* Ambil dimensi baru dari gambar utama yang telah diubah ukurannya */
                $nw = $mainimage->width();
                $nh = $mainimage->height();

                /* set ukuran gambar latar */
                $bgw = $r < $or ? $config['w'] : ceil($config['h'] * $r);
                $bgh = $r < $or ? ceil($config['w'] * $h / $w) : $config['h'];

                /* Lalu clone gambar dasar untuk dijadikan gambar latar,
                ubah ukurannya, lalu blur dan set opacity-nya */
                $bgimage = \Image::make($file);
                $bgimage->resize($bgw, $bgh);
                $bgimage->blur($config['b']);
                $bgimage->opacity(50);

                /* gabungkan semua gambar menjadi satu */
                $compimage->insert($bgimage, 'center');
                $compimage->insert($mainimage, 'center');
                $compimage->save($save, 70);

                /* destroy */
                $mainimage->destroy();
                $bgimage->destroy();
                $compimage->destroy();
            }

            /* jika dimensinya sesuai, langsung pakai gambar utama */
            else {
                /* clone gambar utama untuk dijadikan output */
                $mainimage->save($save, 70);
                $mainimage->destroy();
            }
        }

        return true;
    }

    /**
     * Generate datatable server side
     *
     * @param [type] $request
     * @param [type] $collection
     * @return void
     */
    protected function generateDataTable($builder, array $raw_columns = [])
    {
        $dt = \DataTables::eloquent($builder);
        if (!empty($raw_columns)) {
            $dt = $dt->rawColumns($raw_columns);
        }
        $dt = $dt->make();

        return $dt;
    }

    public function checkDBConnection($driver = 'mysql')
    {
        try {
            \DB::connection($driver)->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getThemeFiles($path)
    {
        if (\File::exists($path)) {
            if (\Str::endsWith($path, '.php')) {
                require $path;
            } else {
                $mime = '';
                if (\Str::endsWith($path, '.js')) {
                    $mime = 'text/javascript';
                } elseif (\Str::endsWith($path, '.css')) {
                    $mime = 'text/css';
                } else {
                    $mime = \File::mimeType($path);
                }
                $response = response(\File::get($path), 200, ['Content-Type' => $mime]);
                $response->setSharedMaxAge(31536000);
                $response->setMaxAge(31536000);
                $response->setExpires(new \DateTime('+1 year'));

                return $response;
            }
        }

        // abort(404);
    }
}
