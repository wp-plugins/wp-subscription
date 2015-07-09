<?php
// Creating the widget 
class wpspwidgetsearch extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpspwidgetsearch', 

// Widget name will appear in UI
__('WP Subscription', 'wp-subscription-plugin'), 

// Widget description
array( 'description' => __( 'Newslatter', 'wp-subscription-plugin' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$category =$instance['category'];
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
global $wpdb;

// This is where you run the code and display the output
$categoryID=$category;
$wps_shorcode=0;
$wps_widget=1;
	include('front_end.php');
$html_content=$return;
       
echo $html_content;
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Subscribe ', 'wp-subscription-plugin' );
}
if ( isset( $instance[ 'category' ] ) ) {
$category = $instance[ 'category' ];
}
else {
$category ="";
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category ID:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" type="text" value="<?php echo esc_attr( $category ); ?>" />
Category IDs, separated by commas.
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
return $instance;
}
} // Class wpspwidget ends here






// Register and load the widget
function wpspwidget_search() {
	register_widget( 'wpspwidgetsearch' );       
}

add_action( 'widgets_init', 'wpspwidget_search' );

