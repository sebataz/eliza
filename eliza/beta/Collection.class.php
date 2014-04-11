<?php 

namespace eliza\beta;

class Collection extends \ArrayObject {
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
}