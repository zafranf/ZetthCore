<?php

namespace App\Http\Middleware;

use RenatoMarinho\LaravelPageSpeed\Middleware\PageSpeed;

class TrimDomains extends PageSpeed
{
    public function apply($buffer)
    {
        $replace = [
            '/\/\/' . env('APP_DOMAIN') . '\//' => '/',
            '/\/\/' . env('APP_DOMAIN') . '/' => '/',
        ];

        return $this->replace($replace, $buffer);
    }
}
