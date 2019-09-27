<?php

namespace ZetthCore\Http\Controllers\Data;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Role;
use ZetthCore\Models\Socmed;
use ZetthCore\Models\SocmedData;
use ZetthCore\Models\User;

class UserController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url(app('admin_path') . '/data/users');
        $this->page_title = 'Kelola Pengguna';
        $this->breadcrumbs[] = [
            'page' => 'Data',
            'icon' => '',
            'url' => url(app('admin_path') . '/data/users'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Pengguna',
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
            'page_subtitle' => 'Daftar Pengguna',
        ];

        return view('zetthcore::AdminSC.data.users', $data);
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

        /* where roles */
        if (\Auth::user()->hasRole('super')) {
            $whrRole = [
                ['status', 1],
            ];
        } else if (\Auth::user()->hasRole('admin')) {
            $whrRole = [
                ['status', 1],
                ['id', '!=', 1],
            ];
        } else {
            $whrRole = [
                ['status', 1],
                ['id', '!=', 1],
                ['id', '!=', 2],
            ];
        }

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Tambah Pengguna',
            'roles' => Role::where($whrRole)->get(),
        ];

        /* socmed */
        $data['socmeds'] = Socmed::where('status', 1)->get();
        /* $data['socmed_data'] = SocmedData::where([
        'type' => 'user',
        ])->with('socmed')->get(); */

        return view('zetthcore::AdminSC.data.users_form', $data);
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
            'name' => 'required|alpha_num|min:3|max:30|unique:users',
            'fullname' => 'required|min:1|max:100',
            'email' => 'required|email',
            'image' => 'mimes:jpg,jpeg,png,svg|max:512|dimensions:max_width=512,max_height=512',
            'password' => 'required|min:6',
            'password_confirmation' => 'same:password',
        ]);

        /* save data */
        $user = new User;
        $user->name = $r->input('name');
        $user->fullname = $r->input('fullname');
        $user->email = $r->input('email');
        $user->password = bcrypt($r->input('password'));
        $user->biography = $r->input('biography');
        $user->is_admin = bool($r->input('is_admin')) ? 1 : 0;
        $user->status = bool($r->input('status')) ? 1 : 0;

        /* upload image */
        if ($r->hasFile('image')) {
            $file = $r->file('image');
            $par = [
                'file' => $file,
                'folder' => '/assets/images/users/',
                'name' => str_slug($user->name),
                'type' => $file->getMimeType(),
                'ext' => $file->getClientOriginalExtension(),
            ];

            if ($this->uploadImage($par)) {
                $user->image = $par['name'] . '.' . $par['ext'];
                // $user->save();
            }
        }

        /* save user */
        $user->save();

        /* attach role */
        $this->assignRole($user, $r->input('role'));

        /* save socmed */
        $this->saveSocmed($user, $r);

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan pengguna "' . $user->name . '"');

        return redirect($this->current_url)->with('success', 'Pengguna "' . $user->name . '" berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        /* set breadcrumbs */
        $this->breadcrumbs[] = [
            'page' => 'Edit',
            'icon' => '',
            'url' => '',
        ];

        /* prevent access */
        if (!\Auth::user()->hasRole('super')) {
            if (in_array($user->id, [1])) {
                abort(404);
            }
        }

        /* where roles */
        if (\Auth::user()->hasRole('super')) {
            $whrRole = [
                ['status', 1],
            ];
        } else if (\Auth::user()->hasRole('admin')) {
            $whrRole = [
                ['status', 1],
                ['id', '!=', 1],
            ];
        } else {
            $whrRole = [
                ['status', 1],
                ['id', '!=', 1],
                ['id', '!=', 2],
            ];
        }

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Edit Pengguna',
            'roles' => Role::where($whrRole)->get(),
            'data' => $user,
        ];

        /* socmed */
        $data['socmeds'] = Socmed::where('status', 1)->get();
        $data['socmed_data'] = SocmedData::where([
            'type' => 'user',
            'data_id' => $user->id,
        ])->with('socmed')->get();

        return view('zetthcore::AdminSC.data.users_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, User $user)
    {
        /* validation */
        $validation = [
            'name' => 'required|alpha_num|min:3|max:30|unique:users,name,' . $user->id . ',id',
            'fullname' => 'required|max:100',
            'email' => 'required|email',
            'image' => 'mimes:jpg,jpeg,png,svg|max:512|dimensions:max_width=512,max_height=512',
        ];
        if ($r->input('password') !== null || $r->input('password_confirmation') !== null) {
            $validation['password'] = 'required|min:6';
            $validation['password_confirmation'] = 'same:password';
        }
        $this->validate($r, $validation);

        /* save data */
        $user->name = $r->input('name');
        $user->fullname = $r->input('fullname');
        $user->email = $r->input('email');
        if ($r->input('password') !== null) {
            $user->password = bcrypt($r->input('password'));
        }
        $user->biography = $r->input('biography');
        $user->is_admin = bool($r->input('is_admin')) ? 1 : 0;
        $user->status = bool($r->input('status')) ? 1 : 0;

        /* upload image */
        if ($r->hasFile('image')) {
            $file = $r->file('image');
            $par = [
                'file' => $file,
                'folder' => '/assets/images/users/',
                'name' => str_slug($user->name),
                'type' => $file->getMimeType(),
                'ext' => $file->getClientOriginalExtension(),
            ];

            if ($this->uploadImage($par)) {
                $user->image = $par['name'] . '.' . $par['ext'];
                // $user->save();
            }
        }

        /* save user */
        $user->save();

        /* attach role */
        $this->assignRole($user, $r->input('role'));

        /* save socmed */
        $this->saveSocmed($user, $r);

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui pengguna "' . $user->name . '"');

        return redirect($this->current_url)->with('success', 'Pengguna "' . $user->name . '" berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r, User $user)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus pengguna "' . $user->name . '"');

        /* soft delete */
        $user->delete();

        return redirect($this->current_url)->with('success', 'Pengguna "' . $user->name . '" berhasil dihapus!');
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
                ['id', '!=', 2],
            ];
        }

        /* get data */
        $data = User::select('id', 'name', 'fullname', /* 'image', 'email', */'status')->where($whrRole)->orderBy('fullname');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }

    /**
     * Assign user to a role
     */
    public function assignRole($user, $newRole)
    {
        /* hapus role sebelumnya */
        foreach ($user->roles as $role) {
            $user->detachRole($role->id);
        }

        /* tambah role baru */
        $user->attachRole($newRole);
    }
}
