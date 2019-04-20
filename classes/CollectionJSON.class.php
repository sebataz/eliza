<?php

namespace eliza;

interface CollectionJSON_I {
    public function toJSON();
}

class CollectionJSON extends CollectionQuery {
    public function __construct($_json = array()) {
		if (static::isJSON($_json))
			static::__construct(static::JSONToArray($_json));
		
		else
			foreach($_json as $key => $value) {
				if (self::isJson($value))
					parent::offsetSet($key, new static(static::JSONToArray($value)));
				
				else
					parent::offsetSet($key, $value);
			}
    }

    public function JSON() {
        $array = (array)$this;
        return json_encode($array);
    }
    
    public static function JSONToArray($_json) {
        return (array) json_decode($_json);
    }
	
	public static function isJSON($string) {
		if (!is_string($string)) return false;
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}