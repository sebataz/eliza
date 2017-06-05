(function(){//----------------------------------------------------------------//
//                               ElizaService.js                              //
//----------------------------------------------------------------------------//
var scripts = document.getElementsByTagName('script');
var __file__ = scripts[scripts.length-1].src;

//----------------------------------------------------------------------------//
//                            class ElizaService:                             //
//                          implements ajax request                           //
//----------------------------------------------------------------------------//
this.ElizaService = function ( service_url ) { 
    var _url = service_url;
    
    this.xhr = null;
    this.request = function (get, post) {
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
            if (window.ActiveXObject) this.xhr = new ActiveXObject();
            else if (window.XMLHttpRequest) this.xhr = new XMLHttpRequest();
            
            // send request
            // console.log('request: ' + _url + '?' + GetVariables.join('&'));
            this.xhr.open(post ? 'POST' : 'GET', _url + '?' + GetVariables.join('&'), true);
            this.xhr.send(PostVariables);
            
            return this;
        } catch (e) {
            console.log(e);
        }
    };
}

ElizaService.prototype.response = function ( callback ) {
    var xhr = this.xhr;
    this.xhr.onreadystatechange = function () {
        if (xhr.readyState > 3) {
            try {
                if (null != callback)
                    callback(xhr.responseText);
            
                
            } catch (e) {
                console.log(e);
                console.log(xhr.responseText);
            }
        }
    };
}


//----------------------------------------------------------------------------//
//                               sub-class Feed:                              //
//                          implements feed handling                          //
//----------------------------------------------------------------------------//    
this.ElizaService.Feed = function( feed, feed_arguments ) {
    // init eliza serivce at service.php
    var _Service = new ElizaService(__file__.replace('ElizaService.js', '../service.php'));
    this.Service = function () { return _Service; }
    
    // prepare base query
    var _baseQuery = [];
    this.baseQuery = function () { return _baseQuery; };
    _baseQuery[feed] = null;
    if (feed_arguments)
        _baseQuery['args'] = Array.isArray(feed_arguments) ? feed_arguments : [];
};

ElizaService.Feed.prototype.dump = function () {
    console.log(this);
};


    
    
ElizaService.Feed.prototype.query = function( params ) {
    this.Service().request(Object.assign([], this.baseQuery(), params));
    return this;
};

ElizaService.Feed.prototype.save = function () {
    var Post = Array();
    for (var property in this)
        if (this.hasOwnProperty(property) 
        && typeof this[property] != 'function')
            Post[property] = this[property];
        
    this.Service().request(
        Object.assign([], this.baseQuery(), {id:(this.Id ? this.Id : null)}), 
        Post
    );
    
    return this;
};

ElizaService.Feed.prototype.delete = function () {
    if (this.Id)
        this.Service().request(
            Object.assign([], this.baseQuery(), {id:this.Id}), 
            {}
        );
    
    return this;
};

ElizaService.Feed.prototype.response = function ( callback ) {
    var ThisFeed = this;
    var Service = this.Service();
    
    this.Service().response(function ( response ) {
        response = JSON.parse(response);
    
        if (null != response.oops) {
            ElizaPlugin.notify('<strong>Oops: </strong>' + response.oops);
            console.log(response);
        
        } else {
            var Collection = new ElizaService.Collection();
            
            for (var feed in response.feed)
                Collection.append(Object.assign(new ElizaService.Feed(), ThisFeed, response.feed[feed])); // mmhhh :-/
                
            callback(Collection, response.html);
        }
    });
};
    
    
    
    
    


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




    
        
    
    




})();//--------------------------end ElizaService.js--------------------------//
//----------------------------------------------------------------------------//