<?php

include 'eliza/beta.php';

if (DEBUG)
    eliza\beta\Presentation::buffered();
else
    eliza\beta\Presentation::cached();
    
    
$Page = eliza\beta\Feed::Page()->getBy('Id', eliza\beta\GlobalContext::Globals()->Get->defaultValue('id'));
$Page = $Page ? $Page : new Page(array('Id' => (time() . substr(microtime(),2,3))));

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo eliza\beta\GlobalContext::Configuration()->Title; ?></title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/theme.css">
        
        <script src="public/js/jquery.preview.js" type="text/javascript"></script>
        
        <script type="text/javascript">        
            $(document).ready(function () {
                $(".preview").preview()
            });
        </script>
    </head>
    <body>
        <form action="./eliza/?Page" method="POST" >
        <div id="container">
            <div id="page">
                <?php if (eliza\beta\Response::hasPrivilege()): ?>
                
                    <input type="hidden" name="Id" value="<?php echo $Page->Id; ?>" />
                    <textarea name="Content"><?php echo $Page->Content; ?></textarea>
                
                <?php elseif (eliza\beta\GlobalContext::Globals()->Get->defaultValue('id')): ?>
                [Page /]{filterBy:Id,<?php echo eliza\beta\GlobalContext::Globals()->Get->defaultValue('id'); ?>}
                <?php endif; ?>
            </div>
            <div id="index">
                <a href="."><img src="public/img/seba.jpg" /></a>
                <?php 
                    echo eliza\beta\Feed::Page()->sortBy('Title', SORT_ASC)->buildIndexHTML();
                ?>
            </div>
        </div>
        <div id="top-bar">
            <div id="form">
                    <?php if (eliza\beta\Response::hasPrivilege()): ?>
                    <select name="Parent">
                    <option value="0">-</option>
                    <?php foreach (eliza\beta\Feed::Page() as $Pages):  ?>
                    <option value="<?php echo $Pages->Id; ?>"><?php echo $Pages->Title; ?></option>
                    <?php endforeach; ?>
                    </select>
                    <input type="text" class="wide" name="Title" placeholder="title..." value="<?php echo $Page->Title; ?>" />
                    <input type="submit" />
                    <?php endif; ?>
                </form>
                <form action="eliza/?<?php echo eliza\beta\GlobalContext::Querystring(); ?>" method="POST" />
                    <input type="password" name="lock" placeholder="unlock..." />
                </form>
            </div>
        </div>
    </body>
</html>
