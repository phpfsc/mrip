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
		
		$query=mysqli_query($con2,"select * from user_master");
		if(!$query)
		{
			$Err=true;
			$ErrMsg="Somthing Went Wrong please try again";
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
		  <div class="col-sm-6"></div>
		  <div class="col-sm-6">
		    <span class="float-right"><button type="button" class="btn btn-primary btn-sm waves-effect waves-light">Add New User</button></span>
		  </div>
		</div>
		<br>
    	<div class="row">
		  <div class="col-sm-12">
		    <table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
			  <thead>
			    <tr>
				 <th>S No</th>
				 <th>Username</th>
				 <th>Name</th>
				 <th>Usertype</th>
				 <th>Options</th>
				</tr>
			  </thead>
			  <tbody>
			    <?php
				$i=1;
				  while($row=mysqli_fetch_array($query))
				  {
					  ?>
                       <tr>
					    <td><?=$i?></td>
					    <td><?=$row['cLoginName']?></td>
					    <td><?=$row['first_name']." ".$row['last_name']?></td>
					    <td><?=($row['bBilling']=='2')?'Kacha Bill':'Pakka bill'?></td>
						<td style="text-align:center"><button type="button" value="<?=base64_encode(base64_encode($row['cUserId']))?>"   class="btn btn-primary btn-sm waves-effect waves-light Edit"><i class="fas fa-edit"></i></button></td>
					   </tr>					  
					  <?php
					  $i++;
				  }
				?>
			  </tbody>
			</table>
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
			   $(".page-content").html(response);
		   },error:function(response)
		   {
			   alert(response.status);
		   }
	})
})
</script>
