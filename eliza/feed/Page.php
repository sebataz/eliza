<?php

class Page extends eliza\feed\Feed implements eliza\feed\HTMLFeedI {
    public $Id = 0;
    public $Parent = 0;
    public $Title = '';
    public $Content = '';
    
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
        
        foreach (eliza\feed\Feed::Node(eliza\beta\GlobalContext::Configuration()->Feed->LocationPage) as $Xml) {
            if ($Xml->IsDir) continue;
        
            $PageXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            $Page = new self();
            $Page->Id = $Xml->Name;
            $Page->Parent = $PageXml->parent;
            $Page->Title = (string) $PageXml->title;
            $Page->Content = (string) html_entity_decode($PageXml->content);
            
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
        
        if (eliza\beta\Response::hasPrivilege()) {
            $index .= '<li><form id="new-page-at-' . $_parent . '" action="eliza\eliza\index.php?Page&by=Id&redirect=1" method="POST">';
            $index .= '<input type="hidden" name="Parent" value="' . $_parent . '" />';
            $index .= '<input type="hidden" name="Title" value="edit title" />';
            $index .= '<input type="hidden" name="Content" value="edit content" />';
            $index .= '<a href="#" onclick="document.getElementById(\'new-page-at-' . $_parent . '\').submit();">new page here</a>';
            $index .= '</form></li>';
        }
        
        $index .= '</ul>';
        return $index;
    }

    public function toHTML() {
        return '<div id="title" contenteditable="' . (eliza\beta\Response::hasPrivilege()?'true':'false') . '">'
            . $this->Title
            . '</div>' . PHP_EOL
            . '<div id="content" contenteditable="' . (eliza\beta\Response::hasPrivilege()?'true':'false') . '">'
            . eliza\beta\Presentation::replaceHTMLFeedReference($this->Content)
            . '</div>';
    }

}