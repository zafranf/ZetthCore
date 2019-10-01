<?php

namespace ZetthCore\Http\Controllers;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Socmed;
use ZetthCore\Models\SocmedData;

class AccountController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url(app('admin_path') . '/account');
        $this->page_title = 'Pengaturan Akun';
        $this->breadcrumbs[] = [
            'page' => 'Pengaturan',
            'icon' => '',
            'url' => url(app('admin_path') . '/setting/site'),
        ];
    }

    public function index()
    {
        /* set breadcrumbs */
        $this->breadcrumbs[] = [
            'page' => 'Akun',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Akun',
            'data' => \Auth::user(),
        ];

        /* socmed */
        $data['socmeds'] = Socmed::where('status', 1)->get();
        $data['socmed_data'] = SocmedData::where([
            'type' => 'user',
            'data_id' => \Auth::user()->id,
        ])->with('socmed')->get();

        return view('zetthcore::AdminSC.user_account_form', $data);
    }

    public function update(Request $r)
    {
        /* validation */
        $validation = [
            'fullname' => 'required|max:100',
            'email' => 'required|email',
            'image' => 'mimes:jpg,jpeg,png,svg|max:512|dimensions:max_width=512,max_height=512',
        ];
        if ($r->input('password_old') !== null || $r->input('password') !== null || $r->input('password_confirmation') !== null) {
            $validation['password_old'] = 'required';
            $validation['password'] = 'required|min:6';
            $validation['password_confirmation'] = 'same:password';
        }
        $this->validate($r, $validation);

        /* check old password */
        if ($r->input('password_old') !== null) {
            if (!password_verify($r->input('password_old'), \Auth::user()->password)) {
                return redirect($this->current_url)->withErrors([
                    'The password old not match',
                ]);
            }
        }

        /* save data */
        $user = \Auth::user();
        // $user->name = $r->input('name');
        $user->fullname = $r->input('fullname');
        $user->email = $r->input('email');
        if ($r->input('password') !== null) {
            $user->password = bcrypt($r->input('password'));
        }
        $user->biography = $r->input('biography');

        /* upload image */
        if ($r->hasFile('image')) {
            $file = $r->file('image');
            $par = [
                'file' => $file,
                'folder' => '/assets/images/users/',
                'name' => str_slug(\Auth::user()->name),
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

        /* save socmed */
        $this->saveSocmed($user, $r);

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui akun');

        return redirect($this->current_url)->with('success', 'Data akun berhasil disimpan!');
    }
}