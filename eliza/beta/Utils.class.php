<?php

namespace eliza\beta;

class Utils {
    public static function createImageThumbnail($_url_to_image, $_thumb_size) {
        $thumb_name =  md5($_url_to_image) . '_' . $_thumb_size . '.jpg';
        $thumb_url = 'http://' . $_SERVER['HTTP_HOST'] . '/temp/thumb/' . $thumb_name;
        $path_to_thumb = ROOT . 'temp' . DS . 'thumb' . DS . $thumb_name;
        
        if (!file_exists(ROOT . 'temp' . DS . 'thumb')) 
            mkdir(ROOT . 'temp' . DS . 'thumb', 0777, true);

        
        if (file_exists($path_to_thumb))
            return $thumb_url;

        if (!ini_get('allow_url_fopen'))
            ini_set('allow_url_fopen', true);

        // load image and get image size
        switch (pathinfo($_url_to_image, PATHINFO_EXTENSION)){
            case 'jpeg':
            case 'jpg':
                $image  = imagecreatefromjpeg($_url_to_image);
                break;
            case 'png':
                $image  = imagecreatefrompng($_url_to_image);
                break;
            case 'gif':
                $image = imagecreatefromgif($_url_to_image);
        }
        
        $image_width = imagesx($image);
        $image_height = imagesy($image);

        // calculate thumb size and save thumb
        $ratio = $_thumb_size / ($image_width >= $image_height ? $image_width : $image_height);
        $thumb_width = (int) ($image_width * $ratio);
        $thumb_height = (int) ($image_height * $ratio);
        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

        // generate thumb
        imagecopyresized($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);

        // save thumb
        imagejpeg($thumb, $path_to_thumb);
        imagedestroy($thumb);
        
        return $thumb_url;
    }
    
    public static function writeFile($_path_to_file, $_file_content) {
        $path_to_file = ROOT . $_path_to_file;
        if (!file_exists(dirname($path_to_file)))
            mkdir(dirname($path_to_file), 0777, true);
            
        if (null !== ($handle = fopen($path_to_file, 'w')))
            if ((bool)fwrite($handle, $_file_content))
                return true;
                
        return false;
    }
    
    public static function deleteFile($_path_to_file) {
        return unlink(ROOT . $_path_to_file);
    }
    
    public static function uniqueId() {
        return time() . substr(microtime(),2,3);
    }
}