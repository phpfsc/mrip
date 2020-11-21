<?php
    require_once("../../config.php");
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');
	//require ('../general/dbconnect.php');
	
	 
   
	$CompanyID=$_GET['CompanyID'];
	$CompanySNO=$_GET['CompanyiSNo'];
	$FromDate=$_GET['FromDate'];
	$fArr=explode('/',$FromDate);
	$fromdate=$FromDate;
	$ToDate=$_GET['ToDate'];
	$tArr=explode('/',$ToDate);
	$todate=$ToDate;
	$PartyID=$_GET['PartyID'];
	$SubParty=$_GET['SubParty'];
	$Clause="";
	
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
			$this->Cell(30, 10, "TDS Report ", 0, 0, 'C');
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
	$pdf->SetFooterMargin(4);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	$pdf->SetHeaderData('', '', "$CompanyName", "$CompanyName");

	$pdf->SetFont('helvetica', '', 13);
	//$pdf->SetHeaderData('', '', "TDS Report","$CompanyName \n $CompanyAddress \n From Date : $FromDate - To Date : $ToDate");
	
	
	$Total=0;
	$GrandTotal=0;
	$SNO=1;
	$show=false;
	if ($PartyID!="")
	{
		$Clause =" and iPartyCode =$PartyID";
		if ($SubParty!="")
		{
			$Clause =$Clause." and iFirmSNo =$SubParty";
		}
	}
	$pqry=mysqli_query($con2,"select distinct(iPartyCode), iFirmSNo  from party_payment where iCompanyCode ='$CompanyID' and iCompanySNo ='$CompanySNO'".$Clause);
	if ($pqry && mysqli_num_rows($pqry)>0)	
	{	
		$tbl.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"570\">";
		$tbl.="<tr>";
		$tbl.="<td width=\"50\" align=\"center\"><b>SNO</b></td>";
		$tbl.="<td width=\"320\" align=\"center\"><b>Party Name</b></td>";
		$tbl.="<td width=\"100\" align=\"center\"><b>Date</b></td>";
		$tbl.="<td width=\"100\" align=\"center\"><b>TDS Amt</b></td>";
		$tbl.="</tr>";
		
		while ($prow=mysqli_fetch_array($pqry))
		{
			$PartyName=$prow['cPartyName'];
			$Tqry=mysqli_query($con2,"select * from party_payment where iCompanyCode ='$CompanyID' and iCompanySNo ='$CompanySNO' and iPartyCode=\"".$prow['iPartyCode']."\" and iFirmSNo=\"".$prow['iFirmSNo']."\" and cPaymentType ='TDS' and dDate between '$fromdate' and '$todate' order by dDate");
			
			if ($Tqry && mysqli_num_rows($Tqry)>0)
			{
				if ($prow['iFirmSNo'] >0)
					$partyqry=mysqli_query($con2,"select cFirmName as cPartyName from party_master_detail where iPartyID =\"".$prow['iPartyCode']."\" and iSNo =\"".$prow['iFirmSNo']."\" ");
				else
					$partyqry=mysqli_query($con2,"select cPartyName from party_master where iPartyID =\"".$prow['iPartyCode']."\"");
				if ($partyqry && mysqli_num_rows($partyqry)>0)
				{
					$partyrow=mysqli_fetch_array($partyqry);
					$PartyName=$partyrow['cPartyName'];
				}
				$tbl.="<tr>";
				$tbl.="<td width=\"50\" align=\"center\">$SNO</td>";
				$tbl.="<td width=\"520\" align=\"left\" colspan=\"3\"><b>$PartyName</b></td>";
				$tbl.="</tr>";
							
				while ($Trow=mysqli_fetch_array($Tqry))
				{					
					$dArr=explode("-",$Trow['dDate']);
					$date="$dArr[2]/$dArr[1]/$dArr[0]";
					$tbl.="<tr>";
					$tbl.="<td width=\"370\" align=\"center\" colspan=\"2\">$Trow[cRemarks]</td>";
					$tbl.="<td width=\"100\" align=\"center\">$date</td>";
					$tbl.="<td width=\"100\" align=\"center\">".number_format($Trow['fTDSAmt'],'2','.','')."</td>";
					$tbl.="</tr>";
					$Total=$Total + $Trow['fTDSAmt'];						
				}
				$tbl.="<tr>";
				$tbl.="<td width=\"370\" align=\"center\" colspan=\"2\"><b>Total :</b></td>";
				$tbl.="<td width=\"100\" align=\"center\"></td>";
				$tbl.="<td width=\"100\" align=\"center\"><b>".number_format($Total,'2','.','')."</b></td>";
				$tbl.="</tr>";
				$GrandTotal=$GrandTotal + $Total;
				$Total=0;
				$SNO++;
			}		
		}
		$tbl.="</table>";
	}

	$pdf->AddPage();
	$pdf->writeHTML($tbl, true, false, false, false, '');
		
		//Close and output PDF document
	$pdf->Output('party_ledger_pdf.php', 'I');
?>