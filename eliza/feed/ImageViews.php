<?php

class ImageViews extends Image {
    public $Id = '';
    public $Views = 0; 
    
    public function save() {
        $this->Views = $this->Views + 1;
        parent::save();
    }
    
    public function __wakeup() {
        $meta_image = ROOT . 'feeds/imageviews/' . $this->Name . '.xml';
        
        if (file_exists($meta_image))
            $this->Views = (int)eliza\beta\Utils::readXMLFromFile($meta_image)->views;
    }
    
    public static function Feed($_gallery, $thumb_size = 0) {
        $RawGallery = parent::Feed($_gallery, $thumb_size);
        $Gallery = new eliza\feed\HTMLFeed();
        
        foreach ($RawGallery as $Image) {
            $ImageViews = $Image->castTo('ImageViews');
            $ImageViews->Id = $Image->Name;
            $Gallery->append($ImageViews);
        }
        
        return $Gallery;
    }
}