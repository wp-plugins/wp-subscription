<?php
 include_once('front_end_function.php');	
$return='<style type="text/css">
.form-style-1 {
    margin:10px auto;
    max-width: 400px;
    padding: 20px 12px 10px 20px;
    font: 13px "Lucida Sans Unicode", "Lucida Grande", sans-serif;
}
.form-style-1 li {
    padding: 0;
    display: block;
    list-style: none;
    margin: 10px 0 0 0;
}
.form-style-1 label{
    margin:0 0 3px 0;
    padding:0px;
    display:block;
    font-weight: bold;
}
.form-style-1 input[type=text], 
.form-style-1 input[type=date],
.form-style-1 input[type=datetime],
.form-style-1 input[type=number],
.form-style-1 input[type=search],
.form-style-1 input[type=time],
.form-style-1 input[type=url],
.form-style-1 input[type=email],
textarea, 
select{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    border:1px solid #BEBEBE;
    padding: 7px;
    margin:0px;
    -webkit-transition: all 0.30s ease-in-out;
    -moz-transition: all 0.30s ease-in-out;
    -ms-transition: all 0.30s ease-in-out;
    -o-transition: all 0.30s ease-in-out;
    outline: none;  
}
.form-style-1 input[type=text]:focus, 
.form-style-1 input[type=date]:focus,
.form-style-1 input[type=datetime]:focus,
.form-style-1 input[type=number]:focus,
.form-style-1 input[type=search]:focus,
.form-style-1 input[type=time]:focus,
.form-style-1 input[type=url]:focus,
.form-style-1 input[type=email]:focus,
.form-style-1 textarea:focus, 
.form-style-1 select:focus{
    -moz-box-shadow: 0 0 8px #88D5E9;
    -webkit-box-shadow: 0 0 8px #88D5E9;
    box-shadow: 0 0 8px #88D5E9;
    border: 1px solid #88D5E9;
}
.form-style-1 .field-divided{
    width: 49%;
}

.form-style-1 .field-long{
    width: 100%;
}
.form-style-1 .field-select{
    width: 100%;
}
.form-style-1 .field-textarea{
    height: 100px;
}
.form-style-1 input[type=submit], .form-style-1 input[type=button]{   
    padding: 8px 15px 8px 15px;
    border: none;
    color: #fff;
}
.form-style-1 input[type=submit]:hover, .form-style-1 input[type=button]:hover{
    background: #4691A4;
    box-shadow:none;
    -moz-box-shadow:none;
    -webkit-box-shadow:none;
}
.form-style-1 .required{
    color:red;
}
</style>';
        if(isset($_POST['wp_subscription'])){
                   if(isset($_POST['first_name'])){
                            $first_name=sanitize_text_field($_POST['first_name']);
                    }else
                    {
                            $first_name="";
                    }   
                    if(isset($_POST['last_name'])){
                            $last_name=sanitize_text_field($_POST['last_name']);
                    }else
                    {
                            $last_name="";
                    }  
                     if(isset($_POST['email'])){
                            $email=sanitize_text_field($_POST['email']);
                            global $wpdb;
                            $values=array('frist_name'=>$first_name,'last_name'=>$last_name,'email'=>$email);                           
		             $subscribe_listings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_subscribers where email like '$email'"); 
                             if(count($subscribe_listings)==0){
                             if($wpdb->insert($wpdb->prefix.'wpsp_subscribers',$values)){
                                 $last = $wpdb->get_row("SHOW TABLE STATUS LIKE '{$wpdb->prefix}wpsp_subscribers'");
                                 $lastid = $last->Auto_increment-1;                               
                                  if(isset($_POST['category'])){
                                      $category=sanitize_text_field($_POST['category']);
                                      $categories=explode(',',$category);
                                      foreach($categories as $categories){     
                                          if($categories!=1){
                                          $categoriesValues=array('subscriber_id'=>$lastid,'category_id'=>$categories);
                                          $wpdb->insert($wpdb->prefix.'wpsp_user_category',$categoriesValues);
                                        }
                                     }
                                        $user['email'] = $email;
					$user['first_name'] = $first_name;
					$user['last_name'] = $last_name;
					$fname=$first_name;
					$lname=$last_name;
					$email_to_subscribe=$email;
                                    $full_target_list_name=get_option('aweber_webform_oauth_removed');
				//AutoResponder Integration	
			
				$mailchimp_apikey = get_site_option('mailchimp_apikey');
                                            if ( ! empty( $mailchimp_apikey ) ) {
                                                $api = mailchimp_load_API();
                                                $api->ping();

                                                        if ( empty( $api->errorMessage ) ) {
                                                                error_log("mailchimp enabled");
                                                                mailchimp_add_user( $user );
                                                        }
                                                        else
                                                        {
                                                                error_log("mailchimp desabled");
                                                        }
                                                         }
                                                         //CMDR_Dual_Synchronizer::cmdr_user_update($user_id, null, $email_to_subscribe, true );
                                                        if(!empty($full_target_list_name))
                                                        {
                                                                    error_log("inside aweber");
                                                                        wp_subscribe_aweber_new_signup_user($full_target_list_name,$fname,$lname,$email_to_subscribe);
                                                                        error_log("aweber registration");
                                                        }
                                                        else
                                                        {
                                                                error_log("aweber in not enabled");
                                                        }

                                  }
                                
                                $selected_option=get_option('wps_sussesfull_message');
                                if(!empty($selected_option)){
                                        $return.='  <ul class="form-style-1"><li>'.get_option('wps_sussesfull_message').'</li><ul>';          
                       
                                }
                                    }
                             }
                    }else
                    {
                            $email="";
                    }
        }
        $return.='<div class="input-group">
            <form action="" method="post">            
                <ul class="form-style-1">';
                 $selected_option=get_option('wps_form_title');
                if($wps_shorcode==1){
                    if(!empty($selected_option)){
                       $wps_form_title=get_option('wps_form_title');
                   }else{
                       $wps_form_title="SUBSCRIBE OUR NEWSLETTER";
                   }
                    $return.='<li><h3>'.$wps_form_title.'</h3></li>';
                }
                $selected_option=get_option('wps_comment_textmessage');
                if(!empty($selected_option)){
                      $return.='<li>'.get_option('wps_comment_textmessage').'</li>';
                  }
                   $return.='<li>';
                 $selected_option=get_option('wps_first_name');
                 if($selected_option==1){
                    $return.='<li><input type="text" class="field-divided form-control" name="first_name" placeholder="First Name">';
                }
                $selected_option=get_option('wps_last_name');
                 if($selected_option==1){            
                     $return.=' <input type="text" class="field-divided form-control" name="last_name" placeholder="Last Name">
                    ';
               }
                $return.='</li>';
                   $return.='<li>
                           <input type="email" class="field-long form-control" name="email" required placeholder="Email Address">
                    </li>';
                   $return.='<input type="hidden" name="category" value="'.$categoryID.'">';
                    $selected_option=get_option('wps_submit_title');
                  if(!empty($selected_option)){
                       $wps_submit_title=get_option('wps_submit_title');
                   }else{
                       $wps_submit_title="Subscribe";
                  }
                    $return.='<li>
                        
                       <input type="submit" name="wp_subscription" name="Subscribe" value="'.$wps_submit_title.'">
                    </li>
            </ul>
         </form>
        </div>';
        ?>
	