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
        $this->current_url = url(adminPath() . '/setting/menus');
        $this->page_title = 'Kelola Menu';
        $this->breadcrumbs[] = [
            'page' => 'Pengaturan',
            'icon' => '',
            'url' => url(adminPath() . '/setting/application'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Menu',
            'icon' => '',
            'url' => url(adminPath() . '/setting/menu-groups'),
        ];

        if (!request()->input('group') && !\App::runningInConsole()) {
            return redirect(adminPath() . '/setting/menu-groups')->send();
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
            'menus' => Menu::where('group_id', _get('group'))
                ->whereNull('parent_id')
                ->with('allSubmenu')
                ->orderBy('order')
                ->get(),
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

        /* set default order */
        $order = 1;
        $lastOrder = Menu::select('order')
            ->whereNull('parent_id')
            ->orderBy('order', 'desc')
            ->first();
        if ($lastOrder) {
            $order = $lastOrder->order + 1;
        }

        /* save data */
        $menu = new Menu;
        $menu->name = $r->input('name');
        $menu->description = $r->input('description');
        $menu->url = $r->input('url');
        $menu->url_external = 'no';
        if ($r->input('url') == 'external') {
            $menu->url = $r->input('url_external');
            $menu->url_external = 'yes';
        }
        $menu->route_name = $r->input('route_name');
        $menu->target = $r->input('target');
        $menu->order = $order;
        $menu->icon = $r->input('icon');
        $menu->status = $r->input('status') ?? 'inactive';
        $menu->is_crud = $r->input('is_crud') ?? 'no';
        $menu->index = $r->input('index') ?? 'no';
        $menu->create = $r->input('create') ?? 'no';
        $menu->read = $r->input('read') ?? 'no';
        $menu->update = $r->input('update') ?? 'no';
        $menu->delete = $r->input('delete') ?? 'no';
        if ($r->input('parent')) {
            $menu->parent_id = (int) $r->input('parent');
        }
        $menu->group_id = (int) $r->input('group');
        $menu->site_id = app('site')->id;
        $menu->save();

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menambahkan Menu "' . $menu->name . '"');

        /* clear cache */
        \Cache::flush();

        return redirect(adminPath() . '/setting/menu-groups/' . $menu->group_id . '/edit')->with('success', 'Menu "' . $menu->name . '" berhasil ditambah!');
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
            'menus' => Menu::where('group_id', _get('group'))
                ->whereNull('parent_id')
                ->with('allSubmenu')
                ->orderBy('order')
                ->get(),
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

        /* save data */
        $menu->name = $r->input('name');
        $menu->description = $r->input('description');
        $menu->url = $r->input('url');
        $menu->url_external = 'no';
        if ($r->input('url') == 'external') {
            $menu->url = $r->input('url_external');
            $menu->url_external = 'yes';
        }
        $menu->route_name = $r->input('route_name');
        $menu->target = $r->input('target');
        $menu->icon = $r->input('icon');
        $menu->status = $r->input('status') ?? 'inactive';
        $menu->is_crud = $r->input('is_crud') ?? 'no';
        $menu->index = $r->input('index') ?? 'no';
        $menu->create = $r->input('create') ?? 'no';
        $menu->read = $r->input('read') ?? 'no';
        $menu->update = $r->input('update') ?? 'no';
        $menu->delete = $r->input('delete') ?? 'no';
        if ($r->input('parent')) {
            $menu->parent_id = (int) $r->input('parent');
        }
        $menu->group_id = (int) $r->input('group');
        $menu->save();

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') memperbarui Menu "' . $menu->name . '"');

        /* clear cache */
        \Cache::flush();

        return redirect(adminPath() . '/setting/menu-groups/' . $menu->group_id . '/edit')->with('success', 'Menu "' . $menu->name . '" berhasil disimpan!');
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

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menghapus Menu "' . $menu->name . '"');

        /* soft delete */
        $menu->delete();

        /* clear cache */
        \Cache::flush();

        return redirect(adminPath() . '/setting/menu-groups/' . $menu->group_id . '/edit')->with('success', 'Menu "' . $menu->name . '" berhasil dihapus!');
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
        $menus = Menu::whereNull('parent_id')->with('allSubmenu')->orderBy('order')->get();

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
