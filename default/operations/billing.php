<?php
	/*
		1. Through this screen we can prepare Invoice. For making Invoice, Item must be present in the Delivery Challan. 
		   For making new Invoice click on 'New Bill' radio button and for editing old invoice click on 'Old Bill'.
		   For making bill you can also enter any other Tax in Misc. tax Col.
		2. We can make a invoice even if delivery challan has not been sent by clicking on 'Without Party Order' radio 
		   button. In this screen also like purchase order we can make New Items.But for this Invoice no record will be saved
		   in the purchase order and delivery challan. 
		3. For making Kacha Invoice  you have to enter the password. In Kacha invoice no Tax will be entered. 
		4. Kacha Invoice entries will be enter in k_party_billing and k_party_billing_detail. 
		5. do not show item if remaining qty is 0  or if all the items have been billed.			
		Table used
		1. party_billing
		2. party_billing_detail
		3. party_account
		
		Files Used
		1. billing.php
		2. biling.js
		3. billing_edit.php
		4. billing_edit_getdc.php
		5. billing_getbilling.php
		6. billing_getdc.php
		7. billing_pendingpdf.php
		8. billing_showbilled.php
		9. billing_showpdf.php
		10. billing_withoutdc.php
		
	*/
	require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="invalid request";
	}
	else if(empty(trim($_POST['txtBillingUser'])))
	{
		$Err=true;
		$ErrMsg="invalid request";
	}
	else
	{
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
		if(base64_decode(base64_decode(trim($_POST['txtBillingUser'])))=='1')
		{
			$PartyBilling="party_billing";
			$PartyBillingDetail="party_billing_detail";
		}
		else if(base64_decode(base64_decode(trim($_POST['txtBillingUser'])))=='2')
		{
			$PartyBilling="k_party_billing";
		    $PartyBillingDetail="k_party_billing_detail";
		}
		else 
		{
			$Err=true;
			$ErrMsg="invalid request";
		}
			$CurrDate=date ("Y-m-d");
			$ToDate=$CurrDate;
			$PageMode="New";
			$Month=$_SESSION['Month'];
			$YearString=$_SESSION['YearString'];
			$firstday = date ("m/d/Y", mktime(0, 0, 0, date("m") , date("d")-15 , date("Y")));
			$startDate=explode("/",$firstday);
			$BeforeDate = $startDate[1]."/".$startDate[0]."/".$startDate[2];	
			$TotalPrice=0;
			$TotalWithtax=0;
			$VatPer=0;
			$CGSTPer=0;
			$SGSTPer=0;
			$IGSTPer=0;
			$MiscTaxPer=0;
			$MiscItemRate=0;
			$MiscItemQty=0;
			$MiscAmt=0;
			$VatAmt=0;
			$SurchargeAmt=0;
			$SurchargePer=0;
			$YearPrefix="";
			$byearprefix="";
			$VatFactor=100;
	}
	
	
	
	
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
	
	
	/*$PPrateQry=mysqli_query($con2,"select fMinValue  from party_master where fMinValue>0 and cPartType ='PowerPress'");	
	if ($PPrateQry && mysqli_num_rows($PPrateQry)>0)
	{
		$PPrateRow=mysqli_fetch_array($PPrateQry);
		$MinValue=$PPrateRow['fMinValue'];
	}
	else
	{
		$MinValue=0;
	}*/
	
	
   // if (isset($_POST['BillingID']) && isset($_POST['PartyID']))
    if (isset($_POST['txtBillingData']))
	{
		
		$BillingID=base64_decode(base64_decode($_POST['txtBillingData']));
		
		$SwitchBill=mysqli_query($con2,"select iBillingType  from $PartyBilling where iBillingID ='$BillingID'");
		if ($SwitchBill)
		{
			if (mysqli_num_rows($SwitchBill)>0)
			{
				$SwitchQry=mysqli_fetch_array($SwitchBill);			
				$DCBilled=$SwitchQry['iBillingType'];
								
				if ($DCBilled==1)
				{
					?>
					<script>
					$.ajax({
								   url:'operations/billing_withoutdc.php',
								   type:'POST',
								   data:{"auth_info":$("#auth_info").val(),"txtBillingUser":$("#txtBillingUser").val(),"iBillingID":'<?=$BillingID?>'},
								   success:function(response)
								   {
									   
									   $('.page-content').html(response);
									   preloadfadeOut();
										   
								   },
								   error:function(response)
								   {
									   
									   if(response.status=='404')
									   {
										   toastr.error("Page not found");
										   preloadfadeOut();
										   
									   }
									   else if(response.status=='500')
									   {
										   toastr.error("Internal server error");
										   preloadfadeOut();
										   
									   }
									   else
									   {
										   toastr.error("Communication error");
										   preloadfadeOut();
										   
									   }
								   }
	})
	                </script>
					
					<?php
				}
			}
		}
		$Billingqry=mysqli_query($con2,"select * from $PartyBilling where iBillingID =\"$BillingID\"");
		
		if ($Billingqry && mysqli_num_rows($Billingqry)>0)
		{
			while ($Billingrow=mysqli_fetch_array($Billingqry))
			{
				$billingDate=$Billingrow['dBillingDate'];
				$cBillingCode=$Billingrow['cBillingCode'];
				$dArr=explode('-',$Billingrow['dBillingDate']);
				$CurrDate="$dArr[2]/$dArr[1]/$dArr[0]";
				$CompanyCode=$Billingrow['iCompanyCode'];
				$CompanySNo=$Billingrow['iCompanySNo'];
				$PartyCode=$Billingrow['iPartyCode'];
				$PartyId=$PartyCode;
				$PartySNo=$Billingrow['iFirmSNo'];
				$tArr=explode('-',$Billingrow['dToDate']);
				$ToDate="$tArr[2]/$tArr[1]/$tArr[0]";
				$VatPer=$Billingrow['fVatPer'];
				$CGSTPer=$Billingrow['iCGSTVal'];
				$SGSTPer=$Billingrow['iSGSTVal'];
				$IGSTPer=$Billingrow['iIGSTVal'];
				$MiscTaxName=$Billingrow['cMiscTaxName'];
				$MiscTaxPer=$Billingrow['fMiscTaxPer'];
				$SurchargePer=$Billingrow['fSurcharge'];
				$Remarks=$Billingrow['cRemarks'];
				$VatFactor=$Billingrow['fVatFactor'];
			}
		}
				// Calculation of Misc item.............
		
		$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp from $PartyBillingDetail where iBillingID ='$BillingID' and iOrderID=0 and cItemType ='MISC'");
		if ($Miscqry)
		{
			if (mysqli_num_rows($Miscqry)>0)
			{
				$Miscrow=mysqli_fetch_array($Miscqry);
				$MiscItemName=$Miscrow['cMiscItemName'];
				$MiscItemRate=$Miscrow['fRate'];
				$MiscItemQty=$Miscrow['iNoPcsDisp'];
				$MiscItemAmt=$MiscItemQty * $MiscItemRate;
				$MiscItemAmt=round($MiscItemAmt,2);
			}	
		}
		$bqry=mysqli_query($con2,"select distinct(iOrderID), cYearPrefix from $PartyBillingDetail where iBillingID ='$BillingID'");
		
		if ($bqry && mysqli_num_rows($bqry)>0)
		{  
			while ($brow=mysqli_fetch_array($bqry))
			{
				$yearprefix=$brow['cYearPrefix'];
				if ($Byearprefix=="old_")
				{
					$result= mysqli_query($con2,"select iOrderID,cOrderCode, dOrderDate, fMinValue from old_party_order where iPartyCode=\"$PartyId\" and iOrderID='$brow[iOrderID]'");
					
				}
				else
				{
					$result= mysqli_query($con2,"select iOrderID,cOrderCode, dOrderDate, fMinValue from party_order where iPartyCode=\"$PartyId\" and iOrderID=\"$brow[iOrderID]\"");
					
				}
				if ($result)
				{
					if(mysqli_num_rows($result)>0)
					{
						while ($row=mysqli_fetch_array($result))
						{
							$OrderId=$row['iOrderID'];	
							$OrderCode=$row['cOrderCode'];
							$dArr=explode("-",$row['dOrderDate']);
							$OrderDate="$dArr[2]/$dArr[1]/$dArr[0]";
							$MinValue=$row['fMinValue'];
							
							$ItemData=$OrderCode."~Caption~".$OrderId."~Caption~".$OrderDate."~Caption~".$MinValue."~Caption~".$yearprefix."~Heading~"; 
						
							
							$itemqry=mysqli_query($con2,"select * from $PartyBillingDetail where iOrderID=\"$OrderId\" and iBillingID =\"$BillingID\"");
							if ($itemqry && mysqli_num_rows($itemqry)>0)
							{
								while ($itemrow=mysqli_fetch_array($itemqry))
								{
									$ItemType=$itemrow['cItemType'];
									$ItemCode=$itemrow['iItemCode'];
									$Rate=$itemrow['fRate'];
									$Disp=$itemrow['iNoPcsDisp'];
									$PartType=$itemrow['cPartType'];
									$Pcs=$itemrow['cPcs'];
									$Collar=$itemrow['cCollar'];
									
									
									if ($Byearprefix=="old_")
									{
										$OrderItem=mysqli_query($con2,"select iNoPcsRec, iNoPcsDisp , cItemRemarks from old_party_order_detail where iOrderID='$OrderId' and cItemType='$ItemType' and iItemCode='$ItemCode' and cPartType='$PartType' and cPcs='$Pcs'");
									}
									else
									{
										$OrderItem=mysqli_query($con2,"select iNoPcsRec, iNoPcsDisp , cItemRemarks from party_order_detail where iOrderID='$OrderId' and cItemType='$ItemType' and iItemCode='$ItemCode' and cPartType=\"$PartType\" and cPcs=\"$Pcs\"");
									}
										// Order Qty..............
									if ($OrderItem && mysqli_num_rows($OrderItem)>0)
									{
										$OrderItemrow=mysqli_fetch_array($OrderItem);
										$TotalQtyRec=$OrderItemrow['iNoPcsRec'];
										$TotalDisp=$OrderItemrow['iNoPcsDisp'];
										$OrderItemRemarks=$OrderItemrow['cItemRemarks'];
									}
									
										// Challan Qty Return..............
										if ($yearprefix="")
											$Challanqry1=mysqli_query($con2,"select sum(iNoPcsReturn) as PcsReturn  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderId' and cPartType='$PartType' and cPcs='$Pcs' group by cItemType, iItemCode ") ;		
										else
											$Challanqry1=mysqli_query($con2,"select sum(PcsReturn) as PcsReturn , cItemType, iItemCode from (select sum(iNoPcsReturn) as PcsReturn ,cItemType, iItemCode  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderId' and cPartType='$PartType' and cPcs='$Pcs' and cYearPrefix='$yearprefix'   group by cItemType, iItemCode) as tbl group by tbl.cItemType, tbl.iItemCode");		
									
									if ($Challanqry1)
									{
										if ($Challanqry1 && mysqli_num_rows($Challanqry1)>0)
										{
											$Challanrow1=mysqli_fetch_array($Challanqry1);
											$Return=$Challanrow1['PcsReturn'];
										}
										else
										{
											$Return=0;
										}
									}
									else
									{
										echo "ERROR:".mysql_error();
									}
									$TotalDisp=$TotalDisp - $Return;
										// Billed Qty (excluding this Billing)...... 
									
										$Billingqry=mysqli_query($con2,"select sum(iNoPcsDisp) as billed  from $PartyBilling join $PartyBillingDetail on $PartyBilling.iBillingID = $PartyBillingDetail.iBillingID  where iOrderID ='$OrderId' and cItemType='$ItemType' and iItemCode ='$ItemCode'and cPartType='$PartType' and cPcs='$Pcs' and $PartyBilling.iBillingID<>'$BillingID' group by $PartyBilling.iBillingID") or die(mysql_error());
											
									if ($Billingqry && mysqli_num_rows($Billingqry)>0)
									{
										while ($Billingrow=mysqli_fetch_array($Billingqry))
										{
											$BilledQty=$BilledQty + $Billingrow['billed'];
										}
									}
									else
									{
										$BilledQty=0;
									}

									if ($ItemType=="Gear")
									{
										$Itemqry=mysqli_query($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ,' (',fDMValue , cType,')') as cName,iTeeth , cItemType  from gear_master where iId ='$ItemCode'");
										if ($Itemqry && mysqli_num_rows($Itemqry)>0)
										{	
											$Itemrow=mysqli_fetch_array($Itemqry);
											$ItemName=$Itemrow['cName'];
										}
										if ($Pcs=="PT")
											$Price=$Disp*$Itemrow['iTeeth']* $Rate; 
										else
											$Price=$Disp*$Rate; 
										
									}
									else if ($ItemType=="Pinion")
									{
										$Itemqry=mysqli_query($con2,"select iTeeth , cItemType ,fDia ,cDiaType , fFace ,cFaceType , iTeeth , cItemType, fDMValue , cType  from pinion_master where iId='$ItemCode'");	
										if ($Itemqry && mysqli_num_rows($Itemqry)>0)
										{	
											$Itemrow=mysqli_fetch_array($Itemqry);
											$ItemName=$Itemrow['iTeeth'].' teeth '.$Itemrow['cItemType'].' dia '.$Itemrow['fDia'].' '.$Itemrow['cDiaType'].' face '.$Itemrow['fFace'].' ('.$Itemrow['fDMValue'].$Itemrow['cType'].')';
											if ($Collar!="")
											{
												$ItemName=$ItemName." + ".$Collar;	
											}
											$ItemName=$ItemName.' '.$Itemrow['cFaceType'];
										}
										if ($Pcs=="PT")
											$Price=$Disp*$Itemrow['iTeeth']* $Rate; 
										else
											$Price=$Disp* $Rate; 
										$IPrice=$Price/$Disp;
										if ($PartType=="PowerPress")
										{
											if ($IPrice<$MinValue)
											{
												$Price=$MinValue*$Disp;
											}
										}
									}
									else if ($ItemType=="Bevel Pinion")
									{
										$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType,'(', fDMValue , cType,')') as cName , iTeeth , '' as cItemType from bevel_pinion_master where iId='$ItemCode'");			
										if ($Itemqry && mysqli_num_rows($Itemqry)>0)
										{	
											$Itemrow=mysqli_fetch_array($Itemqry);
											$ItemName=$Itemrow['cName'];
										}
										if ($Pcs=="PT")
											$Price=$Disp * $Rate * $Itemrow['iTeeth'];
										else
											$Price=$Disp * $Rate;
									}
									else if ($ItemType=="Bevel Gear")
									{
										$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType,'(', fDMValue , cType,')') as cName , iTeeth , '' as cItemType from bevel_gear_master where iId='$ItemCode'");			
										if ($Itemqry && mysqli_num_rows($Itemqry)>0)
										{	
											$Itemrow=mysqli_fetch_array($Itemqry);
											$ItemName=$Itemrow['cName'];
										}
										if ($Pcs=="PT")
											$Price=$Disp * $Rate * $Itemrow['iTeeth'];
										else
											$Price=$Disp * $Rate;	
									}
									else if ($ItemType=="Shaft Pinion")
									{
										$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth ',cItemType ,' dia ',fDia ,' ',cDiaType , ' face ',fFace ,' ', cFaceType,'(', fDMValue , cType,')') as cName, iTeeth, cItemType from shaft_pinion_master where iId='$ItemCode'");
										if ($Itemqry && mysqli_num_rows($Itemqry)>0)
										{	
											$Itemrow=mysqli_fetch_array($Itemqry);
											$ItemName=$Itemrow['cName'];
										}
										if ($Pcs=="PCS")
											$Price=$Disp * $Rate;
										else
											$Price=$Disp * $Rate * $Itemrow['iTeeth'];
									}
									else if ($ItemType=="Chain Wheel")
									{
										$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia , ' ',cDiaType , ' pitch ', fPitch ,' ', cPitchType , ' (',cItemType ,')') as cName, iTeeth , cItemType from chain_gear_master where iId='$ItemCode'");
										if ($Itemqry && mysqli_num_rows($Itemqry)>0)
										{	
											$Itemrow=mysqli_fetch_array($Itemqry);
											$ItemName=$Itemrow['cName'];
										}
										if ($Pcs=="PT")
											$Price=$Disp * $Itemrow['iTeeth']*$Rate;
										else
											$Price=$Disp * $Rate;
									}
									else if ($ItemType=="Worm Gear")
									{
										$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType,'(', fDMValue , cType,')') as cName,iTeeth , '' as cItemType from worm_gear_master where iId='$ItemCode'");			
										if ($Itemqry && mysqli_num_rows($Itemqry)>0)
										{	
											$Itemrow=mysqli_fetch_array($Itemqry);
											$ItemName=$Itemrow['cName'];
										}
										if ($Pcs=="PCS")
											$Price=$Disp * $Rate ;
										else
											$Price=$Disp * $Rate * $Itemrow['iTeeth'];
									}
									
									$TotalPrice=$TotalPrice + $Price;
									if ($Data=="")
									{
										$Data="$ItemType~ArrayItem~$ItemCode~ArrayItem~$ItemName~ArrayItem~$Itemrow[iTeeth]~ArrayItem~$TotalQtyRec~ArrayItem~$TotalDisp~ArrayItem~$PartType~ArrayItem~$BilledQty~ArrayItem~$Disp~ArrayItem~$Rate~ArrayItem~$Price~ArrayItem~$Itemrow[cItemType]~ArrayItem~$Pcs~ArrayItem~$Collar~ArrayItem~$OrderItemRemarks";
									}
									else
									{
										$Data=$Data."~Array~$ItemType~ArrayItem~$ItemCode~ArrayItem~$ItemName~ArrayItem~$Itemrow[iTeeth]~ArrayItem~$TotalQtyRec~ArrayItem~$TotalDisp~ArrayItem~$PartType~ArrayItem~$BilledQty~ArrayItem~$Disp~ArrayItem~$Rate~ArrayItem~$Price~ArrayItem~$Itemrow[cItemType]~ArrayItem~$Pcs~ArrayItem~$Collar~ArrayItem~$OrderItemRemarks";
									}
								}
							}
							if ($ItemArray=="")
							{
								$ItemArray=$ItemData.$Data;
							}
							else
							{
								$ItemArray=$ItemArray."~ItemData~".$ItemData.$Data;
							}
							$Data="";
						}
					}
				}
			}
			$TotalPrice=$TotalPrice + MiscItemAmt;
			$VatAmt=round((($TotalPrice*$VatFactor/100 * $VatPer)/100),2);
			$MiscAmt=round((($TotalPrice * $MiscTaxPer)/100),2);
			$SurchargeAmt=round((($VatAmt * $SurchargePer)/100),2);
			$TotalWithtax= ($TotalPrice +  $VatAmt +  $MiscAmt + $SurchargeAmt);
		}
		$PageMode="Edit";
	}
	else
	{
		$PageMode="New";
	}

	
?>

<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script type="text/javascript" language="javascript" src="../js/string.js"></script>
<script type="text/javascript" language="javascript" src="../js/typeaheadcombo.js"></script>
<script type="text/javascript" language="javascript" src="../js/billing.js"></script>
<script>
$("document").ready(function(){
	PageLoad2();
})
</script>
<script>
function shiftFocus()
{
	document.getElementById("btnSave").focus();

}
</script>

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
<form name="frmBilling1" id="frmBilling1" method="POST">
<input type="hidden" name="txtBillingUser" id="txtBillingUser" value="<?=trim($_POST['txtBillingUser'])?>">
<input type="hidden" name="hdVatFactor" id="hdVatFactor" value="<?=$VatFactor?>">
<input type="hidden" name="auth_info" id="auth_info" value="<?=trim($_POST['auth_info'])?>">
<input type="hidden" name="hdPartySNo" id="hdPartySNo" value="<?=$PartySNo?>">
<input type="hidden" name="hdPageMode" id="hdPageMode" value="<?=$PageMode ?>">
<input type="hidden" name="hdMinValue"	id="hdMinValue" value="<?=$MinValue ?>"> 	
<input type="hidden" name="hfCurrDate" id="hfCurrDate" value="selDCDetails('cmbParty')">
<input type="hidden" name="hdTotalWithTax" id="hdTotalWithTax" value="<?=$TotalWithtax ?>">
<input type="hidden" name="hdBillingData" id="hdBillingData" value="<?=$ItemArray;?>">
<input type="hidden" id='formfocuselement' value="<?=($PageMode=='New')?'cmbFrom':'cmbFrom'; ?>"/>
<input type="hidden" name="cmbBilling" id="cmbBilling" value="<?= base64_encode(base64_encode($BillingID."~".$cBillingCode))?>"> 
    <div class="row">
	   <div class="col-md-4">
	    <span class="float-left">
	      <input type="radio" name="rdb" id="rdNew" value="New" checked onClick="Switch(this.id)">&nbsp;New Billing
		  <input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)">&nbsp;List Billing
		  <br>
		  <input type="radio" name="rdc" id="rdWithChallan" value="With Challan" checked onClick="SwitchDC(this.id)">&nbsp;From Party Order
				<input type="radio" name="rdc" id="rdWithoutChallan" value="Without Challan" onClick="SwitchDC(this.id)">&nbsp;Without Party Order
		</span>
	   </div>
	   <div class="col-md-8">
	     <span class="float-right" id="tdLastBillNo">
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
	<br>
	<div class="row">
	  <div class="col-md-6">
	  <fieldset>
	    <legend></legend>
	  
	   <label for="cmbParty">Party List</label>
	   <select name="cmbParty" id="cmbParty" class="form-control"  tabindex=1 onchange="SelParty('');" <?=($PageMode=='Edit')?'disabled':''?>>
								<option value=""><?php print str_repeat("-",20) ?>Select Party<?php print str_repeat("-",20) ?></option>
								<?php
									
									$result=mysqli_query($con2,"select (iPartyID) as PartyID, iPartyID , cStateCode, cPartyName from party_master order by cPartyName");
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
											print "<option value=\"$row[PartyID]~ArrayItem~$PartyData~ArrayItem~''~ArrayItem~$row[cStateCode]\"";
											if ($PartyId==$row['PartyID'])	print "selected";
											print ">$row[cPartyName]</option>";
											$PartyData="";
										}
									}
								?>
							</select>
							
							<label for="txtStateCode">State Code</label>
							<input type="text" name="txtStateCode" id="txtStateCode"  value="<?php print $stateCode; ?>" class="form-control" onFocus="this.blur()" readonly>
							<label for="cmbFrom">From</label>
							<select name="cmbFrom" id="cmbFrom" class="form-control" tabindex="2" onchange="ChangeLastBill(this.id)">
								<option value=""><?php print str_repeat("-",20) ?>Select Company<?php print str_repeat("-",20) ?></option>
								<?php
									//require('../general/dbconnect.php');
									$result=mysqli_query($con2,"select company_master.iPartyID, (0) as iSNo, concat(iPartyID,'~ArrayItem~','0') as PartyID , cPartyName as cPartyName from company_master UNION select  company_master_detail.iPartyID, iSNo,concat(iPartyID,'~ArrayItem~',iSNo) as PartyID , cFirmName as cPartyName from company_master_detail ");
									if ($result && mysqli_num_rows($result)>0)
									{
										//if ($_POST['CompanySNo']=="") $CompanySNo=0 ;else $CompanySNo=$_POST['CompanySNo'];
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
		 </fieldset>
	  </div>
     <div class="col-md-6">
	    <fieldset>
		  <legend></legend>
		  <label for="txtToDate">To</label>
		  <input type="date" name="txtToDate" id="txtToDate"  value="<?=$ToDate; ?>" class="form-control" onFocus="this.blur()" readonly>
		  <label for="">Billing Date</label>
		  <input type="date" name="txtBillingDate" id="txtBillingDate"  value="<?=(!empty($billingDate))?$billingDate:$CurrDate ?>" class="form-control" onFocus="this.blur()" readonly>
							
		  <label for="">To</label>
			  <select name="cmbTo" id="cmbTo" class="form-control" tabindex="3">
				<option value=""><?php print str_repeat("-",20) ?>Select Party<?php print str_repeat("-",20) ?></option>
			  </select>
		</fieldset>
	 </div>
	</div>
	<br>
	<div class="row">
	  <div class="col-sm-12">
	     <button type="button" name="btnGetData" id="btnGetData"  class="btn btn-primary waves-effect waves-light float-right" onclick="selDCDetails('cmbParty')" >Get Data</button>
	  </div>
	</div>
	<div class="row">
	  <div class="col-sm-12" id="dvChallanDetails"></div>
	  <div id="dvVat" class="col-sm-12" style="display:none;">  
				<table class="table table-bordered dt-responsive">
					<tr class="tableheadingleft">
						<td colspan="3" style="text-align:right">Misc. ItemName :</td>
			            <td colspan="2">
             		  	 <input  type="text" name="txtItemName" id="txtItemName" class="form-control"  onchange="CalculateTax()"  value="<?=$MiscItemName;?>" /></td>
						 <td  style="text-align:right;" width="17%"><input type="text" name="txtItemQty" id="txtItemQty" class="form-control"  onchange="CalculateTax()" value="<?=$MiscItemQty; ?>" onkeydown="OnlyNumeric1(this.id)"></td>
						 <td  style="text-align:right;" width="10%"><input type="text" name="txtItemRate" id="txtItemRate" class="form-control"  onChange="CalculateTax()" value="<?=$MiscItemRate; ?>" onkeydown="OnlyNumeric1(this.id)"></td>
						 <td class="tablecell" width="10%" style="text-align:right;"><label id="lblItemAmt"><?=$MiscItemAmt; ?></label>&nbsp;</td>
					</tr>
					<tr class="tableheadingleft" style="display:none">	
						<td colspan="3" style="text-align:right">Vat % :</td>
						<td colspan="3"></td>
						
						<td class="tablecell" style="text-align:right;" width="10%"><input type="text" name="txtVat" id="txtVat" class="form-control"  onChange="CalculateTax()"  value="<?=$VatPer; ?>" onkeydown="OnlyNumeric1(this.id)"></td>
						<td class="tablecell" style="text-align:right;" width="10%"><label id="lblVatAmt"><?php print $VatAmt; ?></label>&nbsp;</td>
					</tr>
					<tr class="tableheadingleft" style="display:none">
                        <td colspan="3" style="text-align:right">Surcharge on Vat %:</td>
						<td colspan="3"></td>					
						<td width="10%" style="text-align:right;"><input type="text" name="txtSurcharge" id="txtSurcharge" class="form-control"  onChange="CalculateTax()"  value="<?php print $SurchargePer; ?>" onkeydown="OnlyNumeric1(this.id)"></td>
						<td width="10%" style="text-align:right;"><label id="lblSurchargeAmt"><?=$SurchargeAmt; ?></label>&nbsp;</td>
					</tr>
					<tr class="tableheadingleft" id="CGSTdiv" style="display:none">
                        
                        <td colspan="3" style="text-align:right">CGST % :</td>
						<td colspan="3"></td>                        
						<td width="10%"><input type="text" name="txtCGST" id="txtCGST" class="form-control" tabindex="88" onChange="CalculateTax()" onblur="CalculateTax()" value="<?=$CGSTPer; ?>" onkeydown="OnlyNumeric1(this.id)">%</td>
						<td width="10%" style="text-align:right;"><label id="lblCGSTAmt"><?=$CGSTPer; ?></label>&nbsp;</td>
						
					</tr>
					<tr class="tableheadingleft" id="SGSTdiv" style="display:none">	
					
						<td colspan="3" style="text-align:right">SGST % :</td>
						<td colspan="3"></td>  
						
						<td width="10%"><input type="text" name="txtSGST" id="txtSGST" class="form-control" tabindex="88" onChange="CalculateTax()" onblur="CalculateTax()" value="<?=$SGSTPer; ?>" onkeydown="OnlyNumeric1(this.id)"></td>
						<td width="10%" style="text-align:right;"><label id="lblSGSTAmt"><?php print $SGSTPer; ?></label>&nbsp;</td>
						
					</tr>
					<tr id="IGSTdiv" style="display:none">	
						<td colspan="3" style="text-align:right">IGST % :</td>
						<td colspan="3"></td>
						
						<td width="10%" ><input type="text" name="txtIGST" id="txtIGST" class="form-control" tabindex="88" onChange="CalculateTax()" onblur="CalculateTax()" value="<?php print $IGSTPer; ?>" onkeydown="OnlyNumeric1(this.id)"></td>
						<td width="10%" style="text-align:right;"><label id="lblIGSTAmt"><?=$IGSTPer; ?></label>&nbsp;</td>
						
					</tr>
					<tr>	
						<td colspan="3" style="text-align:right">Misc. Tax Name :</td>
						<td colspan="2"><input type="text" name="txtMiscName" id="txtMiscName" class="form-control"  onChange="CalculateTax()"  value="<?php print $MiscTaxName ?>">
						</td>
						<td width="15%"></td>
						
              			<td width="10%" style="text-align:right;"><input type="text" name="txtMiscTax" id="txtMiscTax" class="form-control"  onchange="CalculateTax()" onblur="document.getElementById('txtRemarks').focus()" value="<?=$MiscTaxPer ?>" placeholder="%" onkeydown="OnlyNumeric1(this.id)" /></td>
						<td width="10%" style="text-align:right;"><label id="lblMiscAmt" style="text-align:right;"><?=$MiscAmt ?></label>&nbsp;</td>
					</tr>
					<tr>	
						
						<td colspan="7" style="text-align:right;">Total Amount :</td>
						
						 <td width="10%" style="text-align:right;"><label id="lblTotalAmt" ><?php print $TotalWithtax ?></label>&nbsp;</td>
					</tr>
				</table>
			</div>
	</div>
	<div class="row">
	  <div class="col-sm-12" id="lblErrMsg" style="color:red;text-align:center;font-weight:bold;"></div>
	</div>
	<div class="row">
	   <div class="col-sm-12">
	     <input type="button" id="btnSpecialItem" name="btnSpecialItem" value="Special Items" style="display:none;" class="btn btn-primary waves-effect waves-light float-right" onclick="ShowSpecialItem()">	
	   </div>
	</div>
	<div class="row">
	  <div class="col-sm-6">
	    <label for="">Remarks</label>
		<textarea name="txtRemarks" id="txtRemarks" class="form-control" tabindex="4"><?=$Remarks; ?></textarea>
	  </div>
	  <div class="col-sm-6 float-right" style="display:none;" id="dvPendingItem">
	    <?php
					echo "<input type=\"button\" name=\"btnPrint\" style=\"margin-top:30px\" id=\"btnPrint\" value=\"Pending Item List\" class=\"btn btn-primary waves-effect waves-light\" onBlur=\"shiftFocus()\" onclick=\"OpenPDF('$user', '$pass', '$db')\"/>";
		?>
	  </div>
	</div>
	<br>
	<div class="row">
	  <div class="col-md-12">
	     <button type="button" name="btnSave" id="btnSave"  class="btn btn-primary waves-effect waves-light" onClick="CheckBlank()" tabindex="5">Save</button>
		 <input type="reset" name="btnReset" id="btnReset" value="Reset" class="btn btn-secondary waves-effect waves-light"  tabindex="6" onKeyDown="EnterKeyClick(this.id)" onClick="Reset()" onBlur="focusFirstElement();">
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
		}else{
			document.getElementById('SGSTdiv').style.display='none';
			document.getElementById('CGSTdiv').style.display='none';
			document.getElementById('txtIGST').disabled=false;
		}
	}, 300);
</script>
<?php } ?>
