<?php

class Comment extends eliza\feed\Feed implements eliza\feed\HTMLFeedI {
    public $Id = 0;
    public $Origin = 0;
    public $Author = '';
    public $Text = '';
    
    public function __construct(array $_array = array()) {
        parent::__construct($_array);
        $this->Id = eliza\beta\Utils::uniqueId();
    }
    
    public function save() {
        $this->Text = htmlentities($this->Text);
        $this->Author = htmlentities($this->Author);
        parent::save();
    }
    
    public static function Feed($_origin = null) {
        $Comments = new eliza\feed\HTMLFeed();
        
        foreach (eliza\feed\Feed::Node(eliza\beta\GlobalContext::Configuration()->Feed->LocationComment) as $Xml) {
            if ($Xml->IsDir) continue;
        
        
            $CommentXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            if (($_origin !== null) && ((string)$CommentXml->origin != (string)$_origin)) continue;
            
            $Comment = new self();
            $Comment->Id = $Xml->Name;
            $Comment->Origin = (string) $CommentXml->origin;
            $Comment->Author = htmlentities((string) $CommentXml->author);
            $Comment->Text = htmlentities((string) html_entity_decode($CommentXml->text));
            
            $Comments->append($Comment);
        }
        
        return $Comments;    
    
    }
    
    public function toHTML() {
        return '<div class="comment-author">'
            . $this->Author
            . ' :</div><div class="comment-text">'
            . $this->Text
            . '</div>';
    }
    
    public static function createForm($_id = 0) {
        echo '<div class="comment-form">
                <form action="eliza/eliza/index.php?Comment&by=Id&redirect=1" method="POST">
                    <input type="hidden" name="Origin" value="'.$_id.'" />
                    <div><input type="text" name="Author" value="Anonymous" class="comment-author" /></div>
                    <div><textarea name="Text" class="comment-text"></textarea></div>
                    <button type="submit">post</button>
                </form></div>';
    }
}