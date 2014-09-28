<?php

//include eliza
include '../eliza/beta.php';

//define an item class
class Item extends eliza\beta\Feed {
    public $Value = '';
    
    public static function Feed() {
        $Collection = new eliza\feed\HTMLFeed();
        $Collection->append(new self(array('Value'=>'Hello World!')));
        
        return $Collection;
        
    }
}

//echo formatted html output
echo eliza\beta\Response::HTMLFeed('Item');
echo '<br />';
echo eliza\beta\Feed::Item()->HTMLFeed();