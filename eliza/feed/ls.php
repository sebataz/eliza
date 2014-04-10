<?php 

class Ls extends eliza\beta\JSONFeed {
    public static function Feed($_directory, $_order_by = 'Title', $_sorting = SORT_ASC) {  
        if (!file_exists(ROOT . $_directory)) oops('the directory ' . $_directory . ' does not exist');

        $Ls = new eliza\beta\Collection();
        
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
            
            $Node = new eliza\beta\Object();
            $Node->Title = $title;
            $Node->Filename = $node;
            $Node->Extension = '.' . pathinfo($node, PATHINFO_EXTENSION);
            $Node->Timestamp = filemtime($path . $node);
            $Node->Path = $path . $node;
            $Node->Url = $url . $node;
            $Node->IsDir = is_dir($path . $node);
            $Ls->append($Node);
        }
        
        return $Ls->sortBy($_order_by, $_sorting);
    }
}