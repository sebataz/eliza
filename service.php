<?php include 'eliza.php';


try {//-----------------------------------------------------------------------//
//                               defines query                                //
//----------------------------------------------------------------------------//
if (empty($_REQUEST) && empty($_FILES)) oops(EMPTY_REQUEST);
if (class_exists('eliza\\' . key($_GET))
|| class_exists(key($_GET))) {

    // feed definition
    $feed_name = key($_GET);
    $feed_class = class_exists($feed_name) ? $feed_name : 'eliza\\' . $feed_name;
    $feed_arguments = (array) eliza\GlobalContext::Get()->defaultValue('args', array());
    
    // query feed GET variables
    $feed_query_id = eliza\GlobalContext::Get()->defaultValue('id');
    $feed_query_by = eliza\GlobalContext::Get()->defaultValue('by');
    $feed_query_value = eliza\GlobalContext::Get()->defaultValue('val');
    $feed_query_sort = eliza\GlobalContext::Get()->defaultValue('srt');
    $feed_query_order = eliza\GlobalContext::Get()->defaultValue('ord');
    $feed_query_limit = eliza\GlobalContext::Get()->defaultValue('lmt');
    $feed_query_offset = eliza\GlobalContext::Get()->defaultValue('off');

} else oops(NOT_DEFINED, key($_GET) ? key($_GET) : 'null');
////////////////////////////////////////////////////////////////////////////////
///////// A VALID FEED MUST BE PROVIDED IN ORDER TO HANDLE A REQUEST! //////////
////////////////////////////////////////////////////////////////////////////////



//----------------------------------------------------------------------------//
//                                handles GET                                 //
//----------------------------------------------------------------------------//
$Collection = eliza\Request::feed($feed_name, $feed_arguments);

if ($feed_query_id !== null)
    $Collection->getById($feed_query_id);
    
if ($feed_query_by && !$feed_query_id)
    $Collection->getBy($feed_query_by, $feed_query_value);

if ($feed_query_sort && !$feed_query_id)
    $Collection->sortBy($feed_query_sort, $feed_query_order);
    
if ($feed_query_limit && !$feed_query_id)
    $Collection->limit($feed_query_limit, $feed_query_offset);
    
if ($Collection->count() <= 0)
    $Collection->append(new $feed_class());  

//----------------------------------------------------------------------------//
//                                handles POST                                //
//----------------------------------------------------------------------------//
if (eliza\GlobalContext::Server()->REQUEST_METHOD == 'POST') {    
            
                
    // uploads all posted files
    $upload_path = !empty($feed_arguments) ? 
        $feed_arguments[0] . DS : 
        'feeds' . strtolower($Feed->getClass()) . DS;
        
    foreach (eliza\GlobalContext::Files() as $Uploads)
        foreach ($Uploads as $Upload)
            $Collection->append(eliza\FileContent::uploadFile(
                $Upload->TmpName,
                ROOT . $upload_path . $Upload->Name));
                
    // Feeds operations
    foreach ($Collection as $Feed) { // this foreach may cause nasty surprises!
    
        // deletes a feed
        if (!eliza\GlobalContext::Post()->count()
        && !eliza\GlobalContext::Files()->count()) {
            $Feed->deleteFromDisk();
        
        // saves feed to XML file
        } elseif ($Feed instanceof eliza\XMLDocument) {
            $Feed->mergeWith(eliza\GlobalContext::Post())->saveToDisk();
    
        }
        
    }
}

} catch (eliza\Oops $O) {//---------------------------------------------------//
//                                  fallback                                  //
//----------------------------------------------------------------------------// 
    $buffer = eliza\Presentation::flush();
    if (eliza\GlobalContext::Server()->defaultValue('HTTP_REFERER') && !DEBUG) {           
        $Collection = new eliza\CollectionXML(array(
            'oops' => $O->getMessage(),
            'wtf' => $O->getTrace(), 
            'output-buffer' => $buffer,
            'request-method' => eliza\GlobalContext::Server()->REQUEST_METHOD,
            'get' => $_GET,
            'post' => $_POST,
            'files' => $_FILES
        ));
        
    } else throw $O;    
}//---------------------------------------------------------------------------//
//                                 echo stuff                                 //
//----------------------------------------------------------------------------// 
eliza\Presentation::buffered();
        
if ($Collection
&& eliza\GlobalContext::Configuration()->XMLResponse) {
    header ('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>'; 
    if ($Collection instanceof eliza\CollectionFeed)
        echo sprintf('<Feed>%s</Feed>',
            $Collection->XML());
    else
        echo $Collection->XML();
    
} elseif ($Collection
&& !eliza\GlobalContext::Configuration()->XMLResponse) {
    header ('Content-Type: application/json');
    if ($Collection instanceof eliza\CollectionFeed)
        echo sprintf('{"feed":%s,"html":%s}',
            $Collection->JSON(),
            json_encode($Collection->HTML()));
    else
        echo $Collection->JSON();
}//-------------------------------end service.php-----------------------------// 
//----------------------------------------------------------------------------// 