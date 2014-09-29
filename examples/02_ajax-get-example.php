
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <script src="../public/plugin/jquery-1.11.1.min.js" type="text/javascript"></script>
                
        <script src="../public/js/jquery.eliza.js" type="text/javascript"></script>
        
        <script type="text/javascript">  
                new Eliza('../eliza/index.php').query('Node').call(function(response, html){
                    $('#response').append(html);
                });
        </script>
    </head>
    <body>
        <div id="response"></div>
    </body>
</html>
