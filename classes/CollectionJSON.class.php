<?php

namespace eliza;

interface JSONFeedI {
    public function toJSON();
}

class JSONFeed extends CollectionFeed {
    public function __construct($_json = array()) {
        if (is_array($_json)) parent::__construct($_json);
        else parent::__construct(static::JSONToArray($_json));
    }

    public function JSONFeed() {
        $array = (array)$this;
        return json_encode($array);
    }
    
    public static function JSONToArray($_json) {
        return (array) json_decode($_json);
    }
}