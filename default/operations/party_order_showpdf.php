<?php
    error_reporting(1);
	require_once("../../config.php");
	error_reporting(0);
	if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($new_setting) {
        return true;
    }
}
	require('../fpdf/fpdf.php');
	if(empty(trim($_GET['Data'])))
	{
		die("Invalid Request");
	}
	else 
	{
		 $OrderID=base64_decode(base64_decode(trim($_GET['Data'])));
		 
		 
		 if(empty(trim($OrderID)) || empty(trim($OrderID)))
		 {
			 die("sorry something is wrong please try again later");
		 }
		
	}
    
	$pdf = new FPDF();
	$pdf->addpage();
	$pdf->ln(5);
	
	$SNO=1;
	$TotalPrice=0;
	$TotalItem=0;
	$CmpQry=mysqli_query ($con2,"select cPartyName , cAddress  from company_master");
	if ($CmpQry && mysqli_num_rows($CmpQry)>0)
	{
		$Cmprow=mysqli_fetch_array($CmpQry);
		$CompanyName=$Cmprow['cPartyName'];
		$CompanyAddress=$Cmprow['cAddress'];
	}
	$pdf = new FPDF();
	$pdf->addpage();
	$pdf->ln(5);
	$pdf->SetFont('Arial','B','15');
	$pdf->Cell(190,7,strtoupper($CompanyName),'',0,'C');
	$pdf->ln(7);
	$pdf->SetFont('Arial','','8');
	$pdf->Cell(190,7,$CompanyAddress,'',0,'C');
	$pdf->ln(10);
	
	/*$PPrateQry=mysqli_query ($con2,"select fMinValue  from party_master where fMinValue>0 and cPartType ='PowerPress'");	
	if ($PPrateQry && mysqli_num_rows($PPrateQry)>0)
	{
		$PPrateRow=mysqli_fetch_array($PPrateQry);
		$MinValue=$PPrateRow['fMinValue'];
	}
	else
	{
		$MinValue=0;
	}*/


	$oqry=mysqli_query ($con2,"select cOrderCode , dOrderDate ,cPartyName ,party_order.fMinValue, party_order.cRemarks from party_order join party_master on party_order.iPartyCode = party_master.iPartyID where iOrderID='$OrderID'");
	if ($oqry && mysqli_num_rows($oqry)>0)
	{
		$orow=mysqli_fetch_array($oqry);
		$OrderCode=$orow['cOrderCode'];
		$OrderDate=$orow['dOrderDate'];
		$PartyName=$orow['cPartyName'];
		$Remarks=$orow['cRemarks'];
		$dArr=explode("-",$OrderDate);
		$Date="$dArr[2]/$dArr[1]/$dArr[0]";
		$PartyName=$orow['cPartyName'];
		$MinValue=$orow['fMinValue'];
		$pdf->Cell(100,7,'Order No :'.$OrderCode,'',0,'');		
		$pdf->Cell(90,7,'Dated :'.$Date,'',0,'R');		
		$pdf->ln(7);
		$len=strlen($PartyName) + 40;
		$length=190- $len;
		$pdf->Cell($len,7,'To :'.strtoupper($PartyName),'B',0,'');	
		$pdf->Cell($length,7,'','','1','');
		if ($Remarks!="")
			$pdf->Cell(190,10,'Order Remarks :'.$Remarks,'','1','');
		else
			$pdf->ln(10);
		$ODetailQry=mysqli_query ($con2,"select * from party_order_detail where iOrderID='$OrderID' and bDeleted <>'1'");
		if ($ODetailQry && mysqli_num_rows($ODetailQry)>0)
		{
			$pdf->Cell(10,7,'SNO','RLBT','','C');
			$pdf->Cell(110,7,'Item Name','RLBT','','C');
			$pdf->Cell(20,7,'Qty','RLBT','','C');
			$pdf->Cell(25,7,'Rate','RLBT','','C');
			$pdf->Cell(25,7,'Price','RLBT','1','C');
			
			while ($ODetailRow=mysqli_fetch_array($ODetailQry))
			{
				$ItemType=$ODetailRow['cItemType'];	
				$ItemCode=$ODetailRow['iItemCode']; 
				$Rate=$ODetailRow['fRate']; 
				$Disp=$ODetailRow['iNoPcsRec'];
				$PartType=$ODetailRow['cPartType'];
				$Pcs=$ODetailRow['cPcs'];
				$Pcs=str_replace('PCS','PC',$Pcs);
				$Collar=$ODetailRow['cCollar'];		
				if ($ItemType=="Gear")
				{
					$Itemqry=mysqli_query ($con2,"select concat(iTeeth, ' teeth ',cItemType, ' dia ', fDia,' ', cDiaType,' face ', fFace,' ',cFaceType, ' ', '(',fDMValue ,cType ,')' ) as ItemName, iTeeth from gear_master where iId ='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['ItemName'];
					}
					if ($Pcs=="PT")
						$Price=$Disp*$Itemrow['iTeeth']* $Rate;
					else
						$Price=$Disp*$Rate;
				}
				else if ($ItemType=="Pinion")
				{
					//$Itemqry=mysqli_query ($con2,"select concat(iTeeth, ' teeth ',cItemType, ' dia ', fDia,' ', cDiaType,' face ', fFace,' ',cFaceType, ' ', '(',fDMValue ,cType ,')' ) as ItemName, iTeeth from pinion_master where iId='$ItemCode'");	
					$Itemqry=mysqli_query ($con2,"select iTeeth,cItemType, fDia, cDiaType, fFace,cFaceType,fDMValue ,cType from pinion_master where iId='$ItemCode'");	
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['iTeeth'].' teeth '.$Itemrow['cItemType'].' dia '.$Itemrow['fDia'].' face '.$Itemrow['fFace'];
						
						//$ItemName=$Itemrow['ItemName'];
						if ($Collar!="")
						{
							$ItemName=$ItemName." + ".$Collar;	
						}
						$ItemName=$ItemName.' '.$Itemrow['cFaceType'].' ('.$Itemrow['fDMValue']. $Itemrow['cType'].')';
					}
					if ($Pcs=="PT")
						$Price=$Disp*$Itemrow['iTeeth']* $Rate; 
					else
						$Price=$Disp*$Rate;
					if ($PartType=="PowerPress")
					{
						$IPrice=$Price/$Disp;
						if ($IPrice<$MinValue)
						{
							$Price=$MinValue*$Disp;
						}
					}
				}
				else if ($ItemType=="Bevel Pinion")
				{
					$Itemqry=mysqli_query ($con2,"select concat(iTeeth,' teeth dia ',fDia, ' ', cDiaType,'(',fDMValue ,cType ,')') as ItemName, iTeeth  from bevel_pinion_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['ItemName'];
					}
					if ($Pcs=="PT")
						$Price=$Disp * $Rate * $Itemrow['iTeeth'];
					else
						$Price=$Disp * $Rate;
				}
				else if ($ItemType=="Bevel Gear")
				{
					$Itemqry=mysqli_query ($con2,"select concat(iTeeth,' teeth dia ',fDia, ' ', cDiaType,'(',fDMValue ,cType ,')') as ItemName, iTeeth  from bevel_gear_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['ItemName'];
					}
					if ($Pcs=="PT")
						$Price=$Disp * $Rate * $Itemrow['iTeeth'];
					else
						$Price=$Disp * $Rate;
				}
				else if ($ItemType=="Shaft Pinion")
				{
					$Itemqry=mysqli_query ($con2,"select concat(iTeeth, ' teeth ', cItemType, ' dia ', fDia,' ', cDiaType, ' face ',fFace,' ', cFaceType) as ItemName, iTeeth from shaft_pinion_master where iId='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['ItemName'];
					}
					if ($Pcs=="PT")
						$Price=$Disp * $Rate * $Itemrow['iTeeth'];
					else
						$Price=$Disp * $Rate;
				}
				else if ($ItemType=="Chain Wheel")
				{
					$Itemqry=mysqli_query ($con2,"select concat(iTeeth, ' teeth ',cItemType,' dia ',fDia,' ',cDiaType, ' pitch ', fPitch,' ', cPitchType) as ItemName, cItemType , iTeeth from chain_gear_master where iId='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['ItemName'];
					}
					/*if ($Itemrow['cItemType']=="Single")
					{
						$fac=1;
					}
					else if ($Itemrow['cItemType']=="Duplex")
					{
						 $fac=2;
					}
					else if ($Itemrow['cItemType']=="Triplex")
					{
						 $fac=3;
					}
					else 
					{
						$fac=4;
					}*/
					if ($Pcs=="PT")
						$Price=$Disp* $Itemrow['iTeeth']*$Rate;
					else
						$Price=$Disp*$Rate;
				}
				else if($ItemType=="Worm Gear")
				{
					$Itemqry=mysqli_query ($con2,"select concat(iTeeth,' teeth dia ',fDia, ' ', cDiaType,'(',fDMValue ,cType ,')') as ItemName, iTeeth  from worm_gear_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['ItemName'];
					}
					if ($Pcs=="PT")
						$Price=$Disp * $Rate * $Itemrow['iTeeth'];
					else
						$Price=$Disp * $Rate ;
				}
				
				$pdf->Cell(10,7,$SNO,'LRB','','C');				
				$pdf->Cell(110,7,$ItemType." ".$ItemName,'LRT','','L');
				
				$pdf->Cell(20,7,$Disp,'LRB','','C');
				$pdf->Cell(25,7,$Rate.'('.$Pcs.')','LRB','','C');
				$pdf->Cell(25,7,number_format($Price,'2','.',''),'LRB','1','C');
				if ($ODetailRow['cItemRemarks']!="" )
				{
					$pdf->SetFont('Arial','B','8');
					$pdf->Cell(10,7,'','LRB','','C');	
					$pdf->Cell(110,7,$ODetailRow['cItemRemarks'],'LRB','','L');
					$pdf->Cell(20,7,'','LRB','','C');
					$pdf->Cell(25,7,'','LRB','','C');
					$pdf->Cell(25,7,'','LRB','1','C');
					$pdf->SetFont('Arial','','8');
				}
				$TotalItem= $TotalItem + $Disp;
				$TotalPrice=$TotalPrice + $Price;
				$SNO++;
			}
			//..............Total of Items and Price
			$pdf->Cell(120,7,'Total','LTRB','','C');
			$pdf->Cell(20,7,$TotalItem,'LRB','','C');
			$pdf->Cell(25,7,'','LRB','','C');
			$pdf->Cell(25,7,number_format($TotalPrice,'2','.',''),'LRB','','C');
		}
	}
	
	$pdf->Output();
	
?>