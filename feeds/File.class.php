<?php 

namespace eliza;

class File extends Node {
    public function content($_string = null, $_overwrite = true) {
        if ($_string === null) {
            return file_get_contents($this->Path);
        
        } else {
            if (!Response::hasPrivilege(array(), 'SaveFile')) oops(PERMISSION_DENIED);
            if (!file_exists(dirname($this->Path)))
                mkdir(dirname($this->Path), 0777, true);
                
            if (!file_put_contents($this->Path, $_string, $_overwrite ? 0 : FILE_APPEND))
                oops(OOPS);
        }
    }
}