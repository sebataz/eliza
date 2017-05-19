(function(){//----------------------------------------------------------------//
//                               ElizaService.js                              //
//----------------------------------------------------------------------------//

//----------------------------------------------------------------------------//
//                            class ElizaService:                             //
//                          implements ajax request                           //
//----------------------------------------------------------------------------//
this.ElizaService = function ( service , feed , feed_arguments ) { 
    this.service = service; 
    this.feed = feed;
    this.feed_arguments = feed_arguments;
    
    this._request = null;
}

ElizaService.notify = function ( notice ) {
    console.log( notice );
    
    var body = document.querySelector('body');
    var wrapper = document.createElement('div');
    wrapper.id = 'notification-wrapper';
    wrapper.style.position = 'fixed';
    wrapper.style.bottom = '3em';
    wrapper.style.width = '100%';
    wrapper.style.zIndex = 1;
    
    var notification = document.createElement('div');
    notification.innerHTML  = notice;
    notification.id = 'notification';
    notification.style.width = '47%';
    notification.style.margin = '0 auto';
    notification.style.fontSize = '.7em';
    notification.style.wordWrap = 'break-word';
    notification.style.padding = '.3em';
    notification.style.backgroundColor = '#ffffe6';
    
    wrapper.appendChild(notification)
    body.appendChild(wrapper);
    
    window.setTimeout(function () {
        notification.parentNode.removeChild(notification);
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
    Get['args[]'] = this.feed_arguments;
    
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
            
                if (null != JSON.parse(_Service._request.responseText).oops) {
                    ElizaService.notify('<span style="font-weight: bold">Oops: </span>' + JSON.parse(_Service._request.responseText).oops);
                    console.log(JSON.parse(_Service._request.responseText));
                }
                
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

ElizaService.Collection.prototype.sortBy = function (property, order_asc = true) {
    return new ElizaService.Collection(this._array.sort(function(a, b) {
        if (order_asc)
            return (a[property] <= b[property]) ? -1 : 1;
        else
            return (a[property] <= b[property]) ? 1 : -1;
    }));
};

ElizaService.Collection.prototype.dump = function () { 
    var array = Array();
    
    console.log('-- Collection --')
    for ( item in this._array )
        if ( this._array[item] instanceof ElizaService.Feed )
            this._array[item].dump();
        else
            console.log( this._array[item] );
    console.log('-- end Collection --');
}




    
    

//----------------------------------------------------------------------------//
//                               sub-class Feed:                              //
//                          implements feed handling                          //
//----------------------------------------------------------------------------//    
this.ElizaService.Feed = function( Service , properties ) {  
    var _Service = Service;
    this.Service = function () { return _Service; }
    
    for (var property in properties)
        this[property] = properties[property];
}

ElizaService.Feed.prototype.dump = function () {
    console.log(this);
}

ElizaService.Feed.prototype.save = function () {
    var Get = Array();
    Get[this.Service().feed] = null;
    Get['args[]'] = this.Service().feed_arguments;
    Get['id'] = this.Id ? this.Id : null;
    
    var Post = Array();
    for (var property in this)
        if (this.hasOwnProperty(property) 
        && typeof this[property] != 'function')
            Post[property] = this[property];
            
    this.Service()._request = ElizaService.ajax(this.Service().service, Get, Post);
        
    return this.Service();
}

ElizaService.Feed.prototype.delete = function () {
    var Get = Array();
    Get[this.Service().feed] = null;
    Get['args'] = this.Service().feed_arguments;
    
    if (Get['id'] = this.Id)
        this.Service()._request = ElizaService.ajax(this.Service().service, Get, {});
    
    return this.Service();
}
    
    
    
    
    
    
    




})();//--------------------------end ElizaService.js--------------------------//
//----------------------------------------------------------------------------//