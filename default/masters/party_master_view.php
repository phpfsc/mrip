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
		$query=mysqli_query($con2,"select iPartyID,cPartyName,cContactPerson ,cGSTIn ,cStateCode ,cAddress, concat('<b>Ph:</b> ',cPhone,'<br><b>Mob.</b> ',cMobile) as cContact,cTINNo , if(bActive='1', 'Active','InActive') as Active from party_master");
		if(!$query)
		{
			$Err=true;
			$ErrMsg="Something went wrong Please try again";
		}
	}
 

?>

<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Masters/Party Master</h4>
        <?php
		if($__add==true && $__update==true)
		{
		?>
		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item"><button type="button" class="btn btn-primary btn-sm waves-effect waves-light add">Add New Party</button></li>
			</ol>
		</div>
        <?php
		}
		?>
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
		<input type="hidden" name="eUrl" id="eUrl" value="<?=base64_encode(base64_encode("masters/party_master.php"))?>">
		<input type="hidden" name="nUrl" id="nUrl" value="<?=base64_encode(base64_encode("masters/party_master.php"))?>">
		<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
		 <thead>
		  <tr>
		    <th>S No</th>
		    <th>Party Name</th>
		    <th>Contact Name</th>
		    <th>TIN No</th>
		    <th>GST In</th>
		    <th>State Code</th>
		    <th>Contact No</th>
		    <th>Address</th>
		    <th>Status</th>
		    <th>Action</th>
		  </tr>
		 </thead>
		 <tbody>
		  <?php
		  $sn=1;
		  while($row=mysqli_fetch_array($query))
		  {
		  ?>
		  <tr>
		  <td><?=$sn;?></td>
		  <td><?=$row['cPartyName'];?></td>
		  <td><?=$row['cContactPerson'];?></td>
		  <td><?=$row['cTINNo'];?></td>
		  <td><?=$row['cGSTIn'];?></td>
		  <td><?=$row['cStateCode'];?></td>
		  <td><?=$row['cContact'];?></td>
		  <td><?=$row['cAddress'];?></td>
		  <td><?=$row['Active'];?></td>
		  <td>
		  <?php
		  if($__update==true)
		  {
		  ?>
		  <i class="fas fa-edit" onclick="editPartyDetails('<?=base64_encode(base64_encode($row[iPartyID]))?>')"></i>
		  <?php
		  }
		  ?>
		  </td>
		  </tr>
		  <?php
		  $sn++;
		  }
		  ?>
		 </tbody>
		</table>
		</div>
	</div>
</div>

<script>
  $(function () {
    $("#datatable").DataTable({
      "responsive": true,
      "autoWidth": false,
	  "paging": true,
	  "searching": true,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script>
function editPartyDetails(Id)
{
	preloadfadeIn();
	$.ajax({
		url:atob(atob($("#eUrl").val())),
		type:'POST',
		data:{"auth_info":'<?=$_POST[auth_info]?>',"iPartyID":Id},
		success:function(response)
					{
						
						
						$(".page-content").html(response);
						preloadfadeOut();
						
					},
                    error: function (jqXHR, status, err) {
						if(jqXHR.status=='404')
						{
							toastr.error("Page not found");
							$(".fas fa-edit").prop('disabled',false);
							preloadfadeOut();
						}
						if(jqXHR.status=='500')
						{
							toastr.error("internal server error");
							$(".fas fa-edit").prop('disabled',false);
							preloadfadeOut();
						}
						
                        
                    },
                    complete: function (jqXHR, status) {
						$(".fas fa-edit").prop('disabled',false);
                        preloadfadeOut();
                    }
	})
}
</script>
<script>
$(".add").click(function(){
	
	MenuClick2('<?=$_POST[auth_info]?>',$("#nUrl").val());
})
</script>
