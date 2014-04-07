<?php
$Id = $_GET['edit'] != '' ? $_GET['edit'] : (time() . substr(microtime(),2,3));

$Issue = Get::kb($_GET['edit']);
$Issue = count($Issue) == 1 ? $Issue[0] : array(
    'Type' => '',
    'Tags' => array(),
    'Issue' => '',
    'Description' => '',
    'Checklist' => array(),
    'Related' => array()
);
?>
<script type="text/javascript" src="/public/plugin/raptor/raptor.js"></script>
<link href="/public/plugin/raptor/raptor-front-end.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/public/js/jquery.suggest.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#<?php echo $_GET['edit']; ?>").remove();
    
        $(".suggest").suggest();
        $(".array").keydown( function(e) {
            if (e.keyCode == 9 && !e.shiftKey) {
                var push = $( this ).clone(true);
                push.insertAfter($( this ));
                push.find("input").val('');
                push.find("textarea").val('');  
                push.find(".editor").html('');  
                push.find("h3").remove();
            }
        });
        
        $( ".drag" ).draggable({
            helper: 'clone',
            revert: 'invalid',
            revertDuration: 0,
            appendTo: 'body',
        });
        
        $( ".dragged" ).draggable();
        
        $( ".drop" ).droppable({
            drop: function( event, ui ) {
                var dragged = $( "<div class=\"dragged\"></div>" );
                dragged.append(ui.draggable[0]);
                dragged.append("<input type=\"hidden\" name=\"related[]\" value=\"" + ui.draggable[0].id + "\" />");
                $( this ).append(dragged);
            }
        });
   
        $(".drop").on("dropout", function(event, ui) {
            if ($(ui.draggable).parent().hasClass('dragged')) {
                $(ui.helper).remove();
                $(ui.draggable).parent().remove();
            }
        });
        
        $(".editor").raptor({
            name: 'inline',
            classes: 'raptor-editing-inline',
            autoEnable: true,
            draggable: false,
            unify: false,
            unloadWarning: false,
            reloadOnDisable: true,
            plugins: {
                unsavedEditWarning: false,
                dock: {
                    dockToElement: true,
                    docked: true,
                    persist: false
                }
            },
            layouts: {
                toolbar: {
                    uiOrder: [
                        ['viewSource'],
                        ['textBold', 'textItalic', 'textUnderline', 'textStrike'],
                        ['textBlockQuote'],
                        ['listOrdered', 'listUnordered'],
                        ['textSizeDecrease', 'textSizeIncrease'],
                        ['linkCreate', 'linkRemove']
                    ]
                }
            }
        });
    });
</script>
<form id="kbForm" action="service/proxy.php" method="POST">
<input type="hidden" name="save-kb" value="1" />
<input type="hidden" name="old-id" value="<?php echo $Id; ?>" />

<div class="kb edit">
    <div class="input">
        <h3>Id</h3>
        <input type="text" name="new-id" value="<?php echo $Id; ?>" />
    </div>
    
    <div class="input">
        <h3>Type</h3>
        <select name="type">
        <?php foreach($knowledge_type as $type): ?>
            <option value="<?php echo $type; ?>" <?php echo $type == $Issue['Type'] ? 'selected="selected"' : ''; ?>><?php echo $type; ?></option>
        <?php endforeach; ?>
        </select>
    </div>

    <div class="input">
        <h3>Tags</h3>
        <input type="text" class="suggest" name="tags" value="<?php echo implode(', ', $Issue['Tags']); ?>" autocomplete="off"/>
    </div>

    <div class="input">
        <h3>Title</h3>
        <input type="text" name="issue" value="<?php echo $Issue['Issue']; ?>" />
    </div>

    <div class="input">
        <h3>Description</h3>
        <textarea name="description" class="editor"><?php echo $Issue['Description']; ?></textarea>
    </div>

    <div class="input array">
        <h3>Checklist</h3>
        <input type="text" name="checklist[]" value="<?php echo !empty($Issue['Checklist']) ? $Issue['Checklist'][0] : ''; ?>" />
    </div>

    <?php for ($i = 1; $i < count($Issue['Checklist']); $i ++): ?>
        <div class="input array">
            <input type="text" name="checklist[]" value="<?php echo $Issue['Checklist'][$i]; ?>" />
        </div>
    <?php endfor; ?>

    <div class="input array">
        <h3>Related</h3>
        <div class="drop">
            <?php foreach ($Issue['Related'] as $id): $Related = reset(Get::kb($id)); ?>
                <div class="dragged">
                    <div id="<?php echo $Issue['Id']; ?>" class="kb drag">
                        #<span class="id"><?php echo $Related['Id']; ?></span><span class="title">: <?php echo $Related['Issue']; ?></span>
                    </div>
                    <input type="hidden" name="related[]" value="<?php echo $Related['Id']; ?>" />
                </div>
            <?php endforeach; ?>
            <span>drop an item to relate</span>
        </div>
    </div>

    <div class="input">
        <span class="button" onClick="kbForm.submit();">evas</span>
    </div>
</div>

</form>