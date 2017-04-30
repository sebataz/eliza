<?php 

namespace eliza;

class Node extends Feed {
    public $Name = '';
    public $Filename = '';
    public $Extension = '';
    public $Datetime = 0;
    public $Path = '';
    public $Url = '';
    public $IsDir = false;
    
    public function delete() {
        if (!Request::hasPrivilege(array(), 'DeleteFile')) oops(PERMISSION_DENIED);
        
        unlink($this->Path);
    }
    
    public static function Feed($_directory = '.') {
        $Directory = new CollectionFeed();
        
        foreach (scandir(self::__path(ROOT . $_directory)) as $node) {
            /* IGNORE FILES */
            // ignoring non-file
            if (($node == '.') || ($node == '..') 
            // ignoring hidden file with prefix __
            || preg_match('/^__$/', $node))
                continue;
            
            
            $Directory->append(static::describeNode(ROOT . $_directory . DS . $node));
        }
        
        return $Directory;
    }
    
    public static function describeNode($_path) {
        $directory = dirname($_path);
        $node = basename($_path);
    
        $Node = new static();
        $Node->Name = preg_replace('/(.*)(\.\b.*\b)/', '$1', $node);
        $Node->Id = $Node->Name;
        $Node->Filename = $node;
        $Node->Extension = '.' . pathinfo($node, PATHINFO_EXTENSION);
        $Node->Datetime = filemtime(self::__path($directory) . $node);
        $Node->Path = self::__path($directory) . $node;
        $Node->Url = self::__url($directory) . $node;
        $Node->IsDir = is_dir(self::__path($directory) . $node);
        
        return $Node;
    }
    
    private static function __url($_directory) {
        return BASE_URI . preg_replace('#/+#', '/', str_replace('\\', '/', str_replace(ROOT, '', self::__path($_directory))));
    }
    
    private static function __path($_directory) {
        if (!file_exists($_directory)) 
            oops('the directory ' . $_directory . ' does not exist');
    
        return realpath($_directory) . DS;
    }
}