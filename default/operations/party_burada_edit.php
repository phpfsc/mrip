<?php
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
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
		
		$PageMode="New";
		$CurrDate=date ("Y-m-d");					
		$BeforeDate=$_SESSION['SessionStartDate'];
		if (isset($_POST['CompanyCode']) || isset($_POST['CompanySNo']) || isset($_POST['MainParty']) || isset($_POST['SubParty']) )
				{
					$FromDate=$_POST['FromDate'];
					$BeforeDate=$_POST['FromDate'];
					$dArr=explode("/",$FromDate);
					$fdate="$dArr[2]-$dArr[1]-$dArr[0]";
					$ToDate=$_POST['ToDate'];
					$CurrDate=$_POST['ToDate'];
					$tArr=explode("/",$ToDate);
					$tdate="$tArr[2]-$tArr[1]-$tArr[0]";
					if (isset($_POST['CompanySNo']) && $_POST['CompanySNo']!="")
					{
						$SubCompanyqry="";
						$SubCompanyclause=" and party_billing.iCompanySNo =\"".$_POST['CompanySNo']."\" ";
					}
					else
					{
						$SubCompanyqry="";
						$SubCompanyclause="";
					}	

					if (isset($_POST['MainParty']) && $_POST['MainParty']!="")
					{
						$PartyCode=$_POST['MainParty'];
						if (isset($_POST['SubParty']) && $_POST['SubParty']!="")
						{
							$PartySNo=$_POST['SubParty'];
							$SubPartyclause=" and party_billing.iPartyCode =\"".$_POST['MainParty']."\" and party_billing.iFirmSNo=\"".$_POST['SubParty']."\" ";
						}
						else
						{
							//$Partyqry="  join party_master on party_billing.iPartyCode =party_master.iPartyID ";
							$Partyclause=" and party_billing.iPartyCode =\"".$_POST['MainParty']."\"  ";
							$PartySNo=0;
							$SubPartyqry="";
							$SubPartyclause="";
						}
					}
					else
					{
						$Partyqry="";
						$Partyclause="";
						$SubPartyqry="";
						$SubPartyclause="";
					}
					$query=mysqli_query($con2,"select party_billing.cBillingCode, party_billing.dBillingDate ,party_billing.dBillingDate, party_billing.iBillingID,  party_billing.fBillAmt , party_billing_detail.iNoPcsDisp, party_billing_detail.fRate , party_master.cPartyName as cPartyName , ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName from party_billing join party_billing_detail on party_billing.iBillingID=party_billing_detail.iBillingID left join party_master on party_billing.iPartyCode =party_master.iPartyID  left join  party_master_detail on party_billing.iPartyCode =party_master_detail.iPartyID  and party_billing.iFirmSNo = party_master_detail.iSNo left join company_master_detail on party_billing.iCompanySNo= company_master_detail.iSNo left join company_master on party_billing.iCompanyCode=company_master.iPartyID and party_billing.iCompanySNo =0 ".$SubCompanyqry.$Partyqry.$SubPartyqry." where iBillingType ='2' ".$SubCompanyclause.$Partyclause.$SubPartyclause."order by iBillingID DESC");
					if(!$query)
					{
						$Err=true;
						$ErrMsg="Something went wrong please try again";
					}
					
				}
				else if(isset($_POST['FromDate']) && isset($_POST['ToDate']))
				{
					
					
					$query=mysqli_query($con2,"select party_billing.cBillingCode,party_billing.dBillingDate, party_billing.iBillingID,  party_billing.fBillAmt , party_billing_detail.iNoPcsDisp, party_billing_detail.fRate ,ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName ,  party_master.cPartyName as cPartyName from party_billing join party_billing_detail on party_billing.iBillingID=party_billing_detail.iBillingID left join party_master on party_billing.iPartyCode =party_master.iPartyID  left join  party_master_detail on party_billing.iPartyCode =party_master_detail.iPartyID  and party_billing.iFirmSNo = party_master_detail.iSNo  left join company_master_detail on party_billing.iCompanySNo= company_master_detail.iSNo left join company_master on party_billing.iCompanyCode=company_master.iPartyID and party_billing.iCompanySNo=0  where iBillingType ='2' and dBillingDate between  \"$BeforeDate\" and \"$CurrDate\" order by iBillingID DESC");
					if(!$query)
					{
						$Err=true;
						$ErrMsg="Something went wrong please try again";
					}
					
				}
				else
				{
					
					$query=mysqli_query($con2,"select party_billing.cBillingCode,party_billing.dBillingDate, party_billing.iBillingID,  party_billing.fBillAmt , party_billing_detail.iNoPcsDisp, party_billing_detail.fRate ,ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName ,  party_master.cPartyName as cPartyName from party_billing join party_billing_detail on party_billing.iBillingID=party_billing_detail.iBillingID left join party_master on party_billing.iPartyCode =party_master.iPartyID  left join  party_master_detail on party_billing.iPartyCode =party_master_detail.iPartyID  and party_billing.iFirmSNo = party_master_detail.iSNo  left join company_master_detail on party_billing.iCompanySNo= company_master_detail.iSNo left join company_master on party_billing.iCompanyCode=company_master.iPartyID and party_billing.iCompanySNo=0  where iBillingType ='2' order by iBillingID DESC");
					if(!$query)
					{
						$Err=true;
						$ErrMsg="Something went wrong please try again";
					}
				}
	}
	
    
?>




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
<Form name="frmBurada" method="POST" action="" onLoad="PageLoad('<?php if(isset($_POST['Type']) && ($_POST['Type']=='Party')) echo "billing_edit.php?Type=$_POST[Type]&PartyCode=$_POST[PartyCode]"; else if(isset($_POST['Type']) && ($_POST['Type']=='Date')) echo "billing_edit.php?Type=$_POST[Type]&FromDate=$_POST[FromDate]&ToDate=$_POST[ToDate]"; else if (isset($_POST['Type']) && ($_POST['Type']=='Company')) echo "billing_edit.php?Type=$_POST[Type]&CompanyCode=$_POST[CompanyCode]&CompanySNo=$_POST[CompanySNo]"; else echo "billing_edit.php"; ?>');">
 <input type="hidden" name="auth_info" id="auth_info" value="<?=$_POST['auth_info']?>">
	<div class="row">
	   <div class="col-sm-6">
	     <span class="float-left">
		    <input type="radio" name="rdb" id="rdNew" value="New" onClick="Switch(this.id)">&nbsp;New Burada Sale
			<input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)" checked >&nbsp;List Burada Sale
		 </span>
	   </div>
	   <div class="col-sm-6">
	     <span class="float-right" id='tdLastBillNo'>
			<?php
				$CodeMsg="";
				$result=mysqli_query ($con2,"select cBillingCode, dBillingDate from party_billing where iBillingID=(select MAX(iBillingID) from party_billing)");
				if ($result)
				{
					if (mysqli_num_rows($result)>0)
					{
						$row=mysqli_fetch_array($result);
						$d=explode('-',$row['dBillingDate']);
						$bdate=$d[2].'/'.$d[1].'/'.$d[0];
						$CodeMsg="Last Billing No : ".$row['cBillingCode']."<br> Date : $bdate";
					}
					else
					{
						$CodeMsg="No Billing saved before...";
					}
				}	
				print $CodeMsg;
			?>
		 </span>
	   </div>
	</div>
	<div class="row">
	  <div class="col-sm-6">
	    <label for="cmbSelCompany">Company</label>
	    <select name="cmbSelCompany" id="cmbSelCompany"  class="form-control" onchange="ChangeLastBill(this.id)"   tabindex=1>
				<option value=""><?=str_repeat("-",20) ?>Select Company<?=str_repeat("-",20) ?></option>
				<?php
					
					$result=mysqli_query($con2,"select company_master.iPartyID, (0) as iSNo, concat(iPartyID,'~ArrayItem~','0') as PartyID , cPartyName as cPartyName from company_master UNION select  company_master_detail.iPartyID, iSNo,concat(iPartyID,'~ArrayItem~',iSNo) as PartyID , cFirmName as cPartyName from company_master_detail ");
					if ($result && mysqli_num_rows($result)>0)
					{
						if ($_POST['CompanySNo']=="") $CompanySNo=0 ;else $CompanySNo=$_POST['CompanySNo'];
						while ($row=mysqli_fetch_array($result))
						{
							$PartyID=$row['PartyID'];
							$result1=mysqli_query ($con2,"select cBillingCode, dBillingDate from party_billing where iBillingID=(select MAX(iBillingID) from party_billing where iCompanyCode=\"$row[iPartyID]\" and iCompanySNo=\"$row[iSNo]\")");
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
							if ($row['PartyID']==$_POST['CompanyCode']."~ArrayItem~".$CompanySNo) print "selected";
							print ">$row[cPartyName]</option>";
						}
					}
				?>
			</select>
	  </div>
	  <div class="col-sm-3">
	   <label for="txtFromDate">From Date</label>
	   <input type="hidden" name="hfCurrDate" id="hfCurrDate" >
	   <input type="date" name="txtFromDate" id="txtFromDate" class="form-control"  value="<?=$BeforeDate; ?>" onFocus="this.blur()" readonly>
	  </div>
	  <div class="col-sm-3">
	     <label for="txtToDate">To Date</label>
	     <input type="date" name="txtToDate" id="txtToDate" class="form-control" value="<?=$CurrDate; ?>"  onFocus="this.blur()" readonly>
	  </div>
	</div>
	<div class="row">
	  <div class="col-sm-6">
	  <label for="cmbSelParty">Main Party</label>
	    <select name="cmbSelParty" class="form-control" id="cmbSelParty"  tabindex=2 onchange="SelParty('')" >
			<option value=""><?php print str_repeat("-",25) ?>Select Party<?php print str_repeat("-",25) ?></option>
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
						if ($_POST['MainParty']==$row['PartyID'])	print "selected";
						print ">$row[cPartyName]</option>";
						$PartyData="";
					}
				}
			?>
			
		</select>
	  </div>
	  <div class="col-sm-6">
	    <label for="cmbSelSubParty">Sub Party</label>
	    <select name="cmbSelSubParty" id="cmbSelSubParty"  class="form-control" tabindex=3>
			<option value=""><?php print str_repeat("-",20) ?>Select SubParty<?php print str_repeat("-",20) ?></option>
			
		</select>
	  </div>
	  <div class="col-sm-12">
	  <br>
	   <?php
	   if($__view==true)
	   {
	   ?>
	   <input type="button" name="btnGetData" id="btnGetData" class="btn btn-primary waves-effect waves-light float-right" value="Get Data" class="btncss" onClick="getData(this.id)">
	   <?php
	   }
	   ?>
	  </div>
	  <div class="col-sm-12">
	  <br>
	   <table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed"  id="example3">
	     <thead>
		   <tr>
		     <th>S No</th>
		     <th>Billing Code</th>
		     <th>Date</th>
		     <th>Company Name</th>
		     <th>Party Name</th>
		     <th>Qty</th>
		     <th>Rate</th>
		     <th>Value</th>
		     <th>Action</th>
		   </tr>
		 </thead>
	     <tbody>
	   <?php
	   $sno=1;
	     while($row=mysqli_fetch_array($query))
		 {
			 ?>
			 <tr>
			   <td><?=$sno?></td>
			   <td><?=$row['cBillingCode']?></td>
			   <td><?=$row['dBillingDate']?></td>
			   <td><?=$row['cName']?></td>
			   <td><?=$row['cPartyName']?></td>
			   <td><?=$row['iNoPcsDisp']?></td>
			   <td><?=$row['fRate']?></td>
			   <td><?=$row['fBillAmt']?></td>
			   <td>
			     <?php
				 if($__update==true)
				 {
				 ?>
			     <button type="button" class="btn btn-primary waves-effect waves-light" value="<?=base64_encode(base64_encode($row['iBillingID']))?>" onclick="editOrder(this.value)">Edit</button>
			     <?php
				 }
				 if($__view==true)
				 {
				 ?>
				 <button type="button"  class="btn btn-secondary waves-effect waves-light"  value="<?=base64_encode(base64_encode($row['iBillingID']))?>" onclick="LinkUrlClick(this.value)"><i class="fas fa-print"></i></button>
			     <?php
				 }
				 ?>
			   </td>
			 </tr>
			 <?php
			 $sno++;
		 }
	   ?>
	    </tbody>
	   </table>
	  </div>
	</div>
	
</form>	
<!---display table content here---------------------->
</div>

</div>

<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script type="text/javascript" language="javascript" src="../js/party_burada.js"></script>
<script type="text/javascript" language="JavaScript">
function ChangeLastBill(Id)
{
	company=(document.getElementById("cmbSelCompany").value).split('~ArrayItem~');
	if(company[2]!='')
	{
		document.getElementById('tdLastBillNo').innerHTML="Last Billing No : "+company[2]+"<br> Date : "+company[3];
	}
	else
	{
		document.getElementById('tdLastBillNo').innerHTML="No Billing saved before..."	
	}
}
function editOrder(Id)
{
	$.ajax({
		   url:'operations/party_burada.php',
		   type:'post',
		   data:{"auth_info":$("#auth_info").val(),"iBillingID":Id},
		   success(response)
		   {
			  $('.page-content').html(response);
		   },error(response)
		   {
			   toastr.error(response.status);
		   }
	})
}
function getData()
{
	var validata=true;
	var errmsg="";
	var focusid="";
	if($("#cmbSelCompany").val()==='')
	{
		validata=false;
		errmsg="Please Choose company";
	}
	if(validata==false)
	{
		toastr.error(errmsg);
	}
	else
	{
		FromDate=document.getElementById('txtFromDate').value;
		ToDate=document.getElementById('txtToDate').value;
		company=(document.getElementById("cmbSelCompany").value).split('~ArrayItem~');
		if (company=="")
		{
			companysno="";
		}
		else
		{
			companysno=company[1];
		}
		PartyId=(document.getElementById('cmbSelParty').value).split('~ArrayItem~');	
		SubParty=document.getElementById("cmbSelSubParty").value;
		if ((company=="") && (PartyId=="") && (SubParty==""))
		{
			var dataString={"auth_info":$("#auth_info").val(),"FromDate":FromDate,"ToDate":ToDate};
			//document.location.href="../operations/party_burada_edit.php?FromDate="+FromDate+"&ToDate="+ToDate;
		}
		else
		{
			var dataString={"auth_info":$("#auth_info").val(),"FromDate":FromDate,"ToDate":ToDate,"MainParty":PartyId[0],"CompanyCode":company[0],"CompanySNo":companysno,"SubParty":SubParty};
			//document.location.href="../operations/party_burada_edit.php?FromDate="+FromDate+"&ToDate="+ToDate+"&MainParty="+PartyId[0]+"&CompanyCode="+company[0]+"&CompanySNo="+companysno+"&SubParty="+SubParty;
		}
		
		$.ajax({
			   url:'operations/party_burada_edit.php',
			   type:'POST',
			   data:dataString,
			   success(response)
			   {
				  $('.page-content').html(response);
			   },error(response)
			   {
				   alert(response);
			   }
		})
	}
	
	
	
}

function SelParty(ID)
{
	val=document.getElementById("cmbSelParty").value;
	if (val!="")
	{
		i=document.getElementById("cmbSelParty").options[document.getElementById("cmbSelParty").selectedIndex].text;
		Itemctrl=document.getElementById("cmbSelSubParty");
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
}

function LinkUrlClick(Id)
{
	
	URL="operations/burada_invoice_showpdf.php?iBillingID="+Id+"&auth_info="+'<?=trim($_POST[auth_info])?>';
	//alert (URL);
	
	OpenWin=window.open(URL,"Report","toolbar=no,menubar=no,resizable=yes,location=no,scrollbars=no,width=1024,height=700");
	event.returnValue=false;
}

</script>
<script>
  $(function () {
    $("#datatable").DataTable({
      "responsive": true,
      "autoWidth": false,
	  "paging": true,
	  "searching": true,
    });
    $('#example3').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>