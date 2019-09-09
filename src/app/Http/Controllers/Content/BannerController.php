<?php

namespace ZetthCore\Http\Controllers\Content;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Banner;

class BannerController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url($this->adminPath . '/content/banners');
        $this->page_title = 'Kelola Spanduk';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url($this->adminPath . '/content/banners'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Spanduk',
            'icon' => '',
            'url' => $this->current_url,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            'page_subtitle' => 'Daftar Spanduk',
        ];

        return view('zetthcore::AdminSC.content.banners', $data);
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
            'page_subtitle' => 'Tambah Spanduk',
        ];

        return view('zetthcore::AdminSC.content.banners_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        /* set variable */
        $orders = explode(',', $r->input('orders'));

        /* validation */
        if (bool($r->input('only_image'))) {
            $validate = [
                'image' => 'mimes:jpg,jpeg,png,svg|max:200|dimensions:max_width=500,max_height=500',
            ];
        } else {
            $validate = [
                'title' => 'required',
                'description' => 'required',
                'image' => 'mimes:jpg,jpeg,png,svg|max:200|dimensions:max_width=500,max_height=500',
            ];
        }
        $this->validate($r, $validate);

        /* save data */
        $banner = new Banner;
        $banner->title = str_sanitize($r->input('title'));
        $banner->description = str_sanitize($r->input('description'));
        $banner->url = str_sanitize($r->input('url'));
        $banner->url_external = bool($r->input('url_external')) ? 1 : 0;
        $banner->target = str_sanitize($r->input('target'));
        // $banner->order = $order;
        $banner->only_image = bool($r->input('only_image')) ? 1 : 0;
        $banner->status = bool($r->input('status')) ? 1 : 0;
        $banner->save();

        /* set order */
        if ($r->input('order') == 'first') {
            array_unshift($orders, $banner->id);
        } else {
            $key = array_search($r->input('order'), $orders);
            array_splice($orders, ($key + 1), 0, $banner->id);
        }
        $this->sortQuery($r, $orders);

        /* upload image */
        if ($r->hasFile('image')) {
            $file = $r->file('image');
            $par = [
                'file' => $file,
                'folder' => '/assets/images/banner/',
                'name' => str_slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
                'type' => $file->getMimeType(),
                'ext' => pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION),
            ];

            if ($this->uploadImage($par)) {
                $banner->image = $par['name'] . '.' . $par['ext'];
                $banner->save();
            }
        }

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Spanduk "' . $banner->title . '"');

        return redirect($this->current_url)->with('success', 'Spanduk berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        /* get data */
        $banners = Banner::select('id', 'order', 'title')->orderBy('order')->get();

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Edit Spanduk',
            'banners' => $banners,
            'data' => $banner,
        ];

        return view('admin.content.banner_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Banner $banner)
    {
        /* set variable */
        $orders = explode(',', $r->input('orders'));

        /* validation */
        if (bool($r->input('only_image'))) {
            $validate = [
                'image' => 'mimes:jpg,jpeg,png,svg|max:200|dimensions:max_width=500,max_height=500',
            ];
        } else {
            $validate = [
                'title' => 'required',
                'description' => 'required',
                'image' => 'mimes:jpg,jpeg,png,svg|max:200|dimensions:max_width=500,max_height=500',
            ];
        }
        $this->validate($r, $validate);

        /* save data */
        $banner->title = str_sanitize($r->input('title'));
        $banner->description = str_sanitize($r->input('description'));
        $banner->url = str_sanitize($r->input('url'));
        $banner->url_external = bool($r->input('url_external')) ? 1 : 0;
        $banner->target = str_sanitize($r->input('target'));
        // $banner->order = $order;
        $banner->only_image = bool($r->input('only_image')) ? 1 : 0;
        $banner->status = bool($r->input('status')) ? 1 : 0;
        $banner->save();

        /* set order */
        if ($r->input('order') == 'first') {
            array_unshift($orders, $banner->id);
        } else {
            $key = array_search($r->input('order'), $orders);
            array_splice($orders, ($key + 1), 0, $banner->id);
        }
        $this->sortQuery($r, $orders);

        /* upload image */
        if ($r->hasFile('image')) {
            $file = $r->file('image');
            $par = [
                'file' => $file,
                'folder' => '/assets/images/banner/',
                'name' => str_slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
                'type' => $file->getMimeType(),
                'ext' => pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION),
            ];

            if ($this->uploadImage($par)) {
                $banner->image = $par['name'] . '.' . $par['ext'];
                $banner->save();
            }
        }

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Spanduk "' . $banner->title . '"');

        return redirect($this->current_url)->with('success', 'Spanduk berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Spanduk "' . $banner->title . '"');

        /* soft delete */
        $banner->delete();

        return redirect($this->current_url)->with('success', 'Spanduk berhasil dihapus!');
    }

    /**
     * Undocumented function
     *
     * @param Request $r
     * @return void
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Banner::select('id', 'title', 'description', 'image', 'order', 'status')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }

    /**
     * Undocumented function
     *
     * @param Request $r
     * @return void
     */
    public function sort(Request $r)
    {
        /* get data */
        $banners = Banner::orderBy('order')->get();

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Urutkan Spanduk',
            'data' => $banners,
        ];

        return view('admin.content.banner_sort', $data);
    }

    /**
     * Undocumented function
     *
     * @param Request $r
     * @param array $data
     * @return void
     */
    public function sortSave(Request $r, $data = [])
    {
        /* validation */
        $this->validate($r, [
            'sort' => 'required',
        ]);

        /* save position */
        $save = $this->sortQuery($r);

        return redirect($this->current_url)->with('success', 'Spanduk berhasil diurutkan!');
    }

    /**
     * Undocumented function
     *
     * @param Request $r
     * @param array $data
     * @return void
     */
    public function sortQuery(Request $r, $data = [])
    {
        /* mapping values */
        $updates = [];
        $sorts = empty($data) ? json_decode($r->input('sort'))[0] : $data;
        foreach ($sorts as $order => $sort) {
            $id = $sort->id ?? $sort;
            $updates[] = Banner::where('id', $id)->update([
                'order' => ($order + 1),
            ]);
        }

        return $updates;
    }
}
