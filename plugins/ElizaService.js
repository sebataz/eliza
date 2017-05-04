(function(){//----------------------------------------------------------------//
//                               ElizaService.js                              //
//----------------------------------------------------------------------------//

//----------------------------------------------------------------------------//
//                            class ElizaService:                             //
//                          implements ajax request                           //
//----------------------------------------------------------------------------//
this.ElizaService = function ( service , feed ) { 
    this.service = service; 
    this.feed = feed;
    
    this._request = null;
}

ElizaService.oops = function (excuse) {
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
    
ElizaService.ajax = function (url, get, post) {
    try {
        
        var GetVariables = Array();
        var PostVariables = post ? new FormData() : null;
        
        // encode data for query
        if (post) for (var property in post)
                PostVariables.append(property, post[property]);
                
        if (get) Object.keys(get).forEach(function (property) {
            GetVariables.push( encodeURIComponent(property) + "=" + (get[property] ? encodeURIComponent(get[property]) : '') );
        });
        
        // create compatible request object
        if (window.ActiveXObject) var xhr = new ActiveXObject();
        else if (window.XMLHttpRequest) var xhr = new XMLHttpRequest();
        
        // send request
        xhr.open(post ? 'POST' : 'GET', url + '?' + GetVariables.join('&'), true);
        xhr.send(PostVariables);
        
        return xhr;
    } catch (e) {
        console.log(e);
    }
};
    
ElizaService.prototype.query = function( params ) {
    var Get = Array();
    Get[this.feed] = null;
    Get['id'] = null;
    Get['by'] = null;
    Get['val'] = null;
    Get['srt'] = null;
    Get['ord'] = null;
    Get['lmt'] = null;
    Get['off'] = null;
    
    for ( param in params )
            Get[param] = params[param];
    
    this._request = ElizaService.ajax(this.service, Get, null);
    
    return this;
}
    
ElizaService.prototype.response = function ( callback ) {
    var _Service = this;
    _Service._request.onreadystatechange = function () {
        if (_Service._request.readyState > 3) {
            try {
            
                if (null != JSON.parse(_Service._request.responseText).oops)
                    ElizaService.oops(JSON.parse(_Service._request.responseText).oops);
                
                else if (null != callback) {
                    var CollectionFeed = new ElizaService.Collection();
                
                    response_feed = JSON.parse(_Service._request.responseText).feed;
                    
                    for (var feed in response_feed)
                        if (response_feed[feed])
                            CollectionFeed.append(
                                new ElizaService.Feed(_Service, response_feed[feed]));
                    
                    callback(
                        CollectionFeed, 
                        JSON.parse(_Service._request.responseText).html);
                }
                
            } catch (e) {
                console.log(e);
                console.log(_Service._request.responseText);
            }
        }
    };
}



//----------------------------------------------------------------------------//
//                             sub-class Collection:                          //
//                    reflects eliza\CollectionQuery methods                  //
//----------------------------------------------------------------------------//
this.ElizaService.Collection = function( array ) { this._array = array ? array : Array(); }

ElizaService.Collection.prototype.append = function ( item ) { this._array.push( item ); }

ElizaService.Collection.prototype.dump = function () { console.log(this._array); }

ElizaService.Collection.prototype.first = function () { return this._array[0]; };

ElizaService.Collection.prototype.getBy = function (property, value) {
    var ar = new ElizaService.Collection();
    
    for (var i = 0; i < this._array.length; i++) {
        if (this._array[i][property] == value)
            ar.append(this._array[i]);
    }
    
    return ar;
};

ElizaService.Collection.prototype.limit = function (limit, offset = 0) { 
    return new ElizaService.Collection(this._array.slice(offset, offset+limit)); 
};

ElizaService.Collection.prototype.sortBy = function (property, order = true) {
    return new ElizaService.Collection(this._array.sort(function(a, b) {
        var textA = a[property].toUpperCase();
        var textB = b[property].toUpperCase();
        return (textA < textB) ? (order?-1:1) : (textA > textB) ? (order?1:-1) : 0;
    }));
};




    
    

//----------------------------------------------------------------------------//
//                               sub-class Feed:                              //
//                          implements feed handling                          //
//----------------------------------------------------------------------------//    
this.ElizaService.Feed = function( Service , properties ) {    
    this._Service = Service;
    for (var key in properties)
        this[key] = properties[key];
}

ElizaService.Feed.prototype.dump = function () {
    console.log(this);
}
    
    
    
    
    
    
    




})();//--------------------------end ElizaService.js--------------------------//
//----------------------------------------------------------------------------//