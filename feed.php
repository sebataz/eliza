<?php include 'eliza.php';
if (!function_exists('out')) {
    function out($_Collection = null) { 
        if ($_Collection 
        && eliza\GlobalContext::Configuration()->XMLResponse) {
            header ('Content-Type: text/xml');
            echo $_Collection->XML();
            
        } elseif ($_Collection 
        && !eliza\GlobalContext::Configuration()->XMLResponse) {
            header ('Content-Type: application/json');
            echo $_Collection->JSON();}
    }
}


try {//-----------------------------------------------------------------------//
//                               defines query                                //
//----------------------------------------------------------------------------//
if (empty($_REQUEST) && empty($_FILES)) oops(EMPTY_REQUEST);
if (class_exists('eliza\\' . key($_GET))
|| class_exists(key($_GET))) {

    $feed_name = key($_GET);
    $feed_arguments = (array) eliza\GlobalContext::Get()->defaultValue('args', array());
    $feed_id = eliza\GlobalContext::Get()->defaultValue('id');
    $feed_query_by = eliza\GlobalContext::Get()->defaultValue('by');
    $feed_query_value = eliza\GlobalContext::Get()->defaultValue('val');
    $feed_query_sort = eliza\GlobalContext::Get()->defaultValue('srt');
    $feed_query_order = eliza\GlobalContext::Get()->defaultValue('ord');
    $feed_query_limit = eliza\GlobalContext::Get()->defaultValue('lmt');
    $feed_query_offset = eliza\GlobalContext::Get()->defaultValue('off');

} else oops(NOT_DEFINED, key($_GET) ? key($_GET) : 'null');
////////////////////////////////////////////////////////////////////////////////
////////// A VALID FEED MUST BE PROVIDED IN ORDER TO HANDLE A REQUEST! /////////
////////////////////////////////////////////////////////////////////////////////


//----------------------------------------------------------------------------//
//                             handles a request                              //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                method: POST                                //
//----------------------------------------------------------------------------//
if (eliza\GlobalContext::Server()->REQUEST_METHOD == 'POST'
&& !empty($feed_arguments)) {  
    $feed_class_name = !class_exists($feed_name) ?
        'eliza\\' . $feed_name: 
        $feed_name;
        
    $Feed = !$feed_id ? 
        new $feed_class_name() : 
        eliza\Request::feed($feed_name, $feed_arguments)->getById($feed_id);
    
    // saves feed to XML file
    if ($Feed instanceof eliza\XMLDocument)
        $Feed->mergeWith(eliza\GlobalContext::Post())
             ->saveAs(FEEDS . $feed_arguments[0] . DS . $Feed->Id . '.xml');
             
    // uploads file
    if ($Feed instanceof eliza\File
    && eliza\GlobalContext::Files()->first()
    && !is_array(eliza\GlobalContext::Files()->first()->tmp_name))
        eliza\File::describeNode(eliza\GlobalContext::Files()->first()->tmp_name)
            ->uploadTo(
                ROOT . $feed_arguments[0] . DS . 
                eliza\GlobalContext::Files()->first()->name);
}



//----------------------------------------------------------------------------//
//                                method: GET                                 //
//----------------------------------------------------------------------------//
if ($feed_id) 
    $CollectionFeed = new eliza\CollectionFeed(array(
        eliza\Request::feed($feed_name, $feed_arguments)->getById($feed_id)));

else {
    $CollectionFeed = eliza\Request::feed($feed_name, $feed_arguments);
    $CollectionFeed = ($feed_query_by) ? 
        $CollectionFeed->getBy($feed_query_by, $feed_query_value) :
        $CollectionFeed;
    $CollectionFeed->sortBy($feed_query_sort, $feed_query_order);
    $CollectionFeed->limit($feed_query_limit, $feed_query_offset);
}



//----------------------------------------------------------------------------//
//                                   always                                   //
//----------------------------------------------------------------------------// 
out($CollectionFeed);



} catch (eliza\Oops $O) {//---------------------------------------------------//
//                                  fallback                                  //
//----------------------------------------------------------------------------// 
    $buffer = eliza\Presentation::flush();
    if (eliza\GlobalContext::Server()->defaultValue('HTTP_REFERER') && !DEBUG) {           
        out(new eliza\CollectionXML(array(
            'oops' => $O->getMessage(),
            'wtf' =>$O->getTrace(), 
            'output-buffer' => $buffer,
            'request' => $_REQUEST
        )));
        
    } else throw $O;    
}//--------------------------------end feed.php-------------------------------// 
//----------------------------------------------------------------------------// 

