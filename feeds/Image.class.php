<?php

namespace eliza;

class Image extends File implements CollectionHTML_I {
    public $Width = 0;
    public $Height = 0;
    public $Thumb = null;
    
    public static function Feed($_gallery = '.', $thumb_size = 0) {
        $Files = parent::Feed($_gallery);
        $Gallery = new CollectionFeed();
        
        foreach ($Files as $File)   {
            if (list($image_width, $image_height) = getimagesize($File->Path)) {
                $Image = new static();
                $Image->mergeWith($File);
                $Image->Width = $image_width;
                $Image->Height = $image_height;
                $Image->Thumb = ($thumb_size > 0) ?  Utils::createImageThumbnail($File->Url, $thumb_size) : new static();
                $Gallery->append($Image);
            }
        }
        
        return $Gallery;
    }
        
    // this must go here!!!
    public function toHTML() {
        return "\t" . '<div class="thumb" style="display:inline-block;'
             . 'width:' . $this->Thumb->Width
             . '; height:' . $this->Thumb->Height
             . '; vertical-align: middle;"><a href="' . $this->Url 
             . '" target="_blank" class="preview"><img src="' . $this->Thumb->Url
             . '" alt="' . $this->Name . '"/></a></div>' . "\n";
    }
}