<?php
require_once("../../config.php");
$query=mysqli_query($con2,"select * from user_master where cUserId=\"$__UserId\"");
//echo "select * from user_master where cUserId=\"$__UserId\"";
if(!mysqli_num_rows($query) || mysqli_num_rows($query) > 1)
{
	$Err=true;
	$ErrMsg="sorry Something Went Wrong";
}
else
{
	$row=mysqli_fetch_array($query);
    $cPassword=base64_decode(base64_decode($row['cPassword']));
    $userImage1=trim($row['userImage']);
	if(empty(trim($userImage1)))
	{
		
		$userImage1="../assets/images/users/avatar-2.jpg";
	}
	
}
?>

<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Profile</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Settings</li>
			</ol>
		</div>

	</div>
</div>
</div> 
<div class="card">  
                               <?php
							   if($Err==true)
							   {
								   die($ErrMsg);
							   }
							   ?>
                                <div class="card-body">

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#password" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">Password</span>
                                            </a>
                                        </li>
                                       
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                                <span class="d-none d-sm-block">Basic Info</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="password" role="tabpanel">
                                            <div class="timeline-count mt-5">
                                                <!-- Timeline row Start -->
                                                <div class="row">
                                                  
                                                   <div class="col-sm-6">
												     <label for="">Password</label>
													 <input type="password" class="form-control" name="txtPassword"  value="<?=$cPassword?>" id="txtPassword">
													 <label for=""> Confirm Password</label>
													 <input type="password" name="txtConfirm" id="txtConfirm" value="<?=$cPassword?>" class="form-control">
													 <br>
													 <button type="button" class="btn btn-primary waves-effect waves-light" id="update">Update Password</button>
												   </div>
                                                   
                                                 </div>
                                                <!-- Timeline row Over -->

                                            </div>
                                        </div>
                                        
                                        <div class="tab-pane" id="settings" role="tabpanel">

                                            <div class="row mt-4">
											 <div class="col-md-6">
                                                   <div class="user-wid text-center py-4">
													<div class="user-img">
														<img src="<?=$userImage1?>" height="300px" width="300px" alt="" class="mx-auto rounded-circle">
													</div>

													<div class="mt-3">
                                                        <form method="post" name="frmData" id="frmData" enctype="multipart/form-data">
                                                        <input type="file" id="fileInput" accept="image/*" name="fileInput" style="display:none">
														</form>
														<a href="javascript:void(0)" class="text-dark font-weight-medium font-size-16"><label for="fileInput">Update DP</label></a>
														

													</div>
												</div> 
                                                </div> 
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="firstname">First Name</label>
                                                        <input type="text" class="form-control" maxlength="50" id="firstname" value="<?=$row['first_name']?>" placeholder="Enter first name">
                                                    </div>
													<div class="form-group">
                                                        <label for="lastname">Last Name</label>
                                                        <input type="text" class="form-control" maxlength="50" id="lastname" value="<?=$row['last_name']?>" placeholder="Enter last name">
                                                    </div>
													<div class="form-group">
                                                        <label for="username">UserName</label>
                                                        <input type="text" class="form-control" maxlength="50" id="username" value="<?=$row['cLoginName']?>" placeholder="Enter Username">
                                                    </div>
													<div class="form-group">
													    <br><br>
                                                        <button type="button" class="btn btn-primary waves-effect waves-light float-left" id="updateInfo">Update Info</button>
                                                    </div>
                                                </div>
                                               <!-- end col -->
                                            </div>
											
                                        </div>
                                    </div>

                                </div>
                            </div>
							<script>
							  $("#update").click(function(){
								  $("#update").attr("disabled",true);
								  preloadfadeIn();
								  var validata=true;
								  var errmsg="";
								  var focusid="";
								  if($("#txtPassword").val()==='')
								  {
									  validata=false;
									  focusid="txtPassword";
									  errmsg="Please enter password";
								  }
								  else if($("#txtConfirm").val()==='')
								  {
									  validata=false;
									  errmsg="Please re enter password";
									  focusid="txtConfirm";
								  }
								  else if($("#txtConfirm").val().length!=$("#txtPassword").val().length)
								  {
									  validata=false;
									  errmsg="password length doesn't match";
									  focusid="txtPassword";
								  }
								  else if($("#txtConfirm").val()!=$("#txtPassword").val())
								  {
									  validata=false;
									  errmsg="password  doesn't match";
									  focusid="txtConfirm";
								  }
								  if(validata==false)
								  {
									  toastr.error(errmsg);
									  $("#"+focusid).focus();
									   $("#update").attr("disabled",false);
								       preloadfadeOut();
								  }
								  else
								  {
									  $.ajax({
										     url:'profile/updatePassword.php',
											 type:'POST',
											 data:{"txtPassword":$("#txtPassword").val(),"txtConfirm":$("#txtConfirm").val()},
											 success:function(response)
											 {
												 var data=JSON.parse(response);
												 if(data.error)
												 {
													 toastr.error(data.error_msg);
													  $("#update").attr("disabled",false);
								                      preloadfadeOut();
												 }
												 else
												 {
													 toastr.success(data.error_msg);
													  $("#update").attr("disabled",false);
								                      // preloadfadeOut();
													  MenuClick2('',btoa(btoa("profile/settings.php")));
												 }
												 
											 },error(response)
											 {
												 
												  //toastr.error(response.status);
												  toastr.error("communication error");
												  $("#update").attr("disabled",false);
								                  preloadfadeOut();
											 }
									  })
								  }
								  
							  })
							</script>
							<script>
							$("#updateInfo").click(function(){
								var validata=true;
								var errmsg="";
								var focusid="";
								if($("#firstname").val()==='')
								{
									validata=false;
									errmsg="Please enter first name";
									focusid="firstname";
								}
								else if($("#lastname").val()==='')
								{
									validata=false;
									focusid="lastname";
									errmsg="Please enter last name";
								}
								else if($("#username").val()==='')
								{
									validata=false;
									focusid="username";
									errmsg="Please enter username";
								}
								if(validata==false)
								{
									toastr.error(errmsg);
									$("#"+focusid).focus();
								}
								else
								{
									$.ajax({
										    url:'profile/updateInfo.php',
											type:'POST',
											data:{"firstname":$("#firstname").val(),"lastname":$("#lastname").val(),"username":$("#username").val()},
											success:function(response)
											{
												MenuClick2('',btoa(btoa("profile/settings.php")));
											},error:function(response)
											{
												toastr.error(response.text);
											}
									})
								}
							
							})
							</script>
							<script>
							$("#fileInput").change(function(){
								if($("#fileInput").val!='')
								{
									var formData = new FormData();
									var other_data = $('#frmData').serializeArray();
									
									$.each(other_data,function(key,input){
										formData.append(input.name,input.value);
									});
									
										
										formData.append("fileInput",$("#fileInput")[0].files[0]);
									
									$.ajax({
										   url:'profile/test.php',
										   type:'POST',
										   data:formData,
										    async: true,
											cache: false,
											contentType: false,
											processData: false,
										   success:function(response)
										   {
											   var dataResponse=JSON.parse(response);
											   if(dataResponse.error)
											   {
												   toastr.error(dataResponse.error_msg);
											   }
											   else
											   {
												   toastr.success(dataResponse.error_msg);
												   MenuClick2('',btoa(btoa("profile/settings.php")));
											   }
											  
										   },error:function(response)
										   {
											   toastr.error(response.text);
										   }
									})
								}
							})
							</script>