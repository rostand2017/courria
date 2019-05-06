<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 5/5/2019
 * Time: 4:24 PM
 */

namespace AdminBundle\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(){
        return array(
          new TwigFilter('cast_to_array', array($this, 'castToArray')),
        );
    }

    public function castToArray($stdClassObject){
        $array = [];
        foreach ($stdClassObject as $item){
            array_push($array, $item);
        }
        return $array;
    }
}