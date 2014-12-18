<?php

namespace eliza\feed;

class QFeed extends CollectionFeed {
    public function QFeed() { return $this; }
    public function Q($_search, $_exclusive = true) {
        if ($_search === false) return $this;
        
        $Search = new static();
        
        foreach ($this as $Object) {
            $match = 0;
        
            foreach (explode(' ', $_search) as $term) {
                if (0 < ($found = (self::__search($term, $Object)))) {
                    $match += $found;
                } else if ($_exclusive) continue 2;
            }
            
            if ($match > 0) {
                $Match = new \eliza\beta\Object();
                $Match->Result = $Object;
                $Match->Relevance = $match;
                $Search->append($Match);
            }
        }
        
        $Search->sortBy('Relevance', SORT_DESC);
        return $Search;
    }
    
    public function filterBy($_prop, $_value) {
        $array = array();
        foreach ((array)$this as $key => $Object)
            if (self::__filter($_value, $Object->$_prop))
                $array[] = $this->get($key);
             
        $this->exchangeArray($array);
        return $this;
    }
    
    public static function __search($_term, $_Iterable) {
        $char_found = 0;
        
        foreach ($_Iterable as $Object) {
            if (is_array($Object)
                || is_object($Object)) {
                $matches = self::__search($_term, (array)$Object);
                $char_found = $char_found > $matches ? $char_found : $matches;
                
            } else {
                if (preg_match_all('/' . preg_quote($_term, '/') . '/i', $Object, $matches))
                    $char_found = $char_found > (count($matches[0])/strlen($Object)) ? $char_found : (count($matches[0])/strlen($Object));
            }
        }
         
        return $char_found;
    }
    
    public static function __filter($_prop, $_value) {
        if (is_array($_value)
            || is_object($_value)) {
            foreach ($_value as $el)
                if (self::__filter($_prop, $el)) 
                    return true;
            
        } else {
            return $_prop == $_value;
        }
    }
}
