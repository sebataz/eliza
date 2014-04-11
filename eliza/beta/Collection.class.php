<?php 

namespace eliza\beta;

class Collection extends \ArrayObject {
    public function __construct(array $_array = array())
    {    
        foreach($_array as $key => $value) {
            if(is_array($value)){
                $value = new self($value);
            }
            
            $this->offsetSet($key, $value);
        }
    }

    public function sortBy($_key, $_order = SORT_ASC)  {
        $array = (array) $this;
        if ($this->count()) {
            foreach ($array as $key => $Object)
                $keys[$key] = $Object->$_key;
                
            array_multisort($keys, (int)$_order, $array);
            $this->exchangeArray($array);
        }
        
        return $this;
    }
    
    public function getBy($_key, $_value) {
        foreach ($this as $Object)
            if ($Object->$_key == $_value)
                return $Object;
    }
    
    public function toArray() {
        return (array)$this;
    }
    
    public function toArrayRecursive() {
        oops('the feature is not even implemented yet');
    }
    
    public function __get($_key) { return $this->offsetGet($_key); }
    public function __set($_key, $_value) { $this->offsetSet($_key, $_value); }
}