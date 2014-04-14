<?php

namespace eliza\feed;

class QFeed extends CollectionFeed {
    private static function __search($_term, $_Object, $_case_sensitive = false) {
        if (is_array($_Object)) {
            foreach ($_Object as $prop)
                if (self::__search($_term, $prop)) return true;
        } elseif (is_object($_Object)) {
            foreach (get_object_vars($_Object) as $prop)
                if (self::__search($_term, $prop)) return true;
        } elseif ($_Object instanceof eliza\beta\Collection) {
            foreach ($_Object as $Object)
                if (self::search($_term, $Object)) return true;
        } else
            return (bool)preg_match('/\b' . preg_quote($_term, '/') . '\b/' . ($_case_sensitive ? '' : 'i'), $_Object, $match);
    }

    public function QFeed() { return $this; }
    public function Q($_search, $_case_sensitive = false) {
        if ($_search === false) return $this;
    
        $result_i = 0;
        $exact_result = array();
        $approx_result = array();
        $keywords = explode (' ', $_search);
       
        
        foreach ((array)$this as $Object) {
            if (self::__search(str_replace(' ', '\w', $_search), $Object)) {
                $exact_result[$result_i]['ItemID'] = $result_i;
                $exact_result[$result_i]['Result'] = $Object;
                $exact_result[$result_i]['Relevance'] = 0;
                $result_i++;
                continue;
            }
            
            foreach ($keywords as $term) {
                if (self::__search($term, $Object)) {
                    $approx_result[$result_i]['ItemID'] = $result_i;
                    $approx_result[$result_i]['Result'] = $Object;
                    $approx_result[$result_i]['Relevance'] = isset($approx_result[$result_i]['Relevance']) ? $approx_result[$result_i]['Relevance']+1 : 1;
                }
            }
            
            $result_i++;
        }
        
        foreach ($approx_result as $result)
            $temp_keys[] = $result['Relevance'];
        
        if (isset($temp_keys))
            array_multisort($temp_keys, SORT_DESC, $approx_result);
        
        $Q =  new static(array_merge($exact_result, $approx_result));
        return $Q;
    }
    
    
    public function filterBy($_prop, $_value) {
        $array = array();
        foreach ($this as $key => $Object)
            if (self::__search($_value, $Object->$_prop))
                $array[] = $this->get($key);
             
        $this->exchangeArray($array);
        return $this;
    }
}