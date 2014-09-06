var bbp = bbp || {};
window.wp = window.wp || {};

(function($){
    bbp.Post = Backbone.Model.extend({
        save: function( attributes, options ) {
            options = options || {};
            options.data = _.extend( options.data || {}, {
                action: 'bbpost_save_post',
                data: this.toJSON()
            });
            var deferred = wp.ajax.send( options );
            deferred.done( function() {
                alert('done');
            });
            deferred.fail( function() {
                alert('failed');
            });
        }
    });
})(jQuery);