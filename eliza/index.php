<?php
include '../eliza/beta.php';
    
if (isset($_POST['lock'])) {
    eliza\beta\Session::unlock($_POST['lock']);
    header('Location: ../?' . eliza\beta\Request::querystring());
}
    
if (isset($_POST['Id'])
    && isset($_POST['Tags'])
    && isset($_POST['Title'])
    && isset($_POST['Author'])
    && isset($_POST['Headline'])
    && isset($_POST['Text'])) {
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