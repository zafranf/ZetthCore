<?php

namespace App\Http\Middleware;

use RenatoMarinho\LaravelPageSpeed\Middleware\PageSpeed;

class TrimDomains extends PageSpeed
{
    public function apply($buffer)
    {
        $replace = [
            '/\/\/' . config('app.domain') . '\//' => '/',
            '/\/\/' . config('app.domain') . '/' => '/',
        ];

        return $this->replace($replace, $buffer);
    }
}
