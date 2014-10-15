<?php
//----------------------------------------------------------------------------//
//                                 eliza\beta                                 //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                                rss response                                //
//----------------------------------------------------------------------------//
include '../eliza/beta.php';
eliza\beta\Presentation::buffered();
if (empty($_REQUEST)) oops('you did not request anything');
try {
    $feed = class_exists(key($_GET)) ? key($_GET) : null;
    $args = isset($_GET['args']) ? $_GET['args'] : array();
    
    
    
//----------------------------------------------------------------------------//
//                                 last feed                                  //
//----------------------------------------------------------------------------//  
    if (!$feed)
        $Feed = new eliza\feed\HTMLFeed();
    else
        $Feed = eliza\beta\Response::Feed($feed, $args)
            ->sortBy('Datetime', SORT_DESC)
            ->limit(1, 10);
            
     
     

//----------------------------------------------------------------------------//
//                             rss presentation                               //
//----------------------------------------------------------------------------//   

    $xmlns = 'xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:admin="http://webns.net/mvcb/"
        xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"'
    
    $rss = '<?xml version="1.0" encoding="utf-8"?>' . "\n"
        . '<rss version="2.0"' . $xmlns . '>' . "\n";
 
    // channel required properties
    $rss .= '<channel>' . "\n";
        . '<title>'
        . eliza\beta\GlobalContext::Configuration()->XMLFeed->Title
        . '</title>' . "\n"
        . '<link>'
        . eliza\beta\GlobalContext::Configuration()->XMLFeed->Link
        . '</link>' . "\n"
        . '<description>'
        . eliza\beta\GlobalContext::Configuration()->XMLFeed->Description
        . '</description>' . "\n"
 
    // channel optional properties
    $rss .= '<language>' 
        . eliza\beta\GlobalContext::Configuration()->XMLFeed->defaultValue('Language')
        . '</language>' . "\n"
        . 
 
    // channel optional properties
    if(array_key_exists("language", $this->channel_properties)) {
      $rss .= '<language>' . $this->channel_properties["language"] . '</language>' . "\n";
    }
    if(array_key_exists("image_title", $this->channel_properties)) {
      $rss .= '<image>' . "\n";
      $rss .= '<title>' . $this->channel_properties["image_title"] . '</title>' . "\n";
      $rss .= '<link>' . $this->channel_properties["image_link"] . '</link>' . "\n";
      $rss .= '<url>' . $this->channel_properties["image_url"] . '</url>' . "\n";
      $rss .= '</image>' . "\n";
    }
 
    // get RSS channel items
    $now =  date("YmdHis"); // get current time  // configure appropriately to your environment
    $rss_items = $this->get_feed_items($now);
 
    foreach($rss_items as $rss_item) {
      $rss .= '<item>' . "\n";
      $rss .= '<title>' . $rss_item['title'] . '</title>' . "\n";
      $rss .= '<link>' . $rss_item['link'] . '</link>' . "\n";
      $rss .= '<description>' . $rss_item['description'] . '</description>' . "\n";
      $rss .= '<pubDate>' . $rss_item['pubDate'] . '</pubDate>' . "\n";
      $rss .= '<category>' . $rss_item['category'] . '</category>' . "\n";
      $rss .= '<source>' . $rss_item['source'] . '</source>' . "\n";
 
      if($this->full_feed) {
        $rss .= '<content:encoded>' . $rss_item['content'] . '</content:encoded>' . "\n";
      }
 
      $rss .= '</item>' . "\n";
    }
 
    $rss .= '</channel>';
 
    $rss .= '</rss>';
 
    return $rss;

      
//----------------------------------------------------------------------------//
//                                  fallback                                  //
//----------------------------------------------------------------------------//  
} catch (eliza\beta\Oops $O) {
    header("Content-Type:text/html");
    throw $O;
}