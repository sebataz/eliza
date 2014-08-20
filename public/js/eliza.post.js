// metti un save su ogni editable div
// crea il post 
// deliver the post to eliza
    // spcify feed and data
$.fn.post = function(feed) {
    return this.each(function() {
        var property = $(this).attr('class');
        var save_action = $('<a href="#" class="save_icon">');
        
        property = property.charAt(0).toUpperCase() + property.slice(1);
        save_action.click(function (e) {
            var data = $(this).contents();
            data.remove($('img'));
            
            console.log('saving content for ' + feed + '.' + property);
            console.log(data);
        });
    
        var save_icon = $('<img>');
        save_icon.attr('src', 'public/img/save.png');
        save_icon.css('float', 'right')
        save_icon.css('width', '2em');
        save_icon.css('height', '2em');
        
        save_action.append(save_icon);
        $(this).append(save_action);
    });
};