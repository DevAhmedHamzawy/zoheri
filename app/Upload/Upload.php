<?php

namespace App\Upload;

use Illuminate\Support\Str;

class Upload
{
    public static function uploadImage($image, $sectionName, $name){
        
        $fileName = Str::slug($name) . '.' . $image->getClientOriginalExtension();
        $image->storePubliclyAs("public/main/${sectionName}/", $fileName);
    
        return $fileName;
    }
}