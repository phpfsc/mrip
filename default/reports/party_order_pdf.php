<?php
    //error_reporting(1);
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');
	require_once("../../config.php");
	
	
	
	$PartyID=$_GET['PartyID'];
	$FromDate=$_GET['FromDate'];
	$fArr=explode('/',$FromDate);
	$fromdate=$_GET['FromDate'];
	$ToDate=$_GET['ToDate'];
	$tArr=explode('/',$ToDate);
	$todate=$_GET['ToDate'];
	$dateArr=array();
	
	$pqry=mysqli_query($con2,"select cPartyName, cAddress from party_master where iPartyID ='$PartyID'");
	if ($pqry && mysqli_num_rows($pqry)>0)
	{
		$prow=mysqli_fetch_array($pqry);
		$PartyName=$prow['cPartyName'];
		$PartyAddress=$prow['cAddress'];
	}
	
	// Extend the TCPDF class to create custom Header and Footer
	class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			
			// Set font
			$this->SetFont('helvetica', 'B', 15);
			// Move to the right
			$this->Cell(80);
			// Title
			$this->Cell(30, 10, "Party Order Report", 0, 0, 'C');
			$this->Ln(8);
			$this->Cell(50);
			
			$this->Cell(80, 10, $this->header_title, 0, 0, 'C');
			// Line break
			$this->Ln(2);
		}
	}


	// create new PDF document
	//$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false); 
	$pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
	
	
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
	
	$pdf->SetHeaderData('', '', "$PartyName", "$PartyName");
	
	$pdf->SetFont('helvetica', '', 13);
	
	
	//$pdf->SetHeaderData('', '', "Party Order Report","$PartyName \n From Date : $FromDate-To Date : $ToDate");
	
	$result=mysqli_query($con2,"select iOrderID,cOrderCode, dOrderDate, cItemType, iItemCode, fRate, iNoPcsRec, iNoPcsDisp, cItemRemarks, cPcs, cChallanNo, yearprefix from (select party_order.iOrderID,cOrderCode, dOrderDate, cItemType, iItemCode, fRate, iNoPcsRec, iNoPcsDisp, cItemRemarks, cPcs, cChallanNo , '' as yearprefix from party_order join party_order_detail on party_order.iOrderID=party_order_detail.iOrderID where iPartyCode ='$PartyID' and dOrderDate  between '$fromdate' and '$todate' and bDeleted <>'1') as tbl order by tbl.dOrderDate ")or die(mysqli_error($con2));
	//$result=mysqli_query($con2,"select * from party_order join party_order_detail on party_order.iOrderID=party_order_detail.iOrderID where iPartyCode ='$PartyID' and dOrderDate  between '$fromdate' and '$todate' and bDeleted <>'1' order by dOrderDate");	
	if ($result && mysqli_num_rows($result)>0)
	{
		
		$tbl.="<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
		$tbl.="<thead>";
		$tbl.="<tr>";
		$tbl.="<td width=\"70\" align=\"center\" height=\"20\"><b>Date</b></td>";
		$tbl.="<td width=\"230\" align=\"center\"><b>Item Name</b></td>";
		$tbl.="<td width=\"70\" align=\"center\"><b>Date</b></td>";
		$tbl.="<td width=\"55\" align=\"center\"><b>Disp</b></td>";
		$tbl.="<td width=\"140\" align=\"center\"> <b>Return</b></td>";
		$tbl.="<td width=\"45\" align=\"center\"> <b>Billed</b></td>";
		$tbl.="</tr>";
		$tbl.="</thead>";
		
		while ($row=mysqli_fetch_array($result))
		{
			$OrderID=$row['iOrderID'];
			$OrderCode=$row['cOrderCode'];
			$dArr=explode("-",$row['dOrderDate']);
			$date="$dArr[2]/$dArr[1]/$dArr[0]";
			$ItemType=$row['cItemType'];
			$ItemCode=$row['iItemCode'];
			$Rate=$row['fRate'];
			$PcsRec=$row['iNoPcsRec'];
			$PcsDisp=$row['iNoPcsDisp'];
			$ItemRemarks=$row['cItemRemarks'];
			$Pcs=$row['cPcs'];
			$PartyChallanNo=$row['cChallanNo'];
			//$yearprefix=$row['yearprefix'];
			if ($Pcs=="PCS")
			{
				$Pcs=str_replace('PCS','PC',$Pcs);
			}
			
			if ($ItemType=="Gear")
			{
				$qry=mysql_query("select concat('Gear ',iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ,' (',fDMValue ,' ', cType ,') @') as cName from gear_master where iId='$ItemCode'");
			}
			else if ($ItemType=="Pinion")
			{
				$qry=mysql_query("select concat('Pinion ', iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ,' (',fDMValue ,' ', cType ,') @') as cName from pinion_master where iId='$ItemCode'");
			}
			else if ($ItemType=="Shaft Pinion")
			{
				$qry=mysql_query("select concat('Shaft Pinion ',iTeeth, ' teeth ',cItemType ,' dia ',fDia ,' ',cDiaType , ' face ',fFace ,' ', cFaceType,' (',fDMValue ,' ', cType ,') @') as cName from shaft_pinion_master where iId='$ItemCode'");
			}
			else if ($ItemType=="Bevel Gear")
			{
				$qry=mysql_query("select concat('Bevel Gear',iTeeth, ' teeth dia ',fDia, ' ',cDiaType,' (',fDMValue ,' ', cType ,') @') as cName from bevel_gear_master where iId='$ItemCode'");			
			}
			else if ($ItemType=="Bevel Pinion")
			{
				$qry=mysql_query("select concat('Bevel Pinion',iTeeth, ' teeth dia ',fDia, ' ',cDiaType,' (',fDMValue ,' ', cType ,') @') as cName from bevel_pinion_master where iId='$ItemCode'");			
			}
			else if ($ItemType=="Chain Wheel")
			{
				$qry=mysql_query("select concat('Chain Wheel ',iTeeth, ' teeth (',cItemType,') dia ',fDia , ' ',cDiaType , ' pitch ', fPitch ,' ', cPitchType ,' @') as cName from chain_gear_master where iId='$ItemCode'");			
			}
			else if ($ItemType=="Worm Gear")
			{
				$qry=mysql_query("select concat('Worm Gear',iTeeth, ' teeth dia ',fDia, ' ',cDiaType,' (',fDMValue ,' ', cType ,') @') as cName from worm_gear_master where iId='$ItemCode'");			
			}
			if ($qry && mysqli_num_rows($qry)>0)
			{
				$row1=mysqli_fetch_array($qry);			
				$ItemName="$PcsRec $row1[cName] $Rate ".'  '.$Pcs;
			}
			
			// delivery Challan Item..........
			$DeliveryChallanArr=array();
			
			if ($yearprefix=="")
			{
				$Challanqry=mysqli_query($con2,"select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate ,party_challan.iChallanID , cItemRemarks  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where iPartyCode ='$PartyID'  and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID'");	
				//echo "select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate ,party_challan.iChallanID , cItemRemarks  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where iPartyCode ='$PartyID'  and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID'";
			}
			else
			{
				$Challanqry=mysqli_query($con2,"select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate ,party_challan.iChallanID , cItemRemarks  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where iPartyCode ='$PartyID'  and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID' and cYearPrefix='$yearprefix");
				//echo "select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate ,party_challan.iChallanID , cItemRemarks  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where iPartyCode ='$PartyID'  and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID' and cYearPrefix='$yearprefix' UNION ALL select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate ,old_party_challan.iChallanID , cItemRemarks  from old_party_challan join old_party_challan_detail on old_party_challan.iChallanID = old_party_challan_detail.iChallanID where iPartyCode ='$PartyID'  and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID' and cYearPrefix='$yearprefix'";
			}
			
		
			
			//$Challanqry=mysqli_query($con2,"select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate ,party_challan.iChallanID , cItemRemarks  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where iPartyCode ='$PartyID'  and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID'");	
			$PrevY1=$pdf->GetY();
			$pdf->SetY($PrevY);
			if ($Challanqry && mysqli_num_rows($Challanqry)>0)
			{
				$i=0;
				while ($Challanrow=mysqli_fetch_array($Challanqry))
				{
					$dcArr=explode("-",$Challanrow['dChallanDate']);
					$ChallanDate="$dcArr[2]/$dcArr[1]/$dcArr[0]";
					$DeliveryChallanArr[$i][0]=$ChallanDate;
					$DeliveryChallanArr[$i][1]=$Challanrow[disp];
					$DeliveryChallanArr[$i][2]=$Challanrow[PcsReturn]."  ".$Challanrow[cItemRemarks];
					$i++;
				}
			}
			
			// Billied Item..............
			$BillingArray=array();
			
			if ($yearprefix=="")
				$Billingqry=mysqli_query($con2,"select sum(temp.billed) as billed ,temp.dBillingDate  from (select sum(iNoPcsDisp) as billed , dBillingDate from k_party_billing join k_party_billing_detail on k_party_billing.iBillingID = k_party_billing_detail.iBillingID  where iOrderID = $OrderID and cItemType='$ItemType' and iItemCode ='$ItemCode' and iBillingType <>'2' group by k_party_billing.iBillingID 
				UNION select sum(iNoPcsDisp) as billed , dBillingDate from party_billing join party_billing_detail on party_billing.iBillingID = party_billing_detail.iBillingID  where iOrderID = $OrderID and cItemType='$ItemType' and iItemCode ='$ItemCode' and iBillingType <>'2' group by party_billing.iBillingID) as temp group by temp.dBillingDate");
			else
				$Billingqry=mysqli_query($con2,"select sum(temp.billed) as billed ,temp.dBillingDate  from (select sum(iNoPcsDisp) as billed , dBillingDate from k_party_billing join k_party_billing_detail on k_party_billing.iBillingID = k_party_billing_detail.iBillingID  where iOrderID = $OrderID and cItemType='$ItemType' and iItemCode ='$ItemCode' and iBillingType <>'2' and cYearPrefix='$yearprefix' group by k_party_billing.iBillingID 
			UNION select sum(iNoPcsDisp) as billed , dBillingDate from party_billing join party_billing_detail on party_billing.iBillingID = party_billing_detail.iBillingID  where iOrderID = $OrderID and cItemType='$ItemType' and iItemCode ='$ItemCode' and iBillingType <>'2' and cYearPrefix='$yearprefix' group by party_billing.iBillingID) as temp group by temp.dBillingDate) as temp group by temp.dBillingDate"); 
			
			
			//$Billingqry=mysqli_query($con2,"select sum(temp.billed) as billed ,temp.dBillingDate  from (select sum(iNoPcsDisp) as billed , dBillingDate from k_party_billing join k_party_billing_detail on k_party_billing.iBillingID = k_party_billing_detail.iBillingID  where iOrderID = $OrderID and cItemType='$ItemType' and iItemCode ='$ItemCode' and iBillingType <>'2' group by k_party_billing.iBillingID 
			//UNION select sum(iNoPcsDisp) as billed , dBillingDate from party_billing join party_billing_detail on party_billing.iBillingID = party_billing_detail.iBillingID  where iOrderID = $OrderID and cItemType='$ItemType' and iItemCode ='$ItemCode' and iBillingType <>'2' group by party_billing.iBillingID) as temp group by temp.dBillingDate");
			
			if ($Billingqry && mysqli_num_rows($Billingqry)>0)
			{
				$i=0;
				while ($Billingrow=mysqli_fetch_array($Billingqry))
				{
					$bArr=explode("-",$Billingrow['dBillingDate']);
					$BillDate="$bArr[2]/$bArr[1]/$bArr[0]";
					$BillingArray[$i][0]=$BillDate;
					$BillingArray[$i][1]=$Billingrow['billed'];
					$i++;			
				}
			}
			
			$tbl.="<tr nobr=\"true\">";
			$tbl.="<td width=\"70\" align=\"center\">$date";
			if ($PartyChallanNo!='')
				$tbl.="<br/>Ch No: $PartyChallanNo";
			$tbl.="</td>";
			$tbl.="<td width=\"230\" align=\"left\">$ItemName <br/> $ItemRemarks</td>";
			$tbl.="<td colspan=\"4\" width=\"310\">";
				$tbl.="<table cellspacing=\"0\" width=\"310\" border=\"1\">";	
					
				if (sizeof($DeliveryChallanArr)>0)
				{
					for ($i=0;$i<sizeof($DeliveryChallanArr);$i++)
					{
						$DcDate=$DeliveryChallanArr[$i][0];	
						$DcDispatch=$DeliveryChallanArr[$i][1];
						$DcReturn=$DeliveryChallanArr[$i][2];
						$tbl.="<tr>";
						$tbl.="<td width=\"70\" align=\"center\">$DcDate</td>";
						$tbl.="<td width=\"55\" align=\"center\" style=\"color:#FF0000\">$DcDispatch</td>";
						$tbl.="<td width=\"140\" align=\"center\" style=\"color:#0000FF\">$DcReturn</td>";
						$tbl.="<td width=\"45\" align=\"center\"></td>";
						$tbl.="</tr>";
					}	
				}
				
				if (sizeof($BillingArray)>0)
				{
					for ($i=0;$i<sizeof($BillingArray);$i++)
					{
						$BDate=$BillingArray[$i][0];
						$BBilled=$BillingArray[$i][1];
						$tbl.="<tr>";
						$tbl.="<td width=\"70\" align=\"center\">$BDate</td>";
						$tbl.="<td width=\"55\" align=\"center\"></td>";
						$tbl.="<td width=\"140\" align=\"center\"></td>";
						$tbl.="<td width=\"45\" align=\"center\" style=\"color:#218E21\">$BBilled</td>";
						$tbl.="</tr>";
					}
				}
				$tbl.="</table>";
			$tbl.="</td>";
			$tbl.="</tr>";	
		}
		$tbl.="</table>";
	}
	$pdf->AddPage();
	$pdf->writeHTML($tbl, true, false, false, false, '');
	$pdf->Output('example_048.pdf', 'I');
?>