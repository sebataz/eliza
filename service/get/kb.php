<?php 

function kb($_issue = null, $_order_by = null, $_tag = array(), $_type = null) {
    $knowledge_base = array();
    $issue = array();
    
    $kb_file = Get::directory('issues');
    
    foreach ($kb_file as $xml) {    
        $kb = array();
        $kb['File'] = $xml;
        $issue = (array) simplexml_load_file($xml['Path']);
        
        $kb['Type'] = $issue['type'];
        $kb['Tags'] = explode(', ', $issue['tags']);
        $kb['Issue'] = (string) $issue['issue'];
        $kb['Description'] = (string) html_entity_decode($issue['description']);
        
        if (isset($issue['checklist'])) {
            if (is_array($issue['checklist']))
                foreach ($issue['checklist'] as $checklist)
                    $kb['Checklist'][] = $checklist;
            else
                $kb['Checklist'][] = $issue['checklist'];
        }

        if (isset($issue['solution'])) {
            if (is_array($issue['solution']))
                foreach ($issue['solution'] as $solution)
                    $kb['Solution'][] = (string) $solution;
            else
                $kb['Solution'][] = (string) $issue['solution'];
        }
        
        // filter and return
        
        if ($_type)
            if ($_type != $kb['Type'])
                continue;
         
        foreach ($_tag as $tag) {
            if (!ereg($tag, implode(' ', $kb['Tags'])))
                continue 2;
        }
        
        if ($_issue == $xml['Title'])
            return array($kb);
        else
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