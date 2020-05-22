<?php

namespace ZetthCore\Http\Controllers\Setting;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Site;
use ZetthCore\Models\Socmed;
use ZetthCore\Models\SocmedData;

class SiteController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = _url(adminPath() . '/setting/site');
        $this->page_title = 'Kelola Situs';
        $this->breadcrumbs[] = [
            'page' => 'Pengaturan',
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
        /* set breadcrumbs */
        $this->breadcrumbs[] = [
            'page' => 'Situs',
            'icon' => '',
            'url' => $this->current_url,
        ];
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
            'page_subtitle' => 'Situs',
            'socmeds' => Socmed::where('status', 'active')->get(),
        ];

        return view('zetthcore::AdminSC.setting.site', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Application  $app
     * @return \Illuminate\Http\Response
     */
    public function show(Application $app)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Application  $app
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $app)
    {
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, $id)
    {
        /* validation */
        $r->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,svg,webp|max:384|dimensions:max_width=512,max_height=512',
            'icon' => 'nullable|image|mimes:jpeg,png,svg,webp,ico|max:96|dimensions:max_width=128,max_height=128',
            'name' => 'required|max:50',
            'tagline' => 'nullable|max:100',
            'email' => 'nullable|max:100|email',
            'phone' => 'nullable|numeric',
            'perpage' => 'required|integer|min:3|max:100',

            /* socmed */
            'socmed_uname.*' => 'max:50',

            /* seo */
            'keywords' => 'nullable|max:255',
            'description' => 'nullable|max:255',
            'google_analytics' => 'nullable|max:20',

            /* location */
            'address' => 'nullable|max:255',
            'coordinate' => 'nullable|max:30',
        ]);

        /* save data */
        $app = Site::find($id);
        $app->name = $r->input('name');
        $app->description = $r->input('description');
        $app->keywords = $r->input('keywords');
        $app->tagline = $r->input('tagline');
        $app->email = $r->input('email');
        $app->address = $r->input('address');
        $app->phone = $r->input('phone');
        $app->coordinate = $r->input('coordinate');
        $app->perpage = $r->input('perpage');
        $app->enable_subscribe = $r->input('enable_subscribe') ?? 'no';
        $app->enable_like = $r->input('enable_like') ?? 'no';
        $app->enable_share = $r->input('enable_share') ?? 'no';
        $app->enable_comment = $r->input('enable_comment') ?? 'no';
        $app->google_analytics = $r->input('google_analytics');
        $app->status = $r->input('status');
        $app->active_at = carbon_query($r->input('active_at') ?? date("Y-m-d H:i:s"));

        /* upload logo */
        if ($r->input('logo_remove')) {
            $app->logo = null;
        } else if ($r->hasFile('logo')) {
            $file = $r->file('logo');
            $ext = $file->getClientOriginalExtension();
            $name = 'logo.' . $ext;

            if ($this->uploadImage($file, '/assets/images/', $name)) {
                $app->logo = $name;
            }
        }

        /* upload icon */
        if ($r->input('icon_remove')) {
            $app->icon = null;
        } else {
            if ($r->input('use_logo')) {
                if ($app->logo) {
                    $app->icon = $app->logo;
                }
            } else if ($r->hasFile('icon')) {
                $file = $r->file('icon');
                $ext = $file->getClientOriginalExtension();
                $name = 'icon.' . $ext;

                if ($this->uploadImage($file, '/assets/images/', $name)) {
                    $app->icon = $name;
                }
            }
        }

        /* save config app */
        $app->save();

        /* processing socmed */
        $this->process_socmed($r);

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') memperbarui Pengaturan - Situs');

        /* clear cache */
        \Cache::flush();

        return redirect()->back()->with('success', 'Pengaturan Situs berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Application  $app
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $app)
    {
        abort(403);
    }

    public function process_socmed(Request $r)
    {
        $del = SocmedData::where([
            'socmedable_type' => 'App\Models\Site',
            'socmedable_id' => app('site')->id,
        ])->forceDelete();
        foreach ($r->input('socmed_id') as $key => $val) {
            if ($r->input('socmed_id')[$key] != "" && $r->input('socmed_uname')[$key] != "") {
                $socmed = new SocmedData;
                $socmed->username = $r->input('socmed_uname')[$key];
                $socmed->socmed_id = $r->input('socmed_id')[$key];
                $socmed->socmedable_type = 'App\Models\Site';
                $socmed->socmedable_id = app('site')->id;
                $socmed->save();
            }
        }

        return true;
    }
}
