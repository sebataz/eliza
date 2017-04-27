<?php

namespace eliza;

class Response {
    public static function query($_feed, $_args) {
        return Feed::__callStatic($_feed, $_args);
    }

    public static function remote($_url, $_request, $_method) {
        $_option = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\nUser-Agent: eliza\r\n",
                'content' => http_build_query($_request),
                'method'  => $_method
            )
        );
        
        return file_get_contents($_url, false, stream_context_create($_option));
    }
    
    public static function redirect($_location) {
        header('Location: ' . $_location);
    }
    
    public static function hasPrivilege($_roles = array(), $_permission = null) {
        if (GlobalContext::Post()->offsetExists('lock'))
            GlobalContext::Session(array('lock' => GlobalContext::Post()->lock));
    
        foreach (GlobalContext::Configuration()->Role as $role => $lock) {
            if (!empty($_roles) && !in_array($role, $_roles))
                continue;
        
            if ((GlobalContext::Session()->defaultValue('lock') == $lock)) {
                if ($_permission && in_array($role, (array) GlobalContext::Configuration()->Permission->$_permission))
                    return $role;
                elseif (!$_permission)
                    return $role;
            }
        }
          
        return false;
    }
}