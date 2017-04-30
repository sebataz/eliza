<?php 

namespace eliza;

class File extends Node {
    public static function Feed($_directory = '.') {
        $File = new CollectionFeed();
        foreach (parent::Feed($_directory) as $Node)
            if (!$Node->IsDir)
                $File->append(new static($Node->toArray()));
                
        return $File;
    }

    public function content($_string = null, $_overwrite = true) {
        if ($_string === null) {
            return file_get_contents($this->Path);
        
        } else {
            if (!Request::hasPrivilege(array(), 'SaveFile')) 
                oops(PERMISSION_DENIED);
            
            if (!file_exists(dirname($this->Path)))
                mkdir(dirname($this->Path), 0777, true);
                
            if (!file_put_contents($this->Path, $_string, $_overwrite ? 0 : FILE_APPEND))
                oops(OOPS);
        }
    }
    
    public function uploadTo($_path_to_file) {
        if (!Request::hasPrivilege(array(), 'UploadFile')) 
            oops(PERMISSION_DENIED_UPLOAD);
        
        if (!file_exists(dirname($_path_to_file)))
            mkdir(dirname($_path_to_file));
        
        if (!move_uploaded_file($this->Path, $_path_to_file))
            oops('File could not be uploaded');
    }
    
    public static function touch($_path_to_file) {
        if (!file_exists(dirname($_path_to_file))) 
            mkdir(dirname($_path_to_file));
            
        if (!touch($_path_to_file)) oops(OOPS);
        
        return File::describeNode($_path_to_file);
    }
}