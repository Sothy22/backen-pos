<?php

namespace App\Trait;

trait HashBarcodeTrait
{
    public static function  bootHashBarcodeTrait ()
    {
        static::creating(function ($model) {
            if(empty($model->barcode)){
                $model->barcode = self::generateBarcode();
            }
        });
    }

    private static function generateBarcode()
    {
        return "Pro" . str_pad(mt_rand(100000000000, 999999999999), 13, "0", STR_PAD_LEFT);
    }
}
