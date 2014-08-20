<?php
//----------------------------------------------------------------------------//
//                                 eliza\beta                                 //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                  response                                  //
//----------------------------------------------------------------------------//
include '../eliza/beta.php';
if (empty($_REQUEST)) oops();
try {
    $feed = class_exists(key($_GET)) ? key($_GET) : null;
    $args = $feed ? array_slice($_GET, 1) : array();
    
    

//----------------------------------------------------------------------------//
//                                method: post                                //
//----------------------------------------------------------------------------//
    if (eliza\beta\Response::hasPrivilege() && count($_POST)) {
        if ($feed) $Feed = new eliza\feed\XMLFeed(array(new $feed($_POST)));
        if ($feed && eliza\beta\Utils::writeFile(
            ROOT . strtolower($feed) . DS . $Feed->first()->Id . '.xml',
            $Feed->XMLFeed())
        ) {
            header('Location: ../?id=' . $Feed->first()->Id);
            exit();
        }            
    }
    
    // if post is not feed go back to same querystring
    if (count($_POST))
        header('Location: ../?' . eliza\beta\GlobalContext::Querystring());
        
        
    
//----------------------------------------------------------------------------//
//                                method: get                                 //
//----------------------------------------------------------------------------//
    echo !$feed ? 'undefined':
        eliza\beta\Response::Feed($feed, $args)
            ->Q(isset($_GET['q']) ? $_GET['q'] : false)
            ->sortBy(isset($_GET['sort']) ? $_GET['sort'] : null)
            ->limit(isset($_GET['limit']) ? $_GET['limit'] : null)
            ->JSONFeed();
            
            
            
//----------------------------------------------------------------------------//
//                                  fallback                                  //
//----------------------------------------------------------------------------//  
} catch (eliza\beta\Oops $O) {
    if (DEBUG) throw $O;
    else header('Location: ../?Oops');
}