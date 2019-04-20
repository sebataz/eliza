<?php 

namespace eliza;

class Presentation {
    private static $_cache = null;

    public static function buffered() {
        ob_start();
        register_shutdown_function(array(get_called_class(), 'show'));
    }

    public static function cached($_timeout = 3600) {
        self::$_cache = ROOT . 'temp/cache/' . md5($_SERVER['REQUEST_URI']);
        
        if ((file_exists(self::$_cache)) 
            && (filemtime(self::$_cache) + $_timeout) > time()) {
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
            
            if (self::$_cache) {
                if (!is_dir(CACHE)) mkdir(CACHE, 0777, true);
                file_put_contents(self::$_cache, $buffer);    
            }
            
            echo $buffer;
        } catch (\Exception $e) {
            include ELIZA . 'oops.php'; die();
        }
    }
    
    public static function flush() {
        $buffer = ob_get_contents();
        if ($buffer) ob_end_clean();
        return $buffer;
    }
    
    // prototype [Feed arg1 arg2 /]
    // prototype with query [Feed arg1 arg2 /]{method1:arg1,arg2 method2:arg1,arg2}
    public static function replaceHTMLFeedReference($_content_with_pseudo_tag) {
        $replacedContent = preg_replace_callback('/\[(.*?[^\]])\s\/\](\{(.*?)\})?/', function($matches) {
            try {
                // $matches[1]: feed class name and ::Feed()'s arguments (separated by empty space)
                // build a collection feed, the collection should be initiated with
                // HTMLFeed when defining the feed class
                $callback = explode(' ', $matches[1]);
                $CollectionFeed = Request::feed($callback[0], array_slice($callback, 1));
                
                // $matches[3]: collection methods to be invoked with relative arguments
                if (count($matches) > 3) {
                    foreach (explode(' ', $matches[3]) as $collection_method) {
                        // method and argument block are separated by a semi-colon ':',
                        // while arguments are separated by a comma ','
                        $feed_callback = explode(':', $collection_method);
						
                        
                        // Make the method call. The method as of now can return both
                        // a feed collection or a single feed.
                        $CollectionFeed = call_user_func_array(
                            array($CollectionFeed, $feed_callback[0]), // $CollectionFeed->$feed_callback[0]
                            explode(',', (count($feed_callback)>1)?$feed_callback[1]:1)); // $args = $feed_callback[1]
                    }
                }
                
                
                // return HTML content
                if ($CollectionFeed instanceof CollectionHTML)
                    return $CollectionFeed->HTML();
                elseif ($CollectionFeed instanceof CollectionHTML_I)
                    return $CollectionFeed->toHTML();
            
            } catch (Oops $O) { if (DEBUG) throw $O; }
            // of course if $CollectionFeed does not provide the HTML output, by
            // extending HTMLFeed or implementing HTMLFeedI, a nasty message 
            // will be outputted!!!
            oops('HTML for "' . $matches[0] . '" could not be loaded');
            
        }, $_content_with_pseudo_tag);
    
        return $replacedContent;
    }
}