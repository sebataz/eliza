<?php 

class Node extends eliza\beta\Feed {
    public $Title = '';
    public $Filename = '';
    public $Extension = '';
    public $Timestamp = 0;
    public $Path = '';
    public $Url = '';
    public $IsDir = false;
    
    public static function Feed($_directory = '.') {  
        if (!file_exists(ROOT . $_directory)) oops('the directory ' . $_directory . ' does not exist');

        $Directory = new eliza\feed\JSONFeed();
        
        foreach (scandir(self::path($_directory)) as $node) {
            /* IGNORE FILES */
            // ignoring non-file
            if (($node == '.') || ($node == '..') 
            // ignoring hidden file, preceded with _
            || preg_match('/^_.*$/', $node))
                continue;
                
            // strip extension
            $title = preg_replace('/(.*)(\.\b.*\b)/', '$1', $node);
            
            $Node = new self();
            $Node->Title = $title;
            $Node->Filename = $node;
            $Node->Extension = '.' . pathinfo($node, PATHINFO_EXTENSION);
            $Node->Timestamp = filemtime(self::path($_directory) . $node);
            $Node->Path = self::path($_directory) . $node;
            $Node->Url = self::url($_directory) . $node;
            $Node->IsDir = is_dir(self::path($_directory) . $node);
            $Directory->append($Node);
        }
        
        return $Directory;
    }
    
    public static function path($_directory) {
        return realpath(ROOT . $_directory) . DS;
    }
    
    public static function url($_directory) {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/' . preg_replace('#/+#', '/', str_replace('\\', '/', str_replace(ROOT, '', self::path($_directory))));
    }
}