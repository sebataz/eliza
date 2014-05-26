<?php eliza\beta\Presentation::flush(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo eliza\beta\GlobalContext::Configuration()->Title; ?></title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/kb-theme.css">
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>     
        
        
        <script type="text/javascript">        
            var swim_log = 0;
            var reverse = false;
            console.log(swim_log);
            $(document).scroll(function(e){
                console.log(Math.floor(Math.random() * 6+5));
                var scrollAmount = $(window).scrollTop();
                var documentHeight = $(document).height();
                var swim = 60 - ( scrollAmount/documentHeight) * 150;
                reverse = swim > swim_log && swim_log != 0;
                swim_log = swim;
                $("#shark").css("visibility", "visible");
                $("#shark").css("left", swim+"%");
                if (Math.floor(swim_log%5) == 0)
                    $("#shark").css("bottom",  Math.floor((Math.random()*(5+1))+6) +"%");
                $("#shark").css("background", "url('../public/img/shark" + (reverse ? '-reverse' : '') + ".png') no-repeat");
                $("#shark").css("background-size", "contain");
            });
            
            $(document).ready(function () {
                $(".stacktrace").hide();
                $("h1.wtf").click(function () {
                    $(".stacktrace").toggle();
                });
            });
        </script>
    </head>
    <body style="height: 1600px">
        <div id="background-top"><div id="shark"></div></div>
        
       
        </div>
        
        
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
                    <h1 class="wtf" style="cursor: pointer;">Wtf?</h1><span class="stacktrace"><?php $e->printStackTrace(); ?></span><br /><br />
                <?php endif; ?>
                <a href="<?php echo 'http://', $_SERVER['HTTP_HOST'], '/?', $_SERVER['QUERY_STRING']; ?>">
                <span class="button" style="margin: 0 auto; width: 5em; text-align:center;">yrter</span></a>
            </div>
        </div>
       
        
        
        <div id="background-bottom">
            <div class="title"><a href=".."><?php echo eliza\beta\GlobalContext::Configuration()->Title; ?></a></div>
           
        </div>
    </body>
</html>
