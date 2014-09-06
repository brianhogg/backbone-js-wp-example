var bbp = bbp || {};

(function($){
    bbp.PostsCollection = Backbone.Collection.extend({
        model: bbp.Post,
        url: ajaxurl,

        parse: function ( response ) {
            /*
             Parse response from wp_send_json_success(), which will be something like:
             {
                success: true,
                data: [
                    {
                        id: 1,
                        title: 'Hello World',
                        post_status: 'publish'
                    },
                    ...
                ]
             }
             */

            // This will be undefined if success: false
            return response.data;
        }

//        sync: function( method, model, options ) {
//            if ( 'read' == method ) {
//                var deferred = wp.ajax.send( options );
//                return deferred;
//            } else {
//                return Backbone.Model.prototype.sync.apply( this, arguments );
//            }
//        }
    });
})(jQuery);