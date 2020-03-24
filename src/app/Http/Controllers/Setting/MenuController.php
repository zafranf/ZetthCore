<?php

namespace ZetthCore\Http\Controllers\Setting;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Menu;

class MenuController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url(app('admin_path') . '/setting/menus');
        $this->page_title = 'Kelola Menu';
        $this->breadcrumbs[] = [
            'page' => 'Pengaturan',
            'icon' => '',
            'url' => url(app('admin_path') . '/setting/application'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Menu',
            'icon' => '',
            'url' => url(app('admin_path') . '/setting/menu-groups'),
        ];

        if (!request()->input('group') && !\App::runningInConsole()) {
            return redirect(app('admin_path') . '/setting/menu-groups')->send();
        }
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

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Daftar Menu',
        ];

        return view('zetthcore::AdminSC.setting.menu', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* set breadcrumbs */
        $this->breadcrumbs[] = [
            'page' => 'Tambah',
            'icon' => '',
            'url' => '',
        ];

        /* get additional data */
        $additional = $this->getAdditionalDataOpts();

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Tambah Menu',
            'menus' => Menu::where('group_id', _get('group'))->where('parent_id', 0)->with('allSubmenu')->orderBy('order')->get(),
            'post_opts' => $additional['posts'],
        ];

        return view('zetthcore::AdminSC.setting.menu_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        /* validation */
        $this->validate($r, [
            'name' => 'required',
            // 'description' => 'required',
            'group' => 'required|exists:menu_groups,id',
        ]);

        /* save data */
        $menu = new Menu;
        $menu->name = $r->input('name');
        $menu->description = $r->input('description');
        $menu->url = $r->input('url');
        $menu->url_external = 0;
        if ($r->input('url') == 'external') {
            $menu->url = $r->input('url_external');
            $menu->url_external = 1;
        }
        $menu->route_name = $r->input('route_name');
        $menu->target = $r->input('target');
        // $menu->order = (int) $r->input('order');
        $menu->icon = $r->input('icon');
        $menu->status = bool($r->input('status')) ? 1 : 0;
        $menu->is_crud = bool($r->input('is_crud')) ? 1 : 0;
        $menu->index = bool($r->input('index')) ? 1 : 0;
        $menu->create = bool($r->input('create')) ? 1 : 0;
        $menu->read = bool($r->input('read')) ? 1 : 0;
        $menu->update = bool($r->input('update')) ? 1 : 0;
        $menu->delete = bool($r->input('delete')) ? 1 : 0;
        $menu->parent_id = (int) $r->input('parent');
        $menu->group_id = (int) $r->input('group');
        $menu->save();

        /* log aktifitas */
        $this->activityLog('<b>[~name]</b> menambahkan Menu "' . $menu->name . '"');

        /* clear cache */
        \Cache::flush();

        return redirect(app('admin_path') . '/setting/menu-groups/' . $menu->group_id . '/edit')->with('success', 'Menu "' . $menu->name . '" berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        /* set breadcrumbs */
        $this->breadcrumbs[] = [
            'page' => 'Edit',
            'icon' => '',
            'url' => '',
        ];

        /* get additional data */
        $additional = $this->getAdditionalDataOpts();

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Edit Menu',
            'menus' => Menu::where('group_id', _get('group'))->where('parent_id', 0)->with('allSubmenu')->orderBy('order')->get(),
            'post_opts' => $additional['posts'],
            'data' => $menu,
        ];

        return view('zetthcore::AdminSC.setting.menu_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Menu $menu)
    {
        /* validation */
        $this->validate($r, [
            'name' => 'required',
            // 'description' => 'required',
            'group' => 'required|exists:menu_groups,id',
        ]);

        /* get order number if null */
        if (!$r->input('order')) {
            $parent_id = $r->input('parent') ?? 0;
            $parent = Menu::where('parent_id', (int) $parent_id)->orderBy('order', 'desc')->first();
            $order = $parent->order + 1;
        }

        /* save data */
        $menu->name = $r->input('name');
        $menu->description = $r->input('description');
        $menu->url = $r->input('url');
        $menu->url_external = 0;
        if ($r->input('url') == 'external') {
            $menu->url = $r->input('url_external');
            $menu->url_external = 1;
        }
        $menu->route_name = $r->input('route_name');
        $menu->target = $r->input('target');
        $menu->order = $r->input('order') ?? $order;
        $menu->icon = $r->input('icon');
        $menu->status = bool($r->input('status')) ? 1 : 0;
        $menu->is_crud = bool($r->input('is_crud')) ? 1 : 0;
        $menu->index = bool($r->input('index')) ? 1 : 0;
        $menu->create = bool($r->input('create')) ? 1 : 0;
        $menu->read = bool($r->input('read')) ? 1 : 0;
        $menu->update = bool($r->input('update')) ? 1 : 0;
        $menu->delete = bool($r->input('delete')) ? 1 : 0;
        $menu->parent_id = (int) $r->input('parent');
        $menu->group_id = (int) $r->input('group');
        $menu->save();

        /* log aktifitas */
        $this->activityLog('<b>[~name]</b> memperbarui Menu "' . $menu->name . '"');

        /* clear cache */
        \Cache::flush();

        return redirect(app('admin_path') . '/setting/menu-groups/' . $menu->group_id . '/edit')->with('success', 'Menu "' . $menu->name . '" berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r, Menu $menu)
    {
        /* validation */
        $this->validate($r, [
            'group' => 'required|exists:menu_groups,id',
        ]);

        /* log aktifitas */
        $this->activityLog('<b>[~name]</b> menghapus Menu "' . $menu->name . '"');

        /* soft delete */
        $menu->delete();

        /* clear cache */
        \Cache::flush();

        return redirect(app('admin_path') . '/setting/menu-groups/' . $menu->group_id . '/edit')->with('success', 'Menu "' . $menu->name . '" berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Menu::select('id', 'name', 'description', 'status');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }

    /**
     * Menu Sort Form
     */
    public function sort(Request $r)
    {
        /* get data */
        $menus = Menu::where('parent_id', 0)->with('allSubmenu')->orderBy('order')->get();

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Urutkan Menu',
            'data' => $menus,
        ];

        return view('admin.setting.menu_sort', $data);
    }

    /* Save Sorted Menu */
    public function sortSave(Request $r, $data = [], $parent = 0)
    {
        /* validation */
        $this->validate($r, [
            'sort' => 'required',
        ]);

        /* save position */
        $save = $this->sortQuery($r);

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Menu berhasil diurutkan!');
    }

    /* Do Save Menu */
    public function sortQuery(Request $r, $data = [], $parent = 0)
    {
        /* mapping values */
        $updates = [];
        $sorts = empty($data) ? json_decode($r->input('sort'))[0] : $data;
        foreach ($sorts as $order => $sort) {
            $updates[] = Menu::where('id', $sort->id)->update([
                'order' => ($order + 1),
                'parent_id' => $parent,
            ]);
            if (count($sort->children) > 0) {
                foreach ($sort->children as $child) {
                    if (!empty($child)) {
                        $updates = array_merge($updates, $this->sortQuery($r, $child, $sort->id));
                    }
                }
            }
        }

        return $updates;
    }
}
