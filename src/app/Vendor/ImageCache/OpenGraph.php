<?php

namespace ZetthCore\Vendor\ImageCache;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class OpenGraph extends Base implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        /* set image size */
        $width = config('imagecache.default.opengraph.width');
        $height = config('imagecache.default.opengraph.height');
        $blur = config('imagecache.default.opengraph.blur');

        return $this->apply($image, $width, $height, $blur);
    }
}
