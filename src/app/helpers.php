<?php
if (!function_exists('adminRoute')) {
    function adminRoute()
    {
        return env('ADMIN_ROUTE', 'path');
    }
}

if (!function_exists('isAdminPath')) {
    function isAdminPath()
    {
        return request()->segment(1) == trim(env('ADMIN_PATH', 'manager'), '/');
    }
}

if (!function_exists('adminPath')) {
    function adminPath()
    {
        if (isAdminPath()) {
            return '/' . env('ADMIN_PATH', 'manager');
        }

        return null;
    }
}

if (!function_exists('isAdminSubdomain')) {
    function isAdminSubdomain()
    {
        $host = parse_url(_url('/'))['host'];

        return in_array(env('ADMIN_SUBDOMAIN', 'manager'), explode(".", $host));
    }
}

if (!function_exists('adminSubdomain')) {
    function adminSubdomain()
    {
        if (isAdminSubdomain()) {
            return env('ADMIN_SUBDOMAIN', 'manager');
        }

        return null;
    }
}

if (!function_exists('isAdminPanel')) {
    function isAdminPanel()
    {
        $isAdminSubdomain = adminRoute() == 'subdomain' && isAdminSubdomain();
        $isAdminPath = adminRoute() == 'path' && isAdminPath();

        return $isAdminSubdomain || $isAdminPath;
    }
}

if (!function_exists('isWWW')) {
    function isWWW($host = null)
    {
        if (is_null($host)) {
            $host = parse_url(_url('/'))['host'];
        }

        return in_array('www', explode(".", $host));
    }
}

if (!function_exists('isHTML')) {
    function isHTML($string)
    {
        return $string != strip_tags($string);
    }
}

if (!function_exists('getStatusText')) {
    /**
     * Undocumented function
     *
     * @param integer $sts
     * @param array $par
     * @return void
     */
    function getStatusText($status = 0, $par = [])
    {
        /* default params */
        $params = [
            0 => [
                'tag' => 'span',
                'attributes' => [
                    'class' => 'bg-danger text-center',
                    'style' => 'padding:2px 3px;',
                ],
                'text' => 'Nonaktif',
            ],
            1 => [
                'tag' => 'span',
                'attributes' => [
                    'class' => 'bg-success text-center',
                    'style' => 'padding:2px 3px;',
                ],
                'text' => 'Aktif',
            ],
        ];

        /* check custom parameter */
        if (!empty($par)) {
            $params = $par;
        }

        /* set variables */
        $tag = $params[$status]['tag'];
        $text = $params[$status]['text'];
        $attributes = $params[$status]['attributes'];
        $attr = '';

        /* set attributes */
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $attr .= ' ' . $key . '="' . $value . '"';
            }
        }

        /* generate element */
        return '<' . $tag . $attr . '>' . $text . '</' . $tag . '>';
    }
}

if (!function_exists('getAccessButtons')) {
    /**
     * Undocumented function
     *
     * @param string $url
     * @param string $btn
     * @return void
     */
    function getAccessButtons($url = '', $btn = '')
    {
        $add = app('is_desktop') ? 'TAMBAH' : '';

        /* ambil user login */
        $user = app('user');
        if (!$user) {
            throw new \Exception('There are no user in current session');
        }

        /* ambil route name */
        $name = \Route::current()->getName();
        $xname = explode('.', $name);
        $sliced = array_slice($xname, 0, -1);
        $newname = implode(".", $sliced);

        if ($btn == 'add') {
            if ($user->can($newname . '.create')) {
                echo '<a href="' . _url($url . '/create') . '" class="btn btn-default pull-right" data-toggle="tooltip" data-original-title="Tambah Data"><i class="fa fa-plus"></i>&nbsp;' . $add . '</a>';
            }
        } else {
            if ($user->can($newname . '.read')) {
                echo "actions += '&nbsp;<a href=\"' + url + '\" class=\"btn btn-default btn-xs\" data-toggle=\"tooltip\" data-original-title=\"Detail\"><i class=\"fa fa-eye\"></i></a>';";
            }
            if ($user->can($newname . '.update')) {
                echo "actions += '&nbsp;<a href=\"' + url + '/edit\" class=\"btn btn-default btn-xs\" data-toggle=\"tooltip\" data-original-title=\"Edit\"><i class=\"fa fa-edit\"></i></a>';";
            }
            if ($user->can($newname . '.delete')) {
                echo "actions += '&nbsp;<a href=\"#\" onclick=\"' + del + '\" class=\"btn btn-default btn-xs\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fa fa-trash\"></i></a>';";
            }
        }
    }
}

if (!function_exists('getButtonPost')) {
    /**
     * [getButtonPost description]
     * @param  string $page [description]
     * @return [type]       [description]
     */
    function getButtonPost($page = '', $delete = false, $id = '', $text = null)
    {
        echo '<div class="box-footer">';
        echo '<button type="submit" class="btn btn-warning">Simpan</button>';
        echo ' &nbsp;<a class="btn btn-default" href="' . _url($page) . '">Batal</a>';
        if ($delete && $id != '') {
            echo '<a class="btn btn-danger pull-right" onclick="_delete(\'' . _url($page . '/' . $id) . '\', \'' . $text . '\')">Hapus</a>';
        }
        echo '</div>';
    }
}

if (!function_exists('getImage')) {
    /**
     * [getImage description]
     * @param  string $path  [description]
     * @param  string $image [description]
     * @return [type]        [description]
     */
    function getImage($image, $template = 'original', $default = null)
    {
        $image = ltrim($image, '/');
        $image = str_replace('storage/', '', $image);
        $file = storage_path('app/public/' . $image);
        if (file_exists($file) && !is_dir($file)) {
            $image = str_replace('assets/images/', '', $image);
            $mtime = filemtime($file) / env('DB_PORT', 3306);

            return _url('imache/' . $template . '/' . $image) . '?v=' . $mtime;
        } else {
            return _url($default ?? adminPath() . '/themes/admin/AdminSC/images/no-image.png');
        }

        return null;
    }
}

if (!function_exists('getImageLogo')) {
    /**
     * [getImageLogo description]
     * @param  string $path  [description]
     * @param  string $image [description]
     * @return [type]        [description]
     */
    function getImageLogo($image = null, $default = null)
    {
        $default = $default ?? adminPath() . "/themes/admin/AdminSC/images/logo.v2.png";

        return getImage('/assets/images/' . ($image ?? (app('site')->logo ?? '')), 'original', $default);
    }
}

if (!function_exists('getImageUser')) {
    /**
     * [getImageUser description]
     * @param  string $path  [description]
     * @param  string $image [description]
     * @return [type]        [description]
     */
    function getImageUser($image = null, $default = null)
    {
        $default = $default ?? getImage('/assets/images/no-image-profile.jpg');

        return getImage('/assets/images/users/' . ($image ?? (\Auth::user()->image ?? '')), 'original', $default);
    }
}

if (!function_exists('getSiteConfig')) {
    function getSiteConfig()
    {
        /* get application setting */
        $host = parse_url(_url('/'))['host'];
        if (isWWW($host)) {
            $host = str_replace('www.', '', $host);
        } else if (adminRoute() == 'subdomain' && isAdminSubdomain()) {
            $host = str_replace(adminSubdomain() . '.', '', $host);
        }

        return \Cache::remember('site_config.' . $host, getCacheTime(), function () use ($host) {
            return \App\Models\Site::where('domain', $host)->with('socmed', 'template')->first();
        });
    }
}

if (!function_exists('getSiteURL')) {
    function getSiteURL($path = null)
    {
        $url = _url($path ?? '/');
        if (adminRoute() == 'subdomain') {
            $url = _url(env('APP_URL') . '/' . ltrim($path, '/'));
        }

        return $url;
    }
}

if (!function_exists('getCacheTime')) {
    function getCacheTime()
    {
        $minutes = env('APP_ENV', 'production') == 'production' ? env('CACHE_TIME', 10) : 1;

        return now()->addMinutes($minutes);
    }
}

if (!function_exists('getEmailFile')) {
    function getEmailFile($file)
    {
        if (strpos($file, 'zetthcore::') !== false) {
            return $file;
        }

        $path = str_replace('.', '/', $file);
        $file_path = resource_path('views/' . $path . '.blade.php');
        if (file_exists($file_path) && !is_dir($file_path)) {
            return $file;
        }

        /* get default view */
        $arr = explode("/", $path);
        $file = end($arr);

        return 'emails/' . $file;
    }
}

if (!function_exists('getMenu')) {
    function getMenu($group = 'admin', $cache = true)
    {
        /* get role name */
        $roleName = '';
        if (app('user')) {
            $cacheRoleMenuName = 'cacheRoleMenuGroup.' . \Str::studly($group) . '.' . app('user')->id . app('site')->id;
            $cacheRoleMenu = \Cache::remember($cacheRoleMenuName, getCacheTime(), function () use ($roleName) {
                $roles = app('user')->roles;
                foreach ($roles as $role) {
                    $roleName .= '.' . ucfirst($role->name);
                }

                return $roleName;
            });
        }

        /* get menu based on user role */
        $cacheMenuName = 'cacheMenuGroup' . \Str::studly($group) . $roleName . '.' . app('site')->id;
        $menus = \Cache::remember($cacheMenuName, getCacheTime(), function () use ($group) {
            $groupmenu = \ZetthCore\Models\MenuGroup::active()->where('slug', $group)->with('menu.submenu')->first();
            if (!$groupmenu) {
                return '-';
            }

            return $group == 'admin' ? menuFilterPermission($groupmenu->menu) : $groupmenu->menu;
        });

        return $menus != '-' ? $menus : null;
    }
}

if (!function_exists('getTimezone')) {
    function getTimezone($type = null)
    {
        /* get env timezone */
        $timezone = 'UTC';
        if ($type == 'env') {
            return $timezone;
        }

        /* get config site timezone */
        if ((app()->bound('site') || class_exists('site')) && isset(app('site')->timezone)) {
            $timezone = app('site')->timezone;

            if ($type == 'site') {
                return $timezone;
            }
        }

        /* get user timezone */
        if ((app()->bound('user') || class_exists('user')) && isset(app('user')->detail)) {
            $timezone = app('user')->detail->timezone;

            if ($type == 'user') {
                return $timezone;
            }
        }

        return $timezone;
    }
}

if (!function_exists('menuFilterPermission')) {
    function menuFilterPermission($menus)
    {
        $user = app('user');
        if (is_null($user)) {
            return $menus;
        }

        /* filter by permission */
        $menus = $menus->filter(function ($menu) use ($user) {
            return $user->can($menu->route_name);
        });

        /* filter submenu by permission */
        $menus = $menus->transform(function ($menu) use ($user) {
            if ($menu->submenu->count() > 0) {
                $menu->setRelation('submenu', menuFilterPermission($menu->submenu));
            }

            return $menu;
        });

        return $menus;
    }
}

if (!function_exists('generateMenu')) {
    function generateMenu($group, $params = [], $menus = null, $level = 0)
    {
        /* get menus */
        $menus = $menus ?? getMenu($group);
        if (is_null($menus)) {
            return null;
        }

        /* set index for params */
        $index = 'main';
        if ($level > 0) {
            $index = 'sub';
        }

        /* wrapper */
        $wrap_tag = $params[$index]['wrapper']['tag'] ?? 'ul';
        $wrap_id = $params[$index]['wrapper']['id'] ?? null;
        $wrap_class = $params[$index]['wrapper']['class'] ?? null;
        $wrap_attr = $params[$index]['wrapper']['attributes'] ?? null;
        $wrap_attributes = '';
        if (!is_null($wrap_attr)) {
            foreach ($wrap_attr as $key => $value) {
                $wrap_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        /* list */
        $list_tag = $params[$index]['list']['tag'] ?? (isset($params[$index]['list']) && !$params[$index]['list'] ? null : 'li');
        $list_id = $params[$index]['list']['id'] ?? null;
        $list_class = $params[$index]['list']['class'] ?? null;
        $list_active = $params[$index]['list']['active'] ?? null;
        $list_active_class = $params[$index]['list']['active_class'] ?? null;
        $list_attr = $params[$index]['list']['attributes'] ?? null;
        $list_attributes = '';
        if (!is_null($list_attr)) {
            foreach ($list_attr as $key => $value) {
                $list_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        /* link */
        $link_tag = $params[$index]['link']['tag'] ?? 'a';
        $link_id = $params[$index]['link']['id'] ?? null;
        $link_class = $params[$index]['link']['class'] ?? null;
        $link_active = $params[$index]['link']['active'] ?? null;
        $link_active_class = $params[$index]['link']['active_class'] ?? null;
        $link_attr = $params[$index]['link']['attributes'] ?? null;
        $link_attributes = '';
        if (!is_null($link_attr)) {
            foreach ($link_attr as $key => $value) {
                $link_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        /* initiate print */
        $print = '';

        /* print main wrapper element */
        if (!is_null($wrap_tag)) {
            $print .= '<' . $wrap_tag .
                (!is_null($wrap_id) ? ' id="' . $wrap_id . '"' : '') .
                (!is_null($wrap_class) ? ' class="' . $wrap_class . '"' : '') .
                (!is_null($wrap_attr) ? $wrap_attributes : '') . '>';
        }

        foreach ($menus as $menu) {
            $link_additional = $params[$index]['link']['additional'] ?? null;

            /* set href */
            $href = (!is_null($menu->route_name) ? route($menu->route_name) : _url($menu->url));

            /* set active */
            $active = $href == url()->current();

            /* check additional class for parent */
            if (count($menu->submenu)) {
                $index_sub = 'sub';
                if ($level > 0) {
                    $index_sub = 'sub_level';
                }

                /* parent list */
                if (isset($params[$index_sub]['parent']['list']['class'])) {
                    $list_cls = $params[$index_sub]['parent']['list']['class'];
                    if (strpos($list_class, $list_cls) === false) {
                        $list_class .= ' ' . $list_cls;
                    }
                }
                if (isset($params[$index_sub]['parent']['list']['attributes'])) {
                    $list_attr = $params[$index_sub]['parent']['list']['attributes'];
                    foreach ($list_attr as $key => $value) {
                        $list_attributes .= ' ' . $key . '="' . $value . '"';
                    }
                }

                /* parent link */
                if (isset($params[$index_sub]['parent']['link']['class'])) {
                    $link_cls = $params[$index_sub]['parent']['link']['class'];
                    if (strpos($link_class, $link_cls) === false) {
                        $link_class .= ' ' . $link_cls;
                    }
                }
                if (isset($params[$index_sub]['parent']['link']['attributes'])) {
                    $link_attr = $params[$index_sub]['parent']['link']['attributes'];
                    foreach ($link_attr as $key => $value) {
                        $link_attributes .= ' ' . $key . '="' . $value . '"';
                    }
                }
                if (isset($params[$index_sub]['parent']['link']['additional'])) {
                    $link_additional = $params[$index_sub]['parent']['link']['additional'];
                }
            }

            /* print list element */
            if (!is_null($list_tag)) {
                $print .= '<' . $list_tag .
                    (!is_null($list_id) ? ' id="' . $list_id . '"' : '') .
                    (!is_null($list_class) || $list_active ? ' class="' . $list_class . ' ' . (bool($list_active) && bool($active) ? $list_active_class : '') . '"' : '') .
                    (!is_null($list_attr) ? $list_attributes : '') . '>';
            }

            /* print link element */
            if (!is_null($link_tag)) {
                $print .= '<' . $link_tag .
                    (!is_null($link_id) ? ' id="' . $link_id . '"' : '') .
                    (!is_null($link_class) || $link_active ? ' class="' . $link_class . ' ' . (bool($link_active) && bool($active) ? $link_active_class : '') . '"' : '') .
                    (!is_null($href) ? ' href="' . $href . '"' : '') .
                    (!is_null($link_attr) ? $link_attributes : '') . '>';
                if (isset($link_additional['position']) && $link_additional['position'] == 'before') {
                    $print .= ($link_additional['html'] ?? '<i class="' . $menu->icon . '"></i>&nbsp;');
                }
                $print .= $menu->name;
                if (isset($link_additional['position']) && $link_additional['position'] == 'after') {
                    $print .= ($link_additional['html'] ?? '&nbsp;<i class="' . $menu->icon . '"></i>');
                }
                $print .= '</' . $link_tag . '>';
            }

            /* print submenu */
            if (count($menu->submenu)) {
                $print .= generateMenu($group, $params, $menu->submenu, $level + 1);
            }

            /* close list element */
            if (!is_null($list_tag)) {
                $print .= '</' . $list_tag . '>';
            }
        }

        /* close main wrapper element */
        $print .= '</' . $wrap_tag . '>';

        return $print;
    }
}

if (!function_exists('generateArrayLevel')) {
    /**
     * Generate Array To List Level
     *
     * @return void
     */
    function generateArrayLevel($data, $sub = 'submenu', $separator = '&dash;', $level = 0)
    {
        $array = [];
        $sep = $separator ? str_repeat($separator, $level) : '';
        $pad = $level * 20;
        foreach ($data as $arr) {
            $arr->name = ($sep ? '<span class="text-muted" style="padding-left: ' . $pad . 'px">' . $sep . '</span> ' : '') . $arr->name;
            $array[] = $arr;
            if (isset($arr->{$sub}) && count($arr->{$sub}) > 0) {
                $array = array_merge($array, generateArrayLevel($arr->{$sub}, $sub, $separator, $level + 1));
            }
        }

        return $array;
    }
}

if (!function_exists('generateBreadcrumb')) {
    /**
     * [generateBreadcrumb description]
     * @param  [type] $breadcrumb [description]
     * @return [type]             [description]
     */
    function generateBreadcrumb($breadcrumbs, $with_date = true)
    {
        echo '<ol class="breadcrumb">';
        foreach ($breadcrumbs as $bread) {
            if (!is_null($bread['url']) && empty($bread['url'])) {
                echo '<li class="active">' . $bread['page'] . '</li>';
            } else {
                echo '<li><a href="' . _url($bread['url'] ?? '/') . '">';
                if (isset($bread['icon'])) {
                    echo '<i class="' . $bread['icon'] . '"></i> ';
                }

                echo $bread['page'];
                echo '</a></li>';
            }
        }

        /* generate date */
        if ($with_date) {
            echo '<span class="today pull-right">' . generateDate() . '</span>';
        }

        echo '</ol>';
    }
}

if (!function_exists('generateDate')) {
    /**
     * [generateDate description]
     * @param  [type] $date [description]
     * @param  [type] $lang [description]
     * @return [type]             [description]
     */
    function generateDate($date = null, string $lang = 'id', string $format = null)
    {
        if (is_null($format)) {
            $format = ($lang == 'id') ? 'dddd, Do MMMM YYYY' : 'dddd, MMMM Do YYYY';
        }

        return carbon(($date ?? null))->isoFormat($format);
    }
}

if (!function_exists('generateHelpMenu')) {
    function generateHelpMenu($data, $level = 0)
    {
        foreach ($data as $key => $val) {
            $act = '';
            $pad = ($level + 1) * 10;

            $ico = 'circle';
            if ($level == 1) {
                $ico .= '-o';
            } else if ($level == 2) {
                $ico .= '-thin';
            } else if ($level == 3) {
                $ico = 'angle-double-right';
            } else if ($level == 4) {
                $ico = 'angle-right';
            }

            /* get user roles */
            $userRoles = app('user')->roles->map(function ($arr) {
                return $arr->name;
            })->toArray();

            /* print menu */
            if ($val->roles_array[0] == 'all' || !empty(array_intersect($userRoles, $val->roles_array))) {
                echo '<li class="' . $act . '"><a href="#' . $val->slug . '" style="padding-left: ' . $pad . 'px"><i class="fa fa-' . $ico . '"></i> ' . $val->title . '</a>';
                if (isset($val->subguide)) {
                    echo '<ul class="nav">';
                    generateHelpMenu($val->subguide, $level + 1);
                    echo '</ul>';
                }
                echo "</li>";
            }
        }
    }
}

if (!function_exists('generateHelpContent')) {
    function generateHelpContent($data, $level = 0)
    {
        foreach ($data as $key => $val) {
            /* get user roles */
            $userRoles = app('user')->roles->map(function ($arr) {
                return $arr->name;
            })->toArray();

            /* print content */
            if ($val->roles_array[0] == 'all' || !empty(array_intersect($userRoles, $val->roles_array))) {
                $dates = carbon()->isoFormat('Do MMMM YYYY') . ' - ' . carbon()->isoFormat('Do MMMM YYYY');
                if (app('is_mobile')) {
                    $dates = 'Hari ini';
                }
                $h = 'h' . ($level < 6 ? $level + 1 : 6);
                echo '<div id="' . $val->slug . '" class="section">';
                echo '<' . $h . '>' . $val->title . '</' . $h . '>';
                if ($val->cover) {
                    echo '<center><a href="' . adminPath() . $val->cover . '" target="_blank"><img src="' . adminPath() . $val->cover . '" width="100%"></a></center>';
                }
                echo str_replace('[~dates]', $dates, $val->content);
                echo '</div>';
                if (isset($val->subguide)) {
                    generateHelpContent($val->subguide, $level + 1);
                }
            }
        }
    }
}

if (!function_exists('_url')) {
    function _url($url = null, $secure = false)
    {
        if ($secure || bool(env('FORCE_HTTPS'))) {
            return secure_url($url);
        }

        return url($url);
    }
}

if (!function_exists('_admin_css')) {
    /**
     * Generate link stylesheet tag
     *
     * @param string $file
     * @param array $attributes
     * @return string
     */
    function _admin_css($file = "", $attributes = [])
    {
        return _admin_script($file, $attributes, 'css');
    }
}

if (!function_exists('_admin_js')) {
    /**
     * Generate script tag
     *
     * @param string $file
     * @param array $attributes
     * @return string
     */
    function _admin_js($file = "", $attributes = [])
    {
        return _admin_script($file, $attributes, 'js');
    }
}

if (!function_exists('_admin_script')) {
    /**
     * Generate link/script tag
     *
     * @param string $file
     * @param array $attributes
     * @return string
     */
    function _admin_script($file = "", $attributes = [], $type = 'css')
    {
        $admin_theme_path = adminPath() . '/themes/admin';
        $admin_resource = dirname(__DIR__) . '/resources/themes';
        $path = str_replace($admin_theme_path, "", $file);
        $fullpath = $admin_resource . $path;

        if ($type == 'css') {
            $generate = _site_css($fullpath, $attributes, true);
        } else if ($type == 'js') {
            $generate = _site_js($fullpath, $attributes, true);
        }

        if ($generate) {
            return str_replace($admin_resource, $admin_theme_path, $generate);
        }

        return null;
    }
}

if (!function_exists('_site_css')) {
    /**
     * Generate link stylesheet tag
     *
     * @param string $file
     * @param array $attributes
     * @return string
     */
    function _site_css($file = "", $attributes = [], $is_admin = false)
    {
        $path = !$is_admin ? resource_path($file) : $file;
        if (File::exists($path)) {
            $mtime = filemtime($path) / env('DB_PORT', 3306);
            $attr = ' rel="stylesheet" type="text/css"';
            if (!empty($attributes)) {
                $attr = '';
                foreach ($attributes as $key => $value) {
                    $attr .= ' ' . $key . '="' . $value . '"';
                }
            }

            return '<link href="' . _url($file) . '?v=' . $mtime . '"' . $attr . '>';
        }

        return null;
    }
}

if (!function_exists('_site_js')) {
    /**
     * Generate script tag
     *
     * @param string $file
     * @param array $attributes
     * @return string
     */
    function _site_js($file = "", $attributes = [], $is_admin = false)
    {
        $path = !$is_admin ? resource_path($file) : $file;
        if (File::exists($path)) {
            $mtime = filemtime($path) / env('DB_PORT', 3306);
            $attr = ' type="text/javascript"';
            if (!empty($attributes)) {
                $attr = '';
                foreach ($attributes as $key => $value) {
                    $attr .= ' ' . $key . '="' . $value . '"';
                }
            }

            return '<script src="' . _url($file) . '?v=' . $mtime . '"' . $attr . '></script>';
        }

        return null;
    }
}

if (!function_exists('_encrypt')) {
    function _encrypt($string, $salt = null)
    {
        return openssl_encrypt($string, "AES-128-ECB", env('APP_NAME') . $salt);
    }
}

if (!function_exists('_decrypt')) {
    function _decrypt($string, $salt = null)
    {
        return openssl_decrypt($string, "AES-128-ECB", env('APP_NAME') . $salt);
    }
}

if (!function_exists('carbon')) {
    function carbon($carbon = null, $lang = 'id', $type = 'display')
    {
        /* get timezone */
        $timezone = getTimezone();

        /* initialize new carbon */
        if (is_null($carbon)) {
            $carbon = new \Carbon\Carbon;
        } else if (is_string($carbon)) {
            $carbon = \Carbon\Carbon::parse($carbon, $type == 'store' ? $timezone : 'UTC');
        }

        /* set timezone as UTC for storing to database */
        if ($type == 'store') {
            return $carbon->timezone('UTC');
        }

        return $carbon->timezone($timezone)->locale($lang);
    }
}

if (!function_exists('carbon_query')) {
    function carbon_query($carbon = null)
    {
        return carbon($carbon, null, 'store');
    }
}
