<?php 

namespace eliza;

abstract class XMLDocument extends Feed {
    public static function Feed() {
        $feed_directory = 'feeds' .  DS . strtolower(static::getClass());
        $XMLFeed = new CollectionFeed();
        foreach (Feed::FileContent($feed_directory . DS, 'xml') as $XmlFile)
                $XMLFeed->append(
                    new static(
                        CollectionXML::SimpleXMLToArray(
                            simplexml_load_string(
                                $XmlFile->content()))));
        
        return $XMLFeed;
    }
    
    // dude! this goes into XMLDocument, I believe!
	// yep, done!
    public function saveToDisk() {
        if (!Request::hasPrivilege(array(), 'SaveFeed')) oops(PERMISSION_DENIED);
        if (DEBUG) oops('save new feed in ' . strtolower($this-getClass()));
        
        $Feed = new static();
        $Feed->mergeWith($this);
        
        FileContent::createFile(FEEDS . strtolower(static::getClass()) . DS . $this->Id . '.xml')
        ->content(CollectionXML::ObjectToXML($Feed));
    }
    
    public function deleteFromDisk() {
        if (!Request::hasPrivilege(array(), 'DeleteFeed')) oops(PERMISSION_DENIED);
        if (DEBUG) oops('delete feed ' . $this->Id);
        
        File::describeFile(
            FEEDS . strtolower($this->getClass()) . DS . $this->Id . '.xml'
        )->deleteFromDisk();
    }
}