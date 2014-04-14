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
            // if ($_issue && $_issue != $Xml->Title) continue;
            
            
            $KbXml = new eliza\beta\Collection((array)simplexml_load_file($Xml->Path));
            
            $Kb = new self();
            $Kb->File = $Xml;
            $Kb->Id = $Xml->Title;
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
            
            // filter and return
            
            // if ($_type)
                // if ($_type != $Kb->Type)
                    // continue;
             
            // foreach ($_tag as $tag) {
                // if (!preg_match('/' . $tag . '/', implode(' ', $Kb->Tags)))
                    // continue 2;
            // }
            
            $KnowledgeBase->append($Kb);
        }
        
        
        return $KnowledgeBase;    
    }
}