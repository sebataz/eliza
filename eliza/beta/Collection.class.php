<?php 

namespace eliza\beta;

class Collection extends \ArrayObject {
    public function __construct(array $_array = array()) {    
        foreach($_array as $key => $value) {
            if(is_array($value))
                $value = new self($value);
            
            parent::offsetSet($key, $value);
        }
    }
    
    public function first() {
        return $this->offsetGet(0);
    }
    
    public function defaultValue($_key, $_default_value = null) {
        return $this->offsetExists($_key) ? $this->offsetGet($_key) : $_default_value;
    }
    
    public function __get($_key) { return $this->offsetGet($_key); }
    public function __set($_key, $_value) { $this->offsetSet($_key, $_value); }
}