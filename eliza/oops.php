<?php eliza\beta\Presentation::flush(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Oops!</title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <style>
            body {font-family: tahoma; font-size: .8em;}
            div#oops {margin: 10em auto; width: 55em; background-color: #eee; padding: 1.5em;}
            div#stacktrace {background-color: #fff; padding: .3em; font-size: .8em;}
            h1 {font-size: 1.5em; margin-bottom: .5em;}
            h2 {font-size: 1.2em; margin-bottom: 1em;}
            li {list-style-type: square; margin-left: 2em;}
        </style>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>    
        <script type="text/javascript">
            $(document).ready(function () {
                $("#stacktrace").hide();
                $("h1.wtf").click(function () {
                    $("#stacktrace").toggle();
                });
            });
        </script>
    </head>
    <body>
        <div id="oops">
            <div>
                <h1>Oops!</h1>
                <h2>"Uh, Houston, we've had a problem."</h2>
                <p>
                    Chances are that...
                    <ul>
                        <?php if ($e->getMessage() != ''): ?>
                        <li><?php echo $e->getMessage(); ?></li>
                        <?php else: ?>
                        <li>the page you are seeking was not found</li>
                        <li>either the server or the site is undergoing maintenance work</li>
                        <?php endif; ?>
                        <li>or maybe, it's just that the shark is feeding (scroll to check)</li>
                    </ul>
                </p><br /><br />
                <?php if ($e): ?>
                    <h1 class="wtf" style="cursor: pointer;">Wtf?</h1><div id="stacktrace"><?php $e->printStackTrace(); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
