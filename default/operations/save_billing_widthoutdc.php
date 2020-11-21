<?php
require_once("../../config.php");
$Err=false;
$ErrMsg="";

if (isset($_POST))
	{
		
		if (empty(trim($_POST['cmbParty'])))
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
		else if (!(trim($_POST['txtItemName'])!='' && trim($_POST['txtItemQty'])>0 && trim($_POST['txtItemRate'])>0) && trim($_POST['hdBillingData'])=="")
		{
			$Err=true;
			$ErrMsg="Please Place a Bill...";

		}else if(trim($_POST['txtStateCode'])==3){
			if(trim($_POST['txtCGST'])=='' || trim($_POST['txtCGST'])==0){
			
				$Err=true;
				$ErrMsg="Please enter the CGST tax rate";
				
			}else if(trim($_POST['txtSGST'])=='' || trim($_POST['txtSGST'])==0){
				
				$Err=true;
				$ErrMsg="Please enter the SGST tax rate";
			}
		}
		else if (base64_decode(base64_decode(trim($_POST['txtBillingUser'])))!="2")
		{
			/* if ((trim($_POST['txtVat'])=="") || (trim($_POST['txtVat']=="0")))
			{
				$Err=true;
				$ErrMsg="Please enter VAT Tax %...";
			} */
		}
		
		$PartyName=explode("~ArrayItem~",$_POST['cmbParty']);
		$PartyCode=$PartyName[0];
		$FirmSNo=trim($_POST["cmbTo"]);
		// Selecting Company Name
		$Company=explode("~ArrayItem~",$_POST['cmbFrom']);
		$CompanyCode=$Company[0];
		$CompanySNo=$Company[1];
		
		$dArr=explode("-",$_POST['txtBillingDate']);
		
		$date=$_POST['txtBillingDate'];;
		$Month=$dArr[1];
		$OdDate=$dArr[2];

	    $ItemData=trim($_POST['hdBillingData']);
		
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
		$SurchargePer=trim($_POST['txtSurcharge']);
		$BillTotal=trim($_POST['hdGTWithoutTax']);
		$MinValue=trim($_POST['hdMinValue']);
		if ($MiscItemName!='' && $MiscItemQty!='' && $MiscItemRate!='')
		{
			$MiscAmt=$MiscItemQty * $MiscItemRate;
			$BillTotal=$BillTotal + $MiscAmt;
		}
		
		
		
		if ($Err==false)
		{
			
			$Err=false;
			$BillingNo="";
			$BillingCode="";
			mysqli_query($con2,"begin");
			
		    $txtBillingUser=base64_decode(base64_decode(trim($_POST['txtBillingUser'])));
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
			  
			if ($txtBillingUser=="2")
			{ 
		       
				// Kacha Billing Starts here................
				//2 is for kacha bill
				//1 for paka bill
					
				// Kacha Billing modification................
				if (trim($_POST['hdPageMode']=="Edit"))
				{
					$PendingBillingNoArray=explode("~",base64_decode(base64_decode($_POST['cmbBilling'])));
					$BillingCode=$PendingBillingNoArray[0];
					$PendingBillingNo=$PendingBillingNoArray[1];		
					$result =mysqli_query($con2,"UPDATE k_party_billing set dBillingDate='$date' ,dToDate='$date', iCompanyCode='$CompanyCode', iCompanySNo='$CompanySNo', iPartyCode='$PartyCode', iFirmSNo='$FirmSNo', fBillTotal ='$BillTotal', fBillAmt='$TotalAmount', cRemarks=\"".$Remarks."\" where iBillingID =\"$PendingBillingNo\"");				
					if (!$result)
					{
						$Err=true;
						$ErrMsg="Error in Updating Party billing";
						
					}
					$result =mysqli_query($con2,"DELETE from k_party_billing_detail where iBillingID=\"$PendingBillingNo\"");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error in Updating Party billing details";
						
					}
					else
					{
						$ErrMsg="Billing Data updated successfully";
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
						$billingcode=$row1['cBillingCode'];
						$billingcode= substr($billingcode,strlen("$billingPrefix/$OdDate/$Month/$YearString/")) +1;
					}
					else
					{
						$billingcode=$Firstbillingcode;	
					}
					
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
						
					$result=mysqli_query($con2,"INSERT into k_party_billing values ('','$BillingCode','$date','$date','$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$BillTotal','1','$TotalAmount',\"".$Remarks."\",'0');");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error : errror in saving billing data";
					} 	
				}
				
			}
			else
			{
				 
					// Pakka Billing done ....................
					
				if (trim($_POST['hdPageMode']=="Edit"))
				{
						// Editing Old Entries
					$PendingBillingNoArray=explode("~",base64_decode(base64_decode($_POST['cmbBilling'])));
					$BillingCode=$PendingBillingNoArray[0];
					$PendingBillingNo=$PendingBillingNoArray[1];
					
					$validDate='2017/10/30';
					if (strtotime($date) > $validDate) {
						$query="UPDATE party_billing set dBillingDate='$date' , dToDate='$date', iCompanyCode='$CompanyCode', iCompanySNo='$CompanySNo', iPartyCode='$PartyCode', iFirmSNo='$FirmSNo', fBillTotal ='$BillTotal',fVatFactor ='$VatFactor', fVatPer='$VatPer', fSurcharge ='$SurchargePer' ,iCGSTVal='$CGSTPer',iSGSTVal='$SGSTPer',iIGSTVal='$IGSTPer' ,cMiscTaxName='$MiscTaxName', fMiscTaxPer='$MicsTaxPer', fBillAmt='$TotalAmount', cRemarks=\"".$Remarks."\" where iBillingID =\"$PendingBillingNo\"";
					}else{
						$query="UPDATE party_billing set dBillingDate='$date' , dToDate='$date', iCompanyCode='$CompanyCode', iCompanySNo='$CompanySNo', iPartyCode='$PartyCode', iFirmSNo='$FirmSNo', fBillTotal ='$BillTotal',fVatFactor ='$VatFactor', fVatPer='$VatPer', fSurcharge ='$SurchargePer' ,cMiscTaxName='$MiscTaxName', fMiscTaxPer='$MicsTaxPer', fBillAmt='$TotalAmount', cRemarks=\"".$Remarks."\" where iBillingID =\"$PendingBillingNo\"";
					}
					/* $result =mysqli_query($con2,"UPDATE party_billing set dBillingDate='$date' , dToDate='$date', iCompanyCode='$CompanyCode', iCompanySNo='$CompanySNo', iPartyCode='$PartyCode', iFirmSNo='$FirmSNo', fBillTotal ='$BillTotal',fVatFactor ='$VatFactor', fVatPer='$VatPer', fSurcharge ='$SurchargePer' ,cMiscTaxName='$MiscTaxName', fMiscTaxPer='$MicsTaxPer', fBillAmt='$TotalAmount', cRemarks=\"".$Remarks."\" where iBillingID =\"".$PendingBillingNo[0]."\"");	 */
                    $result=mysqli_query($con2,$query);					
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error in updating party billing";
					}
					$result =mysqli_query($con2,"DELETE from party_billing_detail where iBillingID=\"$PendingBillingNo\"");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error in updating party billing details";
					}
					$result=mysqli_query($con2,"DELETE from party_account where iDebitRef =\"$PendingBillingNo\"");	
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error in updating party billing details";
					}
					
					$DebitRemarks="Debit Ref. of ".$TotalAmount." against Bill No :".$PendingBillingNo;
					$result=mysqli_query($con2,"INSERT into party_account values ('$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$date','$TotalAmount',NULL,\"$PendingBillingNo\",NULL,\"".$DebitRemarks."\")");	
					if (! $result)
					{
						$Err=true;
						$ErrMsg="error in saving party account details";
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
	                
					
					$result=mysqli_query($con2,"INSERT into party_billing values ('','$BillingCode','$date','$date','$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$BillTotal','$VatFactor','$VatPer','$CGSTPer','$SGSTPer','$IGSTPer','$SurchargePer',\"".$MiscTaxName."\",'$MicsTaxPer','1','$TotalAmount',\"".$Remarks."\")");
					
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error : ".mysqli_error($con2);
						
					} 
					$DebitRemarks="Debit Ref. of ".$TotalAmount." against Bill No :".$BillingCode ;
					$result=mysqli_query($con2,"INSERT into party_account values ('$CompanyCode','$CompanySNo','$PartyCode','$FirmSNo','$date','$TotalAmount','NULL',(select iBillingID from party_billing where cBillingCode='$BillingCode'),'NULL',\"".$DebitRemarks."\")");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error : ".mysqli_error($con2);
						
					}
					
				}			
			}
			 
				// Common Code for both Kacha and Pakka Billing (For detail table)............
			if(!empty($ItemData))
			{
				
				$OrderData=explode('~Array~',$ItemData);
				
				for ($x=0;$x<sizeof($OrderData);$x++)
				{
					$Data=explode('~ArrayItem~',$OrderData[$x]);
					if ($Data[11]=="Gear")
					{
						$result=mysqli_query($con2,"select iId from gear_master where iTeeth =$Data[2] and cItemType =\"".$Data[9]."\" and fDia =$Data[5] and cDiaType =\"".$Data[6]."\" and fFace =$Data[7] and cFaceType =\"".$Data[8]."\" and fDMValue =$Data[3] and cType =\"".$Data[4]."\"");
						if ($result)
						{
							if (mysqli_num_rows($result)>0)
							{	// Item already exist
								$row=mysqli_fetch_array($result);	
								$ItemCode=$row['iId'];
							}
							else
							{
								// New entry
								$Insertqry=mysqli_query($con2,"Insert into gear_master values ('',\"".$Data[2]."\", \"".$Data[9]."\", \"".$Data[5]."\", \"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\") ");
								if (!$Insertqry)
								{
									$Err=true;
									$ErrMsg="error in inserting gear master data";
								}
								// Find iId
								$Findqry=mysqli_query($con2,"select iId from gear_master where iTeeth =$Data[2] and cItemType =\"".$Data[9]."\" and fDia =$Data[5] and cDiaType =\"".$Data[6]."\" and fFace =$Data[7] and cFaceType =\"".$Data[8]."\" and fDMValue =$Data[3] and cType =\"".$Data[4]."\"");
								if ($Findqry && mysqli_num_rows($Findqry)>0)
								{	
									$row1=mysqli_fetch_array($Findqry);	
									$ItemCode=$row1['iId'];
								}
							}
						}
					}
					else if ($Data[11]=="Pinion")
					{
						$result=mysqli_query($con2,"select iId from pinion_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
						if ($result)
						{
							if (mysqli_num_rows($result)>0)
							{	// Item already exist
								$row=mysqli_fetch_array($result);
								$ItemCode=$row['iId'];
							}
							else
							{
								// New entry
								$Insertqry=mysqli_query($con2,"Insert into pinion_master values('',\"".$Data[2]."\", \"".$Data[9]."\", \"".$Data[5]."\", \"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\")");
								if (! $Insertqry)
								{
									$Err=true;
									$ErrMsg="error in inserting pinion master data";
								}
								// Find iId
								$Findqry=mysqli_query($con2,"select iId from pinion_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
								if ($Findqry && mysqli_num_rows($Findqry)>0)
								{	
									$row1=mysqli_fetch_array($Findqry);	
									$ItemCode=$row1['iId'];
								}
							}
						}
					}
					else if ($Data[11]=="Shaft Pinion")
					{
						$result=mysqli_query($con2,"select iId from shaft_pinion_master where iTeeth =$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
						if ($result)
						{
							if (mysqli_num_rows($result)>0)
							{
								// Item already exist
								$row=mysqli_fetch_array($result);
								$ItemCode=$row['iId'];
							}
							else
							{	 // New entry
								$Insertqry=mysqli_query($con2,"Insert into shaft_pinion_master values ('',\"".$Data[2]."\",\"".$Data[9]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\")");
								if (! $Insertqry)
								{
									$Err=true;
									$ErrMsg="error in inserting shaft pinion master data";
								}
								// Find iId
								$Findqry=mysqli_query($con2,"select iId from shaft_pinion_master where iTeeth =$Data[2] and  cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
								if ($Findqry && mysqli_num_rows($Findqry)>0)
								{	
									$row1=mysqli_fetch_array($Findqry);	
									$ItemCode=$row1['iId'];
								}
							}
						}
					}
					else if ($Data[11]=="Bevel Gear")
					{
						$result=mysqli_query($con2,"select iId from bevel_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
						if ($result)
						{
							if (mysqli_num_rows($result)>0)
							{
								// Item already exist
								$row=mysqli_fetch_array($result);
								$ItemCode=$row['iId'];
							}
							else
							{
								// New entry
								$Insertqry=mysqli_query($con2,"Insert into bevel_gear_master values ('',\"".$Data[2]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\",\"".$Data[4]."\")");
								if (! $Insertqry)
								{
									$Err=true;
									$ErrMsg="error in inserting bevel gear master data";
								}
								// Find iId
								$Findqry=mysqli_query($con2,"select iId from bevel_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
								if ($Findqry && mysqli_num_rows($Findqry)>0)
								{	
									$row1=mysqli_fetch_array($Findqry);	
									$ItemCode=$row1['iId'];
								}
							}
						}
					}
					else if ($Data[11]=="Bevel Pinion")
					{
						$result=mysqli_query($con2,"select iId from bevel_pinion_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
						if ($result)
						{
							if (mysqli_num_rows($result)>0)
							{
								// Item already exist
								$row=mysqli_fetch_array($result);
								$ItemCode=$row['iId'];
							}
							else
							{
								// New entry
								$Insertqry=mysqli_query($con2,"Insert into bevel_pinion_master values ('',\"".$Data[2]."\" ,\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\" ,\"".$Data[4]."\")");
								if (! $Insertqry)
								{
									$Err=true;
									$ErrMsg="error in inserting bevel pinion master data";
								}
								 // Find iId
								 $Findqry=mysqli_query($con2,"select iId from bevel_pinion_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
								if ($Findqry && mysqli_num_rows($Findqry)>0)
								{	
									$row1=mysqli_fetch_array($Findqry);	
									$ItemCode=$row1['iId'];
								}
							}
						}			
					}
					else if ($Data[11]=="Chain Wheel")
					{
						$result=mysqli_query($con2,"select iId from chain_gear_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fPitch=$Data[10] and  cPitchType=\"".$Data[12]."\"");
						if ($result)
						{
							if (mysqli_num_rows($result)>0)
							{
								// Item already exist
								$row=mysqli_fetch_array($result);
								$ItemCode=$row['iId'];
							}
							else
							{
								// New entry
								$Insertqry=mysqli_query($con2,"Insert into chain_gear_master values ('',\"".$Data[2]."\",\"".$Data[9]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[10]."\",\"".$Data[12]."\")");
								if (! $Insertqry)
								{
									$Err=true;
									$ErrMsg="error in inserting chain gear master data";
								}
									// Find Id
								$Findqry=mysqli_query($con2,"select iId from chain_gear_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fPitch=$Data[10] and  cPitchType=\"".$Data[12]."\"");
								if ($Findqry && mysqli_num_rows($Findqry)>0)
								{	
									$row1=mysqli_fetch_array($Findqry);	
									$ItemCode=$row1['iId'];
								}
							}
						}
					}
					else if ($Data[11]=="Worm Gear")
					{
						$result=mysqli_query($con2,"select iId from worm_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
						if ($result)
						{
							if (mysqli_num_rows($result)>0)
							{
								// Item already exist
								$row=mysqli_fetch_array($result);
								$ItemCode=$row['iId'];
							}
							else
							{
								// New entry
								$Insertqry=mysqli_query($con2,"Insert into worm_gear_master values ('',\"".$Data[2]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\",\"".$Data[4]."\")");
								if (! $Insertqry)
								{
									$Err=true;
									$ErrMsg="error in inserting worm gear master data";
								}
								// Find iId
								$Findqry=mysqli_query($con2,"select iId from worm_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");
								if ($Findqry && mysqli_num_rows($Findqry)>0)
								{	
									$row1=mysqli_fetch_array($Findqry);	
									$ItemCode=$row1['iId'];
								}
							}
						}
					}
					if ($ItemCode>0)
					{
						$result=mysqli_query($con2,"INSERT into $PartyBillingDetail values((select iBillingID from $PartyBilling where cBillingCode='$BillingCode'),'0',\"".$Data[11]."\",'$ItemCode','',\"".$Data[1]."\",\"".$Data[0]."\", \"".$Data[17]."\",\"".$Data[18]."\", \"".$Data[19]."\", \"".$Data[16]."\",\"".$MinValue."\",'')");
						if (! $result)
						{
							$Err=true;
							$ErrMsg="Error : ".mysqli_error($con2);
						}
					}
					else
					{
						$Err=true;
						$ErrMsg="Error : Please try again";
					}
				}
			}
			if ($MiscItemName!='' && $MiscItemQty!='' && $MiscItemRate!='')
			{
				$result =mysqli_query($con2,"INSERT into $PartyBillingDetail values ((select iBillingID from $PartyBilling where cBillingCode='$BillingCode'),'0', 'MISC',0,\"".$MiscItemName."\",$MiscItemRate,$MiscItemQty,'MISC','MISC','','','','') ");
				if (! $result)
				{
					$Err=true;
					$ErrMsg=mysqli_error($con2);
				}
			}
			
			
		}
	}
	       if ($Err==true)
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