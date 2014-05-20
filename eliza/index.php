<?php
include '../eliza/beta.php';
    
if (isset($_POST['lock'])) {
    eliza\beta\GlobalContext::Session(array('unlock' => $_POST['lock']));
    header('Location: ../?' . eliza\beta\GlobalContext::Querystring());
}
    
if (isset($_POST['Id'])
    && isset($_POST['Tags'])
    && isset($_POST['Title'])
    && isset($_POST['Author'])
    && isset($_POST['Headline'])
    && isset($_POST['Text'])) {
    
    eliza\beta\Response::privileged();
    
    $_POST['Date'] = time();
    $_POST['Draft'] = (bool) $_POST['Draft'];
    $SaveArticle = new eliza\feed\XMLFeed(array(new Article($_POST)));
                
    if (eliza\beta\Utils::writeFile(ROOT . 'articles' . DS . $SaveArticle->first()->Id . '.xml', $SaveArticle->XMLFeed()))
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