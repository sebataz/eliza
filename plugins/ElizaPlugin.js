function appendScript(pathToScript) {
    var head = document.getElementsByTagName("head")[0];
    var js = document.createElement("script");
    js.type = "text/javascript";
    js.src = pathToScript;
    head.appendChild(js);
}

(function(){//----------------------------------------------------------------//
//                                ElizaPlugin.js                              //
//----------------------------------------------------------------------------//
    var scripts = document.getElementsByTagName('script');
    var __file__ = scripts[scripts.length-1].src;
//----------------------------------------------------------------------------//
//                             class ElizaPlugin:                             //
//                          implements ajax request                           //
//----------------------------------------------------------------------------//
this.ElizaPlugin = function ( DOMElements ) { 
    var _DOMElements = DOMElements;
    this.DOMElement = function ( callback ) { 
        _DOMElements.forEach(function (currentValue, index, array) {
            // console.log(currentValue) // DOM element you wish to apply the plugin to
            if (currentValue)
                callback.call(currentValue);
        });
    };   
};

ElizaPlugin.byId = function ( id ) {
    return new ElizaPlugin([document.getElementById( id )]);
};

ElizaPlugin.byClassName = function ( class_name ) {
    return new ElizaPlugin([].slice.call(document.getElementsByClassName( class_name )));
};

ElizaPlugin.byTagName = function ( tag_name ) {
    return new ElizaPlugin([].slice.call(document.getElementsByTagName( tag_name )));
};

ElizaPlugin.plugins = ElizaPlugin.prototype = {
    hello: function () { console.log('Hello, I am #' + this.DOMElement().id); },

};

ElizaPlugin.load = function( plugin ) {

    var js = document.createElement('script');
    js.type = 'text/javascript';
    js.src = __file__.replace('ElizaPlugin.js', plugin + '.ElizaPlugin.js');// ahahahahahahaha AH AH AHHHHHHHHHHH crepa lozza!

    document.getElementsByTagName('head')[0].appendChild(js);
};



})();//---------------------------end ElizaPlugin.js--------------------------//
//----------------------------------------------------------------------------//