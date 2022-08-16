<?php
 add_shortcode('lr-account-info', 'lr_seller_account_callback');
  function lr_seller_account_callback() {
    ob_start();
    global $current_user;
    wp_get_current_user();

    $shop = array (
      'logo'  => get_the_author_meta( 'picture', $current_user->id ),
      'name' => get_the_author_meta( 'shop-name', $current_user->id ),
      'url'  => get_the_author_meta( 'shop-url', $current_user->id ),
      'desc'  => get_the_author_meta( 'shop-desc', $current_user->id ),
    );

    if ( is_user_logged_in() ) { 
      echo 'Username: ' . $current_user->user_login . '--' ;
      echo '<br>';
      echo 'User email: ' . $current_user->user_email . '--';
      echo '<br>';
      echo 'User first name: ' . $current_user->user_firstname . '--';
      echo '<br>';
      echo 'User last name: ' . $current_user->user_lastname . '--';
      echo '<br>';
      echo 'User display name: ' . $current_user->display_name . '--';
      echo '<br>';
      echo 'User ID: '. $current_user->id . '--';
      echo '<br>';
      echo  $shop['name'] ;
      echo '<br>';
      echo  $shop['url'] ;
      echo '<br>';
      echo  $shop['desc'] ;
      echo '<br>';
      echo  '<img src=' . $shop['logo']['url'] . ' />';
      echo '<br>';
   
     } else { 
        wp_loginout();
     }
     return ob_get_clean();

}
