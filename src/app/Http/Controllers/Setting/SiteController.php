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
        $this->current_url = url(app('admin_path') . '/setting/site');
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
        ];

        $data['socmeds'] = Socmed::where('status', 1)->get();
        $data['socmed_data'] = SocmedData::where([
            'type' => 'site',
        ])->with('socmed')->get();

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
            'logo' => 'mimes:jpg,jpeg,png,svg|max:384|dimensions:max_width=512,max_height=512',
            'icon' => 'mimes:jpg,jpeg,png,svg,ico|max:96|dimensions:max_width=128,max_height=128',
            'name' => 'required|max:50',
            'tagline' => 'nullable|max:100',
            'email' => 'nullable|max:100|email',
            'phone' => 'nullable|numeric|max:999999999999999',
            'perpage' => 'required|integer|min:3|max:100',

            /* socmed */
            'socmed_uname.*' => 'max:50',

            /* seo */
            'keywords' => 'nullable|max:191',
            'description' => 'nullable|max:191',
            'google_analytics' => 'nullable|20',

            /* location */
            'address' => 'nullable|max:280',
            'coordinate' => 'nullable|max:30',
        ]);

        /* save data */
        $app = Site::find($id);
        $app->name = $r->input('name');
        $app->description = $r->input('description');
        $app->keywords = $r->input('keywords');
        $app->tagline = $r->input('tagline');
        $app->status = $r->input('status');
        $app->email = $r->input('email');
        $app->address = $r->input('address');
        $app->phone = $r->input('phone');
        $app->coordinate = str_replace(" ", "", $r->input('coordinate'));
        $app->perpage = $r->input('perpage');
        $app->enable_subscribe = bool($r->input('enable_subscribe')) ? 1 : 0;
        $app->enable_like = bool($r->input('enable_like')) ? 1 : 0;
        $app->enable_share = bool($r->input('enable_share')) ? 1 : 0;
        $app->enable_comment = bool($r->input('enable_comment')) ? 1 : 0;
        $app->google_analytics = $r->input('google_analytics');
        $app->active_at = $r->input('active_at') ?? date("Y-m-d H:i:s");

        /* upload logo */
        if ($r->input('logo_remove')) {
            $app->logo = '';
        } else if ($r->hasFile('logo')) {
            $file = $r->file('logo');
            $par = [
                'file' => $file,
                'folder' => '/assets/images/',
                'name' => 'logo',
                'type' => $file->getMimeType(),
                'ext' => $file->getClientOriginalExtension(),
            ];

            if ($this->uploadImage($par, true)) {
                $app->logo = $par['name'] . '.' . $par['ext'];
            }
        }

        /* upload icon */
        if ($r->input('icon_remove')) {
            $app->icon = '';
        } else {
            if ($r->input('use_logo')) {
                if ($app->logo) {
                    $app->icon = $app->logo;
                }
            } else if ($r->hasFile('icon')) {
                $file = $r->file('icon');
                $par = [
                    'file' => $file,
                    'folder' => '/assets/images/',
                    'name' => 'icon',
                    'type' => $file->getMimeType(),
                    'ext' => $file->getClientOriginalExtension(),
                ];

                if ($this->uploadImage($par)) {
                    $app->icon = $par['name'] . '.' . $par['ext'];
                }
            }
        }

        /* save config app */
        $app->save();

        /* processing socmed */
        $this->process_socmed($r);

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Pengaturan - Situs');

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
            'type' => 'site',
        ])->forceDelete();
        foreach ($r->input('socmed_id') as $key => $val) {
            if ($r->input('socmed_id')[$key] != "" && $r->input('socmed_uname')[$key] != "") {
                $socmed = new SocmedData;
                $socmed->username = $r->input('socmed_uname')[$key];
                $socmed->type = 'site';
                $socmed->socmed_id = $r->input('socmed_id')[$key];
                $socmed->data_id = 1;
                $socmed->save();
            }
        }

        return true;
    }
}
