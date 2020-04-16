<?php

namespace ZetthCore\Vendor\ImageCache;

use Intervention\Image\Image;

class Base
{
    public function apply(Image $image, $width, $height, $blur)
    {
        /* count ratio */
        $ratio = $width / $height;

        /* main image */
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $width_main = $image->width();
        $height_main = $image->height();
        $ratio_real = $width_main / $height_main;

        /* check ratio */
        if ($ratio != $ratio_real) {
            /* create background canvas */
            $canvas = \Image::canvas($width, $height, '#fff');

            /* set background size */
            $bgw = $ratio_real < $ratio ? $width : ceil($height * $ratio_real);
            $bgh = $ratio_real < $ratio ? ceil($width * $height_main / $width_main) : $height;

            /* clone as background */
            $bgimage = $image->backup();
            $bgimage->resize($bgw, $bgh);
            $bgimage->blur($blur);
            $bgimage->opacity(50);

            /* merge all image to canvas */
            $canvas->insert($bgimage, 'center');
            $canvas->insert($image->reset(), 'center');
            return $canvas;

            return $canvas->encode('jpg', 70);
        } else {
            /* return real resized real image */
            return $image->resize($width, $height);
        }

        return null;
    }
}
