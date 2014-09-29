<?php
//----------------------------------------------------------------------------//
//                                 eliza\beta                                 //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                   global                                   //
//----------------------------------------------------------------------------//
    define ('DS', DIRECTORY_SEPARATOR);
    define ('ROOT', preg_replace('/\/|\\\/', DS, $_SERVER['DOCUMENT_ROOT'] . DS));
    define ('ELIZA', __DIR__ . DS); 
    define ('BASE_URI', 'http://' . $_SERVER['HTTP_HOST'] . '/');
    
    define ('DEBUG', 1);

//----------------------------------------------------------------------------//
//                                  autoload                                  //
//----------------------------------------------------------------------------//
    spl_autoload_register(null, false);
    spl_autoload_extensions('.class.php');
    spl_autoload_register(function ($_class) {
        $class = explode('\\', $_class);
        $class = preg_replace('/(I)$/', '', $class);
        
        $path_to_class = ELIZA . 'beta' . DS 
                       . end($class) . '.class.php';
                       
        $path_to_feed = ELIZA . 'feed' . DS
                      . end($class) . '.php';
                       
        if (file_exists($path_to_class))
            require_once $path_to_class;
        elseif (file_exists($path_to_feed))
            require_once $path_to_feed;
    }, false);



//----------------------------------------------------------------------------//
//                                   errors                                   //
//----------------------------------------------------------------------------//
    error_reporting(E_ALL);
    set_exception_handler(function ($e) {
        include 'oops.php'; die();
    });
    
    function oops($_excuse = null) { 
        if (is_array($_excuse)) oops();
        throw new eliza\beta\Oops($_excuse);
    }
    
    set_error_handler(function () { 
        oops(func_get_arg(1).' in '.func_get_arg(2).' at '.func_get_arg(3)); 
    }, E_ALL & ~E_STRICT);
    
    // debug codes
    define('OOPS', 'there is something fishy going on');