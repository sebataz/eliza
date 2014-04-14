<?php

class Tag extends eliza\beta\Feed {
    public $Tag = '';
    public $Count = 0;
    public $Weight = 0;
    public $Size = 0; //size in em

    public static function Feed($_callback, $_suggest_for = null, $_create_tag_cloud = 1) {
        $Tags = new eliza\feed\JSONFeed();
        
        $tmp_tags = array();
        foreach (eliza\beta\Response::Feed($_callback, array()) as $Object) {
            foreach ($Object->Tags as $tag) {
                if ($_suggest_for)
                    if (!preg_match('/^' . $_suggest_for . '/i', $tag)) 
                        continue;
                $tmp_tags[$tag] = array_key_exists($tag, $tmp_tags) ? $tmp_tags[$tag] + 1 : 1 ;
            }
        }
        
        if (!(bool)$_create_tag_cloud) {
            $tags = array_keys($tmp_tags);
            arsort($tags);
            return new eliza\feed\JSONFeed(array_values($tags));
        }
        
        
        arsort($tmp_tags);
        foreach ($tmp_tags as $tag => $n) {
            $Tag = new self();
            $Tag->Tag = $tag;
            $Tag->Count = $n;
            $Tag->Weight = $n / count($tmp_tags);
            $Tag->Size = ($n / count($tmp_tags)) *  10.4 + 0.7 > 3.3 ? 3.3 : ($n / count($tmp_tags)) * 10.4 + 0.7;
            $Tags->append($Tag);
        }
        
        return $Tags->limit(137);
    }
}