<?php eliza\Presentation::flush(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Oops!</title>
        <link rel="stylesheet" type="text/css" href="https://necolas.github.io/normalize.css/6.0.0/normalize.css">
        <style>
            body {font-size: 1em; font-family: 'Tahoma', sans-serif; background-color: #fff;}
            div#oops {margin: 7% auto 0 auto; width: 70%;}
            div#stacktrace {padding: 0 2em; font-size: .8em;}
            h1 {color: #f03000; border-bottom: 1px solid #aaa;}
            h2 {font-size: 1.2em;}
            li {list-style-type: square;}
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
                <?php if ($e instanceof \eliza\Oops): ?>
                    <h1 class="wtf">Wtf?</h1>
                    <div id="stacktrace"><?php $e->printStackTrace(); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
