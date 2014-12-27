(function ($) {
    $.fn.typewriter = function() {
        
        var text = $(this).html(),
            box = $(this);
        
        return this.each(function () {
            var box = $(this),
                typeOut = true,
                timer,
                letter,
                delay,
                pos = 0;
            
            (function type() {
                delay = Math.floor(Math.random() * (150 - 50 + 1)) + 50;
                letter = text.substr(pos++, 1);
                
                if (letter === '<') typeOut = false;
                if (letter === '>') typeOut = true;
                
                box.html(text.substr(0, pos));
                
                if (pos <= text.length) {
                    if (typeOut)
                        setTimeout(type, delay);
                    else
                        type();
                }
            })(0);
        });
    
    };
}(jQuery));

