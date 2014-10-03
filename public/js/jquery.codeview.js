$.fn.codeview = function(opts) { 
  // set up default options 
  var defaults = {  
    toggleView: function (html, view) {
            console.log('toggle view');
            view.html(html);
    }, 
    toggleCode: function (html, code) {
            console.log('toggle code');
            code.val(html);
    }
  }; 

  // Overwrite default options 
  // with user provided ones 
  // and merge them into "options". 
  var opts = $.extend({}, defaults, opts); 


  return this.each(function() { 
    var codeview = $('<div />');
    codeview.css('position', 'relative');
    codeview.css('margin', '1em');
    codeview.css('border', 'dashed 1px #ccc');
    codeview.css('padding', '15px');
    
    var editable = $(this);
    codeview.insertBefore(editable);
    editable.appendTo(codeview);
    
    var button = $('<button />');
    button.css('position', 'absolute');
    button.css('top', '0');
    button.css('left', '0');
    button.text('code');
    codeview.append(button);
    
    var textarea = $('<textarea />');
    textarea.css('margin', '0');
    textarea.css('padding', '.7em');
    textarea.css('width', '98%');
    textarea.appendTo(codeview);
    textarea.hide();
    
    button.click(function (e) {
        if (button.text() == 'code') {
            button.text('view');
            
            opts.toggleCode($(editable).html(), $(textarea))
            $(textarea).show();
            $(editable).hide();
        } else if (button.text() == 'view') {
            button.text('code');
            
            opts.toggleView($(textarea).val(), $(editable))
            $(textarea).hide();
            $(editable).show();
        }
    });
  }); 
};