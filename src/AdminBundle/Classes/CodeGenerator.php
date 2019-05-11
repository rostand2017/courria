<?php
/**
 * Created by PhpStorm.
 * User: Ross
 * Date: 5/11/2019
 * Time: 4:58 AM
 */

namespace AdminBundle\Classes;

class CodeGenerator
{
    private static $stringMin = "abcdefghijklmnopqrstuvwxyz";
    private static $stringMaj = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private static $number = "0123456789";

    public static function fourCharStringCode(){
        $shuff = str_shuffle(CodeGenerator::$stringMaj);
        return substr($shuff, 0, 4);
    }

    public static function fourCharNumberCode(){
        $shuff = str_shuffle(CodeGenerator::$number);
        return substr($shuff, 0, 4);
    }

    public static function generateToken($lenght){
        $concat = CodeGenerator::$stringMin.CodeGenerator::$stringMaj.CodeGenerator::$number;
        $shuff = str_shuffle($concat);
        return ($lenght <= 62)?substr($shuff, 0, $lenght):substr($shuff, 0, 62);
    }

    public static function generateUniqToken($id){
        $shuff = md5(uniqid());
        $id = (string) $id;
        if(strlen($id) == 1)
            return str_shuffle(substr($shuff, 0, 7).$id);
        else if(strlen($id) == 2)
            return str_shuffle(substr($shuff, 0, 6).$id);
        else if(strlen($id) == 3)
            return str_shuffle(substr($shuff, 0, 5).$id);
        else if(strlen($id) == 4)
            return str_shuffle(substr($shuff, 0, 4).$id);
        else if(strlen($id) == 5)
            return str_shuffle(substr($shuff, 0, 3).$id);
        else if(strlen($id) == 6)
            return str_shuffle(substr($shuff, 0, 2).$id);
        else
            return str_shuffle(substr($shuff, 0, 10).$id);
    }
}