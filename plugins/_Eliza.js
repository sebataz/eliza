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
var Eliza = function (service) { 
    this.service = service; 
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
        console.log('query: ' +  this.service + '?' + getData);
        this.xhr.open(post ? 'POST' : 'GET', this.service + '?' + getData, true);
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
                console.log('trying callback');
                
                if (null != JSON.parse(xhr.responseText).oops)
                    Eliza.oops(JSON.parse(xhr.responseText).oops);
                
                else                
                    callback(JSON.parse(xhr.responseText));
                
            } catch (e) {
                console.log(e);
                console.log(xhr.responseText);
            }
        }
    }
}

Eliza.prototype.getJSON = function(callback) {
    this.response(function (response) {
        callback(response.feed);
    });
}

Eliza.prototype.getHTML = function(callback) {
    this.response(function (response) {
        callback(response.html);
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
 

 
Eliza.prototype.queryFeed = function(feed, feed_id) {
    console.log('Eliza AJAX request');
    console.log('feed: ' + feed);
    
    // prepare get variables
    var Get = {};
    Get[feed] = null;
    if (feed_id) Get['id'] = feed_id
    
    // send request
    this.request(Get);
};


