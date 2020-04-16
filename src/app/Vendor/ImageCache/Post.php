<?php

namespace ZetthCore\Vendor\ImageCache;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Post implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = config('site.post.image.dimension.width') ?? 1280;
        $height = config('site.post.image.dimension.height') ?? 768;

        return $image->resize($width, $height);
    }
}
