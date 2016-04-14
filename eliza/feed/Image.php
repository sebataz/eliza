<?php

class Image extends eliza\feed\Feed implements eliza\feed\HTMLFeedI {
    public $Name = '';
    public $Thumb = '';
    public $ThumbSize = 0; 
    public $File;
    
    public static function Feed($_gallery, $thumb_size = 0) {
        $Gallery = new eliza\feed\HTMLFeed();
        
        $GalleryFolder = eliza\feed\Feed::Node($_gallery);
        
        foreach ($GalleryFolder as $Img) {
            $Image = new self();
            $Image->Name = $Img->Name;
            $Image->File = $Img;
            $Image->ThumbSize = $thumb_size;
            $Image->Thumb = ($thumb_size > 0) 
                          ? eliza\beta\Utils::createImageThumbnail($Img->Url, $thumb_size) : $Img->Url;
                          
            $Gallery->append($Image);
        }
        
        return $Gallery;
    }
    
    public function toHTML() {
        return "\t" . '<div class="thumb" style="display:inline-block; width:'
             . (($this->ThumbSize  > 0) ? $this->ThumbSize  : 'auto')
             . ' ;height:'.(($this->ThumbSize  > 0) ? $this->ThumbSize  : 'auto')
             . ' ;vertical-align: middle;"><a href="' . $this->File->Url 
             . '" target="_blank" class="preview"><img src="' . $this->Thumb 
             . '" alt="' . $this->Name . '"/></a></div>' . "\n";
    }
}