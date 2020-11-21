<?php
   //error_reporting(1);
    set_time_limit(300);
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');
	require_once("../../config.php");
	$PartyID=$_GET['PartyID'];
	// Date
	$FromDate=$_GET['FromDate'];
	$fArr=explode('/',$FromDate);
	$fromdate=$FromDate;
	$ToDate=$_GET['ToDate'];
	$tArr=explode('/',$ToDate);
	$todate=$ToDate;
	
	// Item Type
	$ItemType=$_GET['ItemType'];
	
	// Type
	if($ItemType=='Gear' || $ItemType=='Pinion' || $ItemType=='Shaft Pinion' || $ItemType=='Chain Wheel')
	{
		$Type=$_GET['Type'];
	}
	// Teeth
	$Teeth=$_GET['Teeth'];
	// Dia
	$Dia=$_GET['Dia'];
	$DiaType=$_GET['DiaType'];
	
	// Face
	if($ItemType=='Gear' || $ItemType=='Pinion' || $ItemType=='Shaft Pinion')
	{
		$Face=$_GET['Face'];
		$FaceType=$_GET['FaceType'];
	}	
	// Pitch
	if($ItemType=='Chain Wheel')
	{
		$Pitch=$_GET['Pitch'];
		$PitchType=$_GET['PitchType'];
	}
	
	// DP/Module
	if($ItemType!='Chain Wheel')
	{
		$Processing=$_GET['Processing'];
		$ProcessingType=$_GET['ProcessingType'];
	}	
	
	
	$dateArr=array();
	$qry="";
	$TotalRec=0;
	$TotalDisp=0;
	$TotalReturn=0;
	$TotalBal=0;
	
	
	
	// Extend the TCPDF class to create custom Header and Footer
	class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			
			// Set font
			$this->SetFont('helvetica', 'B', 15);
			// Move to the right
			// Title
			$this->Cell(280, 10, "Search Party Items Report", 0, 0, 'C');
			$this->Ln(8);
			//$this->Cell(50);
			
			//$this->Cell(80, 10, $this->header_title, 0, 0, 'C');
			// Line break
			//$this->Ln(2);
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
	$pdf->SetMargins(5, 15,5);
	$pdf->SetHeaderMargin(5);
	$pdf->SetFooterMargin(4);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 1);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	$pdf->AddPage();
			
	if($PartyID!='')
	{
		$pqry=mysqli_query($con2,"select iPartyID, cPartyName, cAddress from party_master where iPartyID ='$PartyID'");
	}
	else
	{
		$pqry=mysqli_query($con2,"select iPartyID, cPartyName, cAddress from party_master order by cPartyName");
	}
	if ($pqry && mysqli_num_rows($pqry)>0)
	{
		while($prow=mysqli_fetch_array($pqry))
		{
			$PartyID=$prow['iPartyID'];
			$PartyName=$prow['cPartyName'];
			$PartyAddress=$prow['cAddress'];
			
			$pdf->SetFont('helvetica', '', 13);
			
		//	$pdf->SetHeaderData('', '', "Search Party Items Report","$PartyName \n From Date : $FromDate-To Date : $ToDate");
		
			
			$qry='';
			$qry1='';
			if ($Teeth!="" && $Teeth>0)
			{
				$qry="where iTeeth = $Teeth ";	
			}
			
			if ($Type!="")
			{
				if ($qry=="")
					$qry="where cItemType = '$Type' ";
				else
					$qry.=" and cItemType = '$Type' ";
			}
			
			if ($Dia!="" && $Dia>0)
			{
				if ($qry=="")
					$qry=" where fDia = $Dia and cDiaType='$DiaType' ";
				else
					$qry.=" and fDia = $Dia and cDiaType='$DiaType' ";
			}
			
			
			if ($Face!="" && $Face>0)
			{
				if ($qry=="")
					$qry="where fFace = $Face and cFaceType = '$FaceType'";
				else
					$qry.=" and fFace = $Face and cFaceType = '$FaceType'";
			}
			
			if ($Pitch!="" && $Pitch>0)
			{
				if ($qry=="")
					$qry="where fPitch = $Pitch and cPitchType = '$PitchType'";
				else
					$qry.=" and fPitch = $Pitch and cPitchType = '$PitchType'";
			}
			
			if ($Processing!="" && $Processing>0)
			{
				if ($qry=="")
					$qry=" where fDMValue = $Processing and cType= '$ProcessingType' ";
				else
					$qry.=" and fDMValue = $Processing and cType= '$ProcessingType' ";
			}
			
			
			if ($ItemType=="Gear")
			{
				$qry1="select iId from gear_master $qry";
			}
			else if ($ItemType=="Pinion")
			{
				$qry1="select iId from pinion_master  $qry";	
			}
			else if ($ItemType=="Shaft Pinion")
			{
				$qry1="select iId from shaft_pinion_master $qry";
			}
			else if ($ItemType=="Bevel Pinion")
			{
				$qry1="select iId from bevel_pinion_master $qry ";
			}
			else if ($ItemType=="Bevel Gear")
			{
				$qry1="select iId from bevel_gear_master $qry ";
			}
			else if ($ItemType=="Chain Wheel")
			{
				$qry1="select iId from chain_gear_master $qry";
			}
			else if ($ItemType=="Worm Gear")
			{
				$qry1="select iId from worm_gear_master $qry";
			}
			
			// Getting all items Id......
			$result=mysqli_query ($con2,$qry1) or die(mysql_error());
			if (mysqli_num_rows($result)>0)
			{
				while ($row=mysqli_fetch_array($result))
				{
					if ($ItemId=="")
						$ItemId="$row[iId]";
					else
						$ItemId=$ItemId.",$row[iId]";				
				}
			}
			$tbl="";
			// Getting Party Order of that Item
			$orderqry=mysqli_query ($con2,"select dOrderDate, iOrderID, fRate, iNoPcsRec, iNoPcsDisp, iItemCode, cPcs, yearprefix from (select dOrderDate, party_order.iOrderID, fRate, iNoPcsRec, iNoPcsDisp, iItemCode, cPcs , ''  as yearprefix from party_order join party_order_detail on party_order.iOrderID=party_order_detail.iOrderID where  iPartyCode ='$PartyID' and cItemType='$ItemType'  and iItemCode IN ($ItemId) and dOrderDate  between '$fromdate' and '$todate' and bDeleted <>'1'  UNION ALL select dOrderDate, old_party_order.iOrderID, fRate, iNoPcsRec, iNoPcsDisp, iItemCode, cPcs , 'old_'  as yearprefix from old_party_order join old_party_order_detail on old_party_order.iOrderID=old_party_order_detail.iOrderID where  iPartyCode ='$PartyID' and cItemType='$ItemType'  and iItemCode IN ($ItemId) and dOrderDate  between '$fromdate' and '$todate' and bDeleted <>'1' ) as tbl order by tbl.dOrderDate");
			//$orderqry=mysqli_query ($con2,"select * from party_order join party_order_detail on party_order.iOrderID=party_order_detail.iOrderID where iPartyCode ='$PartyID' and cItemType='$ItemType'  and iItemCode IN ($ItemId) and dOrderDate  between '$fromdate' and '$todate' and bDeleted <>'1' order by dOrderDate");	
			if ($orderqry && mysqli_num_rows($orderqry)>0)
			{
				$pdf->SetFont('helvetica', '', 15);
				$tbl1='';
				$tbl1.="<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				$tbl1.="<tr>";
				$tbl1.="<td  width=\"810\" align=\"center\"><b>Party Name :$PartyName &nbsp; &nbsp; &nbsp; &nbsp;From:$FromDate &nbsp; &nbsp;To: $ToDate</b></td>";
				$tbl1.="</tr>";
				$tbl1.="</table>";
				$pdf->writeHTML($tbl1, false, false, false, false, '');
				$pdf->SetFont('helvetica', '', 13);
				
				$tbl.="<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" >";
				$tbl.="<thead>";
				$tbl.="<tr nobr=\"true\">";
				$tbl.="<td width=\"80\" align=\"center\"><b>Date</b></td>";
				$tbl.="<td width=\"70\" align=\"center\"><b>Rec.</b></td>";
				$tbl.="<td width=\"370\" align=\"center\"><b>Particular</b></td>";
				$tbl.="<td width=\"80\" align=\"center\"><b>Date</b></td>";
				$tbl.="<td width=\"70\" align=\"center\"><b>Disp.</b></td>";
				$tbl.="<td width=\"70\" align=\"center\"><b>Return</b></td>";
				$tbl.="<td width=\"70\" align=\"center\"><b>Balance</b></td>";
				$tbl.="</tr>";
				$tbl.="</thead>";
				$TotalRec=0;
				$TotalBal=0;
				$TotalDisp=0;
				$TotalReturn=0;
				while ($orderrow=mysqli_fetch_array($orderqry))
				{
					$dArr=explode("-",$orderrow['dOrderDate']);
					$OrderID=$orderrow['iOrderID'];
					$date="$dArr[2]/$dArr[1]/$dArr[0]";
					$Rate=$orderrow['fRate'];
					$PcsRec=$orderrow['iNoPcsRec'];
					$PcsDisp=$orderrow['iNoPcsDisp'];
					$Balance=$PcsRec - $PcsDisp;
					$ItemCode=$orderrow['iItemCode'];
					$Pcs=$orderrow['cPcs'];	
					$TotalRec=$TotalRec + $orderrow['iNoPcsRec'];
					$TotalBal=$TotalBal + $Balance;
					$yearprefix=$orderrow['yearprefix'];
					
					if ($Pcs=="PCS")
					{
						$Pcs=str_replace('PCS','PC',$Pcs);
					}	
					if ($ItemType=="Gear")
					{
						$qry=mysql_query("select concat('Gear ',iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face <b>', fFace ,'</b> ',cFaceType ,' (',fDMValue ,' ', cType ,') @') as cName from gear_master where iId='$ItemCode'");
					}
					else if ($ItemType=="Pinion")
					{
						$qry=mysql_query("select concat('Pinion ', iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face <b>', fFace ,'</b> ',cFaceType ,' (',fDMValue ,' ', cType ,') @') as cName from pinion_master where iId='$ItemCode'");
					}
					else if ($ItemType=="Shaft Pinion")
					{
						$qry=mysql_query("select concat('Shaft Pinion ',iTeeth, ' teeth ',cItemType ,' dia ',fDia ,' ',cDiaType , ' face <b>', fFace ,'</b> ', cFaceType,' (',fDMValue ,' ', cType ,') @') as cName from shaft_pinion_master where iId='$ItemCode'");
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
						$ItemName="$row1[cName] $Rate ".'  '.$Pcs;
					}
					$DeliveryChallanArr=array();
					
					if ($yearprefix=="")
						$Challanqry=mysqli_query ($con2,"select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where iPartyCode ='$PartyID' and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID'");
					else
						$Challanqry=mysqli_query ($con2,"select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where iPartyCode ='$PartyID' and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID' and cYearPrefix='$yearprefix' UNION ALL select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate  from old_
						party_challan join old_party_challan_detail on old_party_challan.iChallanID = old_party_challan_detail.iChallanID where iPartyCode ='$PartyID' and cItemType='$ItemType' and iItemCode='$ItemCode' and cYearPrefix='$yearprefix' and iOrderID ='$OrderID' ");	
					
					
					//$Challanqry=mysqli_query ($con2,"select iNoPcsDisp as disp, iNoPcsReturn as PcsReturn, dChallanDate  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where iPartyCode ='$PartyID' and cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID'");	
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
					$tbl.="<tr nobr=\"true\">";
					$tbl.="<td width=\"80\" align=\"center\">$date</td>";
					$tbl.="<td width=\"70\" align=\"center\" style=\"color:#05A076\">$PcsRec</td>";
					$tbl.="<td width=\"370\" align=\"left\">$ItemName </td>";	
					$tbl.="<td colspan=\"3\" width=\"220\">";
					$tbl.="<table cellspacing=\"0\" width=\"220\" border=\"1\">";	
					if (sizeof($DeliveryChallanArr)>0)
					{
						for ($i=0;$i<sizeof($DeliveryChallanArr);$i++)
						{
							$DcDate=$DeliveryChallanArr[$i][0];	
							$DcDispatch=$DeliveryChallanArr[$i][1];
							$DcReturn=$DeliveryChallanArr[$i][2];
							$tbl.="<tr>";
							$tbl.="<td width=\"80\" align=\"center\">$DcDate</td>";
							$tbl.="<td width=\"70\" align=\"center\" style=\"color:#FF0000\">$DcDispatch</td>";
							$tbl.="<td width=\"70\" align=\"center\" style=\"color:#0000FF\">$DcReturn</td>";
							$tbl.="</tr>";
							$TotalDisp=$TotalDisp + $DcDispatch;
							$TotalReturn=$TotalReturn + $DcReturn;
						}	
					}
					$tbl.="</table>";	
					$tbl.="</td>";
					$tbl.="<td width=\"70\" align=\"center\">$Balance</td>";
					$tbl.="</tr>";			
				}
				$tbl.="<tr nobr=\"true\">";
				$tbl.="<td width=\"80\" align=\"center\"><b>Total Pcs Rec :</b></td>";
				$tbl.="<td width=\"70\" align=\"center\"><b>$TotalRec</b></td>";
				$tbl.="<td width=\"370\" align=\"center\"></td>";
				$tbl.="<td width=\"80\" align=\"center\"><b>Total Pcs Disp :</b></td>";
				$tbl.="<td width=\"70\" align=\"center\"><b>$TotalDisp</b></td>";
				$tbl.="<td width=\"70\" align=\"center\"><b>Ret : $TotalReturn</b></td>";
				$tbl.="<td width=\"70\" align=\"center\"><b>Bal : $TotalBal</b></td>";
				$tbl.="</tr>";
				if($_GET['PartyID']=='')
				{
					$GrandTotalRec+=$TotalRec;
					$GrandTotalBal+=$TotalBal;
					$GrandTotalDisp+=$TotalDisp;
					$GrandTotalReturn+=$TotalReturn;
					$tbl.="<tr nobr=\"true\">";
					$tbl.="<td width=\"80\" align=\"center\"><b>Grand Total Pcs Rec :</b></td>";
					$tbl.="<td width=\"70\" align=\"center\"><b>$GrandTotalRec</b></td>";
					$tbl.="<td width=\"370\" align=\"center\"></td>";
					$tbl.="<td width=\"80\" align=\"center\"><b>Grand Total Pcs Disp :</b></td>";
					$tbl.="<td width=\"70\" align=\"center\"><b>$GrandTotalDisp</b></td>";
					$tbl.="<td width=\"70\" align=\"center\"><b>Ret : $GrandTotalReturn</b></td>";
					$tbl.="<td width=\"70\" align=\"center\"><b>Bal : $GrandTotalBal</b></td>";
					$tbl.="</tr>";
				}
				$tbl.="</table>";
				
				$pdf->writeHTML($tbl, true, false, false, false, '');
			}
		}
	}

	
	$pdf->Output('example_048.pdf', 'I');
set_time_limit(30);
?>