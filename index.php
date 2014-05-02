<?php

include 'eliza/beta.php';

eliza\beta\Presentation::buffered();

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
            <?php if (isset($_GET['m'])): ?>
            [Article /]{byMonth:<?php echo $_GET['m']; ?>}
            <?php else: ?>
            [Article /]
            <?php endif; ?>
            <div id="archive">
                <ul>
                <?php foreach (eliza\beta\Feed::Article()->Archive() as $ref => $month): ?>
                    <li><a href="?m=<?php echo $ref; ?>"><?php echo $month; ?></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div id="top-bar">
            <div>
                <span class="at">@</span>
                <span>sebataz.ch</span>
                <span>
                    <select name="links" id="links">
                        <option value=".">/</option>
                        <option value="https://github.com/sebataz">projects</option>
                        <option value="https://sourceforge.net/users/sebataz">downloads</option>
                        <option value="http://sebataz.ch/rigoni">about me</option>
                    </select>
                </span>
                <script type="text/javascript">
                    var urlmenu = document.getElementById( 'links' );
                    urlmenu.onchange = function() {
                        window.open(  this.options[ this.selectedIndex ].value , '_self' );
                    };
                </script>
                <form action="." method="GET">
                    <input name="search" id="search" type="text" placeholder="search..." />
                </form>
            </div>
        </div>
    </body>
</html>
