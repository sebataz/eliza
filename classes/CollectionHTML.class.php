<?php

namespace eliza;

interface CollectionHTML_I {
    public function toHTML();
}

class CollectionHTML extends CollectionXML {
    public function HTML() {
        return '<div class="' . basename(get_called_class()) . '">' 
            . self::ObjectToHTML($this) 
            . '</div>';
    }
    
    final public static function ObjectToHTML($_Object) {
        $html = '<div class="' . basename(get_class($_Object)) . '">' . "\n";
    
        foreach ($_Object as $key => $value) {
            $tag_open = '<span class="' . (is_string($key) ? $key : '') . '">';
            $tag_close = '</span>';
        
            if ($value instanceof Object)
                $html .= $tag_open . self::ObjectToHTML($value) . $tag_close;
                
            elseif ($value instanceof CollectionHTML_I)
                $html .= $tag_open . $value->toHTML() . $tag_close;
                
            elseif ($value instanceof CollectionHTML)
                $html .= $tag_open . $value->HTML() . $tag_close;
                
            elseif (is_string($value)
            || is_int($value)
            || is_bool($value))
                $html .= $tag_open . $value . $tag_close;
                
            elseif (is_array($value))
                oops(OOPS);
        }
        
        return $html . '</div>' . "\n";
    }
}