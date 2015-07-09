<?php if(isset($_POST['submit'])){
    if(isset($_POST['wps_first_name'])){
                            update_option('wps_first_name',1);
                    }else{
                        update_option('wps_first_name',0);
                    }
    if(isset($_POST['wps_last_name'])){
                            update_option('wps_last_name',1);
                    }
                   else{
                        update_option('wps_last_name',0);
                    }
    if(isset($_POST['wps_form_title'])){
                            update_option('wps_form_title',sanitize_text_field($_POST['wps_form_title']));
                    }
    if(isset($_POST['wps_submit_title'])){
                            update_option('wps_submit_title',sanitize_text_field($_POST['wps_submit_title']));
                    
}
    if(isset($_POST['wps_sussesfull_message'])){
                            update_option('wps_sussesfull_message',sanitize_text_field($_POST['wps_sussesfull_message']));
                    }
    if(isset($_POST['wps_comment_textmessage'])){
                            update_option('wps_comment_textmessage',sanitize_text_field($_POST['wps_comment_textmessage']));
                    }
}
?>
<div class="panel panel-default">
     <div class="panel-heading">
         <div class="row">
  <div class="col-lg-12">
     <h3 class="panel-title"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Settings</h3>
  </div><!-- /.col-lg-6 -->
  
</div><!-- /.row -->
   
  </div>
  <div class="panel-body">
      
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Form Setting
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
           <form action="" method="post">
               <div class="row">                  
                        <div class="col-lg-6">
                          <div class="input-group">
                            <span class="input-group-addon">
                              <input type="checkbox" <?php echo (get_option('wps_first_name')==1)? 'checked':'';?> name="wps_first_name" aria-label="First Name">
                            </span>
                            <input type="text" placeholder="First Name" class="form-control" aria-label="First Name">
                          </div><!-- /input-group -->
                          <div class="alert alert-defualt" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Click on checkbox for enable first name on form</div>
                        </div><!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                         <div class="input-group">
                            <span class="input-group-addon">
                              <input type="checkbox" <?php echo (get_option('wps_last_name')==1)? 'checked':'';?> name="wps_last_name" aria-label="Last Name">
                            </span>
                            <input type="text" placeholder="Last Name" class="form-control" aria-label="Last Name">
                          </div><!-- /input-group -->
                           <div class="alert alert-defualt" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Click on checkbox for enable last name on form</div>
                        </div><!-- /.col-lg-6 -->
                        
                        <div class="col-lg-6">
                         <div class="input-group">
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </span>
                            <input type="text" name="wps_form_title" value='<?php echo get_option('wps_form_title');?>' placeholder="Subscriber form name" class="form-control" aria-label="title">
                          </div><!-- /input-group -->
                           <div class="alert alert-defualt" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Enter subscription form title</div>
                        </div><!-- /.col-lg-6 -->
                        
                        <div class="col-lg-6">
                         <div class="input-group">
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </span>
                            <input type="text" name="wps_submit_title" value='<?php echo get_option('wps_submit_title');?>' placeholder="Submit Button Text" class="form-control" aria-label="title">
                          </div><!-- /input-group -->
                           <div class="alert alert-defualt" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Enter subscription submit button title</div>
                        </div><!-- /.col-lg-6 -->
                        
                        <div class="col-lg-6">
                         <div class="input-group">
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
                            </span>
                            <input type="text" name="wps_sussesfull_message" value='<?php echo get_option('wps_sussesfull_message');?>' placeholder="Thank you message" class="form-control" aria-label="title">
                          </div><!-- /input-group -->
                           <div class="alert alert-defualt" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Enter thank you message here-after complete subscription</div>
                        </div><!-- /.col-lg-6 -->
                       
                        <div class="col-lg-6">
                         <div class="input-group">
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                            </span>
                            <textarea  name="wps_comment_textmessage" placeholder="Message" class="form-control" ><?php echo get_option('wps_comment_textmessage');?></textarea>
                          </div><!-- /input-group -->
                           <div class="alert alert-defualt" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Enter additional information for subscription form</div>
                        </div><!-- /.col-lg-6 -->                      
                        
               </div>
               
                        
               <div class="row">   <hr>
                        <div class="col-lg-12">
                                              
                            <input type="submit" class="btn btn-primary" name="submit" Value="Save">
                         
                        </div><!-- /.col-lg-6 -->
                        </div><!-- /.row -->
                      </form>
                      
                      
          </div>
    </div>
  </div> 
</div>

      
  </div>
  <div class="panel-footer"><h4>Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/product/wp-all-backup/"> All WPSeeds Plugins.</a> Use Coupon code 'WPDB25'</h4></div>
</div>
