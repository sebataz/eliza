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
    var editable = this;
    
    var button = $('<button />');
    button.text('code');
    button.insertBefore(this);
    
    var textarea = $('<textarea />');
    textarea.insertBefore(this);
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