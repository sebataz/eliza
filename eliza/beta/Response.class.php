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
    
    public static function remote($_url, $_request, $_method) {
        $_option = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($_request),
                'method'  => $_method
            )
        );
        
        return file_get_contents($this->url, false, stream_context_create($_option));
    }
    
    /* I don't like that much this solution, since it becomes a bit too tight
    I'd like it more loose. but for that i must use another strategy and right miao this works!!!
    well i haven't tested it yet, but i am quite confident */
    public static function privileged() {
        if (GlobalContext::Session()->offsetExists('unlock'))
            foreach (GlobalContext::Configuration()->Lock as $user => $lock)
                if (GlobalContext::Session()->unlock == $lock) return;
        
        oops('you are not that privilieged');
    }
}