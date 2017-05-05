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
//                                handles POST                                //
//----------------------------------------------------------------------------//
if (eliza\GlobalContext::Server()->REQUEST_METHOD == 'POST') {  
    $Feed = $feed_query_id ? 
        eliza\Request::feed($feed_name, $feed_arguments)
            ->getById($feed_query_id):
        new $feed_class();
    
    // saves feed to XML file
    if ($Feed instanceof eliza\XMLDocument)
        $Feed->mergeWith(eliza\GlobalContext::Post())
            ->saveAs(
                FEEDS . strtolower($Feed->getClass()) . DS . $Feed->Id . '.xml'
            );
             
    // uploads file
    if ($Feed instanceof eliza\File
    && eliza\GlobalContext::Files()->first()
    && !is_array(eliza\GlobalContext::Files()->first()->tmp_name)) {
        $upload_path = !empty($feed_arguments) ?
            ROOT . $feed_arguments[0] : 
            FEEDS . strtolower($Feed->getClass());
            
        eliza\File::describeNode(
            eliza\GlobalContext::Files()->first()->tmp_name)
            ->uploadAs(
                $upload_path . DS
                . eliza\GlobalContext::Files()->first()->name);
    }
}



//----------------------------------------------------------------------------//
//                                handles GET                                 //
//----------------------------------------------------------------------------//
if ($feed_query_id) {
    $Collection = new eliza\CollectionFeed(array(
        eliza\Request::feed($feed_name, $feed_arguments)
            ->getById($feed_query_id)));

}else {
    $Collection = eliza\Request::feed($feed_name, $feed_arguments);
    
    if ($feed_query_by)
        $Collection->getBy($feed_query_by, $feed_query_value);
    
    if ($feed_query_sort)
        $Collection->sortBy($feed_query_sort, $feed_query_order);
        
    if ($feed_query_limit)
        $Collection->limit($feed_query_limit, $feed_query_offset);
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
            'request' => $_REQUEST
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