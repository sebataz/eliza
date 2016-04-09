<?php

namespace eliza\beta;

class GlobalContext extends Collection {
    public function set($_key, $_value) { }
    public function __set($_key, $_value) { }
    public static function Globals(){
        static $Globals;
        
        if (!$Globals) 
            $Globals = new self(array(
                'Server' => $_SERVER,
                'Get' => $_GET,
                'Post' => $_POST
            ));
            
        return $Globals;
    }

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
    
    public static function Querystring($_include = array()) {
        if (!is_array($_include)) $_include = array($_include);
        $querystring = array();
        
        foreach ($_GET as $key => $value) {
            if (in_array($key, $_include) || empty($_include))
                $querystring[$key] = $value;
        }
        
        // the preg_ will prevent the overwriting of array get variables, by removing the explicit index
        return empty($querystring)
             ? '' : ('&' . preg_replace('/%5B[0-9]*%5D/', '[]', http_build_query($querystring)));
    }
}