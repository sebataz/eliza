<?php

class SearchFeed extends QueryFeed {
    private static function __search($_term, $_Object, $_case_sensitive = false) {
        if (is_object($_Object)) {
            forech (get_object_vars($_Object) as $prop)
                if (self::__search($_term, $prop) return true;
        } elseif ($_Object instanceof eliza\beta\Collection) {
            foreach ($_Object as $Object)
                if (self::search($_term, $Object) return true;
        } else
            return (bool)preg_match('/\b' . $_term . '\b/' . ($_case_sensitive ? '' : 'i'), $_item, $match);
    }

    public function Search($_search, $_case_sensitive = false) {
    
        $result_i = 0;
        $exact_result = array();
        $approx_result = array();
        $keywords = explode (' ', $_search);
        $collection = is_array($_callback_collection) ? $_callback_collection : Get::$_callback_collection();
        
        // so the thing should go like that: you give me something to search, OK? then I will split it in single words and
        // give you back everything that checks with any of those words.
        
        // but a nice thing to do would be, if the search statement/phrase is same as something that can be found. I would want that first exposed.
        
        // let's first try to get the how much the keyword is all about in the thing that I wanna check
        // then maybe, and why not, you could check something like the proximity of words to get better results
        
        // aah!!!! one more thing. Let's say you have a collection composed of object you would wanna check only some of the properites of it.
        // So for this I will, as you see in the arguments, put an array of fields to test against. but for now let's keep it simple stupid.
        
        foreach ((array)$this as $item) {
            if (in_item(str_replace(' ', '\w', $_search), $item)) {
                $exact_result[$result_i]['ItemID'] = $result_i;
                $exact_result[$result_i]['Result'] = $item;
                $exact_result[$result_i]['Relevance'] = 0;
                $result_i++;
                continue;
            }
            
            foreach ($keywords as $term) {
                if (in_item($term, $item)) {
                    $approx_result[$result_i]['ItemID'] = $result_i;
                    $approx_result[$result_i]['Result'] = $item;
                    $approx_result[$result_i]['Relevance'] = isset($approx_result[$result_i]['Relevance']) ? $approx_result[$result_i]['Relevance']+1 : 1;
                }
            }
            
            $result_i++;
        }
        
        foreach ($approx_result as $result)
            $temp_keys[] = $result['Relevance'];
        
        if (isset($temp_keys))
            array_multisort($temp_keys, SORT_DESC, $approx_result);
        
        return array_merge($exact_result, $approx_result);
    }
}