
var Eliza = {
    feed: function (ops) {
        if(typeof ops == 'string') ops = { feed: ops };
        ops.feed = ops.url || '';
        ops.method = ops.method || 'get';
        ops.data = ops.data || {};
        
        var api = {
            request: function (ops) {
            
                return this;
            },
            done: function (callback) {
                this.callback = callback;
                return this;
            }
        }
    }

}