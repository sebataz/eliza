<?php

namespace eliza\feed;

interface HTMLFeedI {
    public function toHTML();
}

class HTMLFeed extends JSONFeed {
    public function HTMLFeed() {
        $html = '';
        
        foreach ($this as $Object) {
            $html .= "\n" . '<div class="' . strtolower(get_class($Object)) . '">' . "\n";
            
            if ($Object instanceof HTMLFeedI) $html .= $Object->toHTML();
            else
                foreach ($Object as $key => $prop)
                    if (!is_array($prop) && !is_object($prop))
                        $html .= "\t" . '<span class="' . strtolower($key) . '">'
                              . $prop . '</span>' . "\n";
                
            $html .= '</div>' . "\n";
        }
        
        return $html;
    }
}