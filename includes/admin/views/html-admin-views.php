<div class="row">
    <div class="col-lg-12">
        <h4><a href=" http://www.wpseeds.com/wp-subscription/" target="_blank"><span class="label label-success">Documentation</span></a></h4>
        <h4><a href="http://www.wpseeds.com/product/wp_subscription/" target="_blank"><span class="label label-success">Get Advance Feature</span></a></h4>
    </div>
</div>
<div class="panel panel-info">
 <div class="panel-heading"><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> Dashboard</div>
  <div class="panel-body">
     <div class="row">
        <div class="col-lg-12">
        
           <?php //listing status chart
		global $wpdb;
                $overview_html="";
                $status_chart="";
                $srcnt=1;
                $paidsrcnt=1;
		$listings_status = $wpdb->get_results("SELECT YEAR(created_date) as year,count(YEAR(created_date)) as count FROM {$wpdb->prefix}wpsp_subscribers group by YEAR(created_date)");       
		$status_chart="";                
                           
                ?>    
        <?php //paid listing status chart
		global $wpdb;
                $paid_list="";
                $paid_status_chart="";                
		$paid_listings_status = $wpdb->get_results("SELECT YEAR(created_date) as year ,is_active,count(YEAR(created_date)) as count FROM {$wpdb->prefix}wpsp_subscribers group by YEAR(created_date),is_active");       
                $paid_status_chart.="data: ["; 
                
                $newary=array();
                foreach ($paid_listings_status as $paid_listings_status){
                     if($paid_listings_status->is_active==1)
                     {                       
                        $newary[$paid_listings_status->year]['year']=$paid_listings_status->year;
                        $newary[$paid_listings_status->year]['Subcriber']=$paid_listings_status->count;
                        
                     }  else {                        
                        $newary[$paid_listings_status->year]['year']=$paid_listings_status->year;
                        $newary[$paid_listings_status->year]['Unsubcriber']=$paid_listings_status->count;
                     }
                 }
	       
                $chart_data="";
                $newary_html=array();
                $newary_html=$newary;
                foreach($newary as $newary){
                    $chart_data.="{";
                    $chart_data.="y: '".$newary['year']."',";
                    if(isset($newary['Subcriber'])){
                        $chart_data.="a: ".$newary['Subcriber'].",";
                    }else{
                         $chart_data.="a: 0,";
                    }
                    if(isset($newary['Unsubcriber'])){
                        $chart_data.="b: ".$newary['Unsubcriber'].",";
                    }else{
                         $chart_data.="b: 0,";
                    }                    
                    $chart_data.="},";
                }
                $paid_status_chart.=$chart_data;
                $paid_status_chart.="],";                
                
               
                
    ?>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="glyphicon glyphicon-user" data-placement="top" title="Show paid and unpaid listing graph(Pro Feature)" aria-hidden="true"></span> Subscriber Listing Chart                         </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">              
                            <div id="morris-bar-chart"></div>
                               <div>
                                Subscriber Chart : Count(y-axis) and Year(x-axis).
                               </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
               
           
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="glyphicon glyphicon-tag" data-placement="top" title="Show listing categories and count" aria-hidden="true"></span> Subscriber Categories
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="morris-donut-chart"></div>
                            <div>
                              Subscriber Listing Categories : This chart show listing categories and it's count.
                               </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                 <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="glyphicon glyphicon-plus-sign" data-placement="top" title="Show New added listing graph with respective year" aria-hidden="true"></span> New added Subscriber
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="myfirstchart"></div>
                            <div>
                                    New added Subscriber : This chart show new Subscriber count(y-axis) and year(x-axis).
                               </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="glyphicon glyphicon-list-alt" data-placement="top" title="Show Summary" aria-hidden="true"></span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <?php $listings_status = $wpdb->get_results("SELECT count(id) as count FROM {$wpdb->prefix}wpsp_subscribers");       
                            
		             foreach ($listings_status as $listings_status){ 
                                  $listing_count=$listings_status->count;                                 
                             }?>
                          <?php $listings_status = $wpdb->get_results("SELECT count(id) as count FROM {$wpdb->prefix}wpsp_subscribers where DATE(created_date)=CURDATE()");       
                            
		             foreach ($listings_status as $listings_status){ 
                                  $listing_count_day=$listings_status->count;
                             }?>
                            
                            <?php $listings_status = $wpdb->get_results("SELECT count(id) as count FROM {$wpdb->prefix}wpsp_subscribers where date_format(created_date, '%Y-%m') = date_format(now(), '%Y-%m');");       
                            
		             foreach ($listings_status as $listings_status){ 
                                 $listing_count_month=$listings_status->count;
                             }?>
                            
                            <?php $listings_status = $wpdb->get_results("SELECT count(id) as count FROM {$wpdb->prefix}wpsp_subscribers where date_format(created_date, '%Y') = date_format(now(), '%Y');");       
                            
		             foreach ($listings_status as $listings_status){ 
                                 $listing_count_year=$listings_status->count;
                             }?>   
                            <ul class="list-group">
                                    <li class="list-group-item">
                                      <span class="badge"><?php echo $listing_count_day;?></span>
                                    New Subscriber: Current Day
                                    </li>
                                    <li class="list-group-item">
                                      <span class="badge"><?php echo $listing_count_month;?></span>
                                     New Subscriber: Current Month
                                    </li>
                                    <li class="list-group-item">
                                      <span class="badge"><?php echo $listing_count_year;?></span>
                                     New Subscriber: Current Year
                                    </li>
                                    <li class="list-group-item">
                                      <span class="badge"><?php echo $listing_count;?></span>
                                      Total Subscriber
                                    </li>
                                  </ul>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
               
                
             
   </div>
   </div>

   <?php
		global $wpdb;
		$clistings = $wpdb->get_results("select c.category_name ,count(c.category_name)as lcat_count from wp_wpsp_user_category uc,wp_wpsp_category c,wp_wpsp_subscribers s where uc.category_id=c.id AND uc.subscriber_id=s.id");       
		$chart="";
			
               			
			$chart=' Morris.Donut({
                                    element: "morris-donut-chart",
                                    data: [';
                                            foreach ($clistings as $clisting){
                                       $chart.='{label: "'.$clisting->category_name.'",
                                        value: '.$clisting->lcat_count.'
                                    },';
                                            }
                                            $chart.='],
                                    resize: true
                                });';

		?>
   
 <?php //new added listing chart
		global $wpdb;
                $new_added_chart="";
		$listings_added_count = $wpdb->get_results("SELECT YEAR(created_date) as year,count(YEAR(created_date)) as count FROM {$wpdb->prefix}wpsp_subscribers group by YEAR(created_date)");       
		$new_added_chart="data: [";
                    foreach ($listings_added_count as $listings_added){
                   $new_added_chart.=" { year: '$listings_added->year', value: $listings_added->count },";
                    }
                   $new_added_chart.="],";
                ?>
 
<div style="clear:both"></div>
                <script>
                    $(function() {
new Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'myfirstchart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  <?php echo $new_added_chart ?>
  // The name of the data record attribute that contains x-values.
  xkey: 'year',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['New subcriber']
});
   
   <?php echo $chart ?>

    Morris.Bar({
        element: 'morris-bar-chart',
        <?php echo $paid_status_chart ?>
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Subcriber', 'Unsubcriber'],
        hideHover: 'auto',
        resize: true
    });

});


                </script>
  </div>
  <div class="panel-footer"><h4>Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/product/wp-all-backup/"> All WPSeeds Plugins.</a> Use Coupon code 'WPDB25'</h4></div>
</div>
  