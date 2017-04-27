<?php

namespace eliza;

interface XMLFeedI {
    public function toXML();
}

class XMLFeed extends JSONFeed {
    public function __construct($_xml = array()) {
        if (is_array($_xml)) parent::__construct();
        if (is_string($_xml)) 
            parent::__construct(static::SimpleXMLToArray(simplexml_load_string($_xml)));
        if ($_xml instanceof SimpleXMLElement)
            parent::__construct(static::SimpleXMLToArray($_xml));
    }

    public function XMLFeed() {
        return self::CollectionToXML($this);
    }
    
    final public static function ObjectToXML($_Object) {
        $xml = '';
    
        foreach ($_Object as $prop => $value) {
            if ($value instanceof Object)
                $xml .= '<' . $prop . '>' . "\n"
                    . self::ObjectToXML($value)
                    . '</' . $prop . '>' . "\n";
            elseif ($value instanceof Collection)
                $xml .= '<' . $prop . '>' . "\n"
                    . self::CollectionToXML($value)
                    . '</' . $prop . '>' . "\n";
            elseif (is_array($value)) {
                foreach ($value as $val)
                    $xml .= "\t" . '<' . $prop . '><![CDATA['
                        . $value . ']]></' . $prop . '>' . "\n";
            }
            else
                $xml .= "\t" . '<' . $prop . '><![CDATA['
                    . $value . ']]></' . $prop . '>' . "\n";
        }
        
        return $xml;
    }
    
    final public static function CollectionToXML($_Collection) {
        $xml = '';
        
        foreach ($_Collection as $Object) {
            $xml .= "\n" . '<' . get_class($Object) . '>' . "\n";
            $xml .= self::ObjectToXML($Object);
            $xml .= '</' . get_class($Object) . '>' . "\n";
        }
        
        return $xml;
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