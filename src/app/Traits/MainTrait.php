<?php

namespace ZetthCore\Traits;

use Intervention\Image\Laravel\Facades\Image;

trait MainTrait
{
    public function getTemplate()
    {
        return app('site')->template->slug ?? 'WebSC';
    }

    public function activityLog($description, $user = null)
    {
        /* Filter password */
        $sensor = 'xxx';
        if (\Request::post('password')) {
            $password = \Request::post('password');
            \Request::merge(['password' => $sensor]);
        }
        if (\Request::post('password_confirmation')) {
            $passwordConfirmation = \Request::post('password_confirmation');
            \Request::merge(['password_confirmation' => $sensor]);
        }
        if (\Request::post('user_password')) {
            $userPassword = \Request::post('user_password');
            \Request::merge(['user_password' => $sensor]);
        }
        if (\Request::post('_token')) {
            $_token = \Request::post('_token');
            \Request::merge(['_token' => $sensor]);
        }

        /* dont log robot */
        $agent = new \Jenssegers\Agent\Agent();
        $is_robot = $agent->isRobot();
        if ($is_robot) {
            return true;
        }

        /* run job */
        \ZetthCore\Jobs\ActivityLog::dispatch($description, $user ?? app('user'), [
            'method' => \Request::method(),
            'path' => \Request::getRequestUri(),
            'header' => \Request::header(),
            'query' => \Request::query(),
            'post' =>  \Request::post(),
            'files' => $_FILES, //\Request::allFiles(), bawaah laravel gak bisa masuk jobs
        ], $_SERVER);

        /* rollback values */
        if (\Request::post('password')) {
            \Request::merge(['password' => $password]);
        }
        if (\Request::post('password_confirmation')) {
            \Request::merge(['password_confirmation' => $passwordConfirmation]);
        }
        if (\Request::post('user_password')) {
            \Request::merge(['user_password' => $userPassword]);
        }
        if (\Request::post('_token')) {
            \Request::merge(['_token' => $_token]);
        }
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
            $folders = explode('/', trim($path, '/'));
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
        $size = $file->getSize();

        /* process upload */
        if (strpos($type, 'image') !== false) {
            return $this->uploadImage($file, $path, $name);
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
            $size = $file->getSize();
        }

        /* preparing image file */
        $img = Image::read($file);

        /* save image */
        $saveas = $folder . '/' . ($name ?? ($filename . '.' . $ext));
        $img->save($saveas);

        /* image optimation */
        /* if ($optimation) {
            $this->uploadImageOptimation($par);
        } */

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
            $mainimage = Image::read($file);
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
                $compimage = Image::create($config['w'], $config['h'], '#fff');
                $compimage->encode('jpg');

                /* Ambil dimensi baru dari gambar utama yang telah diubah ukurannya */
                $nw = $mainimage->width();
                $nh = $mainimage->height();

                /* set ukuran gambar latar */
                $bgw = $r < $or ? $config['w'] : ceil($config['h'] * $r);
                $bgh = $r < $or ? ceil($config['w'] * $h / $w) : $config['h'];

                /* Lalu clone gambar dasar untuk dijadikan gambar latar,
                ubah ukurannya, lalu blur dan set opacity-nya */
                $bgimage = Image::read($file);
                $bgimage->resize($bgw, $bgh);
                $bgimage->blur($config['b']);
                $bgimage->opacity(50);

                /* gabungkan semua gambar menjadi satu */
                $compimage->insert($bgimage, 'center');
                $compimage->insert($mainimage, 'center');
                $compimage->save($save, 75);

                /* destroy */
                $mainimage->destroy();
                $bgimage->destroy();
                $compimage->destroy();
            }

            /* jika dimensinya sesuai, langsung pakai gambar utama */ else {
                /* clone gambar utama untuk dijadikan output */
                $mainimage->save($save, 75);
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
    /* protected function generateDataTable($builder, array $raw_columns = [])
    {
        $dt = \DataTables::eloquent($builder);
        if (!empty($raw_columns)) {
            $dt = $dt->rawColumns($raw_columns);
        }
        $dt = $dt->make();

        return $dt;
    } */

    protected function generateDataTable($builder, array $searchable = [], array $filters = [], array $escapes = [], bool $skipTotalRecords = false)
    {
        $r = request();
        // ambil semua kolom yang searchable dari request, tapi batasi ke kolom tabel
        // yang benar-benar ada (buang alias/kolom virtual seperti '0' atau 'author.fullname')
        $searchableColumns = collect($r->input('columns', []))
            ->filter(function ($col) {
                return is_array($col)
                    && ($col['searchable'] ?? 'false') === 'true'
                    && is_string($col['data'] ?? null)
                    && preg_match('/^[A-Za-z0-9_]+$/', $col['data'])
                    && !in_array($col['data'], ['0', 'created_at', 'updated_at'], true);
            })
            ->pluck('data')
            ->unique()
            ->values()
            ->all();

        if (count($searchableColumns)) {
            $table = method_exists($builder, 'getModel') ? $builder->getModel()->getTable() : null;

            if ($table) {
                $columns = \Schema::getColumnListing($table);

                /*
                 * Kualifikasi dengan nama tabel dasar. Query list admin hampir selalu
                 * JOIN tabel lain yang punya kolom bernama sama (name, initial, status),
                 * sehingga where('name', ...) tanpa prefix berakhir
                 * "Column 'name' in WHERE is ambiguous" alias tabel kosong + pesan error.
                 */
                $searchableColumns = collect($searchableColumns)
                    ->filter(fn($col) => in_array($col, $columns, true))
                    ->map(fn($col) => $table . '.' . $col)
                    ->unique()
                    ->values()
                    ->all();
            } else {
                $searchableColumns = [];
            }
        }

        $searchable = array_values(array_unique(array_merge($searchableColumns, $searchable)));

        $dt = \DataTables::eloquent($builder)
            ->filter(function ($query) use ($r, $searchable, $filters) {
                $regex = trim((string) $r->input('search.value'));

                // Global search
                if ($regex !== '' && count($searchable)) {
                    $term = str_replace(["\\", "%", "_"], ["\\\\", "\\%", "\\_"], $regex);
                    $baseTable = method_exists($query, 'getModel') ? $query->getModel()->getTable() : null;

                    $query->where(function ($q) use ($searchable, $regex, $term, $baseTable) {
                        foreach ($searchable as $column) {
                            $plain = str_contains($column, '.') ? substr($column, strrpos($column, '.') + 1) : $column;

                            /*
                             * users.name / users.email dienkripsi (_encrypt), jadi hanya bisa
                             * dicocokkan sebagai nilai terenkripsi — dan hanya untuk kolom milik
                             * tabel dasar. Tabel lain (pigeon_types.name, clubs.name) plain text.
                             */
                            $isBase = $baseTable && str_starts_with($column, $baseTable . '.');
                            if ($baseTable === 'users' && $isBase && in_array($plain, ['email', 'name'], true)) {
                                /* hanya users.name & users.email yang dienkripsi _encrypt;
                                   kolom name di tabel lain (clubs, lofts, races) plain text */
                                $q->orWhere($column, _encrypt($regex));
                            } else {
                                $q->orWhere($column, 'like', '%' . $term . '%');
                            }
                        }
                    });
                }

                // Extra filters
                foreach ($filters as $field => $type) {
                    $value = $r->get($field);

                    if ($value === null || $value === '') {
                        continue;
                    }

                    switch ($type) {
                        case 'equal':
                            $query->where($field, $value);
                            break;

                        case 'gt':
                            $query->where($field, '>', $value);
                            break;

                        case 'gte':
                            $query->where($field, '>=', $value);
                            break;

                        case 'lt':
                            $query->where($field, '<', $value);
                            break;

                        case 'lte':
                            $query->where($field, '<=', $value);
                            break;

                        case 'between':
                            if (is_array($value) && count($value) === 2) {
                                $query->whereBetween($field, $value);
                            }
                            break;

                        case 'date_range':
                            $from = $r->get("{$field}_from");
                            $to = $r->get("{$field}_to");

                            if ($from && $to) {
                                $query->whereBetween($field, [$from, $to]);
                            }
                            break;

                        case 'like':
                            $query->where($field, 'like', "$value%");
                            break;

                            // add more cases as needed
                    }
                }
            })
            ->rawColumns($escapes); // Optional: if any column contains HTML

        // Halaman log selalu memakai rentang tanggal, sehingga hitungan "total
        // seluruh riwayat" hanya memicu full index scan tanpa guna di UI.
        if ($skipTotalRecords) {
            $dt->skipTotalRecords();
        }

        return $dt->make();
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
