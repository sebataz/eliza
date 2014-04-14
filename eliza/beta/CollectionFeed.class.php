<?php

namespace eliza\feed;

abstract class CollectionFeed extends \eliza\beta\Collection {
    private $__Feed = null;
    
    public function append($_Feed) {
        $this->__Feed = get_class($_Feed);
        return parent::append($_Feed);
    }
    
    public function getBy($_prop, $_value) {
        foreach ($this as $Object)
            if ($Object->$_prop == $_value)
                return $Object;
    }
    
    public function sortBy($_prop, $_order = SORT_ASC)  {
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
    
    public function __call($_method, $_args) {
        if (!$this->__Feed) return new parent();
        return call_user_func_array(array($this->__Feed, $_method),
                                    array_merge(array($this), $_args));
    }
}