<?php

namespace eliza;

class Image extends File implements CollectionHTML_I {
    public $Width = 0;
    public $Height = 0;
    
    public static function describeFile($_path_to_file) {
        if (list($image_width, $image_height) = getimagesize($_path_to_file)) {
            $Image = parent::describeFile($_path_to_file);
            $Image->Width = $image_width;
            $Image->Height = $image_height;
            return $Image;
        } else oops($_path_to_file . ' is not an image');
    }
    
    public function Thumb($_size = '230') {
        if (preg_match('/ico/i', $this->Extension)) return $this;
        return self::createImageThumbnail($this->Url, $_size);
    }
        
    public function toHTML() {
        return <<<EOT
\n<div id="{$this->Id}" class="thumb file">
    <a href="{$this->Url}" target="_blank">
        <img src="{$this->Thumb()->Url}" alt="{$this->Name}"/>
        <span class="filename">{$this->Filename}</span>
    </a>
</div>\n
EOT;
    }
    
    final public static function createImageThumbnail($_url_to_image, $_thumb_size) {
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
                    break;
                
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
        
        $Thumb = static::describeFile(TEMP . 'thumb' . DS . $thumb_name);
        $Thumb->Width = $thumb_width;
        $Thumb->Height = $thumb_height;
        
        return $Thumb;
    }
}