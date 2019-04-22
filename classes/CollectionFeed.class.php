<?php

namespace eliza;

class CollectionFeed extends CollectionHTML {
    public function getBy($_property, $_value) {
        $array = array();
        
        foreach ($this as $Feed)
            if ($Feed->$_property === $_value)
                $array[] = $Feed;
                
        $this->exchangeArray($array);
        return $this;
    }
	
    public function getById($_id) {
        return $this->getBy('Id', $_id);
    }
    
    public function sortBy($_prop, $_order = SORT_ASC /* or SORT_DESC*/)  {
        if (!$_prop) return $this;
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
}