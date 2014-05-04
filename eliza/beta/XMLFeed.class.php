<?php

namespace eliza\feed;

interface XMLFeedI {
    public function toXML();
}

class XMLFeed extends JSONFeed {
    public function XMLFeed() {
        @header('Content-type: application/xml');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        
        foreach ($this as $Object) {
            $xml .= "\n" . '<' . strtolower(get_class($Object)) . '>' . "\n";
            
            if ($Object instanceof XMLFeedI) $xml .= $Object->toXML();
            else
                foreach ($Object as $prop => $value)
                    if (!is_array($value) && !is_object($value))
                        $xml .= "\t" . '<' . strtolower($prop) . '><![CDATA['
                              . $value . ']]></' . strtolower($prop) . '>' . "\n";
                
            $xml .= '</' . strtolower(get_class($Object)) . '>' . "\n";
        }
        
        return $xml;
    }
}