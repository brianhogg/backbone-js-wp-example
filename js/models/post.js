var bbpost = bbpost || {};

(function($){
    bbpost.Post = Backbone.Model.extend({
        save: function( attributes, options ) {
            var options = {};
            options.data = {
                action: 'bbpost_save_post',
                data: this.toJSON()
            }
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