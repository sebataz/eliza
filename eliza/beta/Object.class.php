<?php

namespace eliza\beta;

class Object {
    public function __construct(array $array = array()) {
        foreach ($array as $prop => $value)
            $this->$prop = $value;
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