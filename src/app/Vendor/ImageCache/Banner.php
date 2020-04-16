<?php

namespace ZetthCore\Vendor\ImageCache;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Banner implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $width = config('site.banner.image.dimension.width') ?? 1280;
        $height = config('site.banner.image.dimension.height') ?? 768;

        return $image->resize($width, $height);
    }
}
