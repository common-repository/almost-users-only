<?php

/*
  Plugin Name: Almost Users Only
  Plugin URI: http://www.genvejen.dk/almost-users-only/
  Description: Checks if user is logged in or not. If logged in, the user can view all the pages/posts on the blog. If not logged in, the user can only view a specific page (where he for example might read about the site and how he can get an account).
  Author: Mads Phikamphon
  Version: 1.0
  Author URI: http://www.genvejen.dk/
  License: GPLv2
 */

add_action('wp_head', 'mp_auo_check_and_redirect_user');

// Check if the user is logged in. Redirect if he is not logged in.
function mp_auo_check_and_redirect_user() {
    $currentUrl = get_permalink();    
    $infoUrl = get_option('mp_auo_options');
    
    if(strcmp($currentUrl, $infoUrl) != 0)
    {
        if(is_user_logged_in() == false) {
            //header( "Location: $infoUrl" );
            wp_redirect($infoUrl);
        }
    }
}

add_action('admin_menu', 'mp_auo_add_page');

// Add an options page for administration
function mp_auo_add_page() {
    add_options_page('Almost Users Only', 'Almost Users Only', 'manage_options', 'mp_auo_users_only', 'mp_auo_options_page');
}

// Draw the options page
function mp_auo_options_page() {
    $infoUrl = get_option('mp_auo_options');
    ?>
        <div id="mp_uo_general" class="wrap">
            <h2>Almost Users Only</h2>
            <form method="post" action="admin-post.php" >
                <input type="hidden" name="action" value="save_mp_auo_options" />
                <?php wp_nonce_field('mp_auo'); // nonce_field to make sure input comes from the WP administration pages ?>
                <p>The Info Url must be the complete url, for example: http://www.example.com/info-url/</p>
                Info Url: <input type ="text" name="mp_auo_infourl" value="<?php echo esc_html($infoUrl); ?>" />
                <br/>
                <input type="submit" value="Submit" class="button-primary" />
            </form>
        </div>
    <?php
}

add_action('admin_init', 'mp_auo_admin_init');

function mp_auo_admin_init() {
    add_action('admin_post_save_mp_auo_options', 'process_mp_auo_options');
}

function process_mp_auo_options() {
    // Check user has proper security level
    if(!current_user_can('manage_options'))
        wp_die('Not allowed');
    
    // Check that the nonce field was created in the configuration form
    check_admin_referer('mp_auo');
    
    $infoUrl = get_option('mp_auo_options');
    
    if(isset($_POST["mp_auo_infourl"])) {
        $infoUrl = esc_url($_POST["mp_auo_infourl"]);
    }
    
    update_option('mp_auo_options', $infoUrl);
    
    wp_redirect(add_query_arg('page', 'mp_auo_users_only', admin_url('options-general.php')));
    
    exit;
}

?>