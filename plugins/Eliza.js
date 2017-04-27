//----------------------------------------------------------------------------//
//                                  Eliza.js                                  //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                         javascript Array extension                         //
//----------------------------------------------------------------------------//
Array.prototype.first = function () { return this[0]; };

Array.prototype.getBy = function (property, value) {
    var ar = new Array();
    
    for (var i = 0; i < this.length; i++) {
        if (this[i][property] == value)
            ar.push(this[i]);
    }
    
    return ar;
};

Array.prototype.limit = function (limit, offset = 0) { 
    return this.slice(offset, offset+limit); 
};

Array.prototype.sortBy = function (property, order = true) {
    return this.sort(function(a, b) {
        var textA = a[property].toUpperCase();
        var textB = b[property].toUpperCase();
        return (textA < textB) ? (order?-1:1) : (textA > textB) ? (order?1:-1) : 0;
    });
};



//----------------------------------------------------------------------------//
//                                 class Eliza                                //
//----------------------------------------------------------------------------//
var Eliza = function (proxy) { 
    this.proxy = proxy; 
    this.xhr;
};

 
/**
 * Ajax request 
 */
Eliza.prototype.request = function (get, post) {
    try {
        // create compatible xhr request
        if (window.ActiveXObject) this.xhr = new ActiveXObject();
        else if (window.XMLHttpRequest) this.xhr = new XMLHttpRequest();
        
        // encode data for query
        // method: POST
        if (post) {
            var postData = new FormData();
            for (var property in post)
                postData.append(property, post[property]);
        
        }
        
        // method: GET
        if (get) {
            getData = Array();
            for (var property in get)
                getData.push(
                    encodeURIComponent(property) + 
                    "=" + 
                    encodeURIComponent(get[property])
                );
            
            getData = getData.join('&');
        }
        
        // send request
        this.xhr.open(post ? 'POST' : 'GET', this.proxy + '?' + getData, true);
        this.xhr.send(postData);
    } catch (e) {
        console.log(e);
    }
    
    return this;
};

Eliza.prototype.response = function (callback) {
    var xhr = this.xhr;
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState > 3) {
            try {
        
                if (null != JSON.parse(xhr.responseText).oops)
                    Eliza.oops(JSON.parse(xhr.responseText).oops);
                
                else                
                    callback(
                        JSON.parse(xhr.responseText).feed, 
                        JSON.parse(xhr.responseText).html);
                
            } catch (e) {
                console.log(e);
                console.log(xhr.responseText);
            }
        }
    }
}

/**
 * Interface
 */
Eliza.oops = function (excuse) {
    console.log(excuse);
    
    var body = document.querySelector('body');
    var oops = document.createElement('div');
    body.insertBefore(oops, body.firstChild);
    oops.innerHTML 
        = '<div id="oops" style="width: 100%; padding: .3em; background-color: #ffffe6; font-size: .7em;">'
        + '<span style="font-weight: bold">Oops: </span>'
        + excuse
        + '</div>';
        
    
    window.setTimeout(function () {
        oops.parentNode.removeChild(oops);
    }, 3000);
};

/**
 * Feed handling
 */ 
 
Eliza.prototype.query = function(feed, feed_id, callback) {
    
};

Eliza.prototype.save = function(feed, feed_id, Feed, callback) {
};

Eliza.prototype.delete = function(feed, id_feed, callback) {
}

Eliza.prototype.upload = function (location, file) {
    return this.request({}, {'location': location, 'file': file});
};


