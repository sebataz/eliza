<?php 

namespace eliza;

class XMLDocument extends Feed {
    
    public function __construct(array $_raw = array()) {
        parent::__construct($_raw);
        if (isset($_raw['Id']))
            $this->Id = $_raw['Id'];
    }
    
    public static function Feed($_directory = '.') {
        $XMLFeed = new CollectionFeed();
        foreach (Feed::File(FEEDS . $_directory) as $XmlFile) {            
            $XMLFeed->append(
                new static(
                    CollectionXML::SimpleXMLToArray(
                        simplexml_load_string(
                            $XmlFile->content()))));
        }
        
        return $XMLFeed;
    }
    
    // dude! this goes into XMLDocument, I believe!
    public function saveAs($_path) {
        $Feed = new static();
        $Feed->mergeWith($this);
        if (!Request::hasPrivilege(array(), 'SaveFeed')) oops(PERMISSION_DENIED);
        File::touch($_path)->content(CollectionXML::ObjectToXML($Feed));
    }
    
    private function __getRelativePath() {
        return 'feeds' . DS . lowercase($this->getClass()) . DS . $this->Id . '.xml';
    }
}