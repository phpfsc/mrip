<?php
    require_once("../../config.php");
	$CurrDate=date ("Y-m-d");

	$BankDate=$CurrDate;

	$PageMode="New";

	$Month=$_SESSION['Month'];

	$YearString=$_SESSION['YearString'];
    $BeforeDate =$_SESSION['SessionStartDate'];	

	$SNO=1;

	



?>

<script language="JavaScript" type="text/javascript">

function PageLoad()
{

	document.getElementById("cmbCompany").focus();

}



function ShowPdf()

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

		Party=(document.getElementById("cmbMainParty").value).split('~ArrayItem~');

		SubParty=document.getElementById("cmbSubParty").value;



		url="reports/party_tds_pdf.php?CompanyID="+Company[0]+"&CompanyiSNo="+Company[1]+"&FromDate="+FromDate+"&ToDate="+ToDate+"&PartyID="+Party[0]+"&SubParty="+SubParty;

		document.getElementById('iframe2').src=url;

		event.returnValue=false;

	}

}	



function SelParty(ID)

{

	val=document.getElementById("cmbMainParty").value;

	if (val!="")

	{

		i=document.getElementById("cmbMainParty").options[document.getElementById("cmbMainParty").selectedIndex].text;

		Itemctrl=document.getElementById("cmbSubParty");

		opt=val.split("~ArrayItem~");

		Itemctrl.length=1;

		opts=opt[1].split("~ItemData~");

		Itemctrl.options[1] = new Option(i,'0');

		if (ID=='0')

		{

			Itemctrl.options[1].selected=true;

		}

		else

		{

			Itemctrl.options[1].selected=false;

		}

		if (opts!="")

		{

			for (x=0;x<opts.length ;x++ )

			{

				sel=opts[x].split("~Array~");

				Itemctrl.options[x+2] = new Option(sel[1],sel[0]);

				if (ID==sel[0])

				{

					Itemctrl.options[x+2].selected=true;

				}

				else

				{

					Itemctrl.options[x+2].selected=false;

				}

			}

		}

	}

	else

	{

		ctrlItem=document.getElementById("cmbSubParty");

		ctrlItem.length=1;

	}

}



</script>

</head>
<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Reports</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">TDS</li>
			</ol>
		</div>

	</div>
</div>
</div>
<div class="card">
<div class="card-body">
<form name="frmPartyTds" method="post" action="">

	<div class="row">
	  <div class="col-sm-6">
	    <label for="cmbCompany">Company</label>
	    <select name="cmbCompany" class="form-control" id="cmbCompany" tabindex="1">
                        <option value=""><?php print str_repeat("-",20) ?>Select Company<?php print str_repeat("-",20) ?></option>
                            <?php

												$result=mysqli_query ($con2,"select concat(iPartyID,'~ArrayItem~','0') as PartyID , cPartyName as cPartyName from company_master UNION select  concat(iPartyID,'~ArrayItem~',iSNo) as PartyID , cFirmName as cPartyName from company_master_detail ");

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
       <input type="date" name="txtFromDate" id="txtFromDate" class="form-control" value="<?=$BeforeDate; ?>" size="12" onFocus="this.blur()" readonly>
	  </div>
	  <div class="col-sm-3">
	    <label for="txtToDate">To Date</label>
	    <input type="date" name="txtToDate" id="txtToDate" class="form-control" value="<?=$CurrDate; ?>" size="12" onFocus="this.blur()" readonly>
	  </div>
	  <div class="col-md-6">
	    <label for="cmbMainParty">Main Party</label>
	    <select name="cmbMainParty" id="cmbMainParty" class="form-control" onchange="SelParty('')" tabindex="2">

								<option value=""><?php print str_repeat("-",30); ?>Select Party<?php print str_repeat("-",30); ?></option>

									<?php

										

										$result=mysqli_query ($con2,"select (iPartyID) as PartyID, iPartyID , cPartyName from party_master order by cPartyName");

										if ($result && mysqli_num_rows($result)>0)

										{

											while ($row=mysqli_fetch_array($result))

											{

												$result1=mysqli_query ($con2,"select * from party_master_detail where iPartyID='$row[iPartyID]'");

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

												print "<option value=\"$row[PartyID]~ArrayItem~$PartyData\"";

												if ($PartyId==$row['PartyID'])	print "selected";

												print ">$row[cPartyName]</option>";

												$PartyData="";

											}

										}

									?>



							</select>

	  </div>
	  <div class="col-md-6">
	    <label for="cmbSubParty">Sub Party</label>
		<select name="cmbSubParty" class="form-control" id="cmbSubParty" tabindex="3">

			<option value=""><?php print str_repeat("-",20); ?>Select Sub Party<?php print str_repeat("-",20); ?></option>

		</select>
	  </div>
	 
	  <div class="col-md-12">
	   <br>
	     <input type="button" name="btnGetData" id="btnGetData" value="Get Data" class="btn btn-primary waves-effect waves-light float-right"  tabindex="3" onClick="ShowPdf()">
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

