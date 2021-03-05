<?php

namespace App\Helpers;


class Util{

    public static function formatMoneyFromString($moneyString)
    {
        $filter1 = str_replace(",",".",str_replace(".",'',$moneyString));
        $filter2 = explode(' ',$filter1)[1];
        return floatval($filter2);
    }

    public static function combineArrays($array)
    {
        foreach($array as $key => $value){
            $newArray[] = ['name'=>$key, 'price'=>$value];
        }
        return $newArray;
    }
}