<?php
    require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
	

   

	$CurrDate=date ("Y-m-d");
    $BankDate=$CurrDate;
    $PageMode="New";
    $Month=$_SESSION['Month'];
    $YearString=$_SESSION['YearString'];
    $BeforeDate =$_SESSION['SessionStartDate'];	
    $SNO=1;
    
?>
<script>
function ClearText()

{

	document.getElementById("txtTeeth").value="";

	document.getElementById("txtDia").value="";

	document.getElementById("txtFace").value="";

	document.getElementById("txtProcessing").value="";

}



function ShowPdf()

{

	if (document.getElementById("cmbMainParty").value!='')

	{

		if (document.getElementById("cmbItem").value!="")

		{

			var Party=document.getElementById("cmbMainParty").value;

			var ItemType=document.getElementById("cmbItem").value;

			var FromDate=document.getElementById("txtFromDate").value;

			var ToDate=document.getElementById("txtToDate").value;

			var Teeth=document.getElementById("txtTeeth").value;

			var Dia=document.getElementById("txtDia").value;

			var DiaType=document.getElementById("cmbDiaType").value;

			var Face=document.getElementById("txtFace").value;

			var FaceType=document.getElementById("cmbFaceType").value;

			var Pitch=document.getElementById("txtPitch").value;

			var PitchType=document.getElementById("cmbPitchType").value;

			var Processing=document.getElementById("txtProcessing").value;

			var ProcessingType=document.getElementById("cmbProcessingType").value;

			

			if (ItemType=="Gear" || ItemType=="Pinion" || ItemType=="Shaft Pinion")

				var Type=document.getElementById("cmbType").value;

			else if (ItemType=="Chain Wheel")

				var Type=document.getElementById("cmbChainWheelType").value;

			else

				var Type="";

			

			url="reports/search_items_pdf.php?FromDate="+FromDate+"&ToDate="+ToDate+"&PartyID="+Party+ "&Teeth="+Teeth+"&Dia="+Dia+"&DiaType="+DiaType+"&Face="+Face+"&FaceType="+FaceType+"&Pitch="+Pitch+"&PitchType="+PitchType+"&Processing="+Processing+"&ProcessingType="+ProcessingType+"&ItemType="+ItemType+"&Type="+Type;

			document.getElementById('iframe2').src=url;

			event.returnValue=false;

		}

		else

		{
            toastr.error("Please Select Item Type...");
			$("#cmbItem").focus();
			event.returnValue=false;

		}

	}

	else

	{
        toastr.error("Please Select Main Party...");
		$("#cmbMainParty").focus();
		event.returnValue=false;

	}

}	



function CallDiv(ctrl)
{

	if (ctrl.value=="Gear" || ctrl.value=="Pinion" || ctrl.value=="Shaft Pinion")

	{

		document.getElementById("dvGearType").style['display']="inline";

		document.getElementById("dvFace").style['display']="inline";

		document.getElementById("dvModule").style['display']="inline";

		document.getElementById("dvChainWheelType").style['display']="none";

		document.getElementById("dvPitch").style['display']="none";

	}

	else if (ctrl.value=="Chain Wheel")

	{

		document.getElementById("dvChainWheelType").style['display']="inline";

		document.getElementById("dvPitch").style['display']="inline";

		document.getElementById("dvGearType").style['display']="none";

		document.getElementById("dvFace").style['display']="none";

		document.getElementById("dvModule").style['display']="none";

	}

	else if (ctrl.value=="Bevel Gear" || ctrl.value=="Bevel Pinion" || ctrl.value=="Worm Gear")

	{

		

		document.getElementById("dvModule").style['display']="inline";

		document.getElementById("dvGearType").style['display']="inline";

		document.getElementById("dvGearType").style['display']="none";

		document.getElementById("dvChainWheelType").style['display']="none";

		document.getElementById("dvPitch").style['display']="none";

		document.getElementById("dvFace").style['display']="none";

	}

}



</script>
<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Reports</h4>
        <div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Search Items</li>
			</ol>
		</div>

	</div>
</div>
</div>
<div class="card">
		<div class="card-body">
<form name="frmPartyOutStanding" method="post" action="">
    <div class="row">
	  <div class="col-sm-6">
	     <label for="cmbMainParty">Main Party</label>
	     <select name="cmbMainParty" id="cmbMainParty" class="form-control"  tabindex="1">
                                <option value=""><?php print str_repeat("-",20); ?>Select Party<?php print str_repeat("-",20); ?></option>
                                    <?php
                                        $result=mysqli_query ($con2,"select iPartyID , cPartyName from party_master order by cPartyName");
                                        if ($result && mysqli_num_rows($result)>0)
                                        {

											while ($row=mysqli_fetch_array($result))

											{

												print "<option value=\"$row[iPartyID]\"";

												if ($PartyId==$row['iPartyID'])	print "selected";

												print ">$row[cPartyName]</option>";

											}

										}

									?>

		 </select>
	  </div>
	  <div class="col-sm-3">
	     <label for="txtFromDate">From Date</label>
		 <input type="hidden" name="hfCurrDate" id="hfCurrDate" value="">
         <input type="date" name="txtFromDate" id="txtFromDate" class="form-control" value="<?=$BeforeDate; ?>"  onFocus="this.blur()" readonly>
	  </div>
	  <div class="col-sm-3">
	    <label for="">To Date</label>
		<input type="date" name="txtToDate" id="txtToDate" class="form-control" value="<?=$CurrDate; ?>"  onFocus="this.blur()" readonly>
	  </div>
	  <div class="col-sm-6" style="margin-bottom:20px;margin-top:20px">
	                     <label for="cmbItem">Item Type</label>
	                     <select name="cmbItem" class="form-control" id="cmbItem"  onchange="CallDiv(cmbItem)">
                            <option value="">---------Select Item--------</option>
                                <?php
                                   $Itemqry=mysqli_query ($con2,"select * from item_master");
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
	  <div class="col-sm-3">
	  
			<label for="txtTeeth">Teeth</label>
			<input type="text" name="txtTeeth" id="txtTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="3">
				<div id="dvGearType"> 
                        <label for="cmbType">Type</label>
                        <select name="cmbType" class="form-control" id="cmbType" tabindex="4">
                            <option value="Plain">Plain</option>
                            <option value="Helical">Helical</option>
                            <option value="Spur Hobb">Spur Hobb</option>

						</select>

				</div>
	  
	  </div>
	  <div id="dvChainWheelType" class="col-sm-3" style="display:none">
		<label for="cmbChainWheelType">Type :</label>
		<select name="cmbChainWheelType" id="cmbChainWheelType" class="form-control" tabindex="5">
			<option value="Single">Single</option>
			<option value="Duplex">Duplex</option>
			<option value="Triplex">Triplex</option>
			<option value="Fourplex">Fourplex</option>
		</select>

	</div>
	<div  class="col-sm-3"> 
		<label for="txtDia">Dia </label>
		<input type="text" name="txtDia" id="txtDia" class="form-control" tabindex="6" onblur="onlyGrams(this.id)">
		<br>
		<select name="cmbDiaType" class="form-control" id="cmbDiaType" tabindex="7">
			<option value="inches">inches</option>
			<option value="mm">mm</option>
		</select>

	</div>
	<div id="dvFace"  class="col-sm-3"> 
       <label for="txtFace">Face </label>
		<input type="text" name="txtFace" id="txtFace" class="form-control" tabindex="8">											
        <br>
		<select name="cmbFaceType" class="form-control" id="cmbFaceType" tabindex="9">
		   <option value="inches">inches</option>
		   <option value="mm">mm</option>
        </select>

	</div>
	<div  id="dvPitch"  class="col-sm-3" style="display:none;"> 
	    <label for="txtPitch">Pitch </label>
	    <input type="text" name="txtPitch" class="form-control" id="txtPitch"  tabindex="8">											
        <br>
		<select name="cmbPitchType" id="cmbPitchType" class="form-control" tabindex="9">

			<option value="inches">inches</option>

			<option value="mm">mm</option>

		</select>

	</div>
	<div id="dvModule"  class="col-sm-3"> 
        <label for="txtProcessing">DP/Module</label>
		<input type="text" name="txtProcessing" id="txtProcessing" class="form-control" tabindex="10" >											
        <br>
		<select name="cmbProcessingType" id="cmbProcessingType" class="form-control" tabindex="11">

			<option value="DP">DP</option>

			<option value="Module">Module</option>

		</select>

	</div>
	<div class="col-sm-12">
	   <br>
	   <input type="button" name="btnGetData" id="btnGetData" value="Get Data" class="btn btn-primary waves-effect waves-light"  tabindex="12" onClick="ShowPdf()" />
       <input type="button" name="btnNewSearch" id="btnNewSearch" value="New Search" class="btn btn-secondary waves-effect waves-light" tabindex="13" onClick="ClearText()" onblur="document.getElementById('cmbMainParty').focus()"/>
	</div>
	</div>

	

</form>
<br>
<div class="row">
  <div class="col-sm-12">
     <iframe id='iframe2' height="500" width="950" marginheight="0" marginwidth="0" frameborder="0" style="overflow-x: hidden;"></iframe>
	 <iframe width="132" height="142" name="gToday:contrast:agenda.js" id="gToday:contrast:agenda.js"  scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>

  </div>
</div>
</div>
</div>