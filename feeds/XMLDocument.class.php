<?php 

namespace eliza;

abstract class XMLDocument extends Feed {
    public static function Feed() {
        $feed_directory = 'feeds' .  DS . strtolower(static::getClass());
        $XMLFeed = new CollectionFeed();
        foreach (Feed::File($feed_directory . DS) as $XmlFile)
            if ($XmlFile->Extension == '.xml')
                $XMLFeed->append(
                    new static(
                        CollectionXML::SimpleXMLToArray(
                            simplexml_load_string(
                                $XmlFile->content()))));
        
        
        return $XMLFeed;
    }
    
    // dude! this goes into XMLDocument, I believe!
    public function saveAs($_path) {
        if (!Request::hasPrivilege(array(), 'SaveFeed')) oops(PERMISSION_DENIED);
        if (DEBUG) oops('save feed as ' . $_path);
        
        $Feed = new static();
        $Feed->mergeWith($this);
        
        File::touch($_path)->content(CollectionXML::ObjectToXML($Feed));
    }
    
    public function delete() {
        if (!Request::hasPrivilege(array(), 'DeleteFeed')) oops(PERMISSION_DENIED);
        if (DEBUG) oops('delete feed ' . $this->Id);
        
        Node::describeNode(
            FEEDS . strtolower($this->getClass()) . DS . $this->Id . '.xml'
        )->delete();
    }
}