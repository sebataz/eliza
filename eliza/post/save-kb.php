<?php

function save_kb($_old_id, $_new_id, $_type, $_tags, $_issue, $_description, $_checklist, $_related = array()) {
    $issue_location = 'issues' . DS;
    $issue_id = $_new_id;
    $issue_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<kb>\n"
               . "\t<type>" . utf8_encode($_type) . "</type>\n"
               . "\t<tags>" . utf8_encode($_tags) . "</tags>\n"
               . "\t<issue><![CDATA[" . utf8_encode(htmlentities($_issue)) . "]]></issue>\n"
               . "\t<description><![CDATA[" . utf8_encode(trim($_description)) . "]]></description>\n";
    
    $_checklist = array_map('trim', $_checklist);
    foreach ($_checklist as $checklist) {
        if ($checklist != '')
            $issue_xml .= "\t<checklist>" . utf8_encode($checklist) . "</checklist>\n";
    }
        
    foreach ($_related as $related) {
        if ($related != '')
            $issue_xml .= "\t<related>" . utf8_encode($related) . "</related>\n";
    }
            
    if ((bool)Post::save_file($issue_location . $issue_id . '.xml', $issue_xml . "</kb>"))
        header('Location: ../?id=' . $_new_id);
}