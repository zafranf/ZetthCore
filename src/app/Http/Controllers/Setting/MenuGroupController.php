<?php

namespace ZetthCore\Http\Controllers\Setting;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Menu;
use ZetthCore\Models\MenuGroup;

class MenuGroupController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url($this->adminPath . '/setting/menu-groups');
        $this->page_title = 'Kelola Grup Menu';
        $this->breadcrumbs[] = [
            'page' => 'Pengaturan',
            'icon' => '',
            'url' => url($this->adminPath . '/setting/application'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Menu',
            'icon' => '',
            'url' => $this->current_url,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
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
            'page_subtitle' => 'Daftar Grup Menu',
        ];

        return view('zetthcore::AdminSC.setting.menu_group', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->breadcrumbs[] = [
            'page' => 'Tambah',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Tambah Grup Menu',
        ];

        return view('zetthcore::AdminSC.setting.menu_group_form', $data);
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
            'name' => 'required|unique:menu_groups',
        ]);

        /* save data */
        $menugroup = new MenuGroup;
        $menugroup->name = $r->input('name');
        $menugroup->sodium_crypto_secretstream_xchacha20poly1305_keygen = str_slug($menugroup->name);
        $menugroup->description = $r->input('description');
        $menugroup->status = bool($r->input('status')) ? 1 : 0;
        $menugroup->save();

        /* activity log */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Grup Menu "' . $menugroup->name . '"');

        return redirect($this->current_url . '/' . $menugroup->id . '/edit')->with('success', 'Peran "' . $menugroup->name . '" berhasil ditambah, segera atur daftar menu!');
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
     * @param  \ZetthCore\Models\MenuGroup  $menugroup
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuGroup $menugroup)
    {
        $this->breadcrumbs[] = [
            'page' => 'Edit',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Edit Grup Menu',
            'data' => $menugroup->load('allMenu.submenu'),
        ];

        return view('zetthcore::AdminSC.setting.menu_group_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\MenuGroup  $menugroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, MenuGroup $menugroup)
    {
        /* validation */
        $this->validate($r, [
            'name' => 'required|unique:menu_groups,name,' . $menugroup->id . ',id',
        ]);

        /* save data */
        // $menugroup = MenuGroup::find($id);
        $menugroup->name = $r->input('name');
        $menugroup->slug = str_slug($menugroup->name);
        $menugroup->description = $r->input('description');
        $menugroup->status = bool($r->input('status')) ? 1 : 0;
        $menugroup->save();

        /* save position */
        $save = $this->sortMenu($r);

        /* activity log */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Grup Menu "' . $menugroup->name . '"');

        /* clear cache */
        \Cache::forget('cacheMenu-Group' . ucfirst($menugroup->name));

        return redirect($this->current_url . '/' . $menugroup->id . '/edit')->with('success', 'Peran "' . $menugroup->name . '" berhasil disimpan, segera atur daftar menu!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\MenuGroup  $menugroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r, MenuGroup $menugroup)
    {
        /* activity log */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Grup Menu "' . $menugroup->name . '"');

        /* soft delete */
        $menugroup->delete();

        /* clear cache */
        \Cache::forget('cacheMenu-Group' . ucfirst($menugroup->name));

        return redirect($this->current_url)->with('success', 'Grup Menu "' . $menugroup->name . '" berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* where roles */
        if (\Auth::user()->hasRole('super')) {
            $whrRole = [
                // ['status', 1],
            ];
        } else if (\Auth::user()->hasRole('admin')) {
            $whrRole = [
                // ['status', 1],
                ['id', '!=', 1],
            ];
        } else {
            $whrRole = [
                // ['status', 1],
                ['id', '!=', 1],
                // ['id', '!=', 2],
            ];
        }

        /* get data */
        $data = MenuGroup::select('id', 'name', 'description', 'status')->where($whrRole)->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }

    /* save menu position */
    public function sortMenu(Request $r, $data = [], $parent = 0)
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
                        $updates = array_merge($updates, $this->sortMenu($r, $child, $sort->id));
                    }
                }
            }
        }

        return $updates;
    }
}
