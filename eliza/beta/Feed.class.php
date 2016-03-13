<?php

namespace eliza\beta;

abstract class Feed extends Object {
    public function __get($_prop){ oops(OOPS); }
    public function __set($_prop, $_val) { oops(OOPS); }
    
    public function __construct(array $_array = array()) {
        foreach (get_class_vars(get_called_class()) as $key => $default)
            $this->$key = array_key_exists($key, $_array) ? $_array[$key] : $default;
    }
    
    public function mergeWith(array $_array = array()) {
        foreach (get_object_vars($this) as $prop => $value)
            $this->$prop = array_key_exists($prop, $_array) ? $_array[$prop] : $value;
    }
    
    public function toArray() {
        return get_object_vars($this);
    }
    
    public function save () {
        $XmlFeed = new \eliza\feed\XMLFeed(array($this));
    
        Utils::writeFile(
            GlobalContext::Configuration()->Feed->Location
            . strtolower($this->getClass()) . DS
            . $this->Id . '.xml',
            
            $XmlFeed->XMLFeed()
        );
    }
    
    public function delete () {
        eliza\beta\Utils::deleteFile(
            eliza\beta\GlobalContext::Configuration()->Feed->Location
            . strtolower($this->getClass()) . DS 
            . $this->Id . '.xml'
        );
    }
    
    public static function __callStatic($_feed, $_args) {
        self::load($_feed);
        return $_feed::__Feed($_args);
    }
    
    public static function __Feed($_args) {
        return call_user_func_array(array(get_called_class(), 'Feed'), $_args);
    }
    
    public static function load($_feed) {
        $feed = ELIZA . 'feed' . DS 
              . $_feed . '.php';
              
        if (class_exists($_feed)) return;
        if (!file_exists($feed)) oops();
        require_once $feed;
    }
}