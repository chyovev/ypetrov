<?php

namespace App\Helpers;

use Nette\Utils\Image;

class ThumbGenerator
{

    ///////////////////////////////////////////////////////////////////////////
    public static function generate(string $sourcePath, string $targetPath): void {
        $maxWidth  = 60;
        $maxHeight = 60;
        
        (Image::fromFile($sourcePath))
            ->resize($maxWidth, $maxHeight, Image::OrBigger)
            ->crop(0, 0, $maxWidth, $maxHeight)
            ->sharpen()
            ->save($targetPath);
    }

}