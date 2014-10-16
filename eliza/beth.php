<?php

include 'beta.php';
eliza\beta\Presentation::buffered();

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
        <ul>
        <?php foreach (eliza\beta\Feed::Node('eliza/feed') as $Feed): ?>
            <li><a href="?<?php echo $Feed->Name ?>"><?php echo $Feed->Name; ?></a></li>
        <?php endforeach; ?>
        </div>
        
        <div id="content">
        </div>
        
    </body>
</html>
