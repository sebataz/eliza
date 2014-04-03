<?php
include 'lib/service.php';

// form options
$issue_type = array('FAQ', 'Issue', 'Warning', 'Error', 'Catastrophe');
$no_checklist = 10;
$no_solution = 5;


$issue_id = isset($_GET['id'])?$_GET['id']:null;
$kb = Get::kb($issue_id);
$kb_to_edit = $issue_id != null? $kb[0] : array('Type' => '',
                                                'Tags' => '',
                                                'Issue' => '',
                                                'Description' => '',
                                                'Checklist' => array(null, null, null, null, null, null, null, null, null, null),
                                                'Solution' => array(null, null, null, null, null));



                                           

 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Trilead - Knowledge Base</title>
        <link rel="stylesheet" type="text/css" href="../public/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../public/css/theme.css">
        <link rel="stylesheet" type="text/css" href="../public/css/kb-theme.css">
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>     

        <script src="../public/plugin/ckeditor/ckeditor.js"></script>
        
        <link href="http://alexgorbatchev.com/pub/sh/current/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shCore.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shAutoloader.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPlain.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushBash.js" type="text/javascript"></script>
        <script src="http://alexgorbatchev.com/pub/sh/current/scripts/shBrushPhp.js" type="text/javascript"></script>
        
        <script src="../public/js/shBrushNginx.js" type="text/javascript"></script>
        <script src="../public/js/jquery.suggest.js" type="text/javascript"></script>
        
        <script type="text/javascript">        
            SyntaxHighlighter.defaults['toolbar'] = false;
            SyntaxHighlighter.all();
            
            $(document).ready(function () {
                $(".tags").suggest()
            });
        </script>
    </head>
    <body>
        <div id="background">
        
            <div id="background-top"></div>
            <div id="background-bottom"><div class="title">trilead knowledge base</div></div>
            
        
        </div>
        <div id="content">
            <div id="knowledge-base">
                <div class="kb form">
                    <div id="edit" class="form">
                    <form action="service/proxy.php" method="POST">
                    <input type="hidden" name="save-kb" value="1">
                    <div class="input">
                        <label for="name">type</label>
                        <select name="type">
                        <?php foreach($issue_type as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo $type == $kb_to_edit['Type'] ? 'selected="selected"' : ''; ?>><?php echo $type; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                   <div class="input">
                        <label for="tags">tags</label>
                        <input type="text" class="suggest tags" name="tags" value="<?php echo is_array($kb_to_edit['Tags']) ? implode(', ', $kb_to_edit['Tags']) : ''; ?>" autocomplete="off"/>
                    </div>
                    <div class="input">
                        <label for="issue">issue</label>
                        <input type="text" name="issue" value="<?php echo $kb_to_edit['Issue']; ?>"/>
                    </div>
                    <div class="input">
                        <label for="description">description</label>
                        <textarea name="description" class="ckeditor"><?php echo $kb_to_edit['Description']; ?></textarea>
                    </div>
                    <div class="input">
                        <label for="checklist">checklist</label>
                        <?php for ($i = 0; $i < $no_checklist; $i++): ?>
                        <input type="text" name="checklist[]" value="<?php echo isset($kb_to_edit['Checklist'][$i])?$kb_to_edit['Checklist'][$i]:null; ?>"/>
                        <?php endfor; ?>
                    </div>
                    <?php for ($i = 0; $i < $no_solution; $i++): ?>
                    <div class="input">
                        <label for="solution<?php echo $i+1; ?>">solution <?php echo $i+1; ?></label>
                        <textarea name="solution[]" class="ckeditor"><?php echo isset($kb_to_edit['Solution'][$i])?$kb_to_edit['Solution'][$i]:null; ?></textarea>
                    </div>
                    <?php endfor; ?>
                    <div class="input">
                        <input class="submit" type="submit">
                    </div>
                    
                    <input type="hidden" name="id" value="<?php echo !$issue_id ? time().substr(microtime(),2,3) : $issue_id; ?>" /> 
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="utilities">
            [<a href=".">main</a>]:
            <form action='editor.php' method="GET" style="display: inline;">
                <select name="id">
                    <option></option>
                <?php foreach (Get::kb(null, 'Issue') as $issue): ?>
                    <option value="<?php echo $issue['File']['Title']; ?>"><?php echo '#' . $issue['File']['Title'] . ': ' . $issue['Issue']; ?></option>
                <?php endforeach; ?>
                </select>
                <input type="submit" />
            </form>
        </div>
    </body>
</html>
