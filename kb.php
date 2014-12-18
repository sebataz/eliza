
<script type="text/javascript" src="/public/plugin/zeroclipboard/dist/ZeroClipboard.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
    
                var clientTarget = new ZeroClipboard( $("#target-to-copy"), {
              moviePath: "http://www.paulund.co.uk/playground/demo/zeroclipboard-demo/zeroclipboard/ZeroClipboard.swf",
              debug: false
            } );

            clientTarget.on( "load", function(clientTarget)
            {
                $('#flash-loaded').fadeIn();

                clientTarget.on( "complete", function(clientTarget, args) {
                    clientTarget.setText( args.text );
                    $('#target-to-copy-text').fadeIn();
                } );
            } );
        client.on( "copy", function (event) {
      var clipboard = event.clipboardData;
      clipboard.setData( "application/rtf", $("#hidden-description").text() );
   });
    });
</script>


<div class="kb">
    <div class="issue"><h1><?php echo $Kb->Issue; ?></h1></div>
    
    <div class="type"><?php echo $Kb->Type; ?></div>
    
    <div class="tags">
    <?php foreach ($Kb->Tags as $tag): ?>
        <a href="?t[]=<?php echo $tag; ?>"><?php echo $tag; ?></a>
    <?php endforeach; ?>
    [<a href="?edit=<?php echo $Kb->Id; ?>">edit</a>]
    [<a href="#" id="target-to-copy" data-clipboard-target="hidden-description">copy to clipboard</a>]
    </div>
    
    <div class="clear"></div>
    
    <div class="description">
        <h2>Description</h2>
        <?php echo $Kb->Description; ?>
        
        
    </div>
    
    <div id="hidden-description">
        <?php echo $Kb->Description; ?>
    </div>
    
    <?php if (!empty($Kb->Checklist)): ?>
    <div class="checklist">
        <h2>Checklist</h2>
        <ul>
        <?php foreach ($Kb->Checklist as $question): ?>
            <li><?php echo $question; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div>
    <?php foreach ($Kb->Related as $id): $Related = eliza\beta\Feed::Kb()->getBy('Id', $id); ?>
        <div class="solution">
            <a class="title" href="?kb=<?php echo $id, '&', $querystring; ?>">#<span class="id"><?php echo $id; ?></span>: <?php echo $Related->Issue; ?></a>
            <?php echo $Related->Description; ?>
        </div>
    <?php endforeach; ?>
    </div>
    
</div>
