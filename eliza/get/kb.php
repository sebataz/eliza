<?php 

function kb($_issue = null, $_order_by = null, $_tag = array(), $_type = null) {
    $knowledge_base = array();
    $issue = array();
    
    $kb_file = Get::directory('issues');
    
    
    foreach ($kb_file as $xml) {    
        if ($_issue && $_issue != $xml['Title']) continue;
        
        $kb = array();
        $kb['File'] = $xml;
        $issue = (array) simplexml_load_file($xml['Path']);
        
        $kb['Id'] = $xml['Title'];
        $kb['Type'] = $issue['type'];
        $kb['Tags'] = explode(', ', $issue['tags']);
        $kb['Issue'] = (string) $issue['issue'];
        $kb['Description'] = (string) html_entity_decode($issue['description']);
        
        $kb['Checklist'] = array();
        if (isset($issue['checklist'])) {
            if (is_array($issue['checklist']))
                foreach ($issue['checklist'] as $checklist)
                    $kb['Checklist'][] = $checklist;
            else
                $kb['Checklist'][] = $issue['checklist'];
        }

        $kb['Related'] = array();
        if (isset($issue['related'])) {
            if (is_array($issue['related']))
                foreach ($issue['related'] as $related)
                    $kb['Related'][] = $related;
            else
                $kb['Related'][] = $issue['related'];
        }
        
        // filter and return
        
        if ($_type)
            if ($_type != $kb['Type'])
                continue;
         
        foreach ($_tag as $tag) {
            if (!preg_match('/' . $tag . '/', implode(' ', $kb['Tags'])))
                continue 2;
        }
        
        $knowledge_base[] = $kb;
    }
    
    // order by
    if ($_order_by && !empty($knowledge_base)) {
        foreach ($knowledge_base as $node)
            $temp_keys[] = $node[$_order_by];
        
        array_multisort($temp_keys, SORT_ASC, $knowledge_base);
    }
    
    return $knowledge_base;    
}