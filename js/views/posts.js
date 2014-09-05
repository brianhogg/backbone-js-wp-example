var bbpost = bbpost || {};

(function($){
    bbpost.PostsView = Backbone.View.extend({
        el: '#bbposts',

        initialize: function() {
            this.collection.bind('add', this.addOne, this);
        },

        addOne: function(post) {
            var view = new bbpost.PostView({ model: post });
            this.$el.append(view.render().el);
        }
    });

    $(document).ready(function() {
        bbpost.Posts = new bbpost.PostsCollection();
        bbpost.postsView = new bbpost.PostsView({ collection: bbpost.Posts });
        bbpost.Posts.fetch({ data: { action: 'bbpost_fetch_posts' } });
    });
})(jQuery);
