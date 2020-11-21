<?php
	session_start(); 
	require ('../general/authenticate.php');
	$_SESSION['curritem']='Party TDS';
	require ('../general/dbconnect.php');
	$user=$_SESSION['DbUsername'];
	$pass=$_SESSION['DbPassword'];
	$db=$_SESSION['Database'];
   	$ErrMsg=array();
	$Err=false;
	$CurrDate=date ("d/m/Y");
	$BankDate=$CurrDate;
	$PageMode="New";
	$Month=$_SESSION['Month'];
	$YearString=$_SESSION['YearString'];
	$firstday = date ("m/d/Y", mktime(0, 0, 0, date("m") , date("d")-20 , date("Y")));
	
	$startDate=explode("/",$firstday);
	$BeforeDate = $startDate[1]."/".$startDate[0]."/".$startDate[2];	
	$SNO=1;
	if (isset($_POST['btnGetData']))
	{
		$PartyID=$_POST['cmbParty'];
		$BeforeDate=$_POST['txtFromDate'];
		$CurrDate=$_POST['txtToDate'];
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<META HTTP-EQUIV="refresh"> 
<title>Games and Toys</title>
<link href="../games.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../javascript/general.js"></script>
<script language="JavaScript" type="text/javascript">
function PageLoad()
{
	document.getElementById("cmbMainParty").focus();
}

function ClearText()
{
	document.getElementById("txtTeeth").value="";
	document.getElementById("txtDia").value="";
	document.getElementById("txtFace").value="";
	document.getElementById("txtProcessing").value="";
}

function ShowPdf(User, Pass, Db)
{
	lbl=document.getElementById("lblErrMsg");
	lbl.innerHTML="";
	
	if (document.getElementById("cmbMainParty").value!="")
	{
		if (document.getElementById("cmbItem").value!="")
		{
			Party=document.getElementById("cmbMainParty").value;
			ItemType=document.getElementById("cmbItem").value;
			FromDate=document.getElementById("txtFromDate").value;
			ToDate=document.getElementById("txtToDate").value;
			Teeth=document.getElementById("txtTeeth").value;
			Dia=document.getElementById("txtDia").value;
			DiaType=document.getElementById("cmbDiaType").value;
			Face=document.getElementById("txtFace").value;
			FaceType=document.getElementById("cmbFaceType").value;
			Processing=document.getElementById("txtProcessing").value;
			ProcessingType=document.getElementById("cmbProcessingType").value;
			if (ItemType=="Gear" || ItemType=="Pinion" || ItemType=="Shaft Pinion")
				Type=document.getElementById("cmbType").value;
			else if (ItemType=="Chain Wheel")
				Type=document.getElementById("cmbChainWheelType").value;
			else
				Type="";
			
			url="../reports/search_items_pdf.php?FromDate="+FromDate+"&ToDate="+ToDate+"&PartyID="+Party+ "&Teeth="+Teeth+"&Dia="+Dia+"&DiaType="+DiaType+"&Face="+Face+"&FaceType="+FaceType+"&Processing="+Processing+"&ProcessingType="+ProcessingType+"&ItemType="+ItemType+"&Type="+Type+"&User="+User+"&Pass="+Pass+"&Db="+Db;
			document.getElementById('iframe2').src=url;
			event.returnValue=false;
		}
		else
		{
			lbl.innerHTML="Please Select Item Type...";
			document.getElementById("cmbItem").focus();
			event.returnValue=false;
		}
	}
	else
	{
		lbl.innerHTML="Please Select Main Party...";
		document.getElementById("cmbMainParty").focus();
		event.returnValue=false;
	}
}	

function CallDiv(ctrl)
{
	if (ctrl.value=="Gear" || ctrl.value=="Pinion" || ctrl.value=="Shaft Pinion")
	{
		document.getElementById("dvChainWheelType").style['display']="none";
		document.getElementById("dvGearType").style['display']="inline";
	}
	else if (ctrl.value=="Chain Wheel")
	{
		document.getElementById("dvGearType").style['display']="none";
		document.getElementById("dvChainWheelType").style['display']="inline";
	}
	else if (ctrl.value=="Bevel Gear" || ctrl.value=="Bevel Pinion" || ctrl.value=="Worm Gear")
	{
		document.getElementById("dvGearType").style['display']="none";
		document.getElementById("dvChainWheelType").style['display']="none";
	}
}

</script>
</head>
<body onload="PageLoad()">
<form name="frmPartyTds" method="post" action="">
	<table cellspacing="0" cellpadding="0" width="100%" align="center" class="labeltext">
		<tr>
			<td width="100%" height="25px" class="borderblue">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="25%" style="padding-left:20px;">
							<h5><a  id="group_heading">Search Items</a></h5>
						</td>
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
							<b><label id="lblErrMsg" class='ErrorMsg'></label></b>
						</td>
						<td width="35%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset class="legend" align="center" style="width:900px">
				<legend>Filters</legend>
				<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext" align="center">
					<tr>
						<td height="25px" style="padding-left:5px;"  align="left" width="30%">Main Party :<br>
						<select name="cmbMainParty" id="cmbMainParty" width="180"  tabindex="1">
								<option value=""><?php print str_repeat("-",23); ?>Select Party<?php print str_repeat("-",23); ?></option>
									<?php
										require ('../general/dbconnect.php');
										$result=mysql_query ("select iPartyID , cPartyName from party_master order by cPartyName");
										if ($result && mysql_num_rows($result)>0)
										{
											while ($row=mysql_fetch_array($result))
											{
												print "<option value=\"$row[iPartyID]\"";
												if ($PartyId==$row['iPartyID'])	print "selected";
												print ">$row[cPartyName]</option>";
											}
										}
									?>
							</select>																  
						</td>
						<td width="35%" align="center">From Date : <br>
							<input type="hidden" name="hfCurrDate" id="hfCurrDate" value="">
							<input type="text" name="txtFromDate" id="txtFromDate" style="height:15px" value="<?php print $BeforeDate; ?>" size="12" onFocus="this.blur()" readonly>
							<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fEndPop(document.frmPartyTds.hfCurrDate,document.frmPartyTds.txtFromDate);return false;" >
							<img class="PopcalTrigger" align="absmiddle" src="../js-calender/calbtn.gif" border="0" alt=""></a>
						</td>
						<td width="35%" align="center">To : <br>
							<input type="text" name="txtToDate" id="txtToDate" style="height:15px" value="<?php print $CurrDate; ?>" size="12" onFocus="this.blur()" readonly>
							<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fEndPop(document.frmPartyTds.hfCurrDate,document.frmPartyTds.txtToDate);return false;" >
							<img class="PopcalTrigger" align="absmiddle" src="../js-calender/calbtn.gif" border="0" alt=""></a>
						</td>
					</tr>
					<tr>
						<td height="25px;" style="padding-left:5px;" align="left"> Item Type :<br/>
							<select name="cmbItem" id="cmbItem" width="180" tabindex="2" onchange="CallDiv(cmbItem)">
								<option value="">---------Select Item--------</option>
								<?php
								   require ('../general/dbconnect.php');
								   $Itemqry=mysql_query ("select * from item_master");
								   if ($Itemqry && mysql_num_rows($Itemqry)>0)
								   {
									   while ($Itemrow=mysql_fetch_array($Itemqry))
									   {
										   if ($Itemrow['cItemCode']=="Gear")
										   {
											   print "<option value=\"$Itemrow[cItemCode]\" selected>$Itemrow[cItemName]</option>";
										   }
										   else
										   {
												print "<option value=\"$Itemrow[cItemCode]\">$Itemrow[cItemName]</option>";
										   }
									   }
								   } 
								?>
							</select> 
						</td>
						<td colspan="2" align="left">
							<div style="float:left;">
								<div  style="float:left; "> 
									Teeth : <br>
									<input type="text" name="txtTeeth" id="txtTeeth" size="5" onKeyDown="OnlyInt1(this.id)" tabindex="3">
								</div>
								<div id="dvGearType" style="float:left; "> 
									<span style="margin-left:10px;"></span>Type :<br>
									<select name="cmbType" id="cmType" tabindex="4">
										<option value="Plain">Plain</option>
										<option value="Helical">Helical</option>
										<option value="Spur Hobb">Spur Hobb</option>
									</select>
								</div>
								
								<div id="dvChainWheelType" style="float:left; display:none; ">
									<span style="margin-left:10px;"></span>Type :<br>
									<select name="cmbChainWheelType" id="cmbChainWheelType" tabindex="5">
										<option value="Single">Single</option>
										<option value="Duplex">Duplex</option>
										<option value="Triplex">Triplex</option>
										<option value="Fourplex">Fourplex</option>
									</select>
								</div>
								<div  style="float:left; "> 
									<span style="margin-left:50px;"></span>Dia :<br>
									<input type="text" name="txtDia" id="txtDia" size="7" tabindex="6" onblur="onlyGrams(this.id)">													
									<select name="cmbDiaType" id="cmbDiaType" tabindex="7">
										<option value="inches">inches</option>
										<option value="mm">mm</option>
									</select>
								</div>
								<div  style="float:left; "> 
									<span style="margin-left:50px;"></span>Face :<br>
									<input type="text" name="txtFace" id="txtFace" size="6" tabindex="8">											
									<select name="cmbFaceType" id="cmbFaceType" tabindex="9">
										<option value="inches">inches</option>
										<option value="mm">mm</option>
									</select>
								</div>
								<div  style="float:left; "> 
									<span style="margin-left:20px;"></span>DP/Module :<br>
									<input type="text" name="txtProcessing" id="txtProcessing" size="5" tabindex="10" >											
									<select name="cmbProcessingType" id="cmbProcessingType" tabindex="11">
										<option value="DP">DP</option>
										<option value="Module">Module</option>
									</select>
								</div>
							</div>
						</td>

					</tr>
					<tr>
						<td colspan="3" height="25px;"></td>
					</tr>
					<tr>
						<td style="padding-left:30px;text-align:center;" colspan="3">
							<?php	
							 	print "<input type=\"button\" name=\"btnGetData\" id=\"btnGetData\" value=\"Get Data\" class=\"btncss\"  tabindex=\"12\" onClick=\"ShowPdf('$user','$pass','$db')\">";	
							 ?>
							<!--<input type="button" name="btnGetData" id="btnGetData" value="Get Data" class="btncss" tabindex="12" onClick="ShowPdf()" > -->
							<input type="button" name="btnNewSearch" id="btnNewSearch" value="New Search" class="btncss" tabindex="13" onClick="ClearText()" onblur="document.getElementById('cmbMainParty').focus()">
						</td>
					</tr>
				</table>
			</fieldset>
			</td>
		</tr>
		<tr>
			<td height="25px;"></td>
		</tr>
		<tr>
			<td>
				<iframe id='iframe2' height="500" width="950" marginheight="0" marginwidth="0" frameborder="0" style="overflow-x: hidden;"></iframe>
			</td>
		</tr>
	</table>
</form>
<iframe width=132 height=142 name="gToday:contrast:agenda.js" id="gToday:contrast:agenda.js" src="../js-calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>
</body>
</html>
