<?php

namespace eliza;

class Feed {
    public $Id;
	
    public function __get($_prop){ oops(NOT_DEFINED_PROPERTY, array($this->getClass(), $_prop)); }
    public function __set($_prop, $_val) { oops(NOT_DEFINED_PROPERTY, array($this->getClass(), $_prop)); }
    
    public function __construct(array $_array = array()) {
        $this->Id = static::uniqueId();
        foreach (get_class_vars(get_called_class()) as $key => $default)
            if (array_key_exists($key, $_array))
                $this->$key = $_array[$key];
    }
    
    
    
    public static function __callStatic ($_feed, $_args) {
        if (!is_subclass_of($_feed, 'eliza\\Feed')
        && !is_subclass_of('eliza\\' . $_feed, 'eliza\\Feed'))
            oops('class ' . $_feed . ' is not a valid Feed');
    
        if (class_exists($_feed))
            return call_user_func_array(array($_feed, 'Feed'), $_args);
            
        if (class_exists('eliza\\' . $_feed))
            return call_user_func_array(array('eliza\\' . $_feed, 'Feed'), $_args);
    }
	
    public static function uniqueId() { 
		return time() . substr(microtime(),2,3); 
	}
    
    public function mergeWith($_array = array()) {
        if (is_object($_array)) $_array = get_object_vars($_array);
        foreach ($_array as $prop => $value)
            $this->$prop = $value;
            
        return $this;
    }
    
    public function dump() {
        if (DEBUG) var_dump($this);
        else echo json_encode($this);
    }
	
    public static function getClass($_with_namespace=FALSE) {
        $class = str_replace('\\','',substr('\\' . get_called_class(), strrpos(get_called_class(), '\\')+1));
        return $_with_namespace ? get_called_class() : $class;
    }
    
    // ACHTUNG: method below prevents infinite recursion if Feed::Feed() is called
    public static function Feed() { 
        // which one ist better?
        oops(NOT_DEFINED_METHOD, array(get_called_class(), 'Feed')); 
        //return new CollectionFeed(); 
    } 
}