<?php
	require_once("../../config.php");
	$PageMode="New";
	$Err=false;
	$ErrMsg="";
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="Invalid Request";
	}
	else
	{
		$CurrDate=date("Y-m-d");
	    $query=mysqli_query($con2,"select tbl.* from(select cPartyName , SUM(iNoPcsDisp + iNoPcsReturn) as Disp ,party_challan.cChallanCode ,concat(party_challan.iChallanID,'') as iChallanID , '' as yearprefix, dChallanDate from party_challan join party_challan_detail on party_challan.iChallanID  = party_challan_detail.iChallanID join party_master on party_challan.iPartyCode =party_master.iPartyID inner join party_order on party_order.iOrderID=party_challan_detail.iOrderID  where dChallanDate BETWEEN \"$dStartDate\" AND \"$CurrDate\" group by party_challan.iChallanID)as tbl order by tbl.iChallanID DESC");		
	    if(!$query)
		{
			$Err=true;
			$ErrMsg="Invalid Request";
		}
		else
		{
			$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
			$__view=$auth_info[0]; //view
			$__add=$auth_info[1]; //add 
			$__update=$auth_info[2]; //update
			$__delete=$auth_info[3]; //delete
		}
	}


	
	
	

?>
<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Operations</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Delivery Challan</li>
			</ol>
		</div>

	</div>
</div>
</div>

<div class="card">
		<div class="card-body">
		<?php
		if($Err==true)
		{
			die($ErrMsg);
		}
		?>
<Form name="frmDeliveryChallan" method="POST" action="">
	    <input type="hidden" id="auth_info" name="auth_info" value="<?=$_POST['auth_info']?>">
		<div class="row">
			 <div class="col-sm-8">
			
			 <span class="float-left">
				<input type="radio" name="rdb" id="rdNew" value="New" onClick="Switch(this.id)">&nbsp;New Challan
				<input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)" checked >&nbsp;List Challan
				
				&nbsp;&nbsp;&nbsp;
				<small style="color:red">* Delivery Challans which are already billed can not be Edited.</small>
				</span>
			 </div>
			 <div class="col-sm-4">
			  <span class="float-right">
				<?php
					$CodeMsg="";
					$result=mysqli_query($con2,"select cChallanCode from party_challan where iChallanID=(select MAX(iChallanID) from party_challan)");
					if ($result)
					{
						if (mysqli_num_rows($result)>0)
						{
							$row=mysqli_fetch_array($result);
							$CodeMsg="Last Delivery Challan No : ".$row['cChallanCode'];
						}
						else
						{
							$CodeMsg="No Delivery Challan saved before...";
						}
					}
					echo $CodeMsg;
				?>
			  </span>
			 </div>
			 
		</div>
		
		<br>
		<div class="row">
		  <div class="col-sm-6">
			   <label for="cmbSelParty">Party List</label>
			   <select name="cmbSelParty" id="cmbSelParty" class="form-control"  tabindex=1>
						<option value=""><?=str_repeat("-",20) ?>Select Party<?=str_repeat("-",20) ?></option>
						<?php
							
							$result=mysqli_query ($con2,"select (iPartyID) as PartyID , cPartyName from party_master  order by cPartyName");
							if ($result && mysqli_num_rows($result)>0)
							{
								while ($row=mysqli_fetch_array($result))
								{
									print "<option value=\"$row[PartyID]\"";
									if ($row['PartyID']==$_GET['PartyID']) print "selected";
									print ">$row[cPartyName]</option>";
								}
							}
						?>
				</select>
			 </div>
			 <div class="col-sm-3">
			   <input type="hidden" name="hfCurrDate" id="hfCurrDate" >
			   <input type="hidden" name="auth_info" value="<?=$_POST['auth_info']?>" id="auth_info">
			   <label for="txtFromDate">From Date</label>
	           <input class="form-control" type="date"  value="<?=$dStartDate; ?>" onFocus="this.blur()" min="<?=$dStartDate?>" max="<?=$CurrDate?>" name="txtFromDate" id="txtFromDate">
			   															
			 </div>
			 <div class="col-sm-3">
			 <input type="hidden" name="hfCurrDate" id="hfCurrDate" >
				<label for="txtFromDate">To Date</label>
				<input type="date" name="txtToDate" id="txtToDate" class="form-control" min="<?=$dStartDate?>"  value="<?=$CurrDate; ?>" max="<?=$CurrDate?>"  onFocus="this.blur()">
					
			 </div>
		</div>
		<br>
		<div class="row">
		  <div class="col-sm-12">
		  <?php
		  if($__view==true)
		  {
		  ?>
		    <input type="button" class="btn btn-primary waves-effect waves-light float-right" name="btnGetData" id="btnGetData" value="Get Data" class="btncss" onClick="getData(this.id)">
          <?php
		  }
		  ?>		 
		 </div>
		</div>
		<br>
		<div class="row">
		   <div class="col-sm-12" id="disptable">
		   <table id="example3" class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
		    <thead>
			  <tr>
			    <th>S. No.</th>
			    <th>Challan Code</th>
			    <th>Date</th>
			    <th>Party Name</th>
			    <th>Disp</th>
			    <th>Action</th>
			  </tr>
			</thead>
			<tbody>
			 <?php
			 $i=1;
			 while($row=mysqli_fetch_array($query))
			 {
				
				 $Data=$row['fsYear']."~".$row['iChallanID']."~".$row['yearprefix'];
				 $Data=base64_encode(base64_encode($Data));
				 
				 ?>
				    <tr>
					  <td><?=$i?></td>
					  <td><?=$row['cChallanCode']?><?=empty($row['yearprefix'])?'':'(Old)'?></td>
					  <td><?=$row['dChallanDate']?></td>
					  <td><?=$row['cPartyName']?></td>
					  <td><?=$row['Disp']?></td>
					  <td>
					     <button type="button" name="btnEdit" id="btnEdit" value="Edit" class="btn btn-primary btn-sm waves-effect waves-light edit"  onClick ="editChallan('<?=$Data?>')">Edit</button>
                         <button type="button" name="btnDelete" id="btnDelete" value="" class="btn btn-primary btn-sm waves-effect waves-light delete" onClick="DeleteRecord('<?=$Data?>')"><i class="fas fa-trash"></i></button>
						 <button type='button' name='btnPrint' id='btnPrint' class='btn btn-primary btn-sm waves-effect waves-light'  onClick="LinkUrlClick('<?=$Data?>')"><i class="fas fa-print"></i></button>
					  </td>
					  
					  
					  
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
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script type="text/javascript" language="javascript" src="../js/string.js"></script>
<script type="text/javascript" language="javascript" src="../js/delivery_challan.js"></script>
<script>
$("document").ready(function(){
	$("#example3").dataTable().fnDestroy();
	$('#example3').DataTable({
		
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
})
</script>
<script type="text/javascript" language="JavaScript">
function getData(Id)
{
	$("#btnGetData").attr("disabled",true);
	preloadfadeIn();
	var validata=true;
	var errmsg="";
	var focusid="";
	if($("#cmbSelParty").val()==='' && ($("#txtFromDate").val()==='' || $("#txtToDate").val()===''))
	{
		validata=false;
		errmsg="Please select date range or party name";
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#btnGetData").attr("disabled",false);
	    preloadfadeOut();
		
	}
	else
	{
		$.ajax({
		url:'operations/getChallanData.php',
		type:'POST',
		data:{"FromDate":$("#txtFromDate").val(),"ToDate":$("#txtToDate").val(),"PartyID":$("#cmbSelParty").val()},
		success:function(response)
		{
			   //alert(response);
			   var responseText=JSON.parse(response);
			   if(responseText.error)
			   {
				   toastr.error(responseText.error_msg);
				   $("#btnGetData").attr("disabled",false);
	               preloadfadeOut();
			   }
			   else
			   {
				   $("#disptable").html("");
				   $("#disptable").html(responseText.data);
				   $("#example3").dataTable().fnDestroy();
					$('#example3').DataTable({
						
					  "paging": true,
					  "lengthChange": true,
					  "searching": true,
					  "ordering": true,
					  "info": true,
					  "autoWidth": false,
					  "responsive": true,
					});
					$("#btnGetData").attr("disabled",false);
	                preloadfadeOut();
			   }
		},error()
		{
			toastr.error("Error :Please try again Later");
		}
		})
	}
	
    

}

function LinkUrlClick(Data)
{
	viewurl="operations/delivery_challan_showpdf.php";
	URL=viewurl+"?Data="+Data;
	
	OpenWin=window.open(URL,"Report","toolbar=no,menubar=no,resizable=yes,location=no,scrollbars=no,width=1024,height=700");
	event.returnValue=false;
}
function editChallan(DataChallan)
{
	$(".edit").attr("disabled",true);
	preloadfadeIn();
	$.ajax({
		url:'operations/delivery_challan.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"DataChallan":DataChallan},
		success:function(response)
		{
			 $('.page-content').html(response);
			 preloadfadeOut();
			   // $(".edit").attr("disabled",false);
			// var responseText=JSON.parse(response);
			// if(responseText.error)
			// {
				// toastr.error(responseText.error_msg);
				// preloadfadeOut();
			    // $(".edit").attr("disabled",false);
			// }
			// else
			// {
				// toastr.success(responseText.error_msg);
				// $(".edit").attr("disabled",false);
				// MenuClick2('<?=$_POST[auth_info]?>','<?=base64_encode(base64_encode("operations/delivery_challan_edit.php"))?>');
				
			// }
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			
			$('.page-content').load("404.php");
			preloadfadeOut();
			$(".edit").attr("disabled",false);
			
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			$(".edit").attr("disabled",false);
			 
		}
		else
		{
			preloadfadeOut();
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			$(".edit").attr("disabled",false);
			
		}
		
    }
		
	})
}
function DeleteRecord(Data)
{
	$(".delete").attr("disabled",true);
	preloadfadeIn();
	$.ajax({
		url:'operations/party_challan_delete.php',
		type:'POST',
		data:{"Data":Data},
		success:function(response)
		{
			
			var responseText=JSON.parse(response);
			if(responseText.error)
			{
				toastr.error(responseText.error_msg);
				preloadfadeOut();
			    $(".delete").attr("disabled",false);
			}
			else
			{
				toastr.success(responseText.error_msg);
				$(".delete").attr("disabled",false);
				MenuClick2('<?=$_POST[auth_info]?>','<?=base64_encode(base64_encode("operations/delivery_challan_edit.php"))?>');
				
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			
			$('.page-content').load("404.php");
			preloadfadeOut();
			$(".delete").attr("disabled",false);
			
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			$(".delete").attr("disabled",false);
			 
		}
		else
		{
			preloadfadeOut();
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			$(".delete").attr("disabled",false);
			
		}
		
    }
		
	})
}
</script>

