<?php
    error_reporting(1);
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');
    require_once("../../config.php");
    $CompanyID=$_GET['CompanyID'];
	$CompanySNO=$_GET['CompanyiSNo'];
	$fromdate=$_GET['FromDate'];
	$todate=$_GET['ToDate'];
	$PartyID=$_GET['PartyID'];
	
	$ToDate=date('d-m-Y',strtotime($todate));
	$FromDate=date('d-m-Y',strtotime($fromdate));
	$Clause="";
	$SNO=1;
	
	if ($CompanySNO>0)
	{
		$Coqry=mysqli_query($con2,"select cFirmName as cPartyName, cFirmAddress as cAddress  from company_master_detail where iPartyID='$CompanyID' and iSNo='$CompanySNO' ");
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
			$this->Cell(30, 10, "Party Collection Detail", 0, 0, 'C');
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
	
	$pdf->SetFont('helvetica', '', 13);
	//$pdf->SetHeaderData('', '', "Party Collection Detail","$CompanyName \n $CompanyAddress \n From Date : $FromDate - To Date : $ToDate");
	
	if ($PartyID!="")
	{
		$Clause=" and iPartyCode =$PartyID";
	}
	//echo "select distinct(iPartyCode), iFirmSNo  from party_payment where iCompanyCode ='$CompanyID' and iCompanySNo ='$CompanySNO'".$Clause;die();
	$pqry=mysqli_query ($con2,"select distinct(iPartyCode), iFirmSNo  from party_payment where iCompanyCode ='$CompanyID' and iCompanySNo ='$CompanySNO'".$Clause);
	if ($pqry && mysqli_num_rows($pqry)>0)
	{
		while ($prow=mysqli_fetch_array($pqry))
		{  
	        
			$qry=mysqli_query ($con2,"select iPaymentID ,dDate ,cPaymentType ,fTDSAmt , cMiscRemarks , fMiscAmt , cChequeNo ,dChequeDate ,fPaymentAmt , cBankDetails ,cRemarks, iCashMemoNo from party_payment where iCompanyCode ='$CompanyID' and iCompanySNo ='$CompanySNO' and iPartyCode=\"".$prow['iPartyCode']."\" and iFirmSNo=\"".$prow['iFirmSNo']."\" and dDate between '$fromdate' and '$todate'  and cPaymentType <>'Misc' order by dDate");		
			if ($qry && mysqli_num_rows($qry)>0)
			{
				if ($prow['iFirmSNo']>0)
					$partyqry=mysqli_query ($con2,"select cFirmName as cPartyName from party_master_detail where iPartyID =\"".$prow['iPartyCode']."\" and iSNo =\"".$prow['iFirmSNo']."\"");
				else
					$partyqry=mysqli_query ($con2,"select cPartyName from party_master where iPartyID =\"".$prow['iPartyCode']."\"");
				
				if ($partyqry && mysqli_num_rows($partyqry)>0)
				{
					$partyrow=mysqli_fetch_array($partyqry);
					$PartyName=$partyrow['cPartyName'];
				}
				$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" width=\"570\" border=\"1\">";
				$tbl.="<tr>";
				$tbl.="<td width=\"570\" align=\"center\" colspan=\"4\"><b>Party Name :".$PartyName ."       From :".$FromDate."  - To :".$ToDate."</b></td>";
				$tbl.="</tr>";
				$tbl.="<tr>";
				$tbl.="<td width=\"50\" align=\"center\"><b>SNO</b></td>";
				$tbl.="<td width=\"100\" align=\"center\"><b>Date</b></td>";
				$tbl.="<td width=\"320\" align=\"center\"><b>Particular</b></td>";
				$tbl.="<td width=\"100\" align=\"center\"><b>Balance</b></td>";
				$tbl.="</tr>";
				//$tbl.="</table>";
				
				$InvoiceCode="";
				while ($row=mysqli_fetch_array($qry))
				{
					$size=0;
					$Show=true;
					$InvoiceCode="";
					$PaymentAmt=$row['fPaymentAmt'];
					$TDSAmt=$row['fTDSAmt'];
					$DedAmt=$row['fMiscAmt'];
					$PaymentID=$row['iPaymentID'];
					$qry1=mysqli_query ($con2,"select cBillingCode from party_payment_detail join party_billing on party_payment_detail.iBillingID =party_billing.iBillingID  where party_payment_detail.iPaymentID='$PaymentID'");
					if ($qry1 && mysqli_num_rows($qry1)>0)
					{
						while ($row1=mysqli_fetch_array($qry1))
						{
							if ($InvoiceCode=="")
								$InvoiceCode="$row1[cBillingCode]";					
							else
								$InvoiceCode=$InvoiceCode.", $row1[cBillingCode]";					
						}
					}
					else
					{
						$InvoiceCode="";
					}
					//$PaymentAmt= $PaymentAmt - $TDSAmt - $DedAmt;
					$dArr=explode("-",$row['dDate']);
					$date="$dArr[2]/$dArr[1]/$dArr[0]";
					if ($row['cPaymentType']=="Cheque")
					{
						$chArr=explode("-",$row['dChequeDate']);
						$ChqDate="$chArr[2]/$chArr[1]/$chArr[0]";
						$Remarks="Bill No : $InvoiceCode , $row[cPaymentType] : ".number_format($PaymentAmt,2,'.','');
						$Remarks= $Remarks. " [Chq No : $row[cChequeNo] , Chq Date : $ChqDate , Bank Details : $row[cBankDetails]]";
						$tbl.="<tr>";
						$tbl.="<td width=\"50\" align=\"center\">$SNO</td>";
						$tbl.="<td width=\"100\" align=\"center\">$date</td>";
						$tbl.="<td width=\"320\" align=\"left\">$Remarks</td>";
					}
					else if ($row['cPaymentType']=="TDS")
					{
						$Remarks="Bill No : $InvoiceCode ";
						$tbl.="<tr>";
						$tbl.="<td width=\"50\" align=\"center\">$SNO</td>";
						$tbl.="<td width=\"100\" align=\"center\">$date</td>";
						$tbl.="<td width=\"320\" align=\"left\">$Remarks</td>";
					}
					else if ($row['cPaymentType']=="Cash")
					{
						$Remarks="Bill No : $InvoiceCode ,$row[cPaymentType] :".number_format($PaymentAmt,2,'.','');
						$Remarks=$Remarks.", Cash Memo No: ".$row['iCashMemoNo'];
						$tbl.="<tr>";
						$tbl.="<td width=\"50\" align=\"center\">$SNO</td>";
						$tbl.="<td width=\"100\" align=\"center\">$date</td>";
						$tbl.="<td width=\"320\" align=\"left\">$Remarks</td>";
					}
					if ($row['cPaymentType']=="Cheque")
					{
						$tbl.="<td width=\"100\" align=\"center\">$row[fPaymentAmt]</td>";
					}
					else if ($row['cPaymentType']=="Cash" || $row['cPaymentType']=="TDS")
					{
						$tbl.="<td width=\"100\" align=\"center\">$row[fPaymentAmt]</td>";
					}
					$tbl.="</tr>";
					if ($row['fTDSAmt']>0)
					{
						$Others="TDS : ".number_format($row['fTDSAmt'],2,'.','');
					}
					
					if ($Others!="")
					{
						$tbl.="<tr>";
						$tbl.="<td width=\"50\" align=\"center\"></td>";
						$tbl.="<td width=\"100\" align=\"center\"></td>";
						$tbl.="<td width=\"320\" align=\"left\">$Others</td>";
						$tbl.="<td width=\"100\" align=\"center\"></td>";
						$tbl.="</tr>";
					}			
					if ($row['cRemarks']!="")
					{
						$tbl.="<tr>";
						$tbl.="<td width=\"50\" align=\"center\"></td>";
						$tbl.="<td width=\"100\" align=\"center\"></td>";
						$tbl.="<td width=\"320\" align=\"left\">Remarks :$row[cRemarks]</td>";
						$tbl.="<td width=\"100\" align=\"center\"></td>";
						$tbl.="</tr>";
					}
					$Total=$Total + $row['fPaymentAmt'];							
					$SNO++;
					$Others="";
				}
				$tbl.="<tr>";
				$tbl.="<td width=\"470\" align=\"center\" colspan=\"3\">Total :</td>";
				$tbl.="<td width=\"100\" align=\"center\">".number_format($Total,'2','.','')."</td>";
				$tbl.="</tr>";
				$tbl.="</table>";
				
				$GrandTotal=$GrandTotal + $Total;
				$Total=0;
				$SNO=1;
			}
		}
	}
	$pdf->AddPage();
	$pdf->writeHTML($tbl, true, false, false, false, '');
		
		//Close and output PDF document
	$pdf->Output('party_collection_pdf.php', 'I');
?>