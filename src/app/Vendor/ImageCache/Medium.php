<?php

namespace ZetthCore\Vendor\ImageCache;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Medium extends Base implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        /* set image size */
        $width = config('imagecache.default.medium.width');
        $height = config('imagecache.default.medium.height');
        $blur = config('imagecache.default.medium.blur');

        return $this->apply($image, $width, $height, $blur);
    }
}
