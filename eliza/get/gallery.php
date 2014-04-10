<?php

function gallery($_path_to_dir, $thumb_size = 0, $_order_by = 'Filename', $_sort = SORT_ASC) {
    $gallery = get ('directory', array($_path_to_dir, $_order_by, $_sort));
    
    foreach ($gallery as $key => $val) {
        if ($val['IsDir']) unset($gallery[$key]);
    }
    
    
    $gallery = array_values($gallery);
    
    for ($i = 0; $i < count($gallery); $i++) {
        $gallery[$i]['Thumb'] = ($thumb_size > 0) ? Get::thumb($gallery[$i]['Url'], $thumb_size) : $gallery[$i]['Url'];
        $gallery[$i]['Html'] = '<div class="thumb" style="display:inline-block; width:'.(($thumb_size > 0) ? $thumb_size : 'auto').' ;height:'.(($thumb_size > 0) ? $thumb_size : 'auto').' ;vertical-align: middle;"><a href="' . $gallery[$i]['Url'] . '" target="_blank" class="preview"><img src="' . $gallery[$i]['Thumb'] . '" /></a></div>';
    }
    
    return $gallery;
}