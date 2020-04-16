<?php

namespace ZetthCore\Vendor\ImageCache;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Large extends Base implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        /* set image size */
        $width = config('imagecache.default.large.width');
        $height = config('imagecache.default.large.height');
        $blur = config('imagecache.default.large.blur');

        return $this->apply($image, $width, $height, $blur);
    }
}
