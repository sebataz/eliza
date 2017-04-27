$.fn.upload = function(location, proxy = new Eliza('eliza/eliza/index.php')) {
    return this.each(function() {
        var uploader = $(this);
    
        $(this).css('margin', '1em');
        $(this).css('min-height', '7em');
        $(this).css('border', 'dashed 1px #ccc');
        $(this).css('padding', '15px');
        $(this).css('font-size', '.7em');
        
        populateUploadedFiles($(this), location, proxy);
        
        // Stop default browser actions
        $(this).bind('dragover dragleave', function(event) {
            event.stopPropagation();
            event.preventDefault();
        })
        
        // Catch drop event
        $(this).bind('drop', function(event) {
            // Stop default browser actions
            event.stopPropagation()
            event.preventDefault()

            // Get all files that are dropped
            files = event.originalEvent.dataTransfer.files
            
            for (var i = 0; i < files.length; i++) {
                proxy.upload(location, files[i]).response(function (feed, html) {
                        appendFileToDiv(uploader, location, feed[0], proxy);
                });
            }
            
            return false;
        })
    });
};

function appendFileToDiv(div, location, file, proxy) {
if (file.IsDir) return;

var subDiv = $('<div />');
subDiv.css('margin', '.3em');

var btDelete = $('<button>delete</button>');
btDelete.click(function (event) {
    proxy
        .query({Node: null, 'args[0]': location, by: 'Filename', val: file.Filename}, {})
        .response(function (feed, html) { populateUploadedFiles(div, location, proxy); });
});

var spFile = $('<span> ' + file.Url + '</span>');

subDiv.append(btDelete);
subDiv.append(spFile);
div.append(subDiv);
}

function populateUploadedFiles(div, location, proxy) {
    div.html('');
    proxy
        .query({Node: null, 'args[0]': location})
        .response(function (feed, html) {
            for (var i = 0; i < feed.length; i++)
                appendFileToDiv(div, location, feed[i], proxy);
        });
}
