<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Storage, File, Exception;

class LoadAvatarHelper {

    public static function storageImg(Request $request, $file_name , $field= 'avatar') {

        // if is file
        if($request->hasFile($field)){

            $img = $request->file($field);
            $ext = $img->getClientOriginalExtension();
            $name_file = "{$file_name}.{$ext}";
            $img_file = File::get($img );

            Self::validFormat($ext);
            Storage::disk('avatar')->put($name_file, $img_file);

        }else if(Self::validBase64($request->input($field))){ // if is base64

            $ext = Self::getB64Extension($request->input($field));
            $name_file = "{$file_name}.{$ext}";
            $img_file = Self::getB64Image($request->input($field));

            Self::validFormat($ext);
            Storage::disk('avatar')->put($name_file, $img_file);

        }else if(Self::validUrl($request->input($field))){ // if is url

            $name_file = $request->input($field);
        }

        return $name_file ?? FALSE;
    }

    public static function validFormat($ext){
        $formats = [ 'JPG' , 'JPEG' , 'PNG'];
        if(!in_array(  strtoupper( $ext) , $formats) )
            throw new Exception( 'La imagen debe ser formato jpg o png',  400);
    }

    public static function validBase64($img){
        return (strpos($img, 'data:image') !== FALSE);
    }

    public static function validUrl($img){
        return ((strpos($img, 'http://') !== FALSE) || (strpos($img, 'https://') !== FALSE));
    }

    public static function getB64Image($base64_image){
        $image_service_str = substr($base64_image, strpos($base64_image, ",")+1);
        $image = base64_decode($image_service_str);
        return $image;
   }

   public static function getB64Extension($base64_image, $full=null){
        
        $img_extension = explode(';base64' , $base64_image)[0];
        $img_extension = explode('/',$img_extension)[1] ?? NULL;
        return $img_extension;
   }
}
