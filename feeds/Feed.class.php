<?php

namespace eliza;

abstract class Feed extends Object {
    public $Id;
    
    public function __get($_prop){ oops(NOT_DEFINED_PROPERTY, array($this->getClass(), $_prop)); }
    public function __set($_prop, $_val) { oops(NOT_DEFINED_PROPERTY, array($this->getClass(), $_prop)); }
    
    public function __construct(array $_array = array()) {
        $this->Id = static::uniqueId();
        foreach (get_class_vars(get_called_class()) as $key => $default)
            if (array_key_exists($key, $_array))
                $this->$key = $_array[$key];
    }
    
    public static function uniqueId() {
        return time() . substr(microtime(),2,3);
    }
    
    public static function __callStatic ($_feed, $_args) {
        if (!class_exists($_feed) && !class_exists('eliza\\' . $_feed))
            oops(NOT_DEFINED_CLASS, $_feed);
    
        if (method_exists($_feed, 'Feed'))
            return call_user_func_array(array($_feed, 'Feed'), $_args);
            
        if (method_exists('eliza\\' . $_feed, 'Feed'))
            return call_user_func_array(array('eliza\\' . $_feed, 'Feed'), $_args);
            
        oops(NOT_DEFINED_METHOD, array(!$_feed ? 'null' : $_feed, 'Feed'));
    }
    
    // ACHTUNG: method below prevents infinite recursion if Feed::Feed() is called
    public static function Feed() { return new CollectionFeed(); } 
}