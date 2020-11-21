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

<script language="JavaScript" type="text/javascript">


function CheckBlank()

{

	Company=(document.getElementById('cmbCompany').value).split('~ArrayItem~');
    if (Company=="")
    {
        toastr.error("Please select Company...");
		$("#cmbCompany").focus();
		event.returnValue=false;

	}

	else

	{

		FromDate=document.getElementById("txtFromDate").value;

		ToDate=document.getElementById("txtToDate").value;

		url="reports/party_sale_pdf.php?CompanyID="+Company[0]+"&CompanyiSNo="+Company[1]+"&FromDate="+FromDate+"&ToDate="+ToDate;

		document.getElementById('iframe2').src=url;

		event.returnValue=false;

	}

}

</script>
<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Reports</h4>
        <div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Party Sale</li>
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
	  <label for="cmbCompany">Company</label>
	  <select name="cmbCompany" id="cmbCompany" tabindex="1" class="form-control">
                            <option value=""><?php print str_repeat("-",27) ?>Select Company<?php print str_repeat("-",27) ?></option>
                            <?php
                                $result=mysqli_query($con2,"select concat(iPartyID,'~ArrayItem~','0') as PartyID , cPartyName as cPartyName from company_master UNION select  concat(iPartyID,'~ArrayItem~',iSNo) as PartyID , cFirmName as cPartyName from company_master_detail ");

								if ($result && mysqli_num_rows($result)>0)

								{

									while ($row=mysqli_fetch_array($result))

									{

										print "<option value=\"$row[PartyID]\"";

										if ($row['PartyID']=="$CompanyCode~ArrayItem~$CompanySNo") print "selected";

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
	 <label for="txtToDate">To Date</label>
	   <input type="date" name="txtToDate" id="txtToDate" class="form-control"  value="<?=$CurrDate; ?>"  onFocus="this.blur()" readonly>
	</div>
 </div>
 <br>
 <div class="row">
   <div class="col-sm-12">
    <input type="submit" name="btnGetData" id="btnGetData" value="Get Data" class="btn btn-primary waves-effect waves-light float-right"  tabindex="3" onClick="CheckBlank()">

   </div>
 </div>
<table cellspacing="0" cellpadding="0" width="100%" align="center" class="labeltext">

	

	

	<tr>

		<td>

			<fieldset class="legend" align="center" style="width:900px">

			

			<table cellspacing="0" cellpadding="0" style="width:100%;" class="labeltext" align="center">

				<tr>

					

					

				</tr>

				<tr>

					<td colspan="3" height="25px;"></td>

				</tr>

				<tr>

					<td style="padding-left:30px;text-align:center;" colspan="3">

					<?php	

						 
					?>	

						<!--<input type="submit" name="btnGetData" id="btnGetData" value="Get Data" class="btncss" onclick="CheckBlank()" tabindex="3"> -->

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

<br/>

<br/>

<br/>

</form>

<iframe width=132 height=142 name="gToday:contrast:agenda.js" id="gToday:contrast:agenda.js" src="../js-calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>
</div>
</div>
