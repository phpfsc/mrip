<?php
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');
    require_once("../../config.php");
	
	$CompanyID=$_GET['CompanyID'];
	$CompanySNO=$_GET['CompanyiSNo'];
	$FromDate=$_GET['FromDate'];
	$fArr=explode('/',$FromDate);
	$fromdate=$FromDate;
	$ToDate=$_GET['ToDate'];
	$tArr=explode('/',$ToDate);
	$todate=$ToDate;
	$SNO=1;
	$CfDealerArr=array();
	
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
			$this->Cell(30, 10, "Party Outstanding Report ", 0, 0, 'C');
			$this->Ln(8);
			$this->Cell(50);
			
			$this->Cell(80, 10, $this->header_title, 0, 0, 'C');
			// Line break
			$this->Ln(2);
		}
	}
	$pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(5, 25, 5);
	$pdf->SetHeaderMargin(5);
	$pdf->SetFooterMargin(5);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	$pdf->SetFont('helvetica', '', 13);
	$pdf->SetHeaderData('', '', "$CompanyName", "$CompanyName");
	$TotalInvAmt=0;
	$TotalAmtRec=0;
	$TotalPendingAmt=0;
	
	// Carry Forward
	/*$cfqry=mysql_query ("select distinct(iPartyCode),iFirmSNo  from party_account where iCompanyCode='$CompanyID' and iCompanySNo ='$CompanySNO' and iDebitRef=0 and cRemarks='Carry Forward'");
	if ($cfqry)
	{
		if (mysqli_num_rows($cfqry)>0)
		{
			while ($cfrow=mysqli_fetch_array($cfqry))
			{
				$cfbalqry=mysql_query ("select * from party_account where iCompanyCode='$CompanyCode' and iCompanySNo ='$CompanySNo' and iPartyCode ='$cfrow[iPartyCode]' and iFirmSNo ='$cfrow[iFirmSNo]' and iDebitRef=0 and cRemarks='Carry Forward'");
				if ($cfbalqry )
				{
					if (mysqli_num_rows($cfbalqry)>0)
					{
						$cfbalrow=mysqli_fetch_array($cfbalqry);
						$carryForwardAmt=$cfbalrow['fDebit'];
						$reccfqry=mysqli_query($con2,"select sum(fAmount) as RecAmt from party_payment_detail where iPaymentID IN (select iPaymentID from party_payment where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNO' and iPartyCode='$cfrow[iPartyCode]' and iFirmSNo='$cfrow[iFirmSNo]' ) and iBillingID =0 ");
						if ($reccfqry)
						{
							if (mysqli_num_rows($reccfqry)>0)
							{
								$reccfrow=mysqli_fetch_array($reccfqry);
								$carryforwardpending=$reccfrow['RecAmt'];
							}
							else
							{
								$carryforwardpending=0;
							}
							$carryForwardAmt=$carryForwardAmt-$carryforwardpending;
						}
						
						if ($cfrow['iFirmSNo']>0)
						{
							$Pqry=mysql_query ("select cFirmName as cPartyName  from party_master_detail where iPartyID=\"".$cfrow['iPartyCode']."\"  and iSNo =\"".cfrow['iFirmSNo']."\"");
						}
						else
						{
							$Pqry=mysql_query ("select  cPartyName  from party_master where iPartyID=\"".$cfrow['iPartyCode']."\" ");
						}
						if ($Pqry && mysqli_num_rows($Pqry)>0)
						{
							$Prow=mysqli_fetch_array($Pqry);
						}
					}
				}
				
				$i=0;
				
				$CfDealerArr[$i][0]="$cfrow[iPartyCode]";
				$CfDealerArr[$i][1]="$cfrow[iFirmSNo]";
				$CfDealerArr[$i][2]="$Prow[cPartyName]";	
				$CfDealerArr[$i][3]=$carryForwardAmt;	
				$i++;
			}
		}
	}*/
	if (strtotime("2010-04-01")>$fromdate)
	{
		$fromdate="2010-04-01";
	}
//	print "select distinct(iPartyCode), iFirmSNo  from party_billing where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNO' and dBillingDate > '2010-03-31' UNION ALL select distinct(iPartyCode),iFirmSNo  from party_account where iCompanyCode='$CompanyID' and iCompanySNo ='$CompanySNO' and iDebitRef=0 and cRemarks='Carry Forward'";
	$qry=mysqli_query($con2,"select distinct(iPartyCode), iFirmSNo  from party_billing where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNO' and dBillingDate > '$fromdate' UNION  select distinct(iPartyCode),iFirmSNo  from party_account where iCompanyCode='$CompanyID' and iCompanySNo ='$CompanySNO' and iDebitRef=0 and cRemarks='Carry Forward' Order by iPartyCode") or die(mysqli_error());
//	print $qry;
//	exit;
	//$qry=mysqli_query($con2,"select distinct(iPartyCode), iFirmSNo from party_billing where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNO' and dBillingDate > '2010-03-31' UNION ALL select distinct(iPartyCode),iFirmSNo  from party_account where iCompanyCode='$CompanyID' and iCompanySNo ='$CompanySNO' and iDebitRef=0 and cRemarks='Carry Forward') as tbl order by tbl.iPartyCode, tbl.iFirmSNo ") ;
	if ($qry )
	{
		if (mysqli_num_rows($qry)>0)
		{
			while ($row=mysqli_fetch_array($qry))
			{
				$cfbalqry=mysql_query ("select * from party_account where iCompanyCode='$CompanyID' and iCompanySNo ='$CompanySNO' and iPartyCode =\"".$row['iPartyCode']."\" and iFirmSNo =\"".$row['iFirmSNo']."\" and iDebitRef=0 and cRemarks='Carry Forward'");
				$carryForwardAmt=0;
				if ($cfbalqry )
				{
					if (mysqli_num_rows($cfbalqry)>0)
					{
						$cfbalrow=mysqli_fetch_array($cfbalqry);
						$carryForwardAmt=$cfbalrow['fDebit'];
						
						$reccfqry=mysqli_query($con2,"select sum(fAmount) as RecAmt from party_payment_detail where iPaymentID IN (select iPaymentID from party_payment where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNO' and iPartyCode=\"".$row['iPartyCode']."\" and iFirmSNo=\"".$row['iFirmSNo']."\" ) and iBillingID =0 ");
						if ($reccfqry)
						{
							if (mysqli_num_rows($reccfqry)>0)
							{
								$reccfrow=mysqli_fetch_array($reccfqry);
								$carryforwardpending=$reccfrow['RecAmt'];
							}
							else
							{
								$carryforwardpending=0;
							}
							$carryForwardAmt=$carryForwardAmt-$carryforwardpending;
							$CFSshow=true;
							if ($carryForwardAmt==0)
							{
								$CFSshow=false;
							}
							
						}
					}
					else
					{
						$carryForwardAmt=0;
						$CFSshow=false;
					}
				}
				
				//print "select party_billing.*,ifnull(sum(fAmount),0) as RecAmt , fBillAmt from party_billing  left join party_payment_detail  on party_payment_detail.iBillingID=party_billing.iBillingID where party_billing.iPartyCode =\"".$row['iPartyCode']."\" and party_billing.iFirmSNo  =\"".$row['iFirmSNo']."\" and party_billing.iCompanyCode='$CompanyID' and party_billing.iCompanySNo='$CompanySNO' and party_billing.dBillingDate  between '$fromdate' and '$todate' group by party_billing.iBillingID having fBillAmt > RecAmt";				
				//print "<br>";
				$result=mysql_query ("select party_billing.*,ifnull(sum(fAmount),0) as RecAmt , fBillAmt from party_billing  left join party_payment_detail  on party_payment_detail.iBillingID=party_billing.iBillingID where party_billing.iPartyCode =\"".$row['iPartyCode']."\" and party_billing.iFirmSNo  =\"".$row['iFirmSNo']."\" and party_billing.iCompanyCode='$CompanyID' and party_billing.iCompanySNo='$CompanySNO' and party_billing.dBillingDate  between '$fromdate' and '$todate' group by party_billing.iBillingID having fBillAmt > RecAmt");
				if ($result)
				{
					//$show=false;
					if (mysqli_num_rows($result)>0 || $CFSshow)
					{
						if ($row['iFirmSNo']>0)
						{
							$Pqry=mysql_query ("select cFirmName as cPartyName  from party_master_detail where iPartyID=\"".$row['iPartyCode']."\"  and iSNo =\"".$row['iFirmSNo']."\"");
						}
						else
						{
							$Pqry=mysql_query ("select  cPartyName  from party_master where iPartyID=\"".$row['iPartyCode']."\" ");
						}
						if ($Pqry && mysqli_num_rows($Pqry)>0)
						{
							$Prow=mysqli_fetch_array($Pqry);
							$tbl.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"570\">";
							$tbl.="<tr>";
							$tbl.="<td width=\"570\" align=\"center\"><b>$Prow[cPartyName]</b></td>";
							$tbl.="</tr>";
							$tbl.="</table>";
						}
						$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"570\">";
						$tbl.="<tr>";
						$tbl.="<td width=\"100\" align=\"center\"><b>SNO</b></td>";
						$tbl.="<td width=\"100\" align=\"center\"><b>Date</b></td>";
						$tbl.="<td width=\"270\" align=\"center\"><b>Invoice Code</b></td>";
						$tbl.="<td width=\"100\" align=\"center\"><b>Pending Amt</b></td>";
						$tbl.="</tr>";
						
						if ($CFSshow)
						{
							$tbl.="<tr>";
							$tbl.="<td width=\"100\" align=\"center\">$SNO</td>";
							$tbl.="<td width=\"100\" align=\"center\">1/4/2010</td>";
							$tbl.="<td width=\"270\" align=\"center\">Balance B/F </td>";
							$tbl.="<td width=\"100\" align=\"center\">".number_format($carryForwardAmt,'2','.','')."</td>";
							$tbl.="</tr>";
							$SNO++;
							$TotalPendingAmt=$TotalPendingAmt + $carryForwardAmt;
							$show=true;
						}
						while ($row=mysqli_fetch_array($result))
						{
							$PendingAmt=$row['fBillAmt'] -$row['RecAmt'];
								$BillingCode=	substr(strrchr($row[cBillingCode],'/'),1);		
							$dArr=explode("-",$row['dBillingDate']);
							$date="$dArr[2]/$dArr[1]/$dArr[0]";
							$tbl.="<tr>";
							$tbl.="<td width=\"100\" align=\"center\">$SNO</td>";
							$tbl.="<td width=\"100\" align=\"center\">$date</td>";
							$tbl.="<td width=\"270\" align=\"center\">$BillingCode</td>";
							$tbl.="<td width=\"100\" align=\"center\">".number_format($PendingAmt,'2','.','')."</td>";
							$tbl.="</tr>";
							
							$TotalInvAmt=$TotalInvAmt + $row['fBillAmt'];
							$TotalAmtRec=$TotalAmtRec + $ReceivedAmt;
							$TotalPendingAmt=$TotalPendingAmt + $PendingAmt;				
							$SNO++;
							$show=true;
						}
						
						
						
						$tbl.="<tr>";
						$tbl.="<td width=\"470\" align=\"center\" colspan=\"3\"><b>Total :</b></td>";
						$tbl.="<td width=\"100\" align=\"center\"><b>".number_format($TotalPendingAmt,'2','.','')."</b></td>";
						$tbl.="</tr>";
						$tbl.="</table>";
						$SNO=1;
						$GTotalInvAmt=$GTotalInvAmt + $TotalInvAmt;
						$GTotalAmtRec= $GTotalAmtRec + $TotalAmtRec;
						$GTotalPendingAmt=$GTotalPendingAmt + $TotalPendingAmt;
						$TotalInvAmt=0;
						$TotalAmtRec=0;
						$TotalPendingAmt=0;
					}
				}
			}
			if ($show)
			{
				$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"570\">";
				$tbl.="<tr>";
				$tbl.="<td width=\"470\" align=\"center\"><b>Grand Total :</b></td>";
				$tbl.="<td width=\"100\" align=\"center\"><b>".number_format($GTotalPendingAmt,'2','.','')."</b></td>";
				$tbl.="</tr>";
				$tbl.="</table>";
			}
		}
		else
		{
				// If not a single billing has been done................
			
		/*	for ($i=0; $i<sizeof($CfDealerArr); $i++)
			{
				$SNO=1;
				$tbl.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"570\">";
				$tbl.="<tr>";
				$tbl.="<td width=\"570\" align=\"left\">$CfDealerArr[$i][2]</td>";
				$tbl.="</tr>";
				$tbl.="</table>";
				$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"570\">";
				$tbl.="<tr>";
				$tbl.="<td width=\"100\" align=\"center\"><b>SNO</b></td>";
				$tbl.="<td width=\"100\" align=\"center\"><b>Date</b></td>";
				$tbl.="<td width=\"270\" align=\"center\"><b>Invoice Code</b></td>";
				$tbl.="<td width=\"100\" align=\"center\"><b>Pending Amt</b></td>";
				$tbl.="</tr>";
				$tbl.="<tr>";
				$tbl.="<td width=\"100\" align=\"center\">$SNO</td>";
				$tbl.="<td width=\"100\" align=\"center\">01/04/2010</td>";
				$tbl.="<td width=\"270\" align=\"center\">Carry Forward</td>";
				$PendingAmt=$CfDealerArr[$i][3];
				$tbl.="<td width=\"100\" align=\"center\">".number_format($PendingAmt,'2','.','')."</td>";
				$tbl.="</tr>";
				$SNO++;
				$GTotalPendingAmt=$GTotalPendingAmt+ $PendingAmt;
			}
			$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"570\">";
			$tbl.="<tr>";
			$tbl.="<td width=\"470\" align=\"center\"><b>Grand Total :</b></td>";
			$tbl.="<td width=\"100\" align=\"center\"><b>".number_format($GTotalPendingAmt,'2','.','')."</b></td>";
			$tbl.="</tr>";
			$tbl.="</table>";*/
		}
	}
	
	$pdf->AddPage();
	$pdf->writeHTML($tbl, true, false, false, false, '');
		
		//Close and output PDF document
	$pdf->Output('party_ledger_pdf.php', 'I');
?>