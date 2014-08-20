<?php

class Page extends eliza\beta\Feed implements eliza\feed\HTMLFeedI {
    public $Id = 0;
    public $Title = '';
    public $Author = '';
    public $Draft = true;
    public $Content = '';
    public $File = array();
    
    public static function Feed() {
        $Site = new eliza\feed\HTMLFeed();
        
        foreach (eliza\beta\Feed::Node('page') as $Xml) {
            if ($Xml->IsDir) continue;
        
            $PageXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            $Page = new self();
            $Page->Id = $Xml->Name;
            $Page->Title = (string) $PageXml->title;
            $Page->Author = (string) $PageXml->author;
            $Page->Content = (string) html_entity_decode($PageXml->content);
            $Page->File = $Xml;
            
            $Page->Draft = $PageXml->draft == 0 ? false : true;
            
            if ($Page->Draft && !eliza\beta\Response::hasPrivilege()) continue;
            
            $Site->append($Page);
        }
        
        
        return $Site;    
    
    }
    
    public static function Navigation() {
        // TODO: implementation
    }

    public function toHTML() {
        return '<div class="title">'
            . $this->Title
            . '</div><div class="content">'
            . eliza\beta\Presentation::replaceHTMLFeedReference($this->Content)
            . '</div>';
    }

}