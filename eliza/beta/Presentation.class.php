<?php 

namespace eliza\beta;

class Presentation {
    public static function buffered() {
        ob_start();
        register_shutdown_function(array(get_called_class(), 'show')); 
    }
    
    public static function show() {
        $buffer = self::flush();
        $buffer = self::replaceHTMLFeedReference($buffer);
        echo $buffer; die();
    }
    
    public static function flush() {
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    
    // prototype [Feed arg1 arg2 /]
    // prototype with query [Feed arg1 arg2]{method1:arg1,arg2 method2:arg1,arg2}
    public static function replaceHTMLFeedReference($_content) {
        $replacedContent = '';
        $replacedContent = preg_replace_callback('/\[(.*)\s\/\](\{(.*)\})?/', function($matches) {

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
        
        }, $_content);
    
        return $replacedContent;
    }
}