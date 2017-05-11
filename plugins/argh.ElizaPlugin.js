(function () {
    ElizaPlugin.plugins.argh = function () { 
        this.DOMElement(function () {
            console.log(this);
        });
    };
})();