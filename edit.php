<?php

eliza\beta\Response::privileged();
eliza\beta\Presentation::show(); 

$Id = $_GET['edit'] != '' ? $_GET['edit'] : (time() . substr(microtime(),2,3));

if (!($Edit = eliza\beta\Feed::Article()->getBy('Id', $_GET['edit'])))
    $Edit = new Article(); 
?>

<form id="articleForm" action="eliza/" method="POST">
<input type="hidden" name="Id" value="<?php echo $Id; ?>" />
<input type="hidden" name="Draft" value="0" />

<div class="article edit">

        <h2>Head</h2>
    <div class="input">
        <label><input style="width: auto;" type="checkbox" name="Draft" value="1" <?php var_dump($Edit->Draft); echo $Edit->Draft ? 'checked' : ''; ?> />draft...</label>
    </div>
        
    <div class="input">
        <input type="text" name="Tags" placeholder="tags..." value="<?php echo implode(', ', $Edit->Tags); ?>" autocomplete="off"/>
    </div>

    <div class="input">
        <input type="text" name="Title" placeholder="title..." value="<?php echo $Edit->Title; ?>" />
    </div>

    <div class="input">
        <input type="text" name="Author" placeholder="author..." value="<?php echo $Edit->Author; ?>" />
    </div>

    <div class="input">
        <textarea name="Headline" id="editor-h" name="editor-h"><?php echo $Edit->Headline; ?></textarea>
    </div>

        <h2>Body</h2>
    <div class="input">
        <textarea name="Text" id="editor-t" name="editor-t"><?php echo $Edit->Text; ?></textarea>
    </div>

    <div class="input">
        <input type="submit" />
    </div>
</div>

</form>