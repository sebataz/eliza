<?php

namespace eliza;

class FileContent extends File implements CollectionHTML_I {


    public function content($_string = null, $_overwrite = true) {
        if ($this->IsDir) oops($this->Path . ' is a directory');
        if ($_string === null) {
            return file_get_contents($this->AbsolutePath);
        
        } else {
            //if (!Request::hasPrivilege(array(), 'SaveFile')) oops(PERMISSION_DENIED);
            if (DEBUG) oops('write content to file ' . $this->Path);
            
            if (!file_exists(dirname($this->AbsolutePath)))
                mkdir(dirname($this->AbsolutePath), 0777, true);
                
            if (!file_put_contents($this->AbsolutePath, $_string, $_overwrite ? 0 : FILE_APPEND))
                oops(OOPS);
        }
    }
    
    public static function createFile($_path_to_file) {
        if (!file_exists(dirname($_path_to_file))) 
            mkdir(dirname($_path_to_file));
            
        if (!touch($_path_to_file)) oops(OOPS);
        
        return static::describeFile($_path_to_file);
    }
    
    public static function uploadFile($_file_to_upload, $_upload_path) {
        if (!Request::hasPrivilege(array(), 'UploadFile')) oops(PERMISSION_DENIED_UPLOAD);
        if (DEBUG) oops('upload file as ' . $_upload_path);
        
        if (!file_exists(dirname($_upload_path)))
            mkdir(dirname($_upload_path));
        
        if (!move_uploaded_file($_file_to_upload, $_upload_path))
            oops('File could not be uploaded');
            
        return static::describeFile($_upload_path);
    }

    public function toHTML() {
        return <<<EOT
\n<div id="{$this->Id}" class="file">
    <a href="{$this->Url}" target="_blank">
        <span class="filename">{$this->Filename}<span>
    </a>
</div>\n
EOT;
    }

}