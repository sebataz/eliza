<?php

namespace eliza;

interface CollectionJSON_I {
    public function toJSON();
}

class CollectionJSON extends CollectionFeed implements CollectionFeed_I {
    public function __construct($_json = array()) {
		if (static::isJSON($_json))
			static::__construct(json_decode($_json));
		
		else
			foreach($_json as $key => $value) {
				if (self::isJSON($value))
					parent::offsetSet($key, new static(static::JSONToArray($value)));
				
				else
					parent::offsetSet($key, $value);
			}
    }

    public function toString() {
        $array = (array)$this;
        return json_encode($array);
    }
    
	public static function isJSON($string) {
		if (!is_string($string)) return false;
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}