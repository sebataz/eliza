<?php

namespace eliza\beta;

class Response {
    public static function Feed($_feed, $_args) {
        Feed::load($_feed);
        return $_feed::__Feed($_args);
    }
    
    public static function __callStatic($_method, $_args) {
        if (!class_exists('eliza\\feed\\' . $_method, true)) oops(OOPS);
            
        return self::Feed($_args[0], $_args[1])->{$_method}();
    }
    
    public static function privileged() {
        if (GlobalContext::Session()->unlock)
            foreach (GlobalContext::Configuration()->Lock as $user => $lock)
                if (GlobalContext::Session()->unlock == $lock) return;
        
        oops('this page is locked');
    }
}