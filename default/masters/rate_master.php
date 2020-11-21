<?php
	require_once("../../config.php");
	$PageMode="New";
	$Err=false;
	$ErrMsg="";
	$GearData="";
	$GearDataHelical="";
	$ShaftPinionData="";
	$ShaftPDataHelical="";
	$ChainGearData="";
	$BevelGearData="";
	
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="sorrry no direct access allowed";
	}
	else
	{
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
		function GearPlainShow($con2)
	{
		$GearData="";
		$Gearqry=mysqli_query($con2,"select * from gear_rate_master where cItemType='Plain' order by cType , fDMValue, fFaceFrom , fFaceTo  ");
		if ($Gearqry && mysqli_num_rows($Gearqry)>0)
		{
			while ($Gearrow=mysqli_fetch_array($Gearqry))
			{
				if ($GearData=="")	
				{
					$GearData="$Gearrow[iId]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]~ArrayItem~$Gearrow[fFaceFrom]~ArrayItem~$Gearrow[fFaceTo]~ArrayItem~$Gearrow[cFaceType]~ArrayItem~$Gearrow[fRate]~ArrayItem~Edit";
				}
				else
				{
					$GearData=$GearData. "~Array~$Gearrow[iId]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]~ArrayItem~$Gearrow[fFaceFrom]~ArrayItem~$Gearrow[fFaceTo]~ArrayItem~$Gearrow[cFaceType]~ArrayItem~$Gearrow[fRate]~ArrayItem~Edit";
				}
			}
		}
		return  $GearData;
	}

	function GearHelicalShow($con2)
	{
		$GearDataHelical="";
		$Gearqry=mysqli_query($con2,"select * from gear_rate_master where cItemType='Helical' order by cType , fDMValue, fFaceFrom , fFaceTo ");
		if ($Gearqry && mysqli_num_rows($Gearqry)>0)
		{
			while ($Gearrow=mysqli_fetch_array($Gearqry))
			{
				if ($GearDataHelical=="")	
				{
					$GearDataHelical="$Gearrow[iId]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]~ArrayItem~$Gearrow[fFaceFrom]~ArrayItem~$Gearrow[fFaceTo]~ArrayItem~$Gearrow[cFaceType]~ArrayItem~$Gearrow[fRate]~ArrayItem~Edit";
				}
				else
				{
					$GearDataHelical=$GearDataHelical. "~Array~$Gearrow[iId]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]~ArrayItem~$Gearrow[fFaceFrom]~ArrayItem~$Gearrow[fFaceTo]~ArrayItem~$Gearrow[cFaceType]~ArrayItem~$Gearrow[fRate]~ArrayItem~Edit";
				}
			}
		}
		return  $GearDataHelical;
	}

	function ShaftPinionShow($con2)
	{
		$ShaftPinionData="";
		$ShaftQry=mysqli_query ($con2,"select * from shaft_pinion_rate_master where cItemType='Plain' order by cType , fDMValue");
		if ($ShaftQry && mysqli_num_rows($ShaftQry)>0)
		{
			while ($ShaftRow=mysqli_fetch_array($ShaftQry))
			{
				if ($ShaftPinionData=="")
				{
					$ShaftPinionData="$ShaftRow[iId]~ArrayItem~$ShaftRow[fDMValue]~ArrayItem~$ShaftRow[cType]~ArrayItem~$ShaftRow[fFaceFrom]~ArrayItem~$ShaftRow[fFaceTo]~ArrayItem~$ShaftRow[cFaceType]~ArrayItem~$ShaftRow[iTeeth]~ArrayItem~$ShaftRow[fRate]~ArrayItem~Edit";
				}
				else
				{
					$ShaftPinionData=$ShaftPinionData."~Array~$ShaftRow[iId]~ArrayItem~$ShaftRow[fDMValue]~ArrayItem~$ShaftRow[cType]~ArrayItem~$ShaftRow[fFaceFrom]~ArrayItem~$ShaftRow[fFaceTo]~ArrayItem~$ShaftRow[cFaceType]~ArrayItem~$ShaftRow[iTeeth]~ArrayItem~$ShaftRow[fRate]~ArrayItem~Edit";
				}
			}
		}
		return  $ShaftPinionData;
	}

   function ShaftPHelicalShow($con2)
   {
	   $ShaftPDataHelical="";
		$ShaftPQry=mysqli_query ($con2,"select * from shaft_pinion_rate_master where cItemType='Helical' order by cType , fDMValue");
		if ($ShaftPQry && mysqli_num_rows($ShaftPQry)>0)
		{
			while ($ShaftPRow=mysqli_fetch_array($ShaftPQry))
			{
				if ($ShaftPDataHelical=="")
				{
					$ShaftPDataHelical="$ShaftPRow[iId]~ArrayItem~$ShaftPRow[fDMValue]~ArrayItem~$ShaftPRow[cType]~ArrayItem~$ShaftPRow[fFaceFrom]~ArrayItem~$ShaftPRow[fFaceTo]~ArrayItem~$ShaftPRow[cFaceType]~ArrayItem~$ShaftPRow[fRate]";
				}
				else
				{
					$ShaftPDataHelical=$ShaftPDataHelical."~Array~$ShaftPRow[iId]~ArrayItem~$ShaftPRow[fDMValue]~ArrayItem~$ShaftPRow[cType]~ArrayItem~$ShaftPRow[fFaceFrom]~ArrayItem~$ShaftPRow[fFaceTo]~ArrayItem~$ShaftPRow[cFaceType]~ArrayItem~$ShaftPRow[fRate]";
				}
			}
		}
		return $ShaftPDataHelical;
   }
   
	function ChainGearShow($con2)
	{
		$ChainGearData="";
		$ChainGearQry=mysqli_query ($con2,"select * from chain_gear_rate_master order by cPitchType , fPitchValue");
		if ($ChainGearQry && mysqli_num_rows($ChainGearQry)>0)
		{
			while ($ChainGearRow=mysqli_fetch_array($ChainGearQry))
			{
				if ($ChainGearData=="")
				{
					$ChainGearData="$ChainGearRow[iId]~ArrayItem~$ChainGearRow[fPitchValue]~ArrayItem~$ChainGearRow[cPitchType]~ArrayItem~$ChainGearRow[fRate]~ArrayItem~Edit";
				}
				else
				{
					$ChainGearData=$ChainGearData."~Array~$ChainGearRow[iId]~ArrayItem~$ChainGearRow[fPitchValue]~ArrayItem~$ChainGearRow[cPitchType]~ArrayItem~$ChainGearRow[fRate]~ArrayItem~Edit";
				}

			}
		}
		return  $ChainGearData;
	}

	function BevelGearShow($con2)
	{
		$BevelGearData="";
		$BevelGearQry=mysqli_query($con2,"select * from bevel_gear_rate_master order by cType , fDMValue ");
		if ($BevelGearQry && mysqli_num_rows($BevelGearQry)>0)
		{
			while ($BevelGearRow=mysqli_fetch_array($BevelGearQry))
			{
				if ($BevelGearData=="")
				{
					$BevelGearData="$BevelGearRow[iId]~ArrayItem~$BevelGearRow[fDMValue]~ArrayItem~$BevelGearRow[cType]~ArrayItem~$BevelGearRow[iTeeth]~ArrayItem~$BevelGearRow[fRate]~ArrayItem~Edit";
				}
				else
				{
				   $BevelGearData=$BevelGearData."~Array~$BevelGearRow[iId]~ArrayItem~$BevelGearRow[fDMValue]~ArrayItem~$BevelGearRow[cType]~ArrayItem~$BevelGearRow[iTeeth]~ArrayItem~$BevelGearRow[fRate]~ArrayItem~Edit";
				}
			}
		}
		return  $BevelGearData;
	}
	}
	
	

	

	

	
	
		$GearData=GearPlainShow($con2);
	
		$GearDataHelical=GearHelicalShow($con2);
	
		$ShaftPinionData=ShaftPinionShow($con2);
	


	
		$ShaftPDataHelical=ShaftPHelicalShow($con2);
	

	
		$ChainGearData=ChainGearShow($con2);
	

	// if (isset($_POST['btnBevelGear']))
	// {
		// $BevelGearInfo=explode("~Array~",$_POST['hdBevelGear']);
		// for ($i=0;$i<sizeof($BevelGearInfo);$i++) 
		// {
			// $DataBevelGear=explode('~ArrayItem~',$BevelGearInfo[$i]);
			// if ($DataBevelGear[5]=="New")
			// {
				// $result=mysqli_query ($con2,"Insert into bevel_gear_rate_master (fDMValue, cType, iTeeth, fRate) values (\"".$DataBevelGear[1]."\",\"".$DataBevelGear[2]."\",\"".$DataBevelGear[3]."\",\"".$DataBevelGear[4]."\")");
				// if (!$result)
				// {
					// $SqlError=true;
				// }
			// }
			// else
			// {
				// $result=mysqli_query ($con2,"update bevel_gear_rate_master set fDMValue=\"".$DataBevelGear[1]."\", cType=\"".$DataBevelGear[2]."\", iTeeth=\"".$DataBevelGear[3]."\",fRate=\"".$DataBevelGear[4]."\" where iId=\"".$DataBevelGear[0]."\"")	 ;
				// if (! $result)
				// {
					// $SqlError=true;
				// }
			// }
		// }
		// $BevelGearData=BevelGearShow($con2);	
	// }
	// else
	// {
		$BevelGearData=BevelGearShow($con2);	
	//}


	// if ($SqlError)
	// {
		// mysqli_query($con2,'rollback');
		// $Err=true;
		// $ErrMsg[]="Error in Saving Records...";
	// }
	// else
	// {
		// mysqli_query($con2,'commit');
		// $Err=false;
		// $ErrMsg[]="Records successfully saved...";
	// }

?>

<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Masters</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Expeller Rates Master</li>
			</ol>
		</div>

	</div>
</div>
</div>
<div class="container">

<?php
if($Err==true)
{
	die($ErrMsg);
}
?>

	<div class="card">
		<div class="card-body">
		<div class="row">
		<Form name="frmRateMaster" method="POST">
		<input type="hidden" name="auth_info" id="auth_info" value="<?=$_POST['auth_info']?>">
		<div class="col-md-12">

				<fieldset>
					<legend class="legend">Plain Gears/Pinion Milling M/c Rates</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
						<tr>	  
							<td>
								<input type="hidden" name="hdGearRatePlain" id="hdGearRatePlain" value="<?=$GearData ?>">
								<label for="txtGearProcessingPlain">DP/Module :	</label>
								<input type="text" name="txtGearProcessingPlain" id="txtGearProcessingPlain" class="form-control">
								<br>
								<select name="cmbGearProcessingTypePlain" id="cmbGearProcessingTypePlain" class="form-control">								
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							<td>
							 <label for="example-text-input">Face From :</label>
								<input type="text" name="txtGearFaceFromPlain" id="txtGearFaceFromPlain" class="form-control">
								<br>
							 <label for="txtGearFaceToPlain">To :</label>
								<input type="text" name="txtGearFaceToPlain" id="txtGearFaceToPlain" class="form-control">
								<br>
								<select name="cmbGearFaceTypePlain" id="cmbGearFaceTypePlain" class="form-control">								
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>	
							<td>
							    <label for="txtGearRatePlain">Rate :</label>
								<input type="text" name="txtGearRatePlain" id="txtGearRatePlain" class="form-control" onblur="DecimalNum(this.id)">
							</td>
							
						</tr>
						<tr>
							
                       <td colspan="3"> <input type="button" name="btnGearAddPlain" id="btnGearAddPlain" value="Add" class="btn btn-primary waves-effect waves-light float-right"   onclick="AddGearRate()" onkeydown="AddGearRate()" /></td>
						</tr>
					</table>
					<div id="dvGearDataPlain" class="col-md-12" style="width:100%"></div>
				   <br>
				   <div align="center" style="width:100%"><button type="button" name="btnGearDataPlain" id="btnGearDataPlain"  class="btn btn-primary waves-effect waves-light float-right">Save & Update Rates</button></div>
				</fieldset>
			
		</div>
		<br><br>
		<div class="col-md-12">
		        <fieldset style="width:100%">
					<legend class="legend">Helical Gears/Pinion Milling M/c Rates</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
					<tr>
						    <td>
								<input type="hidden" name="hdGearRateHelical" id="hdGearRateHelical" value="<?=$GearDataHelical ?>">
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
								<br>
								<label for="txtGearFaceToHelical">To :</label>
								<input type="text" name="txtGearFaceToHelical" id="txtGearFaceToHelical" class="form-control">
								<br>
								<select name="cmbGearFaceTypeHelical" id="cmbGearFaceTypeHelical" class="form-control">								
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>	
								
							<td>
								<label for="txtGearRateHelical">Rate :</label>
								<input type="text" name="txtGearRateHelical" id="txtGearRateHelical" class="form-control" onblur="DecimalNum(this.id)">
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<input type="button" name="btnGearAddHelical" id="btnGearAddHelical" value="Add" class="btn btn-primary waves-effect waves-light float-right" onclick="AddGearHelicalRate()" onKeydown="AddGearHelicalRate()">
							</td>
						</tr>
					</table>
					<div id="dvGearDataHelical" style="width:100%"></div>
				   <br>
				   <div><button type="button" name="btnGearDataHelical" id="btnGearDataHelical"  class="btn btn-primary waves-effect waves-light float-right">Save & Update Rates</button></div>
				</fieldset>
			</div>
			<div class="col-md-12">
		        <fieldset style="width:100%;">
					<legend class="legend">Plain Shaft Pinion Rates</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
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
								<select name="cmbShaftPinionFaceType" id="cmbShaftPinionFaceType" class="form-control"><br>								
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
				   <div><button type="button" name="btnShaftPinion" id="btnShaftPinion"  class="btn btn-primary waves-effect waves-light float-right">Save & Update Rates</button></div>
				</fieldset>
			</div>
			<div class="col-md-12">
		   
				<fieldset style="width:100%;">
					<legend class="legend">Helical Shaft Pinion Rates</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
						<tr>	  				
							<td>
								<input type="hidden" name="hdShaftPHelical" id="hdShaftPHelical" value="<?=$ShaftPDataHelical ?>">
								
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
							    <label for="txtShaftPFaceToHelical">Rate :</label>
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
				   <div><button type="button" name="btnShaftPHelical" id="btnShaftPHelical" class="btn btn-primary waves-effect waves-light float-right">Save & Update Rates</button></div>
				</fieldset>
			
		</div>
		<div class="col-md-12">
		<fieldset style="width:100%;">
					<legend class="legend">Chain/Sprocket Gear Rates</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
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
								
								<input type="button" name="btnChainGearAdd" id="btnChainGearAdd" value="Add" class="btn btn-primary waves-effect waves-light float-right"   onclick="AddChainGearRate()" onKeydown="AddChainGearRate()">
							</td>
						</tr>
					</table>
					<div id="dvChainGearData" style="width:100%"></div>
				   <br>
				   <div><button type="button" name="btnChainGear" id="btnChainGear" class="btn btn-primary waves-effect waves-light float-right">Save & Update Rates</button></div>
				</fieldset>
			
		</div>
		<div class="col-md-12">
		
				<fieldset style="width:100%;">
					<legend class="legend">Bevel Gear/Pinion Rates</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
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
								
								<input type="button" name="btnBevelGearAdd" id="btnBevelGearAdd" value="Add" class="btn btn-primary waves-effect waves-light float-right"   onclick="AddBevelGearRate()" onKeydown="AddBevelGearRate()">
							</td>
						</tr>
					</table>
					<div id="dvBevelGearData" style="width:100%"></div>
				   <br>
				   <div><button type="button" name="btnBevelGear" id="btnBevelGear"  class="btn btn-primary waves-effect waves-light float-right">Save & Update Rates</button></div>
				</fieldset>
			</div>

</div>
</Form>
</div>
</div>
</div>
</div>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script language="javascript" type="text/javascript" src="../js/rate_master.js"></script>
<script>
$("#btnGearDataPlain").click(function(){
	preloadfadeIn();
	$("#btnGearDataPlain").attr("disabled",true);
	$.ajax({
		url:'masters/save_rate_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"hdGearRatePlain":$("#hdGearRatePlain").val()},
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
	
})
</script>
<script>
$("#btnGearDataHelical").click(function(){
	preloadfadeIn();
	$("#btnGearDataHelical").attr("disabled",true);
	$.ajax({
		url:'masters/save_rate_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"hdGearRateHelical":$("#hdGearRateHelical").val()},
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
	
})

</script>
<script>
$("#btnShaftPinion").click(function(){
	preloadfadeIn();
	$("#btnShaftPinion").attr("disabled",true);
	$.ajax({
		url:'masters/save_rate_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"hdShaftPinion":$("#hdShaftPinion").val()},
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
	
})
</script>
<script>
$("#btnShaftPHelical").click(function(){
	preloadfadeIn();
	$("#btnShaftPHelical").attr("disabled",true);
	$.ajax({
		url:'masters/save_rate_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"hdShaftPHelical":$("#hdShaftPHelical").val()},
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
	
})
</script>
<script>
$("#btnChainGear").click(function(){
	preloadfadeIn();
	$("#btnChainGear").attr("disabled",true);
	$.ajax({
		url:'masters/save_rate_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"hdChainGear":$("#hdChainGear").val()},
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
	
})
</script>
<script>
$("#btnBevelGear").click(function(){
	 preloadfadeIn();
	 $("#btnBevelGear").attr("disabled",true);
	$.ajax({
		url:'masters/save_rate_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"hdBevelGear":$("#hdBevelGear").val()},
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
	
})
</script>
