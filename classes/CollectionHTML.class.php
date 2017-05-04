<?php

namespace eliza;

interface CollectionHTML_I {
    public function toHTML();
}

class CollectionHTML extends CollectionXML {
    public function HTML() {
        return self::ObjectToHTML($this);
    }
    
    final public static function ObjectToHTML($_Object) {
        $html = '';
        
        foreach ($_Object as $key => $value) {
            $tag_open = '<span class="' . (is_string($key) ? $key : '') . '">';
            $tag_close = '</span>' . "\n";
        
            if ($value instanceof CollectionHTML_I)
                $html .= $value->toHTML();
                
            elseif ($value instanceof Object)
                $html .= $tag_open . self::ObjectToHTML($value) . $tag_close;
                
            elseif ($value instanceof CollectionHTML)
                $html .= $tag_open . $value->HTML() . $tag_close;
                
            elseif (is_string($value)
            || is_int($value)
            || is_bool($value)
            || is_null($value))
                $html .= $tag_open . $value . $tag_close;
                
            elseif (is_array($value))
                $html .= $tag_open . (new self($value))->HTML() . $tag_close;
                
            else
                oops(OOPS);
        }
        
        return $html;
    }
}