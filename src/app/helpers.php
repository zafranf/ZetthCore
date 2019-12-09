<?php
if (!function_exists('adminPath')) {
    function adminPath()
    {
        $adminPath = '/admin';
        $host = parse_url(url('/'))['host'];
        if (strpos($host, 'admin') !== false) {
            $adminPath = '';
        }

        return $adminPath;
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
    function _get_status_text($sts = 0, $par = [])
    {
        /* check custom parameter */
        if (empty($par)) {
            $par = ['Nonaktif', 'Aktif'];
        }

        /* generate text */
        if ($sts == 0) {
            echo '<span class="bg-danger text-center" style="padding:2px 3px;">' . $par[0] . '</span>';
        } else {
            echo '<span class="bg-success text-center" style="padding:2px 3px;">' . $par[1] . '</span>';
        }
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
    function _get_image($image = "", $default = '/assets/images/default.jpg')
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
            $img = url($default);
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
    /**
     * Generate Top Menu
     *
     * @return void
     */
    function generateMenu($group = 'admin')
    {
        /* get all menus */
        $menus = getMenu($group, true);

        echo '<ul class="nav navbar-nav">';
        foreach ($menus as $menu) {
            $active = (!empty($menu->route_name) && route($menu->route_name) == url()->current()) ? 'active' : '';
            $href = !empty($menu->route_name) ? 'href="' . route($menu->route_name) . '"' : null;
            $href = $href ?? ($menu->url ? 'href="' . url($menu->url) . '"' : '');
            $sub = count($menu->submenu) ? ' dropdown' : '';
            $sublink = count($menu->submenu) ? ' dropdown-toggle' : '';
            $subtoggle = count($menu->submenu) ? ' data-toggle="dropdown" role="button"' : '';
            $icon = ($menu->icon != "") ? '<i class="' . $menu->icon . '"></i>' : '';
            $caret = (count($menu->submenu) > 0) ? '<span class="pull-right"><span class="caret"></span></span>' : '';
            echo '<li class="' . ($sub ?? '') . ' ' . $active . '">';
            echo '<a ' . ($href ?? '') . ' class="' . ($sublink ?? '') . '"' . ($subtoggle ?? '') . ' target="' . $menu->target . '">' . $icon . ' ' . $menu->name . $caret . '</a>';
            if (count($menu->submenu) > 0) {
                generateSubmenu($menu->submenu);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}

if (!function_exists('generateSubmenu')) {
    /**
     * Generate Top Submenu
     *
     * @return void
     */
    function generateSubmenu($data, $level = 0)
    {
        $sublevel = ($level > 0) ? 'sub-menu' : '';
        echo '<ul class="dropdown-menu ' . $sublevel . '" role="menu">';
        foreach ($data as $submenu) {
            $active = (!empty($submenu->route_name) && route($submenu->route_name) == url()->current()) ? 'active' : '';
            $href = !empty($submenu->route_name) ? 'href="' . route($submenu->route_name) . '"' : null;
            $href = $href ?? ($submenu->url ? 'href="' . url($submenu->url) . '"' : '');
            $dropdown = count($submenu->submenu) ? 'dropdown-submenu' : 'dropdown';
            $sublink = count($submenu->submenu) ? ' dropdown-toggle submenu' : '';
            $subtoggle = count($submenu->submenu) ? ' data-toggle="dropdown" role="button"' : '';
            $icon = ($submenu->icon != '') ? '<i class="' . $submenu->icon . '"></i>' : '';
            $caret_class = !app('is_mobile') ? ' style="position: absolute;right: 10px;top: 3px;"' : ' class="pull-right"';
            $direction = app('is_mobile') ? 'down' : 'right';
            $caret = !app('is_mobile') ? 'fa fa-caret-' . $direction : 'caret';
            $caret = (count($submenu->submenu) > 0) ? '<span ' . $caret_class . '><span class="' . $caret . '"></span></span>' : '';
            echo '<li class="' . $dropdown . '">';
            echo '<a ' . ($href ?? '') . ' class="dropdown-item' . ($sublink ?? '') . ' ' . $active . '" ' . ($subtoggle ?? '') . ' target="' . $submenu->target . '">' . $icon . ' ' . $submenu->name . $caret . '</a>';
            if (count($submenu->submenu)) {
                generateSubmenu($submenu->submenu, $level + 1);
            }
            echo '</li>';
        }
        echo '</ul>';
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

/* if (!function_exists('is_mobile')) {
function app('is_mobile')
{
return (new \Jenssegers\Agent\Agent())->isMobile();
}
}

if (!function_exists('app('is_tablet')')) {
function app('is_tablet')()
{
return (new \Jenssegers\Agent\Agent())->isTablet();
}
}

if (!function_exists('app('is_desktop')')) {
function app('is_desktop')()
{
return (new \Jenssegers\Agent\Agent())->isDesktop();
}
} */

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
        $path = str_replace("themes/admin", "themes", $file);
        $path = dirname(__DIR__) . '/resources/' . ltrim($path, '/');
        if (file_exists($path)) {
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
        $path = str_replace("themes/admin", "themes", $file);
        $path = dirname(__DIR__) . '/resources/' . ltrim($path, '/');
        if (file_exists($path)) {
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
