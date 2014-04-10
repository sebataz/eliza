<?php

namespace eliza\beta;

class Response {
    public static function __callStatic ($_method, $_args) {
        return static::invoke($_method, $_args);
    }
    
    public static function invoke($_method, $_args) {
        $feed = preg_replace("/(JSON)$/", '', $_method);
        $path_to_feed = ROOT . ELIZA . 'feed' . DS . $feed . '.php';
        
        if (!file_exists($path_to_feed)) oops('there is nothing to see here' . $path_to_feed);
        
        require_once $path_to_feed;
        if (preg_match("/(JSON)$/", $_method))
            return call_user_func_array(array(ucfirst($feed), 'feedJSON'), $_args);
            
        return call_user_func_array(array(ucfirst($_method), 'feed'), $_args);
    }
}