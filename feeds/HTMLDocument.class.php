<?php

namespace eliza;

class HTMLDocument extends FileContent implements CollectionHTML_I {
    public $Title = '';
    public $Source = '';

    public static function Feed ($_directory = '.', $_ext_white_list = 'html,htm,txt') {
        return parent::Feed($_directory, $_ext_white_list);
    }
    
    public static function describeFile($_path_to_file) {        
        $HTMLDocument = parent::describeFile($_path_to_file);
        $HTMLDocument->Title =  $HTMLDocument->Name;
        $HTMLDocument->Source = $HTMLDocument->content();
        return $HTMLDocument;
    }
    
    public function saveToDisk() {
        if (!Request::hasPrivilege(array(), 'SaveFeed')) oops(PERMISSION_DENIED);
        if (DEBUG) oops('save new feed in ' . strtolower($this-getClass()));
        
        $this->content($this->Source);
    }
    
    public function toHTML() {
        return $this->content();
    }
}