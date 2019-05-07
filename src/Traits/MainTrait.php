<?php
namespace ZetthCore\Traits;

trait MainTrait
{
    public function visitorLog()
    {

        $device = '';
        $agent = new \Jenssegers\Agent\Agent();
        if ($agent->isPhone()) {
            $device = 'phone';
        } else if ($agent->isTablet()) {
            $device = 'tablet';
        } else if ($agent->isDesktop()) {
            $device = 'desktop';
        }
        $device_name = $agent->device();
        $ip = (_server('REMOTE_ADDR') == null) ? _server('REMOTE_ADDR') : '127.0.0.1';
        $browser_agent = $agent->getUserAgent();
        $browser = $agent->browser();
        $browser_version = $agent->version($browser);
        $os = $agent->platform();
        $os_version = $agent->version($os);
        $page = (_server('REQUEST_URI') == null) ? _server('REQUEST_URI') : '/';
        $referrer = (_server('HTTP_REFERER') == null) ? _server('HTTP_REFERER') : null;
        $referral = str_replace(url('/'), "", $referrer);
        $is_robot = $agent->isRobot() ? 1 : 0;
        $robot_name = $agent->robot() ? $agent->robot : null;

        $params = [
            'ip' => $ip,
            'page' => $page,
            'referral' => $referral,
            'agent' => $browser_agent,
            'browser' => $browser,
            'browser_version' => $browser_version,
            'device' => $device,
            'device_name' => $device_name,
            'os' => $os,
            'os_version' => $os_version,
            'is_robot' => $is_robot,
            'robot_name' => $robot_name,
        ];

        $q = \ZetthCore\Models\VisitorLog::where($params)->whereBetween('created_at', [date("Y-m-d H:00:00"), date("Y-m-d H:59:59")]);

        $v = $q->first();
        if (!$v) {
            $params['count'] = \DB::raw('count+1');
            \ZetthCore\Models\VisitorLog::create($params);
        } else {
            $q->update([
                'count' => \DB::raw('count+1'),
            ]);
        }
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

        $act = new \ZetthCore\Models\ActivityLog;
        $act->description = $description;
        $act->method = \Request::method();
        $act->path = \Request::path();
        $act->ip = \Request::server('REMOTE_ADDR');
        $act->get = json_encode($_GET);
        $act->post = json_encode($_POST);
        $act->files = json_encode($_FILES);
        $act->user_id = \Auth::user()->id ?? null;
        $act->save();
    }

    public function errorLog($e)
    {
        $log = [
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'message' => substr($e->getMessage(), 0, 191),
            'path' => \Request::path(),
            'params' => json_encode(\Request::all()),
            'trace' => json_encode($e->getTrace()),
        ];
        if (isset($e->data)) {
            $log['data'] = $e->data;
        }

        if ($e->getMessage()) {
            if (\Illuminate\Support\Facades\Schema::hasTable('applications')) {
                $err = \ZetthCore\Models\ErrorLog::updateOrCreate(
                    [
                        'code' => $log['code'],
                        'file' => $log['file'],
                        'line' => $log['line'],
                        'path' => $log['path'],
                        'message' => $log['message'],
                    ],
                    [
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

    public function sendMail($par)
    {
        $status = false;

        $mail = \Mail::send($par['view'], $par['data'], function ($mail) use ($par) {
            /* Set Sender */
            if (isset($par['from'])) {
                if (is_array($par['from'])) {
                    if (isset($par['from']['email'])) {
                        $from_name = isset($par['from']['name']) ? $par['from']['name'] : '';
                        $mail->from($par['from']['email'], $from_name);
                    }
                } else {
                    $mail->from($par['from']);
                }
            } else {
                $mail->from($cfg['username']);
            }

            /* Set Sender 'Reply To' */
            if (isset($par['reply_to'])) {
                if (is_array($par['reply_to'])) {
                    if (isset($par['reply_to']['email'])) {
                        $replyto_name = isset($par['reply_to']['name']) ? $par['reply_to']['name'] : '';
                        $mail->replyTo($par['reply_to']['email'], $replyto_name);
                    }
                } else {
                    $mail->replyTo($par['reply_to']);
                }
            }

            /* Set 'To' Recipient */
            if (isset($par['to'])) {
                if (is_array($par['to'])) {
                    /* Check if recipient more than 1 */
                    if (isset($par['to'][0])) {
                        foreach ($par['to'] as $key => $val) {
                            if (isset($val['email'])) {
                                $to_name = isset($val['name']) ? $val['name'] : '';
                                $mail->to($val['email'], $to_name);
                            } else {
                                $mail->to($val);
                            }
                        }
                    }
                    /* Check if recipient only 1 and using name */
                    else if (isset($par['to']['email'])) {
                        $to_name = isset($par['to']['name']) ? $par['to']['name'] : '';
                        $mail->to($par['to']['email'], $to_name);
                    }
                } else {
                    /* Check if recipient only 1 and just email */
                    $mail->to($par['to']);
                }
            }

            /* Set 'Cc' Recipient */
            if (isset($par['cc'])) {
                if (is_array($par['cc'])) {
                    /* Check if 'Cc' recipient more than 1 */
                    if (isset($par['cc'][0])) {
                        foreach ($par['cc'] as $key => $val) {
                            if (isset($val['email'])) {
                                $cc_name = isset($val['name']) ? $val['name'] : '';
                                $mail->cc($val['email'], $cc_name);
                            } else {
                                $mail->cc($val);
                            }
                        }
                    }
                    /* Check if 'Cc' recipient only 1 and using name */
                    else if (isset($par['cc']['email'])) {
                        $cc_name = isset($par['cc']['name']) ? $par['cc']['name'] : '';
                        $mail->cc($par['cc']['email'], $cc_name);
                    }
                } else {
                    /* Check if 'Cc' recipient only 1 and just email */
                    $mail->cc($par['cc']);
                }
            }

            /* Set 'Bcc' Recipient */
            if (isset($par['bcc'])) {
                if (is_array($par['bcc'])) {
                    /* Check if 'Bcc' recipient more than 1 */
                    if (isset($par['bcc'][0])) {
                        foreach ($par['bcc'] as $key => $val) {
                            if (isset($val['email'])) {
                                $bcc_name = isset($val['name']) ? $val['name'] : '';
                                $mail->bcc($val['email'], $bcc_name);
                            } else {
                                $mail->bcc($val);
                            }
                        }
                    }
                    /* Check if 'Bcc' recipient only 1 and using name */
                    else if (isset($par['bcc']['email'])) {
                        $bcc_name = isset($par['bcc']['name']) ? $par['bcc']['name'] : '';
                        $mail->bcc($par['bcc']['email'], $bcc_name);
                    }
                } else {
                    /* Check if 'Bcc' recipient only 1 and just email */
                    $mail->bcc($par['bcc']);
                }
            }

            /* Set Attachments */
            if (isset($par['attachments'])) {
                if (is_array($par['attachments'])) {
                    /* Check if attachment more than 1 */
                    if (isset($par['attachments'][0])) {
                        foreach ($par['attachments'] as $key => $val) {
                            if (isset($val['file'])) {
                                $attachment_name = isset($val['name']) ? $val['name'] : '';
                                $mail->attach($val['file'], $attachment_name);
                            } else {
                                $mail->attach($val);
                            }
                        }
                    }
                    /* Check if attachment only 1 and using name */
                    else if (isset($par['attachments']['file'])) {
                        $attachment_name = isset($par['attachments']['name']) ? $par['attachments']['name'] : '';
                        $mail->attach($par['attachments']['file'], $attachment_name);
                    }
                } else {
                    /* Check if attachment only 1 and just filename */
                    $mail->attach($par['attachments']);
                }
            }

            /* Set email subject */
            $mail->subject($par['subject']);

            /* Get id */
            $this->mail_id = $mail->getId();
        });

        if ($mail) {
            $status = true;
        }

        return $status;
    }

    /**
     * [_upload_file description]
     * @param  array  $par [description]
     * @return [type]      [description]
     */
    public function uploadFile($par = [])
    {
        /* set variable */
        $file = $par['file'];
        $folder = public_path($par['folder']);
        $name = $par['name'];
        $type = $par['type'];
        $ext = $par['ext'];

        /* checking folder */
        if (!is_dir($folder)) {
            mkdir($folder);
        }

        /* process upload */
        $move = $file->move($folder, $name . "." . $ext);

        return $move;
    }

    /**
     * [uploadImage description]
     * @param  [type] $par [description]
     * @return [type]      [description]
     */
    public function uploadImage($par = [], $optimation = false)
    {
        /* preparing image file */
        $img = \Image::make($par['file']);

        /* folder check */
        $folder = storage_path('app/public/');
        $folders = explode('/', $par['folder']);
        foreach ($folders as $foldr) {
            $folder .= '/' . $foldr;
            if (!is_dir($folder)) {
                mkdir($folder);
            }
        }
        // $folder = storage_path('app/public/' . $par['folder']);
        // dd($folder);

        /* insert watermark */
        /* if (isset($par['watermark'])) {
        $wmImg = \Image::make($par['watermark']);
        $img->insert($wmImg, 'center');
        } */

        /* save original */
        $img->save($folder . '/' . $par['name'] . '.' . $par['ext']);

        /* image optimation */
        if ($optimation) {
            $this->uploadImageOptimation($par);
        }

        return true;
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
    protected function generateDataTable($request, $collection, $escape = false)
    {
        if ($collection instanceof Illuminate\Database\Eloquent\Builder) {
            $collection = $collection->get();
        }

        $make = \DataTables::collection($collection);
        if (bool($escape)) {
            $make = $make->escapeColumns([]);
        }
        $make = $make->make();

        return $make;
    }
}
