<?php

class GitHistory extends eliza\beta\JSONFeed {
    public static function Feed($_n) {
        /** GIT history **/
        $GitHistory = new eliza\beta\Collection();
        $Commit = null;
        
        // query git for history
        exec('git log -n ' . $_n, $output);

        // parse command output
        foreach($output as $line){
            if (strpos($line, 'commit') === 0){
                if ($Commit) $GitHistory->append($Commit);
                $Commit = new eliza\beta\Object();
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
}