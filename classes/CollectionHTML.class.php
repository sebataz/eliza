<?php

namespace eliza;

interface CollectionHTML_I {
    public function toHTML();
}

class CollectionHTML extends CollectionXML {
    public function HTML() {
        $html = '';
        
        foreach ($this as $Object) {
            $html .= "\n" . '<div class="' . strtolower($Object->getClass()) . '">' . "\n";
            
            if ($Object instanceof CollectionHTML_I) $html .= $Object->toHTML();
            else
                foreach ($Object as $key => $prop)
                    if (!is_array($prop) && !is_object($prop))
                        $html .= "\t" . '<span class="' . strtolower($key) . '">'
                              . $prop . '</span>' . "\n";
                
            $html .= "\n" . '</div>' . "\n";
        }
        
        return $html;
    }
}