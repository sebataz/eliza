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
    
    ElizaPlugin.notify = function ( notice ) {
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

})();//---------------------------end ElizaPlugin.js--------------------------//
//----------------------------------------------------------------------------//