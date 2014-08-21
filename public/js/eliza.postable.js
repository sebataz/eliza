// metti un save su ogni editable div
// crea il post 
// deliver the post to eliza
    // spcify feed and data
$.fn.postable = function(feed, properties) {
    return this.each(function() {
        
        var postable = $(this);
        for (var i = 0; i < properties.length; i++)
            postable.find("." + properties[i].toLowerCase()).attr("contenteditable", "true");
        
        var save = $('<div class="save-feed-link">').html('[<a href="#" class="save-feed-link">save</a>]');
        save.css('float', 'right');
        save.appendTo($(this));
        save.click(function (e) {
            save.find('a').text('saving...');
            
            
            console.log('saving content for ' + feed);
            
            // post
            var proxy = location.origin + "/eliza/index.php?" + feed;
            var post = {"Id":1};
            
            for (var i = 0; i < properties.length; i++)
                post[properties[i]] = postable.find("." + properties[i].toLowerCase()).html();
            
            console.log(post);
            $.post(
                proxy,
                post,
                function() {
                    save.find('a').text('save');
                    console.log("saved content");
                }
            );
        });
    
    });
};