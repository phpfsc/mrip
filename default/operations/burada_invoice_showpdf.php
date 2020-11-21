<?php
	require('../fpdf/fpdf.php');
    require_once("../../config.php");
	if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($new_setting) {
        return true;
    }
}
	$pdf = new FPDF();
	$pdf->addpage();
	// Original Copy With ITC
    $BillingID=base64_decode(base64_decode($_GET['iBillingID']));
	
	
	 
	$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp from party_billing_detail where iBillingID =\"$BillingID\" and iOrderID=0 and cItemType =\"BURADA\"") or die(mysqli_error($con2));
	if ($Miscqry)
	{
		if (mysqli_num_rows($Miscqry)>0)
		{		
			$Miscrow=mysqli_fetch_array($Miscqry);
		    $BuradaName=$Miscrow['cMiscItemName'];
			$BuradaQty=$Miscrow['iNoPcsDisp'];
			$BuradaRate=$Miscrow['fRate']; 
		}
	}
   
	$Billingqry=mysqli_query($con2,"select cBillingCode , dBillingDate , iCompanyCode , iCompanySNo ,iPartyCode , iFirmSNo , fVatFactor,fVatPer,iCGSTVal ,iSGSTVal ,iIGSTVal ,  fBillAmt  from party_billing where iBillingID ='$BillingID'")or die(mysqli_error($con2));
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
		$VatFactor=$Billingrow['fVatFactor'];
		$VatPer=$Billingrow['fVatPer'];

		$CGSTPer=$Billingrow['iCGSTVal'];

		$SGSTPer=$Billingrow['iSGSTVal'];

	    $IGSTPer=$Billingrow['iIGSTVal'];

	    $BillAmt=$Billingrow['fBillAmt'];
		if ($CompanySNo>0)
		{
			$CmpDetailQry=mysqli_query($con2,"select cFirmName as Name ,cHSNCode as HSN , cGSTIn as GST,cFirmAddress as Address from  company_master_detail where iPartyID='$CompanyCode' and iSNo='$CompanySNo'")or die(mysqli_error($con2));
		}
		else
		{
			$CmpDetailQry=mysqli_query($con2,"select cPartyName as Name,cHSNCode as HSN , cGSTIn as GST, cAddress as Address from company_master where iPartyID='$CompanyCode'")or die(mysqli_error($con2));
		}
		if ($CmpDetailQry && mysqli_num_rows($CmpDetailQry)>0)
		{
			$CmpDetailrow=mysqli_fetch_array($CmpDetailQry);
			$CompanyName=$CmpDetailrow['Name'];
			
			$CompanyHSN=$CmpDetailrow['HSN'];
			
			$CompanyGST=$CmpDetailrow['GST'];

		    $CompanyAddress=$CmpDetailrow['Address'];
		}
		if ($PartySNo>0)
		{
			$PaDetailQry=mysqli_query($con2,"select cFirmName as Name , cFirmAddress as Address , cFirmTINNO as TinNo, cGSTIn as GST, cStateCode as StateCode from party_master_detail where iPartyID='$PartyCode' and iSNo='$PartySNo'")or die(mysqli_error($con2));
		}
		else
		{
			$PaDetailQry=mysqli_query($con2,"select cPartyName as Name , cAddress as Address , cTINNo as TinNo,cGSTIn as GST, cStateCode as StateCode from party_master where  iPartyID='$PartyCode'")or die(mysqli_error($con2));
		}

		if ($PaDetailQry && mysqli_num_rows($PaDetailQry)>0)
		{
			$PaDetailrow=mysqli_fetch_array($PaDetailQry);
		    $PartyName=$PaDetailrow['Name'];
			$PartyAddress=$PaDetailrow['Address'];

			$PartyGST=$PaDetailrow['GST'];
			
			$PartyStateCode=$PaDetailrow['StateCode'];
			
			$PartyTINNO=$PaDetailrow['TinNo'];
		}

		$pdf->SetFont('Arial','B','12');
		$pdf->Ln(6);
		$pdf->Cell(180,5,'Original Copy ','','1','R');

		$pdf->Ln(41);
		
		$pdf->Cell(50,5,'GST IN :'.$CompanyGST,'','0','L');
		
		$pdf->Cell(70,5,'HSN Code :'.$CompanyHSN,'','1','R');

		$pdf->Ln(5);
		
					
			//  1st Row.................
		$pdf->SetFont('Arial','b','14');
		$pdf->Cell(10,10,'M/S','LT','0','C');
		$pdf->Cell(115,10,$PartyName,'RT','','');
		$pdf->Cell(30,10,'Invoice No:','T',0,'R');
		$BillingCode=substr(strrchr($BillingCode,'/'),1);
		$pdf->Cell(40,10,$BillingCode,'TR',1,'L');
	
	
			//  2nd Row...................
		$PrevY=$pdf->GetY();
		$pdf->SetFont('Arial','B','12');
		$pdf->MultiCell(125,6,$PartyAddress,'LR','0','L');
		$pdf->SetFont('Arial','B','14');
		$NewY=$pdf->GetY();
		$h=($NewY - $PrevY);
		$pdf->SetY($pdf->GetY()- $h);
		$pdf->SetX(135);
		$pdf->Cell(20,$h,'Dated :','',0,'R');
		$pdf->Cell(50,$h,$InvDate,'R',1,'L');
					
			// 3rd Row....................	
		$pdf->Cell(125,8,'GST IN:'.$PartyGST.'  State Code:'.$PartyStateCode,'BLR','','L');
		$pdf->Cell(20,8,'','B',0,'R');
		$pdf->Cell(50,8,'','BR',1,'L');
		
		

		$pdf->SetFont('Arial','B','11');
		$pdf->Cell(10,7,'SNO','TBRL','','C');
		$pdf->Cell(122,7,'Full Description of Goods','TBRL','','C');
		$pdf->Cell(17,7,'Qnty.','TBR','','C');
		$pdf->Cell(18,7,'Rate','TBR','','C');
		$pdf->Cell(28,7,'Amount Rs.','TBR','1','C');
		$pdf->SetFont('Arial','','11');
		$pdf->Cell(10,3,'','RL','','');
		$pdf->Cell(122,3,'','RL','','');
		$pdf->Cell(17,3,'','RL','','');
		$pdf->Cell(18,3,'','RL','','');
		$pdf->Cell(28,3,'','RL','1','');
		$SNO=1;
		$pdf->SetFont('Arial','','11');
		
		

		$pdf->Cell(10,5,'1','RL','','C');
		$pdf->Cell(122,5,$BuradaName,'R','','C');
		$pdf->Cell(17,5,$BuradaQty.' Kg','R','','C');
		$pdf->Cell(18,5,$BuradaRate,'R','','C');
		$TotalPrice=($BuradaQty * $BuradaRate);
		$pdf->Cell(28,5,number_format($TotalPrice,'2','.',''),'R','1','C');

		$Totalheight=115;
		$Totalheight=$Totalheight - 5;
		$pdf->Cell(10,$Totalheight,'','LR','','');
		$pdf->Cell(122,$Totalheight,'','LR','','');
		$pdf->Cell(17,$Totalheight,'','R','','C');
		$pdf->Cell(18,$Totalheight,'','R','','C');
		$pdf->Cell(28,$Totalheight,'','R','1','C');
		
			
		$pdf->SetFont('Arial','B','11');
		// Round Off Total
		$TotalPrice=round($TotalPrice);
		$pdf->Cell(10,5,'','LR','','');
		$pdf->Cell(122,5,'','LR','','');
		$pdf->Cell(35,5,'TOTAL','R','','C');
		$pdf->Cell(28,5,number_format($TotalPrice,'2','.',''),'RTB','1','R');
		
       
		// Vat Percent
if(date($BillingDate)>=date('2017-07-01')){
	
	if($CGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'CGST @ '.$CGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$CGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($SGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'SGST @ '.$SGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$SGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($IGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'IGST @ '.$IGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$IGSTPer/100),'2','.',''),'RTB','1','R');
	}
	
		
}else{		
        
		$pdf->Cell(10,5,'','LR','','');
		if($VatFactor==100)
		{
			$pdf->Cell(122,5,'','LR','','');
			$pdf->Cell(35,5,'VAT @ '.$VatPer.' %','R','','C');
		}
		else
		{
			$pdf->Cell(157,5,'VAT @ '.$VatPer.' % on material consumed for this '.$VatFactor.'% of the work Rs'.number_format($TotalPrice*$VatFactor/100,'2','.','').'/=','LR','','R');
		}
		
		$VatAmt=(($TotalPrice*$VatFactor/100 * $VatPer)/100);
		$VatAmt=ceil($VatAmt);
		$pdf->Cell(28,5,number_format($VatAmt,'2','.',''),'RTB','1','R');
}
		echo $MiscAmt=(($TotalPrice * $MiscTaxPer)/100);
		$MiscAmt=ceil($MiscAmt);
		$TotalWithtax= round($TotalPrice +  $VatAmt +  $MiscAmt + round($TotalPrice*$CGSTPer/100) + round($TotalPrice*$SGSTPer/100) + round($TotalPrice*$IGSTPer/100));

		//$TotalWithtax= ($TotalPrice +  $VatAmt +  $MiscAmt);
		
		if ($MiscTaxPer>0)
		{
			$pdf->Cell(10,5,'','LR','','');
			$pdf->Cell(122,5,'','LR','','');
			$pdf->Cell(35,5,$MiscTaxName.' @ '.$MiscTaxPer.' %','R','','C');
			$pdf->Cell(28,5,number_format($MiscAmt,'2','.',''),'RTB','1','R');
		}
		
		
		
		$pdf->SetFont('Arial','','10');
		$pdf->Cell(132,5,'ITC is avaliable to a taxable person against Original Copy only.','TBLR','','');
		$pdf->Cell(17,5,'','B','','C');
		$pdf->SetFont('Arial','B','11');
		$pdf->Cell(18,5,'G.TOTAL','RB','','C');
		$pdf->Cell(28,5,number_format($TotalWithtax,'2','.',''),'RTB','1','R');
	
		require('../../general/amountinwords.php');
		$invamtwords=amountinwords($TotalWithtax);
		$invamtwords=$invamtwords." Only";
		
		
		$pdf->SetFont('Arial','B','11');
		$pdf->Cell(195,15,'Amount in words :'.$invamtwords,'LRB','1','');
		
	
		
		$pdf->SetFont('Arial','B','9');
		/*$pdf->Cell(10,4,'Note :','LT','0','');
		$pdf->SetFont('Arial','','9');
		$pdf->Cell(125,4,'Second copy should bear "Second copy" and also bear "Not For ITC"','TR','0','');
		
		
		$pdf->Cell(135,4,'Last copy should bear "Last Copy" and is to be retained by the seller','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		*/
		$pdf->SetFont('Arial','','9');
		$pdf->Cell(135,8,'Terms :Subject to Ludhiana Courts only','LR','0','');
		$pdf->Cell(10,8,'FOR ','T','','R');
		$pdf->SetFont('Arial','B','9');
		$pdf->Cell(50,8,strtoupper($CompanyName).'   ','TR','1','L');
		$pdf->SetFont('Arial','','9');
		
		$pdf->Cell(135,4,'Our risk and responsibility ceases once the goods Dispatched','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		$pdf->SetFont('Arial','B','8');
		$pdf->Cell(135,4,'','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		$pdf->Cell(135,4,'','LRB','0','');
		$pdf->Cell(60,4,'Auth. Signatory','RB','1','R');
		
		
	}
	
	// Duplicate Copy with ITC removed
	$pdf->addpage();
	$Billingqry=mysqli_query($con2,"select cBillingCode , dBillingDate , iCompanyCode , iCompanySNo ,iPartyCode , iFirmSNo ,fVatFactor, fVatPer,iCGSTVal ,iSGSTVal ,iIGSTVal , fBillAmt  from party_billing where iBillingID ='$BillingID'")or die(mysqli_error($con2));
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
		$VatFactor=$Billingrow['fVatFactor'];
		$VatPer=$Billingrow['fVatPer'];

		$CGSTPer=$Billingrow['iCGSTVal'];

		$SGSTPer=$Billingrow['iSGSTVal'];

		$IGSTPer=$Billingrow['iIGSTVal'];


		if ($CompanySNo>0)
		{
			$CmpDetailQry=mysqli_query($con2,"select cFirmName as Name ,cHSNCode as HSN , cGSTIn as GST,cFirmAddress as Address from  company_master_detail where iPartyID='$CompanyCode' and iSNo='$CompanySNo'")or die(mysqli_error($con2));
		}
		else
		{
			$CmpDetailQry=mysqli_query($con2,"select cPartyName as Name,cHSNCode as HSN , cGSTIn as GST, cAddress as Address from company_master where iPartyID='$CompanyCode'")or die(mysqli_error($con2));
		}
		if ($CmpDetailQry && mysqli_num_rows($CmpDetailQry)>0)
		{
			$CmpDetailrow=mysqli_fetch_array($CmpDetailQry);
			$CompanyName=$CmpDetailrow['Name'];
			
		    $CompanyHSN=$CmpDetailrow['HSN'];
			
			$CompanyGST=$CmpDetailrow['GST'];

			$CompanyAddress=$CmpDetailrow['Address'];
		}

		if ($PartySNo>0)
		{
			$PaDetailQry=mysqli_query($con2,"select cFirmName as Name , cFirmAddress as Address , cFirmTINNO as TinNo, cGSTIn as GST, cStateCode as StateCode from party_master_detail where iPartyID='$PartyCode' and iSNo='$PartySNo'")or die(mysqli_error($con2));
		}
		else
		{
			$PaDetailQry=mysqli_query($con2,"select cPartyName as Name , cAddress as Address , cTINNo as TinNo, cGSTIn as GST, cStateCode as StateCode from party_master where  iPartyID='$PartyCode'")or die(mysqli_error($con2));
		}

		if ($PaDetailQry && mysqli_num_rows($PaDetailQry)>0)
		{
			$PaDetailrow=mysqli_fetch_array($PaDetailQry);
			$PartyName=$PaDetailrow['Name'];
			$PartyAddress=$PaDetailrow['Address'];
			$PartyTINNO=$PaDetailrow['TinNo'];
		}
		
		$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp from party_billing_detail where iBillingID ='$BillingID' and iOrderID=0 and cItemType ='BURADA'")or die(mysqli_error($con2));
		if ($Miscqry)
		{
			if (mysqli_num_rows($Miscqry)>0)
			{		
				$Miscrow=mysqli_fetch_array($Miscqry);
				$BuradaName=$Miscrow['cMiscItemName'];
				$BuradaQty=$Miscrow['iNoPcsDisp'];
				$BuradaRate=$Miscrow['fRate']; 
			}
		}
		
		
		$pdf->SetFont('Arial','B','12');
		$pdf->Ln(6);
		$pdf->Cell(180,5,'Duplicate Copy ','','1','R');

		$pdf->Ln(41);
		
		$pdf->Cell(50,5,'GST IN :'.$CompanyGST,'','0','L');
		
		$pdf->Cell(70,5,'HSN Code :'.$CompanyHSN,'','1','R');

		$pdf->Ln(5);
			//  1st Row.................
		$pdf->SetFont('Arial','B','14');
		$pdf->Cell(10,10,'M/S','LT','0','C');
		$pdf->Cell(115,10,$PartyName,'RT','','');
		$pdf->Cell(30,10,'Invoice No:','T',0,'R');
		$BillingCode=substr(strrchr($BillingCode,'/'),1);
		$pdf->Cell(40,10,$BillingCode,'TR',1,'L');

			//  2nd Row...................
		//$pdf->SetFont('Arial','','8');
		$PrevY=$pdf->GetY();
		$pdf->SetFont('Arial','B','12');
		$pdf->MultiCell(125,6,$PartyAddress,'LR','0','L');
		$pdf->SetFont('Arial','B','14');
		$NewY=$pdf->GetY();
		$h=($NewY - $PrevY);
		$pdf->SetY($pdf->GetY()- $h);
		$pdf->SetX(135);
		//$pdf->SetFont('Arial','','10');
		$pdf->Cell(20,$h,'Dated :','',0,'R');
		$pdf->Cell(50,$h,$InvDate,'R',1,'L');
					
			// 3rd Row....................	
		$pdf->Cell(125,8,'GST IN:'.$PartyGST.'  State Code:'.$PartyStateCode,'BLR','','L');
		//$pdf->Cell(60,5,'TIN NO :'.$PartyTINNO,'RL','1','');
		$pdf->Cell(20,8,'','B',0,'R');
		$pdf->Cell(50,8,'','BR',1,'L');
		$pdf->SetFont('Arial','B','11');
		
		$pdf->Cell(10,7,'SNO','TBRL','','C');
		$pdf->Cell(122,7,'Full Description of Goods','TBRL','','C');
		$pdf->Cell(17,7,'Qnty.','TBR','','C');
		$pdf->Cell(18,7,'Rate','TBR','','C');
		$pdf->Cell(28,7,'Amount Rs.','TBR','1','C');
		$pdf->SetFont('Arial','','11');
		$pdf->Cell(10,3,'','RL','','');
		$pdf->Cell(122,3,'','RL','','');
		$pdf->Cell(17,3,'','RL','','');
		$pdf->Cell(18,3,'','RL','','');
		$pdf->Cell(28,3,'','RL','1','');
		$SNO=1;
		$pdf->SetFont('Arial','','11');

		$pdf->Cell(10,5,'1','RL','','C');
		$pdf->Cell(122,5,$BuradaName,'R','','C');
		$pdf->Cell(17,5,$BuradaQty.' Kg','R','','C');
		$pdf->Cell(18,5,$BuradaRate,'R','','C');
		$TotalPrice=($BuradaQty * $BuradaRate);
		$pdf->Cell(28,5,number_format($TotalPrice,'2','.',''),'R','1','C');

		$Totalheight=115;
		$Totalheight=$Totalheight - 5;
		$pdf->Cell(10,$Totalheight,'','LR','','');
		$pdf->Cell(122,$Totalheight,'','LR','','');
		$pdf->Cell(17,$Totalheight,'','R','','C');
		$pdf->Cell(18,$Totalheight,'','R','','C');
		$pdf->Cell(28,$Totalheight,'','R','1','C');
		
			// Round Off Total
			
		$pdf->SetFont('Arial','B','11');
		$TotalPrice=round($TotalPrice);
		$pdf->Cell(10,5,'','LR','','');
		$pdf->Cell(122,5,'','LR','','');
		$pdf->Cell(35,5,'TOTAL','R','','C');
		$pdf->Cell(28,5,number_format($TotalPrice,'2','.',''),'RTB','1','R');

		// Vat Percent
		if(date($BillingDate)>=date('2017-07-01')){
	
	if($CGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'CGST @ '.$CGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$CGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($SGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'SGST @ '.$SGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$SGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($IGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'IGST @ '.$IGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$IGSTPer/100),'2','.',''),'RTB','1','R');
	}
	
		
}else{
		$pdf->Cell(10,5,'','LR','','');
		if($VatFactor==100)
		{
			$pdf->Cell(122,5,'','LR','','');
			$pdf->Cell(35,5,'VAT @ '.$VatPer.' %','R','','C');
		}
		else
		{
			$pdf->Cell(157,5,'VAT @ '.$VatPer.' % on material consumed for this '.$VatFactor.'% of the work Rs'.number_format($TotalPrice*$VatFactor/100,'2','.','').'/=','LR','','R');
		}
		$VatAmt=(($TotalPrice*$VatFactor/100 * $VatPer)/100);
		$VatAmt=ceil($VatAmt);
		$pdf->Cell(28,5,number_format($VatAmt,'2','.',''),'RTB','1','R');
}
		$MiscAmt=(($TotalPrice * $MiscTaxPer)/100);
		$MiscAmt=ceil($MiscAmt);	
		$TotalWithtax= round($TotalPrice +  $VatAmt +  $MiscAmt + round($TotalPrice*$CGSTPer/100) + round($TotalPrice*$SGSTPer/100) + round($TotalPrice*$IGSTPer/100));

		//$TotalWithtax= ($TotalPrice +  $VatAmt +  $MiscAmt);
		if ($MiscTaxPer>0)
		{
			$pdf->Cell(10,5,'','LR','','');
			$pdf->Cell(122,5,'','LR','','');
			$pdf->Cell(35,5,$MiscTaxName.' @ '.$MiscTaxPer.' %','R','','C');
			$pdf->Cell(28,5,number_format($MiscAmt,'2','.',''),'RTB','1','R');
		}
		
		$pdf->SetFont('Arial','','10');
		
		$pdf->Cell(132,5,'NOTE: This copy does not entitle the holder to claim Input Tax Credit','TBRL','','');
		$pdf->Cell(17,5,'','B','','C');
		$pdf->SetFont('Arial','B','11');
		$pdf->Cell(18,5,'G.TOTAL','RB','','C');
		$pdf->Cell(28,5,number_format($TotalWithtax,'2','.',''),'RTB','1','R');
		
		// Amount in words.......

		$invamtwords=amountinwords($TotalWithtax);
		$invamtwords=$invamtwords." Only";
		$pdf->SetFont('Arial','B','11');
		$pdf->Cell(195,15,'Amount in words :'.$invamtwords,'LRB','1','');
		
		/*$pdf->SetFont('Arial','B','9');
		$pdf->Cell(10,4,'Note :','LT','0','');
		$pdf->SetFont('Arial','','9');
		$pdf->Cell(125,4,'Second copy should bear "Second copy" and also bear "Not For ITC"','TR','0','');
		
		$pdf->Cell(10,4,'FOR ','T','','R');
		$pdf->SetFont('Arial','B','9');
		$pdf->Cell(50,4,strtoupper($CompanyName).'   ','TR','1','L');
		$pdf->SetFont('Arial','','9');

		$pdf->Cell(135,4,'Last copy should bear "Last Copy" and is to be retained by the seller','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		$pdf->Cell(135,8,'Terms :Subject to Ludhiana Courts only','LR','0','');
		$pdf->Cell(60,8,'','R','1','');
		$pdf->Cell(135,4,'Our risk and responsibility ceases once the goods Dispatched','LRB','0','');
		$pdf->SetFont('Arial','B','8');
		$pdf->Cell(60,4,'Auth. Signatory','RB','1','R');*/
		$pdf->SetFont('Arial','','9');
		$pdf->Cell(135,8,'Terms :Subject to Ludhiana Courts only','LR','0','');
		$pdf->Cell(10,8,'FOR ','T','','R');
		$pdf->SetFont('Arial','B','9');
		$pdf->Cell(50,8,strtoupper($CompanyName).'   ','TR','1','L');
		$pdf->SetFont('Arial','','9');
		
		$pdf->Cell(135,4,'Our risk and responsibility ceases once the goods Dispatched','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		$pdf->SetFont('Arial','B','8');
		$pdf->Cell(135,4,'','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		$pdf->Cell(135,4,'','LRB','0','');
		$pdf->Cell(60,4,'Auth. Signatory','RB','1','R');
	}
	
	// Office Copy with ITC removed
	$pdf->addpage();
	$Billingqry=mysqli_query($con2,"select cBillingCode , dBillingDate , iCompanyCode , iCompanySNo ,iPartyCode , iFirmSNo ,fVatFactor, fVatPer,iCGSTVal ,iSGSTVal ,iIGSTVal , fBillAmt  from party_billing where iBillingID ='$BillingID'")or die(mysqli_error($con2));
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
		$VatFactor=$Billingrow['fVatFactor'];
		$VatPer=$Billingrow['fVatPer'];

		$CGSTPer=$Billingrow['iCGSTVal'];

		$SGSTPer=$Billingrow['iSGSTVal'];

		$IGSTPer=$Billingrow['iIGSTVal'];

		$BillAmt=$Billingrow['fBillAmt'];

		if ($CompanySNo>0)
		{
			$CmpDetailQry=mysqli_query($con2,"select cFirmName as Name ,cHSNCode as HSN , cGSTIn as GST,cFirmAddress as Address from  company_master_detail where iPartyID='$CompanyCode' and iSNo='$CompanySNo'")or die(mysqli_error($con2));
		}
		else
		{
			$CmpDetailQry=mysqli_query($con2,"select cPartyName as Name,cHSNCode as HSN , cGSTIn as GST, cAddress as Address from company_master where iPartyID='$CompanyCode'")or die(mysqli_error($con2));
		}
		if ($CmpDetailQry && mysqli_num_rows($CmpDetailQry)>0)
		{
			$CmpDetailrow=mysqli_fetch_array($CmpDetailQry);
			$CompanyName=$CmpDetailrow['Name'];
			
			$CompanyHSN=$CmpDetailrow['HSN'];
			
			$CompanyGST=$CmpDetailrow['GST'];

			$CompanyAddress=$CmpDetailrow['Address'];
		}

		if ($PartySNo>0)
		{
			$PaDetailQry=mysqli_query($con2,"select cFirmName as Name , cFirmAddress as Address , cFirmTINNO as TinNo, cGSTIn as GST, cStateCode as StateCode from party_master_detail where iPartyID='$PartyCode' and iSNo='$PartySNo'")or die(mysqli_error($con2));
		}
		else
		{
			$PaDetailQry=mysqli_query($con2,"select cPartyName as Name , cAddress as Address , cTINNo as TinNo, cGSTIn as GST, cStateCode as StateCode from party_master where  iPartyID='$PartyCode'")or die(mysqli_error($con2));
		}

		if ($PaDetailQry && mysqli_num_rows($PaDetailQry)>0)
		{
			$PaDetailrow=mysqli_fetch_array($PaDetailQry);
			$PartyName=$PaDetailrow['Name'];
			$PartyAddress=$PaDetailrow['Address'];
			$PartyTINNO=$PaDetailrow['TinNo'];
		}
		
		$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp from party_billing_detail where iBillingID ='$BillingID' and iOrderID=0 and cItemType ='BURADA'")or die(mysqli_error($con2));
		if ($Miscqry)
		{
			if (mysqli_num_rows($Miscqry)>0)
			{		
				$Miscrow=mysqli_fetch_array($Miscqry);
				$BuradaName=$Miscrow['cMiscItemName'];
				$BuradaQty=$Miscrow['iNoPcsDisp'];
				$BuradaRate=$Miscrow['fRate']; 
			}
		}
		$pdf->SetFont('Arial','B','12');
		$pdf->Ln(6);
		$pdf->Cell(180,5,'Office Copy ','','1','R');

		$pdf->Ln(41);
		
		$pdf->Cell(50,5,'GST IN :'.$CompanyGST,'','0','L');
		
		$pdf->Cell(70,5,'HSN Code :'.$CompanyHSN,'','1','R');

		$pdf->Ln(5);
			//  1st Row.................
		$pdf->SetFont('Arial','B','14');
		$pdf->Cell(10,10,'M/S','LT','0','C');
		$pdf->Cell(115,10,$PartyName,'RT','','');
		$pdf->Cell(30,10,'Invoice No:','T',0,'R');
		$BillingCode=substr(strrchr($BillingCode,'/'),1);
		$pdf->Cell(40,10,$BillingCode,'TR',1,'L');

			//  2nd Row...................
		//$pdf->SetFont('Arial','','8');
		$PrevY=$pdf->GetY();
		$pdf->SetFont('Arial','B','12');
		$pdf->MultiCell(125,6,$PartyAddress,'LR','0','L');
		$NewY=$pdf->GetY();
		$h=($NewY - $PrevY);
		$pdf->SetY($pdf->GetY()- $h);
		$pdf->SetX(135);
		$pdf->SetFont('Arial','B','14');
		$pdf->Cell(20,$h,'Dated :','',0,'R');
		$pdf->Cell(50,$h,$InvDate,'R',1,'L');
					
			// 3rd Row....................	
		$pdf->Cell(125,8,'GST IN:'.$PartyGST.'  State Code:'.$PartyStateCode,'BLR','','L');
		//$pdf->Cell(60,5,'TIN NO :'.$PartyTINNO,'RL','1','');
		$pdf->Cell(20,8,'','B',0,'R');
		$pdf->Cell(50,8,'','BR',1,'L');

		$pdf->SetFont('Arial','B','11');
		$pdf->Cell(10,7,'SNO','TBRL','','C');
		$pdf->Cell(122,7,'Full Description of Goods','TBRL','','C');
		$pdf->Cell(17,7,'Qnty.','TBR','','C');
		$pdf->Cell(18,7,'Rate','TBR','','C');
		$pdf->Cell(28,7,'Amount Rs.','TBR','1','C');
		$pdf->SetFont('Arial','','11');
		$pdf->Cell(10,3,'','RL','','');
		$pdf->Cell(122,3,'','RL','','');
		$pdf->Cell(17,3,'','RL','','');
		$pdf->Cell(18,3,'','RL','','');
		$pdf->Cell(28,3,'','RL','1','');
		$SNO=1;
		$pdf->SetFont('Arial','','11');

		$pdf->Cell(10,5,'1','RL','','C');
		$pdf->Cell(122,5,$BuradaName,'R','','C');
		$pdf->Cell(17,5,$BuradaQty.' Kg','R','','C');
		$pdf->Cell(18,5,$BuradaRate,'R','','C');
		$TotalPrice=($BuradaQty * $BuradaRate);
		$pdf->Cell(28,5,number_format($TotalPrice,'2','.',''),'R','1','C');

		$Totalheight=115;
		$Totalheight=$Totalheight - 5;
		$pdf->Cell(10,$Totalheight,'','LR','','');
		$pdf->Cell(122,$Totalheight,'','LR','','');
		$pdf->Cell(17,$Totalheight,'','R','','C');
		$pdf->Cell(18,$Totalheight,'','R','','C');
		$pdf->Cell(28,$Totalheight,'','R','1','C');
		
		
		// Round Off Total
		$pdf->SetFont('Arial','B','11');
		$TotalPrice=round($TotalPrice);
		$pdf->Cell(10,5,'','LR','','');
		$pdf->Cell(122,5,'','LR','','');
		$pdf->Cell(35,5,'TOTAL','R','','C');
		$pdf->Cell(28,5,number_format($TotalPrice,'2','.',''),'RTB','1','R');
       
		// Vat Percent
if(date($BillingDate)>=date('2017-07-01')){
	
	if($CGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'CGST @ '.$CGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$CGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($SGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'SGST @ '.$SGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$SGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($IGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(35,5,'IGST @ '.$IGSTPer.' %','R','','C');

		$pdf->Cell(28,5,number_format(round($TotalPrice*$IGSTPer/100),'2','.',''),'RTB','1','R');
	}
	
		
}else{
			$pdf->Cell(10,5,'','LR','','');
			if($VatFactor==100)
			{
				$pdf->Cell(122,5,'','LR','','');
				$pdf->Cell(35,5,'VAT @ '.$VatPer.' %','R','','C');
			}
			else
			{
				$pdf->Cell(157,5,'VAT @ '.$VatPer.' % on material consumed for this '.$VatFactor.'% of the work Rs'.number_format($TotalPrice*$VatFactor/100,'2','.','').'/=','LR','','R');
			}
			$VatAmt=(($TotalPrice*$VatFactor/100 * $VatPer)/100);
			$VatAmt=ceil($VatAmt);
			$pdf->Cell(28,5,number_format($VatAmt,'2','.',''),'RTB','1','R');
}		
			$MiscAmt=(($TotalPrice * $MiscTaxPer)/100);
			$MiscAmt=ceil($MiscAmt);	
			$TotalWithtax= round($TotalPrice +  $VatAmt +  $MiscAmt + round($TotalPrice*$CGSTPer/100) + round($TotalPrice*$SGSTPer/100) + round($TotalPrice*$IGSTPer/100));

			if ($MiscTaxPer>0)
			{
				$pdf->Cell(10,5,'','LR','','');
				$pdf->Cell(122,5,'','LR','','');
				$pdf->Cell(35,5,$MiscTaxName.' @ '.$MiscTaxPer.' %','R','','C');
				$pdf->Cell(28,5,number_format($MiscAmt,'2','.',''),'RTB','1','R');
			}
		
		
		$pdf->SetFont('Arial','','10');
		
		$pdf->Cell(132,5,'NOTE: This copy does not entitle the holder to claim Input Tax Credit','TBRL','','');
		$pdf->Cell(17,5,'','B','','C');
		$pdf->SetFont('Arial','B','11');
		$pdf->Cell(18,5,'G.TOTAL','RB','','C');
		$pdf->Cell(28,5,number_format($TotalWithtax,'2','.',''),'RTB','1','R');
		
		// Amount in words.......

		$invamtwords=amountinwords($TotalWithtax);
		$invamtwords=$invamtwords." Only";
		$pdf->SetFont('Arial','B','11');
		$pdf->Cell(195,15,'Amount in words :'.$invamtwords,'LRB','1','');
		
		/*$pdf->SetFont('Arial','B','9');
		$pdf->Cell(10,4,'Note :','LT','0','');
		$pdf->SetFont('Arial','','9');
		$pdf->Cell(125,4,'Second copy should bear "Second copy" and also bear "Not For ITC"','TR','0','');
		
		$pdf->Cell(10,4,'FOR ','T','','R');
		$pdf->SetFont('Arial','B','9');
		$pdf->Cell(50,4,strtoupper($CompanyName).'   ','TR','1','L');
		$pdf->SetFont('Arial','','9');

		$pdf->Cell(135,4,'Last copy should bear "Last Copy" and is to be retained by the seller','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		$pdf->Cell(135,8,'Terms :Subject to Ludhiana Courts only','LR','0','');
		$pdf->Cell(60,8,'','R','1','');
		$pdf->Cell(135,4,'Our risk and responsibility ceases once the goods Dispatched','LRB','0','');
		$pdf->SetFont('Arial','B','8');
		$pdf->Cell(60,4,'Auth. Signatory','RB','1','R');*/
		$pdf->SetFont('Arial','','9');
		$pdf->Cell(135,8,'Terms :Subject to Ludhiana Courts only','LR','0','');
		$pdf->Cell(10,8,'FOR ','T','','R');
		$pdf->SetFont('Arial','B','9');
		$pdf->Cell(50,8,strtoupper($CompanyName).'   ','TR','1','L');
		$pdf->SetFont('Arial','','9');
		
		$pdf->Cell(135,4,'Our risk and responsibility ceases once the goods Dispatched','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		$pdf->SetFont('Arial','B','8');
		$pdf->Cell(135,4,'','LR','0','');
		$pdf->Cell(60,4,'','R','1','');
		$pdf->Cell(135,4,'','LRB','0','');
		$pdf->Cell(60,4,'Auth. Signatory','RB','1','R');
	}
	
	$pdf->Output();
?>