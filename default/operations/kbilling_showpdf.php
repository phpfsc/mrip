<?php
	require('../fpdf/fpdf.php');
    require ('../../config.php');
    $pdf = new FPDF();
	$pdf->addpage();
	$pdf->SetAutoPageBreak(on ,1);
	$height=0;
	if (!function_exists('set_magic_quotes_runtime')) {
		function set_magic_quotes_runtime($new_setting) {
			return true;
		}
    }
	$DataArray=explode("~",base64_decode(base64_decode($_GET['Data'])));
    $BillingID=$DataArray[0];
    $tablePrefix=$DataArray[1]; //table prefix old or new table
	
	$State=$_GET['State'];
	$TotalPrice=0;
	
	
	$Billingqry=mysqli_query($con2,"select cBillingCode , dBillingDate , iCompanyCode , iCompanySNo ,iPartyCode , iFirmSNo   from {$tablePrefix}k_party_billing where iBillingID =\"$BillingID\"");
	if ($Billingqry && mysqli_num_rows($Billingqry)>0)
	{
		$Billingrow=mysqli_fetch_array($Billingqry);
		$BillingCode=$Billingrow['cBillingCode'];
		$BillingDate=$Billingrow['dBillingDate'];
		$dArr=explode('-',$BillingDate);
		$InvDate="$dArr[2]/$dArr[1]/$dArr[0]";
		$CompanyCode=$Billingrow['iCompanyCode'];
		$CompanySNo=$Billingrow['iCompanySNo'];
		$PartyCode=$Billingrow['iPartyCode'];
		$PartySNo=$Billingrow['iFirmSNo'];

		if ($CompanySNo>0)
		{
			$CmpDetailQry=mysqli_query($con2,"select cFirmName as Name ,cFirmAddress as Address from  company_master_detail where iPartyID='$CompanyCode' and iSNo='$CompanySNo'");
		}
		else
		{
			$CmpDetailQry=mysqli_query($con2,"select cPartyName as Name, cAddress as Address  from company_master where iPartyID='$CompanyCode'")or die(mysql_error());
		}
		if ($CmpDetailQry && mysqli_num_rows($CmpDetailQry)>0)
		{
			$CmpDetailrow=mysqli_fetch_array($CmpDetailQry);
			$CompanyName=$CmpDetailrow['Name'];
			$CompanyAddress=$CmpDetailrow['Address'];
		}

		if ($PartySNo>0)
		{
			$PaDetailQry=mysqli_query($con2,"select cFirmName as Name , cFirmAddress as Address , cFirmTINNO as TinNo from party_master_detail where iPartyID='$PartyCode' and iSNo='$PartySNo'");
		}
		else
		{
			$PaDetailQry=mysqli_query($con2,"select cPartyName as Name , cAddress as Address , cTINNo as TinNo from party_master where  iPartyID='$PartyCode'");
		}
		if ($PaDetailQry && mysqli_num_rows($PaDetailQry)>0)
		{
			$PaDetailrow=mysqli_fetch_array($PaDetailQry);
			$PartyName=$PaDetailrow['Name'];
		
		}
		$pdf->Ln(10);
		$pdf->SetFont('Arial','B','14');
		$pdf->Cell(180,10,'	ESTIMATE ','','1','C');
		$pdf->Ln(23);
			//  1st Row.................
		
		$pdf->Cell(10,10,'M/S','LT','0','C');
		$pdf->Cell(115,10,$PartyName,'RT','','');
		$pdf->Cell(30,10,'','T',0,'R');
		$pdf->Cell(40,10,'','TR',1,'L');

			//  2nd Row...................
		$PrevY=$pdf->GetY();
		$pdf->MultiCell(125,6,'','LR','0','L');
		$NewY=$pdf->GetY();
		$h=($NewY - $PrevY);
		$pdf->SetY($pdf->GetY()- $h);
		$pdf->SetX(135);
		$pdf->Cell(20,$h,'Dated :','',0,'R');
		$pdf->Cell(50,$h,$InvDate,'R',1,'L');
					
			// 3rd Row....................	
		$pdf->Cell(125,8,'','BLR','','L');
		$pdf->Cell(20,8,'','B',0,'R');
		$pdf->Cell(50,8,'','BR',1,'L');
		$pdf->SetFont('Arial','','10');
		$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp, fMinValue from {$tablePrefix}k_party_billing_detail where iBillingID ='$BillingID' and iOrderID=0 and cItemType ='MISC'");
		if ($Miscqry)
		{
			if (mysqli_num_rows($Miscqry)>0)
			{
				$Miscrow=mysqli_fetch_array($Miscqry);
				$MiscItemName=$Miscrow['cMiscItemName'];
				$MiscItemQty=$Miscrow['iNoPcsDisp'];
				$MiscItemRate=$Miscrow['fRate'];
				
			}
		}
		
		$BDetailqry=mysqli_query($con2,"select * from {$tablePrefix}k_party_billing_detail where iBillingID ='$BillingID' and cItemType<>'MISC'");
		if ($BDetailqry && mysqli_num_rows($BDetailqry)>0)
		{	
			$pdf->SetFont('Arial','B','11');
			$pdf->Cell(10,7,'SNO','TBRL','','C');
			$pdf->Cell(122,7,'Full Description of Goods','TBRL','','C');
			$pdf->Cell(15,7,'Qnty.','TBR','','C');
			$pdf->Cell(23,7,'Rate','TBR','','C');
			$pdf->Cell(25,7,'Amount Rs.','TBR','1','C');
			$pdf->SetFont('Arial','','11');
			$pdf->Cell(10,3,'','RL','','');
			$pdf->Cell(122,3,'','RL','','');
			$pdf->Cell(15,3,'','RL','','');
			$pdf->Cell(23,3,'','RL','','');
			$pdf->Cell(25,3,'','RL','1','');
			$SNO=1;
			while ($BDetailrow=mysqli_fetch_array($BDetailqry))
			{
				$ItemType=$BDetailrow['cItemType'];
				$ItemCode=$BDetailrow['iItemCode'];
				$Rate=$BDetailrow['fRate'];
				$Disp=$BDetailrow['iNoPcsDisp'];
				$PartType=$BDetailrow['cPartType'];
				$Pcs=$BDetailrow['cPcs'] ;
				$MinValue=$BDetailrow['fMinValue'];
				if ($Pcs=="PCS")
				{
					$Pcs=str_replace("PCS","PC",$Pcs);
				}

				if ($ItemType=="Gear")
				{
					$Itemqry=mysqli_query($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ) as cName, iTeeth from gear_master where iId ='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['cName'];
					}
					if ($Pcs=='PT')
					{
						$Price=$Disp*$Itemrow['iTeeth']* $Rate; 
					}
					else
					{
						$Price=$Disp* $Rate; 	
					}
					
				}
				else if ($ItemType=="Pinion")
				{
					//if ($PartType=="PowerPress")
						//$Itemqry=mysqli_query($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType,'(', fDMValue ,' ',cType ,')') as cName , iTeeth from pinion_master where iId='$ItemCode'");	
					//else
						$Itemqry=mysqli_query($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ) as cName , iTeeth from pinion_master where iId='$ItemCode'");	
					
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['cName'];
					}
					 
					if ($Pcs=='PT')
					{
						$Price=$Disp*$Itemrow['iTeeth']* $Rate;	
					}
					else
					{
						$Price=$Disp * $Rate;
					}
					
					if ($PartType=="PowerPress")
					{
						$IPrice=$Price/$Disp;
						
						if ($IPrice<$MinValue)
						{
							$Price=$MinValue*$Disp;
							$Rate=$MinValue;
							$Pcs="PC";
						}
					}
				}
				else if ($ItemType=="Bevel Pinion")
				{
					$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName , iTeeth from bevel_pinion_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['cName'];
					}
					if ($Pcs=='PT')
					{
						$Price=$Disp * $Rate * $Itemrow['iTeeth'];
					}
					else
					{
						$Price=$Disp * $Rate ;
					}
				}
				else if ($ItemType=="Bevel Gear")
				{
					$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName,iTeeth  from bevel_gear_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['cName'];
					}
					if ($Pcs=='PT')
					{
						$Price=$Disp * $Rate * $Itemrow['iTeeth'];
					}
					else
					{
						$Price=$Disp * $Rate ;
					}
					
				}
				else if ($ItemType=="Shaft Pinion")
				{
					$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth ',cItemType ,' dia ',fDia ,' ',cDiaType , ' face ',fFace ,' ', cFaceType) as cName, iTeeth  from shaft_pinion_master where iId='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['cName'];
						
					}
					if ($Pcs=='PT')
					{
						$Price=$Disp * $Rate * $Itemrow['iTeeth'];
					}
					else
					{
						$Price=$Disp * $Rate;
					}
					
				}
				else if ($ItemType=="Chain Wheel")
				{
					$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia , ' ',cDiaType , ' pitch ', fPitch ,' ', cPitchType ) as cName, iTeeth , cItemType from chain_gear_master where iId='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						if (trim($Itemrow['cItemType'])=="Single")
							$ItemName=$Itemrow['cName'];
						else
							$ItemName=$Itemrow['cName']." ( ".$Itemrow['cItemType']." )" ;
					}
					if ($Pcs=='PT')
					{
						$Price=$Disp* $Itemrow['iTeeth']*$Rate;
					}
					else
					{
						$Price=$Disp* $Rate;
					}
				}
				else if ($ItemType=="Worm Gear")
				{
					$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName,iTeeth  from worm_gear_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						$ItemName=$Itemrow['cName'];
					}
					if ($Pcs=='PT')
					{
						$Price=$Disp * $Rate  * $Itemrow['iTeeth'];
					}
					else
					{
						$Price=$Disp * $Rate;
					}
					
				}

				$pdf->Cell(10,5,$SNO,'RL','','C');
				$pdf->Cell(122,5,$ItemType.' '.$ItemName,'RL','','');
				$pdf->Cell(15,5,$Disp,'R','','C');
				$pdf->Cell(23,5,number_format($Rate,'2','.','').' '.$Pcs,'R','','R');
				$pdf->Cell(25,5,number_format($Price,'2','.',''),'R','1','R');
				$TotalPrice=$TotalPrice + $Price;
				$SNO++;
				$height=$height + 5;
			}
		}
		if ($MiscItemName!='' && $MiscItemQty>0 && $MiscItemRate>0)
		{
			$pdf->Cell(10,5,$SNO,'RL','','C');
			$pdf->Cell(122,5,$MiscItemName,'RL','','');
			$pdf->Cell(15,5,$MiscItemQty,'R','','C');
			$pdf->Cell(23,5,number_format($MiscItemRate,'2','.',''),'R','','R');
			$Price=$MiscItemQty * $MiscItemRate;
			$pdf->Cell(25,5,number_format($Price,'2','.',''),'R','1','R');
			$TotalPrice=$TotalPrice + $Price;
			$height=$height + 5;
		}
		$Totalheight=195;
		$Totalheight=$Totalheight - $height;
		$pdf->Cell(10,$Totalheight,'','LR','','');
		$pdf->Cell(122,$Totalheight,'','LR','','');
		$pdf->Cell(15,$Totalheight,'','R','','C');
		$pdf->Cell(23,$Totalheight,'','R','','C');
		$pdf->Cell(25,$Totalheight,'','R','1','C');
		
		// Round Off Total
		$TotalPrice=round($TotalPrice);
		$pdf->Cell(10,5,'','LR','','');
		$pdf->Cell(122,5,'','LR','','');
		$pdf->Cell(38,5,'TOTAL','R','','C');
		$pdf->Cell(25,5,number_format($TotalPrice,'2','.',''),'RTB','1','R');
		
		$pdf->SetFont('Arial','','10');
		$pdf->Cell(132,5,'','TBLR','','');
		$pdf->Cell(15,5,'','B','','C');
		$pdf->SetFont('Arial','','11');
		$pdf->Cell(23,5,'G.TOTAL','RB','','C');
		$pdf->Cell(25,5,number_format($TotalPrice,'2','.',''),'RTB','1','R');
	}
	else
	{
		$pdf->SetFont('Arial','B','15');
		$pdf->Cell(195,4,'Error in  Printing','','','C');
	}
	
	$pdf->Output();
?>