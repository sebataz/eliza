<?php

namespace eliza;

class GlobalContext extends Collection {
    // prevents from setting any values in the global context
    public function offsetSet($_key, $_value) { }
    public function __set($_key, $_value) { }
    
    public static function Server() { return new self($_SERVER); }
    
    public static function Get() { return new self($_GET); }
    public static function Post() { return new self($_POST); }

    public static function Configuration() {
        static $Configuration;
        
        if (!$Configuration) {
            if (file_exists(ELIZA . 'config.php'))
                include (ELIZA . 'config.php');
                
            if (file_exists(ROOT . 'config.php'))
                include (ROOT . 'config.php');
            
            $Configuration = new self($cfg);
        }
        
        return $Configuration;
    }
    
    public static function Session($_array = array()) {
        static $Session;
        
        if (!$Session) {
            session_start();
            $Session = new self($_SESSION);
        }
            
        if (!empty($_array)) {
            foreach ($_array as $key => $value)
                $_SESSION[$key] = $value;
                
            $Session = new self($_SESSION);
        }
            
        return $Session;
    }
    
    public static function Files() { 
        static $Files;
        
        if (!$Files) {
            $arr_files = array();
            
            foreach ($_FILES as $request_file => $file_descriptor) {
                $arr_files[$request_file] = array();
                
                if (!is_array($file_descriptor['error'])) {
                    $file_descriptor['name'] = array($file_descriptor['name']);
                    $file_descriptor['type'] = array($file_descriptor['type']);
                    $file_descriptor['tmp_name'] = array($file_descriptor['tmp_name']);
                    $file_descriptor['error'] = array($file_descriptor['error']);
                    $file_descriptor['size'] = array($file_descriptor['size']);
                }
                
                foreach ($file_descriptor['error'] as $key => $error)
                    $arr_files[$request_file][] = new self(array(
                        'Type' => $file_descriptor['type'][$key],
                        'TmpName' => $file_descriptor['tmp_name'][$key],
                        'Error' => $file_descriptor['error'][$key],
                        'Size' => $file_descriptor['size'][$key],
                        'Name' => preg_replace('/[^A-Za-z0-9\-_\.\[\]\(\)]/', '', 
                            basename($file_descriptor['name'][$key]))
                    ));
                    
                
            }
            
            $Files = new self($arr_files);
        }
        
        return $Files;
        
    
    return new self($_FILES); 
    
    }
}