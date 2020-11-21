<?php
    require ('../../config.php');
    $Err=false;
    $ErrMsg="";
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="Sorry No Direct Access Allowed";
	}
	
	else
	{
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
		if(!empty(trim($_POST['Id'])))
		{
			$PageMode="Edit";
			$Id=base64_decode(base64_decode(trim($_POST['Id'])));
			$query=mysqli_query($con2,"select * from user_master where cUserId=\"$Id\"");
			if(!$query || !mysqli_num_rows($query))
			{
				$Err=true;
				$ErrMsg="Somthing Went Wrong please try again";
			}
			else
			{
				$row=mysqli_fetch_array($query);
			}
		}
		else
		{
			$PageMode="New";
		}
		
		
   }
?>
<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Masters</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">User Master</li>
			</ol>
		</div>

	</div>
</div>
</div>


<div class="row">

<?php
if($Err==true)
{
	die($ErrMsg);
}
?>
<div class="col-xl-12">
	<div class="card">
		<div class="card-body">
		
<form name="frmUserMaster" id="frmUserMaster" method="POST" enctype="multipart/form-data">
       
		
    	<div class="row">
		  <div class="col-sm-6">
		    <label for="username">Username</label>
			<input type="text" id="username" name="username" class="form-control" value="<?=$row['cLoginName']?>">
		  </div>
		  <div class="col-sm-6">
		    <label for="txtPassword">Password</label>
			<input type="password" id="txtPassword" name="txtPassword" class="form-control" value="<?=base64_decode(base64_decode($row['cPassword']))?>">
		  </div>
		   <div class="col-sm-6">
		    <label for="first_name">First Name</label>
			<input type="text" id="first_name" name="first_name" class="form-control" value="<?=$row['first_name']?>">
		  </div>
		  <div class="col-sm-6">
		    <label for="txtConfirm">Confirm Password</label>
			<input type="password" id="txtConfirm" name="txtConfirm" class="form-control" value="<?=base64_decode(base64_decode($row['cPassword']))?>">
		  </div>
		  <div class="col-sm-6">
		    <label for="last_name">Last Name</label>
			<input type="text" id="last_name" name="last_name" class="form-control" value="<?=$row['last_name']?>">
		  </div>
		</div>
		<br>
		<div class="row">
		  <div class="col-sm-12">
		    <button type="button" class="btn btn-primary waves-effect waves-light">Save</button>
		    <button type="button" class="btn btn-secondary waves-effect waves-light">Cancel</button>
		  </div>
		</div>
</form>
</div>
</div>
</div>
</div>
<script>
$(".Edit").click(function(){
	$(".Edit").attr("disabled",true);
	$.ajax({
		   url:'masters/user_master.php',
		   type:'POST',
		   data:{"auth_info":"<?=$_POST['auth_info']?>","Id":this.value},
		   success:function(response)
		   {
			   alert(response);
		   },error:function(response)
		   {
			   alert(response.status);
		   }
	})
})
</script>
