<?php

namespace eliza\beta;

class GlobalContext extends Collection {
    public static function Configuration($_load_file = '../config.php') {
        static $Configuration;
        
        if (!$Configuration) {  
            if (file_exists($_load_file) include ($_load_file);
            $Configuration = new self($cfg)
        }
        
        return $Configuration;
    }
    
    public static function Session($_array = array()) {
        static $Session;
        
        if (!$Session) {
            session_start();
            $Session = new self($_SESSION);
        }
            
        if (!empty($_array))
            foreach ($_array as $key => $value)
                $Session->$key = $_SESSION[$key] = $value;
            
        return $Session;
    }
    
    public static function Get() {
        static $Get;
        
        if (!$Get)
            $Get = new self($_GET);
            
        return $Get;
    }
    
    public static function Post() {
        static $Post;
        
        if (!$Post)
            $Post = new self($_POST);
            
        return $Post;
    }
    
    public static function Querystring($_include = array()) {
        if (!is_array($_include)) $_include = array($_include);
        $querystring = array();
        
        foreach (self::Get() as $key => $value) {
            if (in_array($key, $_include) || empty($_include))
                $querystring[$key] = $value;
        }
        
        // the preg_ will prevent the overwriting of array get variables, by removing the explicit index
        return empty($querystring) 
             ? '' : ('&' . preg_replace('/%5B[0-9]*%5D/', '[]', http_build_query($querystring)));}
}