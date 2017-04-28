<?php

namespace eliza;

class Object {
    public function __construct(array $array = array()) {
        foreach ($array as $prop => $value)
            $this->$prop = $value;
    }
    
    public function mergeWith($_array = array()) {
        if (is_object($_array)) $_array = get_object_vars($_array);
        foreach (get_object_vars($this) as $prop => $value)
            $this->$prop = array_key_exists($prop, $_array) ? $_array[$prop] : $value;
    }
    
    public function toArray() {
        return get_object_vars($this);
    }
    
    
    
    /**
     * Returns the name of the called class.
     * 
     * @return string The class name.
     */
    public static function getClass($_with_namespace=FALSE) {
        $class = str_replace('\\','',substr('\\' . get_called_class(), strrpos(get_called_class(), '\\')+1));
        return $_with_namespace ? get_called_class() : $class;
    }
}