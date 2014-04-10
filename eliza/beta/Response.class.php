<?php

namespace eliza\beta;

class Response {
    public static __callStatic ($_method, $_args) {
        require_once (ROOT . ELIZA . 'feed' . DS . $_method . '.php');
    
        if ($feed = preg_replace('/JSON$/', '', $_method) !== null)
            return call_user_func_array(array('eliza\\feed\\' . $feed, 'feedJSON'), $_args);
            
        return call_user_func_array(array('eliza\\feed\\' . $_method, 'feed'), $_args);
    }
}