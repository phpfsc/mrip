<?php 
    require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
	if($_POST)
	{
		
        if(empty($_POST['auth_info']))
		{
			$Err=true;
			$ErrMsg="Invalid Request";
		}
		else if (trim($_POST['cmbParty']==""))
		{
			$Err=true;
			$ErrMsg="Please select the Party Name...";
		}
		else if (trim($_POST['cmbFrom']==""))
		{
			$Err=true;
			$ErrMsg="Please Select Company Name...";
		}
		else if (trim($_POST['cmbTo']==""))
		{
			$Err=true;
			$ErrMsg="Please Select Party Name...";
		}
		else if (trim($_POST['txtBuradaRemarks'])=="")
		{
			$Err=true;
			$ErrMsg="Please enter Burada Remarks...";
		}
		else if (trim($_POST['txtBuradaQty']=="") || trim($_POST['txtBuradaQty'])==0)
		{
			$Err=true;
			$ErrMsg="Please enter Burada Qty...";
		}
		else if (trim($_POST['txtBuradaRate']=="") || trim($_POST['txtBuradaRate'])==0)
		{
			$Err=true;
			$ErrMsg="Please enter Burada Rate...";
		}
		
		/* else if (trim($_POST['txtVat']=="")|| trim($_POST['txtVat'])==0)
		{
			$Err=true;
			$ErrMsg="Please enter VAT %...";
		} */
		// Selecting Party List
		$PartyName=explode("~ArrayItem~",$_POST['cmbParty']);
		$PartyCode=$PartyName[0];
		$FirmSNo=trim($_POST["cmbTo"]);
		// Selecting Company Name
		$Company=explode("~ArrayItem~",$_POST['cmbFrom']);
		$CompanyCode=$Company[0];
		$CompanySNo=$Company[1];
		$BuradaRemarks=trim($_POST['txtBuradaRemarks']);
		$BuradaQty=trim($_POST['txtBuradaQty']);
		$BuradaRate=trim($_POST['txtBuradaRate']);
		$BuradaValue=trim($_POST['txtBuradaValue']);
		
		$CGSTPer=trim($_POST['txtCGST']);
		$SGSTPer=trim($_POST['txtSGST']);
		$IGSTPer=trim($_POST['txtIGST']);
		$VatPer=trim($_POST['txtVat']);
		$TotalAmt=trim($_POST['txtAmt']);
		$Remarks=trim($_POST['txtRemarks']);	 
		$CurrDate=trim($_POST['txtToDate']);
		$dArr=explode("-",$CurrDate);
		$date=trim($_POST['txtToDate']);
		$Month=$dArr[1];
		$OdDate=$dArr[2];
       
		
		if ($Err==false)
		{
			
			mysqli_query($con2,"begin");
            
			if (trim($_POST['hdPageMode']=="Edit"))
			{
					// Modifying old records
					// Getting Bill No...
			    $PendingBillingNo=explode("~ArrayItem~",$_POST['cmbBilling']);
			    $BillingCode=$PendingBillingNo[1];	
			    $rowBillngIdSerial=$PendingBillingNo[0];	
				
				$result=mysqli_query($con2,"update party_billing set iPartyCode='$PartyCode',dBillingDate ='$date',fBillTotal ='$BuradaValue' ,fVatFactor='$VatFactor',fVatPer ='$VatPer',iCGSTVal ='$CGSTPer',iSGSTVal ='$SGSTPer',iIGSTVal ='$IGSTPer', fBillAmt='$TotalAmt',cRemarks =\"".$Remarks."\" where iBillingID =\"".$PendingBillingNo[0]."\"");
				if (!$result)
				{
					//die("line no 90");
					$Err=true;
					$ErrMsg=mysqli_error($con2);
				}
				$result=mysqli_query($con2,"DELETE from party_billing_detail where iBillingID =\"".$PendingBillingNo[0]."\"");	
				if (!$result)
				{
					//die("line no 97");
					$Err=true;
					$ErrMsg=mysqli_error($con2);
				}
				
				$result=mysqli_query($con2,"DELETE from party_account where iDebitRef =\"".$PendingBillingNo[0]."\"");	
				if (! $result)
				{
					//die("line no 105");
					$Err=true;
					$ErrMsg=mysqli_error($con2);
				}
				$DebitRemarks="Debit Ref. of ".$TotalAmt." against Bill No :".$PendingBillingNo[1];
				$result=mysqli_query($con2,"INSERT into party_account values ('$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$date','$TotalAmt','NULL',\"".$PendingBillingNo[0]."\",'NULL',\"".$DebitRemarks."\")");
				
				if (! $result)
				{
					//die("line no 114");
					$Err=true;
					$ErrMsg=mysqli_error($con2);
				}
			}
			else
			{
				
				if ($CompanySNo>0)
				{
					$Invqry=mysqli_query($con2,"select cFirmInvoiceCode as cInvoiceCode, iFirmInvoiceSerial as iInvoiceSerial from company_master_detail where iSNo=$CompanySNo");
				}
				else
				{
					$Invqry=mysqli_query($con2,"select cInvoiceCode, iInvoiceSerial from company_master");
				}
				if ($Invqry && mysqli_num_rows($Invqry)>0)
				{
					$Invrow=mysqli_fetch_array($Invqry);
					$Firstbillingcode=$Invrow['iInvoiceSerial'];
					$billingPrefix=$Invrow['cInvoiceCode'];
				}
				
				// Inserting new records
				$result1=mysqli_query($con2,"select cBillingCode from party_billing where iBillingID=(Select Max(iBillingID) from party_billing where iCompanyCode=$CompanyCode and iCompanySNo=$CompanySNo)");
				if ($result1)
				{
					if (mysqli_num_rows($result1)>0)
					{
						$row1=mysqli_fetch_array($result1);
						$billingcode=$row1['cBillingCode'];
						$billingcode= substr($billingcode,strlen("$billingPrefix/$OdDate/$Month/$YearString/")) +1;
					}
					else
					{
						$billingcode=$Firstbillingcode;
					}
				}
				//$NewBillingCode="CB/$OdDate/$Month/$YearString/";
				if ($billingcode < 10)			 
					$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/00000".$billingcode;
				else if ($billingcode < 100)
					$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/0000".$billingcode;	
				else if ($billingcode < 1000)
					$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/000".$billingcode;
				else if ($billingcode < 10000)
					$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/00".$billingcode;
				else if ($billingcode < 100000)
					$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/0".$billingcode;
				else
					$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/".$billingcode;
				
				//$result=mysqli_query($con2,"INSERT into party_billing values ('','$BillingCode','$date','$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo',\"".$BuradaRemarks."\",'$BuradaQty','$BuradaRate','$VatPer','','','$TotalAmt','2',\"".$Remarks."\");");
				$result=mysqli_query($con2,"INSERT into party_billing values ('','$BillingCode','$date','$date','$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$BuradaValue','$VatFactor','$VatPer','$CGSTPer','$SGSTPer','$IGSTPer','0','','','2','$TotalAmt',\"".$Remarks."\");");
				if (! $result)
				{
					$Err=true;
					$ErrMsg=mysqli_error($con2);
				}
				$rowBillingId=mysqli_fetch_array(mysqli_query($con2,"select iBillingID from party_billing where cBillingCode='$BillingCode'"));
			    $rowBillngIdSerial=$rowBillingId['iBillingID'];
				$DebitRemarks="Debit Ref. of ".$TotalAmt." against Bill No :".$BillingCode ;
				$result=mysqli_query($con2,"INSERT into party_account values ('$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$date','$TotalAmt','NULL',\"$rowBillngIdSerial\",'NULL',\"".$DebitRemarks."\")");
				if (! $result)
				{
					$Err=true;
					$ErrMsg=mysqli_error($con2);
				}					
			}
			
			// Common Code
			$result=mysqli_query($con2,"INSERT into party_billing_detail values (\"$rowBillngIdSerial\", '0','BURADA',0,\"".$BuradaRemarks."\",'$BuradaRate','$BuradaQty', 'BURADA','','','','','')");
			if (! $result)
			{
				$Err=true;
				$ErrMsg=mysqli_error($con2);
				//die("line no 114");
			}
			else
			{
				$ErrMsg="Data updated successfully";
				//echo "INSERT into party_billing_detail values (\"$rowBillngIdSerial\", '0','BURADA',0,\"".$BuradaRemarks."\",'$BuradaRate','$BuradaQty', 'BURADA','','','','','')";
			}
			
		}
	}
	else
	{
		$Err=true;
		$ErrMsg="sorry Invalid Request";
	}
	if ($Err==true)
		{
			mysqli_query($con2,"rollback");
			//$Err=true;
			//$ErrMsg="Error in Saving Burada Invoice...";
		}
		else
		{
			mysqli_query($con2,"commit");
			
			
		}
		$response_array['error']=$Err;
		$response_array['error_msg']=$ErrMsg;
		echo json_encode($response_array);
?>