<?php
include 'lib/service.php';

$issue_type = array('FAQ', 'Issue', 'Warning', 'Error', 'Catastrophe');

$kb = Get::kb(null, 'Issue', isset($_GET['t'])?$_GET['t']:array(), isset($_GET['ty'])?$_GET['ty']:null);
$tag_cloud = Get::tags($kb);


$tag_querystring = isset($_GET['ty'])?'&ty=' . $_GET['ty']:'';
if (isset($_GET['t'])) {
    foreach ($_GET['t'] as $tag)
        $tag_querystring .= '&t[]=' . $tag;
}

if (isset($_GET['q'])) {
    $kb = array();
    foreach (Get::search('kb', $_GET['q']) as $issue)
        $kb[] = $issue['Result'];
}

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Trilead - Knowledge Base</title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/kb-theme.css">
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>      
        
        <link href="http://alexgorbatchev.com/pub/sh/current/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shCore.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shAutoloader.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPlain.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushBash.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPhp.js" type="text/javascript"></script>
        
        <script src="../public/js/shBrushNginx.js" type="text/javascript"></script>
        
        <script type="text/javascript">        
            SyntaxHighlighter.defaults['toolbar'] = false;
            SyntaxHighlighter.all();
            $(document).ready(function () {
                $(".kb").minimize();
            });
        </script>
    </head>
    <body>
        <div id="background">
        
            <div id="background-top"></div>
            <div id="background-bottom"><div class="title">trilead knowledge base</div></div>
            
        
        </div>
        <div id="search">
            <form name"searchForm" id="searchForm" action='.' method="GET">
                <input type="text" class="search" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>"/><span class="search" onClick="searchForm.submit();">sebarch</span>
            </form>
            <div class="tag-cloud type">
                <?php foreach ($issue_type as $type): ?>
                    <a href="?ty=<?php echo $type; ?>" style="font-size: 2em;"><?php echo $type; ?></a>&nbsp;&nbsp;
                <?php endforeach; ?>
            </div>
            <div class="list">
                <?php foreach($kb as $Issue): ?>
                    <a href="?id=<?php echo $Issue['File']['Title'] . $tag_querystring; ?>"><div class="kb">#<?php echo $Issue['File']['Title']; ?>: <?php echo $Issue['Issue']; ?></div></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="content">
            <div id="knowledge-base">
                <a href="editor.php"><div class="kb"><span class="new">+ create new</span></div></a>
                <?php if (isset($_GET['id'])): $Issue = reset(Get::kb($_GET['id'])); ?>
                    <?php include 'issue.php'; ?>
                <?php elseif (count($kb)==1): $Issue = $kb[0]; ?>
                    <?php include 'issue.php'; ?>
                <?php else: ?>
                
            <div class="tag-cloud">
                <?php foreach (Get::tags($kb) as $tag): ?>
                        <a href="?t[]=<?php echo $tag['Tag'] . $tag_querystring; ?>" style="font-size: <?php echo $tag['Size']; ?>em;"><?php echo $tag['Tag']; ?></a>
                    <?php endforeach; ?>
            </div>
                <?php endif; ?>
            </div>
        </div>
        <div id="utilities">
            [<a href="editor.php">editor</a>]
            [<a href=".">main</a>]:
            <form action='.' method="GET" style="display: inline;">
                <select name="id" style="width: 60%">
                    <option></option>
                <?php foreach (Get::kb(null, 'Issue') as $issue): ?>
                    <option value="<?php echo $issue['File']['Title']; ?>"><?php echo '#' . $issue['File']['Title'] . ': ' . $issue['Issue']; ?></option>
                <?php endforeach; ?>
                </select>
                <input type="text" name="q" />
                <input type="submit" />
            </form>
        </div>
    </body>
</html>
