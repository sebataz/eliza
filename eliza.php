<?php
//----------------------------------------------------------------------------//
//                                 eliza.php                                  //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                   global                                   //
//----------------------------------------------------------------------------//
    
    // debug status
    if (!defined('DEBUG')) define ('DEBUG', 0);
    
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
    
    // eliza classes autoload
    spl_autoload_register(function ($_class) {
        if (in_array($_class, get_declared_classes())) return;
        
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
    
    // Autoloads a class from a configured path in config.php
    spl_autoload_register(function ($_class) {
    
        // You can define the path to one or more classes that you wish to
        // autoload by setting the configuration variable $cfg['PathToClass']
        // in the config.php file.
        $path_to_class = eliza\GlobalContext::Configuration()->PathToClass;
        
        // Default class extension should be used for the class to be autoloaded:
        // default extension is .class.php. You can change the default below:
        $class_extension = '.class.php';
        
        foreach (is_array($path_to_class) ? $path_to_class : array($path_to_class) 
        as $path)
            if (file_exists(ROOT . $path . DS . $_class . $class_extension))
                require_once ROOT .$path . DS . $_class . $class_extension;
    }, true);
    

    

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
    
    // Oops codes
    if (!defined('OOPS')) define('OOPS', 'there is something fishy going on');
    if (!defined('NOT_DEFINED')) define('NOT_DEFINED', '%s is not defined');
    if (!defined('NOT_DEFINED_PROPERTY')) define('NOT_DEFINED_PROPERTY', 'property %s::$%s is not defined');
    if (!defined('NOT_DEFINED_METHOD')) define('NOT_DEFINED_METHOD', 'method %s::%s() is not defined');
    if (!defined('EMPTY_REQUEST')) define('EMPTY_REQUEST', 'you did not request anything');
    if (!defined('PERMISSION_DENIED')) define('PERMISSION_DENIED', 'you do not have permission to perform this operation');
    if (!defined('PERMISSION_DENIED_UPLOAD')) define('PERMISSION_DENIED_UPLOAD', 'you do not have permission to upload files');
    if (!defined('PERMISSION_DENIED_CONTENT')) define('PERMISSION_DENIED_CONTENT', 'you are not worthy of this content');