<?php

include 'eliza/beta.php';

eliza\beta\Presentation::buffered();

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
                
        <script src="public/js/jquery.preview.js" type="text/javascript"></script>
        
        <script src="public/js/eliza.get.js" type="text/javascript"></script>
        <script src="public/js/eliza.post.js" type="text/javascript"></script>
        
        <script type="text/javascript">                    
            $(document).ready(function () {
                $(".title").post('Page');
                $(".content").post('Page');
                $(".preview").preview()
            });
        </script>
    </head>
    <body>
    
    <div id="container">
        <div id="page">
            [Page getBy:id,<?php echo eliza\beta\GlobalContext::Globals()->Get->defaultValue('p'); ?> /]
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
