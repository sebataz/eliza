<?php 

function xml_dataset($_path_to_xml = null) {
    $dataset = array();
    
    $xml_file = Get::directory($_path_to_xml);
    
    foreach ($xml_file as $xml) {    
        $record = array();
        $record['Source'] = $xml;
        
        $tmp_record = (array) @simplexml_load_file($xml['Path']);
        if ($tmp_record[reset(array_keys($tmp_record))] === false) continue;
        
        foreach ($tmp_record as $key => $value) {
            if (!array_key_exists($key, $record)) {
                $record[ucfirst($key)] = $value;
                continue;
            }
            
            if (!is_array($record[ucfirst($key)])) {
                $record[ucfirst($key)][] = $record[ucfirst($tag)];
                $record[ucfirst($key)][] = $value;
                continue;
            }
            
            $record[ucfirst($key)][] = $value;
        }
        
        $dataset[] = $record;
    }
    
    return $dataset;    
}