(function ($) {
    $.fn.suggest = function() {
        $( this ).keyup(function() {
            var input = $( this );
            var term = $( this ).val();
            
            term = term.match(/,/) ? term.split(",") : term;
            term = term instanceof Array ? term[term.length-1] : term;
            term = term.trim();
            
            $( ".suggestion-box" ).remove();
            
            if (term.length > 0) {
                console.log("suggest for " + term);
                
                $.ajax({
                    url: location.origin+"/service/proxy.php?tags&c=kb&t=" + term + "&tc=0",
                    context: $( this ).parent()
                }).done(function( suggestion ) {
                console.log(suggestion);
                    if (suggestion.length > 0) {
                        var box = $("<div class=\"suggestion-box\" />");
                        $( this ).append(box);
                        
                        console.log( "suggestions received:" );
                        console.log( suggestion );
                        
                        suggestion.forEach( function( tag ) {
                            var link = $( "<span>" + tag + "</span>" );
                            link.click(function (e) { 
                                console.log("append " + tag + " to tags");
                                value = input.val();
                                value = value.match(/,/) ? (value.slice(0, value.lastIndexOf(",")) + ", ") : '';
                                input.val(value + tag + ", ");
                            });
                        
                            box.append(link);
                        });
                    }
                });
            }
        });
    };
}(jQuery));