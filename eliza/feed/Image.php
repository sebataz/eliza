<?php

class Image extends eliza\beta\Feed {
    public static function Feed($_gallery, $thumb_size = 0) {
        $Gallery = eliza\beta\Feed::Node($_gallery);
    
        foreach ($Gallery as $key => $Node)
            if ($Node->IsDir)
                $Gallery->unset($key);
        
        foreach ($Gallery as $Image) {
            $Image->Thumb = ($thumb_size > 0) 
                          ? eliza\beta\Thumbnail::create($Image->Url, $thumb_size) : $Image->Url;
                          
            $Image->Html = '<div class="thumb" style="display:inline-block; width:'
                         . (($thumb_size > 0) ? $thumb_size : 'auto')
                         . ' ;height:'.(($thumb_size > 0) ? $thumb_size : 'auto')
                         . ' ;vertical-align: middle;"><a href="' . $Image->Url 
                         . '" target="_blank" class="preview"><img src="' . $Image->Thumb 
                         . '" /></a></div>';
        }
        
        return $Gallery;
    }
}