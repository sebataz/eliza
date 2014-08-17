<?php

include 'eliza/beta.php';

eliza\beta\Presentation::buffered();

$Blog = new eliza\feed\HTMLFeed();
if (isset($_GET['search']))
    foreach (eliza\beta\Feed::Article()->Q($_GET['search']) as $Found)
        $Blog->append($Found->Result);
        
if (isset($_GET['t'])) {
    $Blog = eliza\beta\Feed::Article();
    foreach ($_GET['t'] as $t)
        $Blog->filterBy('Tags', $t);
}        

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo eliza\beta\GlobalContext::Configuration()->Title; ?></title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/blog-theme.css">
        
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        
        <link href="http://alexgorbatchev.com/pub/sh/current/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shCore.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shAutoloader.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPlain.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPhp.js" type="text/javascript"></script>
        <script src="public/plugin/shBrush/shBrushNginx.js" type="text/javascript"></script>
                
        <script src="public/js/jquery.preview.js" type="text/javascript"></script>
        
        <script type="text/javascript">        
            SyntaxHighlighter.defaults['toolbar'] = false;
            SyntaxHighlighter.all();
            
            $(document).ready(function () {
                $(".preview").preview()
            });
        </script>
    </head>
    <body>
    
    <div id="container">
        <div id="blog">
            <?php if (isset($_GET['id'])): ?>
            [Article /]{filterBy:Id,<?php echo $_GET['id']; ?>}
            <?php elseif (isset($_GET['search'])): ?>
                <?php echo $Blog->sortBy('Id', 3)->HTMLFeed(); ?>
            <?php elseif (isset($_GET['m'])): ?>
            [Article /]{sortBy:Date,4 byMonth:<?php echo $_GET['m']; ?>}
            <?php elseif (isset($_GET['t'])): ?>
                <?php echo $Blog->sortBy('Id', 3)->HTMLFeed(); ?>
            <?php elseif (isset($_GET['edit'])): ?>
                <?php include 'edit.php'; ?>
            <?php else: ?>
            [Article /]{sortBy:Date,3}
            <?php endif; ?>
            <div id="archive">
                <p><a href=".">Home</a></p>
                <ul>
                <?php foreach (eliza\beta\Feed::Article()->Archive() as $ref => $month): ?>
                    <li><a href="?m=<?php echo $ref; ?>"><?php echo $month; ?></a></li>
                <?php endforeach; ?>
                </ul>
                <div id="tag-cloud">
                    <?php foreach (eliza\beta\Feed::Article()->TagCloud() as $tag => $count): ?>
                        <span class="tag"><a href="?t[]=<?php echo $tag; ?>"><?php echo $tag; ?></a>(<?php echo $count; ?>)</span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
        
        <div id="top-bar">
            <div>
                <span>sebataz</span><span>.ch</span>
                <form action="." method="GET">
                    <input name="search" id="search" type="text" placeholder="search..." value="<?php echo isset($_GET['search'])?$_GET['search']:''; ?>" />
                </form>
            </div>
        </div>
        
        <div id="bottom-bar">
            <div id="columns">
                <div class="sebataz">
                    <h4>sebataz</h4>
                    <ul>
                        <?php foreach (eliza\beta\GlobalContext::Configuration()->Me as $name => $url): ?>
                            <li><a href="<?php echo $url; ?>"><?php echo $name; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="sebataz">
                    <h4>comunities</h4>
                    <ul>
                        <?php foreach (eliza\beta\GlobalContext::Configuration()->Comunities as $name => $url): ?>
                            <li><a href="<?php echo $url; ?>"><?php echo $name; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div id="unlock">
                    <form action="eliza/?<?php echo eliza\beta\GlobalContext::Querystring(); ?>" method="POST" />
                        <input type="password" name="lock" placeholder="unlock..." />
                    </form>
                </div>
            </div>
        </div>
        
    </body>
</html>
