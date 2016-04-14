<?php

class GitHistory extends eliza\feed\Feed implements eliza\feed\HTMLFeedI {
    public $Hash = '';
    public $Author = '';
    public $Date = '';
    public $Message = '';    

    public static function Feed() {
        /** GIT history **/
        $GitHistory = new eliza\feed\HTMLFeed();
        $Commit = null;
        
        // query git for history
        chdir(ROOT); //looks like this is needed when using buffered output, 
        // otherwise eliza will ask for log in wrong directory... but why????
        exec('git log', $output);
        
        // parse command output
        foreach($output as $line){
            if (strpos($line, 'commit') === 0){
                if ($Commit) $GitHistory->append($Commit);
                $Commit = new self();
                $Commit->Message = array();
                
                $Commit->Hash = substr($line, strlen('commit'));
            }
            else if(strpos($line, 'Author') === 0){
                $Commit->Author = substr($line, strlen('Author:'));
            }
            else if(strpos($line, 'Date') === 0){
                $Commit->Date = substr($line, strlen('Date:'));
            }
            else{		
                $Commit->Message[] = $line;
            }
        }
        
        return $GitHistory;
    }
    
    public function toHTML() {
        return '<li>'.@date('Y.m.d', strtotime($this->Date)) . ': ' . implode($this->Message) . '</li>';
    }
}