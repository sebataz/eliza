<?php

class Disegni extends Image {
    public $Id = '';
    public $Views = 0; 
    
    public function save() {
        $this->Views = $this->Views + 1;
        parent::save();
    }
    
    public function __wakeup() {
        $meta_disegno = 'feeds/disegni/' . $this->Name . '.xml';
        
        if (file_exists(ROOT . $meta_disegno)) {
            $DisegnoXml = new eliza\beta\Collection((array)simplexml_load_file(ROOT . $meta_disegno));
            $this->Views = (int)$DisegnoXml->views;
        }
    }
    
    public static function Feed($_gallery, $thumb_size = 0) {
        $RawGallery = parent::Feed($_gallery, $thumb_size);
        $Gallery = new eliza\feed\HTMLFeed();
        
        foreach ($RawGallery as $Image) {
            $Disegno = $Image->castTo('Disegni');
            $Disegno->Id = $Image->Name;
            $Gallery->append($Disegno);
        }
        
        return $Gallery;
    }
}