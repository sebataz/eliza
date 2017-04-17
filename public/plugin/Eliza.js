//----------------------------------------------------------------------------//
//                                  Eliza.js                                  //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                         javascript Array extension                         //
//----------------------------------------------------------------------------//
Array.prototype.first = function () { return this[0]; };

Array.prototype.limit = function (limit, offset = 0) { 
    return this.slice(offset, offset+limit); 
};

Array.prototype.filterBy = function (property, value) {
    var ar = new Array();
    
    for (var i = 0; i < this.length; i++) {
        if (this[i][property] == value)
            ar.push(this[i]);
    }
    
    return ar;
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
Eliza.prototype.query = function (get, post) {
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

Eliza.prototype.callback = function (callback) {
    var xhr = this.xhr;
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState > 3) {
            callback(xhr.responseText);
        }
    }
}

Eliza.prototype.response = function (callback) {
    this.callback(function (data) {
        try {
        
            if (null != JSON.parse(data).oops)
                Eliza.oops(JSON.parse(data).oops);
            
            else                
                callback(JSON.parse(data).feed, JSON.parse(data).html);
                
        } catch (e) {
            console.log(e);
            console.log(data);
        }
    });
    
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
Eliza.prototype.upload = function (location, file) {
    return this.query({}, {'location': location, 'file': file});
};
    
Eliza.prototype.queryFeed = function(feed, callback) {
    return this.query({[feed]: null});
};

Eliza.prototype.writeFeed = function(feed, feed_id, Feed, callback) {
    return this.query({[feed]: null, 'by': 'Id', 'val': feed_id}, Feed);
};

Eliza.prototype.deleteFeed = function(feed, id_feed, callback) {
    return this.writeFeed(feed, id_feed, {}, callback);
}