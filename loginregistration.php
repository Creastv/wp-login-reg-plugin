<?php
/**
 * Plugin Name:       Personalize Login, registration, lost password
 * Description:       Custome login form, reagistration form, lost password
 * Version:           1.0.0
 * Author:            Piotr Stefaniak
 * License:           GPL-2.0+
 * Text Domain:       lr
 */

 
require_once plugin_dir_path( __FILE__ ) . '/admin/login.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/registration.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/forgotpassword.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/changepassword.php';

require_once plugin_dir_path( __FILE__ ) . '/user-panel/usercustomefilds.php';
require_once plugin_dir_path( __FILE__ ) . '/user-panel/accountinfo.php';

require_once plugin_dir_path( __FILE__ ) . '/listing/listing.php';

class Active {
    /**
     * Plugin activation hook.
     *
     * Creates all WordPress pages needed by the plugin.
     */
    public static function plugin_activated() {
        // Information needed for creating the plugin's pages
        $page_definitions = array(
            'member-account-login' => array(
                'title' => __( 'Sign In', 'lr' ),
                'content' => '[lr-login-form]'
            ),
            'member-account' => array(
                'title' => __( 'Your Account', 'lr' ),
                'content' => '[lr-account-info]'
            ),
            'member-account-lostpassword' => array(
                'title' => __( 'Lost password', 'lr' ),
                'content' => '[lr-forgot-pwd-form]'
            ),
            'member-account-changepassword' => array(
                'title' => __( 'Change password', 'lr' ),
                'content' => '[lr-change-pwd-form]'
            ),
            'member-account-registration' => array(
                'title' => __( 'Create Account', 'lr' ),
                'content' => '[lr-create-acount-form]'
            ),
        );
    
        foreach ( $page_definitions as $slug => $page ) {
            // Check that the page doesn't exist already
            $query = new WP_Query( 'pagename=' . $slug );
            if ( ! $query->have_posts() ) {
                // Add the page using the data from the array above
                wp_insert_post(
                    array(
                        'post_content'   => $page['content'],
                        'post_name'      => $slug,
                        'post_title'     => $page['title'],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                    )
                );
            }
        }
    }
}

// Initialize the plugin
$personalize_login_pages_plugin = new Active();

// Create the custom pages at plugin activation
register_activation_hook( __FILE__, array( 'Active', 'plugin_activated' ) );


/* Create Seller User Role */
add_role(
    'seller', //  System name of the role.
    __( 'Seller'  ), // Display name of the role.
    array(
        'read'  => true,
        'delete_posts'  => true,
        'delete_published_posts' => true,
        'edit_posts'   => true,
        'publish_posts' => true,
        'upload_files'  => true,
        'edit_pages'  => true,
        'edit_published_pages'  =>  true,
        'publish_pages'  => true,
        'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
    )
);
