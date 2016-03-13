<?php

class Text extends eliza\beta\Feed implements eliza\feed\HTMLFeedI {
    public $Id = 0;
    public $Label = '';
    public $Title = '';
    public $Text = '';
    public $File = array();
    
    public function __construct(array $array = array()) {
        parent::__construct($array);
        $this->Id = eliza\beta\Utils::uniqueId();
    }
     
    public function save() {
        if (eliza\beta\Response::hasPrivilege())
            parent::save();
        else
            oops('not allowed');    
    }
       
    public function delete() {
        if (eliza\beta\Response::hasPrivilege())
            parent::delete();
        else
            oops('not allowed');    
    }
    
    public static function Feed() {
        $Site = new eliza\feed\HTMLFeed();
        
        foreach (eliza\beta\Feed::Node(eliza\beta\GlobalContext::Configuration()->Feed->LocationText) as $Xml) {
            if ($Xml->IsDir) continue;
        
            $TextXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            $Text = new self();
            $Text->Id = $Xml->Name;
            $Text->Label = (string) $TextXml->label;
            $Text->Title = (string) $TextXml->title;
            $Text->Text = (string) html_entity_decode($TextXml->text);
            $Text->File = $Xml;
            
            $Site->append($Text);
        }
        
        
        return $Site;    
    
    }

    public function toHTML() {
        return '<div id="title"><h1>'
            . $this->Title
            . '</h1></div><div id="text">'
            . eliza\beta\Presentation::replaceHTMLFeedReference($this->Text)
            . '</div>';
    }
    
    public function wordCount() {
        return str_word_count(strip_tags($this->Text));
    }

}