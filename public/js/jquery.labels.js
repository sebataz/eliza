
(function($) {
    $.fn.labels = function(offset) {   
            var label = $(this).find($('.label'));
            
            
            label.each(function () {
                var Label = $("<div />");
                Label.css({
                    'position' : 'absolute',
                    'left' : '190px',
                    'top' : offset,
                    'background-color' : $(this).css('background-color'),
                    'font-family' : 'Courier',
                    'font-weight' : 'bold',
                    'text-transform' : 'uppercase',
                    'margin-left': '-30px',
                    'width' : '100px',  
                    'padding': '10px 15px',
                    'z-index' : '-1'
                });
                
                Label.mouseenter(function () {
                    $(this).css({'margin-left': '-60px'});
                });
                
                Label.mouseleave(function () {
                    $(this).css({'margin-left': '-30px'});
                });
                
                var title = "";
                for (var c = 0; c < $(this).find('a').text().length; c++) {
                    title += $(this).find('a').text()[c] + "<br />";
                }
                
                Label.html(title);
                
                var link = $("<a href=\""+$(this).find($('a')).attr('href')+"\"></a>");
                link.html(Label);
                
                
                $(this).parent().parent().prepend(link);
                offset += Label.height() + 20 + 5;
                
            });
            
            $(this).find('.labels').remove();
                
                

    };
})(jQuery);
