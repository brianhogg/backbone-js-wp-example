var bbp = bbp || {};

(function($){
    bbp.PostView = Backbone.View.extend({
        className: 'bbpost',

        initialize: function() {
            this.model.on("change", this.render, this);
        },

        render: function() {
            var template = _.template($('#tmpl-bbpost').html());
            var html = template(this.model.toJSON());
            this.$el.html(html);
            return this;
        },

        events: {
            'click button': 'updatePost'
        },

        updatePost: function() {
            this.model.set('title', this.$('.title').val());
            this.model.set('post_status', this.$('.post_status').val());
            this.model.save();
        }
    });
})(jQuery);