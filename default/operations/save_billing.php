<?php
//print_r ($_POST);
require_once("../../config.php");
$Err=false;
$ErrMsg="";
if ($_POST)
	{
		if(empty(trim($_POST['auth_info'])))
		{
			$Err=true;
			$ErrMsg="Invalid Request";
		}
		else if (trim($_POST['cmbParty']==""))
		{
			$Err=true;
			$ErrMsg="Please select the Party Name...";
		}
		else if (trim($_POST['txtBillingDate']==""))
		{
			$Err=true;
			$ErrMsg="Please Select Date...";
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
		//1 for pakka bill 2 for kacha bill
		else if (base64_decode(base64_decode(trim($_POST['txtBillingUser'])))!='2')
		{
			/* if (trim($_POST['txtVat']=="") || (trim($_POST['txtVat']=="0")))
			{
				$Err=true;
				$ErrMsg[]="Please enter VAT Tax %...";
			} */
		}
		else if (base64_decode(base64_decode(trim($_POST['txtBillingUser'])))!='2')
		{
			if (trim($_POST['txtSurcharge']=="") || (trim($_POST['txtSurcharge']=="0")))
			{
				$Err=true;
				$ErrMsg="Please enter Surcharge Tax %...";
			}
			
		}
		else if (trim($_POST['txtItemName'])!="" &&( trim($_POST['txtItemQty'])=="" || trim($_POST['txtItemRate'])==""))
		{
			$Err=true;
			$ErrMsg="Please enter Mics Item detail...";
		}
		else if (trim($_POST['txtItemQty'])>0 &&( trim($_POST['txtItemName'])=="" || trim($_POST['txtItemRate'])==""))
		{
			$Err=true;
			$ErrMsg="Please enter Mics Item detail...";
		}
		else if (trim($_POST['txtItemRate'])>0 &&( trim($_POST['txtItemQty'])=="" || trim($_POST['txtItemName'])==""))
		{
			$Err=true;
			$ErrMsg="Please enter Mics Item detail...";
		}
		

		// Selecting Party List
		$PartyName=explode("~ArrayItem~",$_POST['cmbParty']);
		$PartyCode=$PartyName[0];
		$FirmSNo=trim($_POST["cmbTo"]);
		// Selecting Company Name
		$Company=explode("~ArrayItem~",$_POST['cmbFrom']);
		$CompanyCode=$Company[0];
		$CompanySNo=$Company[1];
		$ItemData1=trim($_POST['hdBillingData']);
		$dArr=explode("/",$_POST['txtBillingDate']);
		$date=date('Y-m-d');
		$Month=date('m');
		$OdDate=date('d');

		$tArr=explode("/",$_POST['txtToDate']);
		$ToDate="$tArr[2]-$tArr[1]-$tArr[0]";
				
		
		$Remarks=trim($_POST['txtRemarks']);	 
		$TotalAmount=trim($_POST['hdTotalWithTax']);
		$VatPer=trim($_POST['txtVat']);
		$CGSTPer=trim($_POST['txtCGST']);
		$SGSTPer=trim($_POST['txtSGST']);
		$IGSTPer=trim($_POST['txtIGST']);
		$MiscTaxName=trim($_POST['txtMiscName']);
		$MicsTaxPer=trim($_POST['txtMiscTax']);
		$MiscItemName=trim($_POST['txtItemName']);
		$MiscItemQty=trim($_POST['txtItemQty']);
		$MiscItemRate=trim($_POST['txtItemRate']);
		$BillTotal=trim($_POST['hdGTWithoutTax']);
		$SurchargePer=trim($_POST['txtSurcharge']);
		if ($MiscItemName!='' && $MiscItemQty!='' && $MiscItemRate!='')
		{
			$MiscAmt=$MiscItemQty * $MiscItemRate;
			$BillTotal=$BillTotal + $MiscAmt;
		}
												  
		

		

		if ($Err==false)
		{
			
			$BillingNo="";
			$BillingCode="";
			mysqli_query($con2,"begin");

			if (base64_decode(base64_decode(trim($_POST['txtBillingUser'])))=="2")
			{
				
				$PartyBilling="k_party_billing";
				$PartyBillingDetail="k_party_billing_detail";
					// Kachha Billing.................
				if (trim($_POST['hdPageMode']=="Edit"))
				{
						// Modifiying Kacha Bill....................
						// Modifying old records
	
						// modifying party_billing
					$PendingBillingNoArray=explode("~",base64_decode(base64_decode(trim($_POST['cmbBilling']))));
					$PendingBillingNo=$PendingBillingNoArray[0];
					$BillingCode=$PendingBillingNoArray[1];
					
					$result=mysqli_query($con2,"update k_party_billing set dBillingDate ='$date' ,dToDate='$ToDate' ,iCompanyCode ='$CompanyCode' , iCompanySNo ='$CompanySNo', iPartyCode ='$PartyCode', iFirmSNo='$FirmSNo',fBillTotal='$BillTotal', fBillAmt='$TotalAmount',iBillingType ='0',cRemarks =\"".$Remarks."\" where iBillingID =\"$PendingBillingNo\"");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error".mysqli_error($con2);
					}
						//  deleting all party_billing_details
					$result=mysqli_query($con2,"delete from k_party_billing_detail where iBillingID =\"$PendingBillingNo\"");
					
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error : ".mysqli_error($con2);
					}
				}
				else
				{
					
						// New Entry in kacha Bill.................
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
					$result1=mysqli_query($con2,"select cBillingCode from k_party_billing where iBillingID=(Select Max(iBillingID) from k_party_billing where iCompanyCode=$CompanyCode and iCompanySNo=$CompanySNo)");
					if (mysqli_num_rows($result1)>0)
					{
						$row1=mysqli_fetch_array($result1);
						$BillingCode=$row1['cBillingCode'];
						$BillingCode= substr($BillingCode,strlen("$billingPrefix/$OdDate/$Month/$YearString/")) +1;
					}
					else
					{
						$BillingCode=$Firstbillingcode;	
					}
					
					if ($BillingCode < 10)			 
						$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/00000".$BillingCode;
					else if ($BillingCode < 100)
						$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/0000".$BillingCode;	
					else if ($BillingCode < 1000)
						$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/000".$BillingCode;
					else if ($BillingCode < 10000)
						$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/00".$BillingCode;
					else if ($BillingCode < 100000)
						$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/0".$BillingCode;
					else
						$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/".$BillingCode;
					
					$result=mysqli_query($con2,"INSERT into k_party_billing values ('','$BillingCode','$date','$ToDate','$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$BillTotal','0','$TotalAmount',\"".$Remarks."\",'0')");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="error :".mysqli_error($con2);
					}
				
				}
			}
			else
			{
				
				$PartyBilling="party_billing";
				$PartyBillingDetail="party_billing_detail";
					// Pakka Billing.....................................
				if (trim($_POST['hdPageMode']=="Edit"))
				{
						// Modifying old records
	                 
						// modifying party_billing
				    $PendingBillingNoArray=explode("~",base64_decode(base64_decode(trim($_POST['cmbBilling']))));
					$PendingBillingNo=$PendingBillingNoArray[0];
					$BillingCode=$PendingBillingNoArray[1];
					
					
					$result=mysqli_query($con2,"update party_billing set dBillingDate ='$date' ,dToDate='$ToDate' ,iCompanyCode ='$CompanyCode' , iCompanySNo ='$CompanySNo', iPartyCode ='$PartyCode', iFirmSNo='$FirmSNo',fBillTotal='$BillTotal',fVatFactor ='$VatFactor',fVatPer ='$VatPer',fSurcharge ='$SurchargePer' ,cMiscTaxName =\"".$MiscTaxName."\", fMiscTaxPer ='$MicsTaxPer', fBillAmt='$TotalAmount',iBillingType ='0',cRemarks =\"".$Remarks."\" where iBillingID =\"$PendingBillingNo\"");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="error in updating party billing";
					}
						//  deleting all party_billing_details
					$result=mysqli_query($con2,"delete from party_billing_detail where iBillingID =\"$PendingBillingNo\"");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error in updating party billing details";
					}
						
					$result=mysqli_query($con2,"DELETE from party_account where iDebitRef =\"$PendingBillingNo\"");	
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error in updating party account details";
					}
					$DebitRemarks="Debit Ref. of ".$TotalAmount." against Bill No :".$PendingBillingNo;
					
					$result=mysqli_query($con2,"INSERT into party_account(iCompanyCode,iCompanySNo,iPartyCode,iFirmSNo,dDate,fDebit,fCredit,iDebitRef,cCreditRef,cRemarks) values('$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$date','$TotalAmount',NULL,\"$PendingBillingNo\",NULL,\"".$DebitRemarks."\")");	
					if (! $result)
					{
						$Err=true;
						$ErrMsg="error in updating party account";
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
					
					//creation of billing id
					
					
					
				   
						$result1=mysqli_query($con2,"select cBillingCode from party_billing where iBillingID=(Select Max(iBillingID) from party_billing where iCompanyCode=$CompanyCode and iCompanySNo=$CompanySNo)");
						if (mysqli_num_rows($result1)>0)
						{
							$row1=mysqli_fetch_array($result1);
							$BillingCode=$row1['cBillingCode'];
							$BillingCode= substr($BillingCode,strlen("$billingPrefix/$OdDate/$Month/$YearString/")) +1;
						}
						else
						{
							$BillingCode=$Firstbillingcode;	
						}
						
						if ($BillingCode < 10)			 
							$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/00000".$BillingCode;
						else if ($BillingCode < 100)
							$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/0000".$BillingCode;	
						else if ($BillingCode < 1000)
							$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/000".$BillingCode;
						else if ($BillingCode < 10000)
							$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/00".$BillingCode;
						else if ($BillingCode < 100000)
							$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/0".$BillingCode;
						else
							$BillingCode="$billingPrefix/$OdDate/$Month/$YearString/".$BillingCode;
					
					
					
	                
					
					
				}
			}
			//   Kacha and Pakka Billing main table entry ends here..............
			
			// common code  for both Kacha And Pakka Billing  (For Both New And Edit)
			
			                $result=mysqli_query($con2,"INSERT into party_billing values ('','$BillingCode','$date','$ToDate','$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$BillTotal','$VatFactor','$VatPer','$CGSTPer','$SGSTPer','$IGSTPer','$SurchargePer',\"".$MiscTaxName."\",'$MicsTaxPer','0','$TotalAmount',\"".$Remarks."\");");
							if (! $result)
							{
								$Err=true;
								$ErrMsg="Error : ".mysqli_error($con2);
								
							} 
							
						   $queryBilingCode=mysqli_fetch_array(mysqli_query($con2,"select iBillingID from $PartyBilling where cBillingCode=\"$BillingCode\""));
		                   $fetchBillingID=$queryBilingCode['iBillingID'];
						   
							$DebitRemarks="Debit Ref. of ".$TotalAmount." against Bill No :".$BillingCode ;
							
							$result=mysqli_query($con2,"INSERT into party_account (iCompanyCode,iCompanySNo,iPartyCode,iFirmSNo,dDate,fDebit,fCredit,iDebitRef,cCreditRef,cRemarks) values ('$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$date','$TotalAmount','NULL',\"$fetchBillingID\",'NULL',\"".$DebitRemarks."\")");
						
							if (! $result)
							{
								$Err=true;
								$ErrMsg="Error : ".mysqli_error($con2);
								
							}
							
			$ItemArray=explode("~ItemData~",$_POST['hdBillingData']);
			for ($x=0;$x<sizeof($ItemArray);$x++)
			{
				$Data=explode("~Heading~",$ItemArray[$x]);
				$Caption=explode("~Caption~",$Data[0]);
				$ordercode=$Caption[0]; // Order Code
				$orderid=$Caption[1]; // Order ID
				$orderdate=$Caption[2]; // Order Date
				$yearprefix=$Caption[4]; // Year Prefix
				
				if (! $result)
				{
					$Err=true;
					
				}
				$MinQry=mysqli_query($con2,"select fMinValue from party_order where iOrderID='$orderid'");
				
				if ($MinQry && mysqli_num_rows($MinQry)>0)
				{
					$MinRow=mysqli_fetch_array($MinQry);
					$OrderMinValue=$MinRow['fMinValue'];
				}
				
				
				$ItemPart=explode("~Array~",$Data[1]);
				for ($z=0;$z<sizeof($ItemPart); $z++)
				{
					$ArrayItem=explode("~ArrayItem~",$ItemPart[$z]);	
					$orderid1=$ArrayItem[0];  		 // order id;
					$categorytype1=$ArrayItem[1];	 // category type
					$itemcode1=$ArrayItem[2]; 		 // itemcode
					$PartType=$ArrayItem[7];         // Power Press or Expeller
					$dispqty1=$ArrayItem[9];  		 // Disp Qty
					$price1=$ArrayItem[10];	  		 // rate
					$Pcs=$ArrayItem[13];      		 // PT/PCS	
					$Collar=$ArrayItem[14];          // Collar
				    
					if($Err==false)
					{
					   if ($orderid==$orderid1)
						{
							if ($dispqty1>0)
							{
								
								// echo "INSERT into $PartyBillingDetail values ((select iBillingID from $PartyBilling where cBillingCode=\"$BillingCode\"),\"".$orderid1."\",\"".$categorytype1."\",\"".$itemcode1."\",'',\"".$price1."\",\"".$dispqty1."\",\"".$PartType."\",\"".$Pcs."\" ,\"".$Collar."\",'' ,\"".$OrderMinValue."\", \"".$yearprefix."\")";
								$result=mysqli_query($con2,"INSERT into $PartyBillingDetail values (\"$fetchBillingID\",\"".$orderid1."\",\"".$categorytype1."\",\"".$itemcode1."\",'',\"".$price1."\",\"".$dispqty1."\",\"".$PartType."\",\"".$Pcs."\" ,\"".$Collar."\",'' ,\"".$OrderMinValue."\", \"".$yearprefix."\")");
								if (!$result)
								{
									$Err=true;
									$ErrMsg="Error : ".mysqli_error($con2);
									
								}
								else
								{
									$ErrMsg="data updated successfully";
								}
							}
						}	
					}
					
				}
			}
			if ($MiscItemName!='' && $MiscItemQty!='' && $MiscItemRate!='')
			{
				$result=mysqli_query($con2,"INSERT into $PartyBillingDetail values(\"$fetchBillingID\",0,'MISC',0,\"".$MiscItemName."\",$MiscItemRate,$MiscItemQty,'MISC','MISC','','','','')");
				if (! $result)
				{
					$Err=true;
					$ErrMsg="error in saving mu misc. details";
				}
			}
			
			
		}
	}
	else
	{
		$Err=true;
		$ErrMsg="Invalid Request";
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