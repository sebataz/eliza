<?php
include 'lib/service.php';

if (isset($_GET['id']))
    if (empty(Get::kb($_GET['id'])))
        header('Location: oops.php?' . $_SERVER['QUERY_STRING']);

$knowledge_type = array('FAQ', 'HowTo', 'Warning/Error', 'Troubleshooting');

$kb = Get::kb(null, 'Issue', isset($_GET['t'])?$_GET['t']:array(), isset($_GET['ty'])?$_GET['ty']:null);

$querystring = array();
if (isset($_GET['ty'])) $querystring['ty'] = $_GET['ty'];
$querystring = isset($_GET['q']) ? array_merge($querystring, array('q' => $_GET['q'])) : $querystring;
$querystring = '&' . urldecode(http_build_query($querystring));
if (isset($_GET['t'])) foreach ($_GET['t'] as $tag) $querystring .= ($querystring == '' ? 't[]=' : '&t[]=') . $tag;

if (isset($_GET['q'])) {
    $tmp_kb = array();
    foreach (Get::search($kb, $_GET['q']) as $issue)
        $tmp_kb[] = $issue['Result'];
    
    $kb = $tmp_kb;
}

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Trilead - Knowledge Base</title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/kb-theme.css">
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>     
        
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
                            url: "service/proxy.php?search&c=kb&q=" + search,
                            context: $(".list")
                        }).done(function(data) {
                            console.log("found:")
                            console.log(data);
                            
                            var list = $( this );
                            list.empty();
                            
                            data.forEach(function(i) {
                                var kb = $( "<a href=\"?id=" + i.Result.Id + "<?php echo $querystring; ?>\"><div id=\"" + i.Result.Id + "\" class=\"kb drag\">#<span class=\"id\">" + i.Result.Id + "</span><span class=\"title\">: " + i.Result.Issue + "</span></div></a>");
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
                <?php foreach ($knowledge_type as $type): ?>
                <td><a class="<?php if(isset($_GET['ty'])) if ($_GET['ty'] == $type) echo 'active'; ?>"S  href="?ty=<?php echo $type, isset($_GET['q']) ? '&q=' . $_GET['q'] : ''; ?>"><?php echo $type; ?></a></td>
                <?php endforeach; ?>
            </tr></table>
            <div class="list">
                <?php foreach($kb as $Issue): ?>
                    <a href="?id=<?php echo $Issue['File']['Title'] . $querystring; ?>"><div id="<?php echo $Issue['Id']; ?>" class="kb drag">#<span class="id"><?php echo $Issue['File']['Title']; ?></span><span class="title">: <?php echo $Issue['Issue']; ?></span></div></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        
        
        <div id="knowledge-base">
            <?php if (isset($_GET['edit'])): ?>
                <?php include 'edit.php'; ?>
            <?php elseif (!isset($_GET['id']) && !empty($kb)): $Issue = reset($kb); ?>
                <?php include 'issue.php'; ?>
            <?php elseif (isset($_GET['id'])): $Issue = reset(Get::kb($_GET['id'])); ?>
                <?php include 'issue.php'; ?>
            <?php endif; ?>
            
            <?php if (!isset($_GET['edit'])): ?>
                <div class="kb"><a href="?edit"><div><span class="button">wen etaerc +</span></div></a></div>
            <?php endif; ?>
        </div>
        
        
        <div id="background-bottom">
            <div class="title"><a href=".">trilead knowledge base</a></div>
            <?php foreach (Get::tags($kb) as $tag): ?>
                <a href="?t[]=<?php echo $tag['Tag'] . $querystring; ?>" style="font-size: <?php echo $tag['Size']; ?>em;"><?php echo $tag['Tag']; ?></a>
            <?php endforeach; ?>
        </div>
    </body>
</html>
