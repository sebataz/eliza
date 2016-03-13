<?php 

namespace eliza\beta;

class Presentation {
    private static $_cache = null;

    public static function buffered() {
        ob_start();
        register_shutdown_function(array(get_called_class(), 'show'));
    }

    public static function cached($_timeout = 3600) {
        self::$_cache = '/temp/cache/' . md5($_SERVER['REQUEST_URI']);
        
        if ((file_exists(self::$_cache)) && (filemtime(self::$_cache) + $_timeout) > time()) {
            readfile(self::$_cache);
            exit();
        } else {
            self::buffered();
        }
    }
    
    public static function show() {
        try {
            $buffer = self::flush();
            $buffer = self::replaceHTMLFeedReference($buffer);
            if (self::$_cache) file_put_contents(self::$_cache, $buffer);
            echo $buffer; //might be bug: die();
        } catch (\Exception $e) {
            include ELIZA . 'oops.php'; die();
        }
    }
    
    public static function flush() {
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    
    // prototype [Feed arg1 arg2 /]
    // prototype with query [Feed arg1 arg2 /]{method1:arg1,arg2 method2:arg1,arg2}
    public static function replaceHTMLFeedReference($_content) {
        $replacedContent = '';
        $replacedContent = preg_replace_callback('/\[(.[^\]]*?)\s\/\](\{(.*?)\})?/', function($matches) {

            $callback = explode(' ', $matches[1]);
            $Feed = Response::Feed($callback[0], array_slice($callback, 1));
            
            if (count($matches) > 3) {
                foreach (explode(' ', $matches[3]) as $q) {
                    $feed_callback = explode(':', $q);
                    $Feed = call_user_func_array(array($Feed, $feed_callback[0]), explode(',', $feed_callback[1]));
                }
            }
            
            if ($Feed instanceof \eliza\feed\HTMLFeed)
                return $Feed->HTMLFeed();

            
            oops('HTML for "' . $callback[0] . '" could not be loaded');
            return $matches[0];
        }, $_content);
    
        return $replacedContent;
    }
}