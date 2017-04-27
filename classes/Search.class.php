<?php

namespace eliza;

//not tested, AT ALL!!!
class Search {

    public function search($_query, $_Collection, $_exclusive = true) {
        $Found = new Collection();
        
        foreach ($_Collection as $Object) {
            $match = 0;
        
            foreach (explode(' ', $_query) as $term) {
                if (0 < ($found = (self::__search($term, $Object)))) {
                    $match += $found;
                } else if ($_exclusive) continue 2;
            }
            
            if ($match > 0) {
                $Match = new Object();
                $Match->Result = $Object;
                $Match->Relevance = $match;
                $Found->append($Match);
            }
        }
        
        $Found->sortBy('Relevance', SORT_DESC);
        return $Found;
    }
    
    private static function __search($_term, $_Iterable) {
        $char_found = 0;
        
        foreach ($_Iterable as $Object) {
            if (is_array($Object)
                || is_object($Object)) {
                $matches = self::__search($_term, (array)$Object);
                $char_found = $char_found > $matches ? $char_found : $matches;
                
            } else {
                if (preg_match_all('/\b' . preg_quote($_term, '/') . '\b/i', $Object, $matches))
                    $char_found = $char_found > (count($matches[0])/strlen($Object)) ? $char_found : (count($matches[0])/strlen($Object));
            }
        }
         
        return $char_found;
    }
    
    public function filter ($_filters, $_Collection, $_operator = 'AND') {
        $Matches = new Collection();
        
        foreach ($_Collection as $Object) {
            foreach ($_filters as $property => $value) {
                if ('AND' === $_operator 
                && !self::__filter($value, $Object->$property))
                    continue 2;
                    
                if ('OR' === $_operator 
                && !self::__filter($value, $Object->$property))
                    continue;
                
                $Matches->append($Object);
            }
        }
    }
    
    private static function __filter($_value, $_Object) {
        if (is_array($_Object)
        || is_object($_Object))
            foreach ($_Object as $property => $value)
                if (self::__filter($_value, $value)) 
                    return true;
            
        else
            return $_value == $_Object;
    }
}