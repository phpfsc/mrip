<?php
require_once("../../config.php");

$Err=false;
$ErrMsg="";
$focusid="txtPartyName";
if($_POST)
{
if(empty(trim($_POST['auth_info'])))
{
	$Err=true;
	$ErrMsg="Sorry Invalid Request";
}
 else if(empty(trim($_POST['hfPageMode'])))
 {
	$Err=true;
	$ErrMsg="Sorry Invalid Request";
 }
 else if(empty(trim($_POST['hdPartyID'])) && trim($_POST['hfPageMode'])=='Edit')
 {
	$Err=true;
	$ErrMsg="Sorry Invalid Request";
 }
 else if(empty(trim($_POST['txtContactPerson'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Contact Person";
 }
 else if(empty(trim($_POST['txtInvoiceCode'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Invoice Code";
	$focusid="txtInvoiceCode";
 }
 else if(empty(trim($_POST['txtInvoiceSerial'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Invoice Serial";
	$focusid="txtInvoiceSerial";
 }
 else if(!is_numeric(trim($_POST['txtInvoiceSerial'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Only Numeric value for serial";
	$focusid="txtInvoiceSerial";
 }
 else if(empty(trim($_POST['txtGSTCode'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Gst In";
	$focusid="txtGSTCode";
 }
 else if(empty(trim($_POST['txtHSNCode'])))
 {
	$Err=true;
	$ErrMsg="Please Enter HSN Code";
	$focusid="txtHSNCode";
 }
 else if(empty(trim($_POST['txtTINNo'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Tin No";
	$focusid="txtTINNo";
 }
 else if(empty(trim($_POST['txtMobileNo'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Mobile No";
	$focusid="txtMobileNo";
 }
 else if(!is_numeric(trim($_POST['txtMobileNo'])) || strlen(trim($_POST['txtMobileNo']))!='10')
 {
	 $Err=true;
	 $ErrMsg="Invalid Mobile no";
 }
 else if(empty(trim($_POST['txtFaxNo'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Fax No";
	$focusid="txtFaxNo";
 }
 else if(strlen(trim($_POST['txtFaxNo']))>'12' || strlen(trim($_POST['txtFaxNo']))<'7')
 {
	 $Err=true;
	 $ErrMsg="Invalid fax no";
 }
 else if(empty(trim($_POST['txtPhoneNo'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Phone  No";
	$focusid="txtPhoneNo";
 }
 else if(empty(trim($_POST['txtSTNo'])))
 {
	$Err=true;
	$ErrMsg="Please Enter StNo";
	$focusid="txtSTNo";
 }
 else if(empty(trim($_POST['txtCSTNo'])))
 {
	$Err=true;
	$ErrMsg="Please Enter CSTNo";
	$focusid="txtCSTNo";
 }
 else if(empty(trim($_POST['txtAddress'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Address";
	$focusid="txtAddress";
 }
 else if(empty(trim($_POST['txtTransporter'])))
 {
	$Err=true;
	$ErrMsg="Please Enter Transporter";
	$focusid="txtTransporter";
 }
 
 else
 {
	   $auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
		
	   $PageMode=trim($_POST['hfPageMode']);//New//Edit
	   $iPartyID=trim($_POST['hdPartyID']);
	   $PartyName=trim($_POST['txtPartyName']);
	   $ContactPerson=trim($_POST['txtContactPerson']);
	   $PhoneNo=trim($_POST['txtPhoneNo']);
	   $MobileNo=trim($_POST['txtMobileNo']);
	   $FaxNo=trim($_POST['txtFaxNo']);
	   $TINNo=trim($_POST['txtTINNo']);
	   $STNo=trim($_POST['txtSTNo']);
	   $CSTNo =trim($_POST['txtCSTNo']);
	   $gstCode =trim($_POST['txtGSTCode']);
	   $hsnCode =trim($_POST['txtHSNCode']);
	   $Address=trim($_POST['txtAddress']);
	   $InvoiceSerial=trim($_POST['txtInvoiceSerial']);
	   $InvoiceCode=trim($_POST['txtInvoiceCode']);
	   $Transporter=trim($_POST['txtTransporter']);
	   $Remarks=trim($_POST['txtRemarks']);
	   $ItemData=trim($_POST['hdBillingFirm']);
	   if($Err==false)
	   {
		   mysqli_query($con2,"begin");
		   if($PageMode=='New')
		   {
			   if($__add==true)
			   {
				    $result=mysqli_query($con2,"INSERT into company_master (cPartyName, cContactPerson,cAddress, cPhone, cMobile, cFax, cSTNo, cCSTNo, cTINNo, iInvoiceSerial,cInvoiceCode,cTransporter, cRemarks ,cGSTIn, cHSNCode) values (\"".$PartyName."\",\"".$ContactPerson."\",\"".$Address."\",\"".$PhoneNo."\",\"".$MobileNo."\",\"".$FaxNo."\",\"".$STNo."\",\"".$CSTNo."\",\"".$TINNo."\",\"$InvoiceSerial\",\"".$InvoiceCode."\",\"".$Transporter."\",\"".$Remarks."\",\"".$gstCode."\",\"".$hsnCode."\")");
					if(!$result)
					{
						$Err=true;
						$ErrMsg= mysqli_error($con2)." INSERT into company_master (cPartyName, cContactPerson,cAddress, cPhone, cMobile, cFax, cSTNo, cCSTNo, cTINNo, iInvoiceSerial,cInvoiceCode,cTransporter, cRemarks ,cGSTIn, cHSNCode) values (\"".$PartyName."\",\"".$ContactPerson."\",\"".$Address."\",\"".$PhoneNo."\",\"".$MobileNo."\",\"".$FaxNo."\",\"".$STNo."\",\"".$CSTNo."\",\"".$TINNo."\",$InvoiceSerial,\"".$InvoiceCode."\",\"".$Transporter."\",\"".$Remarks."\",\"".$gstCode."\",\"".$hsnCode."\")"; 
						
					}
					else
					{
						$j=0;
									if($ItemData!="")
									{
										$FirmData=explode("~Array~",$ItemData);
										for ($i=0;$i<sizeof($FirmData);$i++)
										{
											$j=$j+1;
											$Data=explode("~ArrayItem~",$FirmData[$i]);
											
											$result1=mysqli_query($con2,"INSERT into company_master_detail values((select iPartyID from company_master where cPartyName=\"".$PartyName."\"),'$j',\"".$Data[0]."\",\"".$Data[3]."\",\"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[4]."\" ,\"".$Data[5]."\",\"".$Data[1]."\",\"".$Data[2]."\")");
											if(!$result1)
											{
												$Err=true;
												$ErrMsg=mysqli_error($con2);
												break;
												
											}
											else
											{
												$ErrMsg="Company details added successfully";
											}
										}
									}
									else
									{
										$ErrMsg="Company details added successfully";
									}
					}
			   }
			   else
			   {
				   $Err=true;
				   $ErrMsg="sorry you dont have permission to add data";
			   }
		   }
		   else if($PageMode=='Edit')
		   {
			   if($__update==true)
			   {
				   $rec=mysqli_query ($con2,"select * from company_master where iPartyID=\"$iPartyID\"");
					if (mysqli_num_rows($rec)>0)
					{
						$result=mysqli_query ($con2,"update company_master set cContactPerson=\"".$ContactPerson."\" ,cAddress=\"".$Address."\", cPhone=\"".$PhoneNo."\",cMobile=\"".$MobileNo."\",cFax=\"".$FaxNo."\", cSTNo=\"".$STNo."\", cCSTNo=\"".$CSTNo."\", cTINNo=\"".$TINNo."\", iInvoiceSerial=$InvoiceSerial,cInvoiceCode=\"".$InvoiceCode."\" , cTransporter=\"".$Transporter."\", cRemarks=\"".$Remarks."\", cGSTIn=\"".$gstCode."\", cHSNCode=\"".$hsnCode."\"  where iPartyID =\"$iPartyID\"");
						if(!$result)
						{
							$Err=true;
							$ErrMsg="sorry something went wrong1";
							
						}
						else
						{
							$delete=mysqli_query ($con2,"DELETE from company_master_detail where iPartyID='$iPartyID'");
							if(!$delete)
							{
								$Err=true;
								$ErrMsg="sorry something went wrong";
							}
							else
							{
								$j=0;
									if($ItemData!="")
									{
										$FirmData=explode("~Array~",$ItemData);
										for ($i=0;$i<sizeof($FirmData);$i++)
										{
											$j=$j+1;
											$Data=explode("~ArrayItem~",$FirmData[$i]);
											
											$result1=mysqli_query($con2,"INSERT into company_master_detail values (\"$iPartyID\",'$j',\"".$Data[0]."\",\"".$Data[3]."\",\"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[4]."\" ,\"".$Data[5]."\",\"".$Data[1]."\",\"".$Data[2]."\")");
											if(!$result1)
											{
												$Err=true;
												$ErrMsg=mysqli_error($con2);
												break;
												
											}
											else
											{
												$ErrMsg="Data Updated successfully";
											}
										}
									}
									else
									{
										$ErrMsg="Data Updated successfully";
									}
							}
						}
						
					}
					else
					{
						$Err=true;
						$ErrMsg="Sorry something went wrong2";
					}
			   }
			   else
			   {
				   $Err=true;
				   $ErrMsg="sorry you dont have permission to update data";
			   }
		   }
		   else
		   {
			   $Err=true;
			   $ErrMsg="sorry Invalid Request";
		   }
	   }
 }
	
}
else
{
	$Err=true;
	$ErrMsg="Invalid request";
}
if($Err==true)
{
	mysqli_query($con2,"rollback");
}
else
{
	mysqli_query($con2,"commit");
}
$response_array['error']=$Err;
$response_array['error_msg']=$ErrMsg;
echo json_encode($response_array);
?>