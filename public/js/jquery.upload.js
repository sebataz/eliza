function callbackDelete(location, filename) {
    return function () {
        console.log(filename);
        new Eliza('eliza/eliza/index.php')
            .query('Node&args[]=' + location
                + '&by=Filename&val=' + filename, 1)
            .call(function (data) {
                console.log(data);
            });
    }
}

function buildFileList(div, location) {
    div.html('');
    new Eliza('eliza/eliza/index.php')
        .query('Node&args[]=' + location)
        .call(function (data) {
            var feed = data.feed;
            
            console.log(data);
            for (var i = 0; i < feed.length; i++) {
                var Feed = feed[i];
                
                if (Feed.IsDir) continue;
                
                var subDiv = $('<div />');
                subDiv.css('margin', '.3em');
                
                var btDelete = $('<button>delete</button>');
                btDelete.click(callbackDelete(location, Feed.Filename));
                btDelete.click(function (event) {$(this).parent().remove()});
                
                var spFile = $('<span> ' + Feed.Url + '</span>');
                
                subDiv.append(btDelete);
                subDiv.append(spFile);
                div.append(subDiv);
            }
        });    
}

$.fn.upload = function(location) {
    return this.each(function() {
        var files = [];
        var Eli = new Eliza('eliza/eliza/index.php');
        var formData = new FormData();
        formData.append('location', location);
        
        var $this = $(this);
        $this.css('margin', '1em');
        $this.css('width', '100%');
        $this.css('min-height', '7em');
        $this.css('border', 'dashed 1px #ccc');
        $this.css('padding', '15px');
        $this.css('font-size', '.7em');
        buildFileList($this, location);
        
    
        // Stop default browser actions
        $(this).bind('dragover dragleave', function(event) {
            event.stopPropagation()
            event.preventDefault()
        })
        
        // Catch drop event
        $(this).bind('drop', function(event) {
            // Stop default browser actions
            event.stopPropagation()
            event.preventDefault()

            // Get all files that are dropped
            files = event.originalEvent.dataTransfer.files
            console.log(files);
            
            for (var i = 0; i < files.length; i++) {
              formData.append('file[]', files[i]);
            }
            
            Eli.query(null, formData, true).call(function (data) {
                console.log(data);
                buildFileList($this, location);
            });
           
            
            return false
        })
    });
};
