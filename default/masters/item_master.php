<?php
    session_start();
    require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
	if(empty($_POST['auth_info']))
	{
		$Err=true;
		$ErrMsg="Sorry, no direct access allowed";
	}
	else
	{
	 
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
	
	
	$SqlError=false;
	$Save=false;
	$result=mysqli_query ($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fFace , cFaceType , fDMValue , cType from gear_master order by iTeeth, cItemType desc");
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row=mysqli_fetch_array($result))
		{
			if ($GearData=="")
			{
				$GearData="$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[cItemType]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
			else
			{
				$GearData=$GearData."~Array~$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[cItemType]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
		}
	}
	
	$result=mysqli_query ($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fFace , cFaceType , fDMValue , cType from pinion_master order by iTeeth, cItemType desc");
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row=mysqli_fetch_array($result))
		{
			if ($PinionData=="")
			{
				$PinionData="$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[cItemType]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
			else
			{
				$PinionData=$PinionData."~Array~$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[cItemType]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
		}
	}
	
	$result=mysqli_query ($con2,"select concat(iTeeth, ' teeth ',cItemType ,'dia ',fDia ,' ',cDiaType , ' face ',fFace ,' ', cFaceType) as cName, iId, iTeeth,cItemType, fDia, cDiaType, fFace , cFaceType, fDMValue , cType from shaft_pinion_master order by iTeeth, cItemType desc");
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row=mysqli_fetch_array($result))
		{
			if ($ShaftPinionData=="")
			{
				$ShaftPinionData="$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[cItemType]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
			else
			{
				$ShaftPinionData=$ShaftPinionData."~Array~$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[cItemType]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
		}
	}
	
	$result=mysqli_query ($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from bevel_gear_master  order by iTeeth ");
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row=mysqli_fetch_array($result))
		{
			if ($BevelGearData=="")
			{
				$BevelGearData="$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
			else
			{
				$BevelGearData=$BevelGearData."~Array~$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
		}
	}
	
	$result=mysqli_query ($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from bevel_pinion_master  order by iTeeth");
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row=mysqli_fetch_array($result))
		{
			if ($BevelPinionData=="")
			{
				$BevelPinionData="$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
			else
			{
				$BevelPinionData=$BevelPinionData."~Array~$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
		}
	}
	
	$result=mysqli_query ($con2,"select concat(iTeeth, ' teeth chain wheel dia ',fDia , ' ',cDiaType , ' pitch ', fPitch ,' ', cPitchType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fPitch , cPitchType  from chain_gear_master order by iTeeth, cItemType");
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row=mysqli_fetch_array($result))
		{
			if ($ChainGearData=="")
			{
				$ChainGearData="$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[cItemType]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fPitch]~ArrayItem~$row[cPitchType]";
			}
			else
			{
				$ChainGearData=$ChainGearData."~Array~$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[cItemType]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fPitch]~ArrayItem~$row[cPitchType]";
			}
		}
	}
	
	$result=mysqli_query ($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from worm_gear_master  order by iTeeth ");
  	if ($result && mysqli_num_rows($result)>0)
	{	
		while ($row=mysqli_fetch_array($result))
		{
			if ($WormGearData=="")
			{
				$WormGearData="$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
			else
			{
				$WormGearData=$WormGearData."~Array~$row[iId]~ArrayItem~$row[cName]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]";
			}
		}
	}
	
  	// if (isset($_POST['btnGearSave']))
	// {
		// $Save=true;
		// $ArrayData=explode("~Array~",$_POST['hdGearData']);	
		// for ($i=0;$i<sizeof($ArrayData);$i++)
		// {
			// $ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
			// $GearId=$ArrayItem[0];
			// $GearTeeth=$ArrayItem[2];
			// $GearType=$ArrayItem[3];
			// $GearDia=$ArrayItem[4];
			// $GearDiaMeasure=$ArrayItem[5];
			// $GearFace=$ArrayItem[6];
			// $GearFaceType=$ArrayItem[7];
			// $GearDMValue=$ArrayItem[8];
			// $GearDMType=$ArrayItem[9];
			
			// $result=mysqli_query ($con2,"UPDATE gear_master  set iTeeth= $GearTeeth , cItemType='$GearType' , fDia=$GearDia , cDiaType='$GearDiaMeasure' , fFace=$GearFace  , cFaceType='$GearFaceType' , fDMValue=$GearDMValue , cType='$GearDMType' where iId=$GearId");
			// if (! $result)
			// {
				// $SqlError=true;
			// }			
		// }
	// }
	
	// if (isset($_POST['btnPinionSave']))
	// {
		// $Save=true;
		// $ArrayData=explode("~Array~",$_POST['hdPinionData']);	
		// for ($i=0;$i<sizeof($ArrayData);$i++)
		// {
			// $ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
			// $PinionId=$ArrayItem[0];
			// $PinionTeeth=$ArrayItem[2];
			// $PinionType=$ArrayItem[3];
			// $PinionDia=$ArrayItem[4];
			// $PinionDiaMeasure=$ArrayItem[5];
			// $PinionFace=$ArrayItem[6];
			// $PinionFaceType=$ArrayItem[7];
			// $PinionDMValue=$ArrayItem[8];
			// $PinionDMType=$ArrayItem[9];
			// $result=mysqli_query ($con2,"UPDATE pinion_master  set iTeeth= $PinionTeeth , cItemType='$PinionType' , fDia=$PinionDia , cDiaType='$PinionDiaMeasure' , fFace=$PinionFace  , cFaceType='$PinionFaceType' , fDMValue=$PinionDMValue , cType='$PinionDMType' where iId=$PinionId");
			// if (! $result)
			// {
				// $SqlError=true;
			// }			
		// }
	// }

	// if (isset($_POST['btnShaftPinionSave']))
	// {
		// $Save=true;
		// $ArrayData=explode("~Array~",$_POST['hdShaftPinionData']);	
		// for ($i=0;$i<sizeof($ArrayData);$i++)
		// {
			// $ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
			// $ShaftPinionId=$ArrayItem[0];
			// $ShaftPinionTeeth=$ArrayItem[2];
			// $ShaftPinionType=$ArrayItem[3];
			// $ShaftPinionDia=$ArrayItem[4];
			// $ShaftPinionDiaMeasure=$ArrayItem[5];
			// $ShaftPinionFace=$ArrayItem[6];
			// $ShaftPinionFaceType=$ArrayItem[7];
			// $ShaftPinionDMValue=$ArrayItem[8];
			// $ShaftPinionDMType=$ArrayItem[9];
			// $result=mysqli_query ($con2,"UPDATE shaft_pinion_master  set iTeeth= $ShaftPinionTeeth , cItemType='$ShaftPinionType' , fDia=$ShaftPinionDia , cDiaType='$ShaftPinionDiaMeasure' , fFace=$ShaftPinionFace  , cFaceType='$ShaftPinionFaceType' , fDMValue=$ShaftPinionDMValue , cType='$ShaftPinionDMType' where iId=$ShaftPinionId");
			// if (! $result)
			// {
				// $SqlError=true;
			// }			
		// }
	// }

	// if (isset($_POST['btnBevelGearSave']))
	// {	
		// $Save=true;
		// $ArrayData=explode("~Array~",$_POST['hdBevelGearData']);	
		// for ($i=0;$i<sizeof($ArrayData);$i++)
		// {
			// $ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
			// $BevelGearId=$ArrayItem[0];
			// $BevelGearTeeth=$ArrayItem[2];
			// $BevelGearDia=$ArrayItem[3];
			// $BevelGearDiaMeasure=$ArrayItem[4];
			// $BevelGearDMValue=$ArrayItem[5];
			// $BevelGearDMType=$ArrayItem[6];
			// $result=mysqli_query ($con2,"UPDATE bevel_gear_master  set iTeeth= $BevelGearTeeth , fDia=$BevelGearDia , cDiaType='$BevelGearDiaMeasure' ,fDMValue=$BevelGearDMValue , cType='$BevelGearDMType' where iId=$BevelGearId");
			// if (! $result)
			// {
				// $SqlError=true;
			// }			
		// }	
	// }

	// if (isset($_POST['btnBevelPinionSave']))
	// {
		// $Save=true;
		// $ArrayData=explode("~Array~",$_POST['hdBevelPinionData']);	
		// for ($i=0;$i<sizeof($ArrayData);$i++)
		// {
			// $ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
			// $BevelPinionId=$ArrayItem[0];
			// $BevelPinionTeeth=$ArrayItem[2];
			// $BevelPinionDia=$ArrayItem[3];
			// $BevelPinionDiaMeasure=$ArrayItem[4];
			// $BevelPinionDMValue=$ArrayItem[5];
			// $BevelPinionDMType=$ArrayItem[6];
			// $result=mysqli_query ($con2,"UPDATE bevel_pinion_master  set iTeeth= $BevelPinionTeeth , fDia=$BevelPinionDia , cDiaType='$BevelPinionDiaMeasure' ,fDMValue=$BevelPinionDMValue , cType='$BevelPinionDMType' where iId=$BevelPinionId");
			// if (! $result)
			// {
				// $SqlError=true;
			// }			
		// }	
	// }

	// if (isset($_POST['btnChainGearSave']))
	// {
		// $Save=true;
		// $ArrayData=explode("~Array~",$_POST['hdChainGearData']);	
		// for ($i=0;$i<sizeof($ArrayData);$i++)
		// {
			// $ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
			// $ChainGearId=$ArrayItem[0];
			// $ChainGearTeeth=$ArrayItem[2];
			// $ChainGearType=$ArrayItem[3];
			// $ChainGearDia=$ArrayItem[4];
			// $ChainGearDiaMeasure=$ArrayItem[5];
			// $ChainGearPitch=$ArrayItem[6];
			// $ChainGearPitchType=$ArrayItem[7];
			// $result=mysqli_query ($con2,"UPDATE chain_gear_master  set iTeeth= $ChainGearTeeth ,cItemType='$ChainGearType', fDia=$ChainGearDia , cDiaType='$ChainGearDiaMeasure' ,fPitch=$ChainGearPitch , cPitchType='$ChainGearPitchType' where iId=$ChainGearId");
			// if (! $result)
			// {
				// $SqlError=true;
			// }			
		// }	
	// }
	
	if (isset($_POST['btnWormGearSave']))
	{
		$Save=true;
		$ArrayData=explode("~Array~",$_POST['hdWormGearData']);	
		for ($i=0;$i<sizeof($ArrayData);$i++)
		{
			$ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
			$WormGearId=$ArrayItem[0];
			$WormGearTeeth=$ArrayItem[2];
			$WormGearDia=$ArrayItem[3];
			$WormGearDiaMeasure=$ArrayItem[4];
			$WormGearDMValue=$ArrayItem[5];
			$WormGearDMType=$ArrayItem[6];
			$result=mysqli_query ($con2,"UPDATE worm_gear_master  set iTeeth= $WormGearTeeth , fDia=$WormGearDia , cDiaType='$WormGearDiaMeasure' ,fDMValue=$WormGearDMValue , cType='$WormGearDMType' where iId=$WormGearId");
			if (! $result)
			{
				$SqlError=true;
			}			
		}	
	}
	
	if ($Save)
	{	
		if ($SqlError)
		{
			mysql_query('rollback');
		}
		else
		{
			mysqli_query ($con2,'commit');
			$host=$_SERVER['HTTP_HOST'];
			$uri=rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$extra="item_master.php";
			header("Location: http://$host$uri/$extra");
		}
	}
	}
	
			
	
?>

<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Masters</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Item Master</li>
			</ol>
		</div>

	</div>
</div>
</div>
<!-- end page title -->

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

<form name="frmRateMaster" id="frmRateMaster" method="POST">
  <input type="hidden" name="auth_info" id="auth_info" value="<?=$_POST['auth_info']?>">
	<?php
	if($Err==true)
	{
		die($ErrMsg);
	}
	?>
	    <div class="row">
		<div class="col-md-6">
		   <div class="form-group">
				<label for="example-text-input">Item</label>
				<select name="cmbItem" id="cmbItem" class="form-control" onChange="selDiv(this.id)">
				<option value=""><?=str_repeat("-",20) ?>Select Item<?=str_repeat("-",20) ?></option>	
					<?php
					
					$result=mysqli_query ($con2,"select * from item_master");
					if ($result && mysqli_num_rows($result)>0)
					{
						while ($row=mysqli_fetch_array($result))
						{
							if ($row['cItemCode']=="Gear")
								echo "<option value=\"$row[cItemCode]\" selected>$row[cItemName]</option>";
							else
								echo  "<option value=\"$row[cItemCode]\">$row[cItemName]</option>";
						}
					}
					?>
				</select>
			</div>
		   </div>
		</div>
		 <input type="hidden" name="hdGearData" id="hdGearData" value="<?=$GearData ?>">
	     <div class="row" id="dvGear">
	    
		 <table class="table table-bordered">
		 <tr>
		 <td>
		 <label for="example-text-input">Teeth :</label><br>
		  <input type="text" class="form-control" name="txtGearTeeth" id="txtGearTeeth" onKeyDown="OnlyInt1(this.id)" >
		 
		 <label for="example-text-input">Type :</label>
		 
		        <select name="cmbGearType" id="cmbGearType" class="form-control">
									<option value="Plain">Plain</option>
									<option value="Helical">Helical</option>
									<option value="Spur Hobb">Spur Hobb</option>
				</select>
		 </td>
		 <td>
		        <label for="example-text-input">Dia :</label>
				<input type="text" class="form-control" name="txtGearDia" id="txtGearDia" >
				<br>
				<select name="cmbGearDiaType" id="cmbGearDiaType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
				</select>
		 </td>
		 <td>
		        <label for="example-text-input">Face :</label>
				<input type="text" name="txtGearFace" id="txtGearFace" class="form-control">
				<br>
				<select name="cmbGearFaceType" id="cmbGearFaceType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
			    </select>
				
		 </td>
		 <td>
		        <label for="txtGearProcessing">DP/Module :</label>
				<input type="text" name="txtGearProcessing" id="txtGearProcessing" class="form-control">
				<br>
				<select name="cmbGearProcessingType" id="cmbGearProcessingType" class="form-control" >
									<option value="DP">DP</option>
									<option value="Module">Module</option>
				</select>
		 </td>
		 
		 </tr>
		 <tr>
		 <td colspan="4">
		   <button type="button" name="btnGearEdit" id="btnGearEdit" value="Modify" class="btn btn-primary waves-effect waves-light float-right" onclick="ModifyGear()">Modify</button>
		 </td>
		 </tr>
		  </table>
		   <div class="col-md-12" id="dvGearShow"></div>
		   
		   <div class="col-md-12" style="padding:20px">
		     <button type="button" class="btn btn-primary waves-effect waves-light float-right" name="btnGearSave" id="btnGearSave" >Save</button>
		   </div>
	</div>
	
	<div id="dvPinion" style="display:none;">
				<input type="hidden" name="hdPinionData" id="hdPinionData" value="<?php print $PinionData ?>">
				 <fieldset width="100%">
				 <legend class="legend">Pinion</legend>
					<table  class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
						<tr>
						    <td>
							<label for="txtPinionTeeth">Teeth :</label>
							<input type="text" name="txtPinionTeeth" id="txtPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" >
							<br>
							
							<select name="cmbPinionType" id="cmbPinionType" class="form-control">
									<option value="Plain">Plain</option>
									<option value="Helical">Helical</option>
									<option value="Spur Hobb">Spur Hobb</option>
							</select>
							</td>
							
							<td>
							    <label for="txtPinionDia">Dia :</label>
								<input type="text" name="txtPinionDia" id="txtPinionDia" class="form-control">	
                                 <br>								
								<select name="cmbPinionDiaType" id="cmbPinionDiaType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td>
							    <label for="txtPinionFace">Face :</label>
								<input type="text" name="txtPinionFace" id="txtPinionFace" class="form-control" >
                                 <br>								
								<select name="cmbPinionFaceType" id="cmbPinionFaceType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td>
							    <label for="txtPinionProcessing">DP/Module :</label>
								<input type="text" name="txtPinionProcessing" id="txtPinionProcessing" class="form-control">
                                <br>								
								<select name="cmbPinionProcessingType" id="cmbPinionProcessingType" class="form-control">
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							
						</tr>
						<tr>
							<td height="26" colspan="5">
							<input type="button" name="btnPinionEdit" id="btnPinionEdit" value="Modify" class="btn btn-primary waves-effect waves-light float-right" onclick="ModifyPinion(this.id)">
							</td>
						</tr>
					</table>
					<div id="dvPinionShow"></div>
					<div height="25"></div>
					<div style="padding:20px"> <button type="button" name="btnPinionSave" id="btnPinionSave"  class="btn btn-primary waves-effect waves-light float-right">Save</button></div>					
				 </fieldset>
				</div>
	
	            <div id="dvShaftPinion" style="display:none;">
				<input type="hidden" name="hdShaftPinionData" id="hdShaftPinionData" value="<?php print $ShaftPinionData ?>">
				 <fieldset width="100%">
				 <legend class="legend">Shaft Pinion</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
						<tr>
						    <td> 
						        <label for="txtShaftPinionTeeth">Teeth :</label>
							    <input type="text" name="txtShaftPinionTeeth" id="txtShaftPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" >
							    <br>
								<select name="cmbShaftPinionType" id="cmbShaftPinionType" class="form-control">
									<option value="Plain">Plain</option>
									<option value="Helical">Helical</option>
									<option value="Spur Hobb">Spur Hobb</option>
								</select>
							</td>
							
							<td>
							    <label for="txtShaftPinionDia">Dia :</label>
								<input type="text" name="txtShaftPinionDia" id="txtShaftPinionDia" class="form-control">
                                <br>								
								<select name="cmbShaftPinionDiaType" id="cmbShaftPinionDiaType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td>
							    <label for="txtShaftPinionFace">Face :</label>
								<input type="text" name="txtShaftPinionFace" id="txtShaftPinionFace" class="form-control">											
								<br>
								<select name="cmbShaftPinionFaceType" id="cmbShaftPinionFaceType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td>
							    <label for="txtShaftPinionProcessing">DP/Module :</label>
								<input type="text" name="txtShaftPinionProcessing" id="txtShaftPinionProcessing" class="form-control">											
								<br>
								<select name="cmbShaftPinionProcessingType" id="cmbShaftPinionProcessingType" class="form-control">
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							
						</tr>
						<tr>
							<td height="26" colspan="5">
							  <input type="button" name="btnShaftPinionEdit" id="btnShaftPinionEdit" value="Modify" class="btn btn-primary waves-effect waves-light float-right" onclick="ModifyShaftPinion()">
							</td>
						</tr>
					</table>
					<div id="dvShaftPinionShow"></div>
					
					<div style="padding:20px"><button type="button" name="btnShaftPinionSave" id="btnShaftPinionSave"  class="btn btn-primary waves-effect waves-light float-right">Save</button></div>					
					</fieldset>
				</div>
				
				<div id="dvBevelGear" style="display:none;">
				<input type="hidden" name="hdBevelGearData" id="hdBevelGearData" value="<?=$BevelGearData ?>">
				 <fieldset width="100%">
				 <legend class="legend">Bevel Gear</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
						<tr>
							<td> 
							    <label for="txtBevelGearTeeth">Teeth :</label>
							    <input type="text" name="txtBevelGearTeeth" id="txtBevelGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" >
								
								
							</td>
							<td>
							    <label for="txtBevelGearDia">Dia :</label>
								<input type="text" name="txtBevelGearDia" id="txtBevelGearDia" class="form-control">
                                <br>								
								<select name="cmbBevelGearDiaType" id="cmbBevelGearDiaType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td>
							    <label for="txtBevelGearProcessing">DP/Module :</label>
							    <input type="text" name="txtBevelGearProcessing" id="txtBevelGearProcessing" class="form-control">											
								<br>
								<select name="cmbBevelGearProcessingType" id="cmbBevelGearProcessingType" class="form-control">
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							
						</tr>
						<tr>
							<td height="26" colspan="3">
							   <input type="button" name="btnBevelGearEdit" id="btnBevelGearEdit" value="Modify" class="btn btn-primary waves-effect waves-light float-right" onclick="ModifyBevelGear()">
							</td>
						</tr>
					</table>
					<div id="dvBevelGearShow"></div>
					<div height="25"></div>
					<div style="padding:20px"> <button type="button" name="btnBevelGearSave" id="btnBevelGearSave"  class="btn btn-primary waves-effect waves-light float-right">Save</button></div>					
					
					</fieldset>
				</div>
				
				<div id="dvBevelPinion" style="display:none;">
				<input type="hidden" name="hdBevelPinionData" id="hdBevelPinionData" value="<?php print $BevelPinionData ?>">
				 <fieldset width="100%">
				 <legend class="legend">Bevel Pinion</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
						<tr>
							<td>
                                <label for="txtBevelPinionTeeth">Teeth :</label>
                                <input type="text" name="txtBevelPinionTeeth" id="txtBevelPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" >
							</td>
							<td>
							    <label for="txtBevelPinionDia">Dia :</label>
							    <input type="text" name="txtBevelPinionDia" id="txtBevelPinionDia" class="form-control">													
								<br>
								<select name="cmbBevelPinionDiaType" id="cmbBevelPinionDiaType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td>
							    <label for="txtBevelPinionProcessing">DP/Module :</label>
							    <input type="text" name="txtBevelPinionProcessing" id="txtBevelPinionProcessing" class="form-control">											
								 <br>
								<select name="cmbBevelPinionProcessingType" id="cmbBevelPinionProcessingType" class="form-control">
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							
						</tr>
						<tr>
							<td height="26" colspan="3">
							   <input type="button" name="btnBevelPinionEdit" id="btnBevelPinionEdit" value="Modify" class="btn btn-primary waves-effect waves-light float-right" onclick="ModifyBevelPinion()">
							</td>
						</tr>
					</table>
					<div id="dvBevelPinionShow"></div>
					<div height="25"></div>
					<div style="padding:20px"><button type="button" name="btnBevelPinionSave" id="btnBevelPinionSave"  class="btn btn-primary waves-effect waves-light float-right">Save</button></div>					
				 
					</fieldset>
				</div>
				
				<div id="dvChainGear" style="display:none;">
				<input type="hidden" name="hdChainGearData" id="hdChainGearData" value="<?=$ChainGearData ?>">
				 <fieldset width="600">
				 <legend class="legend">Chain Wheel</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
						<tr>
						    <td>
						     <label for="txtChainGearTeeth">Teeth :</label>
							 <input type="text" name="txtChainGearTeeth" id="txtChainGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" >
							 <br>
							 <label for="cmbChainGearType">Type:</label>
							 <select name="cmbChainGearType" id="cmbChainGearType" class="form-control">
									<option value="Single">Single</option>
									<option value="Duplex">Duplex</option>
									<option value="Triplex">Triplex</option>
									<option value="Fourplex">Fourplex</option>
							</select>
							</td>
							
							<td>
							    <label for="txtChainGearDia">Dia :</label>
							    <input type="text" name="txtChainGearDia" id="txtChainGearDia" class="form-control">													
								<br>
								<select name="cmbChainGearDiaType" id="cmbChainGearDiaType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td>
							    <label for="txtChainGearPitch">Pitch :</label>
							    <input type="text" name="txtChainGearPitch" id="txtChainGearPitch" class="form-control">											
								<br>
								<select name="cmbChainGearPitchType" id="cmbChainGearPitchType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							
								
							
						</tr>
						<tr>
							<td height="26" colspan="3">
							   <input type="button" name="btnChainGearEdit" id="btnChainGearEdit" value="Modify" class="btn btn-primary waves-effect waves-light float-right" onclick="ModifyChainGear()">
							</td>
						</tr>
					</table>
					<div id="dvChainGearShow"></div>
					<div height="25"></div>
					<div style="padding:20px"> <button type="button" name="btnChainGearSave" id="btnChainGearSave"  class="btn btn-primary waves-effect waves-light float-right">Save</button></div>					
				 
				
				</fieldset>
				</div>
				<div id="dvWormGear" style="display:none;">
				<input type="hidden" name="hdWormGearData" id="hdWormGearData" value="<?=$WormGearData ?>">
				 <fieldset width="100%">
				 <legend class="legend">Worm Gear</legend>
					<table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable">
						<tr class="tableheadingleft">
							<td>
                                <label for="txtWormGearTeeth">Teeth :</label>
							    <input type="text" name="txtWormGearTeeth" id="txtWormGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" >
							</td>
							<td>
							    <label for="txtWormGearDia">Dia :</label>
							    <input type="text" name="txtWormGearDia" id="txtWormGearDia" class="form-control">													
								<br>
								<select name="cmbWormGearDiaType" id="cmbWormGearDiaType" class="form-control">
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td>
							    <label for="txtWormGearProcessing">DP/Module :</label>
							    <input type="text" name="txtWormGearProcessing" id="txtWormGearProcessing" class="form-control">											
								<br>
								<select name="cmbWormGearProcessingType" id="cmbWormGearProcessingType" class="form-control">
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							
								
							
						</tr>
						<tr>
							<td height="26" colspan="3">
							   <input type="button" name="btnWormGearEdit" id="btnWormGearEdit" value="Modify" class="btn btn-primary waves-effect waves-light float-right" onclick="ModifyWormGear()">
							</td>
						</tr>
					</table>
					<div id="dvWormGearShow"></div>
					<div height="25"></div>
					<div style="padding:20px"> <button type="button" name="btnWormGearSave" id="btnWormGearSave"  class="btn btn-primary waves-effect waves-light float-right">Save</button></div>					
					
					</fieldset>
				</div>
	
	
	
	</form>
	
		</div>
	</div>
</div>


</div>

<script type="text/javascript" language="javascript" src="../js/typeaheadcombo.js"></script>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script language="javascript" type="text/javascript" src="../js/item_master.js"></script>
<script type="text/javascript" language="javascript" src="../js/string.js"></script>

<script>
$("#btnGearSave").click(function(){
	preloadfadeIn();
	$("#btnGearSave").attr("disabled",true);
	$.ajax({
		url:'masters/save_item_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"txtgear":$("#hdGearData").val()},
		cache: false,
		success:function(response)
		{
			var dataResponse=JSON.parse(response);
			if(dataResponse.error)
			{
				toastr.error(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnGearSave").attr("disabled",false);
			}
			else
			{
				toastr.success(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnGearSave").attr("disabled",false);
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			toastr.error("Page not found");
			preloadfadeOut();
			$("#btnGearSave").attr("disabled",false);
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			$("#btnGearSave").attr("disabled",false);
		}
		else
		{
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			preloadfadeOut();
			$("#btnGearSave").attr("disabled",false);
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    }
	})
	
})
</script>
<script>
$("#btnPinionSave").click(function(){
	preloadfadeIn();
	$("#btnPinionSave").attr("disabled",true);
	$.ajax({
		url:'masters/save_item_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"txtpinion":$("#hdPinionData").val()},
		success:function(response)
		{
			var dataResponse=JSON.parse(response);
			if(dataResponse.error)
			{
				toastr.error(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnPinionSave").attr("disabled",false);
			}
			else
			{
				toastr.success(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnPinionSave").attr("disabled",false);
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			toastr.error("Page not found");
			preloadfadeOut();
	        $("#btnPinionSave").attr("disabled",false);
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
	        $("#btnPinionSave").attr("disabled",false);
		}
		else
		{
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			preloadfadeOut();
	        $("#btnPinionSave").attr("disabled",false);
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    }
	})
	
})

</script>
<script>
$("#btnShaftPinionSave").click(function(){
	preloadfadeIn();
	$("#btnShaftPinionSave").attr("disabled",true);
	$.ajax({
		url:'masters/save_item_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"txtshaftpinion":$("#hdShaftPinionData").val()},
		success:function(response)
		{
			var dataResponse=JSON.parse(response);
			if(dataResponse.error)
			{
				toastr.error(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnShaftPinionSave").attr("disabled",false);
			}
			else
			{
				toastr.success(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnShaftPinionSave").attr("disabled",false);
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			toastr.error("Page not found");
			preloadfadeOut();
	        $("#btnShaftPinionSave").attr("disabled",false);
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
	        $("#btnShaftPinionSave").attr("disabled",false);
		}
		else
		{
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			preloadfadeOut();
	        $("#btnShaftPinionSave").attr("disabled",false);
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    }
	})
	
})
</script>
<script>
$("#btnBevelGearSave").click(function(){
	preloadfadeIn();
	$("#btnBevelGearSave").attr("disabled",true);
	$.ajax({
		url:'masters/save_item_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"txtBevelGear":$("#hdBevelGearData").val()},
		success:function(response)
		{
			var dataResponse=JSON.parse(response);
			if(dataResponse.error)
			{
				toastr.error(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnBevelGearSave").attr("disabled",false);
			}
			else
			{
				toastr.success(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnBevelGearSave").attr("disabled",false);
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			toastr.error("Page not found");
			preloadfadeOut();
			$("#btnBevelGearSave").attr("disabled",false);
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			$("#btnBevelGearSave").attr("disabled",false);
		}
		else
		{
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			preloadfadeOut();
			$("#btnBevelGearSave").attr("disabled",false);
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    }
	})
	
})
</script>
<script>
$("#btnBevelPinionSave").click(function(){
	preloadfadeIn();
	$("#btnBevelPinionSave").attr("disabled",true);
	$.ajax({
		url:'masters/save_item_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"txtBevelPinion":$("#hdBevelPinionData").val()},
		success:function(response)
		{
			var dataResponse=JSON.parse(response);
			if(dataResponse.error)
			{
				toastr.error(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnBevelPinionSave").attr("disabled",false);
			}
			else
			{
				toastr.success(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnBevelPinionSave").attr("disabled",false);
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			toastr.error("Page not found");
			preloadfadeOut();
			 $("#btnBevelPinionSave").attr("disabled",false);
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			 $("#btnBevelPinionSave").attr("disabled",false);
		}
		else
		{
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			 preloadfadeOut();
			 $("#btnBevelPinionSave").attr("disabled",false);
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    }
	})
	
})
</script>
<script>
$("#btnChainGearSave").click(function(){
	 preloadfadeIn();
	 $("#btnChainGearSave").attr("disabled",true);
	$.ajax({
		url:'masters/save_item_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"txtchaingear":$("#hdChainGearData").val()},
		success:function(response)
		{
			var dataResponse=JSON.parse(response);
			if(dataResponse.error)
			{
				toastr.error(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnChainGearSave").attr("disabled",false);
			}
			else
			{
				toastr.success(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnChainGearSave").attr("disabled",false);
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			toastr.error("Page not found");
			preloadfadeOut();
			$("#btnChainGearSave").attr("disabled",false);
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			$("#btnChainGearSave").attr("disabled",false);
		}
		else
		{
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			preloadfadeOut();
			$("#btnChainGearSave").attr("disabled",false);
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    }
	})
	
})
</script>
<script>
$("#btnWormGearSave").click(function(){
	preloadfadeIn();
	$("#btnWormGearSave").attr("disabled",true);
	$.ajax({
		url:'masters/save_item_master.php',
		type:'POST',
		data:{"auth_info":$("#auth_info").val(),"txtWormGear":$("#hdWormGearData").val()},
		success:function(response)
		{
			var dataResponse=JSON.parse(response);
			if(dataResponse.error)
			{
				toastr.error(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnWormGearSave").attr("disabled",false);
			}
			else
			{
				toastr.success(dataResponse.error_msg);
				preloadfadeOut();
			    $("#btnWormGearSave").attr("disabled",false);
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			toastr.error("Page not found");
			preloadfadeOut();
			$("#btnWormGearSave").attr("disabled",false);
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			$("#btnWormGearSave").attr("disabled",false);
		}
		else
		{
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			preloadfadeOut();
			$("#btnWormGearSave").attr("disabled",false);
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    }
	})
	
})
						
</script>
 