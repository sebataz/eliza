<?php

class Article extends eliza\beta\Feed implements eliza\feed\HTMLFeedI {
    public $Id = 0;
    public $Title = '';
    public $Author = '';
    public $Date = 0;
    public $Draft = true;
    public $Headline = '';
    public $Text = '';
    public $Tags = array();
    public $File = array();
    
    public function __construct(array $_array = array()) {
        parent::__construct($_array);
        $this->Id = eliza\beta\Utils::uniqueId();
        $this->Date = time();
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
        $Blog = new eliza\feed\HTMLFeed();
        
        foreach (eliza\beta\Feed::Node(eliza\beta\GlobalContext::Configuration()->Feed->LocationArticle) as $Xml) {
            if ($Xml->IsDir) continue;
        
            $ArticleXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            $Article = new self();
            $Article->Id = $Xml->Name;
            $Article->Title = (string) $ArticleXml->title;
            $Article->Author = (string) $ArticleXml->author;
            $Article->Date = (int) $ArticleXml->date;
            $Article->Headline = (string) html_entity_decode($ArticleXml->headline);
            $Article->Text = (string) html_entity_decode($ArticleXml->text);
            if (isset($ArticleXml->tags))
                $Article->Tags = explode(', ', $ArticleXml->tags);
            $Article->File = $Xml;
            
            $Article->Draft = $ArticleXml->draft == 0 ? false : true;
            
            if ($Article->Draft && !eliza\beta\Response::hasPrivilege()) continue;
            
            $Blog->append($Article);
        }
        
        
        return $Blog;    
    
    }
    
    public static function Archive($_FeedCollection) {
        $temp_archive = array();
        foreach ($_FeedCollection->sortBy('Date', SORT_DESC) as $Article)
            $temp_archive[date('FY', $Article->Date)] = date('F Y', $Article->Date);
            
        return new eliza\beta\Collection(array_unique($temp_archive));
    }
    
    public static function TagCloud($_FeedCollection) {
        $Tags = new eliza\feed\JSONFeed();
        
        foreach ($_FeedCollection as $Object) {
            foreach ($Object->Tags as $tag)                
                $Tags->set($tag, $Tags->offsetExists($tag) ? $Tags->get($tag) + 1 : 1);
        }
        
        $temp_tags = $Tags->getArrayCopy();
        arsort($temp_tags);
        
        $Tags->exchangeArray($temp_tags);        
        return $Tags;
    }
    
    public static function byMonth($_FeedCollection, $_month) {
        $ByMonth = new eliza\feed\HTMLFeed();
        
        foreach ($_FeedCollection as $Article)
            if (date('FY', $Article->Date) == $_month)
                $ByMonth->append($Article);
        
        return $ByMonth;
    }
    
    public function toHTML() {
        return '<div class="title">'
            . '<h1><a href="?id=' . $this->Id . '">'
            . $this->Title
            . '</a></h1><div class="date">'
            . date('d F Y', $this->Date)
            . '</div></div><div class="author">by '
            . $this->Author
            . '</div><div class="headline">'
            . eliza\beta\Presentation::replaceHTMLFeedReference($this->Headline)
            . '</div><div class="text">'
            . eliza\beta\Presentation::replaceHTMLFeedReference($this->Text)
            . '</div>';
    }

}