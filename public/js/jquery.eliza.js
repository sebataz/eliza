

function Eliza (url) {
    this.url = url;
    this.method = 'GET';
    this.data = null;
    
    this.query = function (query, data) {
        this.data = data;
        this.method = data ? 'POST' : 'GET';
        this.source = this.url + '?' + query;
        console.log(this.source);
        console.log(this.method);
        console.log(this.data);
        return this;
    };
    
    this.call = function (callback, verbose) {
        $.ajax({
            url : this.source + (verbose ? '&verbose=1' : ''),
            type: this.method,
            data : this.data,
            success : function (data) {
            console.log(data);
                var html = $('<div />');
                data.forEach(function(item, index, collection) {
                    //console.log('');
                    //console.log('item-'+index||item.Id);
                    var div = $('<div />');
                    div.attr('id', index);
                    for (var prop in item) {
                        //console.log(prop+":"+item[prop]);
                        div.append($('<div class="' + prop + '">' + item[prop] + '</div>'));
                    }
                    html.append(div);
                });
                
                callback(data, html);
            }
        });
    };
}