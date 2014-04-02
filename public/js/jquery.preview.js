$.fn.preview = function() {
    return this.each(function() {
        $(this).click(function (e) {
            e.preventDefault();
            var loaded = false;
            var foreground = $('<div id="foreground"/>');
            var preview = $('<div id="preview"/>');
            var spinner = $('<div class="spinner fixed"/>');
            var image = $('<img>');
            
            foreground.append(spinner);

            image.attr('src', $(this).attr('href'));
            image.on('load', function(){
                loaded = true;
                preview.show();
                console.log('image size: ' + preview.width() + ', ' + preview.height());
                
                var limit_w, limit_h;
                limit_w = foreground.width() * .85;
                limit_h = foreground.height() * .85;
                console.log('foreground limits: ' + limit_w + ', ' + limit_h);
                
                if (preview.width() >= limit_w || preview.height() >= limit_h) {
                    var landscape = preview.width() > preview.height();
                    var ratio = (landscape ? limit_w : limit_h) / (landscape ? preview.width() : preview.height());
                    var ratio_w = limit_w / preview.width();
                    var ratio_h = limit_h / preview.height();
                    
                    console.log('preview ratio: ' + ratio_w + ', ' + ratio_h);
                    
                    image.height(preview.height() * ((ratio_w < ratio_h) ? ratio_w : ratio_h));
                    // if (preview.width() >= limit_h) {
                        // image.width(preview.width() * ratio_w);
                    // } else if (preview.height() >= limit_h) {
                        // image.height(preview.height() * ratio_h);
                    // }
                }
                
                preview.css("top", Math.max(0, ((foreground.height() - preview.height()) / 2)) + "px");
                preview.css("left", Math.max(0, ((foreground.width() - preview.width()) / 2)) + "px");
                spinner.remove();
            });

            preview.hide();
            
            preview.append(image);
            preview.css("position", "absolute");
            preview.click(function (e) { foreground.remove(); });
            
            foreground.append(preview);
            foreground.click(function (e) { foreground.remove(); });
            
            $(document.body).append(foreground);
        });
    });
};