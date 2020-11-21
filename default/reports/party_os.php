<?php
	require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
   	
	$CurrDate=date ("Y-m-d");
	$BankDate=$CurrDate;
	$PageMode="New";
	$Month=$_SESSION['Month'];
	$YearString=$_SESSION['YearString'];
	//$firstday = date ("m/d/Y", mktime(0, 0, 0, date("m") , date("d")-15 , date("Y")));
	
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
		url="reports/party_os_pdf.php?CompanyID="+Company[0]+"&CompanyiSNo="+Company[1]+"&FromDate="+FromDate+"&ToDate="+ToDate;
		document.getElementById('iframe2').src=url;
		event.returnValue=false;
	}
}

function SelPartyHead(Id)
{
	val=document.getElementById(Id).value;
	ItemCtrl=document.getElementById("cmbParty");
	if (val!='')
	{
		sel=val.split('~ArrayItem~');
		var http=false;
		url="reports/os_getbillparty.php?PartyID="+sel[0]+"&iSNo="+sel[1];
		if (navigator.appName=="Microsoft Internet Explorer")
		{
			http= new ActiveXObject ("Microsoft.XMLHTTP");
		}
		else
		{
			http=	 new XMLHttpRequest();
		}
		http.open ("GET",url);

		http.onreadystatechange=function()
		{
			if (http.readyState==4 && http.status==200)
			{
				if (http.responseText!="")
				{
					ItemCtrl.length=1;
					ItemData=(http.responseText).split('~ItemData~');
					for (i=0;i<ItemData.length;i++)
					{
						MainParty=(ItemData[i]).split('~ArrayItem~');
						ItemCtrl.options[i+1] = new Option(MainParty[1],MainParty[0]);
					}
				}
			}
		}
		http.send(null);
	}
}
</script>

<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Reports</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Party o/s</li>
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
    <select name="cmbCompany" class="form-control" id="cmbCompany" tabindex="1">
		<option value=""><?=str_repeat("-",20) ?>Select Company<?=str_repeat("-",20) ?></option>
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
    <label for="txtFromDate">From Date </label>
    <input type="hidden" name="hfCurrDate" id="hfCurrDate" value="">
	<input type="date" name="txtFromDate" id="txtFromDate" class="form-control" value="<?=$BeforeDate; ?>"  onFocus="this.blur()" readonly>
  </div>
  <div class="col-sm-3">
    <label for="txtToDate">To Date </label>
    <input type="date" name="txtToDate" id="txtToDate"  value="<?=$CurrDate; ?>" class="form-control" onFocus="this.blur()" readonly>
  </div>
  <div class="col-sm-12">
  <br>
    <input type="button" name="btnGetData" id="btnGetData" value="Get Data" class="btn btn-primary waves-effect waves-light float-right"  tabindex="3" onClick="CheckBlank()">
  </div>
</div>

<br>
<div class="row">
  <div class="col-sm-12">
    <iframe id='iframe2' height="500" width="950" marginheight="0" marginwidth="0" frameborder="0" style="overflow-x: hidden;"></iframe>
	<iframe width="132" height="142" name="gToday:contrast:agenda.js" id="gToday:contrast:agenda.js"  scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>
  </div>
</div>
</form>

</div>
</div>
