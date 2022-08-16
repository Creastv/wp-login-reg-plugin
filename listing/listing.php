<?php
 add_shortcode('lr-listing-shops', 'lr_listing_shops');
  function lr_listing_shops() {
    ob_start();
    
    $blogusers = get_users( array( 'role__in' => array( 'seller' ) ) );
    // Array of WP_User objects.
    foreach ( $blogusers as $user ) {
        $shop = array (
        'logo'  => get_the_author_meta( 'picture', $user->id ),
        'name' => get_the_author_meta( 'shop-name', $user->id ),
        'url'  => get_the_author_meta( 'shop-url', $user->id ),
        'desc'  => get_the_author_meta( 'shop-desc', $user->id ),
    );
        echo '<span>' . esc_html( $user->display_name ) . '</span><br>';
        echo  $shop['name'] ;
        echo '<br>';
        echo  $shop['url'] ;
        echo '<br>';
        echo  $shop['desc'] ;
        echo '<br>';
        echo  '<img src=' . $shop['logo']['url'] . ' />';
        echo '<br>';
    }
     return ob_get_clean();

}
