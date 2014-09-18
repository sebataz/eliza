<?php eliza\beta\Presentation::flush(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Oops!</title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <style>
            body {font-family: tahoma; background-color: #fff; font-size: .8em;}
            div#oops {margin: 10em auto; width: 55em; padding: 1.5em;}
            div#stacktrace {padding: .3em; font-size: .8em;}
            h1 {font-size: 1.5em; margin-top: 2em; margin-bottom: .5em; color: #e00; border-bottom: dotted 1px #aaa;}
            h2 {font-size: 1.2em; margin-bottom: 1em;}
            li {list-style-type: square; margin-left: 2em;}
        </style>
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
                    </ul>
                </p><br /><br />
                <?php if ($e): ?>
                    <h1 class="wtf">Wtf?</h1><div id="stacktrace"><?php $e->printStackTrace(); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
