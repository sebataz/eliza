<?php

class Article extends eliza\beta\Feed implements eliza\feed\HTMLFeedI {
    public $Id = 0;
    public $Title = '';
    public $Author = '';
    public $Headline = '';
    public $Text = '';
    public $Tags = array();
    public $File;
    
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

    public function toHTML() {
        return '<div class="title">'
            . '<img class="avatar" src="public/img/sebataz-avatar.jpg" /><h3>'
            . $this->Title
            . '</h3><div class="date">'
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