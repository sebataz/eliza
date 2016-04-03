

function Eliza (url) {
    this.url = url;
    this.conentType = 'application/x-www-form-urlencoded; charset=UTF-8';
    this.processData = true;
    this.method = 'GET';
    this.data = null;
    
    this.query = function (query, data, multipartFormData) {
        this.data = data;
        this.method = data ? 'POST' : 'GET';
        this.source = this.url + '?' + query;
        if (multipartFormData) {
            this.conentType = false;
            this.processData = false;
        }
        //console.log(this.source);
        //console.log(this.method);
        //console.log(this.data);
        return this;
    };
    
    this.call = function (callback) {
        $.ajax({
            url : this.source,
            contentType: this.conentType,
            processData: this.processData,
            type: this.method,
            data : this.data,
            success : function (data) {
                callback(data);
            }
        });
    };
}