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
        
        return $thumb_url;
    }
    
    public static function writeFile($_path_to_file, $_file_content, $_write_mode = 'w') {
        $path_to_file = ROOT . $_path_to_file;
        if (!file_exists(dirname($path_to_file)))
            mkdir(dirname($path_to_file), 0777, true);
            
        if (null !== ($handle = fopen($path_to_file, $_write_mode)))
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
    
    public static function queryDatabase($_query) {
        static $DatabaseConnection;
        
        if (!$DatabaseConnection)
            $DatabaseConnection = new \PDO(
                'mysql:host=' . GlobalContext::Configuration()->Mysql->Hostname
                . ';dbname=' . GlobalContext::Configuration()->Mysql->Database
                , GlobalContext::Configuration()->Mysql->Username
                , GlobalContext::Configuration()->Mysql->Password
                , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        
        // return $DatabaseConnection->query($_query, \PDO::FETCH_ASSOC);
        
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
    
    public static function readXMLFromFile($_path) {
        return new Object((array)simplexml_load_file($_path));
    }
    
    public static function readXMLFromDirectory($_directory) {
        $Collection = new Collection();
        
        foreach (\eliza\feed\Feed::Node($_directory) as $Xml) {
            if ($Xml->IsDir) continue;
            if ($Xml->Extension != '.xml') continue;
        
            $Collection->append(self::readXMLFromFile($Xml->Path));
        }
        
        return $Collection; 
    }
}