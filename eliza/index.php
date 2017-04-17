<?php
//----------------------------------------------------------------------------//
//                                 eliza\beta                                 //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                  response                                  //
//----------------------------------------------------------------------------//
include '../eliza/beta.php';

if (DEBUG) var_dump(array('GET'=>$_GET, 'POST'=>$_POST, 'FILES'=>$_FILES)); 
if (DEBUG) die();

eliza\beta\Presentation::buffered();
eliza\beta\Response::hasPrivilege();

if (empty($_REQUEST) && empty($_FILES)) oops('you did not request anything');

try {
    $feed = class_exists(key($_GET)) ? key($_GET) : null;
    $args = isset($_GET['args']) ? $_GET['args'] : array();
    
    
//----------------------------------------------------------------------------//
//                                method: get                                 //
//----------------------------------------------------------------------------//  
    if (!$feed)
        $Feed = new eliza\feed\HTMLFeed();
    else
        $Feed = eliza\feed\Feed::__callStatic($feed, $args)
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
     
     

//----------------------------------------------------------------------------//
//                                method: post                                //
//----------------------------------------------------------------------------//
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($feed) {
                   
            // create a new feed if there are none matched
            if ($Feed->count() < 1)
                $Feed->append($feed ? new $feed() : new eliza\beta\Object());
            
            // delete a feed if POST is empty
            if (count($_POST) < 1) {
                $Feed->first()->delete();
                
            
            } else {
                $Feed->first()->mergeWith($_POST);
                $Feed->first()->save();
            }
            
        }
        
        /*
         * File upload
         */
        if (!empty($_FILES)) {
            if (eliza\beta\Response::hasPrivilege()) {
                if (!file_exists(ROOT . $_POST['location']))
                    mkdir(ROOT . $_POST['location']);
            
                move_uploaded_file($_FILES['file']['tmp_name'],
                    ROOT . $_POST['location'] . DS .
                    $_FILES['file']['name']);
                
                // return uploaded file
                $feed = 'Node';
                $Feed = eliza\feed\Feed::Node($_POST['location'])
                    ->filterBy('Filename', $_FILES['file']['name'])
                    ->limit(1);
            } else
                oops('you cannot upload files');
        }
    }
    
    

//----------------------------------------------------------------------------//
//                                  always                                    //
//----------------------------------------------------------------------------//      
    if (!$feed || isset($_GET['redirect'])) 
        header('Location: '. $_SERVER['HTTP_REFERER']);
    
    else {
        if (
            eliza\beta\GlobalContext::Configuration()
            ->defaultValue('XMLResponse')
        ) {
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