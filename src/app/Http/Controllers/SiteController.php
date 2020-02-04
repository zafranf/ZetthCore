<?php

namespace ZetthCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class SiteController extends Controller
{
    use \ZetthCore\Traits\MainTrait;

    public $breacrumbs;

    public function __construct()
    {
        $this->breadcrumbs[] = [
            'page' => 'Beranda',
            'icon' => '',
            'url' => url('/'),
        ];
    }

    public function themes(\Illuminate\Http\Request $r, $path)
    {
        $path = str_start(str_replace(['../', './'], '', urldecode($path)), '/');
        $path = resource_path('themes' . $path);
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
