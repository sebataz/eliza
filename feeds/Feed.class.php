<?php

namespace eliza;

abstract class Feed extends Object {
    public $Id = null;
    
    public function __get($_prop){ oops(NOT_DEFINED_PROPERTY, array($this->getClass(), $_prop)); }
    public function __set($_prop, $_val) { oops(NOT_DEFINED_PROPERTY, array($this->getClass(), $_prop)); }
    
    public function __construct(array $_array = array()) {
        $this->Id = static::uniqueId();
        foreach (get_class_vars(get_called_class()) as $key => $default)
            $this->$key = array_key_exists($key, $_array) ? $_array[$key] : $default;
    }
    
    public static function uniqueId() {
        return time() . substr(microtime(),2,3);
    }
    
    public static function __callStatic ($_feed, $_args) {
        if (class_exists($_feed))
            return call_user_func_array(array($_feed, 'Feed'), $_args);
            
        if (class_exists('eliza\\' . $_feed))
            return call_user_func_array(array('eliza\\' . $_feed, 'Feed'), $_args);

        oops(NOT_DEFINED, $_feed);
    }
}