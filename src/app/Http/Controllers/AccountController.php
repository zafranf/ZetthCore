<?php

namespace ZetthCore\Http\Controllers;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Socmed;

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
            'data' => app('user')->load(['socmed', 'detail']),
            'socmeds' => Socmed::where('status', 'active')->get(),
        ];

        return view('zetthcore::AdminSC.user_account_form', $data);
    }

    public function update(Request $r)
    {
        /* validation */
        $validation = [
            'fullname' => 'required|max:100',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpeg,png,svg|max:384|dimensions:max_width=512,max_height=512',
        ];
        if (!is_null($r->input('password_old')) || !is_null($r->input('password')) || !is_null($r->input('password_confirmation'))) {
            $validation['password_old'] = 'required';
            $validation['password'] = 'required|min:6';
            $validation['password_confirmation'] = 'same:password';
        }
        $this->validate($r, $validation);

        /* check old password */
        if (!is_null($r->input('password_old'))) {
            if (!password_verify($r->input('password_old'), app('user')->password)) {
                return redirect($this->current_url)->withErrors([
                    'The password old not match',
                ]);
            }
        }

        /* save data */
        $user = app('user');
        $user->fullname = $r->input('fullname');
        $user->email = $r->input('email');
        if (!is_null($r->input('password'))) {
            $user->password = $r->input('password');
        }

        /* upload image */
        if ($r->input('image_remove')) {
            $user->image = null;
        } else if ($r->hasFile('image')) {
            $file = $r->file('image');
            $ext = $file->getClientOriginalExtension();
            $name = \Str::slug(app('user')->name) . '.' . $ext;

            if ($this->uploadImage($file, '/assets/images/users/', $name)) {
                $user->image = $name;
            }
        }

        /* save user */
        $user->save();

        /* save user detail */
        $this->saveDetail($user, $r);

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') memperbarui akun');

        return redirect($this->current_url)->with('success', 'Data akun berhasil disimpan!');
    }
}
