(function(){//----------------------------------------------------------------//
//                                ElizaPlugin.js                              //
//----------------------------------------------------------------------------//
//----------------------------------------------------------------------------//
//                             class ElizaPlugin:                             //
//                         implements plugin manager                          //
//----------------------------------------------------------------------------//

    this.ElizaPlugin = function ( DOMElements ) { 
        var _DOMElements = DOMElements;
        this.DOMElement = function ( callback ) { 
            _DOMElements.forEach(function (currentValue, index, array) {
                if (currentValue)
                    callback.call(currentValue);
            });
        };   
    };

    ElizaPlugin.byId = function ( id ) {
        return new ElizaPlugin([
            document.getElementById( id )]);
    };

    ElizaPlugin.byClassName = function ( class_name ) {
        return new ElizaPlugin([].slice.call(
            document.getElementsByClassName( class_name )));
    };

    // // Will I ever use it???
    // ElizaPlugin.byTagName = function ( tag_name ) {
        // return new ElizaPlugin([].slice.call(
            // document.getElementsByTagName( tag_name )));
    // };

    ElizaPlugin.plugins = ElizaPlugin.prototype = {
        plugins: function () { 
            console.log( ElizaPlugin.prototype ); },

    };

})();//---------------------------end ElizaPlugin.js--------------------------//
//----------------------------------------------------------------------------//