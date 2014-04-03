<div class="kb">
    <div class="type"><?php echo $Issue['Type']; ?></div>
    <div class="tags">
    <?php foreach ($Issue['Tags'] as $tag): ?>
    <a href="?t[]=<?php echo $tag; ?>"><?php echo $tag; ?></a>
    <?php endforeach; ?>
    [<a href="editor.php?id=<?php echo $Issue['File']['Title']; ?>">edit</a>]
    </div>
    <div class="clear"></div>
    <div class="issue"><h1><?php echo $Issue['Issue']; ?></h1></div>
    
    <div class="description">
        <h2>Description</h2>
        <?php echo $Issue['Description']; ?>
    </div>
    
    <?php if (isset($Issue['Checklist'])): ?>
    <div class="checklist">
        <h2>Checklist</h2>
        <ul>
        <?php foreach ($Issue['Checklist'] as $question): ?>
            <li><?php echo $question; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <?php if (isset($Issue['Solution'])): ?>
    <?php for ($i = 0; $i < count($Issue['Solution']); $i++): ?>
    <div class="solution <?php echo $i%2==0 ? 'even' : 'odd'; ?>">
        <h3>Solution <?php echo $i+1; ?></h3>
        <?php echo $Issue['Solution'][$i]; ?>
    </div>
    <?php endfor; ?>
    <?php endif; ?>
    
</div>