<?php
include 'lib/service.php';

$issue_type = array('FAQ', 'Issue', 'Warning', 'Error', 'Catastrophe');

$kb = Get::kb(null, 'Issue', isset($_GET['t'])?$_GET['t']:array(), isset($_GET['ty'])?$_GET['ty']:null);


$querystring = array();
$querystring = isset($_GET['ty']) ? array_merge($querystring, array('ty' => $_GET['ty'])) : $querystring;
$querystring = isset($_GET['q']) ? array_merge($querystring, array('q' => $_GET['q'])) : $querystring;
$querystring = '&' . urldecode(http_build_query($querystring));
if (isset($_GET['t'])) foreach ($_GET['t'] as $tag) $querystring .= ($querystring == '' ? 't[]=' : '&t[]=') . $tag;

if (isset($_GET['q'])) {
    $tmp_kb = array();
    foreach (Get::search($kb, $_GET['q']) as $issue)
        $tmp_kb[] = $issue['Result'];
    
    $kb = $tmp_kb;
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
        
        <script type="text/javascript">        
            SyntaxHighlighter.defaults['toolbar'] = false;
            SyntaxHighlighter.all();
            $(document).scroll(function(e){
 
                // grab the scroll amount and the window height
                var scrollAmount = $(window).scrollTop();
                var documentHeight = $(document).height();
                
                var swim = 60 - ( scrollAmount/documentHeight) * 150;
                console.log(swim);
                $("#shark").css("left", swim+"%");
                $("#shark").css("bottom",  Math.floor(Math.random() * 6) + 6 +"%");
                
                
            });
            
            
            $(document).ready(function () {
            
            });
        </script>
    </head>
    <body>
        <div id="background-top"><div id="shark"></div></div>
        
        <div id="search">
            <form name"searchForm" id="searchForm" action='.' method="GET">
                <div><span><input type="text" class="search" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>"/></span><span class="search" onClick="searchForm.submit();"></span></div>
            </form>
            <div class="list">
                <?php foreach($kb as $Issue): ?>
                    <a href="?id=<?php echo $Issue['File']['Title'] . $querystring; ?>"><div class="kb">#<span class="id"><?php echo $Issue['File']['Title']; ?></span><span class="title">: <?php echo $Issue['Issue']; ?></span></div></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        
        
        <div id="knowledge-base">
            <?php if (isset($_GET['edit'])): ?>
                <?php include 'edit.php'; ?>
            <?php elseif (!isset($_GET['id']) && !empty($kb)): $Issue = reset($kb); ?>
                <?php include 'issue.php'; ?>
            <?php elseif (isset($_GET['id'])): $Issue = reset(Get::kb($_GET['id'])); ?>
                <?php include 'issue.php'; ?>
            <?php endif; ?>
            
            <?php if (!isset($_GET['edit'])): ?>
                <div class="kb"><a href="?edit=<?php echo time(), substr(microtime(),2,3); ?>"><div><span class="a-button">+ create new</span></div></a></div>
            <?php endif; ?>
        </div>
        
        
        <div id="background-bottom">
            <div class="title"><a href=".">trilead knowledge base</a></div>
            <?php foreach (Get::tags($kb) as $tag): ?>
                <a href="?t[]=<?php echo $tag['Tag'] . $querystring; ?>" style="font-size: <?php echo $tag['Size']; ?>em;"><?php echo $tag['Tag']; ?></a>
            <?php endforeach; ?>
        </div>
    </body>
</html>
