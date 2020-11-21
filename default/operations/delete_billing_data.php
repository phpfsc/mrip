<?php
require_once("../../config.php");
$Err=false;
$ErrMsg="";
if($_POST)
{
	$txtBillingUser=base64_decode(base64_decode(trim($_POST['txtBillingUser'])));
	$txtBillingDataArray=explode("~",base64_decode(base64_decode(trim($_POST['txtBillingData']))));
	$BillingId=$txtBillingDataArray[0];
	$tablePrefix=$txtBillingDataArray[1]; //old or new table variable
	
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="Invalid request";
	}
	else if(empty(trim($_POST['txtBillingData'])))
	{
		$Err=true;
		$ErrMsg="Invalid Request";
	}
	else if(empty(trim($_POST['txtBillingUser'])))
	{
		$Err=true;
		$ErrMsg="Invalid Request";
	}
	else
	{
		$txtBillingUser= base64_decode(base64_decode(trim($_POST['txtBillingUser'])));
		
		if($txtBillingUser=='2')
		{
			
			// For Kacha Billing 
			$dqry=mysqli_query($con2,"UPDATE {$tablePrefix}k_party_billing set bDeleted=1 where iBillingID =\"$BillingId\"");
			if (!$dqry)
			{
				$Err=true;
				$ErrMsg="error in deleting bill";
			}
			else
			{
				$Msg="Bill Deleted...";
			}	
		}		
		else if($txtBillingUser=='1')
		{
			
			// For Pakka Billing
			$dqry=mysqli_query($con2,"DELETE from {$tablePrefix}party_billing where iBillingID = \"$BillingId\"");
			if (!$dqry)
			{
				$Err=true;
				$ErrMsg="error in deleting bill";
			}
			else
			{
				$ErrMsg="Bill Deleted...";
			}
			$dqry=mysqli_query($con2,"DELETE from {$tablePrefix}party_billing_detail where iBillingID = \"$BillingId\"");
			if (!$dqry)
			{
				$Err=true;
				$ErrMsg="error in deleting bill";
			}
			else
			{
				$ErrMsg="Bill Deleted...";
			}
			$dqry=mysqli_query($con2,"DELETE from {$tablePrefix}party_account where iDebitRef =\"$BillingId\"");
			if (!$dqry)
			{
				$Err=true;
				$ErrMsg="error in deleting bill";
			}
			else
			{
				$ErrMsg="Bill Deleted...";
			}	
		}
		else
		{
			$Err=true;
			$ErrMsg="Invalid Request";
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