<?php

include 'beta.php';
eliza\beta\Presentation::buffered();


$feed = class_exists(key($_GET)) ? key($_GET) : null;
$args = isset($_GET['args']) ? $_GET['args'] : array();


 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo eliza\beta\GlobalContext::Configuration()->Title; ?></title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/beth.css">
        
        <script src="../public/plugin/jquery-1.11.1.min.js" type="text/javascript"></script>
        <script src="../public/js/jquery.eliza.js" type="text/javascript"></script>
        <script src="../public/js/jquery.preview.js" type="text/javascript"></script>
        <script src="../public/js/jquery.codeview.js" type="text/javascript"></script>

        <link href="../public/plugin/prism/prism.css" rel="stylesheet" />
        <script src="../public/plugin/prism/prism.js"></script>
        
        
        <style>
            
          
        </style>
        
        <script type="text/javascript">
            console.log('hello!');
            
            var Eli = new Eliza('index.php');
            var querystring = window.location.href.slice(window.location.href.indexOf('?') + 1);
            console.log('query: ' + querystring);
                
                
            $(document).ready(function() {
                
                Eli.query(querystring).call(function (data) {
                        console.log('response:');
                        console.log
                        console.log(data);
                    
                        var html = $(data.html);
                        $('#content').html(html);
                        
                        // plugins
                        html.find(".preview").preview()
                        Prism.highlightAll();
                });
            });
        </script>
    </head>
    <body>
        
        <div id="feeds">
            <?php foreach (eliza\beta\Feed::Node('eliza/feed') as $Feed): ?>
            <span><a href="?<?php echo $Feed->Name ?>"><?php echo $Feed->Name; ?></a></span>
            <?php endforeach; ?>:
            
            <?php if ($feed): $Empty = new $feed(); ?>
            <form action="beth.php" method="GET">
                <input type="hidden" name="<?php echo $feed; ?>" />
                
                <input type="text" name="q" placeholder="search..." />
                
                <select name="by">
                <?php foreach ($Empty as $prop => $val): ?>
                    <option value="<?php echo $prop; ?>"><?php echo $prop; ?></option>
                <?php endforeach; ?>
                </select>
                
                <input type="text" name="val" placeholder="filter by..." />
                
                <select name="srt">
                <?php foreach ($Empty as $prop => $val): ?>
                    <option value="<?php echo $prop; ?>"><?php echo $prop; ?></option>
                <?php endforeach; ?>
                </select>
                
                <select name="ord" >
                    <option value="<?php echo SORT_ASC; ?>">asc</option>
                    <option value="<?php echo SORT_DESC; ?>">desc</option>
                </select>
                
                <input type="text" name="lmt" placeholder="limit by..." />
                <input type="text" name="off" placeholder="offset..." />
                
                <button type="submit">get</button>
            </form>
            <?php endif; ?>
        </div>
        
        <div id="content">
        </div>
        
    </body>
</html>
