<?php
include '../eliza/beta.php';
    
if (count($_POST)) {
    $SaveArticle = new eliza\feed\XMLFeed(array(new Article($_POST)));
    
    if (null !== ($handle = fopen(ROOT . 'articles' . DS . $SaveArticle->first()->Id . '.xml', 'w')))
            if ((bool)fwrite($handle, $SaveArticle->XMLFeed()))
                header('Location: ../?id=' . $SaveArticle->first()->Id);
}

if (!count($_GET)) oops();

$feed = key($_GET);
$args = array_slice($_GET, 1);

echo eliza\beta\Response::Feed($feed, $args)
    ->Q(isset($_GET['q']) ? $_GET['q'] : false)
    ->sortBy(isset($_GET['sort']) ? $_GET['sort'] : null)
    ->limit(isset($_GET['limit']) ? $_GET['limit'] : null)
    ->JSONFeed();