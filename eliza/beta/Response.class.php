<?php

namespace eliza\beta;

class Response {
    private static function loadFeed($_feed) {
        $path_to_feed = ROOT . ELIZA . 'feed' . DS . $_feed . '.php';
        if (!file_exists($path_to_feed)) oops('there is nothing to see here');
        require_once $path_to_feed;
    }

    public static function __callStatic ($_method, $_args) {
        if (!class_exists('eliza\\beta\\' . $_method, true))
            return self::Feed($_method, $_args);
            
        self::loadFeed($_args[0]);
        return call_user_func_array(array(ucfirst($_args[0]), $_method), $_args[1]);
    }
    
    public static function Feed($_feed, $_args) {
        self::loadFeed($_feed);
        return call_user_func_array(array(ucfirst($_feed), 'Feed'), $_args);
    }
}