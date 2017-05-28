(function () {
    ElizaPlugin.plugins.ckeditor = function (id, feed, property) {
        var Service, autoSave, delay = 2700;
        Service = new ElizaService('../api/eliza/service.php', feed);
        
        this.DOMElement(function () {
            this.setAttribute( 'contenteditable', true );
            
            var editable = this;
            
            var CKeditor = CKEDITOR.inline( this, { 
                allowedContent: true, 
                extraPlugins: 'sourcedialog',
                removePlugins: 'about',
            });
            
            
            var saveFeed = function () {
                clearTimeout(autoSave);
                
                var Feed = new ElizaService.Feed(Service);
                Feed.Id = id;
                Feed[property] = CKeditor.getData();
                
                Feed.save().response(function () {
                    ElizaService.notify('changes were saved');
                });
            };
            
            CKeditor.on( 'blur' , function () { saveFeed(); });
            CKeditor.on( 'key' , function () {
                clearTimeout(autoSave);
                autoSave = setInterval(function () {
                    if (delay > 0) delay -= 100;
                    else {
                        delay = 2700;
                        saveFeed();
                    }
                }, 100);
            });
        });
    };
})();