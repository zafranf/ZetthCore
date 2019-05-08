<?php

namespace ZetthCore\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;

class AdminController extends BaseController
{
    use \ZetthCore\Traits\MainTrait;

    public $isAdminSubdomain = false;
    public $adminPath = '/admin';
    public $breadcrumbs;

    public function __construct()
    {
        $host = parse_url(url('/'))['host'];
        if (strpos($host, 'admin') !== false) {
            $this->isAdminSubdomain = true;
            $this->adminPath = '';
        }
        $this->breadcrumbs[] = [
            'page' => '',
            'icon' => 'fa fa-home',
            'url' => url($this->adminPath),
        ];
    }

    public function themes(\Illuminate\Http\Request $r, $path)
    {
        $path = str_start(str_replace(['../', './'], '', urldecode($path)), '/');
        $path = base_path('vendor/zafranf/zetthcore/resources/themes' . $path);
        if (File::exists($path)) {
            $mime = '';
            if (ends_with($path, '.js')) {
                $mime = 'text/javascript';
            } elseif (ends_with($path, '.css')) {
                $mime = 'text/css';
            } else {
                $mime = File::mimeType($path);
            }
            $response = response(File::get($path), 200, ['Content-Type' => $mime]);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));

            return $response;
        }

        abort(404);
    }
}
