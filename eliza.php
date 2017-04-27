<?php
//----------------------------------------------------------------------------//
//                                 eliza.php                                  //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                   global                                   //
//----------------------------------------------------------------------------//
    
    // debug status
    if (!defined('DEBUG')) define ('DEBUG', 1);
    
    // directories definition
    if (!defined('DS')) define ('DS', DIRECTORY_SEPARATOR);
    if (!defined('ROOT')) define ('ROOT', preg_replace('/\/|\\\/', DS, $_SERVER['DOCUMENT_ROOT'] . DS));
    
    // folders definition
    if (!defined('ELIZA')) define ('ELIZA', __DIR__ . DS);
    if (!defined('FEEDS')) define ('FEEDS', ROOT . 'feeds' . DS);
    if (!defined('TEMP')) define ('TEMP', ROOT . 'temp' . DS);
    if (!defined('CACHE')) define ('CACHE', TEMP . 'cache' . DS);
    
    // URIs definition
    if (!defined('BASE_URI')) define ('BASE_URI', 'http://' . $_SERVER['HTTP_HOST'] . '/');
    if (!defined('ELIZA_URI')) define ('ELIZA_URI', BASE_URI . 
        preg_replace('#/+#', '/', 
            str_replace('\\', '/', 
                str_replace(ROOT, '', ROOT . DS . dirname(__DIR__) . DS))));
    
    
    

//----------------------------------------------------------------------------//
//                                  autoload                                  //
//----------------------------------------------------------------------------//
    spl_autoload_extensions('.class.php');
    spl_autoload_register(null, false);
    spl_autoload_register(function ($_class) {
        // removes the _I suffix from the invoked interface
        $_class = preg_replace('/(_I)$/', '', $_class);
        
        // defines class definition file and path to namespace
        $class = @end(explode('\\', $_class)) . '.class.php';
        
        // loads class definition
        if (file_exists(ELIZA . 'classes' . DS . $class))
            require_once ELIZA . 'classes' . DS . $class;
        elseif (file_exists(ELIZA . 'feeds' . DS . $class))
            require_once ELIZA . 'feeds' . DS . $class;
    }, false);

    

//----------------------------------------------------------------------------//
//                                   errors                                   //
//----------------------------------------------------------------------------//
    error_reporting(E_ALL);
    set_exception_handler(function ($e) {
        include 'oops.php'; die();
    });
    
    if (!function_exists('oops')) {
        function oops($_excuse = null, $_details = array()) { 
            if (is_array($_excuse)) oops();
            throw new eliza\Oops(vsprintf($_excuse, $_details));
        }
    }
    
    set_error_handler(function () { 
        oops(func_get_arg(1).' in '.func_get_arg(2).' at '.func_get_arg(3)); 
    }, E_ALL & ~E_STRICT);
    
    // debug codes
    if (!defined('OOPS')) define('OOPS', 'there is something fishy going on');
    if (!defined('NOT_DEFINED')) define('NOT_DEFINED', '%s is not defined');
    if (!defined('NOT_DEFINED_PROPERTY')) define('NOT_DEFINED_PROPERTY', 'property %s::$%s is not defined');
    if (!defined('REQUEST_EMPTY')) define('REQUEST_EMPTY', 'you did not request anything');
    if (!defined('PERMISSION_DENIED')) define('PERMISSION_DENIED', 'you do not have permission to perform this operation');
    if (!defined('PERMISSION_DENIED_UPLOAD')) define('PERMISSION_DENIED_UPLOAD', 'you do not have permission to upload files');
    if (!defined('PERMISSION_DENIED_CONTENT')) define('PERMISSION_DENIED_CONTENT', 'you are not worthy of this content');