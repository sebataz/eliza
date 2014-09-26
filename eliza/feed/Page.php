<?php

class Page extends eliza\beta\Feed implements eliza\feed\HTMLFeedI {
    public $Id = 0;
    public $Parent = 0;
    public $Title = '';
    public $Content = '';
    public $File = array();
    
    public static function Feed() {
        $Site = new eliza\feed\HTMLFeed();
        
        foreach (eliza\beta\Feed::Node(eliza\beta\GlobalContext::Configuration()->Feed->LocationPage) as $Xml) {
            if ($Xml->IsDir) continue;
        
            $PageXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            $Page = new self();
            $Page->Id = $Xml->Name;
            $Page->Parent = (int) $PageXml->parent;
            $Page->Title = (string) $PageXml->title;
            $Page->Content = (string) html_entity_decode($PageXml->content);
            $Page->File = $Xml;
            
            $Site->append($Page);
        }
        
        
        return $Site;    
    
    }
    
    public static function buildIndexHTML($_FeedCollection, $_parent = 0) {
        $index = '<ul class="index">';
        
        foreach ($_FeedCollection as $Page) {
            if ($Page->Parent == $_parent) {
                $index .= '<li><a href="?';
                $index .= (eliza\beta\Response::hasPrivilege()?'id':'id');
                $index .= '=' . $Page->Id . '">' . $Page->Title . '</a>';
                $index .= self::buildIndexHTML($_FeedCollection, $Page->Id) . '</li>';
            }
        }
            
        
        $index .= '</ul>';
        return $index;
    }

    public function toHTML() {
        return '<div class="title">'
            . $this->Title
            . '</div><div class="content">'
            . eliza\beta\Presentation::replaceHTMLFeedReference($this->Content)
            . '</div>';
    }

}