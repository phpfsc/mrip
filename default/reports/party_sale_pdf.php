<?php
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');
    require_once("../../config.php");
	//print_r($_GET);
	
	$CompanyID=$_GET['CompanyID'];
	$CompanySNO=$_GET['CompanyiSNo'];
	$FromDate=$_GET['FromDate'];
	
	$fromdate=$_GET['FromDate'];
    $ToDate=$_GET['ToDate'];
	
    $todate=$_GET['ToDate'];
	$show=false;
	$show1=false;
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
			$this->Cell(30, 10, "Invoice Sale Report", 0, 0, 'C');
			$this->Ln(8);
			$this->Cell(50);
			
			$this->Cell(80, 10, $this->header_title, 0, 0, 'C');
			// Line break
			$this->Ln(2);
		}
	}
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
	//$pdf->SetHeaderData('', '', "Invoice Sale Report","$CompanyName \n $CompanyAddress \n From Date :$FromDate - To Date :$ToDate");
	$Total=0;
			// Invoice Sale
	$pdf->SetFont('helvetica', '', 12);
	//echo "select cBillingCode , dBillingDate , fBillTotal , if(party_billing.iFirmSNo >0 , party_master_detail.cFirmName, party_master.cPartyName ) as cPartyName from party_billing join party_master on party_billing.iPartyCode =party_master.iPartyID  left join  party_master_detail on party_billing.iPartyCode =party_master_detail.iPartyID  and party_billing.iFirmSNo = party_master_detail.iSNo  where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNO' and iBillingType <>2 and dBillingDate between '$fromdate' and '$todate' order by dBillingDate,cBillingCode ";
	$qry=mysqli_query($con2,"select cBillingCode , dBillingDate , fBillTotal , if(party_billing.iFirmSNo >0 , party_master_detail.cFirmName, party_master.cPartyName ) as cPartyName from party_billing join party_master on party_billing.iPartyCode =party_master.iPartyID  left join  party_master_detail on party_billing.iPartyCode =party_master_detail.iPartyID  and party_billing.iFirmSNo = party_master_detail.iSNo  where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNO' and iBillingType <>2 and dBillingDate between '$fromdate' and '$todate' order by dBillingDate,cBillingCode ");
	if ($qry && mysqli_num_rows($qry)>0)
	{
		$show=false;
		$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"570\">";
		$tbl.="<thead>";
		$tbl.="<tr>";
		$tbl.="<td width=\"30\" align=\"center\"><b>SNO</b></td>";
		$tbl.="<td width=\"80\" align=\"center\"><b>Date</b></td>";
		$tbl.="<td width=\"110\" align=\"center\"><b>Bill No</b></td>";
		$tbl.="<td width=\"200\" align=\"center\"><b>Party Name</b></td>";
		$tbl.="<td width=\"75\" align=\"center\"><b>Amt</b></td>";
		$tbl.="<td width=\"75\" align=\"center\"><b>Total Amt</b></td>";
		$tbl.="</tr>";
		$tbl.="</thead>";
		
		while ($row=mysqli_fetch_array($qry))
		{
			$dArr=explode("-",$row['dBillingDate']);
			$Date="$dArr[2]/$dArr[1]/$dArr[0]";
			$tbl.="<tr>";
			$tbl.="<td width=\"30\" align=\"center\">$SNO</td>";
			$tbl.="<td width=\"80\" align=\"center\">$Date</td>";
			$tbl.="<td width=\"110\" align=\"center\">$row[cBillingCode]</td>";
			$tbl.="<td width=\"200\" align=\"left\">$row[cPartyName]</td>";
			$BillTotal=round($row['fBillTotal']);
			$tbl.="<td width=\"75\" align=\"right\">".number_format($BillTotal,'2','.','')."</td>";
			$Total=$Total + $BillTotal;
			$tbl.="<td width=\"75\" align=\"right\">".number_format($Total,'2','.','')."</td>";
			$tbl.="</tr>";
			$SNO++;			
		}
		$tbl.="</table>";			
	}
	else
	{
		$show=true;
	}

	if ($show==true)
	{
		$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"570\">";
		$tbl.="<tr>";
		$tbl.="<td width=\"570\" align=\"center\">NO Record Found...</td>";
		$tbl.="</tr>";
		$tbl.="</table>";
	}

	$pdf->AddPage();
	$pdf->writeHTML($tbl, true, false, false, false, '');
		
		//Close and output PDF document
	$pdf->Output('party_sale_pdf.php', 'I');
?>