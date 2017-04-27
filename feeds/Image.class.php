<?php

namespace eliza;

class Image extends Node implements CollectionHTML_I {
    public $Width = 0;
    public $Height = 0;
    public $Thumb = null;
    
    public static function Feed($_gallery = '.', $thumb_size = 230) {
        $Gallery = parent::Feed($_gallery);
        
        for ($i = 0; $i < count($Gallery); $i++) {
            if ($Gallery[$i]->IsDir) continue;
            list($width, $height) = getimagesize($Gallery[$i]->Path);
            $Gallery[$i]->Width = $width;
            $Gallery[$i]->Height = $height;
            $Gallery[$i]->Thumb = ($thumb_size > 0) 
                          ? Utils::createImageThumbnail($Gallery[$i]->Url, $thumb_size) 
                          : new static();
                          
        }
        
        return $Gallery;
    }
    
    // this must go here!!!
    public function toHTML() {oops();
        return "\t" . '<div class="thumb" style="display:inline-block;'
             . 'width:' . $this->Thumb->Width
             . '; height:' . $this->Thumb->Height
             . '; vertical-align: middle;"><a href="' . $this->Url 
             . '" target="_blank" class="preview"><img src="' . $this->Thumb->Url
             . '" alt="' . $this->Name . '"/></a></div>' . "\n";
    }
}