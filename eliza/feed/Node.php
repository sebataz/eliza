<?php 

class Node extends eliza\beta\Feed {
    public $Name = '';
    public $Filename = '';
    public $Extension = '';
    public $Timestamp = 0;
    public $Path = '';
    public $Url = '';
    public $IsDir = false;
    
    public static function Feed($_directory = '.') {  
        if (!file_exists(ROOT . $_directory)) oops('the directory ' . $_directory . ' does not exist');

        $Directory = new eliza\feed\JSONFeed();
        
        foreach (scandir(self::__path($_directory)) as $node) {
            /* IGNORE FILES */
            // ignoring non-file
            if (($node == '.') || ($node == '..') 
            // ignoring hidden file, preceded with _
            || preg_match('/^_.*$/', $node))
                continue;
            
            $Node = new self();
            $Node->Name = preg_replace('/(.*)(\.\b.*\b)/', '$1', $node);
            $Node->Filename = $node;
            $Node->Extension = '.' . pathinfo($node, PATHINFO_EXTENSION);
            $Node->Timestamp = filemtime(self::__path($_directory) . $node);
            $Node->Path = self::__path($_directory) . $node;
            $Node->Url = self::__url($_directory) . $node;
            $Node->IsDir = is_dir(self::__path($_directory) . $node);
            $Directory->append($Node);
        }
        
        return $Directory;
    }
    
    private static function __path($_directory) {
        return realpath(ROOT . $_directory) . DS;
    }
    
    private static function __url($_directory) {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/' . preg_replace('#/+#', '/', str_replace('\\', '/', str_replace(ROOT, '', self::__path($_directory))));
    }
}