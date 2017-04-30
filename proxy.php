<?php
//----------------------------------------------------------------------------//
//                                 proxy.php                                  //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                  response                                  //
//----------------------------------------------------------------------------//
include 'eliza.php';

if (0) var_dump(array('GET'=>$_GET, 'POST'=>$_POST, 'FILES'=>$_FILES)); 

eliza\Presentation::buffered();
eliza\Response::hasPrivilege();

if (empty($_REQUEST) && empty($_FILES)) oops(REQUEST_EMPTY);

try {
    $feed = class_exists('eliza\\'.key($_GET)) ? key($_GET) : null;
    $args = isset($_GET['args']) ? $_GET['args'] : array();
    
//----------------------------------------------------------------------------//
//                                method: get                                 //
//----------------------------------------------------------------------------//  
    if (!$feed)
        $Feed = new eliza\CollectionFeed();
    else
        $Feed = eliza\Response::query($feed, $args);
        
        if (isset($_GET['id']))
            $Feed->getById($_GET['id']);
            
        $Feed->sortBy(
            isset($_GET['srt']) ? $_GET['srt'] : null,
            isset($_GET['ord']) ? $_GET['ord'] : null);
            
        $Feed->limit(
            isset($_GET['lmt']) ? $_GET['lmt'] : null,
            isset($_GET['off']) ? $_GET['off'] : null);
     
     
//----------------------------------------------------------------------------//
//                                method: post                                //
//----------------------------------------------------------------------------//
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($feed) {
                   
            // create a new feed if there are none matched
            if ($Feed->count() < 1)
                $Feed->append($feed ? new $feed() : new eliza\Object());
            
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
            if (eliza\Response::hasPrivilege(array(), 'UploadFile')) {
                if (!file_exists(ROOT . $_POST['location']))
                    mkdir(ROOT . $_POST['location']);
            
                move_uploaded_file($_FILES['file']['tmp_name'],
                    ROOT . $_POST['location'] . DS .
                    $_FILES['file']['name']);
                
                // return uploaded file
                $feed = 'Node';
                $Feed = eliza\Feed::Node($_POST['location'])
                    ->getBy('Filename', $_FILES['file']['name'])
                    ->limit(1);
            } else
                oops(PERMISSION_DENIED_UPLOAD);
        }
    }
    
    

//----------------------------------------------------------------------------//
//                                  always                                    //
//----------------------------------------------------------------------------//      
    if (!$feed && isset($_GET['redirect'])) 
        header('Location: '. $_SERVER['HTTP_REFERER']);
    
    else {
        if (
            eliza\GlobalContext::Configuration()
            ->defaultValue('XMLResponse')
        ) {
            header ("Content-Type:text/xml");
            echo '<?xml version="1.0" encoding="UTF-8"?>'; 
            echo '<feed>' . $Feed->XML() . '</feed>'; 
        } else {
            header('Content-Type: application/json');     
            echo '{"feed":' . $Feed->JSON() . ',';
            echo '"html":' . json_encode($Feed->HTML()) . '}';
        }
    }

      
//----------------------------------------------------------------------------//
//                                  fallback                                  //
//----------------------------------------------------------------------------//  
} catch (eliza\Oops $O) {
    if (isset($_GET['redirect'])) {
        header("Content-Type:text/html");
        throw $O;
    }
    
    eliza\Presentation::flush();
    header('Content-Type: application/json');
    echo json_encode(array(
        'oops'=>$O->getMessage(),
        'wtf'=>$O->getTrace(), 
        'request'=>$_REQUEST
    ));
}