<?php
/*
Plugin Name: Backbone.js WP Example
Plugin URI:
Description: Basic Backbone.js Example in WordPress to edit basic Post properties
Version: 1.0
Author: Brian Hogg
Author URI: http://brianhogg.com
License: GPL2
*/

define( 'BBPOST_VERSION', 1 );

class BBPostAdmin {
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'wp_ajax_bbpost_fetch_posts', array( &$this, 'ajax_fetch_posts' ) );
            add_action( 'wp_ajax_bbpost_save_post', array( &$this, 'ajax_save_post' ) );

            if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
                add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
                if ( isset( $_GET['page'] ) and 'bbpostadmin' == $_GET['page'] ) {
                    add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
                }
            }
        }
    }

    function enqueue_scripts() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'backbone' );
        wp_enqueue_script( 'underscore' );
        wp_enqueue_script( 'wp-util' );
        // Add backbone.js models first, then collections, followed by views
        $folders = array( 'models', 'collections', 'views' );
        foreach ( $folders as $folder ) {
            foreach ( glob( dirname( __FILE__ ) . "/js/$folder/*.js" ) as $filename ) {
                $basename = basename( $filename );
                wp_register_script( "$folder/$basename", plugins_url( "js/$folder/$basename", __FILE__ ), array( 'jquery', 'backbone', 'underscore', 'wp-util' ), BBPOST_VERSION );
                wp_enqueue_script( "$folder/$basename" );
            }
        }

        wp_register_style( 'bbpost.admin.css', plugins_url( 'css/admin.css', __FILE__ ), false, BBPOST_VERSION );
        wp_enqueue_style( 'bbpost.admin.css' );
    }

    function admin_menu() {
        add_menu_page( 'Backbone JS Post Admin Example', 'Backbone JS Post Admin Example', 'add_users', 'bbpostadmin', array( &$this, 'admin_page' ) );
    }

    function admin_page() {
        ?>
        <script id="tmpl-bbpost" type="text/html">
            <div class="bbpost">
                <h2><%- title %></h2>
                Post title: <input type="text" class="title" value="<%- title %>" />, Status:
                    <select class="post_status">
                        <option value=""></option>
                        <option value="publish" <% if ( 'publish' == post_status ) { %>SELECTED<% } %>>Published</option>
                        <option value="draft" <% if ( 'draft' == post_status ) { %>SELECTED<% } %>>Draft</option>
                    </select>
                <button>Update</button>
            </div>
        </script>

        <h1>Backbone.js WordPress Post Admin Example</h1>

        <div id="bbposts">
        </div>
        <?php
    }

    function ajax_fetch_posts() {
        if ( ! current_user_can( 'edit_published_posts' ) )
            wp_send_json_error();

        $posts = get_posts( array(
            'post_status' => 'any'
        ) );
        $retval = array();
        foreach ( $posts as $post ) {
            $retval[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'post_status' => $post->post_status,
            );
        }

        wp_send_json_success( $retval );
    }

    function ajax_save_post() {
        if ( ! $post = get_post( (int) $_POST['data']['id'] ) )
            wp_send_json_error();

        if ( ! current_user_can( 'edit_post', $post->ID ) )
            wp_send_json_error();

        if ( wp_update_post( array(
            'ID' => $post->ID,
            'post_title' => $_POST['data']['title'],
            'post_status' => $_POST['data']['post_status'],
        ) ) == $post->ID )
            wp_send_json_success();
        else
            wp_send_json_error();
    }
}

$GLOBALS['bbpostadmin'] = new BBPostAdmin();