<?php

namespace eliza;

class Image extends File implements CollectionHTML_I {
    public $Width = 0;
    public $Height = 0;
    public $Thumb = null;
    
    public function Thumb($_size = '230') {
    
    }
    
    public static function Feed($_gallery = '.', $thumb_size = 0) {
        $Files = parent::Feed($_gallery);
        $Gallery = new CollectionFeed();
        
        foreach ($Files as $File)   {
            if (list($image_width, $image_height) = getimagesize($File->Path)) {
                $Image = new static();
                $Image->mergeWith($File);
                $Image->Width = $image_width;
                $Image->Height = $image_height;
                
                if ($thumb_size > 0) $Image->Thumb = self::createImageThumbnail($File->Url, $thumb_size);
                else $Image->Thumb = new static((array)$Image);
                
                $Gallery->append($Image);
            }
        }
        
        return $Gallery;
    }
        
    // this must go here!!!
    public function toHTML() {
        return <<<EOT
\n<a href="{$this->Url}" target="_blank">
    <div id="{$this->Id}" class="thumb" style="width:{$this->Thumb->Width}px; height:{$this->Thumb->Height}px;">
        <img src="{$this->Thumb->Url}" alt="{$this->Name}"/>
    </div>
</a>\n
EOT;
    }
    
    // should this go into Image?
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
        
        $Thumb = static::describeNode($path_to_thumb);
        $Thumb->Width = $thumb_width;
        $Thumb->Height = $thumb_height;
        
        return $Thumb;
    }
}