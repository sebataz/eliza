<?php 

namespace eliza;

class File extends Feed {
    public $Filename = '';
    public $IsDir = false;
    public $Extension = '';
    public $AbsolutePath = '';
    public $Datetime = 0;
    
    // use these ;)
    public $Name = ''; // = $this->Id
    public $Path = '';
    public $Url = '';

    // all path must be passed relative to ROOT
    public static function Feed($_directory = '.', $_ext_white_list = '') {
        $Directory = new CollectionFeed();
        
        foreach (scandir(ROOT . $_directory) as $file) {
            if (($file == '.') || ($file == '..'))
                continue;
                
            $File = static::describeFile(ROOT . $_directory . DS . $file);
            if(!$File)continue;

            $regex = str_replace('.', '^$', $_ext_white_list); // use '.' for file without extension
            $regex = '/(' . str_replace(',', '|', $regex) . ')/i';
            if (!preg_match($regex, $File->Extension))
                continue;
            
            $Directory->append($File);
        }
        
        return $Directory;
    }
    
    
    
    public static function describeFile($_path_to_file) {
        if (!file_exists($_path_to_file)) oops($_path_to_file . ' does not exist');
        
        $File = new static();
        $File->AbsolutePath = realpath($_path_to_file);
        $File->Path = str_replace(ROOT, '', $File->AbsolutePath);
        $File->Filename = basename($File->Path);
        $File->Id = $File->Name = pathinfo($File->Filename, PATHINFO_FILENAME);
        $File->Extension = pathinfo($File->Filename, PATHINFO_EXTENSION);
        $File->Datetime = filemtime($File->AbsolutePath);
        $File->IsDir = is_dir($File->AbsolutePath);
        $File->Url = BASE_URI . addslashes($File->Path);
        
        return $File;
    }
    
    public function deleteFromDisk() {
        if (!Request::hasPrivilege(array(), 'DeleteFile')) oops(PERMISSION_DENIED);
        
        if (file_exists($this->AbsolutePath)) {
            if (DEBUG) oops('delete file ' . $this->AbsolutePath);
            if ($this->IsDir)
                rmdir($this->AbsolutePath);
            else
                unlink($this->AbsolutePath);
        }
    }
}