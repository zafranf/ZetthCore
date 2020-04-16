<?php

namespace ZetthCore\Vendor\ImageCache;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Thumbnail extends Base implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        /* set image size */
        $width = config('imagecache.default.thumbnail.width');
        $height = config('imagecache.default.thumbnail.height');
        $blur = config('imagecache.default.thumbnail.blur');

        return $this->apply($image, $width, $height, $blur);
    }
}
