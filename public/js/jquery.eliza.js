

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
    
    this.ask = function (callback) {
        $.ajax({
            url : this.source,
            type: this.method,
            success : callback
        });
    };
}