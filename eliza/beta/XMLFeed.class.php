<?php

namespace eliza\feed;

interface XMLFeedI {
    public function toXML();
}

class XMLFeed extends JSONFeed {
    public function XMLFeed() {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . self::CollectionToXML($this);
            
        return $xml;
    }
    
    final public static function ObjectToXML($_Object) {
        $xml = '';
    
        foreach ($_Object as $prop => $value) {
            if ($value instanceof \eliza\beta\Object)
                $xml .= '<' . strtolower($prop) . '>' . "\n"
                    . self::ObjectToXML($value)
                    . '</' . strtolower($prop) . '>' . "\n";
            elseif ($value instanceof \eliza\beta\Collection)
                $xml .= '<' . strtolower($prop) . '>' . "\n"
                    . self::CollectionToXML($value)
                    . '</' . strtolower($prop) . '>' . "\n";
            elseif (is_array($value)) {
                foreach ($value as $val)
                    $xml .= "\t" . '<' . strtolower($prop) . '><![CDATA['
                        . $value . ']]></' . strtolower($prop) . '>' . "\n";
            }
            else
                $xml .= "\t" . '<' . strtolower($prop) . '><![CDATA['
                    . $value . ']]></' . strtolower($prop) . '>' . "\n";
        }
        
        return $xml;
    }
    
    final public static function CollectionToXML($_Collection) {
        $xml = '';
        
        foreach ($_Collection as $Object) {
            $xml .= "\n" . '<' . strtolower(get_class($Object)) . '>' . "\n";
            $xml .= self::ObjectToXML($Object);
            $xml .= '</' . strtolower(get_class($Object)) . '>' . "\n";
        }
        
        return $xml;
    }
}