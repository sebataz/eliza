<?php
//----------------------------------------------------------------------------//
//                                 eliza\beta                                 //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                  response                                  //
//----------------------------------------------------------------------------//
include '../eliza/beta.php';
if (empty($_REQUEST)) oops('you did not request anything');
try {
    $feed = class_exists(key($_GET)) ? key($_GET) : null;
    $args = $feed ? array_slice($_GET, 1) : array();
    

//----------------------------------------------------------------------------//
//                                method: post                                //
//----------------------------------------------------------------------------//
    if (count ($_POST)) {
        if (eliza\beta\Response::hasPrivilege()) {
            if ($feed) {
                $Feed = new eliza\feed\XMLFeed(array(new $feed($_POST)));
                eliza\beta\Utils::writeFile(
                    eliza\beta\GlobalContext::Configuration()->Feed->Location
                    . strtolower($feed) . DS 
                    . $Feed->first()->Id . '.xml',     
                    $Feed->XMLFeed()
                );
            }
        }
            
        if (!eliza\beta\GlobalContext::Globals()->Get->defaultValue('verbose'))
            header('Location: '. $_SERVER['HTTP_REFERER']);
            
        header('Content-Type: application/json');
        echo json_encode(array(array('collected'=>$Feed->JSONFeed())));
        
        die();
    }
    
        
    
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
    if (!eliza\beta\GlobalContext::Globals()->Get->defaultValue('verbose')) 
        throw $O;
    
    header('Content-Type: application/json');
    echo json_encode(array(
        'oops'=>$O->getMessage(),
        'wtf'=>$O->getTrace(), 
        'request'=>$_REQUEST
    ));
}