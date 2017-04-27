<?php

namespace eliza;

interface CollectionJSON_I {
    public function toJSON();
}

class CollectionJSON extends CollectionQuery {
    public function __construct($_json = array()) {
        if (is_array($_json)) parent::__construct($_json);
        else parent::__construct(static::JSONToArray($_json));
    }

    public function JSON() {
        $array = (array)$this;
        return json_encode($array);
    }
    
    public static function JSONToArray($_json) {
        return (array) json_decode($_json);
    }
}