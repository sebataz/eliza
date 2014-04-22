<?php
$Id = $_GET['edit'] != '' ? $_GET['edit'] : (time() . substr(microtime(),2,3));

if (!($KbEdit = eliza\beta\Feed::Kb()->getBy('Id', $_GET['edit'])))
    $KbEdit = new Kb();

?>
<script type="text/javascript" src="/public/plugin/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/public/js/jquery.suggest.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
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
        
        $( ".drag" ).draggable(draggable_list);
        
        
        $( ".drop" ).droppable({
            drop: function( event, ui ) {
                if (!$(ui.draggable).hasClass("dragged")) {
                    $(ui.draggable).addClass("dragged");
                    $(ui.draggable).append("<input type=\"hidden\" name=\"related[]\" value=\"" + ui.draggable[0].id + "\" />");
                }
                    $( this ).append($(ui.draggable));
            }
        });
   
        $(".drop").on("dropout", function(event, ui) {
            if ($(ui.draggable).hasClass('dragged')) {
                $(ui.helper).remove();
                $(ui.draggable).remove();
            }
        });
    });
</script>
<form id="kbForm" action="eliza/?SaveKb" method="POST">
<input type="hidden" name="old-id" value="<?php echo $Id; ?>" />

<div class="kb edit">
    <div class="input">
        <h3>Id</h3>
        <input type="text" name="new-id" value="<?php echo $Id; ?>" />
    </div>
    
    <div class="input">
        <h3>Type</h3>
        <select name="type">
        <?php foreach(eliza\beta\Configuration::get()->Types as $type): ?>
            <option value="<?php echo $type; ?>" <?php echo $type == $KbEdit->Type ? 'selected="selected"' : ''; ?>><?php echo $type; ?></option>
        <?php endforeach; ?>
        </select>
    </div>

    <div class="input">
        <h3>Tags</h3>
        <input type="text" class="suggest" name="tags" value="<?php echo implode(', ', $KbEdit->Tags); ?>" autocomplete="off"/>
    </div>

    <div class="input">
        <h3>Title</h3>
        <input type="text" name="issue" value="<?php echo $KbEdit->Issue; ?>" />
    </div>

    <div class="input">
        <h3>Description</h3>
        <textarea name="description" id="editor" name="editor"><?php echo $KbEdit->Description; ?></textarea>
        <script>
			CKEDITOR.replace( 'editor', {
				uiColor: '#ffffff'
			});

		</script>

    </div>

    <div class="input array">
        <h3>Checklist</h3>
        <input type="text" name="checklist[]" value="<?php echo !empty($KbEdit->Checklist) ? $KbEdit->Checklist[0] : ''; ?>" />
    </div>

    <?php for ($i = 1; $i < count($KbEdit->Checklist); $i ++): ?>
        <div class="input array">
            <input type="text" name="checklist[]" value="<?php echo $KbEdit->Checklist[$i]; ?>" />
        </div>
    <?php endfor; ?>

    <div class="input array">
        <h3>Related</h3>
        <div class="drop">
            <?php foreach ($KbEdit->Related as $id): $Related = eliza\beta\Feed::Kb()->getBy('Id', $id); ?>
                    <div id="<?php echo $KbEdit->Id; ?>" class="kb drag dragged">
                        #<span class="id"><?php echo $Related->Id; ?></span><span class="title">: <?php echo $Related->Issue; ?></span>
                    <input type="hidden" name="related[]" value="<?php echo $Related->Id; ?>" />
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