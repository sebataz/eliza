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
                        
        if (!file_exists($path_to_service)) return false;
        
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
    
    public static function remote($_url, $_request, $_method = 'GET') {
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => $_method,
                'content' => http_build_query($_request),
            )
        );
        
        $context  = stream_context_create($options);
        
        if ($_method == 'POST') {
                file_get_contents($_url . ($_method == 'GET' ? '?' . $options['http']['content'] : ''), false, $context);
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
        } 
        
        if ($_method == 'GET') {
            @header('Content-type: application/json');
            echo file_get_contents($_url . ($_method == 'GET' ? '?' . $options['http']['content'] : ''), false, $context);
            exit();
        }
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
                exit();
            }
        }
    }
}