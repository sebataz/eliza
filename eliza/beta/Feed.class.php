<?php

namespace eliza\beta;

abstract class Feed extends Object {
    public function __get($_prop){ oops(OOPS); }
    public function __set($_prop, $_val) { oops(OOPS); }
    
    public function __construct(array $array = array()) {
        foreach (get_class_vars(get_called_class()) as $key => $default)
            $this->$key = array_key_exists($key, $array) ? $array[$key] : $default;
    }
    
    public static function __callStatic($_feed, $_args) {
        self::load($_feed);
        return $_feed::__Feed($_args);
    }
    
    public static function __Feed($_args) {
        return call_user_func_array(array(get_called_class(), 'Feed'), $_args);
    }
    
    public static function load($_feed) {
        $feed = ROOT . ELIZA . 'feed' . DS 
              . $_feed . '.php';
              
        if (!file_exists($feed)) oops();
        require_once $feed;
    }
}