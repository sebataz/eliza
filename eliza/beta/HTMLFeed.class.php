<?php

namespace eliza\feed;

interface HTMLFeedI {
    public function toHTML();
}

class HTMLFeed extends JSONFeed {
    public function HTMLFeed() {
        foreach ($this as $Object) {
            echo "\n", '<div class="', strtolower(get_class($Object)), '">', "\n";
            
            if ($Object instanceof HTMLFeedI) echo $Object->toHTML();
            else
                foreach ($Object as $key => $prop)
                    if (!is_array($prop) && !is_object($prop))
                        echo "\t", '<span class="', strtolower($key), '">', $prop, '</span>', "\n";
                
            echo '</div>', "\n";
        }
    }
}