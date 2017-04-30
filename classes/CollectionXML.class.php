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
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<Feed>\n";
        
        foreach ($this as $Object)
            $xml .= self::ObjectToXML($Object);
        
        return $xml . '</Feed>';
    }
    
    final public static function ObjectToXML($_Object) {
        $xml = '<' . $_Object->getClass() . '>' . "\n";
    
        foreach ($_Object as $prop => $value) {
        
            if ($value instanceof Object)
                $xml .= '<' . $prop . '>' . "\n"
                    . self::ObjectToXML($value)
                    . '</' . $prop . '>' . "\n";
                    
            elseif ($value instanceof Collection)
                $xml .= "\t" . '<' . $prop . '>'
                     . $value->XML() . '</' . $prop . '>' . "\n";

            elseif (is_array($value))
                foreach ($value as $val)
                    $xml .= "\t" . '<' . $prop . '><![CDATA['
                        . $value . ']]></' . $prop . '>' . "\n";
           
            else
                $xml .= "\t" . '<' . $prop . '><![CDATA['
                    . $value . ']]></' . $prop . '>' . "\n";
        }
        
        return $xml . '</' . $_Object->getClass() . '>' . "\n";
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