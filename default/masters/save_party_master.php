<?php
//print_r($_POST);
require_once("../../config.php");
$Err="";
$ErrMsg="";
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
	 else
	 {
		    $auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
			$__view=$auth_info[0]; //view
			$__add=$auth_info[1]; //add 
			$__update=$auth_info[2]; //update
			$__delete=$auth_info[3]; //delete
			if($__add==true)
			{
				mysqli_query($con2,"begin");
				$iPartyID=base64_decode(base64_decode(trim($_POST['iPartyId'])));
			    $PageMode=trim($_POST['hfPageMode']);
				if (trim($_POST['txtPartyName'])=="")
				   {
						$Err=true;
						$ErrMsg="Please enter the Party Name...8";
				   }
				   else
				   {
						if($PageMode=='New')
						{
							$CheckName=mysqli_query($con2,"select * from party_master where cPartyName=\"".trim($_POST['txtPartyName'])."\"");
						}
						else
						   {
							  
								$CheckName=mysqli_query ($con2,"select * from party_master where cPartyName=\"".trim($_POST['txtPartyName'])."\" and iPartyID!='$iPartyID'");
						   }
						   if ($CheckName)
						   {
							   if (mysqli_num_rows($CheckName)>0)
							   {
									$Err=true;
									$ErrMsg="Party Name already exist..";
							   }
						   }
						   else
						   {
								$Err=true;
								$ErrMsg="Error in checking Duplicacy of Party Name...";
						   }
				   }
						   $PartyName=trim($_POST['txtPartyName']);
						   $ContactPerson=trim($_POST['txtContactPerson']);
						   $PhoneNo=trim($_POST['txtPhoneNo']);
						   $MobileNo=trim($_POST['txtMobileNo']);
						   $FaxNo=trim($_POST['txtFaxNo']);
						   $TINNo=trim($_POST['txtTINNo']);
						   $gstCode=trim($_POST['txtGSTCode']);
						   $stateCode=trim($_POST['txtStateCode']);
						   $STNo=trim($_POST['txtSTNo']);
						   $CSTNo =trim($_POST['txtCSTNo']);
						   $Address=trim($_POST['txtAddress']);
						   $Transporter=trim($_POST['txtTransporter']);
						   $Remarks=trim($_POST['txtRemarks']);
						   $PartType=trim($_POST['cmbPartType']);
						   if($_POST['chkActive']=='On' || $_POST['chkActive']=='on')
						   {
							   $Active=1;
						   }
						   else
						   {
							   $Active=0;
						   }		   
		
			               $ItemData=trim($_POST['hdBillingFirm']);

	   
       
				if($Err==false)
				{
					
					if ($iPartyID)
					{
						
						
						$result=mysqli_query ($con2,"update party_master set cContactPerson=\"".$ContactPerson."\" ,cAddress=\"".$Address."\", cPhone=\"".$PhoneNo."\",cMobile=\"".$MobileNo."\",cFax=\"".$FaxNo."\", cSTNo=\"".$STNo."\", cCSTNo=\"".$CSTNo."\", cTINNo=\"".$TINNo."\", cTransporter=\"".$Transporter."\", cRemarks=\"".$Remarks."\" , cGSTIn=\"".$gstCode."\" , cStateCode=\"".$stateCode."\" ,cPartType=\"".$PartType."\", bActive='$Active' where iPartyID ='$iPartyID'");
						if(!$result)
						{
							$Err=true;
							$ErrMsg="Error in updating firm data";
						}
						else
						{
							$ErrMsg="Party Details updated successfully";
						}
						$result=mysqli_query ($con2,"DELETE from party_master_detail where iPartyID='$iPartyID'");
						if(!$result)
						{
							$Err=true;
							$ErrMsg="Error please try again later";
						}
					}
					else
					{
						
						
						$result=mysqli_query($con2,"INSERT into party_master (cPartyName, cContactPerson,cAddress, cPhone, cMobile, cFax, cSTNo, cCSTNo, cTINNo, cTransporter, cRemarks,cPartType,cGSTIn,cStateCode, bActive) values (\"".$PartyName."\",\"".$ContactPerson."\",\"".$Address."\",\"".$PhoneNo."\",\"".$MobileNo."\",\"".$FaxNo."\",\"".$STNo."\",\"".$CSTNo."\",\"".$TINNo."\",\"".$Transporter."\",\"".$Remarks."\",\"".$PartType."\",\"".$gstCode."\",\"".$stateCode."\",'$Active')");
						if(!$result)
						{
							$Err=true;
							$ErrMsg="Error :Please try again later";
						}
						else
						{
							$ErrMsg="party details added successfully";
						}
					}
					if($Err==false)
					{
						$j=0;
						if($ItemData!="")
						{
							$FirmData=explode("~Array~",$ItemData);
							for ($i=0;$i<count($FirmData);$i++)
							{
								$j=$j+1;
								$Data=explode("~ArrayItem~",$FirmData[$i]);
								$result1=mysqli_query($con2,"INSERT into party_master_detail values ((select iPartyID from party_master where cPartyName=\"".$PartyName."\"),'$j',\"".$Data[0]."\",\"".$Data[3]."\",\"".$Data[4]."\",\"".$Data[5]."\", \"".$Data[1]."\",\"".$Data[2]."\")");
								if(!$result1)
								{
									
									$Err=true;
									$ErrMsg=$PartyName;
									break;
								}
								else
								{
									$ErrMsg="Records updated successfully";
								}
							}
						}
					}
					
					
					
				}
			}
			else
			{
				$Err=true;
				$ErrMsg="sorry Invalid request";
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