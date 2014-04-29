<?php

include 'eliza/beta.php';

eliza\beta\Buffer::buffered();

$Blog = eliza\beta\Feed::Article();
$Article = $Blog->first();

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo eliza\beta\Configuration::get()->Title; ?></title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/blog-theme.css">
        
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        
        <link href="http://alexgorbatchev.com/pub/sh/current/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shCore.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shAutoloader.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPlain.js" type="text/javascript"></script>
        <script src="public/plugin/shBrush/shBrushNginx.js" type="text/javascript"></script>
        
        <script type="text/javascript">        
            SyntaxHighlighter.defaults['toolbar'] = false;
            SyntaxHighlighter.all();
        </script>
    </head>
    <body>
        <div id="blog">
            <?php if (!isset($_GET['id'])): ?>
                <?php include 'article.php'; ?>
            <?php elseif (isset($_GET['id'])): ?>
                <?php include 'article.php'; ?>
            <?php endif; ?>
        </div>
    </body>
</html>
