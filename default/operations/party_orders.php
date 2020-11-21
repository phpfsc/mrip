 <?php
    session_start(); 
	require ('../../config.php');
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

		if(!empty(trim($_POST['updatedData'])))
		{
			 $updatedData=trim($_POST['updatedData']);
		}
		
		$PageMode="New";
		$ErrMsg=array();
		$Err=false;
		$PartyName="";
		$ContactPerson="";
		$PhoneNo="";
		$MobileNo="";
		$FaxNo="";
		$TINNo="";
		$STNo="";
		$gstCode="";
		$hsnCode="";
		$CSTNo ="";
		$Address="";
		$Transporter="";
		$Remarks="";
		
		$ItemData="";

		$PageMode="Edit";
		$result=mysqli_query($con2,"select * from company_master");
		if ($result && mysqli_num_rows($result)>0)
		{
			while ($row=mysqli_fetch_array($result))
			{
				$iPartyID=$row['iPartyID'];
				$PartyName=$row['cPartyName'];
				$ContactPerson=$row['cContactPerson'];
				$PhoneNo=$row['cPhone'];
				$MobileNo=$row['cMobile'];
				$FaxNo=$row['cFax'];
				$TINNo=$row['cTINNo'];
				$STNo=$row['cSTNo'];
				$InvoiceSerial=$row['iInvoiceSerial'];
				$InvoiceCode=$row['cInvoiceCode'];
				$gstCode =$row['cGSTIn'];
				$hsnCode =$row['cHSNCode'];
				$CSTNo =$row['cCSTNo'];
				$Address=$row['cAddress'];
				$Transporter=$row['cTransporter'];
				$Remarks=$row['cRemarks'];
			}
			$result1=mysqli_query($con2,"select * from company_master_detail order by iSNo ");
			if ($result1 && mysqli_num_rows($result1)>0)
			{
				while ($row1=mysqli_fetch_array($result1))
				{
					$FirmName=$row1['cFirmName'];
					$FrimPhNo=$row1['cFirmPhNo'];
					$FirmAddress=$row1['cFirmAddress'];
					$FirmTINNO=$row1['cFirmTINNO'];
					$FirmgstCode=$row1['cGSTIn'];
					$FirmhsnCode=$row1['cHSNCode'];
					$FirmInvoiceCode=$row1['cFirmInvoiceCode'];
					$FirmInvoiceSerial=$row1['iFirmInvoiceSerial'];
					if ($ItemData=="")
					{
						$ItemData="$FirmName~ArrayItem~$FrimPhNo~ArrayItem~$FirmAddress~ArrayItem~$FirmTINNO~ArrayItem~$FirmInvoiceCode~ArrayItem~$FirmInvoiceSerial~ArrayItem~$FirmgstCode~ArrayItem~$FirmhsnCode";
					}
					else
					{
						$ItemData=$ItemData."~Array~$FirmName~ArrayItem~$FrimPhNo~ArrayItem~$FirmAddress~ArrayItem~$FirmTINNO~ArrayItem~$FirmInvoiceCode~ArrayItem~$FirmInvoiceSerial~ArrayItem~$FirmgstCode~ArrayItem~$FirmhsnCode";
					}
				}
				
			}
			else
			{
				
				$ItemData="";
			}
		}
		else
	   {
			$PageMode="New";	
	   }

   
		
	}
	
	
?>

<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Masters</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Company Master</li>
			</ol>
		</div>

	</div>
</div>
</div>
<!-- end page title -->

<div class="row">

<?php
if($Err==true)
{
	die($ErrMsg);
	
}

?>
<div class="col-xl-12">
	<div class="card">
		<div class="card-body">
		
		<form name="frmPartyOrder" id="frmPartyOrder" method="POST">
		<input type="hidden" id='hfPageMode' name='hfPageMode' value="<?=$PageMode;?>" />
		<input type="hidden" id='auth_info' name='auth_info' value="<?=trim($_POST['auth_info']) ?>" />
		<input type="hidden" id="hdPartyID" name="hdPartyID" value="<?php echo  $iPartyID ?>">
		<input type="hidden" name="hdBillingFirm" id="hdBillingFirm" value="<?=(empty(trim($updatedData)))?$ItemData:$updatedData ?>">
		<div class="row">
		  <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Party Name</label>
				
					<input class="form-control" type="text" id="txtPartyName" name="txtPartyName" placeholder="Party name" onBlur="CheckName(this.id)" value="<?=$PartyName ?>">
				
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtContactPerson">Contact Person</label>
				
					<input class="form-control" type="text"  id="txtContactPerson" name="txtContactPerson" placeholder="Contact Person" value="<?=$ContactPerson?>" maxlength="200">
				
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtContactPerson">Invoice Code</label>
				
					<input class="form-control" type="text"  name="txtInvoiceCode" id="txtInvoiceCode" value="<?=$InvoiceCode;?>" placeholder="Invoice Code">
				
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtInvoiceSerial">Invoice Serial</label>
				
					<input type="text" class="form-control" name="txtInvoiceSerial" id="txtInvoiceSerial" value="<?=$InvoiceSerial;?>"  onkeydown="OnlyInt1(this.id)">                                   
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtGSTCode">Gst In</label>
				<input type="text" class="form-control" name="txtGSTCode" id="txtGSTCode" value="<?=$gstCode;?>" >      
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtHSNCode">HSN Code</label>
				<input type="text" class="form-control" name="txtHSNCode" id="txtHSNCode" value="<?=$hsnCode;?>"  onkeydown="OnlyInt1(this.id)">      
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtTINNo">TIN No</label>
				<input type="text" class="form-control"  name="txtTINNo" id="txtTINNo" maxlength="50" size="27"  value="<?=$TINNo ?>">      
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtMobileNo">Mobile No</label>
				<input type="text" class="form-control"  name="txtMobileNo" id="txtMobileNo" maxlength="50"   value="<?=$MobileNo ?>">    
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtFaxNo">Fax No</label>
				<input type="text" class="form-control" name="txtFaxNo" id="txtFaxNo" maxlength="50"   value="<?=$FaxNo ?>">    
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtPhoneNo">Phone No</label>
				<input type="text" class="form-control" name="txtPhoneNo" id="txtPhoneNo" maxlength="50"  value="<?=$PhoneNo ?>">    
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtSTNo">STNo</label>
				<input type="text" class="form-control"  name="txtSTNo" id="txtSTNo"  value="<?=$STNo ?>">    
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtSTNo">CSTNo</label>
				<input type="text" class="form-control"  name="txtCSTNo" id="txtCSTNo" maxlength="50"  value="<?=$CSTNo ?>">    
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtAddress">Address</label>
				<textarea name="txtAddress" id="txtAddress" class="form-control"  onKeyDown="TextAreaNewLine(this.id)" onKeyUp="MaxCharCount(this.id,250)"><?=$Address ?></textarea>    
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtTransporter">Transporter</label>
				<textarea name="txtTransporter" id="txtTransporter" class="form-control" onKeyDown="TextAreaNewLine(this.id)" onKeyUp="MaxCharCount(this.id,200)"><?=$Transporter ?></textarea>    
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="txtSTNo">Remarks</label>
				<textarea name="txtRemarks" id="txtRemarks" class="form-control"  onKeyDown="TextAreaNewLine(this.id)" onKeyUp="MaxCharCount(this.id,200)"><?=$Remarks ?></textarea>    
			</div>
		   </div>
		  
		   </div>
		   
			 
			 <fieldset style="border:1px solid #A487A7;padding:20px;width:100%">
			   <legend>Billing Firm Details</legend>
			   <div class="row">
			   <div class="col-md-6">
				   <div class="form-group">
						<label for="txtSTNo">Firm Name</label>
						<input type="text"  name="txtFirmName" id="txtFirmName" class="form-control">    
					</div>
			   </div>
			   <div class="col-md-6">
				   <div class="form-group">
						<label for="txtFirmTINNO">Firm Tin No</label>
						<input type="text"  name="txtFirmTINNO" id="txtFirmTINNO" class="form-control">    
					</div>
			   </div>
			   <div class="col-xl-6">
				   <div class="form-group">
						<label for="txtFirmGSTCode">GST In</label>
						<input type="text" name="txtFirmGSTCode" id="txtFirmGSTCode" class="form-control">    
					</div>
			   </div>
			   <div class="col-xl-6">
				   <div class="form-group">
						<label for="txtFirmHSNCode">HSN Code</label>
						<input type="text" name="txtFirmHSNCode" id="txtFirmHSNCode" class="form-control">    
					</div>
			   </div>
			   <div class="col-xl-6">
				   <div class="form-group">
						<label for="txtFirmInvoiceCode">Invoice Code</label>
						<input type="text" name="txtFirmInvoiceCode" id="txtFirmInvoiceCode" class="form-control">    
					</div>
			   </div>
			   <div class="col-xl-6">
				   <div class="form-group">
						<label for="txtFirmInvoiceSerial">Invoice Serial</label>
						<input  type="text" class="form-control" name="txtFirmInvoiceSerial" id="txtFirmInvoiceSerial"  onkeydown="OnlyInt1(this.id)">    
					</div>
			   </div>
			   <div class="col-xl-6">
				   <div class="form-group">
						<label for="txtFirmAddress">Firm Address</label>
						<textarea name="txtFirmAddress" id="txtFirmAddress"  class="form-control" onKeyDown="TextAreaNewLine(this.id)" onKeyUp="MaxCharCount(this.id,200)"></textarea>    
					</div>
			   </div>
			   <div class="col-xl-6">
				   <div class="form-group">
						<label for="txtFirmInvoiceSerial">Firm Phone No</label>
						<input  type="text" class="form-control" name="txtFirmInvoiceSerial" id="txtFirmInvoiceSerial"  onkeydown="OnlyInt1(this.id)">    
					</div>
			   </div>
			   
			   <div class="col-xl-6">
				   <div class="form-group">
						<label for="txtFirmPhoneNo">Firm Phone No</label>
						<input type="text"  name="txtFirmPhoneNo" id="txtFirmPhoneNo" maxlength="100" class="form-control">    
					</div>
			   </div>
			   
			   <div class="col-xl-12">
				   <input type="button"  class="btn btn-primary waves-effect waves-light" name="btnAdd" id="btnAdd" value="Add"  onClick="AddFirm()">
			   </div>
			   <br>
			   </div>
			 </fieldset>
			 <br>
		   <div class="row">
		   <div id="dvBillingFirm" class="col-md-12">
		   </div>
		   <br>
		   <div class="col-md-12">
		   <button type="button"  class="btn btn-primary waves-effect waves-light" name="btnSave" id="btnSave"  onKeyDown="EnterKeyClick(this.id)">Save</button>
	
		   <input type="reset" value="Reset" class="btn btn-light"  name="btnReset" id="btnReset" onclick="resetPage()">
		   <input type="hidden" id="formfocuselement" value="<?php if($PageMode=='New')echo 'txtPartyName'; else echo 'txtContactPerson'; ?>"/>
		   </div>
		   </div>
		   
		</form>
			
		</div>
	</div>
</div>


</div>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script language="javascript" type="text/javascript" src="../js/company_master.js"></script>			
    <script>
  $(function () {
    $("#datatable").DataTable({
      "responsive": true,
      "autoWidth": false,
	  "paging": true,
	  "searching": true,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>