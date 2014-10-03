
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <script src="../public/plugin/jquery-1.11.1.min.js" type="text/javascript"></script>
                
        <script src="../public/js/jquery.eliza.js" type="text/javascript"></script>
        
        <script type="text/javascript">  
                new Eliza('../eliza/index.php').query('Page', {
                    Id: 1,
                    Parent: 0,
                    Title: 'ajax post',
                    Content: 'first post with eliza'
                }).call(function(response, html){
                    console.log(response);
                }, 1);
        </script>
    </head>
    <body>
        <div id="response"></div>
        <form action="../eliza/index.php" method="POST" />
            <input type="password" name="lock" placeholder="unlock..." />
        </form>
    </body>
</html>