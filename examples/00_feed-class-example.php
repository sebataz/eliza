<?php

include '../eliza/beta.php';

class Item extends eliza\beta\Feed {
    public $Value = '';
    
    public static function Feed() {
        $Collection = new eliza\feed\HTMLFeed();
        $Collection->append(new self(array('Value'=>'Hello World!')));
        
        return $Collection;
        
    }
}
                    

echo eliza\beta\Response::HTMLFeed('Item');