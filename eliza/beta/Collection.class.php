<?php 

namespace eliza\beta;

class Collection extends \ArrayObject {
    public function __construct(array $_array = array()) {    
        foreach($_array as $key => $value) {
            if(is_array($value)){
                $value = new self($value);
            }
            
            $this->offsetSet($key, $value);
        }
    }
    
    public function get($_key) { return $this->offsetGet($_key); }
    public function set($_key, $_value) { $this->offsetSet($_key, $_value); }
    public function __get($_key) { return $this->get($_key); }
    public function __set($_key, $_value) { $this->set($_key, $_value); }
}