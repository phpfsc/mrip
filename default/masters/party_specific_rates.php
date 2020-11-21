<?php
	require_once("../../config.php");
	$PageMode="New";
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
    }


	
?>

<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Masters</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Expeller Parts Party Rate</li>
			</ol>
		</div>

	</div>
</div>
</div>



<?php
if($Err==true)
{
	die($ErrMsg);
}
?>

	<div class="card">
		<div class="card-body">

         <form name="frmSpecificRateMaster" method="POST" onsubmit="return false"> 
		  <div class="form-group row">
			<label for="cmbParty" class="col-md-2 col-form-label">Party List :</label>
			<div class="col-md-6">
				<select name="cmbParty" id="cmbParty" class="form-control" onchange="selItems(this.id)">
								<option value=""><?=str_repeat("-",20) ?>Select Party<?=str_repeat("-",20) ?></option>
								<?php
									
									$result=mysqli_query ($con2,"select (iPartyID) as PartyID, iPartyID , cPartyName from party_master where cPartType='Expeller' order by cPartyName");
									if ($result && mysqli_num_rows($result)>0)
									{
										while ($row=mysqli_fetch_array($result))
										{
											
											echo  "<option value=\"$row[PartyID]\"";
											if ($PartyId==$row['PartyID'])	echo  "selected";
											echo  ">$row[cPartyName]</option>";
										}
									}
								?>
				</select>
			</div>
		</div>
		<div class="row">
		   <div class="col-md-12">
		     <fieldset style="width:100%;">
								<legend class="legend">Plain Gears/Pinion Milling M/c Rates</legend>
								<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="width:100%">
									<tr>	  
										<td>
											<input type="hidden" name="hdGearRatePlain" id="hdGearRatePlain" value="<?=$GearData ?>">
											<label for="txtGearProcessingPlain">DP/Module :</label>
										    <input type="text" name="txtGearProcessingPlain" id="txtGearProcessingPlain" class="form-control">
											<br>
											<select name="cmbGearProcessingTypePlain" id="cmbGearProcessingTypePlain" class="form-control">								
												<option value="DP">DP</option>
												<option value="Module">Module</option>
											</select>
										</td>
										<td>
											
											<label for="txtGearFaceFromPlain">Face From :</label>
											<input type="text" name="txtGearFaceFromPlain" id="txtGearFaceFromPlain" class="form-control">
										</td>	
										<td>
											
											<label for="txtGearFaceToPlain">To :</label>
											<input type="text" name="txtGearFaceToPlain" id="txtGearFaceToPlain" class="form-control">
											<br>
											<select name="cmbGearFaceTypePlain" id="cmbGearFaceTypePlain" class="form-control">								
												<option value="inches">inches</option>
												<option value="mm">mm</option>
											</select>
										</td>	
										<td>
											<label for="txtGearFaceToPlain">Rate :</label>
											<input type="text" name="txtGearRatePlain" id="txtGearRatePlain" class="form-control" onblur="DecimalNum(this.id)">
										</td>
										
									</tr>
									<tr>
										<td colspan="4">
											<input type="button" name="btnGearAddPlain" id="btnGearAddPlain" value="Add" class="btn btn-primary waves-effect waves-light float-right"  style="margin-top:5px; margin-bottom:10px;" onclick="AddGearRate()" onKeydown="AddGearRate()">
										</td>
									</tr>
								</table>
								<div id="dvGearDataPlain" style="width:100%"></div>
							   <br>
							  <div><button type="button" name="btnGearDataPlain" id="btnGearDataPlain"  class="btn btn-primary waves-effect waves-light float-right" >Save & Update Rates</button></div>
							</fieldset>
		   </div>
		</div>
		<div class="row">
		  <div class="col-md-12">
		     <fieldset style="width:100%">
								<legend class="legend">Helical Gears/Pinion Milling M/c Rates</legend>
								<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="width:100%;">
									<tr>	  
										<td>
											<input type="hidden" name="hdGearRateHelical" id="hdGearRateHelical" value="<?php print $GearDataHelical ?>">
											<label for="txtGearProcessingHelical">DP/Module :</label>
											<input type="text" name="txtGearProcessingHelical" id="txtGearProcessingHelical" class="form-control">
											<br>
											<select name="cmbGearProcessingTypeHelical" id="cmbGearProcessingTypeHelical" class="form-control">								
												<option value="DP">DP</option>
												<option value="Module">Module</option>
											</select>
										</td>
										<td>
											
											<label for="txtGearFaceFromHelical">Face From :</label>
											<input type="text" name="txtGearFaceFromHelical" id="txtGearFaceFromHelical" class="form-control">
										</td>	
										<td>
											
											<label for="txtGearFaceFromHelical">To :</label>
											<input type="text" name="txtGearFaceToHelical" id="txtGearFaceToHelical" class="form-control">
											<br>
											<select name="cmbGearFaceTypeHelical" id="cmbGearFaceTypeHelical" class="form-control">								
												<option value="inches">inches</option>
												<option value="mm">mm</option>
											</select>
										</td>	
										<td>
											
											<label for="txtGearFaceFromHelical">Rate :</label>
											<input type="text" name="txtGearRateHelical" id="txtGearRateHelical" class="form-control" onblur="DecimalNum(this.id)">
										</td>
									</tr>
									<tr>
										<td colspan="5">
											<input type="button" name="btnGearAddHelical" id="btnGearAddHelical" value="Add" class="btn btn-primary waves-effect waves-light float-right"   onclick="AddGearHelicalRate()" onKeydown="AddGearHelicalRate()">
										</td>
									</tr>
								</table>
								<div id="dvGearDataHelical" style="width:100%"></div>
							   <br>
							   <div ><button type="button" name="btnGearDataHelical" id="btnGearDataHelical"  class="btn btn-primary waves-effect waves-light float-right" >Save & Update Rates</button></div>
							</fieldset>
		  </div>
		</div>
        <div class="row">
		  <div class="col-md-12">
		     <fieldset style="width:100%;">
								<legend class="legend">Plain Shaft Pinion Rates</legend>
								
								<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="width:100%;">
									<tr>	  
										<td>
											<input type="hidden" name="hdShaftPinion" id="hdShaftPinion" value="<?=$ShaftPinionData ?>">
											<label for="txtShaftPinionProcessing">DP/Module :</label>
											<input type="text" name="txtShaftPinionProcessing" id="txtShaftPinionProcessing" class="form-control">
											<br>
											<select name="cmbShaftPinionProcessingType" id="cmbShaftPinionProcessingType" class="form-control">
                                            	<option value="DP">DP</option>
												<option value="Module">Module</option>
											</select>
										</td>
										<td>
										    <label for="txtShaftPinionFaceFrom">Face From :</label>
											<input type="text" name="txtShaftPinionFaceFrom" id="txtShaftPinionFaceFrom" class="form-control">
										</td>	
										<td>
											<label for="txtShaftPinionFaceTo">To :</label>
											<input type="text" name="txtShaftPinionFaceTo" id="txtShaftPinionFaceTo" class="form-control">
											<br>
											<select name="cmbShaftPinionFaceType" id="cmbShaftPinionFaceType" class="form-control">								
												<option value="inches">inches</option>
												<option value="mm">mm</option>
											</select>
										</td>
										<td>
											<label for="txtShaftPinionTeeth">Teeth :</label>
											<input type="text" name="txtShaftPinionTeeth" id="txtShaftPinionTeeth" class="form-control" onkeydown="OnlyInt1(this.id)">
										</td>
										<td>
										    <label for="txtShaftPinionRate">Rate :</label>
											<input type="text" name="txtShaftPinionRate" id="txtShaftPinionRate" class="form-control" onblur="DecimalNum(this.id)">
										</td>
									</tr>
									<tr>
										<td colspan="6">
											<input type="button" name="btnShaftPinionAdd" id="btnShaftPinionAdd" value="Add" class="btn btn-primary waves-effect waves-light float-right"   onclick="AddShaftPinionRate()" onKeydown="AddShaftPinionRate()">
										</td>
									</tr>
								</table>
								<div id="dvShaftPinionData" style="width:100%"></div>
							   <br>
							   <div><button type="button" name="btnShaftPinion" id="btnShaftPinion"  class="btn btn-primary waves-effect waves-light float-right" >Save & Update Rates</button></div>
							</fieldset>
		  </div>
		</div>
		<div class="row">
		   <div class="col-md-12">
		     <fieldset>
								<legend class="legend">Helical Shaft Pinion Rates</legend>
								
								<table  style="width:100%;" class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
									<tr>	  				
										<td>
											<input type="hidden" name="hdShaftPHelical" id="hdShaftPHelical" value="<?php print $ShaftPDataHelical ?>">
											<label for="txtShaftPProcessingHelical">DP/Module:</label>
											<input type="text" name="txtShaftPProcessingHelical" id="txtShaftPProcessingHelical" class="form-control">
											<br>
											<select name="cmbShaftPProcessingTypeHelical" id="cmbShaftPProcessingTypeHelical" class="form-control">								
												<option value="DP">DP</option>
												<option value="Module">Module</option>
											</select>
										</td>
										<td>
										    <label for="txtShaftPFaceFromHelical">Face From :</label>
											<input type="text" name="txtShaftPFaceFromHelical" id="txtShaftPFaceFromHelical" class="form-control">
										</td>	
										<td>
											
											<label for="txtShaftPFaceToHelical">To :</label>
											<input type="text" name="txtShaftPFaceToHelical" id="txtShaftPFaceToHelical" class="form-control">
											<br>
											<select name="cmbShaftPFaceTypeHelical" id="cmbShaftPFaceTypeHelical" class="form-control">								
												<option value="inches">inches</option>
												<option value="mm">mm</option>
											</select>
										</td>	
										<td>
											<label for="txtShaftPRateHelical">Rate :</label>
											<input type="text" name="txtShaftPRateHelical" id="txtShaftPRateHelical" class="form-control" onblur="DecimalNum(this.id)">
										</td>
									</tr>
									<tr>
										<td colspan="5">
											<input type="button" name="btnShaftPHelicalAdd" id="btnShaftPHelicalAdd" value="Add" class="btn btn-primary waves-effect waves-light float-right"  style="margin-top:5px; margin-bottom:10px;" onclick="AddShaftPHelicalRate()" onKeydown="AddShaftPHelicalRate()">
										</td>
									</tr>
								</table>
								<div id="dvShaftPHelicalData" style="width:100%"></div>
							   <br>
							   <div><button type="button" name="btnShaftPHelical" id="btnShaftPHelical"  class="btn btn-primary waves-effect waves-light float-right" >Save & Update Rates</button></div>
							</fieldset>
		   </div>
		</div>
		<div class="row">
		   <div class="col-md-12">
		      <fieldset>
								<legend class="legend">Chain/Sprocket Gear Rates</legend>
								<table  style="width:100%;" class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
									<tr>
										<td>
											<input type="hidden" name="hdChainGear" id="hdChainGear" value="<?=$ChainGearData ?>">
											<label for="txtChainGearPitch">Pitch Type :</label>
											<input type="text" name="txtChainGearPitch" id="txtChainGearPitch" class="form-control">
											<br>
											<select name="cmbChainGearType" id="cmbChainGearType" class="form-control">								
												<option value="inches">inches</option>
												<option value="mm">mm</option>
											</select>
										</td>
										<td>
										    <label for="txtChainGearRate">Rate :</label>
											<input type="text" name="txtChainGearRate" id="txtChainGearRate" class="form-control" onblur="DecimalNum(this.id)">
										</td>
										
									</tr>
									<tr>
									 <td colspan="2">
											<br>
											<input type="button" name="btnChainGearAdd" id="btnChainGearAdd" value="Add" class="btn btn-primary waves-effect waves-light float-right"  style="margin-top:5px; margin-bottom:10px;" onclick="AddChainGearRate()" onKeydown="AddChainGearRate()">
										</td>
									</tr>
								</table>
								<div id="dvChainGearData" style="width:100%"></div>
							   <br>
							   <div align="center" style="width:100%"><button type="button" name="btnChainGear" id="btnChainGear"  class="btn btn-primary waves-effect waves-light float-right" >Save & Update Rates</button></div>
							</fieldset>
		   </div>
		</div>
		<div class="row">
		   <div class="col-md-12">
		      <fieldset>
								<legend class="legend">Bevel Gear/Pinion Rates</legend>
								
								<table  class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style ="width:100%">
									<tr>
										<td>
											<input type="hidden" name="hdBevelGear" id="hdBevelGear" value="<?=$BevelGearData ?>">
											<label for="txtBevelGearProcessing">DP/Module :</label>
											<input type="text" name="txtBevelGearProcessing" id="txtBevelGearProcessing" class="form-control">
											<br>
											<select name="cmbBevelGearProcessingType" id="cmbBevelGearProcessingType" class="form-control">								
												<option value="DP">DP</option>
												<option value="Module">Module</option>
											</select>
										</td>
										<td>
											 
											<label for="txtBevelGearTeeth">Teeth :</label>
											<input type="text" name="txtBevelGearTeeth" id="txtBevelGearTeeth" class="form-control">
										</td>
										<td>
											 
											<label for="txtBevelGearRate">Rate :</label>
											<input type="text" name="txtBevelGearRate" id="txtBevelGearRate" class="form-control" onblur="DecimalNum(this.id)">
										</td>
										
									</tr>
									<tr>
									  <td colspan="3">
											
											<input type="button" name="btnBevelGearAdd" id="btnBevelGearAdd" value="Add" class="btn btn-primary waves-effect waves-light float-right"  style="margin-top:5px; margin-bottom:10px;" onclick="AddBevelGearRate()" onKeydown="AddBevelGearRate()">
									  </td>
									</tr>
								</table>
								<div id="dvBevelGearData" style="width:100%"></div>
							   <br>
							   <div><button type="button" name="btnBevelGear" id="btnBevelGear"  class="btn btn-primary waves-effect waves-light float-right" >Save & Update Rates</button></div>
							</fieldset>
		   </div>
		</div>
		<div class="row">
		   <div class="col-md-12">
		     <fieldset>
								<legend class="legend">Special items Rates</legend>
								<table  style="width:100%;" class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
									<tr>	  
										<td>
											<input type="hidden" name="hdSpecialItem" id="hdSpecialItem" value="<?=$SpecialItemData ?>">
											<label for="txtSpecialItemName">Item Name :</label>
											<input type="text" name="txtSpecialItemName" id="txtSpecialItemName" class="form-control">
										</td>	
										<td>
											<label for="txtSpecialItemRate">Rate :</label>
											<input type="text" name="txtSpecialItemRate" id="txtSpecialItemRate" class="form-control" onblur="DecimalNum(this.id)">
										</td>
										<td>
											<label for="cmbSpecialItemType">Measurement:</label>
											<select name="cmbSpecialItemType" id="cmbSpecialItemType" class="form-control">
												<option value="PT">PT</option>
												<Option value="PCS">PCS</option>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<input type="button" name="btnSpecialItemAdd" id="btnSpecialItemAdd" value="Add" class="btn btn-primary waves-effect waves-light float-right"  style="margin-top:5px; margin-bottom:10px;" onclick="AddSpecialItemRate()" onKeydown="AddSpecialItemRate()">
										</td>
									</tr>
								</table>
								<div id="dvSpecialItemData" style="width:100%"></div>
							   <br>
							   <div><button type="button" name="btnSpecialItem" id="btnSpecialItem"  class="btn btn-primary waves-effect waves-light float-right" >Save & Update Rates</button></div>
							</fieldset>
		   </div>
		</div>
		
		
	
</form>
</div>
</div>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script language="javascript" type="text/javascript" src="../js/party_specific_rates.js"></script>
<script type="text/javascript" language="javascript" src="../js/string.js"></script>
<script type="text/javascript" language="javascript" src="../js/typeaheadcombo.js"></script>


<script>
$("document").ready(function(){
	
	Pageload();
	
})
</script>
<script>
$("#btnGearDataPlain").click(function(){
	
	$("#btnGearDataPlain").attr("disabled",true);
	if($("#cmbParty").val()==='')
	{
		toastr.error("Please Choose Party Name");
		$("#cmbParty").focus();
		preloadfadeOut();
	    $("#btnGearDataPlain").attr("disabled",false);
	}
	else if($("#hdGearRatePlain").val()==='')
	{
		toastr.error("Please Add Data first");
		$("#txtGearProcessingPlain").focus();
		preloadfadeOut();
	    $("#btnGearDataPlain").attr("disabled",false);
	}
	
	else
	{
				$.ajax({
				url:'masters/save_party_specific_rates.php',
				type:'POST',
				data:{"auth_info":'<?=$_POST[auth_info]?>',"hdGearRatePlain":$("#hdGearRatePlain").val(),"PartyID":$("#cmbParty").val()},
				cache: false,
				success:function(response)
				{
					 
					var dataResponse=JSON.parse(response);
					if(dataResponse.error)
					{
						toastr.error(dataResponse.error_msg);
						preloadfadeOut();
						$("#btnGearDataPlain").attr("disabled",false);
					}
					else
					{
						toastr.success(dataResponse.error_msg);
						preloadfadeOut();
					    $("#btnGearDataPlain").attr("disabled",false);
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
				if(XMLHttpRequest.status==404)
				{
					toastr.error("Page not found");
					preloadfadeOut();
					$("#btnGearDataPlain").attr("disabled",false);
					
				}
				else if(XMLHttpRequest.status==500)
				{
					toastr.error("Internal server error");
					preloadfadeOut();
					$("#btnGearDataPlain").attr("disabled",false);
				}
				else
				{
					toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
					preloadfadeOut();
					$("#btnGearDataPlain").attr("disabled",false);
					//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
				}
				
			}
			})
	}
	
	
})
</script>
<script>
$("#btnGearDataHelical").click(function(){
	
	$("#btnGearDataHelical").attr("disabled",true);
	if($("#cmbParty").val()==='')
	{
		toastr.error("Please Choose Party Name");
		$("#cmbParty").focus();
		preloadfadeOut();
	    $("#btnGearDataHelical").attr("disabled",false);
	}
	else if($("#hdGearRatePlain").val()==='')
	{
		toastr.error("Please Add Data first");
		$("#txtGearProcessingHelical").focus();
		preloadfadeOut();
	    $("#btnGearDataHelical").attr("disabled",false);
	}
	
	else
	{
				$.ajax({
				url:'masters/save_party_specific_rates.php',
				type:'POST',
				data:{"auth_info":'<?=$_POST[auth_info]?>',"hdGearRateHelical":$("#hdGearRateHelical").val(),"PartyID":$("#cmbParty").val()},
				cache: false,
				success:function(response)
				{
					 
					var dataResponse=JSON.parse(response);
					if(dataResponse.error)
					{
						toastr.error(dataResponse.error_msg);
						preloadfadeOut();
						$("#btnGearDataHelical").attr("disabled",false);
					}
					else
					{
						toastr.success(dataResponse.error_msg);
						preloadfadeOut();
					    $("#btnGearDataHelical").attr("disabled",false);
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
				if(XMLHttpRequest.status==404)
				{
					toastr.error("Page not found");
					preloadfadeOut();
					$("#btnGearDataHelical").attr("disabled",false);
					
				}
				else if(XMLHttpRequest.status==500)
				{
					toastr.error("Internal server error");
					preloadfadeOut();
					$("#btnGearDataHelical").attr("disabled",false);
				}
				else
				{
					toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
					preloadfadeOut();
					$("#btnGearDataHelical").attr("disabled",false);
					//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
				}
				
			}
			})
	}
	
	
})
</script>
<script>
$("#btnShaftPinion").click(function(){
	
	$("#btnShaftPinion").attr("disabled",true);
	if($("#cmbParty").val()==='')
	{
		toastr.error("Please Choose Party Name");
		$("#cmbParty").focus();
		preloadfadeOut();
	    $("#btnShaftPinion").attr("disabled",false);
	}
	else if($("#hdShaftPinion").val()==='')
	{
		toastr.error("Please Add Data first");
		$("#txtShaftPinionProcessing").focus();
		preloadfadeOut();
	    $("#btnShaftPinion").attr("disabled",false);
	}
	
	else
	{
				$.ajax({
				url:'masters/save_party_specific_rates.php',
				type:'POST',
				data:{"auth_info":'<?=$_POST[auth_info]?>',"hdShaftPinion":$("#hdShaftPinion").val(),"PartyID":$("#cmbParty").val()},
				cache: false,
				success:function(response)
				{
					 
					var dataResponse=JSON.parse(response);
					if(dataResponse.error)
					{
						toastr.error(dataResponse.error_msg);
						preloadfadeOut();
						$("#btnShaftPinion").attr("disabled",false);
					}
					else
					{
						toastr.success(dataResponse.error_msg);
						preloadfadeOut();
					    $("#btnShaftPinion").attr("disabled",false);
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
				if(XMLHttpRequest.status==404)
				{
					toastr.error("Page not found");
					preloadfadeOut();
					$("#btnShaftPinion").attr("disabled",false);
					
				}
				else if(XMLHttpRequest.status==500)
				{
					toastr.error("Internal server error");
					preloadfadeOut();
					$("#btnShaftPinion").attr("disabled",false);
				}
				else
				{
					toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
					preloadfadeOut();
					$("#btnShaftPinion").attr("disabled",false);
					//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
				}
				
			}
			})
	}
	
	
})
</script>
<script>
$("#btnShaftPHelical").click(function(){
	
	$("#btnShaftPHelical").attr("disabled",true);
	if($("#cmbParty").val()==='')
	{
		toastr.error("Please Choose Party Name");
		$("#cmbParty").focus();
		preloadfadeOut();
	    $("#btnShaftPHelical").attr("disabled",false);
	}
	else if($("#hdShaftPHelical").val()==='')
	{
		toastr.error("Please Add Data first");
		$("#txtShaftPProcessingHelical").focus();
		preloadfadeOut();
	    $("#btnShaftPHelical").attr("disabled",false);
	}
	
	else
	{
				$.ajax({
				url:'masters/save_party_specific_rates.php',
				type:'POST',
				data:{"auth_info":'<?=$_POST[auth_info]?>',"hdShaftPHelical":$("#hdShaftPHelical").val(),"PartyID":$("#cmbParty").val()},
				cache: false,
				success:function(response)
				{
					 
					var dataResponse=JSON.parse(response);
					if(dataResponse.error)
					{
						toastr.error(dataResponse.error_msg);
						preloadfadeOut();
						$("#btnShaftPHelical").attr("disabled",false);
					}
					else
					{
						toastr.success(dataResponse.error_msg);
						preloadfadeOut();
					    $("#btnShaftPHelical").attr("disabled",false);
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
				if(XMLHttpRequest.status==404)
				{
					toastr.error("Page not found");
					preloadfadeOut();
					$("#btnShaftPHelical").attr("disabled",false);
					
				}
				else if(XMLHttpRequest.status==500)
				{
					toastr.error("Internal server error");
					preloadfadeOut();
					$("#btnShaftPHelical").attr("disabled",false);
				}
				else
				{
					toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
					preloadfadeOut();
					$("#btnShaftPHelical").attr("disabled",false);
					//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
				}
				
			}
			})
	}
	
	
})
</script>

<script>
$("#btnChainGear").click(function(){
	
	$("#btnChainGear").attr("disabled",true);
	if($("#cmbParty").val()==='')
	{
		toastr.error("Please Choose Party Name");
		$("#cmbParty").focus();
		preloadfadeOut();
	    $("#btnChainGear").attr("disabled",false);
	}
	else if($("#hdChainGear").val()==='')
	{
		toastr.error("Please Add Data first");
		$("#txtChainGearPitch").focus();
		preloadfadeOut();
	    $("#btnChainGear").attr("disabled",false);
	}
	
	else
	{
				$.ajax({
				url:'masters/save_party_specific_rates.php',
				type:'POST',
				data:{"auth_info":'<?=$_POST[auth_info]?>',"hdChainGear":$("#hdChainGear").val(),"PartyID":$("#cmbParty").val()},
				cache: false,
				success:function(response)
				{
					 
					var dataResponse=JSON.parse(response);
					if(dataResponse.error)
					{
						toastr.error(dataResponse.error_msg);
						preloadfadeOut();
						$("#btnChainGear").attr("disabled",false);
					}
					else
					{
						toastr.success(dataResponse.error_msg);
						preloadfadeOut();
					    $("#btnChainGear").attr("disabled",false);
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
				if(XMLHttpRequest.status==404)
				{
					toastr.error("Page not found");
					preloadfadeOut();
					$("#btnChainGear").attr("disabled",false);
					
				}
				else if(XMLHttpRequest.status==500)
				{
					toastr.error("Internal server error");
					preloadfadeOut();
					$("#btnChainGear").attr("disabled",false);
				}
				else
				{
					toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
					preloadfadeOut();
					$("#btnChainGear").attr("disabled",false);
					//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
				}
				
			}
			})
	}
	
	
})
</script>

<script>
$("#btnBevelGear").click(function(){
	
	$("#btnBevelGear").attr("disabled",true);
	if($("#cmbParty").val()==='')
	{
		toastr.error("Please Choose Party Name");
		$("#cmbParty").focus();
		preloadfadeOut();
	    $("#btnBevelGear").attr("disabled",false);
	}
	else if($("#hdBevelGear").val()==='')
	{
		toastr.error("Please Add Data first");
		$("#txtBevelGearProcessing").focus();
		preloadfadeOut();
	    $("#btnBevelGear").attr("disabled",false);
	}
	
	else
	{
				$.ajax({
				url:'masters/save_party_specific_rates.php',
				type:'POST',
				data:{"auth_info":'<?=$_POST[auth_info]?>',"hdBevelGear":$("#hdBevelGear").val(),"PartyID":$("#cmbParty").val()},
				cache: false,
				success:function(response)
				{
					 
					var dataResponse=JSON.parse(response);
					if(dataResponse.error)
					{
						toastr.error(dataResponse.error_msg);
						preloadfadeOut();
						$("#btnBevelGear").attr("disabled",false);
					}
					else
					{
						toastr.success(dataResponse.error_msg);
						preloadfadeOut();
					    $("#btnBevelGear").attr("disabled",false);
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
				if(XMLHttpRequest.status==404)
				{
					toastr.error("Page not found");
					preloadfadeOut();
					$("#btnBevelGear").attr("disabled",false);
					
				}
				else if(XMLHttpRequest.status==500)
				{
					toastr.error("Internal server error");
					preloadfadeOut();
					$("#btnBevelGear").attr("disabled",false);
				}
				else
				{
					toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
					preloadfadeOut();
					$("#btnBevelGear").attr("disabled",false);
					//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
				}
				
			}
			})
	}
	
	
})
</script>

<script>
$("#btnSpecialItem").click(function(){
	
	$("#btnSpecialItem").attr("disabled",true);
	if($("#cmbParty").val()==='')
	{
		toastr.error("Please Choose Party Name");
		$("#cmbParty").focus();
		preloadfadeOut();
	    $("#btnSpecialItem").attr("disabled",false);
	}
	else if($("#hdSpecialItem").val()==='')
	{
		toastr.error("Please Add Data first");
		$("#txtSpecialItemName").focus();
		preloadfadeOut();
	    $("#btnSpecialItem").attr("disabled",false);
	}
	
	else
	{
				$.ajax({
				url:'masters/save_party_specific_rates.php',
				type:'POST',
				data:{"auth_info":'<?=$_POST[auth_info]?>',"hdSpecialItem":$("#hdSpecialItem").val(),"PartyID":$("#cmbParty").val()},
				cache: false,
				success:function(response)
				{
					 
					var dataResponse=JSON.parse(response);
					if(dataResponse.error)
					{
						toastr.error(dataResponse.error_msg);
						preloadfadeOut();
						$("#btnSpecialItem").attr("disabled",false);
					}
					else
					{
						toastr.success(dataResponse.error_msg);
						preloadfadeOut();
					    $("#btnSpecialItem").attr("disabled",false);
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
				if(XMLHttpRequest.status==404)
				{
					toastr.error("Page not found");
					preloadfadeOut();
					$("#btnSpecialItem").attr("disabled",false);
					
				}
				else if(XMLHttpRequest.status==500)
				{
					toastr.error("Internal server error");
					preloadfadeOut();
					$("#btnSpecialItem").attr("disabled",false);
				}
				else
				{
					toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
					preloadfadeOut();
					$("#btnSpecialItem").attr("disabled",false);
					//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
				}
				
			}
			})
	}
	
	
})
</script>