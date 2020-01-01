<?php
if (!function_exists('isAdminPath')) {
    function isAdminPath()
    {
        return request()->segment(1) == trim(adminPath(), '/');
    }
}

if (!function_exists('adminPath')) {
    function adminPath()
    {
        return '/' . env('ADMIN_PATH', 'manager');
    }
}

if (!function_exists('isAdminSubdomain')) {
    function isAdminSubdomain()
    {
        $host = parse_url(url('/'))['host'];

        return in_array(adminSubdomain(), explode(".", $host));
    }
}

if (!function_exists('adminSubdomain')) {
    function adminSubdomain()
    {
        return env('ADMIN_SUBDOMAIN', 'manager');
    }
}

if (!function_exists('isAdminPanel')) {
    function isAdminPanel()
    {
        return isAdminSubdomain() || isAdminPath();
    }
}

if (!function_exists('_get_status_text')) {
    /**
     * Undocumented function
     *
     * @param integer $sts
     * @param array $par
     * @return void
     */
    function _get_status_text($status = 0, $par = [])
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

if (!function_exists('_get_access_buttons')) {
    /**
     * Undocumented function
     *
     * @param string $url
     * @param string $btn
     * @return void
     */
    function _get_access_buttons($url = '', $btn = '')
    {
        $add = app('is_desktop') ? 'TAMBAH' : '';

        /* ambil user login */
        $user = \Auth::user();
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
                echo '<a href="' . url($url . '/create') . '" class="btn btn-default pull-right" data-toggle="tooltip" data-original-title="Tambah Data"><i class="fa fa-plus"></i>&nbsp;' . $add . '</a>';
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

if (!function_exists('_get_button_post')) {
    /**
     * [_get_button_post description]
     * @param  string $page [description]
     * @return [type]       [description]
     */
    function _get_button_post($page = '', $delete = false, $id = '')
    {
        echo '<div class="box-footer">';
        echo '<button type="submit" class="btn btn-warning">Simpan</button>';
        echo ' &nbsp;<a class="btn btn-default" href="' . url($page) . '">Batal</a>';
        if ($delete && $id != '') {
            echo '<a class="btn btn-danger pull-right" onclick="_delete(\'' . url($page . '/' . $id) . '\')">Hapus</a>';
        }
        echo '</div>';
    }
}

if (!function_exists('_get_image')) {
    /**
     * [_get_image description]
     * @param  string $path  [description]
     * @param  string $image [description]
     * @return [type]        [description]
     */
    function _get_image($image, $default = null)
    {
        $img = storage_path('app/public/' . $image);
        $fm = base_path('vendor/zafranf/zetthcore/src/resources/themes/AdminSC/plugins/filemanager/source' . $image);
        if (file_exists($img) && !is_dir($img)) {
            $mtime = filemtime($img);
            $img = url('/storage/' . $image) . '?v=' . $mtime;
        } else if (file_exists($fm) && !is_dir($fm)) {
            $mtime = filemtime($fm);
            $img = url($image) . '?v=' . $mtime;
        } else {
            $img = !is_null($default) ? url($default) : null;
        }

        return $img;
    }
}

if (!function_exists('getMenu')) {
    function getMenu($group = 'admin', $cache = false)
    {
        $roleName = '';
        if (\Auth::user()) {
            foreach (\Auth::user()->roles as $role) {
                $roleName .= ucfirst($role->name);
            }
        }
        $cacheMenuName = 'cacheMenuGroup' . studly_case($group) . $roleName;
        $cacheMenu = \Cache::get($cacheMenuName);
        if ($cacheMenu && $cache) {
            $menus = $cacheMenu;
        } else {
            $groupmenu = \ZetthCore\Models\MenuGroup::where('slug', $group)->with('menu.submenu')->first();
            if (!$groupmenu) {
                return null;
            }
            $menus = $group == 'admin' ? menuFilterPermission($groupmenu->menu) : $groupmenu->menu;

            \Cache::put($cacheMenuName, $menus, 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10)));
        }

        return $menus;
    }
}

if (!function_exists('menuFilterPermission')) {
    function menuFilterPermission($menus)
    {
        $user = \Auth::user();
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
        $menus = $menus ?? getMenu($group, true);

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
        $list_tag = $params[$index]['list']['tag'] ?? 'li';
        $list_id = $params[$index]['list']['id'] ?? null;
        $list_class = $params[$index]['list']['class'] ?? null;
        $list_active = $params[$index]['list']['active'] ?? null;
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
        $link_attr = $params[$index]['link']['attributes'] ?? null;
        $link_attributes = '';
        if (!is_null($link_attr)) {
            foreach ($link_attr as $key => $value) {
                $link_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }
        $link_additional = $params[$index]['link']['additional'] ?? null;

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
            /* set href */
            $href = (!is_null($menu->route_name) ? route($menu->route_name) : url($menu->url));

            /* set active */
            $active = ($href == url()->current()) ? 'active' : '';

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
                    (!is_null($list_class) || $list_active ? ' class="' . $list_class . ' ' . (bool($list_active) ? $active : '') . '"' : '') .
                    (!is_null($list_attr) ? $list_attributes : '') . '>';
            }

            /* print link element */
            if (!is_null($link_tag)) {
                $print .= '<' . $link_tag .
                    (!is_null($link_id) ? ' id="' . $link_id . '"' : '') .
                    (!is_null($link_class) || $link_active ? ' class="' . $link_class . ' ' . (bool($link_active) ? $active : '') . '"' : '') .
                    (!is_null($href) ? ' href="' . $href . '"' : '') .
                    (!is_null($link_attr) ? $link_attributes : '') . '>';
                if (isset($link_additional['position']) && $link_additional['position'] == 'before') {
                    $print .= $link_additional['html'];
                }
                $print .= $menu->name;
                if (isset($link_additional['position']) && $link_additional['position'] == 'after') {
                    $print .= $link_additional['html'];
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

// if (!function_exists('generateMenu')) {
//     /**
//      * Generate Top Menu
//      *
//      * @return void
//      */
//     function generateMenu($group = 'admin')
//     {
//         /* get all menus */
//         $menus = getMenu($group, true);

//         echo '<ul class="nav navbar-nav">';
//         foreach ($menus as $menu) {
//             $active = (!empty($menu->route_name) && route($menu->route_name) == url()->current()) ? 'active' : '';
//             $href = !empty($menu->route_name) ? 'href="' . route($menu->route_name) . '"' : null;
//             $href = $href ?? ($menu->url ? 'href="' . url($menu->url) . '"' : '');
//             $sub = count($menu->submenu) ? ' dropdown' : '';
//             $sublink = count($menu->submenu) ? ' dropdown-toggle' : '';
//             $subtoggle = count($menu->submenu) ? ' data-toggle="dropdown" role="button"' : '';
//             $icon = ($menu->icon != "") ? '<i class="' . $menu->icon . '"></i>' : '';
//             $caret = (count($menu->submenu) > 0) ? '<span class="pull-right"><span class="caret"></span></span>' : '';
//             echo '<li class="' . ($sub ?? '') . ' ' . $active . '">';
//             echo '<a ' . ($href ?? '') . ' class="' . ($sublink ?? '') . '"' . ($subtoggle ?? '') . ' target="' . $menu->target . '">' . $icon . ' ' . $menu->name . $caret . '</a>';
//             if (count($menu->submenu) > 0) {
//                 generateSubmenu($menu->submenu);
//             }
//             echo '</li>';
//         }
//         echo '</ul>';
//     }
// }

// if (!function_exists('generateSubmenu')) {
//     /**
//      * Generate Top Submenu
//      *
//      * @return void
//      */
//     function generateSubmenu($data, $level = 0)
//     {
//         $sublevel = ($level > 0) ? 'sub-menu' : '';
//         echo '<ul class="dropdown-menu ' . $sublevel . '" role="menu">';
//         foreach ($data as $submenu) {
//             $active = (!empty($submenu->route_name) && route($submenu->route_name) == url()->current()) ? 'active' : '';
//             $href = !empty($submenu->route_name) ? 'href="' . route($submenu->route_name) . '"' : null;
//             $href = $href ?? ($submenu->url ? 'href="' . url($submenu->url) . '"' : '');
//             $dropdown = count($submenu->submenu) ? 'dropdown-submenu' : 'dropdown';
//             $sublink = count($submenu->submenu) ? ' dropdown-toggle submenu' : '';
//             $subtoggle = count($submenu->submenu) ? ' data-toggle="dropdown" role="button"' : '';
//             $icon = ($submenu->icon != '') ? '<i class="' . $submenu->icon . '"></i>' : '';
//             $caret_class = !app('is_mobile') ? ' style="position: absolute;right: 10px;top: 3px;"' : ' class="pull-right"';
//             $direction = app('is_mobile') ? 'down' : 'right';
//             $caret = !app('is_mobile') ? 'fa fa-caret-' . $direction : 'caret';
//             $caret = (count($submenu->submenu) > 0) ? '<span ' . $caret_class . '><span class="' . $caret . '"></span></span>' : '';
//             echo '<li class="' . $dropdown . '">';
//             echo '<a ' . ($href ?? '') . ' class="dropdown-item' . ($sublink ?? '') . ' ' . $active . '" ' . ($subtoggle ?? '') . ' target="' . $submenu->target . '">' . $icon . ' ' . $submenu->name . $caret . '</a>';
//             if (count($submenu->submenu)) {
//                 generateSubmenu($submenu->submenu, $level + 1);
//             }
//             echo '</li>';
//         }
//         echo '</ul>';
//     }
// }

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
    function generateBreadcrumb($breadcrumb, $with_date = true)
    {
        echo '<ol class="breadcrumb">';
        foreach ($breadcrumb as $bread) {
            if (empty($bread['url'])) {
                echo '<li class="active">' . $bread['page'] . '</li>';
            } else {
                echo '<li><a href="' . url($bread['url']) . '">';
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
    function generateDate($date = null, string $lang = 'id')
    {
        $date = $date ?? date("Y-m-d");
        $format = ($lang == 'id') ? 'dddd, Do MMMM YYYY' : 'dddd, MMMM Do YYYY';

        return \Carbon\Carbon::parse($date)->locale($lang)->isoFormat($format);
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
        $admin_resource = dirname(__DIR__) . '/resources/themes';
        $path = str_replace("themes/admin", "", $file);
        $fullpath = $admin_resource . $path;

        if ($type == 'css') {
            $generate = _site_css($fullpath, $attributes, true);
        } else if ($type == 'js') {
            $generate = _site_js($fullpath, $attributes, true);
        }

        if ($generate) {
            return str_replace($admin_resource, '/themes/admin', $generate);
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
            $mtime = filemtime($path);
            $attr = ' rel="stylesheet" type="text/css"';
            if (!empty($attributes)) {
                $attr = '';
                foreach ($attributes as $key => $value) {
                    $attr .= ' ' . $key . '="' . $value . '"';
                }
            }

            return '<link href="' . url($file) . '?' . $mtime . '"' . $attr . '>';
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
            $mtime = filemtime($path);
            $attr = ' type="text/javascript"';
            if (!empty($attributes)) {
                $attr = '';
                foreach ($attributes as $key => $value) {
                    $attr .= ' ' . $key . '="' . $value . '"';
                }
            }

            return '<script src="' . url($file) . '?' . $mtime . '"' . $attr . '></script>';
        }

        return null;
    }
}

if (!function_exists('carbon')) {
    function carbon($carbon = null, $lang = 'id', $type = 'display')
    {
        /* set default timezone */
        $timezone = app('site')->timezone ?? env('APP_TIMEZONE', 'UTC');

        /* check user timezone */
        $user_settings = \Auth::user() ? json_decode(\Auth::user()->settings) : '[]';
        if (isset($user_settings->timezone)) {
            $timezone = $user_settings->timezone;
        }

        /* initialize new carbon */
        if (is_null($carbon)) {
            $carbon = new \Carbon\Carbon;
        } else if (is_string($carbon)) {
            $carbon = \Carbon\Carbon::parse($carbon, $timezone);
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

if (!function_exists('getCacheTime')) {
    function getCacheTime()
    {
        $minutes = env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10);

        return now()->addMinutes($minutes);
    }
}
