(function(){


    this.Eliza = function ( service ) { this.service = service; }
    this.Feed = function(){}
    
    Eliza.ajax = function (url, get, post, callback) {
        try {
            
            var GetVariables = Array();
            var PostVariables = post ? new FormData() : null;
            
            // encode data for query
            if (post) for (var property in post)
                    PostVariables.append(property, post[property]);
                    
            if (get) for (var property in get)
                    GetVariables.push( encodeURIComponent(property) + "=" + encodeURIComponent(get[property]));
           
            
            
            // create compatible request object
            if (window.ActiveXObject) var x = new ActiveXObject();
            else if (window.XMLHttpRequest) var x = new XMLHttpRequest();
            
            // send request
            x.open(post ? 'POST' : 'GET', url + '?' + GetVariables.join('&'), true);
            x.send(PostVariables);
            x.onreadystatechange = function () {
            if (x.readyState > 3) {
                try {
                    if (null != JSON.parse(x.responseText).oops)
                        Eliza.oops(JSON.parse(x.responseText).oops);
                    
                    else                
                        callback(JSON.parse(x.responseText));
                    
                } catch (e) {
                    console.log(e);
                    console.log(x.responseText);
                }
            }
        }
        } catch (e) {
            console.log(e);
        }
    };
    
    Eliza.prototype.query = function(feed, feed_id) {
        console.log('querying feed: ' + feed + ' at ' + this.service);
        
    }
    
    Eliza.Feed = function(json) {
        console.log('building feed: ' + json.feed);
    }
    
    
    
    
    
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

})();