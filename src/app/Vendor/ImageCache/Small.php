<?php

namespace ZetthCore\Vendor\ImageCache;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Small extends Base implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        /* set image size */
        $width = config('imagecache.default.small.width');
        $height = config('imagecache.default.small.height');
        $blur = config('imagecache.default.small.blur');

        return $this->apply($image, $width, $height, $blur);
    }
}
