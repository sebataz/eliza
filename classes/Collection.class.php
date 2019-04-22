<?php 

namespace eliza;

class Collection extends \ArrayObject {
    public function __get($_key) { return $this->offsetGet($_key); }
    public function __set($_key, $_value) { $this->offsetSet($_key, $_value); }

    public function __construct(array $_array = array()) {
        foreach($_array as $key => $value) {
            if(is_array($value))
                $value = new static($value);
            
            parent::offsetSet($key, $value);
        }
    }
    
    public function defaultValue($_key, $_default_value = null) {
        return $this->offsetExists($_key) ? $this->offsetGet($_key) : $_default_value;
    }
    
    public function first() {
        foreach ($this as $first)
            return $first;
    }
    
	// thinking about removing this method, might be an overstatement...
    // public function append($_Object) {
       // parent::append($_Object);
    // }
    
    public function concat($_Collection) {
        if ($_Collection instanceof self)
            foreach ($_Collection as $Value)
                parent::append($Value);
    }
    
    public function shuffle() {
        $array = (array) $this;
        shuffle($array);
        
        $this->exchangeArray($array);
        return $this;
    }
}