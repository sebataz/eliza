<?php

// main settings
define ('DS', DIRECTORY_SEPARATOR); 
define ('ROOT', dirname(__DIR__) . DS);

abstract class Service {
    
    public static function load($_service) {
        $path_to_service = ROOT 
                        . 'service' . DS
                        . strtolower(get_called_class()) . DS
                        . $_service . '.php';
                        
        if (!file_exists($path_to_service))
            return false;
        
        include_once($path_to_service);
        return true;
    }
    
    public static function invoke($_service, $_params) {
        if (self::load($_service) === false) return false;
    
        $callback = str_replace('-', '_', $_service);
    
        return call_user_func_array($callback, $_params);
    }
    
    public static function __callStatic($_method, $_args) {
        return self::invoke($_method, $_args);
    }
    
    abstract public static function proxy(array $_request);
}

class Get extends Service {
    public static function proxy(array $_request) {
        if (empty($_request)) return;
    
        $params = array_keys($_request);
        
        // output data
        if (($result = self::invoke($params[0], array_slice($_request, 1, count($_request)))) !== false) {
            @header('Content-type: application/json');
            echo json_encode($result);
            exit();
        }
    }
}

class Post extends Service {
    public static function proxy(array $_request) {
        if (empty($_request)) return;
    
        $params = array_keys($_request);
        
        // output data
        if (($result = self::invoke($params[0], array_slice($_request, 1, count($_request)))) !== false) {
            if (($post = post($service, $_POST)) !== false) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }
}