<?php
    
	require('../fpdf/fpdf.php');
	require_once("../../config.php");
	if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($new_setting) {
        return true;
    }
}
   	$Err=false;
	$ErrMsg="";
	 $hostname=$_hostname;
	 $User=$_username;
	
    $Pass=$_password;
	if(empty($_GET['Data']))
	{
		$Err=true;
		$ErrMsg="Invalid Request";
	}
	else
	{
		$challanData=explode("~",base64_decode(base64_decode(trim($_GET['Data']))));
		$fsYear=$challanData[0];
		$ChallanID=$challanData[1];
	    $YearPrefix=$challanData[2]; //yearprefix
	
	}
    if($Err==true)
	{
		die($ErrMsg);
	}
	$TotalDisp=0;
	$CmpQry=mysqli_query($con2,"select cPartyName , cAddress  from company_master");
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
	$pdf->SetFont('Arial','','10');
	$pdf->Cell(190,5,$CompanyAddress,'',0,'C');
	$pdf->ln(10);
	
	$Chqry=mysqli_query ($con2,"select cChallanCode , dChallanDate , cPartyName , cChallanNo ,{$YearPrefix}party_challan.cRemarks from {$YearPrefix}party_challan join party_master on {$YearPrefix}party_challan.iPartyCode= party_master.iPartyID  where iChallanID='$ChallanID'") ;
	if ($Chqry && mysqli_num_rows($Chqry)>0)
	{
		$Chrow=mysqli_fetch_array($Chqry);
	    $ChallanCode=$Chrow['cChallanCode'];
		$ChallanDate=$Chrow['dChallanDate'];
		$dArr=explode("-",$ChallanDate);
		$Date="$dArr[2]/$dArr[1]/$dArr[0]";
		$PartyName=$Chrow['cPartyName'];
		$PartyChallanNo=$Chrow['cChallanNo'];
		$Remarks=$Chrow['cRemarks'];
		$pdf->Cell(100,5,'Challan No :'.$ChallanCode,'',0,'');		
		$pdf->Cell(90,5,'Dated :'.$Date,'',0,'R');		
		$pdf->ln(7);
		$len=strlen($PartyName) + 40;
		$length=190- $len;
		$pdf->Cell($len,5,'To :'.strtoupper($PartyName),'B',0,'');	
		$pdf->Cell($length,5,'Delivery Challan No :'.$PartyChallanNo,'','1','R');
		
		$pdf->ln(7);
		$pdf->SetFont('Arial','B','10');
		$pdf->Cell(190,5,'We are returning your goods after TEETH CUTTUNG Job Work','B',0,'');		
		$pdf->SetFont('Arial','','10');
	}
	
	$pdf->ln(10);
	$pdf->SetFont('Arial','B','10');
	$pdf->Cell(20,5,'SNo','TBLR','','C');
	$pdf->Cell(130,5,'Item Name','TBLR','','C');
	$pdf->Cell(40,5,'Qty','TBR','1','C');
	$pdf->SetFont('Arial','','10');
	$ChDetailqry=mysqli_query ($con2,"SELECT sum(`iNoPcsDisp`) as iNoPcsDisp, sum(`iNoPcsReturn`) as iNoPcsReturn, `cItemType` , `iItemCode`, `cItemRemarks`,`cPartType` , `cPcs` , `cCollar` , cItemRemarks  FROM {$YearPrefix}party_challan_detail where `iChallanID`='$ChallanID' and `cCollar`='' group by `cItemType`, `iItemCode` ");
	if ($ChDetailqry && mysqli_num_rows($ChDetailqry)>0)
	{
		$SNo=1;
		while ($ChDetailrow=mysqli_fetch_array($ChDetailqry))
		{
			$ItemType=$ChDetailrow['cItemType'];	
			$ItemCode=$ChDetailrow['iItemCode']; 
			$DispPcs=$ChDetailrow['iNoPcsDisp']; 
			$ReturnPcs=$ChDetailrow['iNoPcsReturn'];
			$ItemRemarks=$ChDetailrow['cItemRemarks'];
			$Collar=$ChDetailrow['cCollar'];
			$Pcs=$ChDetailrow['cPcs'];
			
			if ($ItemType=="Gear")
			{
				$Itemqry=mysqli_query ($con2,"select * from gear_master where iId ='$ItemCode'");
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType']." face ".$Itemrow['fFace']." ".$Itemrow['cFaceType'];
				}
			}
			else if ($ItemType=="Pinion")
			{
				$Itemqry=mysqli_query ($con2,"select * from pinion_master where iId='$ItemCode'");	
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType']." face ".$Itemrow['fFace'];
					if ($Collar!="")
						$ItemName=$ItemName." + ".$Collar;
					$ItemName=$ItemName." ".$Itemrow['cFaceType'];
				}
			}
			else if ($ItemType=="Bevel Pinion")
			{
				$Itemqry=mysqli_query ($con2,"select * from bevel_pinion_master where iId='$ItemCode'");			
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth "." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType'];
				}
			}
			else if ($ItemType=="Bevel Gear")
			{
				$Itemqry=mysqli_query ($con2,"select * from bevel_gear_master where iId='$ItemCode'");			
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth "." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType'];
				}
			}
			else if ($ItemType=="Shaft Pinion")
			{
				$Itemqry=mysqli_query ($con2,"select * from shaft_pinion_master where iId='$ItemCode'");
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType']." face ".$Itemrow['fFace']." ".$Itemrow['cFaceType'];
				}
			}
			else if ($ItemType=="Chain Wheel")
			{
				$Itemqry=mysqli_query ($con2,"select * from chain_gear_master where iId='$ItemCode'");
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType']." pitch ".$Itemrow['fPitch']." ".$Itemrow['cPitchType'];
				}
			}
			else if ($ItemType=="Worm Gear")
			{
				$Itemqry=mysqli_query ($con2,"select * from worm_gear_master where iId='$ItemCode'");			
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth "." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType'];
				}
			}
			$pdf->Cell(20,5,"$SNo",'LR','','C');
			$pdf->Cell(130,5,$ItemType." ".$ItemName,'LR','','L');
			$pdf->Cell(40,5,$DispPcs,'R','1','C');
			$TotalDisp=$TotalDisp + $DispPcs + $ReturnPcs;
			if ($ChDetailrow['cItemRemarks']!="" || $ReturnPcs>0)
			{
				$pdf->SetFont('Arial','B','9');
				if ($ChDetailrow['cItemRemarks']!="" )
				{
					$pdf->Cell(20,5,'','LR','','C');
					$pdf->Cell(130,5,$ChDetailrow['cItemRemarks'],'LR','','L');
				}
				else
				{
					$pdf->Cell(20,5,'','LR','','C');
					$pdf->Cell(130,5,'','LR','','L');
				}
				$pdf->SetFont('Arial','','9');	
				if ($ReturnPcs>0)
					$pdf->Cell(40,5,$ReturnPcs.' (Returned)','R','1','C');
				else
					$pdf->Cell(40,5,'','R','1','C');
				$pdf->SetFont('Arial','','10');	
			}
			$SNo++;
		}
	}
	// Printing WIth Collar
	$ChDetailqry=mysqli_query ($con2,"select * from {$yearprefix}party_challan_detail where iChallanID='$ChallanID' and `cCollar`!='' ");
	if ($ChDetailqry && mysqli_num_rows($ChDetailqry)>0)
	{
		while ($ChDetailrow=mysqli_fetch_array($ChDetailqry))
		{
			$ItemType=$ChDetailrow['cItemType'];	
			$ItemCode=$ChDetailrow['iItemCode']; 
			$DispPcs=$ChDetailrow['iNoPcsDisp']; 
			$ReturnPcs=$ChDetailrow['iNoPcsReturn'];
			$ItemRemarks=$ChDetailrow['cItemRemarks'];
			$Collar=$ChDetailrow['cCollar'];
			$Pcs=$ChDetailrow['cPcs'];
			if ($ItemType=="Gear")
			{
				$Itemqry=mysqli_query ($con2,"select * from gear_master where iId ='$ItemCode'");
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType']." face ".$Itemrow['fFace']." ".$Itemrow['cFaceType'];
				}
			}
			else if ($ItemType=="Pinion")
			{
				$Itemqry=mysqli_query ($con2,"select * from pinion_master where iId='$ItemCode'");	
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType']." face ".$Itemrow['fFace'];
					if ($Collar!="")
						$ItemName=$ItemName." + ".$Collar;
					$ItemName=$ItemName." ".$Itemrow['cFaceType'];
				}
			}
			else if ($ItemType=="Bevel Pinion")
			{
				$Itemqry=mysqli_query ($con2,"select * from bevel_pinion_master where iId='$ItemCode'");			
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth "." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType'];
				}
			}
			else if ($ItemType=="Bevel Gear")
			{
				$Itemqry=mysqli_query ($con2,"select * from bevel_gear_master where iId='$ItemCode'");			
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth "." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType'];
				}
			}
			else if ($ItemType=="Shaft Pinion")
			{
				$Itemqry=mysqli_query ($con2,"select * from shaft_pinion_master where iId='$ItemCode'");
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType']." face ".$Itemrow['fFace']." ".$Itemrow['cFaceType'];
				}
			}
			else if ($ItemType=="Chain Wheel")
			{
				$Itemqry=mysqli_query ($con2,"select * from chain_gear_master where iId='$ItemCode'");
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType']." pitch ".$Itemrow['fPitch']." ".$Itemrow['cPitchType'];
				}
			}
			else if ($ItemType=="Worm Gear")
			{
				$Itemqry=mysqli_query ($con2,"select * from worm_gear_master where iId='$ItemCode'");			
				if ($Itemqry && mysqli_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysqli_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth "." dia ".$Itemrow['fDia']." ".$Itemrow['cDiaType'];
				}
			}
			$pdf->Cell(20,5,"$SNo",'LR','','C');
			$pdf->Cell(130,5,$ItemType." ".$ItemName,'LR','','L');
			$pdf->Cell(40,5,$DispPcs,'R','1','C');
		 	$TotalDisp=$TotalDisp + $DispPcs + $ReturnPcs;
			if ($ChDetailrow['cItemRemarks']!="" || $ReturnPcs>0)
			{
				$pdf->SetFont('Arial','B','9');
				if ($ChDetailrow['cItemRemarks']!="" )
				{
					$pdf->Cell(20,5,'','LR','','C');
					$pdf->Cell(130,5,$ChDetailrow['cItemRemarks'],'LR','','L');
				}
				else
				{
					$pdf->Cell(20,5,'','LR','','C');
					$pdf->Cell(130,5,'','LR','','L');
				}
				$pdf->SetFont('Arial','','9');	
				if ($ReturnPcs>0)
					$pdf->Cell(40,5,$ReturnPcs.' (Returned)','R','1','C');
				else
					$pdf->Cell(40,5,'','R','1','C');
				$pdf->SetFont('Arial','','10');	
			}
			$SNo++;
		}
	}
	$pdf->Cell(190,5,$Remarks,'TLR','1','L');
	$pdf->SetFont('Arial','B','10');
	$pdf->Cell(150,5,'Total :','TBLR','','C');
	$pdf->Cell(40,5,$TotalDisp.'  Pcs','TBR','','C');
	$pdf->Output();
?>