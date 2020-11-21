<?php
	/*	 Through this screen we can make Delivery Challan by clicking on new radio button and we can edit Old delivery 
		 challan by clicking on Old challan button. we can increase/ decrease the no. of dispatch items.

		 Table used
		 1. party_challan
		 2. party_challan_detail
		 3. party_order_detail
		 
		 Files Used
		 1. delivery_challan.php
		 2. delivery_challan.js
		 3. delivery_challan_edit.php
		 4. delivery_challan_edit_getpo.php
		 5. delivery_challan_getchallan.php
		 6. delivery_challan_getpo.php
		 7. delivery_challan_showpdf.php
		 
   */
	// session_start(); 
	// require ('../general/authenticate.php');
	// $_SESSION['curritem']='Delivery Challan';
	// require ('../general/dbconnect.php');
	
   	// $ErrMsg=array();
	// $Err=false;
	require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
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
		
		$CurrDate=date ("Y-m-d");
		$PageMode="New";
		$Month=$_SESSION['Month'];
		$YearString=$_SESSION['YearString'];
	}
	

	if (isset($_POST['DataChallan']))
	{
		
		
		 $ChallanData=explode("~",base64_decode(base64_decode($_POST['DataChallan'])));
		 $fsYear=$ChallanData[0];
		 $ChallanID=$ChallanData[1];
		 $TablePrefix=$ChallanData[2];
		
			
		$ItemData="";
		
		
		$challanqry=mysqli_query($con2,"select * from {$TablePrefix}party_challan where iChallanID=\"$ChallanID\"");
		if ($challanqry && mysqli_num_rows($challanqry)>0)
		{
			$challanrow=mysqli_fetch_array($challanqry);
			$PartyCode=$challanrow['iPartyCode'];
			$Remarks=$challanrow['cRemarks'];
			$ChallanNo=$challanrow['cChallanNo'];
			$dOrderdate=explode("-",$challanrow['dChallanDate']);
			$CurrDate= "$dOrderdate[2]/$dOrderdate[1]/$dOrderdate[0]";
			
		}
		
		$result=mysqli_query($con2,"select a.* from party_challan_detail a inner join party_order b on a.iOrderID=b.iOrderID where iChallanID=\"$ChallanID\"");
		if ($result)
		{
			if (mysqli_num_rows($result)>0)
			{
				while ($row=mysqli_fetch_array($result))
				{
					$ItemType=$row['cItemType']; 
					$ItemCode=$row['iItemCode'];
					$OrderID=$row['iOrderID'];
					$Disp=$row['iNoPcsDisp'];
					$PartyID=$row['iPartyCode'];
					$ChallanID=$row['iChallanID'];
					$FirmID=$row['iFirmSNo'];
					$Returned=$row['iNoPcsReturn'];
					$PartyType=$row['cPartType'];
					
					$Pcs=$row['cPcs'];
					$Collar=$row['cCollar'];
					
					
					
					
					if ($ItemType=="Gear")
					{
						$Itemqry=mysqli_query($con2,"select * from gear_master where iId =\"$ItemCode\"");
						if ($Itemqry && mysqli_num_rows($Itemqry)>0)
						{	
							$Itemrow=mysqli_fetch_array($Itemqry);
							$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
						}
					}
					else if ($ItemType=="Pinion")
					{
						$Itemqry=mysqli_query($con2,"select * from pinion_master where iId='$ItemCode'");	
						if ($Itemqry && mysqli_num_rows($Itemqry)>0)
						{	
							$Itemrow=mysqli_fetch_array($Itemqry);
							$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
						}
					}
					else if ($ItemType=="Bevel Pinion")
					{
						$Itemqry=mysqli_query($con2,"select * from bevel_pinion_master where iId='$ItemCode'");			
						if ($Itemqry && mysqli_num_rows($Itemqry)>0)
						{	
							$Itemrow=mysqli_fetch_array($Itemqry);
							$ItemName=$Itemrow['iTeeth']." teeth dia $Itemrow[fDia] $Itemrow[cDiaType](".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
						}
					}
					else if ($ItemType=="Bevel Gear")
					{
						$Itemqry=mysqli_query($con2,"select * from bevel_gear_master where iId='$ItemCode'");			
						if ($Itemqry && mysqli_num_rows($Itemqry)>0)
						{	
							$Itemrow=mysqli_fetch_array($Itemqry);
							$ItemName=$Itemrow['iTeeth']." teeth dia $Itemrow[fDia] $Itemrow[cDiaType] (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
						}
					}
					else if ($ItemType=="Shaft Pinion")
					{
						$Itemqry=mysqli_query($con2,"select * from shaft_pinion_master where iId='$ItemCode'");
						if ($Itemqry && mysqli_num_rows($Itemqry)>0)
						{	
							$Itemrow=mysqli_fetch_array($Itemqry);
							$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia $Itemrow[fDia] $Itemrow[cDiaType] face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
						}
					}
					else if ($ItemType=="Chain Wheel")
					{
						$Itemqry=mysqli_query($con2,"select * from chain_gear_master where iId='$ItemCode'");
						if ($Itemqry && mysqli_num_rows($Itemqry)>0)
						{	
							$Itemrow=mysqli_fetch_array($Itemqry);
							$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." pitch ".$Itemrow['fPitch']."&nbsp;".$Itemrow['cPitchType'];
						}
					}
					else if ($ItemType=="Worm Gear")
					{
						$Itemqry=mysqli_query($con2,"select * from worm_gear_master where iId='$ItemCode'");			
						if ($Itemqry && mysqli_num_rows($Itemqry)>0)
						{	
							$Itemrow=mysqli_fetch_array($Itemqry);
							$ItemName=$Itemrow['iTeeth']." teeth dia $Itemrow[fDia] $Itemrow[cDiaType] (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
						}
					}
					$ItemData="$ItemType~Caption~$ItemName~Heading~";
					 
					 
						$result1=mysqli_query($con2,"select {$TablePrefix}party_order_detail.iOrderID,cOrderCode,dOrderDate , iNoPcsRec, {$TablePrefix}party_order_detail.iNoPcsDisp ,cChallanNo ,  {$TablePrefix}party_order_detail.iItemCode , {$TablePrefix}party_order_detail.fRate ,{$TablePrefix}party_order_detail.cPartType, {$TablePrefix}party_challan_detail.cItemRemarks, {$TablePrefix}party_order_detail.cItemRemarks as PartyOrderItemRem ,({$TablePrefix}party_challan_detail.iNoPcsDisp) as Dispatch , {$TablePrefix}party_order_detail.cPcs , {$TablePrefix}party_order_detail.cCollar from {$TablePrefix}party_order inner join {$TablePrefix}party_order_detail on {$TablePrefix}party_order.iOrderID={$TablePrefix}party_order_detail.iOrderID  inner join {$TablePrefix}party_challan_detail on {$TablePrefix}party_order_detail.iOrderID={$TablePrefix}party_challan_detail.iOrderID and {$TablePrefix}party_order_detail.cItemType={$TablePrefix}party_challan_detail.cItemType and {$TablePrefix}party_order_detail.iItemCode={$TablePrefix}party_challan_detail.iItemCode  and {$TablePrefix}party_order_detail.cPartType={$TablePrefix}party_challan_detail.cPartType and {$TablePrefix}party_order_detail.cPcs={$TablePrefix}party_challan_detail.cPcs where {$TablePrefix}party_order_detail.iOrderID=\"$OrderID\" and {$TablePrefix}party_challan_detail.iChallanID=\"$ChallanID\" and {$TablePrefix}party_challan_detail.cItemType=\"$ItemType\" and {$TablePrefix}party_challan_detail.iItemCode=\"$ItemCode\" and {$TablePrefix}party_challan_detail.cPartType=\"$PartyType\"  and  {$TablePrefix}party_challan_detail.cPcs =\"$Pcs\"");
					
					if ($result1 && mysqli_num_rows($result1)>0)
					{
						
						$row1=mysqli_fetch_array($result1);
						$dArr=explode("-",$row1['dOrderDate']);
						$ItemRemarks=$row1['PartyOrderItemRem'];
						$date="$dArr[2]/$dArr[1]/$dArr[0]";
						
						
						 $Dispatched=($row1['iNoPcsDisp'] - ($row1['Dispatch'] + $Returned)); 
						 
						 $Data="$row1[iOrderID]~ArrayItem~$row1[cOrderCode]~ArrayItem~$row1[iNoPcsRec]~ArrayItem~$Dispatched~ArrayItem~$row1[cChallanNo]~ArrayItem~$row1[iItemCode]~ArrayItem~$row1[fRate]~ArrayItem~$row1[cItemRemarks]~ArrayItem~$row1[Dispatch]~ArrayItem~$Returned~ArrayItem~$date~ArrayItem~$row1[cPartType]~ArrayItem~$row1[cPcs]~ArrayItem~$row1[cCollar]~ArrayItem~$ItemRemarks~ArrayItem~$YearPrefix";
					}
					if ($ItemArray=="")
						$ItemArray=$ItemData.$Data;
					else
						$ItemArray=$ItemArray."~ItemData~".$ItemData.$Data;
					$Data="";
				}
			}
			else
			{
				$ItemArray="";
			}
		}
		else
		{
			$ItemArray="Error";
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
<script type="text/javascript" language="javascript" src="../js/delivery_challan.js"></script>
<script>
$("document").ready(function(){
	PageLoad2();
})
</script>
<script>
function shiftFocus()
{
	if (DeliveryArr.length>0)
	{
		do{
			document.getElementById("txtDispatch0").focus();
		}while (document.getElementById("txtDispatch0").focus());
	}
	else
	{
		document.getElementById("cmbParty").focus();
	}
}

</script>
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
<form name="frmChallanOrder" id="frmChallanOrder" method="POST">
<input type="hidden" name="hdPageMode" id="hdPageMode" value="<?=$PageMode ?>">
<input type="hidden" name="hfCurrDate" id="hfCurrDate" value="">
<input type="hidden" name="auth_info" id="auth_info" value="<?=$_POST['auth_info']?>">
<input type="hidden" name="hdPODetails" id="hdPODetails" value="<?=$ItemArray ?>">
<input type="hidden" name="TablePrefix" id="TablePrefix" value="<?=$TablePrefix ?>">
<input type="hidden" name="txtChallanId" id="txtChallanId" value="<?=base64_encode(base64_encode($ChallanID)) ?>">
<input type="hidden" id='formfocuselement' value='<?=($PageMode=='New')?'cmbParty':'cmbParty'; ?>'/>

  <div class="row">
     <div class="col-sm-6">
	  <span class="float-left">
	   <input type="radio" name="rdb" id="rdNew" value="New" checked onClick="Switch(this.id)">New Challan
	   <input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)">List Challan
	  </span>
	 </div>
	 <div class="col-sm-6">
	  <span class="float-right">
	      <?php
			$CodeMsg="";
			$result=mysqli_query($con2,"select cChallanCode,iChallanID from party_challan where iChallanID=(select MAX(iChallanID) from party_challan)");
			if ($result)
			{
				if (mysqli_num_rows($result)>0)
				{
					$row=mysqli_fetch_array($result);
					$CodeMsg="Last Delivery Challan No : ".$row['cChallanCode'];
					//print "<a href=\"#\" onClick=\"PrintPdf('$row[iChallanID]')\">".$CodeMsg."</a>";
					print $CodeMsg;
				}
				else
				{
					$CodeMsg="No Delivery Challan saved before...";
					print $CodeMsg;
				}
			}
		?>
	  </span>
	 </div>
  </div>
  <br>
  <div class="row">
    <div class="col-sm-6">
	                        <label for="cmbParty">Select party</label>
							<select name="cmbParty" id="cmbParty" class="form-control"  tabindex=1>
								<option value=""><?=str_repeat("-",10) ?>Select Party<?=str_repeat("-",10) ?></option>
								<?php
									//require ('../general/dbconnect.php');
									$result=mysqli_query($con2,"select (iPartyID) as PartyID , cPartyName from party_master order by cPartyName");
									if ($result && mysqli_num_rows($result)>0)
									{
										while ($row=mysqli_fetch_array($result))
										{
											print "<option value=\"$row[PartyID]\"";
											if ( $PartyCode==$row['PartyID']) print "selected";
											print ">$row[cPartyName]</option>";
										}
									}
								?>
							</select>
	</div>
    <div class="col-sm-6">
	  <label for="txtDeliveryChallan">Delivery Challan No</label>
	  <input type="text" name="txtDeliveryChallan" id="txtDeliveryChallan" tabindex=2 class="form-control" value="<?=$ChallanNo ?>" onblur="shiftFocus()">
	</div>
    <div class="col-sm-6">
	  <label for="txtFromDate">From Date</label> 
	   <input class="form-control" type="date"  value="<?=$dStartDate; ?>" onFocus="this.blur()"  max="<?=$__sysDate?>" name="txtFromDate" id="txtFromDate">
	  				
	</div>
	<div class="col-sm-6">
	  <!-- <label for="txtToDate">To Date</label>
      // <input class="form-control" type="date"  value="<?=$__sysDate; ?>" onFocus="this.blur()"  max="<?=$__sysDate?>" name="txtToDate" id="txtToDate">
	  	-->  
	  				
	</div>
  </div>
  <br>
  <div class="row">
    <div class="col-sm-12">
	   <input type="button" name="btnGetData" id="btnGetData" value="Get Data" class="btn btn-primary waves-effect waves-light float-right" onclick="selPODetails()"/>
	</div>
  </div>
  <div class="row">
     <div class="col-sm-12" id="dvPoHeading">
	   
	   
	 </div>
  </div>
  <div class="row">
    <div class="col-sm-6">
	  <label for="txtRemarks">Remarks</label>
	   <textarea name="txtRemarks" id="txtRemarks" class="form-control"><?=$Remarks ?></textarea>
	</div>
	<div class="col-sm-6">
	  <span class="float-left">
	    <input type="button" name="btnExecute" id="btnExecute" style="margin-top:30px" value="Execute" class="btn btn-primary waves-effect waves-light" onclick="PrintReport()">
	  </span>
	</div>
    
	
    
  </div>
  <br>
  <div class="row">
    <div class="col-sm-12">
	<?php
	if($__add==true && $__update==true)
	{
	?>
	<button type="button" name="btnSave"  id="btnSave"  class="btn btn-primary waves-effect waves-light">Save</button>
	<?php
	}
	?>
	<input type="button"  value="Cancel" class="btn btn-secondary" onclick="cancelBtn()">
	</div>
  </div>
	
</form>
</div>
</div>
<script>
function cancelBtn()
{
	MenuClick2($("#auth_info").val(),btoa(btoa("operations/delivery_challan_edit.php")));
}
</script>