<?php

namespace eliza;

interface CollectionFeed_I {
	public function toString();
}

class CollectionFeed extends Collection {
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
	
	public function __call($_encoding, $_) {
		$encoding = ('eliza\Collection' . $_encoding);
		
		if (!class_exists($enconding)) oops('The collection enconding ' . $_encoding . ' is not implemented.');
			
		return (new $encoding((array)$this))->toString();
	}
}