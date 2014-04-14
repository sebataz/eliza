<?php

namespace eliza\beta;

class Object {
    public function __construct(array $array = array()) {
        foreach ($array as $prop => $value)
            $this->$prop = $value;
    }
}