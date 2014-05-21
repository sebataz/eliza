<?php
include '../eliza/beta.php';
if (!count($_REQUEST)) oops();
try {

// METHOD: GET
    $feed = key($_GET);
    $args = array_slice($_GET, 1);
    
    echo eliza\beta\Response::Feed($feed, $args)
        ->Q(isset($_GET['q']) ? $_GET['q'] : false)
        ->sortBy(isset($_GET['sort']) ? $_GET['sort'] : null)
        ->limit(isset($_GET['limit']) ? $_GET['limit'] : null)
        ->JSONFeed();

        
// METHOD: POST
    if (eliza\beta\Response::hasPrivilege() && count($_POST)) {
        if (class_exists($feed)) {
            $Feed = new eliza\feed\XMLFeed(array(new $feed($_POST)));
            if (
                eliza\beta\Utils::writeFile(ROOT . strtolower($feed) . DS . $Feed->first()->Id . '.xml',
                $Feed->XMLFeed())
            )
                header('Location: ../?id=' . $SaveArticle->first()->Id);
        }
    }
    
    
} catch (eliza\beta\Oops $O) {
    header('Location: ../?' . eliza\beta\GlobalContext::Querystring() . '&Oops');
}