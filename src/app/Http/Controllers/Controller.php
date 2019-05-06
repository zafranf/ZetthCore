<?php

namespace ZetthCore\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use \ZetthCore\Traits\MainTrait;

    public $isAdminSubdomain = false;
    public $adminPath = '/admin';

    public function __construct()
    {
        $host = parse_url(url('/'))['host'];
        if (strpos($host, 'admin') !== false) {
            $this->isAdminSubdomain = true;
            $this->adminPath = '';
        }
    }
}
