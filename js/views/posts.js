var bbp = bbp || {};

(function($){
    bbp.PostsView = Backbone.View.extend({
        el: '#bbposts',

        initialize: function() {
            this.collection.bind('add', this.addOne, this);
        },

        addOne: function(post) {
            var view = new bbp.PostView({ model: post });
            this.$el.append(view.render().el);
        }
    });

    $(document).ready(function() {
        bbp.posts = new bbp.PostsCollection();
        bbp.postsView = new bbp.PostsView({ collection: bbp.posts });
        bbp.posts.fetch({ data: { action: 'bbpost_fetch_posts' } });
    });
})(jQuery);
