<?php
	session_start(); 
	require ('../general/authenticate.php');
	$_SESSION['curritem']='Specific Party Rate Master';
	require ('../general/dbconnect.php');
   	$ErrMsg=array();
	$Err=false;
	$PageMode="New";

	if (isset($_POST['btnGearDataPlain']))
	{
		if (trim($_POST['cmbParty'])=="")		
		{
			$Err=true;
			$ErrMsg[]="Please Select the Party...";
		}
		else if (trim($_POST['hdGearRatePlain'])=="")
		{
			$Err=true;
			$ErrMsg[]="Please enter the Data...";
		}
		
		$PartyID=$_POST['cmbParty'];
		$hdGearPlainData=trim($_POST['hdGearRatePlain']);

		$Validate=false;
		if (! $Err)
			$Validate=true;

		if ($Validate)
		{
			$rollback=false;
			$ItemArray=explode('~Array~', $hdGearPlainData);
			for ($i=0;$i<sizeof($ItemArray);$i++)
			{
				$data=explode('~ArrayItem~',$ItemArray[$i]);
				if ($data[7]=="New")
				{
					// New Case............
					$result=mysql_query ("INSERT into pp_party_rates_master (iPartyID, cItemName, fDMValue, cType ,fDiaFrom, fDiaTo, cDiaType, fRate) values ('$PartyID','Gear',\"".$data[1]."\",\"".$data[2]."\",\"".$data[3]."\",\"".$data[4]."\",\"".$data[5]."\", \"".$data[6]."\")");
					if (! $result)
					{
						$rollback=true;
					}
				}
				else
				{
					// Update Case...........
					//$result = mysql_query ("UPDATE pp_party_rates_master set fRate=\"".$data[6]."\" where iPartyID='$PartyID' and cItemName='Gear' and  fDMValue=$data[1] and cType=\"".$data[2]."\" and fDiaFrom=$data[3] and fDiaTo=$data[4] and cDiaType=\"".$data[5]."\"");
					$result = mysql_query ("UPDATE pp_party_rates_master set fDMValue=\"".$data[1]."\", cType=\"".$data[2]."\" , fDiaFrom=\"".$data[3]."\" , fDiaTo=\"".$data[4]."\" , cDiaType=\"".$data[5]."\" , fRate=\"".$data[6]."\" where iPartyID='$PartyID' and cItemName='Gear' and iId=\"".$data[0]."\"");
					if (! $result)
					{
						$rollback=true;
					}
				}
			}
		}
	}
	
	if (isset($_POST['btnGearDataHelical']))
	{
		if (trim($_POST['cmbParty'])=="")		
		{
			$Err=true;
			$ErrMsg[]="Please Select the Party...";
		}
		else if (trim($_POST['hdGearRateHelical'])=="")
		{
			$Err=true;
			$ErrMsg[]="Please enter the Data...";
		}
		$Validate=false;
		if (! $Err)
			$Validate=true;
		
		$PartyID=$_POST['cmbParty'];
		$hdGearHelicalData=trim($_POST['hdGearRateHelical']);	
		if ($Validate)
		{
			$rollback=false;
			$ItemArray=explode('~Array~', $hdGearHelicalData);
			for ($i=0;$i<sizeof($ItemArray);$i++)
			{
				$data=explode('~ArrayItem~',$ItemArray[$i]);
				if ($data[7]=="New")
				{
					$result=mysql_query ("INSERT into pp_party_rates_master (iPartyID, cItemName, fDMValue, cType ,iTeethFrom, iTeethTo, cTeethType, fRate) values ('$PartyID','Pinion', \"".$data[1]."\",\"".$data[2]."\",\"".$data[3]."\",\"".$data[4]."\",\"".$data[5]."\", \"".$data[6]."\")");
					if (! $result)
					{
						$rollback=true;
					}
				}
				else
				{
					// Update Case...........
					//$result = mysql_query ("UPDATE pp_party_rates_master set fRate=\"".$data[6]."\" where iPartyID='$PartyID' and cItemName='Pinion' and  fDMValue=$data[1] and cType=\"".$data[2]."\" and iTeethFrom=$data[3] and iTeethTo=$data[4] and cTeethType=\"".$data[5]."\"");
					$result = mysql_query ("UPDATE pp_party_rates_master set fDMValue=\"".$data[1]."\" ,cType=\"".$data[2]."\" , iTeethFrom=\"".$data[3]."\" , iTeethTo=\"".$data[4]."\" , cTeethType=\"".$data[5]."\", fRate=\"".$data[6]."\" where iPartyID='$PartyID' and cItemName='Pinion' and iId=\"".$data[0]."\"");
					if (! $result)
					{
						$rollback=true;
					}
				}
			}
		}	
	}
	
	if (isset($_POST['btnSpecialItem']))
	{
		if (trim($_POST['cmbParty'])=="")		
		{
			$Err=true;
			$ErrMsg[]="Please Select the Party...";
		}
		else if (trim($_POST['hdSpecialItem'])=="")
		{
			$Err=true;
			$ErrMsg[]="Please enter the Data...";
		}
		$Validate=false;
		if (! $Err)
			$Validate=true;
			
		$PartyID=$_POST['cmbParty'];
		if ($Validate)
		{
			$rollback=false;		
			$SpecialItemInfo=explode("~Array~",$_POST['hdSpecialItem']);
			
			for ($i=0;$i<sizeof($SpecialItemInfo);$i++)
			{
				$DataSpecialItem=explode("~ArrayItem~",$SpecialItemInfo[$i]);
				
				if ($DataSpecialItem[4]=="New")
				{
					$result=mysql_query ("INSERT into special_item_rate_master (iPartyID, cItemName,fRate,cMeasurement) values (\"".$PartyID."\" ,\"".$DataSpecialItem[1]."\",\"".$DataSpecialItem[2]."\",\"".$DataSpecialItem[3]."\")");				
					if (! $result)
					{
						$rollback=true;
					}
				}
				else
				{
					$result=mysql_query ("UPDATE special_item_rate_master set cItemName=\"".$DataSpecialItem[1]."\",fRate=\"".$DataSpecialItem[2]."\",cMeasurement=\"".$DataSpecialItem[3]."\" where iId=\"".$DataSpecialItem[0]."\"");
					if (! $result)
					{
						$rollback=true;
					}
				}
			}
		}
	}
	
	if ($rollback)
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
<script language="javascript" type="text/javascript" src="party_specific_ratespp.js"></script>
<script type="text/javascript" language="javascript" src="../javascript/string.js"></script>
<script type="text/javascript" language="javascript" src="../javascript/typeaheadcombo.js"></script>
</head>
<body onkeyup="ShiftUp()" onkeydown="EnterKeyHandle()" onload="Pageload()">
<form name="frmSpecificRateMaster" method="POST" action="">
	<table cellspacing="0" cellpadding="0" width="100%" align="center" class="labeltext">
		<tr>
			<td class="borderblue" colspan="4">
				<table cellpadding="0" cellspacing="0" width="100%" >
					<tr>
						<td width="60%"><h5><a  id="group_heading" style="width:60%">Power Press Party Rate</a></h5></td>
						<td width="40%">
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
							<b><label id="lblErrMsg" class='ErrorMsg'>
							</label></b>
						</td>
					</tr>
				</table>			
			</td>
		</tr>
		<tr>
			<td height="20px">	
			</td>
		</tr>
		<tr>
			<td height="25px">
				<table cellpadding="0" cellspacing="0" width="100%" style="padding-left:30px;" class="labeltext">
					<tr>
						<td width="40%">Party List :<br>
							<select name="cmbParty" id="cmbParty" width="250px" tabindex="1" onchange="selItems(this.id)">
								<option value=""><?php print str_repeat("-",35) ?>Select Party<?php print str_repeat("-",35) ?></option>
								<?php
									require ('../general/dbconnect.php');
									$result=mysql_query ("select (iPartyID) as PartyID, iPartyID , cPartyName from party_master where cPartType='PowerPress' order by cPartyName");
									if ($result && mysql_num_rows($result)>0)
									{
										while ($row=mysql_fetch_array($result))
										{
											print "<option value=\"$row[PartyID]\"";
											if ($PartyId==$row['PartyID'])	print "selected";
											print ">$row[cPartyName]</option>";
										}
									}
								?>
							</select>
						</td>
						<td width="20%"> 
						</td>
						<td width="40%">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<fieldset style="width:520px;">
								<legend class="legend">Gears</legend>
								<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext">
									<tr>	  
										<td width="30%">
											<input type="hidden" name="hdGearRatePlain" id="hdGearRatePlain" value="<?php print $GearData ?>">
											DP/Module :	<br>
											<input type="text" name="txtGearProcessingPlain" id="txtGearProcessingPlain" size="7">
											<select name="cmbGearProcessingTypePlain" id="cmbGearProcessingTypePlain">								
												<option value="DP">DP</option>
												<option value="Module">Module</option>
											</select>
										</td>
										<td width="13%">
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
										<td width="20%">
											Rate :<br>
											<input type="text" name="txtGearRatePlain" id="txtGearRatePlain" size="7" onblur="DecimalNum(this.id)">
										</td>
										
									</tr>
									<tr>
										<td colspan="5">
											<input type="button" name="btnGearAddPlain" id="btnGearAddPlain" value="Add" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddGearRate()" onKeydown="AddGearRate()">
										</td>
									</tr>
								</table>
								<div id="dvGearDataPlain" style="width:100%"></div>
							   <br>
							  <div align="center" style="width:100%"><input type="submit" name="btnGearDataPlain" id="btnGearDataPlain" value="Save & Update Rates" class="btncss" onclick="CheckBlank(this.id)"></div>
							</fieldset>							
						</td>
						<td style="text-align:left; vertical-align:top;" valign="top">
							<label id="lblGearPlainMsg" class='ErrorMsg'></label>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<fieldset style="width:95%">
								<legend class="legend">Pinion </legend>
								<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext">
									<tr>	  
										<td width="30%">
											<input type="hidden" name="hdGearRateHelical" id="hdGearRateHelical" value="<?php print $GearDataHelical ?>">
											DP/Module :	<br>
											<input type="text" name="txtGearProcessingHelical" id="txtGearProcessingHelical" size="7">
											<select name="cmbGearProcessingTypeHelical" id="cmbGearProcessingTypeHelical">								
												<option value="DP">DP</option>
												<option value="Module">Module</option>
											</select>
										</td>
										<td width="20%">
											Teeth From :</br>
											<input type="text" name="txtGearTeethFromHelical" id="txtGearTeethFromHelical" size="7">
										</td>	
										<td width="30%">
											To :<br>
											<input type="text" name="txtGearTeethToHelical" id="txtGearTeethToHelical" size="7">
											<select name="cmbGearTeethTypeHelical" id="cmbGearTeethTypeHelical">								
												<option value="inches">inches</option>
												<option value="mm">mm</option>
											</select>
										</td>	
										<td width="20%">
											Rate :<br>
											<input type="text" name="txtGearRateHelical" id="txtGearRateHelical" size="7" onblur="DecimalNum(this.id)">
										</td>
									</tr>
									<tr>
										<td colspan="5">
											<input type="button" name="btnGearAddHelical" id="btnGearAddHelical" value="Add" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddGearHelicalRate()" onKeydown="AddGearHelicalRate()">
										</td>
									</tr>
								</table>
								<div id="dvGearDataHelical" style="width:100%"></div>
							   <br>
							   <div align="center" style="width:100%"><input type="submit" name="btnGearDataHelical" id="btnGearDataHelical" value="Save & Update Rates" class="btncss" onclick="CheckBlank(this.id)"></div>
							</fieldset>
						</td>
						<td style="text-align:left; vertical-align:top;" valign="top">
							<label id="lblGearHelicalMsg" class='ErrorMsg'></label>
						</td>
					</tr>
					<tr>
						<td width="70%" valign="top"  colspan="2">
						
							<fieldset style="width:85%;">
								<legend class="legend">Special items Rates</legend>
								<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext">
									<tr>	  
										<td width="50%" align="left">Item Name :</br>
											<input type="hidden" name="hdSpecialItem" id="hdSpecialItem" value="<?php print $SpecialItemData ?>">
											<input type="text" name="txtSpecialItemName" id="txtSpecialItemName" size="28">
										</td>	
										<td width="25%" align="left">
											Rate :<br>
											<input type="text" name="txtSpecialItemRate" id="txtSpecialItemRate" size="7" onblur="DecimalNum(this.id)">
										</td>
										<td width="25%" align="left">
											Measurement:<br>
											<select name="cmbSpecialItemType" id="cmbSpecialItemType" >
												<option value="PT">PT</option>
												<Option value="PCS">PCS</option>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<input type="button" name="btnSpecialItemAdd" id="btnSpecialItemAdd" value="Add" class="btncss"  style="margin-top:5px; margin-bottom:10px;" onclick="AddSpecialItemRate()" onKeydown="AddSpecialItemRate()">
										</td>
									</tr>
								</table>
								<div id="dvSpecialItemData" style="width:100%"></div>
							   <br>
							   <div align="center" style="width:100%"><input type="submit" name="btnSpecialItem" id="btnSpecialItem" value="Save & Update Rates" class="btncss" onclick="CheckBlank(this.id)"></div>
							</fieldset>
						</td>
						<td style="text-align:left; vertical-align:top; width:30%" valign="top"><br><label id="lblSpecialItemMsg" class='ErrorMsg'></label></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="25px;">
			</td>
		</tr>
		<!--<tr>
      		<td height="25px;" style="text-align:right; padding-right:50px;">
				<input type="hidden" name="hdItemType" id="hdItemType">
				<input type="hidden" name="hdItemData" id="hdItemData">
			</td>
		</tr>
		<tr>
			<td>
				<div id="dvAddRates" style="padding-left:30px;">
					
				</div>
			</td>
		</tr>-->
		<tr>
			<td height="25px;"></td>
		</tr>
		<tr>
			<td style="text-align:center;">
				<!--<input type="submit" name="btnSave" id="btnSave" value="Save" class="btncss" onclick="CheckBlank()" tabindex="9">
				<span style="margin-right:10px;"></span>
				<input type="reset" name="btnReset" id="btnReset" value="Reset" class="btncss" tabindex="10" onKeyDown="EnterKeyClick(this.id)" onClick="Reset()" onBlur="focusFirstElement();">
				<input type="hidden" id='formfocuselement' value=''/>-->
			</td>
		</tr>
	</table>
</form>
</body>
</html>
