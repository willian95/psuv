<?php

namespace App\Helpers;
use Storage, File, Exception;

class LoadFileHelper {

    public static function storageFile($file, $title = 'file') {

        $ext = $file->getClientOriginalExtension();
        $name_file = "{$title}.{$ext}";
        Storage::disk('user-files')->put($name_file, File::get($file));
        return $name_file;
    }

    public static function storageImg($file, $title = 'img') {

        $ext = $file->getClientOriginalExtension();
        $name_file = "{$title}.{$ext}";
        Storage::disk('public')->put($name_file, File::get($file));
        return $name_file;
    }

    public static function setTitle($id){
        $data = [
            'purchase_policy_file' => "politicas-de-compra",
            'academic_regulation_file' => "reglamento-academico" ,
            'public_log_file' => "registro-publico",
            'bank_statement_file' => 'estado-de-cuenta-bancaria',
            'utility_receipt_file' => "recibo" ,
            'contract_file' => "contrato-firmado",
            'instruction_letter_file' => "carta-instrucciones-bancarias",
        ];
        return $data[$id];
    }

}
