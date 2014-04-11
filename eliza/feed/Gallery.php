<?php

class Gallery extends eliza\beta\JSONFeed {
    public static function Feed($_path_to_dir, $thumb_size = 0, $_order_by = 'Filename', $_sort = SORT_ASC) {
        $Gallery = eliza\beta\Response::Ls($_path_to_dir, $_order_by, $_sort);
    
        
        foreach ($Gallery as $key => $Node)
            if ($Node->IsDir)
                $Gallery->unset($key);
        
        
        $gallery = array_values($gallery);
        
        foreach ($Gallery as $Image) {
            $Image->Thumb = ($thumb_size > 0) ? eliza\beta\Response::Thumb($Image->Url, $thumb_size) : $Image->Url;
            $Image->Html = '<div class="thumb" style="display:inline-block; width:'.(($thumb_size > 0) ? $thumb_size : 'auto').' ;height:'.(($thumb_size > 0) ? $thumb_size : 'auto').' ;vertical-align: middle;"><a href="' . $Image->Url . '" target="_blank" class="preview"><img src="' . $Image->Thumb . '" /></a></div>';
        }
        
        return $Gallery;
    }
}