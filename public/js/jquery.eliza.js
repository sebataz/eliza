

function Eliza (url) {
    this.url = url;
    this.method = 'GET';
    this.data = null;
    
    this.query = function (query, data, verbose) {
        this.data = data;
        this.method = data ? 'POST' : 'GET';
        this.source = this.url + '?' + query + (verbose ? 'verbose=1' : '');
        console.log(this.source);
        return this;
    };
    
    this.call = function (callback) {
        $.ajax({
            url : this.source,
            type: this.method,
            success : function (data) {
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
                console.log(data);
                callback(data, html);
            }
        });
    };
}