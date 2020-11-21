<?php
	require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
	if(empty(trim($_POST['auth_info'])))
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
		
		$CurrDate=date ("Y-m-d");
		$PageMode="New";
		$Month=$_SESSION['Month'];
		$BeforeDate = date('Y-m-d', strtotime('-10 days', strtotime($CurrDate)));
	    $txtBillingUser=base64_decode(base64_decode(trim($_POST['txtBillingUser'])));
	}
   	
	
	$PageMode="New";
	
		
	$TotalPrice=0;
	$TotalWithtax=0;
	$VatPer=0;
	$CGSTPer=0;
	$SGSTPer=0;
	$IGSTPer=0;
	$MiscTaxPer=0;
	$MiscItemRate=0;
	$MiscItemQty=0;
	$SurchargeAmt=0;
	$SurchargePer=0;
	$VatFactor=100;
	
	/*
		To implement vat calculation on 50% of total value.
		fVat factor 50% added to table vat_factor
		logic written by shravan dated 01/10/10
	
	*/
	
	$result=mysqli_query($con2,"select fVatFactor from vat_factor");
	if($result && mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$VatFactor=$row['fVatFactor'];
	}
	else
	{
		$Err=true;
		$ErrMsg="Error in getting vat factor...";
	}
	
	$PPrateQry=mysqli_query($con2,"select fMinValue  from party_master where fMinValue>0 and cPartType ='PowerPress'");	
	if ($PPrateQry && mysqli_num_rows($PPrateQry)>0)
	{
		$PPrateRow=mysqli_fetch_array($PPrateQry);
		$MinValue=$PPrateRow['fMinValue'];
	}
	else
	{
		$MinValue=0;
	}
	
	if ($txtBillingUser=="2")   //user type 2 for kacha bill
	{
	
		$PartyBilling="k_party_billing";
		$PartyBillingDetail="k_party_billing_detail";
	}
	else if ($txtBillingUser=="1")   //user type 1 for pakka bill
	{
	
		$PartyBilling="party_billing";
		$PartyBillingDetail="party_billing_detail";
	}
	else
	{
		$Err=true;
		$ErrMsg="Invalid Request";
	}
	
	if (isset($_POST['iBillingID']))
	{
		
		$BillingID=$_POST['iBillingID'];
		$BYearprefix=$_POST['BYearPrefix'];
		$Billingqry=mysqli_query($con2,"select * from $PartyBilling where iBillingID=\"$BillingID\"");
		if ($Billingqry && mysqli_num_rows($Billingqry)>0)
		{
			$Billingrow=mysqli_fetch_array($Billingqry);
			$BillingCode=$Billingrow['cBillingCode'];
			
			$CurrDate=$Billingrow['dBillingDate'];
			$CompanyCode=$Billingrow['iCompanyCode'];
			$CompanySNo=$Billingrow['iCompanySNo'];
			$PartyCode=$Billingrow['iPartyCode'];
			$PartyId=$PartyCode;
			$PartySNo=$Billingrow['iFirmSNo'];
			
			$VatPer=$Billingrow['fVatPer'];
			$CGSTPer=$Billingrow['iCGSTVal'];
			$SGSTPer=$Billingrow['iSGSTVal'];
			$IGSTPer=$Billingrow['iIGSTVal'];
			$SurchargePer=$Billingrow['fSurcharge'];
			
			$MiscTaxName=$Billingrow['cMiscTaxName'];
			$MiscTaxPer=$Billingrow['fMiscTaxPer'];
			$BillAmt=$Billingrow['fBillAmt'];
			$Remarks=$Billingrow['cRemarks'];
			$VatFactor=$Billingrow['fVatFactor'];
			// Calculation of Misc Item..............
			$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp from $PartyBillingDetail where iBillingID='$BillingID' and cItemType='MISC'");
			if ($Miscqry && mysqli_num_rows($Miscqry)>0)
			{
				$Miscrow=mysqli_fetch_array($Miscqry);
				$MiscItemName=$Miscrow['cMiscItemName'];
				$MiscItemQty=$Miscrow['iNoPcsDisp'];
				$MiscItemRate=$Miscrow['fRate'];
				$MiscItemAmt=$MiscItemQty * $MiscItemRate;
				$MiscItemAmt=round($MiscItemAmt,2);
			}
			$BDetailqry=mysqli_query($con2,"select * from $PartyBillingDetail where iBillingID='$BillingID' and cItemType<>'MISC'");
			if ($BDetailqry && mysqli_num_rows($BDetailqry)>0)
			{
				while ($row=mysqli_fetch_array($BDetailqry))
				{ 
					$ItemType=$row['cItemType'];
					$ItemCode=$row['iItemCode'];
					$ItemRate=$row['fRate'];
					$PcsDisp=$row['iNoPcsDisp'];
					$PartType=$row['cPartType'];
					$Pcs=$row['cPcs'];
					$MinValue=$row['fMinValue'];
					
					$Collar=$row['cCollar'];
					
					if ($row['cItemType']=="Gear")
				    {
						$qry=mysqli_query($con2,"select * from gear_master where iId='$row[iItemCode]'");
						
				    }
				    else if ($row['cItemType']=="Pinion")
				    { 
						$qry=mysqli_query($con2,"select * from pinion_master where iId='$row[iItemCode]'");
				    }
				   else if ($row['cItemType']=="Shaft Pinion")
				   {
						$qry=mysqli_query($con2,"select * from shaft_pinion_master where iId='$row[iItemCode]'");
				   }
				   else if ($row['cItemType']=="Bevel Gear")
				   {
						$qry=mysqli_query($con2,"select * from bevel_gear_master where iId='$row[iItemCode]'");
				   }
				   else if ($row['cItemType']=="Bevel Pinion")
				   {
						$qry=mysqli_query($con2,"select * from bevel_pinion_master where iId='$row[iItemCode]'");
				   }
				   else if ($row['cItemType']=="Chain Wheel")
				   {
					   $qry=mysqli_query($con2,"select * from chain_gear_master where iId='$row[iItemCode]'");
				   }
				   else if ($row['cItemType']=='Worm Gear')
				   {
				   		$qry=mysqli_query($con2,"select * from worm_gear_master where iId=$row[iItemCode]");
				   }

				   if ($qry && mysqli_num_rows($qry)>0)
				   {
						$row1=mysqli_fetch_array($qry);
	
					   if ($ItemData=="")
					   {
							$ItemData ="$row[iNoPcsDisp]~ArrayItem~$row[fRate]~ArrayItem~$row1[iTeeth]~ArrayItem~$row1[fDMValue]~ArrayItem~$row1[cType]~ArrayItem~$row1[fDia]~ArrayItem~$row1[cDiaType]~ArrayItem~$row1[fFace]~ArrayItem~$row1[cFaceType]~ArrayItem~$row1[cItemType]~ArrayItem~$row1[fPitch]~ArrayItem~$row[cItemType]~ArrayItem~$row1[cPitchType]~ArrayItem~$row1[iId]~ArrayItem~~ArrayItem~$PartType~ArrayItem~$Pcs~ArrayItem~$Collar";
					   }
					   else
					   {
							$ItemData=$ItemData. "~Array~$row[iNoPcsDisp]~ArrayItem~$row[fRate]~ArrayItem~$row1[iTeeth]~ArrayItem~$row1[fDMValue]~ArrayItem~$row1[cType]~ArrayItem~$row1[fDia]~ArrayItem~$row1[cDiaType]~ArrayItem~$row1[fFace]~ArrayItem~$row1[cFaceType]~ArrayItem~$row1[cItemType]~ArrayItem~$row1[fPitch]~ArrayItem~$row[cItemType]~ArrayItem~$row1[cPitchType]~ArrayItem~$row1[iId]~ArrayItem~~ArrayItem~$PartType~ArrayItem~$Pcs~ArrayItem~$Collar";
					   }
					   
				   }
				}
			}
			$PageMode="Edit";	
			
		}
	}
	else
	{
		$PageMode="New";
	}

	
	
?>
<script>
$("document").ready(function(){
	PageLoad3();
})
</script>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script type="text/javascript" language="javascript" src="../js/string.js"></script>
<script type="text/javascript" language="javascript" src="../js/typeaheadcombo.js"></script>
<script type="text/javascript" language="javascript" src="../js/billing.js"></script>
  

<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Operations</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Billing</li>
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
<form name="frmBilling" id="frmBilling" method="POST" action="">
<input type="hidden" name="txtBillingUser" id="txtBillingUser" value="<?=trim($_POST['txtBillingUser'])?>">
<input type="hidden" name="auth_info" id="auth_info" value="<?=trim($_POST['auth_info'])?>">
<input type="hidden" name="hdVatFactor" id="hdVatFactor" value="<?php print $VatFactor?>">
<input type="hidden" name="hdPartySNo" id="hdPartySNo" value="<?php print $PartySNo?>">
<input type="hidden" name="hdPageMode" id="hdPageMode" value="<?php print $PageMode ?>">
<input type="hidden" id="cmbBilling" name="cmbBilling" value="<?=base64_encode(base64_encode($BillingCode."~".$BillingID))?>">
<input type="hidden" name="hdMinValue"	id="hdMinValue" value="<?=$MinValue ?>">
<input type="hidden" name="hfCurrDate" id="hfCurrDate" value="">

   <div class="row">
      <div class="col-sm-6">
	     <span class="float-left">
		   
				<input type="radio" name="rdb" id="rdNew" value="New" checked onClick="Switch(this.id)">&nbsp;New Billing &nbsp;&nbsp;
				<input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)">List Billing &nbsp;&nbsp;
			    <br>
				<input type="radio" name="rdc" id="rdWithChallan" value="With Challan"  onClick="SwitchDC(this.id)">&nbsp;From Party Order
				<input type="radio" name="rdc" id="rdWithoutChallan" value="Without Challan" checked onClick="SwitchDC(this.id)">&nbsp;Without Party Order
			
		 </span>
	  </div>
      <div class="col-sm-6">
	  <span class="float-right"id="tdLastBillNo">
		   <?php
					$CodeMsg="";
					$result=mysqli_query($con2,"select cBillingCode,iBillingID, dBillingDate from party_billing where iBillingID=(select MAX(iBillingID) from party_billing)");
					if ($result)
					{
						if (mysqli_num_rows($result)>0)
						{
							$row=mysqli_fetch_array($result);
							$d=explode('-',$row['dBillingDate']);
							$bdate=$d[2].'/'.$d[1].'/'.$d[0];
							$CodeMsg="Last Billing No : ".$row['cBillingCode']."<br> Date : $bdate";
							print $CodeMsg;
						}
						else
						{
							$CodeMsg="No Billing saved before...";
							print $CodeMsg;
						}
					}	
					
				?>
		 </span>
	  </div>
   </div>
   <div class="row">
     <div class="col-sm-6">
	   <label for="cmbParty">Party List</label>
	   <select name="cmbParty" id="cmbParty" class="form-control" tabindex="1" onchange="selPartType(); SelParty(''); ">
								<option value=""><?php print str_repeat("-",20) ?>Select Party<?php print str_repeat("-",20) ?></option>
								<?php
									$result=mysqli_query($con2,"select (iPartyID) as PartyID, iPartyID ,cStateCode , cPartyName, cPartType from party_master order by cPartyName");
									if ($result && mysqli_num_rows($result)>0)
									{
										while ($row=mysqli_fetch_array($result))
										{
											$result1=mysqli_query($con2,"select * from party_master_detail where iPartyID='$row[iPartyID]'");
											{
												if ($result1 && mysqli_num_rows($result1)>0)
												{
													while ($row1=mysqli_fetch_array($result1))
													{
														if ($PartyData=="")
															$PartyData="$row1[iSNo]~Array~$row1[cFirmName]";
														else
															$PartyData=$PartyData."~ItemData~"."$row1[iSNo]~Array~$row1[cFirmName]";
													}
												}
											}
											print "<option value=\"$row[PartyID]~ArrayItem~$PartyData~ArrayItem~$row[cPartType]~ArrayItem~$row[cStateCode]\"";
											if ($PartyId==$row['PartyID'])	print "selected";
											print ">$row[cPartyName]</option>";
											$PartyData="";
										}
									}
								?>
							</select>
	 </div>
	 <div class="col-sm-6">
	    <label for="txtBillingDate">Billing Date</label>
		<input type="date" name="txtBillingDate" id="txtBillingDate" class="form-control" value="<?=$CurrDate; ?>"  onFocus="this.blur()" readonly>
	 </div>
   </div>
   <div class="row">
     <div class="col-sm-6">
	 <label for="">State Code</label>
	   <input type="text" name="txtStateCode" id="txtStateCode" class="form-control" value="<?=$stateCode; ?>"  onFocus="this.blur()" readonly>
	 </div>
     <div class="col-sm-6">
	 
	 </div>
   </div>
   <div class="row">
     <div class="col-sm-6">
	 <label for="cmbFrom">From</label>
	   <select name="cmbFrom" id="cmbFrom" class="form-control" tabindex="2" onchange="ChangeLastBill(this.id)">
								<option value=""><?=str_repeat("-",20) ?>Select Company<?=str_repeat("-",20) ?></option>
								<?php
									
									$result=mysqli_query($con2,"select company_master.iPartyID, (0) as iSNo, concat(iPartyID,'~ArrayItem~','0') as PartyID , cPartyName as cPartyName from company_master UNION select  company_master_detail.iPartyID, iSNo,concat(iPartyID,'~ArrayItem~',iSNo) as PartyID , cFirmName as cPartyName from company_master_detail ");
									if ($result && mysqli_num_rows($result)>0)
									{
										if ($_POST['CompanySNo']=="") $CompanySNo=0 ;else $CompanySNo=$_POST['CompanySNo'];
										while ($row=mysqli_fetch_array($result))
										{
											$PartyID=$row['PartyID'];
											$result1=mysqli_query($con2,"select cBillingCode, dBillingDate from party_billing where iBillingID=(select MAX(iBillingID) from party_billing where iCompanyCode=\"$row[iPartyID]\" and iCompanySNo=\"$row[iSNo]\")");
											if($result1 && mysqli_num_rows($result1)>0)
											{
												$row1=mysqli_fetch_array($result1);
												$d=explode('-',$row1['dBillingDate']);
												$bdate=$d[2].'/'.$d[1].'/'.$d[0];
												
												$PartyID.='~ArrayItem~'.$row1['cBillingCode'].'~ArrayItem~'.$bdate;
											}
											else
											{
												$PartyID.='~ArrayItem~~ArrayItem~';
											}
											print "<option value=\"$PartyID\"";
											if ($row['PartyID']=="$CompanyCode~ArrayItem~$CompanySNo") print "selected";
											print ">$row[cPartyName]</option>";
										}
									}
								?>
							</select>
	 </div>
     <div class="col-sm-6">
	      <label for="cmbTo">To</label>
	      <select name="cmbTo" id="cmbTo" class="form-control" tabindex="3">
				<option value=""><?=str_repeat("-",20) ?>Select Party<?=str_repeat("-",20) ?></option>
		  </select>
	 </div>
   </div>
   <div class="row">
     <div class="col-sm-6">
	   <label for="">Item Type :</label>
	   <select name="cmbItem" id="cmbItem" class="form-control" onChange="CallDiv()" tabindex="4">
								<option value="">---------Select Item--------</option>
								<?php
								   
								   $Itemqry=mysqli_query($con2,"select * from item_master");
								   if ($Itemqry && mysqli_num_rows($Itemqry)>0)
								   {
									   while ($Itemrow=mysqli_fetch_array($Itemqry))
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
	 </div>
     <div class="col-sm-6"></div>
   </div>
   
    <div id="dvgear">
								<fieldset style="width:100%;padding:20px;border:1px solid #ABD0BC">
									<legend>Gear</legend>
									 <div  class="row">
									<div class="col-sm-6">
									   <label for="cmbGear">Select Gear</label>
									   <select name="cmbGear" id="cmbGear" class="form-control"  onChange="selGear(this.id)" tabindex="5">
											<option value=""><?=str_repeat("-",20) ?>  Select Gear  <?=str_repeat("-",20) ?></option>
											<?php
											   
											   $Gearqry=mysqli_query($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fFace , cFaceType , fDMValue , cType from gear_master order by iTeeth, cItemType");
											   if ($Gearqry && mysqli_num_rows($Gearqry)>0)
											   {
												   while ($Gearrow=mysqli_fetch_array($Gearqry))
												   {
														print "<option value=\"$Gearrow[iId]~ArrayItem~$Gearrow[iTeeth]~ArrayItem~$Gearrow[cItemType]~ArrayItem~$Gearrow[fDia]~ArrayItem~$Gearrow[cDiaType]~ArrayItem~$Gearrow[fFace]~ArrayItem~$Gearrow[cFaceType]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]\">$Gearrow[cName] $Gearrow[fDMValue] $Gearrow[cType]</option>";
												   }
											   }
											?>
										</select>
									</div>
									<div class="col-sm-6">
									 <label for="cmbGearPartyType">Party Type</label>
									 <select name="cmbGearPartyType" class="form-control" id="cmbGearPartyType" tabindex="6">
											<option value="Expeller">Expeller</option>
											<option value="PowerPress">Power Press</option>
									  </select>
									</div>
									<div class="col-sm-12">
									 <table class="table table-bordered dt-responsive">
										
										
										<tr>
											<td>
												<label for="txtGearTeeth">Teeth :</label>
												<input type="text" name="txtGearTeeth" id="txtGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="7">
												<label for="cmbGearType">Type :</label>
												<select name="cmbGearType" class="form-control" id="cmbGearType" tabindex="8">
													<option value="Plain">Plain</option>
													<option value="Helical">Helical</option>
													<option value="Spur Hobb">Spur Hobb</option>
												</select>
											</td>
											
											<td>
											    <label for="txtGearDia">Dia :</label>
												<input type="text" name="txtGearDia" id="txtGearDia" class="form-control" tabindex="9" onblur="onlyGrams(this.id)">	<br>												
												<select name="cmbGearDiaType" id="cmbGearDiaType" class="form-control" tabindex="10">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												<label for="cmbGearFaceType">Face :</label>
												<input type="text" name="txtGearFace" id="txtGearFace" class="form-control" tabindex="11" onblur="DecimalNum(this.id)">
												<br>
												<select name="cmbGearFaceType" class="form-control" id="cmbGearFaceType" tabindex="12">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												
												<label for="txtGearProcessing">DP/Module :</label>
												<input type="text" name="txtGearProcessing" id="txtGearProcessing" class="form-control" tabindex="13" onblur="DecimalNum(this.id)">
                                                 <br>												
												<select name="cmbGearProcessingType" class="form-control" id="cmbGearProcessingType" tabindex="14">
													<option value="DP">DP</option>
													<option value="Module">Module</option>
												</select>
											</td>
											<td>
												
												<label for="txtGearPcs">No. of Pcs :</label>
												<input type="text" name="txtGearPcs" id="txtGearPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="15">											
											</td>
											<td>
												
												<label for="cmbGearCal">PT/PCS:</label>
												<select name="cmbGearCal" id="cmbGearCal" style="width:100px" class="form-control" tabindex="16">
													<option value="PT">PT</option>
													<option value="PCS">PCS</option>
												</select>
											</td>
											
										</tr>
										<tr>
										 <td colspan="6">
												<input type="Button" name="btnAddGear" id="btnAddGear" value="Add" class="btn btn-primary waves-effect waves-light float-right" onClick="CheckBlankOrder(this.id)" onkeydown="CheckBlankOrder(this.id)" tabindex="17">
											</td>
										</tr>
									</table>
									</div>
									</div>
										  
								</fieldset>
				 </div>
				 <div id="dvpinion" style="display:none;">
								<fieldset style="width:100%;padding:20px;border:1px solid #ABD0BC">
									<legend class="legend">Pinion</legend>
									<div class="row">
									  <div class="col-sm-6">
									  <label for="cmbPinion">Select Pinion</label>
									   <select name="cmbPinion" id="cmbPinion" class="form-control" onchange="selPinion(this.id)" tabindex="18">
													<option value=""><?php print str_repeat("-",20) ?>  Select Pinion  <?=str_repeat("-",20) ?></option>
													<?php
													   
													    $Pinionqry=mysqli_query($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fFace , cFaceType , fDMValue , cType from pinion_master order by iTeeth, cItemType");
													   if ($Pinionqry && mysqli_num_rows($Pinionqry)>0)
													   {
														   while ($Pinionrow=mysqli_fetch_array($Pinionqry))
														   {
															   print "<option value=\"$Pinionrow[iId]~ArrayItem~$Pinionrow[iTeeth]~ArrayItem~$Pinionrow[cItemType]~ArrayItem~$Pinionrow[fDia]~ArrayItem~$Pinionrow[cDiaType]~ArrayItem~$Pinionrow[fFace]~ArrayItem~$Pinionrow[cFaceType]~ArrayItem~$Pinionrow[fDMValue]~ArrayItem~$Pinionrow[cType]\">$Pinionrow[cName] $Pinionrow[fDMValue] $Pinionrow[cType]</option>";
														   }
													   }
													?>
												</select>
									  </div>
									  <div class="col-sm-6">
									    <label for="">Party Type</label>
										<select name="cmbPinionPartyType" id="cmbPinionPartyType" class="form-control" tabindex="19">
													<option value="Expeller">Expeller</option>
													<option value="PowerPress">Power Press</option>
										</select>
									  </div>
									    <div class="col-sm-12">
										    <table class="table table-bordered dt-responsive">
										
										
										<tr>
											<td>
												<label for="txtPinionTeeth">Teeth :</label>
												<input type="text" name="txtPinionTeeth" id="txtPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="20">
												<br>
												<label for="cmbPinionType">Type :</label>
												<select name="cmbPinionType" id="cmbPinionType" class="form-control" tabindex="21">
													<option value="Plain">Plain</option>
													<option value="Helical">Helical</option>
													<option value="Spur Hobb">Spur Hobb</option>
												</select>
											</td>
											
											<td>
											    <label for="txtPinionTeeth">Dia :</label>
												<input type="text" name="txtPinionDia" id="txtPinionDia" class="form-control" tabindex="22" onblur="onlyGrams(this.id)">
                                                <br>												
												<select name="cmbPinionDiaType" id="cmbPinionDiaType" class="form-control" tabindex="23">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
											    <label for="txtPinionFace">Face :</label>
												 <input type="text" name="txtPinionFace" id="txtPinionFace" class="form-control" tabindex="24" onblur="DecimalNum(this.id)">	
                                                <label for="txtPinionCollar">Collar :</label>												
												<input type="text" name="txtPinionCollar" id="txtPinionCollar" class="form-control" tabindex="25">
												<label for="cmbPinionFaceType">Face Type</label>	
												<select name="cmbPinionFaceType" id="cmbPinionFaceType" class="form-control" tabindex="26">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												
												<label for="txtPinionProcessing">DP/Module </label>
												<input type="text" name="txtPinionProcessing" id="txtPinionProcessing" class="form-control" tabindex="27" onblur="DecimalNum(this.id)">
                                                <br>											
												<select name="cmbPinionProcessingType" id="cmbPinionProcessingType" class="form-control" tabindex="28">
													<option value="DP">DP</option>
													<option value="Module">Module</option>
												</select>
											</td>
											<td>
											    <label for="txtPinionPcs">No. of Pcs :</label>
												
												<input type="text" name="txtPinionPcs" id="txtPinionPcs" class="form-control"  onKeyDown="OnlyInt1(this.id)" tabindex="29">
											</td>
											<td>
												<label for="cmbPinionCal">PT/PCS :</label>
												<select name="cmbPinionCal" id="cmbPinionCal" style="width:100px" class="form-control" tabindex="30">
													<option value="PT">PT</option>
													<option value="PCS">PCS</option>
												</select>
											</td>
											
										</tr>
										<tr>
										    <td colspan="6">
												<input type="Button" name="btnAddPinion" id="btnAddPinion" value="Add" class="btn btn-primary waves-effect waves-light float-right" onClick="CheckBlankOrder(this.id)" onkeydown="CheckBlankOrder(this.id)" tabindex="31">
											</td>
										</tr>
									</table>
										</div>
									</div>
									
								</fieldset>
							 </div>
                             <div  id="dvshaftpinion" style="display:none;">
							   <fieldset>
							     <legend>Shaft Pinion</legend>
								 <div class="row">
								   <div class="col-sm-6">
								     <label for="cmbShaftPinion">Choose Shaft Pinion</label>
									 <select name="cmbShaftPinion" id="cmbShaftPinion" class="form-control" onchange="selShaftPinion(this.id)" tabindex="32">
													<option value=""><?=str_repeat("-",20) ?>  Select Shaft Pinion  <?=str_repeat("-",20) ?></option>
													<?php
													
													$result=mysqli_query($con2,"select concat(iTeeth, ' teeth ',cItemType ,' dia ',fDia ,' ',cDiaType , ' face ',fFace ,' ', cFaceType) as cName, iId, iTeeth,cItemType, fDia, cDiaType, fFace , cFaceType, fDMValue , cType from shaft_pinion_master order by iTeeth, cItemType");
													if ($result && mysqli_num_rows($result)>0)
													{
														while ($row=mysqli_fetch_array($result))
														{
															print "<option value=\"$row[iId]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[cItemType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]\">$row[cName] $row[fDMValue] $row[cType]</option>";	
														}
													}
													?>
										</select>
								   </div>
								   <div class="col-sm-6">
								    <label for="cmbShaftPinionPartyType">Pionion Part Type</label>
								     <select name="cmbShaftPinionPartyType" class="form-control" id="cmbShaftPinionPartyType" tabindex="33">
											<option value="Expeller">Expeller</option>
											<option value="PowerPress">Power Press</option>
									 </select>
								   </div>
								   <div class="col-sm-12">
								   <table class="table  dt-responsive">
								    <tr>
											<td>
											    <label for="txtShaftPinionTeeth">Teeth</label>
												<input type="text" name="txtShaftPinionTeeth" id="txtShaftPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="34">															
											    
											    <label for="cmbShaftPinionType">Type </label>
												<select name="cmbShaftPinionType" id="cmbShaftPinionType" class="form-control" tabindex="35">
													<option value="Plain">Plain</option>
													<option value="Helical">Helical</option>
													<option value="Spur Hobb">Spur Hobb</option>
												</select>
											</td>
											<td>
											   
												<label for="txtShaftPinionDia">Dia :</label><br>
												<input type="text" name="txtShaftPinionDia" id="txtShaftPinionDia" class="form-control" tabindex="36" onblur="onlyGrams(this.id)">	
                                                <br>												
												<select name="cmbShaftPinionDiaType" id="cmbShaftPinionDiaType" class="form-control" tabindex="37">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												
												<label for="txtShaftPinionFace">Face</label>
												<input type="text" name="txtShaftPinionFace" id="txtShaftPinionFace" class="form-control" tabindex="38" onblur="DecimalNum(this.id)">	
                                                <br>												
												<select name="cmbShaftPinionFaceType" id="cmbShaftPinionFaceType" class="form-control" tabindex="39">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												
												<label for="txtShaftPinionProcessing">DP/Module</label>
												<input type="text" name="txtShaftPinionProcessing" id="txtShaftPinionProcessing" class="form-control" tabindex="40" onblur="DecimalNum(this.id)">
                                                <br>												
												<select name="cmbShaftPinionProcessingType" id="cmbShaftPinionProcessingType" class="form-control" tabindex="41">
													<option value="DP">DP</option>
													<option value="Module">Module</option>
												</select>
											</td>
											<td>
												
												<label for="txtShaftPinionPcs">No. of Pcs</label>
												<input type="text" name="txtShaftPinionPcs" id="txtShaftPinionPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="42">
											
												
												<label for="cmbShaftPinionCal">PT/PCS</label>
												<select name="cmbShaftPinionCal" id="cmbShaftPinionCal" class="form-control" tabindex="43">
													<option value="PCS">PCS</option>
													<option value="PT">PT</option>
												</select>
											</td>
											 
											 
										</tr>
										<tr>
										  <td colspan="5">
											    <input type="Button" name="btnAddShaftPinion" id="btnAddShaftPinion" value="Add" class="btn btn-primary waves-effect waves-light float-right" onClick="CheckBlankOrder(this.id)" onkeydown="CheckBlankOrder(this.id)" tabindex="44">
											 </td>
										</tr>
								   </table>
								   </div>
								   
								   </div>
							   </fieldset>
							 </div>
							 <div id="dvshaftpinion" style="display:none;">
							   <fieldset align="center" width="770px">
									<legend class="legend">Shaft Pinion</legend>
									<div class="row">
									  <div class="col-sm-6">
									          <label for="cmbShaftPinion">Shaft Pinion</label>
									          <select name="cmbShaftPinion" id="cmbShaftPinion"  onchange="selShaftPinion(this.id)" tabindex="32">
													<option value=""><?=str_repeat("-",20) ?>  Select Shaft Pinion  <?=str_repeat("-",20) ?></option>
													<?php
													
													$result=mysqli_query($con2,"select concat(iTeeth, ' teeth ',cItemType ,' dia ',fDia ,' ',cDiaType , ' face ',fFace ,' ', cFaceType) as cName, iId, iTeeth,cItemType, fDia, cDiaType, fFace , cFaceType, fDMValue , cType from shaft_pinion_master order by iTeeth, cItemType");
													if ($result && mysqli_num_rows($result)>0)
													{
														while ($row=mysqli_fetch_array($result))
														{
															print "<option value=\"$row[iId]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[cItemType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]\">$row[cName] $row[fDMValue] $row[cType]</option>";	
														}
													}
													?>
												</select>
												
									  </div>
									  <div class="col-sm-6">
									  <label for="cmbShaftPinionPartyType">Part Type</label>
									  <select name="cmbShaftPinionPartyType" id="cmbShaftPinionPartyType" tabindex="33">
													<option value="Expeller">Expeller</option>
													<option value="PowerPress">Power Press</option>
									  </select>
									  </div>
									  <div class="col-sm-12">
									    <table class="table table-responsive">
										   <tr>
											<td>
												<label for="txtShaftPinionTeeth">Teeth</label>
												<input type="text" name="txtShaftPinionTeeth" id="txtShaftPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="34">															
											
												
												<label for="cmbShaftPinionType">Type</label>
												<select name="cmbShaftPinionType" id="cmbShaftPinionType" class="form-control" tabindex="35">
													<option value="Plain">Plain</option>
													<option value="Helical">Helical</option>
													<option value="Spur Hobb">Spur Hobb</option>
												</select>
											</td>
											<td>
											    <label for="txtShaftPinionDia">Dia :</label>
												<input type="text" name="txtShaftPinionDia" id="txtShaftPinionDia" class="form-control" tabindex="36" onblur="onlyGrams(this.id)">	<br>															
												<select name="cmbShaftPinionDiaType" id="cmbShaftPinionDiaType" tabindex="37">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												<label for="txtShaftPinionFace">Face</label>
												<input type="text" name="txtShaftPinionFace" id="txtShaftPinionFace" class="form-control" tabindex="38" onblur="DecimalNum(this.id)"><br>													
												<select name="cmbShaftPinionFaceType" id="cmbShaftPinionFaceType" tabindex="39">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												<label for="txtShaftPinionProcessing">DP/Module</label>
												<input type="text" name="txtShaftPinionProcessing" id="txtShaftPinionProcessing" class="form-control" tabindex="40" onblur="DecimalNum(this.id)">	<br>											
												<select name="cmbShaftPinionProcessingType"  class="form-control" id="cmbShaftPinionProcessingType" tabindex="41">
													<option value="DP">DP</option>
													<option value="Module">Module</option>
												</select>
											</td>
											<td>
												<label for="txtShaftPinionPcs">No. of Pcs</label>
												<input type="text" name="txtShaftPinionPcs" id="txtShaftPinionPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="42">
											</td>
											<td>
												<label for="cmbShaftPinionCal">PT/PCS</label>
												<select name="cmbShaftPinionCal" id="cmbShaftPinionCal" tabindex="43">
													<option value="PCS">PCS</option>
													<option value="PT">PT</option>
												</select>
											</td>
											
											 
										</tr>
										<tr>
										  <td colspan="6">
										    <td>
												<input type="Button" name="btnAddShaftPinion" id="btnAddShaftPinion" value="Add" class="btn btn-primary waves-effect waves-light float-right" onClick="CheckBlankOrder(this.id)" onkeydown="CheckBlankOrder(this.id)" tabindex="44">
											 </td>
										  </td>
										</tr>
										</table>
									  </div>
									</div>
							   </fieldset>
							 </div>
							 <div id="dvbevelgear" style="display:none;">
								<fieldset>
									<legend class="legend">Bevel Gear</legend>
									<div class="row">
										     <div class="col-sm-6">
											    <label for="cmbBevelGear">Bevel Gear</label>
											    <select name="cmbBevelGear" id="cmbBevelGear" class="form-control" onChange="selBevelGear(this.id)" tabindex="45">
													<option value=""><?php print str_repeat("-",20) ?>  Select Bevel Gear  <?php print str_repeat("-",20) ?></option>
													<?php
														
														$BevelGearqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from bevel_gear_master order by iTeeth");
														if ($BevelGearqry && mysqli_num_rows($BevelGearqry)>0)
														{
															while ($BevelGearrow=mysqli_fetch_array($BevelGearqry))
															{
																print "<option value=\"$BevelGearrow[iId]~ArrayItem~$BevelGearrow[iTeeth]~ArrayItem~$BevelGearrow[fDia]~ArrayItem~$BevelGearrow[cDiaType]~ArrayItem~$BevelGearrow[fDMValue]~ArrayItem~$BevelGearrow[cType]\">$BevelGearrow[cName] $BevelGearrow[fDMValue] $BevelGearrow[cType]</option>";
															}
														}
													?>
												</select>
												</div>
												<div class="col-sm-6">
												<label for="cmbBevelGearPartyType">Bevel Gear Part Type</label>
												<select name="cmbBevelGearPartyType" class="form-control" id="cmbBevelGearPartyType" tabindex="46">
													<option value="Expeller">Expeller</option>
													<option value="PowerPress">Power Press</option>
												</select>
											</td>
										</div>
										<div class="col-sm-12">
										<table class="table table-responsive">
										
										<tr>
											<td>
												<label for="txtBevelGearTeeth">Teeth</label>
												<input type="text" name="txtBevelGearTeeth" id="txtBevelGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="47">
											</td>
											<td>
											    <label for="txtBevelGearDia">Dia </label>
												<input type="text" name="txtBevelGearDia" id="txtBevelGearDia" class="form-control" tabindex="48" onblur="onlyGrams(this.id)">		<br>														
												<select name="cmbBevelGearDiaType" id="cmbBevelGearDiaType" class="form-control" tabindex="49">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												<label for="txtBevelGearProcessing">DP/Module</label>
												<input type="text" name="txtBevelGearProcessing" id="txtBevelGearProcessing" class="form-control" tabindex="50" onblur="DecimalNum(this.id)"><br>
												<select name="cmbBevelGearProcessingType" id="cmbBevelGearProcessingType"  class="form-control" tabindex="51">
													<option value="DP">DP</option>
													<option value="Module">Module</option>
												</select>
											</td>
											<td>
												<label for="txtBevelGearPcs">No. of Pcs</label>
												<input type="text" name="txtBevelGearPcs" id="txtBevelGearPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="52">
											
												<label for="cmbBevelGearCal">PT/PCS</label>
												<select name="cmbBevelGearCal" id="cmbBevelGearCal" class="form-control" tabindex="53">
													<option value="PT">PT</option>
													<option value="PCS">PCS</option>
												</select>
											</td>
											
											
										</tr>
										<tr>
										 <td colspan="4">
												<input type="Button" name="btnAddBevelGear" id="btnAddBevelGear" value="Add" class="btn btn-primary waves-effect waves-light float-right" onClick="CheckBlankOrder(this.id)" onkeydown="CheckBlankOrder(this.id)" tabindex="54">
											</td>
										</tr>
										</table>
										</div>
									</div>
								</fieldset>
							 </div>
							 <div id="dvbevelpinion" style="display:none;">
								<fieldset>
									<legend class="legend">Bevel Pinion</legend>
									<div class="row">
										  <div class="col-sm-6">
												<select name="cmbBevelPinion" id="cmbBevelPinion" class="form-control" onChange="selBevelPinion(this.id)" tabindex="55">
													<option value=""><?=str_repeat("-",20) ?>  Select Bevel Pinion  <?=str_repeat("-",50) ?></option>
													<?php
														
														$BevelPinionqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from bevel_pinion_master order by iTeeth");
														if ($BevelPinionqry && mysqli_num_rows($BevelPinionqry)>0)
														{
															while ($BevelPinionrow=mysqli_fetch_array($BevelPinionqry))
															{
																print "<option value=\"$BevelPinionrow[iId]~ArrayItem~$BevelPinionrow[iTeeth]~ArrayItem~$BevelPinionrow[fDia]~ArrayItem~$BevelPinionrow[cDiaType]~ArrayItem~$BevelPinionrow[fDMValue]~ArrayItem~$BevelPinionrow[cType]\">$BevelPinionrow[cName] $BevelPinionrow[fDMValue] $BevelPinionrow[cType]</option>";							
															}
														}
													?>
												</select>
												</div>
												<div class="col-sm-6">
												<select name="cmbBevelPinionPartyType" class="form-control" id="cmbBevelPinionPartyType" tabindex="56">
													<option value="Expeller">Expeller</option>
													<option value="PowerPress">Power Press</option>
												</select>
											</td>
										</tr>
										</div>
										<div class="col-sm-12">
										<table class="table table-responsive">
										<tr>
											<td>
												<label for="txtBevelPinionTeeth">Teeth</label>
												<input type="text" name="txtBevelPinionTeeth" id="txtBevelPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="57">
											</td>
											<td>
											    <label for="txtBevelPinionDia">Dia</label>
												<input type="text" name="txtBevelPinionDia" id="txtBevelPinionDia" class="form-control" tabindex="58" onblur="onlyGrams(this.id)">		<br>														
												<select name="cmbBevelPinionDiaType" class="form-control" id="cmbBevelPinionDiaType" tabindex="59">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												<label for="txtBevelPinionProcessing">DP/Module</label>
												<input type="text" name="txtBevelPinionProcessing" id="txtBevelPinionProcessing" class="form-control" tabindex="60" onblur="DecimalNum(this.id)"><br>
												<select name="cmbBevelPinionProcessingType"  class="form-control" id="cmbBevelPinionProcessingType" tabindex="61">
													<option value="DP">DP</option>
													<option value="Module">Module</option>
												</select>
											</td>
											<td>
												<label for="txtBevelPinionPcs">No. of Pcs</label>
												<input type="text" name="txtBevelPinionPcs" id="txtBevelPinionPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="62">													
											
												<label for="txtBevelPinionPcs">PT/PCS</label>
												<select name="cmbBevelPinionCal" id="cmbBevelPinionCal"class="form-control"  tabindex="63">
													<option value="PT">PT</option>
													<option value="PCS">PCS</option>
												</select>
											</td>
											
											
										</tr>
										<tr>
										<td colspan="4">
												<input type="Button" name="btnAddBevelPinion" id="btnAddBevelPinion" value="Add" class="btn btn-primary waves-effect waves-light float-right" onClick="CheckBlankOrder(this.id)" onkeydown="CheckBlankOrder(this.id)" tabindex="64">
										</td>
										</tr>
									</table>
									
									</div>
									</div>
								</fieldset>
							 </div>
							 <div id="dvchainwheel" style="display:none;">
								<fieldset>
									<legend class="legend">Chain Wheel</legend>
									  <div class="row">
									    <div class="col-sm-6">
										
												<select name="cmbChainWheel" id="cmbChainWheel" class="form-control" onchange="selChainGear(this.id)" tabindex="65">
													<option value=""><?php print str_repeat("-",20) ?>  Select Chain Wheel  <?php print str_repeat("-",20) ?></option>
													<?php
														
														$ChainGearqry=mysqli_query($con2,"select concat(iTeeth, ' teeth (',cItemType,') dia ',' ',fDia , ' ',cDiaType , ' pitch ', fPitch ,' ', cPitchType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fPitch , cPitchType  from chain_gear_master order by iTeeth");
														if ($ChainGearqry && mysqli_num_rows($ChainGearqry)>0)
														{
															while ($ChainGearrow=mysqli_fetch_array($ChainGearqry))
															{
																print "<option value=\"$ChainGearrow[iId]~ArrayItem~$ChainGearrow[iTeeth]~ArrayItem~$ChainGearrow[cItemType]~ArrayItem~$ChainGearrow[fDia]~ArrayItem~$ChainGearrow[cDiaType]~ArrayItem~$ChainGearrow[fPitch]~ArrayItem~$ChainGearrow[cPitchType]\">$ChainGearrow[cName]</option>";
															}
														}
													?>
												</select>
									  </div>
									  <div class="col-sm-6">
											<select name="cmbChainGearPartyType" id="cmbChainGearPartyType" class="form-control" tabindex="66">
												<option value="Expeller">Expeller</option>
												<option value="PowerPress">Power Press</option>
											</select>
											
									  </div>
										<div class="col-sm-12">
										<table class="table table-responsive">
										<tr>
											<td>
												<label for="txtChainWheelTeeth">Teeth</label>
												<input type="text" name="txtChainWheelTeeth" id="txtChainWheelTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="67">															
											
												<label for="cmbChainWheelType">Type</label>
												<select name="cmbChainWheelType" id="cmbChainWheelType" class="form-control" tabindex="68">
													<option value="Single">Single</option>
													<option value="Duplex">Duplex</option>
													<option value="Triplex">Triplex</option>
													<option value="Fourplex">Fourplex</option>
												</select>
											</td>
											<td>
												<label for="txtChainWheelDia">Dia</label>
												<input type="text" name="txtChainWheelDia"  id="txtChainWheelDia" class="form-control" tabindex="69" onblur="onlyGrams(this.id)">	<br>													
												<select name="cmbChainWheelDia" id="cmbChainWheelDia" class="form-control" tabindex="70">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												<label for="txtChainWheelDia">Pitch</label>
												<input type="text" name="txtChainWheelPitch" id="txtChainWheelPitch" class="form-control" tabindex="71" onblur="DecimalNum(this.id)"><br>
												<select name="cmbChainWheelPitchType" id="cmbChainWheelPitchType" class="form-control" tabindex="72">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												<label for="txtChainWheelPcs">No. of Pcs</label>
												<input type="text" name="txtChainWheelPcs" id="txtChainWheelPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="73">
											  <label for="txtChainWheelPcs">PT/PCS</label>
												
												<select name="cmbChainWheelCal" id="cmbChainWheelCal" class="form-control" tabindex="74">
													<option value="PT">PT</option>
													<option value="PCS">PCS</option>
												</select>
											</td>
											
											
										</tr>
										<tr>
										 <td colspan="4">
												<input type="Button" name="btnAddChainWheel" id="btnAddChainWheel" value="Add" class="btn btn-primary waves-effect waves-light float-right" onClick="CheckBlankOrder(this.id)" onkeydown="CheckBlankOrder(this.id)" tabindex="75">	
										 </td>
										</tr>
									</table>
									</div>
									</div>
								</fieldset>
							 </div>
							
							 <div id="dvwormgear" style="display:none;">
							 	<fieldset>	
									<legend class="legend">Worm Gear</legend>
                                    <div class="row">
									   <div class="col-sm-6">
									     <select name="cmbWormGear" id="cmbWormGear" class="form-control" onchange="selWormGear(this.id)" tabindex="76">
													<option value=""><?php print str_repeat("-",20) ?> Select Worm Gear <?php print str_repeat("-",20) ?> </option>
													<?php
														
														$WormGearqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from worm_gear_master order by iTeeth");
														if ($WormGearqry && mysqli_num_rows($WormGearqry)>0)
														{
															while ($WormGearrow=mysqli_fetch_array($WormGearqry))
															{
																print "<option value=\"$WormGearrow[iId]~ArrayItem~$WormGearrow[iTeeth]~ArrayItem~$WormGearrow[fDia]~ArrayItem~$WormGearrow[cDiaType]~ArrayItem~$WormGearrow[fDMValue]~ArrayItem~$WormGearrow[cType]\">$WormGearrow[cName] $WormGearrow[fDMValue] $WormGearrow[cType]</option>";							
															}
														}
													?>
											</select>
									   </div>
									   <div class="col-sm-6"></div>
									   <div class="col-sm-12">
									     <table class="table table-responsive">
										
										<tr>
											<td>
												<label for="txtWormGearTeeth">Teeth</label>
												<input type="text" name="txtWormGearTeeth" id="txtWormGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="77">															
												
											</td>
											<td>
											    <label for="txtWormGearDia">Dia </label>
												<input type="text" name="txtWormGearDia" id="txtWormGearDia" class="form-control"  onblur="onlyGrams(this.id)" tabindex="78"><br>																
												<select name="cmbWormGearDiaType" id="cmbWormGearDiaType" class="form-control" tabindex="79">
													<option value="inches">inches</option>
													<option value="mm">mm</option>
												</select>
											</td>
											<td>
												<label for="txtWormGearProcessing">DP/Module </label>
												<input type="text" name="txtWormGearProcessing" id="txtWormGearProcessing" class="form-control" tabindex="80">
												<br>
												<select name="cmbWormGearProcessingType" id="cmbWormGearProcessingType" class="form-control" tabindex="81">
													<option value="DP">DP</option>
													<option value="Module">Module</option>
												</select>
											</td>
											<td>
												<label for="txtWormGearPcs">No. of Pcs </label>
												<input type="text" name="txtWormGearPcs" id="txtWormGearPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="82">													
											
												<label for="cmbWormGearCal">PT/PCS</label>
												<select name="cmbWormGearCal" id="cmbWormGearCal" class="form-control" tabindex="83">
													<option value="PCS">PCS</option>
													<option value="PT">PT</option>
												</select>
											</td>
											
											
										</tr>
										<tr>
										  <td colspan="4">
												<input type="Button" name="btnAddWormGear" id="btnAddWormGear" value="Add" class="btn btn-primary waves-effect waves-light float-right" onClick="CheckBlankOrder(this.id)" onkeydown="CheckBlankOrder(this.id)" tabindex="84">
											</td>
										</tr>
							 		</table>
									   </div>
									</div>									
									
							 	</fieldset>
							 </div>
	<table cellspacing="0" cellpadding="0" width="100%" align="center" class="labeltext">
		
		<tr>
			<td style="padding-left:30px;">
				<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext">
					
					<tr>
						<td  height="25px" colspan="4">
							 	
							 
							 
							 
							 
							 
							 
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div id="dvChallanDetails"></div>
			</td>
		</tr>
				<tr>
			<td>
			<div id="dvVat" style="display:none;">  
				<table class="table table-bordered dt-responsive">
					<tr>
						
						<td style="text-align:right;width:12%">Misc. ItemName :</td><td style="width:13%"><input type="text" name="txtItemName" id="txtItemName"  onchange="CalculateTax()" tabindex="85" value="<?=$MiscItemName;?>"> &nbsp;&nbsp;</td><td style="text-align:right;width:12%">Qty :</td><td style="width:10%"><input type="text" name="txtItemQty" id="txtItemQty" size="7" tabindex="86" onchange="CalculateTax()" value="<?php print $MiscItemQty; ?>" onkeydown="OnlyNumeric1(this.id)"></td>
						<td class="tablecell" style="text-align:left;width:25%"><input type="text" name="txtItemRate" id="txtItemRate" tabindex="87" onChange="CalculateTax()" value="<?=$MiscItemRate; ?>" onkeydown="OnlyNumeric1(this.id)"></td>
						<td class="tablecell" style="text-align:right;width:9%"><label id="lblItemAmt"><?php print $MiscItemAmt; ?></label>&nbsp;</td>
						<td class="tablecell" style="text-align:right;width:10%">&nbsp;</td>
					</tr>
					<tr class="tableheadingleft" style="display:none">	
						
						<td class="tabelcell" style="text-align:right;">Vat % :</td>
						<td class="tablecell" ><input type="text" name="txtVat" id="txtVat" class="form-control" tabindex="88" onChange="CalculateTax()" onblur="CalculateTax()" value="<?php print $VatPer; ?>" onkeydown="OnlyNumeric1(this.id)">%</td>
						<td class="tablecell" style="text-align:left;"><label id="lblVatAmt"><?php print $VatAmt; ?></label>&nbsp;</td>
						<td class="tablecell" style="text-align:right;">&nbsp;</td>
					</tr>
					<tr class="tableheadingleft" style="display:none">	
						
						<td class="tabelcell" style="text-align:right;">Surcharge on Vat %:</td>
						<td class="tablecell" ><input type="text" name="txtSurcharge" id="txtSurcharge" tabindex="89"  onChange="CalculateTax()"  class="form-control" value="<?php print $SurchargePer; ?>" onkeydown="OnlyNumeric1(this.id)">%</td>
						<td class="tablecell" style="text-align:left;"><label id="lblSurchargeAmt"><?php print $SurchargeAmt; ?></label>&nbsp;</td>
						<td class="tablecell" style="text-align:right;">&nbsp;</td>
					</tr>
					<tr class="tableheadingleft" id="CGSTdiv" style="display:none">	
						
						<td class="tabelcell" style="text-align:right;">CGST % :</td>
						<td class="tablecell" ><input type="text" name="txtCGST" id="txtCGST" class="form-control" tabindex="88" onChange="CalculateTax()" onblur="CalculateTax()" value="<?php print $CGSTPer; ?>" onkeydown="OnlyNumeric1(this.id)">%</td>
						<td class="tablecell" style="text-align:left;"><label id="lblCGSTAmt"><?php print $CGSTPer; ?></label>&nbsp;</td>
						<td class="tablecell" style="text-align:right;">&nbsp;</td>
					</tr>
					<tr class="tableheadingleft" id="SGSTdiv" style="display:none">	
						
						<td class="tabelcell" style="text-align:right;">SGST % :</td>
						<td class="tablecell" colspan="2"><input type="text" name="txtSGST" id="txtSGST"  tabindex="88" onChange="CalculateTax()" onblur="CalculateTax()" value="<?php print $SGSTPer; ?>" onkeydown="OnlyNumeric1(this.id)">&nbsp;%</td>
						<td class="tablecell"  class="tablecell" colspan="3" style="text-align:right;"><label id="lblSGSTAmt"><?php print $SGSTPer; ?></label>&nbsp;</td>
						<td class="tablecell" colspan="1" style="text-align:right;">&nbsp;</td>
					</tr>
					<tr class="tableheadingleft" id="IGSTdiv" style="display:none">	
						
						<td class="tabelcell" style="text-align:right;">IGST % :</td>
						<td class="tablecell" style="" colspan="2"><input type="text" name="txtIGST" id="txtIGST"  tabindex="88" onChange="CalculateTax()" onblur="CalculateTax()" value="<?php print $IGSTPer; ?>" onkeydown="OnlyNumeric1(this.id)">&nbsp;%</td>
						<td class="tablecell" colspan="3" style="text-align:right;"><label id="lblIGSTAmt"><?=$IGSTPer; ?></label>&nbsp;</td>
						<td class="tablecell" colspan="1" style="text-align:right;">&nbsp;</td>
					</tr>
					<tr class="tableheadingleft">	
						
						<td class="tabelcell" style="text-align:right;"> Misc. Tax Name :</td>
						<td><input type="text" name="txtMiscName" id="txtMiscName"  tabindex="90" onChange="CalculateTax()" onblur="CalculateTax()" value="<?php print $MiscTaxName ?>"></td>
						
						<td class="tablecell" colspan="2"><input type="text" name="txtMiscTax" id="txtMiscTax"  tabindex="91" onChange="CalculateTax()" onblur="CalculateTax()" value="<?php print $MiscTaxPer ?>" onkeydown="OnlyNumeric1(this.id)">&nbsp;%</td>
						<td class="tablecell" colspan="2" style="text-align:left;"><label id="lblMiscAmt" style="text-align:left;"><?php print $MiscAmt ?></label>&nbsp;</td>
						 <td class="tablecell" style="text-align:right;">&nbsp;</td>
					</tr>
					<tr class="tableheadingleft">	
						<td></td>
						<td class="tabelcell" colspan="4" style="text-align:right;" >Total Amount :</td>
						<input type="hidden" name="hdGTWithoutTax" id="hdGTWithoutTax">
						 <input type="hidden" name="hdTotalWithTax" id="hdTotalWithTax" value="<?php print $TotalWithtax ?>">
						<td class="tablecell"><label id="lblTotalAmt" ><?php print $TotalWithtax ?></label>&nbsp;</td>
						<td></td>
					</tr>
				</table>
			</div>
			</td>
		</tr>

		<tr><td height="25"></td></tr>
		<tr>
			<td style="padding-left:30px;">
				
			</td>
		</tr>
		<tr><td height="25"></td></tr>
		<tr>
			<td style="padding-left:30px;">
				
			</td>
		</tr>	
	</table>
	<div class="row">
	   <div class="col-sm-12">
			<label for="txtRemarks">Remarks</label>
			<textarea name="txtRemarks" id="txtRemarks" class="form-control" tabindex="92"><?php print $Remarks; ?></textarea>
	   </div>
	   <div class="col-sm-12" id="lblErrMsg" style="text-align:center;color:red;font-weight:bold">
	     
	   </div>
	   <br>
	   <div class="col-sm-12">
	     <input type="hidden" name="hdOrderItemRate" id="hdOrderItemRate">
		<input type="hidden" name="hdBillingData" id="hdBillingData" value="<?php print $ItemData;?>">
		<button type="button" name="btnSave" id="btnSave"  class="btn btn-primary waves-effect waves-light" onClick="CheckBlank1()" tabindex="93">Save</button>
		<input type="reset" name="btnReset" id="btnReset" value="Reset" class="btn btn-secondary waves-effect waves-light"  tabindex="94" onKeyDown="EnterKeyClick(this.id)" onClick="Reset()" onBlur="focusFirstElement();">
		<input type="hidden" id='formfocuselement' value='<?php if($PageMode=='New')echo 'cmbFrom'; else echo 'cmbFrom'; ?>'/>
	   </div>
	   
	</div>
</form>
</div>
</div>
<?php
if($PageMode=="Edit"){?>
	<script>
		document.getElementById('SGSTdiv').style.display='table-row';
		document.getElementById('CGSTdiv').style.display='table-row';
		document.getElementById('IGSTdiv').style.display='table-row';
		document.getElementById('txtCGST').disabled=true;
		document.getElementById('txtSGST').disabled=true;
		document.getElementById('txtIGST').disabled=true;
		setTimeout(function() {if(document.getElementById('txtStateCode').value=='03'){
			document.getElementById('IGSTdiv').style.display='none';
			document.getElementById('txtSGST').disabled=false;
			document.getElementById('txtIGST').disabled=false;
document.getElementById('txtCGST').disabled=false;
		}else{
			document.getElementById('SGSTdiv').style.display='none';
			document.getElementById('CGSTdiv').style.display='none';
			document.getElementById('txtIGST').disabled=false;
		}
	}, 300);
</script>
<?php } ?>

