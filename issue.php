<div class="kb">
    <div class="issue"><h1><?php echo $Issue['Issue']; ?></h1></div>
    
    <div class="type"><?php echo $Issue['Type']; ?></div>
    
    <div class="tags">
    <?php foreach ($Issue['Tags'] as $tag): ?>
        <a href="?t[]=<?php echo $tag; ?>"><?php echo $tag; ?></a>
    <?php endforeach; ?>
    [<a href="?edit=<?php echo $Issue['File']['Title']; ?>">edit</a>]
    </div>
    
    <div class="clear"></div>
    
    <div class="description">
        <h2>Description</h2>
        <?php echo $Issue['Description']; ?>
    </div>
    
    <?php if (!empty($Issue['Checklist'])): ?>
    <div class="checklist">
        <h2>Checklist</h2>
        <ul>
        <?php foreach ($Issue['Checklist'] as $question): ?>
            <li><?php echo $question; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div>
    <?php foreach ($Issue['Related'] as $id): $Related = reset(Get::kb($id)); ?>
        <div class="solution">
            <h3><?php echo $Related['Issue']; ?></h3>
            <?php echo $Related['Description']; ?>
        </div>
    <?php endforeach; ?>
    </div>
    
</div>