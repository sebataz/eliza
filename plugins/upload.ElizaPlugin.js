(function () {
    ElizaPlugin.plugins.selection = function ( ) {
        this.DOMElement(function () {
            var check_to_delete = document.createElement('input');
            check_to_delete.type = 'checkbox';
            check_to_delete.style.position = 'absolute'; 
            check_to_delete.style.top = 0;
            check_to_delete.style.right = 0;
            this.style.position = 'relative';
            this.appendChild(check_to_delete);
        });
    };
    
    ElizaPlugin.plugins.upload = function ( location ) {
        var UploadService = new ElizaService('../api/eliza/service.php', 'FileContent', location);
        this.DOMElement(function () {
            var upload_with_eliza = this;
        
            var delete_files = document.createElement('div');
            var a_delete_files = document.createElement('a');

            delete_files.id = 'delete-files';
            delete_files.className = 'file';
            delete_files.appendChild(a_delete_files);

            a_delete_files.href = '#';
            a_delete_files.innerHTML = '<span class="filename">delete selected files</span>';
            a_delete_files.onclick = function () {
                for (var i = 0; i < upload_with_eliza.children.length; i++) {
                    var nodes = upload_with_eliza.children;
                    if (nodes[i].id != 'add-files'
                    && nodes[i].id != 'delete-files') {
                        if (nodes[i].getElementsByTagName('input')[0].checked) {
                            var file = nodes[i].id;
                            
                            // deletes files
                            var File = new ElizaService.Feed(UploadService);
                            File.Id = file;
                            
                            File.delete().response(function () {
                                UploadService.query().response(function (C, HTML) {
                                    upload_with_eliza.innerHTML = HTML;
                                    ElizaPlugin.byClassName('file').selection();
                                    upload_with_eliza.appendChild(add_files);
                                    upload_with_eliza.appendChild(delete_files);
                                    ElizaService.notify('files were deleted succesfully');
                                });
                            });
                            
                        }
                    }
                }
            };



            var add_files = document.createElement('div');
            var a_add_files = document.createElement('a');
            var input_add_files = document.createElement('input');

            add_files.id = 'add-files';
            add_files.className = 'file';
            add_files.appendChild(a_add_files);
            add_files.appendChild(input_add_files);

            a_add_files.href = '#';
            a_add_files.innerHTML = '<span class="filename">add files</span>';
            a_add_files.onclick = function () {input_add_files.click();};

            input_add_files.type = 'file';
            input_add_files.style.display = 'none';
            input_add_files.multiple = true;
            input_add_files.onchange = function () {
            
                
                // uploads files
                for (var i = 0; i < this.files.length; i++) {
                    var File = new ElizaService.Feed(
                        UploadService, 
                        {file:this.files[i]}
                    );
                    
                    File.save().response(function(){
                        UploadService.query().response(function (Collection, HTML) {
                            upload_with_eliza.innerHTML = HTML;
                            ElizaPlugin.byClassName('file').selection();
                            upload_with_eliza.appendChild(add_files);
                            upload_with_eliza.appendChild(delete_files);
                            ElizaService.notify('files were uploaded succesfully');
                        });
                    });
                }
                    
                
            };

            UploadService.query().response(function (Collection, HTML) {
                upload_with_eliza.innerHTML = HTML;
                ElizaPlugin.byClassName('file').selection();    
                upload_with_eliza.appendChild(add_files);
                upload_with_eliza.appendChild(delete_files);
            });        
        
        
        });
    };
})();