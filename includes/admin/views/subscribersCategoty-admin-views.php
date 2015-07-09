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
        if(isset($_GET['page']) && isset($_GET['action']) && isset($_GET['id']) && $_GET['action']=='csvreport'){

		global $wpdb;
                $category_id=$_GET['id'];
		$subscribe_listings = $wpdb->get_results( "select Distinct s.id,s.is_active,s.created_date,s.frist_name,s.last_name,s.email from {$wpdb->prefix}wpsp_user_category uc,{$wpdb->prefix}wpsp_category c,{$wpdb->prefix}wpsp_subscribers s where uc.category_id=c.id AND uc.subscriber_id=s.id AND c.id=$category_id" );                
              
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
        if(isset($_GET['category_name'])){
            $category_name=$_GET['category_name'];
        }else{
            $category_name=$category_id;
        }
        $csv_file_abs_path = WPSP_PLUGIN_DIR.$category_name.'_category_list.csv';//realpath(dirname(__FILE__)).'/affiliate_payout_report.csv';                
	$Handle = fopen($csv_file_abs_path,'w') ;//or die("can't open file named 'subscriber_list.csv'");	
	fwrite($Handle, $csv_output);	
	fclose($Handle);	
        $csv_file_url_path=WPSP_PLUGIN_URL.$category_name.'_category_list.csv';
        echo '<div class="alert alert-success" role="alert"><a href="'.$csv_file_url_path.'"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download CSV Report for category '.$category_name.'</a></div>';
                
        }
	$errorFlag=0;
	$errormessage ="";
	$sucssesMessage="";
	$category_name="";
	$category_id="";
	$category_edit=0;
	if(isset($_POST['create']))
     {

	if(isset($_POST['category']) && !(empty($_POST['category']))){
		$category_name=ucfirst(sanitize_text_field($_POST['category']));
	}else
	{
		$errormessage.="<span class='label label-danger'>Please enter category name</span><br>";
		$errorFlag=1;
	}

	
     
    $user_ID = get_current_user_id();
	if(!($errorFlag==1)){
		global $wpdb;
		
		$values=array('category_name'=>$category_name);		
		if($wpdb->insert($wpdb->prefix.'wpsp_category',$values)){
			$sucssesMessage.="Subscription List  added successfully";
     		$errorFlag=2;
			unset($_POST);
		}	
		
	}

}

if(isset($_POST['update']) && isset($_POST['editcategory']) && !(empty($_POST['editcategory'])))
     {

	if(isset($_POST['category_id']) && !(empty($_POST['category_id']))){
		$categoryid=sanitize_text_field($_POST['category_id']);
		$editcategory=ucfirst(sanitize_text_field($_POST['editcategory']));
		global $wpdb;
		
		$values=array(
		'id'=>$categoryid,
		'category_name'=>$editcategory	 
		);
		$wpdb->update($wpdb->prefix.'wpsp_category',$values,array('id'=>$categoryid));
		
	}
}

if(isset($_GET['action']) && $_GET['action']=="removecategory")
     {

	if(isset($_GET['id']) && !(empty($_GET['id']))){
		$id=sanitize_text_field($_GET['id']);
		global $wpdb;
		
		$values=array(
		'category'=>1
		);
		//$wpdb->update($wpdb->prefix.'wpbdp_listing',$values,array('category'=>$id));
		
		$wpdb->delete($wpdb->prefix.'wpsp_category',array('id'=>$id));
		
		//echo 'deleted';

	}
}


if(isset($_GET['action']) && $_GET['action']=="editcategory")
     {

	if(isset($_GET['id']) && !(empty($_GET['id']))){
		$id=sanitize_text_field($_GET['id']);
		global $wpdb;
		//echo $id;
		
		$sql="select id,category_name FROM {$wpdb->prefix}wpsp_category WHERE  id=".$id;
		$currentCid = $wpdb->get_row( $sql );
	
        $category_name=$currentCid->category_name;
        $category_id=$currentCid->id;
		$category_edit=1;
		
		

	}
}

if(isset($_POST['Cancle']) && $_POST['Cancle']=="Cancle"){
	$category_edit=0;	
}	
?>


<div class="panel panel-primary wpsp_admin_panel" >
	<div class="panel-heading">
            		    <h3 class="panel-title"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <?php _e('Subscription Lists','wp-subscription');?></h3>
    </div>
    <div class="panel-body">
	 <?php if(($errorFlag==1)){ ?>
     <?php _e($errormessage,'wp-subscription'); ?>
    <?php }else if($errorFlag==2){ ?>
     <span class="label label-success"><?php _e($sucssesMessage,'wp-subscription'); ?></span>
    <?php }	?>
	
        <div id="createCategoryContainer">
	<form id="categoryForm" method="post">
		   
                       <div class="input-group">
                            <input id="newCatName" name="category" class="form-control" type="text" placeholder="<?php _e('Enter Subscription List Name','wp-subscription');?>" ><br>
	 
                <span class="input-group-btn">
                 <input type="submit" class="btn btn-success" name="create" value="<?php _e('Add New Subscription List','wp-subscription');?>"/>
      </span>
                            
    </div>
</form><hr>
        </div>
<?php if($category_edit==1 && !(empty($category_name)) && !(empty($category_id))) {?>

 <div id="createCategoryContainer">
<form id="editCategoryForm" method="post">
		  
           <input name="editcategory" value="<?php echo $category_name; ?>" class="form-control" type="text" >
		   <input name="category_id" value="<?php echo $category_id; ?>"type="hidden"><br>
	       <input type="submit" class="btn btn-success" name="update" value="<?php _e('Update Subscription List ','wp-subscription');?>"/>
		   <?php echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=wpsp-subscriberscategoty"
		    ><span class="btn btn-primary">';
			_e('Cancle','wp-subscription');
			echo '</span></a>';?>

</form><hr>
</div>
<?php } ?>


<div class="tab-content">
	<?php
	global $wpdb;
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_category" );
?>
<div id="catDisplayTableContainer" class="table-responsive">
	<table class="table table-striped">
		<tr>
                         <th><?php _e('Category ID','wp-subscription');?></th>
			<th><?php _e('Subscription List  Name','wp-subscription');?></th>
			<th><?php _e('Action','wp-subscription');?></th>
		</tr>
		<?php foreach ($categories as $category){?>
			<tr>
				<td><?php _e($category->id,'wp-subscription');?></td>
                                <td><?php _e($category->category_name,'wp-subscription');?></td>
				<td>
					<?php if($category->id!=1){?>					
					<?php echo '<div class="row"><div class="col-lg-3"><a href="'.get_bloginfo("url").'/wp-admin/admin.php?page=wpsp-subscriberscategoty&action=editcategory&id='.$category->id.'" title="Delete"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>';
					echo '</div><div class="col-lg-3"><a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=wpsp-subscriberscategoty&action=removecategory&id='.$category->id.'" title="Delete"><span style="color:red" class="glyphicon glyphicon-remove"></span><a/>';
                                            echo '</div><div class="col-lg-6"><a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=wpsp-subscriberscategoty&action=csvreport&id='.$category->id.'&category_name='.$category->category_name.'" title="CSV Report"><span class="glyphicon glyphicon-list-alt"></span> CSV Report<a/></div>';
					}?>
				</td>
			</tr>
		<?php }?>
	</table>
</div>


</div>

	</div>
            
</div>

