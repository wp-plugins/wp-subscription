<?php
function wp_subscription_escape_csv_value($value) 
{
	$value = str_replace('"', '""', $value); // First off escape all " and make them ""
	$value = trim($value, ",");
	$value = preg_replace("/[\n\r]/"," ",$value);//replace newlines with space
	$value = preg_replace('/,/'," ",$value);//replace comma with space
	if(preg_match('/"/', $value)) { // Check if I have any " character
		return '"'.$value.'"'; // If I have new lines or commas escape them
	} else {
		return $value; // If no new lines or commas just return the value
	}
}
		global $wpdb;
		$subscribe_listings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_subscribers" ); 
                if(isset($_POST['csv_format'])){
                $separator = ", ";
                $csv_output = "";
                $csv_output.= "ID". $separator;
                $csv_output.= "First Name". $separator;
                $csv_output.= "Last Name". $separator;
                $csv_output.= "Email". $separator;
                $csv_output.= "Status". $separator;
                $csv_output.= "Date". $separator;	
                $csv_output.= "\n";
        
         foreach ($subscribe_listings as $subscribe_user){
             $csv_output.= wp_subscription_escape_csv_value($subscribe_user->id). $separator;
             $csv_output.= wp_subscription_escape_csv_value($subscribe_user->frist_name). $separator;
             $csv_output.= wp_subscription_escape_csv_value($subscribe_user->last_name). $separator;
             $csv_output.= wp_subscription_escape_csv_value($subscribe_user->email). $separator;
             $csv_output.= wp_subscription_escape_csv_value($subscribe_user->is_active). $separator;
             $csv_output.= wp_subscription_escape_csv_value($subscribe_user->created_date). $separator;
             $csv_output.= "\n";
         }
        
        $csv_file_abs_path = WPSP_PLUGIN_DIR.'subscriber_list.csv';//realpath(dirname(__FILE__)).'/affiliate_payout_report.csv';                
	$Handle = fopen($csv_file_abs_path,'w') ;//or die("can't open file named 'subscriber_list.csv'");	
	fwrite($Handle, $csv_output);	
	fclose($Handle);	
        $csv_file_url_path=WPSP_PLUGIN_URL.'subscriber_list.csv';
        echo '<div class="alert alert-success" role="alert"><a href="'.$csv_file_url_path.'"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download CSV Report</a></div>';
                }
?> 

<div class="panel panel-default">
     <div class="panel-heading">
         <div class="row">
  <div class="col-lg-6">
     <h3 class="panel-title"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Subscription Lists</h3>
  </div><!-- /.col-lg-6 -->
  <div class="col-lg-6">   
      <form action="" method="post">
       <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <input type="submit" class="btn btn-success" name="csv_format" value="CSV Report">
      </form>
      
   
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
   
  </div>
  <div class="panel-body">
    <div class="table-responsive">
                   <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid">
                              
                              
                <table class="table table-striped table-bordered table-hover display" id="example">
                    <thead> <tr>
                            <th>Sr.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Status</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=0;
                            foreach ($subscribe_listings as $subscribe_user){
                                $subscribe_category="General";
                                $subscribe_category_listings = $wpdb->get_results( "select c.category_name from {$wpdb->prefix}wpsp_user_category uc,{$wpdb->prefix}wpsp_category c where uc.category_id=c.id AND subscriber_id=$subscribe_user->id" );         
                                     foreach ($subscribe_category_listings as $subscribe_category_listings){
                                         $subscribe_category.=", $subscribe_category_listings->category_name";
                                     }
                               
                               
                                    
                                ?>
                        <tr>
                            
                            <td><?php echo ++$i?></td>
                            <td><?php _e($subscribe_user->frist_name,'wp-subscription');?> <?php _e($subscribe_user->last_name,'wp-subscription');?></td>
                            <td><?php _e($subscribe_user->email,'wp-subscription');?></td>
                            <td><?php _e($subscribe_category,'wp-subscription');?></td>
                            <td><?php _e($subscribe_user->is_active==1 ? '<span style="color:green" class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Subscribe':'<span style="color:red" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>Unsubscribe');?></td>
                        </tr>
                            <?php } ?>
                    </tbody>                    
                </table>
                   </div>
    </div>
  </div>
       
  <div class="panel-footer"><h4>Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/product/wp-all-backup/"> All WPSeeds Plugins.</a> Use Coupon code 'WPDB25'</h4></div>
</div>
