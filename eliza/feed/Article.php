<?php

class Article extends eliza\beta\Feed implements eliza\feed\HTMLFeedI {
    public $Id = 0;
    public $Title = '';
    public $Author = '';
    public $Headline = '';
    public $Text = '';
    public $Tags = array();
    public $File = array();
    
    public static function Feed() {
        $Blog = new eliza\feed\HTMLFeed();
        
        foreach (eliza\beta\Feed::Node('articles') as $Xml) { 
            if ($Xml->IsDir) continue;
        
            $ArticleXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            $Article = new self();
            $Article->Id = $Xml->Name;
            $Article->Title = (string) $ArticleXml->title;
            $Article->Author = (string) $ArticleXml->author;
            $Article->Headline = (string) html_entity_decode($ArticleXml->headline);
            $Article->Text = (string) html_entity_decode($ArticleXml->text);
            $Article->Tags = explode(', ', $ArticleXml->tags);
            $Article->File = $Xml;
            
            $Blog->append($Article);
        }
        
        
        return $Blog;    
    
    }
    
    public static function Archive($_FeedCollection) {
        $temp_archive = array();
        foreach ($_FeedCollection as $Article)
            $temp_archive[date('FY', $Article->File->Datetime)] = date('F Y', $Article->File->Datetime);
            
        return new eliza\beta\Collection(array_unique($temp_archive));
    }
    
    public static function byMonth($_FeedCollection, $_month) {
        $ByMonth = new eliza\feed\HTMLFeed();
        
        foreach ($_FeedCollection as $Article)
            if (date('FY', $Article->File->Datetime) == $_month)
                $ByMonth->append($Article);
        
        return $ByMonth;
    }

    public function toHTML() {
        return '<div class="title">'
            . '<img class="avatar" src="public/img/' . $this->Author . '-avatar.jpg" />'
            . '<a href="?id=' . $this->Id . '"><h3>'
            . $this->Title
            . '</a></h3><div class="date">'
            . date('d M', $this->File->Datetime)
            . '</div></div><div class="author">by '
            . $this->Author
            . '</div><div class="headline">'
            . $this->Headline
            . '</div><div class="text">'
            . eliza\beta\Presentation::replaceHTMLFeedReference($this->Text)
            . '</div>';
    }

}