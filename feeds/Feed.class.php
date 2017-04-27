<?php

namespace eliza;

abstract class Feed extends Object {
    public $Id = null;
    
    public function __get($_prop){ oops(PROPERTY_NOT_DEFINED, array($this->getClass(), $_prop)); }
    public function __set($_prop, $_val) { oops(PROPERTY_NOT_DEFINED, array($this->getClass(), $_prop)); }
    
    public function __construct(array $_array = array()) {
        $this->Id = static::uniqueId();
        foreach (get_class_vars(get_called_class()) as $key => $default)
            $this->$key = array_key_exists($key, $_array) ? $_array[$key] : $default;
    }
    
    public function mergeWith(array $_array = array()) {
        foreach (get_object_vars($this) as $prop => $value)
            $this->$prop = array_key_exists($prop, $_array) ? $_array[$prop] : $value;
    }
    
    public function toArray() {
        return get_object_vars($this);
    }
    
    public static function uniqueId() {
        return time() . substr(microtime(),2,3);
    }
    
    public static function __callStatic ($_feed, $_args) {
        return call_user_func_array(array($_feed, 'Feed'), $_args);
    }
}