<?php 

namespace eliza;

class XMLDocument extends Feed {
    protected $Raw;
    
    public function __construct($_raw) {
        parent::__construct($_raw);
        if (isset($_raw['Id']))
            $this->Id = $_raw['Id'];
        $this->Raw = $_raw;
    }
    
    public static function Feed($_directory = '.') {
        $XMLFeed = new HTMLFeed();
        foreach (Feed::File($_directory) as $XmlFile) {
            if ($XmlFile->IsDir) continue;
            
            $XMLFeed->append(
                new static(
                    eliza\XMLFeed::SimpleXMLToArray(
                        simplexml_load_string(
                            $XmlFile->content()))));
        }
        
        return $XMLFeed;
    }
    
    // dude! this goes into XMLDocument, I believe!
    public function save() {
        if (!Response::hasPrivilege(array(), 'SaveFeed')) oops(PERMISSION_DENIED);
        File::describeNode($this->__getRelativePath())->content(XMLFeed::ObjectToXML($this));
    }
    
    
    // dude! this goes into XMLDocument, I believe!
    public function delete () {
        if (!Response::hasPrivilege(array(), 'DeleteFeed')) oops(PERMISSION_DENIED);
        File::describeNode($this->__getRelativePath())->delete();
    }
    
    private function __getRelativePath() {
        return 'feeds' . DS . lowercase($this->getClass()) . DS . $this->Id . '.xml';
    }
}