<?php
function git_history() {
/** GIT history **/
$git_history = array();
$limit_commits = 7;
$publish = 0;

// query git for history
exec('git log -n ' . $limit_commits, $output);

// parse command output
foreach($output as $line){
    if (strpos($line, 'commit') === 0){
        $publish++;
        $git_history[$publish]['Hash'] = substr($line, strlen('commit'));
    }
    else if(strpos($line, 'Author') === 0){
        $git_history[$publish]['Author'] = substr($line, strlen('Author:'));
    }
    else if(strpos($line, 'Date') === 0){
        $git_history[$publish]['Date'] = substr($line, strlen('Date:'));
    }
    else{		
        $git_history[$publish]['Message'][] = $line;
    }
}

return $git_history;
}