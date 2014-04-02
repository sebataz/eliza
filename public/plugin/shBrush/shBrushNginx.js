SyntaxHighlighter.brushes.Custom = function() {
    var funcs       = 'worker_processes error_log worker_connections default_type sendfile keepalive_timeout listen server_name root index autoindex fastcgi_pass fastcgi_index fastcgi_param';
    var keywords    = 'events http server location error_page';
    var operators   = 'include';
 
    this.regexList = [
        { regex: /#(.*)$/gm,                                                css: 'comments' },
        { regex: new RegExp(this.getKeywords(funcs), 'gmi'),                css: 'color3' },
        { regex: new RegExp(this.getKeywords(operators), 'gmi'),            css: 'color1' },
        { regex: new RegExp(this.getKeywords(keywords), 'gmi'),             css: 'keyword' },
        { regex: /index\./gm,                                               css: 'color4' },
    ];
};

SyntaxHighlighter.brushes.Custom.prototype    = new SyntaxHighlighter.Highlighter();
SyntaxHighlighter.brushes.Custom.aliases  = ['nginx'];