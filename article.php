<div class="article">
    <div class="title"> 
        <h3><?php echo $Article->Title; ?></h3>
        <span class="author">by <?php echo $Article->Author; ?></span>
        <span class="tags"><?php echo implode(', ', $Article->Tags); ?></span>
    </div>
    <div class="text">
        <?php echo $Article->Text; ?>
    </div>
</div>