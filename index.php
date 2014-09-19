<?php

include 'eliza/beta.php';

if (DEBUG)
    eliza\beta\Presentation::buffered();
else
    eliza\beta\Presentation::cached();

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo eliza\beta\GlobalContext::Configuration()->Title; ?></title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/theme.css">
        
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
                
        <script src="public/js/jquery.preview.js" type="text/javascript"></script>
        
        <script type="text/javascript">        
            $(document).ready(function () {
                $(".preview").preview()
            });
        </script>
    </head>
    <body>
        <div id="container">
            <div id="page">
                [Page /]{filterBy:Id,<?php echo eliza\beta\GlobalContext::Globals()->Get->defaultValue('p'); ?>}
            </div>
            <div id="index">
                <?php
                    echo eliza\beta\Feed::Page()->buildIndexHTML();
                ?>
            </div>
        </div>
    </body>
</html>
