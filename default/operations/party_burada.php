<?php			
	/*																					
		This screen is used to enter the Sale of Burada
		
		Table Used
		1. burada
		
		Files Used
		1. party_burada.php
		2. party_burada.js
		3. party_burada_edit.php
	*/
	require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="Invalid request";
	}
	else
	{
		$CurrDate=date ("Y-m-d");
		$PageMode="New";
		$Month=$_SESSION['Month'];
		$YearString=$_SESSION['YearString'];
		$firstday = date ("m/d/Y", mktime(0, 0, 0, date("m") , date("d")-15 , date("Y")));
		$startDate=explode("/",$firstday);
		$BeforeDate = $startDate[1]."/".$startDate[0]."/".$startDate[2];	
		$VatFactor=100;
		
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
		
		if (isset($_POST['iBillingID']))
			{
				$BillingID=base64_decode(base64_decode($_POST['iBillingID']));
				$result=mysqli_query($con2,"select * from party_billing where iBillingID=\"$BillingID\"");
				if ($result && mysqli_num_rows($result)>0)
				{
					$row=mysqli_fetch_array($result);
					$BillDate=$row['dBillingDate'];
					$dArr=explode("-",$BillDate);
					$CurrDate=$BillDate;
					$CompanyCode=$row['iCompanyCode'];
					$CompanySNo=$row['iCompanySNo'];
					$PartyCode=$row['iPartyCode'];
					$FirmSNo=$row['iFirmSNo'];
					$PartySNo=$FirmSNo;
					/*$Qty=$row['iMiscItemQty'];
					$Rate=$row['fMiscItemRate'];*/
					$VatFactor=$row['fVatFactor'];
					$VatPer=$row['fVatPer'];
					//$BuradaRemarks=$row['cMiscItemName'];
					$TotalAmt=$row['fBillAmt'];
					$CGST=$row['iCGSTVal'];
					$SGST=$row['iSGSTVal'];
					$IGST=$row['iIGSTVal'];
					$Remarks=$row['cRemarks'];
				}
				$miscqry=mysqli_query($con2,"select * from party_billing_detail where iBillingID=\"$BillingID\"");
				if ($miscqry && mysqli_num_rows($miscqry)>0)
				{
					$miscrow=mysqli_fetch_array($miscqry);
					$Qty=$miscrow['iNoPcsDisp'];
					$BuradaRemarks=$miscrow['cMiscItemName'];
					$Rate=$miscrow['fRate'];
				}
				else
				{
					$Err=true;
					$ErrMsg="Sorry something went wrong";
				}
				$PageMode="Edit";
			}
			else
			{
				$PageMode="New";
			}
	}
	
	
	/*
		To implement vat calculation on 50% of total value.
		fVat factor 50% added to table vat_factor
		logic written by shravan dated 01/10/10
	
	*/
	
	/*$result=mysql_query("select fVatFactor from vat_factor");
	if($result && mysqli_num_rows($result)>0)
	{
		$row=mysqli_fetch_array($result);
		$VatFactor=$row['fVatFactor'];
	}
	else
	{
		$Err=true;
		$ErrMsg="Error in getting vat factor...";
	}*/
	

	
?>
<script>
$("#document").ready(function(){
	PageLoad2();
})
</script>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script type="text/javascript" language="javascript" src="../js/string.js"></script>
<script type="text/javascript" language="javascript" src="../js/typeaheadcombo.js"></script>
<script type="text/javascript" language="javascript" src="../js/party_burada.js"></script>
<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Operations</h4>
        <div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Burada sale</li>
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
		<form name="frmBurada" id="frmBurada" method="POST" action="">
		<input type="hidden" name="hdVatFactor" id="hdVatFactor" value="<?=$VatFactor?>">
		<input type="hidden" name="hdPartySNo" id="hdPartySNo" value="<?=$PartySNo?>">
		<input type="hidden" name="hdPageMode" id="hdPageMode" value="<?=$PageMode ?>">
		<input type="hidden" name="auth_info" id="auth_info" value="<?=$_POST['auth_info']?>">
        <div class="row">
		  <div class="col-sm-6">
		        <input type="radio" name="rdb" id="rdNew" value="New" checked onClick="Switch(this.id)">&nbsp;New Burada Sale
				<input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)">&nbsp;List Burada Sale
		  </div> 
		  <div class="col-sm-6" id="tdLastBillNo">
		   <span class="float-right">
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
		  <?php
				  if (isset($_POST['iBillingID']))
				  {
			?>
				<div id="dvPendingBilling" class="col-sm-6">
				Billing : 	<br>
				<select name="cmbBilling" id="cmbBilling"  class="form-control">
					<option value=""><?php print str_repeat("-",20) ?>Select Billing<?php print str_repeat("-",20) ?></option>
					<?php
						
						$Orderqry=mysqli_query($con2,"SELECT distinct(party_billing.iBillingID), cBillingCode, dBillingDate , iPartyCode , iFirmSNo , cRemarks FROM `party_billing` where iBillingType  ='2' order by iBillingID Desc");
						if ($Orderqry)
						{
							if (mysqli_num_rows($Orderqry)>0)
							{
								while ($Orderrow=mysqli_fetch_array($Orderqry))
								{
									print "<option value=\"$Orderrow[iBillingID]~ArrayItem~$Orderrow[cBillingCode]\"";
									if ($Orderrow['iBillingID']==base64_decode(base64_decode($_POST['iBillingID']))) print "selected";								
									print ">$Orderrow[cBillingCode]</option>";
								}
							}
						}
					?>
				</select>
				</div>
				<div class="col-sm-6"></div>
				<?php
					  }
				?>
		</div>
		<div class="row">
		  <div class="col-sm-6">
		    <label for="cmbParty">Party List</label>
		    <select name="cmbParty" id="cmbParty" class="form-control" tabindex="1" onchange="SelParty(this.id)">
								<option value=""><?php print str_repeat("-",20) ?>Select Party<?php print str_repeat("-",20) ?></option>
								<?php
									
									$result=mysqli_query($con2,"select (iPartyID) as PartyID, iPartyID ,cStateCode, cPartyName from party_master order by cPartyName");
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
											print "<option value=\"$row[PartyID]~ArrayItem~$PartyData~ArrayItem~$row[cStateCode]\"";
											if ($PartyCode==$row['PartyID'])	print "selected";
											print ">$row[cPartyName]</option>";
											$PartyData="";
										}
									}
								?>
							</select>
		  </div>
		  <div class="col-sm-6">
		   <input type="hidden" name="hfCurrDate" id="hfCurrDate" value="">
		   <label for="txtToDate">Date</label>
		   <input type="date" name="txtToDate" id="txtToDate"  value="<?=$CurrDate; ?>" class="form-control" onFocus="this.blur()" readonly>
							
		  </div>
		  <div class="col-sm-6">
		  <label for="">State Code</label>
		    <input type="text" name="txtStateCode" id="txtStateCode"  class="form-control" value="<?=$stateCode; ?>"  onFocus="this.blur()" readonly>
		  </div>
		   <div class="col-sm-6"></div>
		   <div class="col-sm-6">
		     <label for="">From</label>
		     <select name="cmbFrom" id="cmbFrom" class="form-control" tabindex="2" onchange="ChangeLastBill(this.id)">
					<option value="cmbFrom"><?=str_repeat("-",20) ?>Select Company<?=str_repeat("-",20) ?></option>
					<?php
						
						$result=mysqli_query($con2,"select company_master.iPartyID, (0) as iSNo, concat(iPartyID,'~ArrayItem~','0') as PartyID , cPartyName as cPartyName from company_master UNION select  company_master_detail.iPartyID, iSNo,concat(iPartyID,'~ArrayItem~',iSNo) as PartyID , cFirmName as cPartyName from company_master_detail ");
						if ($result && mysqli_num_rows($result)>0)
						{
							//if ($_GET['CompanySNo']=="") $CompanySNo=0 ;else $CompanySNo=$_GET['CompanySNo'];
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
		   <div class="col-sm-12">
		     <table class="table table-bordered dt-responsive nowrap no-footer dtr-inline collapsed dataTable" width="100%">
			   <thead>
			     <tr>
				   <td>Burada Remarks</td>
				   <td>Burada Qty</td>
				   <td>Rate</td>
				   <td>Value</td>
				   
				 </tr>
			   </thead>
			   <tbody>
			      <tr>
				   <td><input type="text" name="txtBuradaRemarks" id="txtBuradaRemarks" tabindex="4" value="<?php print $BuradaRemarks; ?>" size="30"></td>
				   <td><input type="text" name="txtBuradaQty" id="txtBuradaQty"  tabindex="5" value="<?php print $Qty;?>" size="11" onchange="CalBurada()"  onkeydown="OnlyInt1(this.id)"> Kgs</td>
				   <td><input type="text" name="txtBuradaRate" id="txtBuradaRate" tabindex="6" value="<?php print $Rate;?>" size="11" onchange="CalBurada()" onkeydown="OnlyNumeric1(this.id)"></td>
				   <td><input type="text" name="txtBuradaValue" id="txtBuradaValue" size="11" readonly></td>
				  
				 </tr>
				 <!--<tr>
						<td style="padding-left:30px;" height="25px">
							Vat :<br> 
							<input type="text" name="txtVat" id="txtVat" size="9" tabindex="7" onchange="CalVat(this.id)" value="<?php /* print $VatPer; */?>" onkeydown="OnlyNumeric1(this.id)">%
							<span style="margin-left:22px;"></span>
							<input type="text" name="txtVatAmt" id="txtVatAmt" size="11" readonly>
						</td>
						<td colspan="2">
						</td>
					</tr>-->
					<tr id="CGSTdiv" style="display:none">	
					    <td colspan="3" style="text-align:right">CGST % :</td>
						<td>
						  <input type="text" name="txtCGST" id="txtCGST" size="5"  tabindex="88" value="<?=$CGST; ?>" onChange="CalGst()" onblur="CalGst()" onkeydown="OnlyNumeric1(this.id)">&nbsp;%
						</td>
					</tr>
					<tr id="SGSTdiv" style="display:none">	
						<td colspan="3" style="text-align:right">SGST % :</td>
						<td><input type="text" name="txtSGST"  id="txtSGST" size="5" tabindex="88" value="<?php echo $SGST; ?>" onChange="CalGst()" onblur="CalGst()" onkeydown="OnlyNumeric1(this.id)">&nbsp;%</td>
						
						
					</tr>
					<tr  id="IGSTdiv" style="display:none">	
					    <td colspan="3" style="text-align:right">IGST % </td>
						<td>
						<input type="text" name="txtIGST" id="txtIGST" size="5" tabindex="88" value="<?=$IGST; ?>" onChange="CalGst()" onblur="CalGst()"  onkeydown="OnlyNumeric1(this.id)">&nbsp;%
					    </td>
					</tr>
					<tr>
					  <td colspan="3" style="text-align:right">Total</td>
					  <td>
					    <input type="text" name="txtAmt" style="border:none;" id="txtAmt"  value="<?=$TotalAmt ;?>" readonly>
					  </td>
					  
					</tr>
			   </tbody>
			 </table>
		   </div>
		</div>
		<div class="row">
		  <div class="col-sm-12">
		    <label for="txtRemarks">Remarks</label>
		    <textarea name="txtRemarks" id="txtRemarks" class="form-control" tabindex="8"><?=$Remarks; ?></textarea>
		  </div>
		  <div class="col-sm-12">
		    <br>
		    <input type="button" name="btnSave" id="btnSave" value="Save" class="btn btn-primary waves-effect waves-light" onClick="CheckBlank()" tabindex="9">
		    <input type="reset" name="btnReset" id="btnReset" value="Cancel" class="btn btn-secondary waves-effect waves-light" onclick="MenuClick2('<?=trim($_POST[auth_info])?>','<?=base64_encode(base64_encode("operations/party_burada_edit.php"))?>')" tabindex="10">
		    <input type="hidden" id='formfocuselement' value='<?php if($PageMode=='New')echo 'cmbParty'; else echo 'txtBuradaQty'; ?>'/>
		  </div>
		</div>
	
</form>
</div>
</div>