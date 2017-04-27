<?php

namespace eliza;

class Utils {
    // should this go into Image?
    public static function createImageThumbnail($_url_to_image, $_thumb_size) {
        $thumb_name =  md5($_url_to_image) . '_' . $_thumb_size . '.jpg';
        $thumb_url = 'http://' . $_SERVER['HTTP_HOST'] . '/temp/thumb/' . $thumb_name;
        $path_to_thumb = TEMP . 'thumb' . DS . $thumb_name;
        
        if (!file_exists(TEMP . 'thumb')) 
            mkdir(TEMP . 'thumb', 0777, true);

        
        if (!file_exists($path_to_thumb)) {

            if (!ini_get('allow_url_fopen'))
                ini_set('allow_url_fopen', true);

            // load image and get image size
            switch (pathinfo($_url_to_image, PATHINFO_EXTENSION)){
                case 'jpeg':
                case 'jpg':
                case 'JPEG':
                case 'JPG':
                    $image  = imagecreatefromjpeg($_url_to_image);
                    break;
                case 'png':
                case 'PNG':
                    $image  = imagecreatefrompng($_url_to_image);
                    break;
                case 'gif':
                case 'GIF':
                    $image = imagecreatefromgif($_url_to_image);
                
                default: 
                    return null; 
                    // oops($_url_to_image . ' is not a recognized image');
            }
            
            $image_width = imagesx($image);
            $image_height = imagesy($image);

            // calculate thumb size and save thumb
            $ratio = $_thumb_size / ($image_width >= $image_height ? $image_width : $image_height);
            $thumb_width = (int) ($image_width * $ratio);
            $thumb_height = (int) ($image_height * $ratio);
            $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

            // generate thumb
            imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);

            // save thumb
            imagejpeg($thumb, $path_to_thumb);
            imagedestroy($thumb);
        } else {
            list($thumb_width, $thumb_height) = getimagesize($path_to_thumb);
        }
        
        $Thumb = \Image::describeNode($path_to_thumb);
        $Thumb->Width = $thumb_width;
        $Thumb->Height = $thumb_height;
        
        return $Thumb;
    }
    
    public static function queryDatabase($_query, $connection = 'Mysql') {
        static $DatabaseConnection;
        
        if (!$DatabaseConnection)
            $DatabaseConnection = new \PDO(
                'mysql:host=' . GlobalContext::Configuration()->$connection->Hostname
                . ';dbname=' . GlobalContext::Configuration()->$connection->Database
                , GlobalContext::Configuration()->$connection->Username
                , GlobalContext::Configuration()->$connection->Password
                , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        
        
        // DEBUG INFO: returns query result before manipolation
        if (DEBUG) return $DatabaseConnection->query($_query, \PDO::FETCH_ASSOC);
        // DEBUG END
        
        try {
            $Collection = new Collection();
        
            foreach ($DatabaseConnection->query($_query, \PDO::FETCH_ASSOC) as $row) {
                $Collection->append(new Object($row));
            }
            
            return $Collection;
        } catch (\PDOException $E) {
            if ($E->getCode() == "HY000") // general error produced by cycling a non-dataset query
                return true;
            else throw $E;        
        }
        
    }
}