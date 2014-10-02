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
        echo json_encode(array(array('collected'=>(array)$Feed)));
        
        die();
    }
    
        
    
//----------------------------------------------------------------------------//
//                                method: get                                 //
//----------------------------------------------------------------------------//       
    if (!$feed) die('none');
    
    $Feed = eliza\beta\Response::Feed($feed, $args)
        ->Q(isset($_GET['q']) ? $_GET['q'] : false)
        ->filterBy(
            isset($_GET['by']) ? $_GET['by'] : false,
            isset($_GET['val']) ? $_GET['val'] : null
        )
        ->sortBy(
            isset($_GET['srt']) ? $_GET['srt'] : null,
            isset($_GET['ord']) ? $_GET['ord'] : null
        )
        ->limit(
            isset($_GET['lmt']) ? $_GET['lmt'] : null,
            isset($_GET['off']) ? $_GET['off'] : null
        );
        
    echo '{"feed":' . $Feed->JSONFeed() . ',';
    echo '"html":' . json_encode($Feed->HTMLFeed()) . '}';
            
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