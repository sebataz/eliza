<?php 

function tags($_callback_collection, $_suggest_for = null, $_create_tag_cloud = 1) {
    $tags = array();
    $tmp_tags = array();
    
    $collection = is_array($_callback_collection) ? $_callback_collection : Get::$_callback_collection();
    foreach ($collection as $item) {
        foreach ($item['Tags'] as $tag) {
            if ($_suggest_for)
                if (!preg_match('/^' . $_suggest_for . '/i', $tag)) 
                    continue;
                    
            $tmp_tags[$tag] = array_key_exists($tag, $tmp_tags) ? $tmp_tags[$tag] + 1 : 1 ;
        }
    }
    
    if (!(bool)$_create_tag_cloud) {
        $tags = array_keys($tmp_tags);
        arsort($tags);
        return $tags;
    }
    
    
    arsort($tmp_tags);
    foreach ($tmp_tags as $tag => $n) {
        $tags[] = array('Tag' => $tag,
                        'Count' => $n,
                        'Weight' => $n / count($tmp_tags),
                        'Size' => ($n / count($tmp_tags)) *  10.4 + 0.7 > 3.3 ? 3.3 : ($n / count($tmp_tags)) * 10.4 + 0.7); // use em
    }
    
    
    $tags = array_slice($tags, 0, 137);
    
    return $tags;
}