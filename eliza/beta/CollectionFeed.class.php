<?php

namespace eliza\feed;

abstract class CollectionFeed extends \eliza\beta\Collection {
    public function sortBy($_prop, $_order = SORT_ASC)  {
        $array = (array) $this;
        if ($this->count()) {
            foreach ($array as $key => $Object)
                $props[$key] = $Object->$_prop;
                
            array_multisort($props, (int)$_order, $array);
            $this->exchangeArray($array);
        }
        
        return $this;
    }
    
    public function limit($_limit, $_offset = 0) {
        $array = (array) $this;
        $this->exchangeArray(array_slice($array, $_offset, $_limit));
        return $this;
    }
    
    public function getBy($_prop, $_value) {
        foreach ($this as $Object)
            if ($Object->$_prop == $_value)
                return $Object;
    }
}