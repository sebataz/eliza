<?php 

class Kb extends eliza\beta\Feed {
    public $Id = null;
    public $Type = '';
    public $Issue = '';
    public $Description = '';

    public $File;
    
    public $Checklist = array();
    public $Related = array();
    public $Tags = array();
    
    public static function Feed() {
        $KnowledgeBase = new eliza\feed\JSONFeed();
        
        foreach (eliza\beta\Feed::Node('issues') as $Xml) { 
            $KbXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            $Kb = new self();
            $Kb->File = $Xml;
            $Kb->Id = $Xml->Name;
            $Kb->Type = $KbXml->type;
            $Kb->Tags = explode(', ', $KbXml->tags);
            $Kb->Issue = (string) $KbXml->issue;
            $Kb->Description = (string) html_entity_decode($KbXml->description);
            
            $Kb->Checklist = array();
            if ($KbXml->offsetExists('checklist')) {
                if (is_string($KbXml->checklist))
                    $Kb->Checklist[] = $KbXml->checklist;
                else
                    foreach (((array)$KbXml->checklist) as $checklist)
                        $Kb->Checklist[] = $checklist;
            }

            $Kb->Related = array();
            if ($KbXml->offsetExists('related')) {
                if (is_string($KbXml->related))
                    $Kb->Related[] = $KbXml->related;
                else
                    foreach (((array)$KbXml->related) as $related)
                        $Kb->Related[] = $related;
            }
            
            $KnowledgeBase->append($Kb);
        }
        
        
        return $KnowledgeBase;    
    }
    
    public static function TagCloud($_FeedCollection) {
        $Tags = new eliza\feed\JSONFeed();
        
        foreach ($_FeedCollection->TagList() as $tag => $n) {
            $Tag = new eliza\beta\Object();
            $Tag->Tag = $tag;
            $Tag->Count = $n;
            $Tag->Weight = $n / $_FeedCollection->TagList()->count();
            $Tag->Size = ($n / $_FeedCollection->TagList()->count()) *  10.4 + 0.7 > 3.3 
                       ? 3.3 : ($n / $_FeedCollection->TagList()->count()) * 10.4 + 0.7;
            $Tags->append($Tag);
        }
        
        $Tags->sortBy('Weight', SORT_DESC);
        return $Tags->limit(137);
    }
    
    public static function TagList($_FeedCollection, $_suggest_for = null) {
        $Tags = new eliza\feed\JSONFeed();
        foreach ($_FeedCollection as $Object) {
            foreach ($Object->Tags as $tag) {
                if ($_suggest_for)
                    if (!preg_match('/^' . $_suggest_for . '/i', $tag)) 
                        continue;
                
                $Tags->set($tag, $Tags->offsetExists($tag) ? $Tags->get($tag) + 1 : 1);
            }
        }
        
        if ($_suggest_for) {
            $tmp_array = array_values(array_keys((array)$Tags));
            sort($tmp_array);
            return new eliza\feed\JSONFeed($tmp_array);
        }
        
        return $Tags;
    }
}