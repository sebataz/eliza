<?php

namespace eliza\beta;

class Response {
    public static function __callStatic($_method, $_args) {
        if (!class_exists('eliza\\feed\\' . $_method, true))
            oops('format ' . $_method . ' not found');
        
        return \eliza\feed\Feed::__callStatic($_args[0], array_slice($_args, 1))->{$_method}();
    }

    public static function remote($_url, $_request, $_method) {
        $_option = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($_request),
                'method'  => $_method
            )
        );
        
        return file_get_contents($_url, false, stream_context_create($_option));
    }
    
    public static function redirect($_location) {
        header('Location: ' . $_location);
    }
    
    public static function hasPrivilege() {
        if (GlobalContext::Globals()->Post->offsetExists('lock'))
            GlobalContext::Session(array('unlock' => GlobalContext::Globals()->Post->lock));
    
        foreach (GlobalContext::Configuration()->Lock as $user => $lock)
            if (GlobalContext::Session()->defaultValue('unlock') == $lock) return true;
            
        return false;
    }
}