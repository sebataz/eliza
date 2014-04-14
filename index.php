<?php

include 'eliza/beta.php';

eliza\beta\Buffer::buffered();

$KnowledgeBase = eliza\beta\Feed::Kb();
$Kb = new Kb();

$querystring = null;

if (isset($_GET['kb']))
    if (!($Kb = ($KnowledgeBase->getBy('Id', $_GET['kb']))))
        oops('knowledge could not be found');
        
if (isset($_GET['y']))
    $KnowledgeBase->filterBy('Type', $_GET['y']);
    
if (isset($_GET['t']))
    foreach ($_GET['t'] as $t)
        $KnowledgeBase->filterBy('Tags', $t);
    
if (isset($_GET['q'])) {
    $tmp_kb = array();
    foreach ($KnowledgeBase->Q($_GET['q']) as $issue)
        $tmp_kb[] = $issue['Result'];
    
    $KnowledgeBase->exchangeArray($tmp_kb);
}

$Kb = !isset($_GET['kb']) && $KnowledgeBase->count() 
    ? $KnowledgeBase->first() : $Kb;

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo eliza\beta\Configuration::get()->Title; ?></title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/kb-theme.css">
        
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        
        <link href="http://alexgorbatchev.com/pub/sh/current/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shCore.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shAutoloader.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPlain.js" type="text/javascript"></script>
        
        <script type="text/javascript">        
            SyntaxHighlighter.defaults['toolbar'] = false;
            SyntaxHighlighter.all();
               
               
            var draggable_list = {
                                    helper: 'clone',
                                    revert: 'invalid',
                                    revertDuration: 0,
                                    appendTo: 'body',
                                };
                                
            var swim_log = 0;
            
            
            console.log(swim_log);
            $(document).scroll(function(e){
                console.log(Math.floor(Math.random() * 6+5));
                var scrollAmount = $(window).scrollTop();
                var documentHeight = $(document).height();
                var swim = 60 - ( scrollAmount/documentHeight) * 150;
                var reverse = swim > swim_log && swim_log != 0;
                swim_log = swim;
                $("#shark").css("visibility", "visible");
                $("#shark").css("left", swim+"%");
                if (Math.floor(swim_log%5) == 0)
                    $("#shark").css("bottom",  Math.floor((Math.random()*(5+1))+6) +"%");
                $("#shark").css("background", "url('public/img/shark" + (reverse ? '-reverse' : '') + ".png') no-repeat");
                $("#shark").css("background-size", "contain");
            });
            
            $(document).ready(function () {
                $(".search").keypress(function(e) {
                    if (e.keyCode == 32) {
                        var search = $( this ).val();
                        console.log("searching " + search);
                        
                        $.ajax({
                            url: "eliza/?Kb&q=" + search,
                            context: $(".list")
                        }).done(function(data) {
                            console.log("found:")
                            console.log(data);
                            
                            var list = $( this );
                            list.empty();
                                                        
                            data.forEach(function(i) {
                                var kb = $( "<a href=\"?kb=" + i.Result.Id + "<?php echo $querystring; ?>\"><div id=\"" + i.Result.Id + "\" class=\"kb drag\">#<span class=\"id\">" + i.Result.Id + "</span><span class=\"title\">: " + i.Result.Issue + "</span></div></a>");
                                kb.find(".drag").draggable(draggable_list);
                                list.append(kb);
                            });
                        });
                    }
                });
            });
        </script>
    </head>
    <body>
        <div id="background-top"><div id="shark"></div></div>
        
        <div id="search">
            <form name"searchForm" id="searchForm" action='.' method="GET">
                <div><span><input type="text" class="search" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>"/></span><span class="search" onClick="searchForm.submit();"></span></div>
            </form>
            <table class="type"><tr>
                <?php foreach (eliza\beta\Configuration::get()->Types as $type): ?>
                <td><a class="<?php if(isset($_GET['y'])) if ($_GET['y'] == $type) echo 'active'; ?>"  href="?y=<?php echo $type, eliza\beta\Request::querystring('q'); ?>"><?php echo $type; ?></a></td>
                <?php endforeach; ?>
            </tr></table>
            <div class="list">
                <?php foreach($KnowledgeBase as $KbLink): ?>
                    <a href="?kb=<?php echo $KbLink->Id, eliza\beta\Request::querystring(array('q', 'y', 't')), '#', $KbLink->Id; ?>"><div id="<?php echo $KbLink->Id; ?>" class="kb drag">#<span class="id"><?php echo $KbLink->Id; ?></span><span class="title">: <?php echo $KbLink->Issue; ?></span></div></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        
        
        <div id="knowledge-base">
            <?php if (isset($_GET['edit'])): ?>
                <?php include 'edit.php'; ?>
            <?php elseif (!isset($_GET['id'])): ?>
                <?php include 'kb.php'; ?>
            <?php elseif (isset($_GET['id'])): ?>
                <?php include 'kb.php'; ?>
            <?php endif; ?>
            
            <?php if (!isset($_GET['edit'])): ?>
                <div class="kb"><a href="?edit"><div><span class="button">wen etaerc +</span></div></a></div>
            <?php endif; ?>
        </div>
        
        
        <div id="background-bottom">
            <div class="title"><a href="."><?php echo eliza\beta\Configuration::get()->Title; ?></a></div>
            <?php foreach ($KnowledgeBase->TagCloud() as $Tag): ?>
                <a href="?t[]=<?php echo $Tag->Tag, eliza\beta\Request::querystring(array('q', 'y', 't')); ?>" style="font-size: <?php echo $Tag->Size; ?>em;"><?php echo $Tag->Tag; ?></a>
            <?php endforeach; ?>
        </div>
    </body>
</html>
