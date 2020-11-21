<?php
    //error_reporting(1);
	require_once("../../config.php");
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');

	
	 
   
	$CompanyID=$_GET['CompanyID'];
	$CompanySNo=$_GET['CompanyiSNo'];
	
	
	
	$fromdate=$_GET['FromDate'];
	
	$todate=$_GET['ToDate'];
	$ToDate=date('d-m-Y',strtotime($todate));
	$FromDate=date('d-m-Y',strtotime($fromdate));
	$PartyID=$_GET['PartyID'];
	$SubParty=$_GET['SubParty'];
	$Clause="";
	$SNO=1;
	
	if ($CompanySNo>0)
	{
		$Coqry=mysqli_query($con2,"select cFirmName as cPartyName, cFirmAddress as cAddress  from company_master_detail where iPartyID='$CompanyID' and iSNo='$CompanySNo' ");
	}
	else
	{
		$Coqry=mysqli_query($con2,"select cPartyName, cAddress  from company_master where iPartyID='$CompanyID'");
	}
	if ($Coqry && mysqli_num_rows($Coqry)>0)
	{	
		$Corow=mysqli_fetch_array($Coqry);
		$CompanyName=$Corow['cPartyName'];
		$CompanyAddress=$Corow['cAddress'];
	}
	// Extend the TCPDF class to create custom Header and Footer
	class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			
			// Set font
			$this->SetFont('helvetica', 'B', 13);
			// Move to the right
			$this->Cell(80);
			// Title
			$this->Cell(30, 10, "Party Ledger ", 0, 0, 'C');
			$this->Ln(8);
			$this->Cell(50);
			
			$this->Cell(80, 10, $this->header_title, 0, 0, 'C');
			// Line break
			$this->Ln(2);
		}
	}


	// create new PDF document
	//$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false); 
	$pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
	$pdf->setPrintFooter(true);
// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(5, 25, 5);
	$pdf->SetHeaderMargin(5);
	$pdf->SetFooterMargin(4);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	$pdf->SetHeaderData('', '', "$CompanyName", "$CompanyName");
	//$pdf->setPrintHeader("Party Ledger","<br/>Company Name", "<br/>Company Address");
	
	// ---------------------------------------------------------
	
    
	$pdf->SetFont('helvetica', '', 13);
//	$pdf->SetHeaderData('', '', "Party Ledger","$CompanyName ");
	
	if ($PartyID!="")
	{
		$Clause=" and iPartyCode =$PartyID ";
		if ($SubParty!="")
			$Clause=$Clause." and iFirmSNo=$SubParty";	
	}
	
	$qry1=mysqli_query($con2,"select distinct(iPartyCode), iFirmSNo  from party_account where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNo' ".$Clause);
	if ($qry1 && mysqli_num_rows($qry1)>0)
	{
		while ($row1=mysqli_fetch_array($qry1))
		{
			if ($row1['iFirmSNo']>0)
			{
				$pqry=mysqli_query ($con2,"select cFirmName as cPartyName from party_master_detail where iPartyID =\"".$row1['iPartyCode']."\" and iSNo =\"".$row1['iFirmSNo']."\"");
			}
			else
			{
				$pqry=mysqli_query ($con2,"select cPartyName from party_master where iPartyID =\"".$row1['iPartyCode']."\"");
			}
			if ($pqry && mysqli_num_rows($pqry)>0)
			{
				$prow=mysqli_fetch_array($pqry);
				$PartyName=$prow['cPartyName'];
			}
			
			$SNO=1;		
			$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"570\">";
			$tbl.="<tr>";
			$tbl.="<td width=\"570\" align=\"center\" colspan=\"7\"><b>Party Name :".$PartyName ."       From :".$FromDate."  - To :".$ToDate."</b></td>";
			$tbl.="</tr>";
			$tbl.="<tr>";
			$tbl.="<td width=\"40\" align=\"center\"><b>SNO</b></td>";
			$tbl.="<td width=\"80\" align=\"center\"><b>Date</b></td>";
			$tbl.="<td width=\"160\" align=\"center\"><b>Particular</b></td>";
			$tbl.="<td width=\"80\" align=\"center\"><b>Debit</b></td>";
			$tbl.="<td width=\"80\" align=\"center\"><b>Credit</b></td>";
			$tbl.="<td width=\"50\" align=\"center\"><b>Dr./Cr.</b></td>";
			$tbl.="<td width=\"80\" align=\"center\"><b>Balance</b></td>";
			$tbl.="</tr>";
			
			/*if (strtotime("2010-04-01")>strtotime($fromdate))
			{
				$fromdate="2010-04-01";
			}*/
			if (strtotime($fromdate)> strtotime("2010-04-01"))
			{
				$beforeqry= mysqli_query ($con2,"select (sum(ifnull(fDebit,0)) - sum(ifnull(fCredit,0))) as balance from party_account where party_account.iPartyCode =\"".$row1['iPartyCode']."\" and party_account.iFirmSNo=\"".$row1['iFirmSNo']."\" and iCompanyCode ='$CompanyID' and iCompanySNo ='$CompanySNo' and party_account.dDate <'$fromdate' order by dDate "); 					
				if ($beforeqry && mysqli_num_rows($beforeqry)>0)
				{
					$beforerow=mysqli_fetch_array($beforeqry);
					$balance=$beforerow['balance'];
				}
				else
				{
					$balance=0;
				}
			}
			else
			{
				$beforeqry= mysqli_query ($con2,"select (sum(ifnull(fDebit,0)) - sum(ifnull(fCredit,0))) as balance from party_account where party_account.iPartyCode =\"".$row1['iPartyCode']."\" and party_account.iFirmSNo=\"".$row1['iFirmSNo']."\" and iCompanyCode ='$CompanyID' and iCompanySNo ='$CompanySNo' and party_account.dDate <='$fromdate' and cCreditRef ='Carry Forward' order by dDate "); 					
				if ($beforeqry && mysqli_num_rows($beforeqry)>0)
				{
					$beforerow=mysqli_fetch_array($beforeqry);
					$balance=$beforerow['balance'];
				}
				else
				{
					$balance=0;
				}
			}
			
			$tbl.="<tr>";
			$tbl.="<td width=\"40\" align=\"center\"></td>";
			$tbl.="<td width=\"80\" align=\"center\"></td>";
			$tbl.="<td width=\"160\" align=\"center\">B/F</td>";
			$tbl.="<td width=\"80\" align=\"center\"></td>";
			$tbl.="<td width=\"80\" align=\"center\"></td>";
			if ($balance>0)
			{
				$tbl.="<td width=\"50\" align=\"center\">Dr</td>";
				$tbl.="<td width=\"80\" align=\"center\">".number_format($balance,2,'.','')."</td>";
			}
			else
			{
				$tbl.="<td width=\"50\" align=\"center\">Cr</td>";
				$tbl.="<td width=\"80\" align=\"center\">".number_format($balance,2,'.','')."</td>";			
			}
			$tbl.="</tr>";
			
		//	echo "SELECT party_account.dDate, party_account.fDebit ,party_account.fCredit , party_billing.cBillingCode,party_payment.cPaymentType, party_payment.iCashMemoNo , party_payment.cChequeNo, party_payment.dChequeDate, party_payment.cBankDetails, party_payment.cRemarks FROM party_account left join party_billing on party_account.iDebitRef = party_billing.iBillingID left join party_payment on party_account.cCreditRef=party_payment.cPaymentCode where party_account.`iCompanyCode`=$CompanyID and party_account.`iCompanySNo`=$CompanySNo and party_account.`iPartyCode`=\"".$row1['iPartyCode']."\" and party_account.`iFirmSNo`=\"".$row1['iFirmSNo']."\" and party_account.dDate between '$fromdate' and '$todate' order  by party_account.dDate ";
			
			$qry=mysqli_query($con2,"SELECT party_account.dDate, party_account.fDebit ,party_account.fCredit , party_billing.cBillingCode,party_payment.cPaymentType, party_payment.iCashMemoNo , party_payment.cChequeNo, party_payment.dChequeDate, party_payment.cBankDetails, party_payment.cRemarks FROM party_account left join party_billing on party_account.iDebitRef = party_billing.iBillingID left join party_payment on party_account.cCreditRef=party_payment.cPaymentCode where party_account.`iCompanyCode`=$CompanyID and party_account.`iCompanySNo`=$CompanySNo and party_account.`iPartyCode`=\"".$row1['iPartyCode']."\" and party_account.`iFirmSNo`=\"".$row1['iFirmSNo']."\" and (iDebitRef>0 || ISNULL(iDebitRef))  and party_account.cRemarks<>'Carry Forward' and party_account.dDate between '$fromdate' and '$todate' order  by party_account.dDate ");
			if ($qry)
			{
				if (mysqli_num_rows($qry)>0)
				{
					while ($row=mysqli_fetch_array($qry))
					{
						$rdate=explode("-",$row['dDate']);
						$date="$rdate[2]/$rdate[1]/$rdate[0]";
						$Credit=$row['fCredit'];
						if ($row['fDebit']>0 )
						{
							$tbl.="<tr>";
							$tbl.="<td width=\"40\" align=\"center\">$SNO</td>";
							$tbl.="<td width=\"80\" align=\"center\">$date</td>";
							
							$BillingCode=substr(strrchr($row['cBillingCode'],'/'),1);
							if ($BillingCode=="")
								$tbl.="<td width=\"160\" align=\"left\">To Carry Forward</td>";
							else
								$tbl.="<td width=\"160\" align=\"left\">To Bill No : $BillingCode</td>";
							$tbl.="<td width=\"80\" align=\"center\">".number_format($row['fDebit'],2,'.','')."</td>";
							$tbl.="<td width=\"80\" align=\"center\"></td>";
							$balance=$balance + $row['fDebit'];
							$TotalDebit=$TotalDebit + $row['fDebit'];
						}
						else
						{
							if ($row['cPaymentType']=="Cheque")
							{
								$chArr=explode("-",$row['dChequeDate']);
								$ChqDate="$chArr[2]/$chArr[1]/$chArr[0]";
								//$Remarks="By ".$row['cPaymentType']."[Chq No : $row[cChequeNo] , Dt:$ChqDate , $row[cBankDetails]]";
								$Remarks="By Chq No : $row[cChequeNo] , Dt:$ChqDate , $row[cBankDetails]";
								$tbl.="<tr>";
								$tbl.="<td width=\"40\" align=\"center\">$SNO</td>";
								$tbl.="<td width=\"80\" align=\"center\">$date</td>";
								$tbl.="<td width=\"160\" align=\"left\">$Remarks</td>";
							}
							else if($row['cPaymentType']=="Cash")
							{
								$Remarks="By ".$row['cPaymentType']." ,Cash Memo No: $row[iCashMemoNo]";
								$tbl.="<tr>";
								$tbl.="<td width=\"40\" align=\"center\">$SNO</td>";
								$tbl.="<td width=\"80\" align=\"center\">$date</td>";
								$tbl.="<td width=\"160\" align=\"left\">$Remarks</td>";
							}
							else if ($row['cPaymentType']=='TDS')
							{
								$Remarks=$row['cRemarks'];
								$tbl.="<tr>";
								$tbl.="<td width=\"40\" align=\"center\">$SNO</td>";
								$tbl.="<td width=\"80\" align=\"center\">$date</td>";
								$tbl.="<td width=\"160\" align=\"left\">$Remarks</td>";
							}
							else
							{
								$Remarks=$row['cRemarks'];
								$tbl.="<tr>";
								$tbl.="<td width=\"40\" align=\"center\">$SNO</td>";
								$tbl.="<td width=\"80\" align=\"center\">$date</td>";
								$tbl.="<td width=\"160\" align=\"left\">$Remarks</td>";
							}
							$TotalCredit=$TotalCredit + $Credit;
							$tbl.="<td width=\"80\" align=\"center\"></td>";
							$tbl.="<td width=\"80\" align=\"center\">".number_format($Credit,2,'.','')."</td>";
							$balance=$balance - $Credit;
						}
						if ($balance>0)
						{
							$tbl.="<td width=\"50\" align=\"center\">Dr</td>";
							$tbl.="<td width=\"80\" align=\"center\">".abs(number_format($balance,2,'.',''))."</td>";
						}
						else if($balance<0)
						{
							$tbl.="<td width=\"50\" align=\"center\">Cr</td>";
							$tbl.="<td width=\"80\" align=\"center\">".abs(number_format($balance,2,'.',''))."</td>";
						}
						else
						{
							$tbl.="<td width=\"50\" align=\"center\"></td>";
							$tbl.="<td width=\"80\" align=\"center\">".abs(number_format($balance,2,'.',''))."</td>";
						}
						$tbl.="</tr>";
						$SNO++;
					}
					$tbl.="<tr>";
					$tbl.="<td width=\"280\" align=\"center\" colspan=\"3\"><b>Total :</b></td>";
					$tbl.="<td width=\"80\" align=\"center\"><b>".number_format($TotalDebit,2,'.','')."</b></td>";
					$tbl.="<td width=\"80\" align=\"center\"><b>".number_format($TotalCredit,2,'.','')."</b></td>";
					if ($balance>0)
					{
						$tbl.="<td width=\"50\" align=\"center\"><b>Dr</b></td>";
						$tbl.="<td width=\"80\" align=\"center\"><b>".abs(number_format($balance,2,'.',''))."</b></td>";
					}
					else if ($balance<0)
					{
						$tbl.="<td width=\"50\" align=\"center\"><b>Cr</b></td>";
						$tbl.="<td width=\"80\" align=\"center\"><b>".abs(number_format($balance,2,'.',''))."</b></td>";
					}
					else
					{
						$tbl.="<td width=\"50\" align=\"center\"></td>";
						$tbl.="<td width=\"80\" align=\"center\"><b>".abs(number_format($balance,2,'.',''))."</b></td>";
					}
					$tbl.="</tr>";
				}
				else
				{
					$tbl.="<tr>";
					$tbl.="<td width=\"570\" align=\"center\" colspan=\"7\">No Record Found...</td>";
					$tbl.="</tr>";
				}
				$TotalDebit=0;
				$TotalCredit=0;
				$balance=0;
			}
			$tbl.="</table>";			
		}
	}
	else
	{
		$tbl.="<table>";
		$tbl.="<tr>";
		$tbl.="<td width=\"570\" align=\"center\" colspan=\"7\">No Record Found...</td>";
		$tbl.="</tr>";
		$tbl.="</table>";
	}
	$pdf->AddPage();
	$pdf->writeHTML($tbl, true, false, false, false, '');
		
		//Close and output PDF document
	$pdf->Output('party_ledger_pdf.php', 'I');

	
?>