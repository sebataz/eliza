<?php

namespace eliza;

class ElizaObject {
    public function __construct(array $array = array()) {
        foreach ($array as $prop => $value)
            $this->$prop = $value;
    }
    
    public function mergeWith($_array = array()) {
        if (is_object($_array)) $_array = get_object_vars($_array);
        foreach ($_array as $prop => $value)
            $this->$prop = $value;
            
        return $this;
    }
    
    public function toArray() {
        return get_object_vars($this);
    }
    
    public function dump() {
        if (DEBUG) var_dump($this);
        else echo json_encode($this);
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