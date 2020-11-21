<?php
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
		$BeforeDate = date('Y-m-d', strtotime('-10 days', strtotime($CurrDate)));
	    $txtBillingUser=base64_decode(base64_decode(trim($_POST['txtBillingUser'])));
	}
	
		
?>
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


<?php
	 
	
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
	

if(!empty($txtBillingUser))
{
   
if (isset($_POST['CompanyCode']) || isset($_POST['CompanySNo']) || isset($_POST['MainParty']) || isset($_POST['SubParty']) )
{
	$FromDate=trim($_POST['FromDate']);
	$BeforeDate=trim($_POST['FromDate']);
	$dArr=explode("/",$FromDate);
	$fdate="$dArr[2]-$dArr[1]-$dArr[0]";
	$ToDate=trim($_POST['ToDate']);
	$CurrDate=$_POST['ToDate'];
	$tArr=explode("/",$ToDate);
	$tdate="$tArr[2]-$tArr[1]-$tArr[0]";
	if (isset($_POST['CompanySNo']) && $_POST['CompanySNo']!="")
	{
		$SubCompanyqry="";
		$SubCompanyclause=" and $PartyBilling.iCompanySNo =\"".$_POST['CompanySNo']."\" ";
		$OldSubCompanyclause=" and old_$PartyBilling.iCompanySNo =\"".$_POST['CompanySNo']."\" ";
	}
	else
	{
		$Companyqry="";
		$Companyclause="";
		$CompanySNo=0;
		$SubCompanyqry="";
		$SubCompanyclause="";
		$OldSubCompanyclause="";
	}
	if (isset($_POST['MainParty']) && $_POST['MainParty']!="")
	{
		$PartyCode=$_POST['MainParty'];
		if (isset($_POST['SubParty']) && $_POST['SubParty']!="")
		{
			$PartySNo=$_POST['SubParty'];
			$SubPartyclause=" and $PartyBilling.iFirmSNo=\"".$_POST['SubParty']."\" ";
			$OldSubPartyclause=" and old_$PartyBilling.iFirmSNo=\"".$_POST['SubParty']."\" ";
		}
		else
		{
			$PartySNo=0;
			$SubPartyqry="";
			$SubPartyclause="";
			$OldSubPartyclause="";
		}
		$Partyclause=" and $PartyBilling.iPartyCode =\"".$_POST['MainParty']."\"  ";
		$OldPartyclause=" and old_$PartyBilling.iPartyCode =\"".$_POST['MainParty']."\"  ";
	}
	else
	{
		$Partyqry="";
		$Partyclause="";
		$SubPartyqry="";
		$SubPartyclause="";
	}
	
	if ($txtBillingUser=="2")
	{
		
		//die("kacha bill");
		$query=mysqli_query($con2,"select if($PartyBilling.iFirmSNo >0 , party_master_detail.cFirmName, party_master.cPartyName ) as cPartyName ,'' as tablePrefix , SUM(iNoPcsDisp) as Disp ,$PartyBilling.cBillingCode, $PartyBilling.cRemarks ,$PartyBilling.iBillingID, dBillingDate,fBillAmt ,ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName from $PartyBilling join $PartyBillingDetail on $PartyBilling.iBillingID = $PartyBillingDetail.iBillingID join party_master on $PartyBilling.iPartyCode =party_master.iPartyID left join  party_master_detail on $PartyBilling.iPartyCode =party_master_detail.iPartyID  and $PartyBilling.iFirmSNo = party_master_detail.iSNo left join company_master_detail on $PartyBilling.iCompanySNo= company_master_detail.iSNo left join company_master on $PartyBilling.iCompanyCode=company_master.iPartyID and $PartyBilling.iCompanySNo =0 ".$Companyqry.$SubCompanyqry.$Partyqry.$SubPartyqry." where dBillingDate  between \"$BeforeDate\" and \"$CurrDate\" and iBillingType<>2 and bDeleted<>1 ".$Companyclause.$SubCompanyclause.$Partyclause.$SubPartyclause." group by $PartyBilling.iBillingID");		
		
		
	}
	else
	{
		$query=mysqli_query($con2,"select if($PartyBilling.iFirmSNo >0 , party_master_detail.cFirmName, party_master.cPartyName ) as cPartyName ,'' as tablePrefix , SUM(iNoPcsDisp) as Disp ,$PartyBilling.cBillingCode , $PartyBilling.cRemarks ,$PartyBilling.iBillingID, dBillingDate,fBillAmt ,ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName from $PartyBilling join $PartyBillingDetail on $PartyBilling.iBillingID = $PartyBillingDetail.iBillingID join party_master on $PartyBilling.iPartyCode =party_master.iPartyID left join  party_master_detail on $PartyBilling.iPartyCode =party_master_detail.iPartyID  and $PartyBilling.iFirmSNo = party_master_detail.iSNo  left join company_master_detail on $PartyBilling.iCompanySNo= company_master_detail.iSNo left join company_master on $PartyBilling.iCompanyCode=company_master.iPartyID and $PartyBilling.iCompanySNo =0 ".$Companyqry.$SubCompanyqry.$Partyqry.$SubPartyqry." where dBillingDate  between '$BeforeDate' and '$CurrDate' and iBillingType<>2".$Companyclause.$SubCompanyclause.$Partyclause.$SubPartyclause." group by $PartyBilling.iBillingID");	
		
		
	}
}
else if(isset($_POST['FromDate']) && isset($_POST['ToDate']))
{
	$FromDate=$_POST['FromDate'];
	$BeforeDate=$_POST['FromDate'];
	$dArr=explode("/",$FromDate);
	$fdate="$dArr[2]-$dArr[1]-$dArr[0]";
	$ToDate=$_POST['ToDate'];
	$CurrDate=$_POST['ToDate'];
	$tArr=explode("/",$ToDate);
	$tdate="$tArr[2]-$tArr[1]-$tArr[0]";

	if ($txtBillingUser=="2")
	{
		$query=mysqli_query($con2,"select if($PartyBilling.iFirmSNo >0 , party_master_detail.cFirmName, party_master.cPartyName ) as cPartyName ,'' as tablePrefix , SUM(iNoPcsDisp) as Disp ,$PartyBilling.cBillingCode ,$PartyBilling.iBillingID,  $PartyBilling.cRemarks , dBillingDate ,fBillAmt ,ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName  from $PartyBilling join k_party_billing_detail on $PartyBilling.iBillingID = k_party_billing_detail.iBillingID join party_master on $PartyBilling.iPartyCode =party_master.iPartyID left join company_master_detail on $PartyBilling.iCompanySNo= company_master_detail.iSNo left join  party_master_detail on $PartyBilling.iPartyCode =party_master_detail.iPartyID  and $PartyBilling.iFirmSNo = party_master_detail.iSNo left join company_master on $PartyBilling.iCompanyCode=company_master.iPartyID and $PartyBilling.iCompanySNo =0 where dBillingDate  between \"$BeforeDate\" and \"$CurrDate\" and iBillingType<>2  and bDeleted<>1 group by $PartyBilling.iBillingID");
		
		
		
	}
	else
	{
		$query=mysqli_query($con2,"select if($PartyBilling.iFirmSNo >0 , party_master_detail.cFirmName, party_master.cPartyName ) as cPartyName,'' as tablePrefix , SUM(iNoPcsDisp) as Disp ,party_billing.cBillingCode ,concat(party_billing.iBillingID, '') as iBillingID,  party_billing.cRemarks , dBillingDate ,fBillAmt ,ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName  from party_billing join party_billing_detail on party_billing.iBillingID = party_billing_detail.iBillingID join party_master on party_billing.iPartyCode =party_master.iPartyID left join company_master_detail on party_billing.iCompanySNo= company_master_detail.iSNo left join  party_master_detail on $PartyBilling.iPartyCode =party_master_detail.iPartyID  and $PartyBilling.iFirmSNo = party_master_detail.iSNo left join company_master on party_billing.iCompanyCode=company_master.iPartyID and party_billing.iCompanySNo =0 where dBillingDate  between \"$BeforeDate\" and \"$CurrDate\" and iBillingType<>2 group by party_billing.iBillingID ");
		
	}
}
else
{
	
	// for kacha bill
	if ($txtBillingUser=="2")
	{
		
		$query=mysqli_query($con2,"select if($PartyBilling.iFirmSNo >0 , party_master_detail.cFirmName, party_master.cPartyName ) as cPartyName ,'' as tablePrefix , SUM(iNoPcsDisp) as Disp ,$PartyBilling.cBillingCode ,concat($PartyBilling.iBillingID, '') as iBillingID , $PartyBilling.cRemarks, dBillingDate ,fBillAmt, ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName  from $PartyBilling join $PartyBillingDetail on $PartyBilling.iBillingID  = $PartyBillingDetail.iBillingID join party_master on $PartyBilling.iPartyCode =party_master.iPartyID left join  party_master_detail on $PartyBilling.iPartyCode =party_master_detail.iPartyID  and $PartyBilling.iFirmSNo = party_master_detail.iSNo left join company_master_detail on $PartyBilling.iCompanySNo= company_master_detail.iSNo left join company_master on $PartyBilling.iCompanyCode=company_master.iPartyID and $PartyBilling.iCompanySNo =0 where dBillingDate  between \"$BeforeDate\" and \"$CurrDate\" and iBillingType<>2  and bDeleted<>1 group by $PartyBilling.iBillingID");
		
		
	}
	else
	{
		$query=mysqli_query($con2,"select if($PartyBilling.iFirmSNo >0 , party_master_detail.cFirmName, party_master.cPartyName ) as cPartyName, '' as tablePrefix , SUM(iNoPcsDisp) as Disp ,$PartyBilling.cBillingCode ,concat($PartyBilling.iBillingID,'') as iBillingID , $PartyBilling.cRemarks, dBillingDate ,fBillAmt, ifnull(company_master_detail.cFirmName, company_master.cPartyName) as cName  from $PartyBilling join $PartyBillingDetail on $PartyBilling.iBillingID  = $PartyBillingDetail.iBillingID join party_master on $PartyBilling.iPartyCode =party_master.iPartyID left join  party_master_detail on $PartyBilling.iPartyCode =party_master_detail.iPartyID  and $PartyBilling.iFirmSNo = party_master_detail.iSNo left join company_master_detail on $PartyBilling.iCompanySNo= company_master_detail.iSNo left join company_master on $PartyBilling.iCompanyCode=company_master.iPartyID and $PartyBilling.iCompanySNo =0 where iBillingType<>2 and $PartyBilling.dBillingDate  between \"$BeforeDate\" and \"$CurrDate\" group by $PartyBilling.iBillingID");
		
	}
}
}
?>
<script>
$("document").ready(function(){
	if($("#txtBillingUser").val()==='')
	{
		$(".bs-example-modal-center").modal({
			backdrop: 'static',
			keyboard: false
		});
		
	}
	$("#example3").dataTable().fnDestroy();
					$('#example3').DataTable({
						
					  "paging": true,
					  "lengthChange": true,
					  "searching": true,
					  "ordering": true,
					  "info": true,
					  "autoWidth": false,
					  "responsive": true,
					});
	
})

function deleterecord(BillingData)
{
	preloadfadeIn();
	$(".delete").attr("disabled",true);
	$.ajax({
		   url:'operations/delete_billing_data.php',
		   type:'POST',
		   data:{"auth_info":$("#auth_info").val(),"txtBillingData":BillingData,"txtBillingUser":'<?=trim($_POST[txtBillingUser])?>'},
		   success:function(response)
		   {
			   
			   var responseText=JSON.parse(response);
			   if(responseText.error)
			   {
				   toastr.error(responseText.error_msg);
				   $(".delete").attr("disabled",false);
				   preloadfadeOut();
			   }
			   else
			   {
				   toastr.success(responseText.error_msg);
				   getData("btnGetData");
				   $(".delete").attr("disabled",false);
			   }
			   
			   
	               
		   },
		   error:function(response)
		   {
			   
			   if(response.status=='404')
			   {
				   toastr.error("Page not found");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
			   else if(response.status=='500')
			   {
				   toastr.error("Internal server error");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
			   else
			   {
				   toastr.error("Communication error");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
		   }
	})
}
</script>
<script>
function editBill(BillingData)
{
	preloadfadeIn();
	$(".edit").attr("disabled",true);
	$.ajax({
		   url:'operations/billing.php',
		   type:'POST',
		   data:{"auth_info":$("#auth_info").val(),"txtBillingData":BillingData,"txtBillingUser":'<?=trim($_POST[txtBillingUser])?>'},
		   success:function(response)
		   {
			   
			   $(".edit").attr("disabled",false);
			   $('.page-content').html(response);
	           preloadfadeOut();
		   },
		   error:function(response)
		   {
			   
			   if(response.status=='404')
			   {
				   toastr.error("Page not found");
				   preloadfadeOut();
	               $(".edit").attr("disabled",false);
			   }
			   else if(response.status=='500')
			   {
				   toastr.error("Internal server error");
				   preloadfadeOut();
	              $(".edit").attr("disabled",false);
			   }
			   else
			   {
				   toastr.error("Communication error");
				   preloadfadeOut();
	               $(".edit").attr("disabled",false);
			   }
		   }
	})
}
</script>
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			
			<div class="modal-body">
				<div class="row">
				  <div class="col-md-12">
				    <label for="">UserName</label>
					<input type="text" class="form-control" name="username" id="username">
					<label for="">Password</label>
					<input type="password" class="form-control" name="password" id="password">
					
				  </div>
				  <div class="col-md-12" style="margin-top:20px">
				    <button type="button" class="btn btn-primary waves-effect waves-light float-right" id="btnLogin" name="btnLogin">Log In</button>
				  </div>
				</div>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<Form name="frmPartyOrder" method="POST" action="">
<input type="hidden" name="hdType" id="hdType" value="<?=$Type;?>">
<input type="hidden" name="hfCurrDate" id="hfCurrDate" >
<input type="hidden" id="txtBillingUser" name="txtBillingUser" value="<?=$_POST['txtBillingUser']?>">
<input type="hidden" id="auth_info" name="auth_info" value="<?=trim($_POST['auth_info'])?>">
<div class="row">
  <div class="col-sm-6">
    <span class="float-left">
	   <input type="radio" name="rdb" id="rdNew" value="New" onClick="Switch(this.id)">&nbsp;New Billing&nbsp;&nbsp;
	   <input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)" checked >&nbsp;List Billing
	</span>
  </div>
  <div class="col-sm-6">
    <span class="float-right" id="tdLastBillNo">
	    
		<?php
			
			$CodeMsg="";
			$result=mysqli_query($con2,"select cBillingCode, dBillingDate from party_billing where iBillingID=(select MAX(iBillingID) from party_billing)");
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
			echo  $CodeMsg;
		?>
									
	</span>
  </div>
</div>
<div class="row">
	  <div class="col-sm-6">
	    <fieldset>
		  <legend>Date Range</legend>
		  <div class="row">
		  <div class="col-md-6">
		     <label for="">From Date</label>
		     <input type="date" name="txtFromDate" id="txtFromDate" class="form-control"  value="<?php print $BeforeDate; ?>"  onFocus="this.blur()">

		  </div>
		  <div class="col-md-6">
		     <label for="">To Date</label>
			 <input type="date" name="txtToDate" id="txtToDate" class="form-control"  value="<?php print $CurrDate; ?>"  onFocus="this.blur()">
		  </div>
		  
		  <div class="col-md-12" style="margin-top:20px">
		   <label for="">Main Party</label>
		    <select name="cmbSelParty" id="cmbSelParty"   class="form-control" tabindex=2 onchange="SelParty('')" >
					<option value=""><?php print str_repeat("-",25) ?>Select Party<?php print str_repeat("-",25) ?></option>
					<?php
						
						$result=mysqli_query($con2,"select (iPartyID) as PartyID, iPartyID , cPartyName from party_master order by cPartyName");
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
								print "<option value=\"$row[PartyID]~ArrayItem~$PartyData\"";
								if ($_POST['MainParty']==$row['PartyID'])	print "selected";
								print ">$row[cPartyName]</option>";
								$PartyData="";
							}
						}
					?>
																
			</select>
		  </div>
		  </div>
		 		  
		  
		</fieldset>
	  </div>
	  <div class="col-sm-6">
	      <fieldset>
		    <legend>Filter</legend>
			<label for="cmbSelCompany">Company</label>
			<select name="cmbSelCompany" id="cmbSelCompany" class="form-control" onchange="ChangeLastBill(this.id)"  tabindex=1 style="margin-bottom:20px">
					<option value=""><?php print str_repeat("-",20) ?>Select Company<?php print str_repeat("-",20) ?></option>
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
								if ($row['PartyID']==$_POST['CompanyCode']."~ArrayItem~".$CompanySNo) print "selected";
								print ">$row[cPartyName]</option>";
							}
						}
					?>
				</select>
			<label for="cmbSelSubParty">Sub Party</label>
			<select name="cmbSelSubParty" id="cmbSelSubParty" class="form-control"  tabindex=3>
				<option value=""><?=str_repeat("-",20) ?>Select SubParty<?=str_repeat("-",20) ?></option>
			</select>
		  </fieldset>
		 
	  </div>
	   <div class="col-sm-12" style="margin-top:20px;margin-bottom:20px">
	      <?php
		    if($__view==true)
			{
		  ?>
		    <input type="button" name="btnGetData" id="btnGetData" value="Get Data" class="btn btn-primary waves-effect waves-light float-right" onClick="getData(this.id)">
	      <?php
			}
		  ?>
	   </div>
	   
</div>
	
</form>	
<div class="row">
<div class="col-md-12" id="TableView" style="margin-top:20px">
		    <table class="table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable" id="example3">
			  <thead>
			    <tr>
				  <th>S No</th>
				  <th>Date</th>
				  <th>Party Name</th>
				  <th>Amount</th>
				  <th>Remarks</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
			    <?php
				$i=1;
				  while($row=mysqli_fetch_array($query))
				  {
					  $Data=$row['iBillingID'];
				      $Data=base64_encode(base64_encode($Data));
					  ?>
                       <tr>
					     <td><?=$i?></td>
					     <td><?=$row['dBillingDate']?></td>
					     <td><?=$row['cPartyName']?></td>
					     <td><?=$row['fBillAmt']?></td>
					     <td><?=$row['cRemarks']?></td>
					     <td>
						 <?php
						 if($__update==true)
						 {
						 ?>
						 <button type="button" class="btn btn-primary btn-sm waves-effect waves-light edit" onclick="editBill('<?=$Data?>')">Edit</button>
						 &nbsp;
						 <?php
						 }
						 if($__delete==true)
						 {
						 ?>
						 <button type="button" class="btn btn-primary btn-sm waves-effect waves-light delete" onclick="deleterecord('<?=$Data?>')"><i class="fas fa-trash"></i></button>
						 &nbsp;
						 <?php
						 }
						 if($__view==true)
						 {
						 ?>
						 <button type="button" class="btn btn-primary btn-sm waves-effect waves-light print" onclick="LinkUrlClick('<?=$Data?>')"><i class="fas fa-print"></i></button>
                          <?php
						 }
						  ?>						
						</td>
					     
					   </tr>					  
					  <?php
				$i++;
				  }
				?>
			  </tbody>
			</table>
	   </div>
</div>

</div>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script type="text/javascript" language="javascript" src="../js/string.js"></script>
<script type="text/javascript" language="javascript" src="../js/typeaheadcombo.js"></script>
<script type="text/javascript" language="javascript" src="../js/billing.js"></script>
<script language="JavaScript" type="text/javascript">
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
function getData(Id)
{
	preloadfadeIn();
	$("#btnGetData").attr("disabled",true);
	var url;
	FromDate=document.getElementById('txtFromDate').value;
	ToDate=document.getElementById('txtToDate').value;
	company=(document.getElementById("cmbSelCompany").value).split('~ArrayItem~');
	if (company!="")
	{
		companysno=company[1];
	}
	else
	{
		companysno="";
	}

	PartyId=(document.getElementById('cmbSelParty').value).split('~ArrayItem~');	
	SubParty=document.getElementById("cmbSelSubParty").value;
	if ((company=="") && (PartyId=="") && (SubParty==""))
	{
		dataString={"FromDate":FromDate,"ToDate":ToDate,"txtBillingUser":'<?=$_POST[txtBillingUser]?>',"auth_info":'<?=$_POST[txtBillingUser]?>'};
		//document.location.href="../operations/billing_edit.php?FromDate="+FromDate+"&ToDate="+ToDate;
	}
	else
	{
		dataString={"FromDate":FromDate,"ToDate":ToDate,"MainParty":PartyId[0],"CompanyCode":company[0],"CompanySNo":companysno,"SubParty":SubParty,"txtBillingUser":'<?=$_POST[txtBillingUser]?>',"auth_info":'<?=$_POST[txtBillingUser]?>'};
		// document.location.href="../operations/billing_edit.php?FromDate="+FromDate+"&ToDate="+ToDate+"&MainParty="+PartyId[0]+"&CompanyCode="+company[0]+"&CompanySNo="+companysno+"&SubParty="+SubParty;
	}
	$.ajax({
		   url:'operations/billing_edit.php',
		   type:'POST',
		   data:dataString,
		   success:function(str)
		   {
			   $('.page-content').html(str);
			   $("#btnGetData").attr("disabled",false);
			    preloadfadeOut();
		   },error:function(xhttp)
		   {
			   $("#btnGetData").attr("disabled",false);
			   preloadfadeOut();
		   }
	})
}


function LinkUrlClick(Data)
{ 
    if('<?=$txtBillingUser?>'=='1')
	{
		viewurl="operations/billing_showpdf.php";
		URL=viewurl+"?Data="+Data+"&State=yes";
		OpenWin=window.open(URL,"Report","toolbar=no,menubar=no,resizable=yes,location=no,scrollbars=no,width=1024,height=700");
		event.returnValue=false;
	}
	else if('<?=$txtBillingUser?>'=='2')
	{
		viewurl="operations/kbilling_showpdf.php";
		URL=viewurl+"?Data="+Data+"&State=yes";
		OpenWin=window.open(URL,"Report","toolbar=no,menubar=no,resizable=yes,location=no,scrollbars=no,width=1024,height=700");
		event.returnValue=false;
	}
    else
	{
		toastr.error("invalid request");
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
</script>