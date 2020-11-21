<?php
	session_start(); 
	require ('../general/authenticate.php');
	$_SESSION['curritem']='Rate Master';
	require ('../general/dbconnect.php');
	$PageMode="New";
	$ErrMsg=array();
	$Err=false;
	$SqlError=false;
	$GearData="";
	$GearDataHelical="";
	$ShaftPinionData="";
	$ShaftPDataHelical="";
	$SpecialItemData="";

	function GearPlainShow()
	{
		$Gearqry=mysql_query("select * from pp_gear_rate_master  order by cType , fDMValue, fDiaFrom , fDiaTo  ");
		if ($Gearqry && mysql_num_rows($Gearqry)>0)
		{
			while ($Gearrow=mysql_fetch_array($Gearqry))
			{
				if ($GearData=="")	
				{
					$GearData="$Gearrow[iId]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]~ArrayItem~$Gearrow[fDiaFrom]~ArrayItem~$Gearrow[fDiaTo]~ArrayItem~$Gearrow[cDiaType]~ArrayItem~$Gearrow[fRate]~ArrayItem~Edit";
				}
				else
				{
					$GearData=$GearData. "~Array~$Gearrow[iId]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]~ArrayItem~$Gearrow[fDiaFrom]~ArrayItem~$Gearrow[fDiaTo]~ArrayItem~$Gearrow[cDiaType]~ArrayItem~$Gearrow[fRate]~ArrayItem~Edit";
				}
			}
		}
	
		return  $GearData;
	}

	function GearHelicalShow()
	{
		$Gearqry=mysql_query("select * from pp_pinion_rate_master order by cType , fDMValue, iTeethFrom , iTeethTo ");
		if ($Gearqry && mysql_num_rows($Gearqry)>0)
		{
			while ($Gearrow=mysql_fetch_array($Gearqry))
			{
				if ($GearDataHelical=="")	
				{
					$GearDataHelical="$Gearrow[iId]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]~ArrayItem~$Gearrow[iTeethFrom]~ArrayItem~$Gearrow[iTeethTo]~ArrayItem~$Gearrow[fRate]~ArrayItem~Edit";
				}
				else
				{
					$GearDataHelical=$GearDataHelical. "~Array~$Gearrow[iId]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]~ArrayItem~$Gearrow[iTeethFrom]~ArrayItem~$Gearrow[iTeethTo]~ArrayItem~$Gearrow[fRate]~ArrayItem~Edit";
				}
			}
		}
		return  $GearDataHelical;
	}

	/*function ShaftPinionShow()
	{
		$ShaftQry=mysql_query ("select * from pp_shaft_pinion_rate_master where cItemType='Plain' order by cType , fDMValue");
		if ($ShaftQry && mysql_num_rows($ShaftQry)>0)
		{
			while ($ShaftRow=mysql_fetch_array($ShaftQry))
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

   function ShaftPHelicalShow()
   {
		$ShaftPQry=mysql_query ("select * from pp_shaft_pinion_rate_master where cItemType='Helical' order by cType , fDMValue");
		if ($ShaftPQry && mysql_num_rows($ShaftPQry)>0)
		{
			while ($ShaftPRow=mysql_fetch_array($ShaftPQry))
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
   }*/


	mysql_query ("begin");

	if (isset($_POST['btnGearDataPlain']))
	{
		if (trim($_POST['hdGearRatePlain'])=="")
		{
			print "Enter data...";
		}
		else
		{
			$GearInfo=explode("~Array~",$_POST['hdGearRatePlain']);
			for ($i=0;$i<sizeof($GearInfo);$i++)
			{
				$DataItemGear= explode("~ArrayItem~",$GearInfo[$i]);
				if ($DataItemGear[7]=="New")
				{
					$result=mysql_query ("insert into pp_gear_rate_master (fDMValue, cType, fDiaFrom,fDiaTo, cDiaType,fRate) values (\"".$DataItemGear[1]."\",\"".$DataItemGear[2]."\",\"".$DataItemGear[3]."\",\"".$DataItemGear[4]."\",\"".$DataItemGear[5]."\",\"".$DataItemGear[6]."\")");	
					if (!$result)
					{
					
						$SqlError=true;
						//	print "Error in Inserting records...";
					}
				}
				else
				{
					$result=mysql_query ("update pp_gear_rate_master set fDMValue=\"".$DataItemGear[1]."\", cType=\"".$DataItemGear[2]."\", fDiaFrom=\"".$DataItemGear[3]."\",fDiaTo=\"".$DataItemGear[4]."\", cDiaType=\"".$DataItemGear[5]."\",fRate=\"".$DataItemGear[6]."\"  where iId=\"".$DataItemGear[0]."\"");
					if (!$result)
					{
						$SqlError=true;
						//print "Error in updating records...";
					}
				}
			}
		}
		$GearData=GearPlainShow();
	}
	else
	{
		$GearData=GearPlainShow();
	}


	if (isset($_POST['btnGearDataHelical']))
	{
		if (trim($_POST['txtMinGearRateHelical'])=="" || trim($_POST['txtMinGearRateHelical'])==0)
		{
			print "Enter Min value...";
		}
		else if (trim($_POST['hdGearRateHelical'])=="")
		{
			print "Enter the Data...";	
		}
		else
		{
			$MinValue=trim($_POST['txtMinGearRateHelical']);
			
			$result=mysql_query ("UPDATE party_master set fMinValue='$MinValue'");
			if (!$result)
			{
				$SqlError=true;
			}
			$GearInfo=explode("~Array~",$_POST['hdGearRateHelical']);
			for ($i=0;$i<sizeof($GearInfo);$i++)
			{
				$DataItemGear= explode("~ArrayItem~",$GearInfo[$i]);
				if ($DataItemGear[6]=="New")
				{
					$result=mysql_query ("insert into pp_pinion_rate_master (fDMValue, cType, iTeethFrom,iTeethTo, fRate) values (\"".$DataItemGear[1]."\",\"".$DataItemGear[2]."\",\"".$DataItemGear[3]."\",\"".$DataItemGear[4]."\",\"".$DataItemGear[5]."\")");	
					if (!$result)
					{
						print mysql_error();
						$SqlError=true;
						//	print "Error in Inserting records...";
					}
				}
				else
				{
					$result=mysql_query ("update pp_pinion_rate_master set fDMValue=\"".$DataItemGear[1]."\", cType=\"".$DataItemGear[2]."\", iTeethFrom=\"".$DataItemGear[3]."\",iTeethTo=\"".$DataItemGear[4]."\",fRate=\"".$DataItemGear[5]."\"  where iId=\"".$DataItemGear[0]."\" ");
					if (!$result)
					{
						$SqlError=true;
						//print "Error in updating records...";
					}
				}
			}
		}
		$GearDataHelical=GearHelicalShow();
		$Minqry=mysql_query ("select fMinValue from party_master");
		if ($Minqry)
		{
			if (mysql_num_rows($Minqry)>0)
			{	
				$Minrow=mysql_fetch_array($Minqry);
				$MinValue=$Minrow['fMinValue'];
			}
			else
			{
				$MinValue=0;
			}
		}
		else
		{
			$MinValue=0;
		}
		
	}
	else
	{
		$GearDataHelical=GearHelicalShow();
		$Minqry=mysql_query ("select fMinValue from party_master");
		if ($Minqry)
		{
			if (mysql_num_rows($Minqry)>0)
			{	
				$Minrow=mysql_fetch_array($Minqry);
				$MinValue=$Minrow['fMinValue'];
			}
			else
			{
				$MinValue=0;
			}
		}
		else
		{
			$MinValue=0;
		}
	}

	/*if (isset($_POST['btnShaftPinion']))
	{
		$ShaftPinionInfo=explode("~Array~",$_POST['hdShaftPinion']);
		for ($i=0;$i<sizeof($ShaftPinionInfo);$i++) 
		{
			$DataShaftPinion=explode('~ArrayItem~',$ShaftPinionInfo[$i]);
			if ($DataShaftPinion[8]=="New")
			{
				$result=mysql_query ("Insert into pp_shaft_pinion_rate_master (cItemType, fDMValue, cType, fFaceFrom,fFaceTo, cFaceType,iTeeth,  fRate) values ('Plain',\"".$DataShaftPinion[1]."\",\"".$DataShaftPinion[2]."\",\"".$DataShaftPinion[3]."\",\"".$DataShaftPinion[4]."\",\"".$DataShaftPinion[5]."\",\"".$DataShaftPinion[6]."\",\"".$DataShaftPinion[7]."\")");
				if (!$result)
				{
					$SqlError=true;
					//print "Error in Inserting records...";
				}
			}
			else
			{
				$result=mysql_query ("update pp_shaft_pinion_rate_master set fRate=\"".$DataShaftPinion[7]."\" where iId=\"".$DataShaftPinion[0]."\"")	 ;
				if (! $result)
				{
					$SqlError=true;
					//print "Error in updating records...";
				}
			}
		}
		$ShaftPinionData=ShaftPinionShow();
	}
	else
	{
		$ShaftPinionData=ShaftPinionShow();
	}


	if (isset($_POST['btnShaftPHelical']))
	{
		$ShaftPHelicalInfo=explode("~Array~",$_POST['hdShaftPHelical']);
		
		for ($i=0;$i<sizeof($ShaftPHelicalInfo);$i++) 
		{
			$DataShaftPHelical=explode('~ArrayItem~',$ShaftPHelicalInfo[$i]);
			if ($DataShaftPHelical[7]=="New")
			{
				$result=mysql_query ("Insert into pp_shaft_pinion_rate_master (cItemType, fDMValue, cType, fFaceFrom,fFaceTo, cFaceType,iTeeth,  fRate) values ('Helical',\"".$DataShaftPHelical[1]."\",\"".$DataShaftPHelical[2]."\",\"".$DataShaftPHelical[3]."\",\"".$DataShaftPHelical[4]."\",\"".$DataShaftPHelical[5]."\",'0',\"".$DataShaftPHelical[6]."\")");
				if (!$result)
				{
					
					$SqlError=true;
				}
			}
			else
			{
				$result=mysql_query ("update pp_shaft_pinion_rate_master set fRate=\"".$DataShaftPHelical[6]."\" where iId=\"".$DataShaftPHelical[0]."\"")	 ;
				if (! $result)
				{
					$SqlError=true;
				}
			}
		}
		$ShaftPDataHelical=ShaftPHelicalShow();
	}
	else
	{
		$ShaftPDataHelical=ShaftPHelicalShow();
	}*/

	if ($SqlError)
	{
		
		mysql_query('rollback');
		$Err=true;
		$ErrMsg[]="Error in Saving Records...";
	}
	else
	{
		mysql_query('commit');
		$Err=false;
		$ErrMsg[]="Records successfully saved...";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Games and Toys</title>
<link href="../games.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../javascript/general.js"></script>
<script language="javascript" type="text/javascript" src="power_press_rate_master.js"></script>
</head>
<body onload ="Pageload()" onkeyup="ShiftUp()" onkeydown="EnterKeyHandle()">
<Form name="frmRateMaster" method="POST" action="">
	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="borderblue" colspan="2"><h5><a  id="group_heading">Power Press Rates Master</a></h5></td>
		</tr>
		<tr>
			<td height="20px" colspan="2">	
				<label id="ErrMsg" class='ErrorMsg'>
					<?php
					if($Err)
					{
						echo "<ul>";
						foreach($ErrMsg as $errmsg)
						{
							echo "<li>$errmsg</li>";
						}
						echo "</ul>";
					}
					?>
				</label>
			</td>
		</tr>
		<tr>
			<td height="20px" colspan="2">	
				<label id="lblErrMsg" class='ErrorMsg'>
				</label>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table cellpadding="0" cellspacing="0" width="100%" class="legend">
					<tr>
						<a name="Top">
						<td height="20"><a href="#Map1">Gear</a></td>
						<td><a href="#Map2">Pinion</a></td>
						</a>
					</tr>
				</table>	 
			</td>
		</tr>
		<tr>
			<td height="20px" colspan="2">	</td>
		</tr>
		<tr>
			<td width="70%" valign="top" style="padding-left:20px;">
				<a name="Map1"></a>
				<fieldset style="width:600px;">
					<legend class="legend">Gears</legend>
					
					<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext" >
						<tr>	  
							<td width="25%">
								<input type="hidden" name="hdGearRatePlain" id="hdGearRatePlain" value="<?php print $GearData ?>">
								DP/Module :	<br>
								<input type="text" name="txtGearProcessingPlain" id="txtGearProcessingPlain" size="7">
								<select name="cmbGearProcessingTypePlain" id="cmbGearProcessingTypePlain">								
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							<td width="15%">
								Dia From :</br>
								<input type="text" name="txtGearDiaFromPlain" id="txtGearDiaFromPlain" size="7">
							</td>	
							<td width="25%">
								To :<br>
								<input type="text" name="txtGearDiaToPlain" id="txtGearDiaToPlain" size="7">
								<select name="cmbGearDiaTypePlain" id="cmbGearDiaTypePlain">								
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>	
							<td width="15%">
								Rate :<br>
								<input type="text" name="txtGearRatePlain" id="txtGearRatePlain" size="7" onblur="DecimalNum(this.id)">
							</td>
							<td width="20%">
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<input type="button" name="btnGearAddPlain" id="btnGearAddPlain" value="Add" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddGearRate()" onKeydown="AddGearRate()">
							</td>
						</tr>
					</table>
					<div id="dvGearDataPlain" style="width:100%"></div>
				   <br>
				   <div align="center" style="width:100%"><input type="submit" name="btnGearDataPlain" id="btnGearDataPlain" value="Save & Update Rates" class="btncss" onClick="checkBlank(this.id)"></div>
				</fieldset>
			</td>
			<td  style="text-align:left; vertical-align:top; width:30%" valign="top">
				<label id="lblGearPlainMsg" class='ErrorMsg'></label>
			</td>
		</tr>
		<tr>
			<td width="70%" valign="top" style="padding-left:20px;">
				<a name="Map2"></a>
				<fieldset style="width:600px;">
					<legend class="legend">Pinion </legend>
					
					<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext">
						<tr>	  
							<td width="25%">
								<input type="hidden" name="hdGearRateHelical" id="hdGearRateHelical" value="<?php print $GearDataHelical ?>">
								DP/Module :	<br>
								<input type="text" name="txtGearProcessingHelical" id="txtGearProcessingHelical" size="7">
								<select name="cmbGearProcessingTypeHelical" id="cmbGearProcessingTypeHelical">								
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							<td width="15%">
								Teeth From :</br>
								<input type="text" name="txtGearTeethFromHelical" id="txtGearTeethFromHelical" size="7">
							</td>	
							<td width="25%">
								To :<br>
								<input type="text" name="txtGearTeethToHelical" id="txtGearTeethToHelical" size="7">
								<!--<select name="cmbGearTeethTypeHelical" id="cmbGearTeethTypeHelical">								
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>-->
							</td>	
							<td width="15%">
								Rate :<br>
								<input type="text" name="txtGearRateHelical" id="txtGearRateHelical" size="7" onblur="DecimalNum(this.id)">
							</td>
							<td width="20%">
								Min. Rate :<br>
								<input type="text" name="txtMinGearRateHelical" id="txtMinGearRateHelical" size="7" onblur="DecimalNum(this.id)" value="<?php print $MinValue?>">
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<input type="button" name="btnGearAddHelical" id="btnGearAddHelical" value="Add" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddGearHelicalRate()" onKeydown="AddGearHelicalRate()">
							</td>
						</tr>
					</table>
					<div id="dvGearDataHelical" style="width:100%"></div>
				   <br>
				   <div align="center" style="width:100%"><input type="submit" name="btnGearDataHelical" id="btnGearDataHelical" value="Save & Update Rates" class="btncss" onClick="checkBlank(this.id)"></div>
				</fieldset>
			</td>
			<td style="text-align:left; vertical-align:top; width:30%;" valign="top"><a href="#Top" class="legend" >Top</a><br><label id="lblGearHelicalMsg" class='ErrorMsg'></label></td>
		</tr>
		<tr><td colspan="2" height="20" width="100%"></td></tr>
<!-- 		<tr>
			<td width="70%" valign="top" style="padding-left:20px;">
				<a name="Map3"></a>
				<fieldset style="width:85%;">
					<legend class="legend">Special items Rates</legend>
					<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext">
						<tr>	  
							<td width="50%" align="left">Item Name :</br>
								<input type="hidden" name="hdSpecialItem" id="hdSpecialItem" value="<?php print $SpecialItemData ?>">
								<input type="text" name="txtSpecialItemName" id="txtSpecialItemName" size="28">
							</td>	
							<td width="50%" align="left">
								Rate :<br>
								<input type="text" name="txtSpecialItemRate" id="txtSpecialItemRate" size="7" onblur="DecimalNum(this.id)">
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="button" name="btnSpecialItemAdd" id="btnSpecialItemAdd" value="Add Rates" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddSpecialItemRate()" onKeydown="AddSpecialItemRate()">
							</td>
						</tr>
					</table>
					<div id="dvSpecialItemData" style="width:100%"></div>
				   <br>
				   <div align="center" style="width:100%"><input type="submit" name="btnSpecialItem" id="btnSpecialItem" value="Save & Update Rates" class="btncss"></div>
				</fieldset>
			</td>
			<td style="text-align:left; vertical-align:top; width:30%" valign="top"><a href="#Top" class="legend" >Top</a><br><label id="lblSpecialItemMsg" class='ErrorMsg'></label></td>
		</tr>
 -->		<!--<tr>
			<td valign="top" style="padding-left:20px;">
				<a name="Map4"></a>
				<fieldset style="width:85%;">
					<legend class="legend">Helical Shaft Pinion Rates</legend>
					
					<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext">
						<tr>	  				
							<td width="30%">
								<input type="hidden" name="hdShaftPHelical" id="hdShaftPHelical" value="<?php print $ShaftPDataHelical ?>">
								DP/Module:	<br>
								<input type="text" name="txtShaftPProcessingHelical" id="txtShaftPProcessingHelical" size="7">
								<select name="cmbShaftPProcessingTypeHelical" id="cmbShaftPProcessingTypeHelical">								
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							<td width="20%">
								Face From :</br>
								<input type="text" name="txtShaftPFaceFromHelical" id="txtShaftPFaceFromHelical" size="7">
							</td>	
							<td width="30%">
								To :<br>
								<input type="text" name="txtShaftPFaceToHelical" id="txtShaftPFaceToHelical" size="7">
								<select name="cmbShaftPFaceTypeHelical" id="cmbShaftPFaceTypeHelical">								
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>	
							<td width="20%">
								Rate :<br>
								<input type="text" name="txtShaftPRateHelical" id="txtShaftPRateHelical" size="7" onblur="DecimalNum(this.id)">
							</td>
						</tr>
						<tr>
							<td colspan="5">
								<input type="button" name="btnShaftPHelicalAdd" id="btnShaftPHelicalAdd" value="Add Rates" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddShaftPHelicalRate()" onKeydown="AddShaftPHelicalRate()">
							</td>
						</tr>
					</table>
					<div id="dvShaftPHelicalData" style="width:100%"></div>
				   <br>
				   <div align="center" style="width:100%"><input type="submit" name="btnShaftPHelical" id="btnShaftPHelical" value="Save & Update Rates" class="btncss"></div>
				</fieldset>
			</td>
			<td style="text-align:left; vertical-align:top; width:30%;" valign="top"><a href="#Top" class="legend" >Top</a><br><label id="lblShaftHelicalMsg" class='ErrorMsg'></label></td>
		</tr>
		<tr><td colspan="2" width="100%" height="20"></td></tr>	
		<tr>
			<td valign="top" style="padding-left:20px;">
				<a name="Map5"></a>
				<fieldset style="width:85%;">
					<legend class="legend">Chain/Sprocket Gear Rates</legend>
					
					<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext">
						<tr>
							<td width="30%">
								<input type="hidden" name="hdChainGear" id="hdChainGear" value="<?php print $ChainGearData ?>">
								Pitch Type :	<br>
								<input type="text" name="txtChainGearPitch" id="txtChainGearPitch" size="7">
								<select name="cmbChainGearType" id="cmbChainGearType">								
									<option value="inches">inches</option>
									<option value="mm">mm</option>
								</select>
							</td>
							<td width="20%">
								Rate :<br>
								<input type="text" name="txtChainGearRate" id="txtChainGearRate" size="7" onblur="DecimalNum(this.id)">
							</td>
							<td width="50%" colspan="2">
								<br>
								<input type="button" name="btnChainGearAdd" id="btnChainGearAdd" value="Add Rates" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddChainGearRate()" onKeydown="AddChainGearRate()">
							</td>
						</tr>
					</table>
					<div id="dvChainGearData" style="width:100%"></div>
				   <br>
				   <div align="center" style="width:100%"><input type="submit" name="btnChainGear" id="btnChainGear" value="Save & Update Rates" class="btncss"></div>
				</fieldset>
			</td>
			<td style="text-align:left; vertical-align:top; width:30%;" valign="top"><a href="#Top" class="legend" >Top</a><br><label id="lblChainGearMsg" class='ErrorMsg'></label></td>
		</tr>
		<tr>
			<td valign="top" style="padding-left:20px;">
				<a name="Map6"></a>
				<fieldset style="width:85%;">
					<legend class="legend">Bevel Gear/Pinion Rates</legend>
					
					<table cellspacing="0" cellpadding="0" class="labeltext" style ="width:100%">
						<tr>
							<td width="30%">
								<input type="hidden" name="hdBevelGear" id="hdBevelGear" value="<?php print $BevelGearData ?>">
								DP/Module :	<br>
								<input type="text" name="txtBevelGearProcessing" id="txtBevelGearProcessing" size="7">
								<select name="cmbBevelGearProcessingType" id="cmbBevelGearProcessingType">								
									<option value="DP">DP</option>
									<option value="Module">Module</option>
								</select>
							</td>
							<td width="16%">
								Teeth : <br>
								<input type="text" name="txtBevelGearTeeth" id="txtBevelGearTeeth" size="7">
							</td>
							<td width="16%">
								Rate : <br>
								<input type="text" name="txtBevelGearRate" id="txtBevelGearRate" size="7" onblur="DecimalNum(this.id)">
							</td>
							<td width="20%">
								<br>
								<input type="button" name="btnBevelGearAdd" id="btnBevelGearAdd" value="Add Rates" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddBevelGearRate()" onKeydown="AddBevelGearRate()">
							</td>
						</tr>
					</table>
					<div id="dvBevelGearData" style="width:100%"></div>
				   <br>
				   <div align="center" style="width:100%"><input type="submit" name="btnBevelGear" id="btnBevelGear" value="Save & Update Rates" class="btncss"></div>
				</fieldset>
			</td>
			<td style="text-align:left; vertical-align:top; width:30%;" valign="top"><a href="#Top" class="legend" >Top</a><br><label id="lblBevelGearMsg" class='ErrorMsg'></label></td>
		</tr>-->
	</table>
</Form>
</body>
</html>
