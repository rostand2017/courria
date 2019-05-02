<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 10/9/2018
 * Time: 11:15 PM
 */

namespace AdminBundle\Classes;


class Thumbnails
{
    public function __construct()
    {
        //
    }

    public function createThumbnail($src, $destination, $targetWith){
        $type = pathinfo($src)['extension'];
        $dimension = getimagesize($src);
        $largeWidth = $dimension[0]; // obtenir la largeur de la tof
        $largeHeight = $dimension[1]; // la hauteur
        $ratio = $largeWidth / $largeHeight;
        $targetHeight = $targetWith / $ratio;

        if( $src != '' && isset($src) ){
            $pic = ($type == 'jpg' || $type == 'jpeg') ? imagecreatefromjpeg($src) : imagecreatefrompng($src) ;
            $small = imagecreatetruecolor($targetWith, $targetHeight);
            imagecopyresampled($small, $pic, 0, 0, 0, 0, $targetWith, $targetHeight, $largeWidth, $largeHeight);

            return ($type == 'jpg' || $type == 'jpeg') ? imagejpeg($small, $destination) : imagepng($small, $destination);
        }
    }

}