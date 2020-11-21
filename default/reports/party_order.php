<?php
    require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
	
	
	$CurrDate=date ("Y-m-d");
	$BankDate=$CurrDate;
	$PageMode="New";
	$Month=$_SESSION['Month'];
	$YearString=$_SESSION['YearString'];
	
	$startDate=explode("/",$firstday);
	$BeforeDate =$_SESSION['SessionStartDate'];	
	$SNO=1;
	

?>

<script language="JavaScript" type="text/javascript">
function CheckBlank()
{
	
	Party=document.getElementById("cmbParty").value;
	if (Party=="")
	{
		toastr.error("Please select Party...");
		$("#cmbParty").focus();
		event.returnValue=false;
	}
	else
	{
		FromDate=document.getElementById("txtFromDate").value;
		ToDate=document.getElementById("txtToDate").value;
		url="reports/party_order_pdf.php?PartyID="+Party+"&FromDate="+FromDate+"&ToDate="+ToDate;
		document.getElementById('iframe2').src=url;
		event.returnValue=false;
	}
}



function OnLoad()
{
	document.getElementById("cmbParty").focus();
}
</script>
<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Reports</h4>
        <div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Party Orders</li>
			</ol>
		</div>

	</div>
</div>
</div>
<div class="card">
		<div class="card-body">
<form name="frmPartyLedger" method="post" action="">
   <div class="row">
     <div class="col-sm-6">
	    <label for="cmbParty">Party List</label>
		<select name="cmbParty" id="cmbParty" class="form-control"  tabindex="1">
			<option value=""><?=str_repeat("-",20) ?>Select Party<?=str_repeat("-",20) ?></option>
				<?php
					
					$result=mysqli_query($con2,"select (iPartyID) as PartyID, iPartyID , cPartyName from party_master order by cPartyName");
					if ($result && mysqli_num_rows($result)>0)
					{
						while ($row=mysqli_fetch_array($result))
						{
							print "<option value=\"$row[PartyID]\"";
							if ($PartyId==$row['PartyID'])	print "selected";
							print ">$row[cPartyName]</option>";
							
						}
					}
				?>
		</select>		
	 </div>
	 <div class="col-sm-3">
	 <label for="txtFromDate">From Date</label>
	 <input type="hidden" name="hfCurrDate" id="hfCurrDate" value="">
	 <input type="date" name="txtFromDate" id="txtFromDate"  value="<?php print $BeforeDate; ?>" class="form-control" onFocus="this.blur()" readonly>
	 </div>
	 <div class="col-sm-3">
	  <label for="txtToDate">To Date</label>
	  <input type="date" name="txtToDate" id="txtToDate"  value="<?php print $CurrDate; ?>" class="form-control" onFocus="this.blur()" readonly>
	 </div>
	 <div class="col-sm-12">
	    <br>
	    <input type="button" name="btnGetData" id="btnGetData" value="Get Data" class="btn btn-primary waves-effect waves-light float-right"  tabindex="2" onClick="CheckBlank()">
				
	 </div>
   </div>
<table cellspacing="0" cellpadding="0" width="100%" align="center" class="labeltext">
	<tr>
		<td width="100%" height="25px" class="borderblue">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					
					
					<td width="25%">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext" align="center">
				
				<tr>
					<td colspan="3" height="25px;"></td>
				</tr>
				<tr>
					<td style="padding-left:30px;">
					</td>
					
				</tr>
			</table>
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
<br/>
<br/>
<br/>
</form>
<iframe width=132 height=142 name="gToday:contrast:agenda.js" id="gToday:contrast:agenda.js"  scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>

</div>
</div>
