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
        
        Eli.query('Node&args[]=' + location).call(function (data) {
            console.log(data);
            for (var i = 0; i < data.feed.length; i++) {
                $this.append('<div>' + data.feed[i].Url + '</div>');
            }
        });
    
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
                $this.html('');
                for (var i = 0; i < data.feed.length; i++) {
                    $this.append('<div>' + data.feed[i].Url + '</div>');
                }
            });
           
            
            return false
        })
    });
};
