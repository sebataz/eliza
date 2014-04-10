<?php 

/**
 *  List directory content, path must be given to relative from site directory
 */
 
function directory($_directory, $_order_by = null, $_sorting = SORT_ASC) {
    if (!file_exists(ROOT . $_directory)) return array();

    $ls = array();
    $path = realpath(ROOT . $_directory) . DS;
    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . preg_replace('#/+#', '/', str_replace('\\', '/', str_replace(ROOT, '', $path)));
    
    foreach (scandir($path) as $node) {
        /* IGNORE FILES */
        // ignoring non-file
        if (($node == '.') || ($node == '..') 
        // ignoring hidden file, preceded with _
        || preg_match('/^_.*$/', $node))
            continue;
            
        // strip extension
        $title = preg_replace('/(.*)(\.\b.*\b)/', '$1', $node);
        
        // collecting files
        $ls[] = array('Title' => $title,
                      'Filename' => $node,
                      'Extension' => '.' . pathinfo($node, PATHINFO_EXTENSION),
                      'Timestamp' => filemtime($path . $node),
                      'Path' => $path . $node,
                      'Url' => $url . $node,
                      'IsDir' => is_dir($path . $node));
    }
    
    // order by
    if ($_order_by && !empty($ls)) {
        foreach ($ls as $node)
            $temp_keys[] = $node[$_order_by];
        
        array_multisort($temp_keys, (int)$_sorting, $ls);
    }
    
    return $ls;
}