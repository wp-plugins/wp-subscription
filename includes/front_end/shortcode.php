<?php
add_shortcode( 'wp_subscription', 'wp_subscription' );


function wp_subscription($atts) {
    $categoryID=$atts['category'];    
    $wps_shorcode=1;
    $wps_widget=0;
	include('front_end.php');	
    return $return;
    
}
