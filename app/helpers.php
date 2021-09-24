<?php

  function helperBase64ToFile($base64,$path)
  {
    try {
      $data = explode(',', $base64)[1];
      $extension = explode('/', explode(';', explode(',', $base64)[0])[0])[1];
      $fileName = md5(date("Y-m-d H:i:s")) . ".$extension";
      $uploadPath = $path . "/" .$fileName;
      $data = base64_decode($data);
      // if (file_put_contents($uploadPath, $data)) {
      $disk="publicmedia";
      if(\Storage::disk($disk)->put($uploadPath, $data)){
        return $fileName;
      } else {
        return false;
      }
    } catch (\Exception $e) {
        return false;
    }
  }

