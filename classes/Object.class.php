<?php

namespace eliza;

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
    
    /**
     * Casts an object to another class, by serializing the object and then
     * unserializing it to the new class instance.
     * 
     * @param string $_clazz_name A class name.
     * @return Object The casted object.
     */
    public function castTo($_class_name) {
        return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($_class_name) . ':"' . $_class_name . '"', serialize($this)));
    }
}