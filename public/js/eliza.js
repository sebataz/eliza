
var proxy_url = location.origin + '/eliza/index.php';


var getParams = function(data, url) {
    var arr = [], str;
    for(var name in data) {
        arr.push(name + '=' + encodeURIComponent(data[name]));
    }
    str = arr.join('&');
    if(str != '') {
        return url ? (url.indexOf('?') < 0 ? '?' + str : '&' + str) : str;
    }
    return '';
}

var Eliza = {
    query: function (ops) {
        if(typeof ops == 'string') ops = { query: ops };
        ops.method = ops.method || 'get';
        ops.data = ops.data || {};
        ops.source =  proxy_url + ops.query
        
        var api = {
            host: {},
            request: function (ops) {
                // creating the XMLHttpRequest object
                var postBody = '', self = this;
                this.xhr = null;
                if(window.ActiveXObject) { this.xhr = new ActiveXObject('Microsoft.XMLHTTP'); }
                else if(window.XMLHttpRequest) { this.xhr = new XMLHttpRequest(); }
                
                // getting feedback of the request
                if(this.xhr) {
                    this.xhr.onreadystatechange = function() {
                        if(self.xhr.readyState == 4 && self.xhr.status == 200) {
                            var result = JSON.parse(result);
                            self.doneCallback && self.doneCallback.apply(self.host, [result, self.xhr]);
                        } else if(self.xhr.readyState == 4) {
                            self.failCallback && self.failCallback.apply(self.host, [self.xhr]);
                        }
                        self.alwaysCallback && self.alwaysCallback.apply(self.host, [self.xhr]);
                    }
                } 
                
                // sending the request
                if(ops.method == 'get') {
                    this.xhr.open("GET", ops.source + getParams(ops.data, ops.source), true);
                } else {
                    this.xhr.open(ops.method, ops.source, true);
                    this.setHeaders({
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-type': 'application/x-www-form-urlencoded'
                    });
                }
                if(ops.headers && typeof ops.headers == 'object') {
                    this.setHeaders(ops.headers);
                }       
                setTimeout(function() { 
                    ops.method == 'get' ? self.xhr.send() : self.xhr.send(getParams(ops.data)); 
                }, 20);
                
                return this;
            },
            done: function (callback) {
                this.doneCallback = callback;
                return this;
            },
            fail: function (callback) {
                this.failCallback = callback;
                return this;
            }
        }
        
        return api.request(ops);
    }

}