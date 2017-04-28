<?php 

namespace eliza;

class XMLDocument extends Feed {
    protected $Raw;
    
    public function __construct(array $_raw) {
        parent::__construct($_raw);
        if (isset($_raw['Id']))
            $this->Id = $_raw['Id'];
        $this->Raw = $_raw;
    }
    
    public static function Feed($_directory = '.') {
        $XMLFeed = new CollectionFeed();
        foreach (Feed::File($_directory) as $XmlFile) {            
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
        $Raw = new Object($this->Raw);
        $Raw->mergeWith($this);
        if (!Response::hasPrivilege(array(), 'SaveFeed')) oops(PERMISSION_DENIED);
        File::touch($_path)->content(XMLFeed::ObjectToXML($Raw)); // in teoria te dovresa mia scrivumal al roo
    }
    
    private function __getRelativePath() {
        return 'feeds' . DS . lowercase($this->getClass()) . DS . $this->Id . '.xml';
    }
}