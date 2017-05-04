<?php

namespace eliza;

interface CollectionXML_I {
    public function toXML();
}

class CollectionXML extends CollectionJSON {
    public function __construct($_xml = array()) {
        if (is_array($_xml)) parent::__construct($_xml);
        if (is_string($_xml)) 
            parent::__construct(static::SimpleXMLToArray(simplexml_load_string($_xml)));
        if ($_xml instanceof SimpleXMLElement)
            parent::__construct(static::SimpleXMLToArray($_xml));
    }

    public function XML() {
        return self::ObjectToXML($this);
    }
    
    final public static function ObjectToXML($_Object) {
        $xml =  "\n" . '<' . basename(get_class($_Object)) . '>' . "\n";
    
        foreach ($_Object as $key => $value) {
            $tag_open = '<' . (is_string($key) ? $key : 'Value') . '>';
            $tag_close = '</' . (is_string($key) ? $key : 'Value') . '>' . "\n";
                
            if ($value instanceof CollectionXML_I)
                $xml .= $tag_open . $value->toXML() . $tag_close . "\n";
        
            elseif ($value instanceof Object)
                $xml .= $tag_open . self::ObjectToXML($value) . $tag_close . "\n";
                
            elseif ($value instanceof CollectionXML)
                $xml .= $tag_open . $value->XML() . $tag_close . "\n";
                
            elseif (is_string($value)
            || is_int($value)
            || is_bool($value)
            || is_null($value))
                $xml .= $tag_open . '<![CDATA[' . $value . ']]>' . $tag_close . "\n";
                
            elseif (is_array($value))
                $xml .= $tag_open . (new self($value))->XML() . $tag_close;
            
            else
                oops(OOPS);
        }
        
        return $xml . '</' . basename(get_class($_Object)) . '>' . "\n";
    }
    
    public static function SimpleXMLToArray($_Xml) {
        $array = array();
    
        foreach ($_Xml as $El) {
            $children = get_object_vars($El);
        
            if (!empty($children))
                $array[$El->getName()][] = self::SimpleXMLToArray($El);
            
            else
                $array[$El->getName()] = trim($El);
        
        }
        
        return $array;
    }
}