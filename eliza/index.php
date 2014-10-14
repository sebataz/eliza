<?php
//----------------------------------------------------------------------------//
//                                 eliza\beta                                 //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                  response                                  //
//----------------------------------------------------------------------------//
include '../eliza/beta.php';
eliza\beta\Presentation::buffered();
if (empty($_REQUEST)) oops('you did not request anything');
try {
    $feed = class_exists(key($_GET)) ? key($_GET) : null;
    $args = isset($_GET['args']) ? $_GET['args'] : array();
    
    
    
//----------------------------------------------------------------------------//
//                                method: get                                 //
//----------------------------------------------------------------------------//  
    if (!$feed)
        $Feed = new eliza\feed\HTMLFeed();
    else
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
            
    if ($Feed->count() < 1)
        $Feed->append($feed ? new $feed() : new eliza\beta\Object());
     
     

//----------------------------------------------------------------------------//
//                                method: post                                //
//----------------------------------------------------------------------------//
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (eliza\beta\Response::hasPrivilege() && $feed) {
            
            if (count($_POST) < 1) {
                eliza\beta\Utils::deleteFile(
                    eliza\beta\GlobalContext::Configuration()->Feed->Location
                    . strtolower($feed) . DS 
                    . $Feed->first()->Id . '.xml'
                );
                
                header('Location: '. $_SERVER['HTTP_ORIGIN']);
            
            } else {
                $Feed->first()->mergeWith($_POST);
                
                eliza\beta\Utils::writeFile(
                    eliza\beta\GlobalContext::Configuration()->Feed->Location
                    . strtolower($feed) . DS 
                    . $Feed->first()->Id . '.xml',     
                    
                    '<?xml version="1.0" encoding="UTF-8"?>'
                    . $Feed->XMLFeed()
                );
            }
            
        }
    }
    
    

//----------------------------------------------------------------------------//
//                                  always                                    //
//----------------------------------------------------------------------------//      
    if (!$feed || isset($_GET['redirect'])) 
        header('Location: '. $_SERVER['HTTP_REFERER']);
<<<<<<< HEAD
                
    header('Content-Type: application/json');     
    echo '{"feed":' . $Feed->JSONFeed() . ',';
    echo '"html":' . json_encode($Feed->HTMLFeed()) . '}';
=======
>>>>>>> 8125cc8bf0ab2d3e79fd80ceb3a4f7341c49085c
    
    else {
        if (eliza\beta\GlobalContext::Configuration()->defaultValue('XMLResponse')) {
            header ("Content-Type:text/xml");
            echo '<?xml version="1.0" encoding="UTF-8"?>'; 
            echo '<feed>' . $Feed->XMLFeed() . '</feed>'; 
        } else {
            header('Content-Type: application/json');     
            echo '{"feed":' . $Feed->JSONFeed() . ',';
            echo '"html":' . json_encode($Feed->HTMLFeed()) . '}';
        }
    }

      
//----------------------------------------------------------------------------//
//                                  fallback                                  //
//----------------------------------------------------------------------------//  
} catch (eliza\beta\Oops $O) {
    if (isset($_GET['redirect'])) {
        header("Content-Type:text/html");
        throw $O;
    }
    
    eliza\beta\Presentation::flush();
    header('Content-Type: application/json');
    echo json_encode(array(
        'oops'=>$O->getMessage(),
        'wtf'=>$O->getTrace(), 
        'request'=>$_REQUEST
    ));
}