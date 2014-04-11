<?php 

class Kb extends eliza\beta\JSONFeed {
    public static function Feed($_issue = null, $_order_by = null, $_tag = array(), $_type = null) {
        $KnowledgeBase = new eliza\beta\Collection();
        $KbFile = eliza\beta\Response::Ls('issues');
        
        foreach ($KbFile as $Xml) { 
            if ($_issue && $_issue != $Xml->Title) continue;
            
            $KbXml = new eliza\beta\Object();
            $Kb = new eliza\beta\Object();
            
            $Kb->File = $Xml;
            $KbXml = simplexml_load_file($Xml->Path);
            
            $Kb->Id = $Xml->Title;
            $Kb->Type = $KbXml->type;
            $Kb->Tags = explode(', ', $KbXml->tags);
            $Kb->Issue = (string) $KbXml->issue;
            $Kb->Description = (string) html_entity_decode($KbXml->description);
            
            $Kb->Checklist = array();
            if (isset($KbXml->checklist)) {
                if (is_array($KbXml->checklist))
                    foreach ($KbXml->checklist as $checklist)
                        $Kb->Checklist[] = $checklist;
                else
                    $Kb->Checklist[] = $KbXml->checklist;
            }

            $Kb->Related = array();
            if (isset($KbXml->related)) {
                if (is_array($KbXml->related))
                    foreach ($KbXml->related as $related)
                        $Kb->Related[] = $related;
                else
                    $Kb->Related[] = $KbXml->related;
            }
            
            // filter and return
            
            if ($_type)
                if ($_type != $Kb->Type)
                    continue;
             
            foreach ($_tag as $tag) {
                if (!preg_match('/' . $tag . '/', implode(' ', $Kb->Tags)))
                    continue 2;
            }
            
            $KnowledgeBase->append($Kb);
        }
        
        
        return $KnowledgeBase;    
    }
}