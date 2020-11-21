<?php
    
	require('../fpdf/fpdf.php');
    require_once("../../config.php");
	error_reporting(0);
	if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($new_setting) {
        return true;
    }
}
	$DataArray=explode("~",base64_decode(base64_decode($_GET['Data'])));
    $BillingID=$DataArray[0];
    $tablePrefix=$DataArray[1];
	$pdf=new FPDF('P','mm','A4');
    $pdf->SetAutoPageBreak(on ,1); 
    $pdf->addpage();
    
    $State=$_GET['state'];

	$TotalPrice=0;
   // Original Copy with ITC

	$Billingqry=mysqli_query($con2,"select cBillingCode , dBillingDate , iCompanyCode , iCompanySNo ,iPartyCode , iFirmSNo , fVatFactor, fVatPer,iCGSTVal ,iSGSTVal ,iIGSTVal , cMiscTaxName,fMiscTaxPer, fSurcharge  from {$tablePrefix}party_billing where iBillingID ='$BillingID'");

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

		$MiscTaxName=$Billingrow['cMiscTaxName'];

		$MiscTaxPer=$Billingrow['fMiscTaxPer'];

		$SurchargePer=$Billingrow['fSurcharge'];



		if ($CompanySNo>0)

		{

			$CmpDetailQry=mysqli_query($con2,"select cFirmName as Name ,cHSNCode as HSN , cGSTIn as GST,cFirmAddress as Address from  company_master_detail where iPartyID='$CompanyCode' and iSNo='$CompanySNo'");

		}

		else

		{

			$CmpDetailQry=mysqli_query($con2,"select cPartyName as Name,cHSNCode as HSN , cGSTIn as GST, cAddress as Address  from company_master where iPartyID='$CompanyCode'")or die(mysqli_error($con2));

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

			$PaDetailQry=mysqli_query($con2,"select cFirmName as Name , cFirmAddress as Address , cGSTIn as GST, cStateCode as StateCode from party_master_detail where iPartyID='$PartyCode' and iSNo='$PartySNo'");

		}

		else

		{

			$PaDetailQry=mysqli_query($con2,"select cPartyName as Name , cAddress as Address , cGSTIn as GST, cStateCode as StateCode from party_master where  iPartyID='$PartyCode'");

		}

		if ($PaDetailQry && mysqli_num_rows($PaDetailQry)>0)

		{

			$PaDetailrow=mysqli_fetch_array($PaDetailQry);

			$PartyName=$PaDetailrow['Name'];

			$PartyAddress=$PaDetailrow['Address'];

			$PartyGST=$PaDetailrow['GST'];
			
			$PartyStateCode=$PaDetailrow['StateCode'];

		}

		

		$pdf->SetFont('Arial','B','12');

		//$pdf->Ln(6);

		$pdf->Ln(6);

		$pdf->Cell(180,5,'Original Copy ','','1','R');

		$pdf->Ln(41);
		
		$pdf->Cell(50,5,'GST IN :'.$CompanyGST,'','0','L');
		
		$pdf->Cell(70,5,'SAC/HSN Code :'.$CompanyHSN,'','1','R');

		$pdf->Ln(5);

			//  1st Row.................

		$pdf->SetFont('Arial','B','13');

		$pdf->Cell(10,10,'M/S','LT','0','C');

		$pdf->Cell(115,10,$PartyName,'RT','','');

		$pdf->Cell(30,10,'Invoice No:','T',0,'R');

		$BillingCode=substr(strrchr($BillingCode,'/'),1);

		$pdf->Cell(40,10,$BillingCode,'TR',1,'L');

			//  2nd Row...................

		$PrevY=$pdf->GetY();

		$pdf->SetFont('Arial','B','11');

		$pdf->MultiCell(125,6,$PartyAddress,'LR','0','L');

		$pdf->SetFont('Arial','B','13');

		$NewY=$pdf->GetY();

		$h=($NewY - $PrevY);

		$pdf->SetY($pdf->GetY()- $h);

		$pdf->SetX(135);

		$pdf->Cell(20,$h,'Dated :','',0,'R');

		$pdf->Cell(50,$h,$InvDate,'R',1,'L');

					

			// 3rd Row....................	

		$pdf->Cell(125,8,'GST IN:'.$PartyGST.'  State Code:'.$PartyStateCode,'BLR','','L');
		
		//$pdf->Cell(125,8,'State Code:'.$PartyStateCode,'BLR','','L');

		$pdf->Cell(20,8,'','B',0,'R');

		$pdf->Cell(50,8,'','BR',1,'L');



		$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp from {$tablePrefix}party_billing_detail where iBillingID ='$BillingID' and iOrderID=0 and cItemType ='MISC'");

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

		

		$BDetailqry=mysqli_query($con2,"select * from {$tablePrefix}party_billing_detail where iBillingID ='$BillingID' and cItemType<>'MISC'");

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

				$Pcs=$BDetailrow['cPcs'];

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

						$Price=$Disp* $Itemrow['iTeeth']*$Rate;

					}

					else

					{

						$Price=$Disp* $Rate;

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

		$Totalheight=120;

		$Totalheight=$Totalheight - $height;

		$pdf->Cell(10,$Totalheight,'','LR','','');

		$pdf->Cell(122,$Totalheight,'','LR','','');

		$pdf->Cell(15,$Totalheight,'','R','','C');

		$pdf->Cell(23,$Totalheight,'','R','','C');

		$pdf->Cell(25,$Totalheight,'','R','1','C');

		

		// Round Off Total

		$pdf->SetFont('Arial','B','11');
		$TotalPrice=round($TotalPrice);

		$pdf->Cell(10,5,'','LR','','');

		$pdf->Cell(122,5,'','LR','','');

		$pdf->Cell(38,5,'TOTAL','R','','C');

		$pdf->Cell(25,5,number_format($TotalPrice,'2','.',''),'RTB','1','R');

	
if(date($BillingDate)>=date('2017-07-01')){
	
	if($CGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'CGST @ '.$CGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$CGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($SGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'SGST @ '.$SGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$SGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($IGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'IGST @ '.$IGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$IGSTPer/100),'2','.',''),'RTB','1','R');
	}
		
}else{
	
		// Vat Percent

		$pdf->Cell(10,5,'','LR','','');

		if($VatFactor==100)

		{

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'VAT @ '.$VatPer.' %','R','','C');

		}

		else

		{

			$pdf->Cell(160,5,'VAT @ '.$VatPer.' % on material consumed for this '.$VatFactor.'% of the work Rs'.number_format($TotalPrice*$VatFactor/100,'2','.','').'/=','LR','','R');

		}	

		$VatAmt=(($TotalPrice*$VatFactor/100 * $VatPer)/100);

		$VatAmt=ceil($VatAmt);

		$pdf->Cell(25,5,number_format($VatAmt,'2','.',''),'RTB','1','R');

		

		

		// Surcharge

		$SurchargeAmt=(($VatAmt * $SurchargePer)/100);

		$FSurchargeAmt=floor($SurchargeAmt);

		$DSurchargeAmt= $SurchargeAmt - $FSurchargeAmt;

		if ($DSurchargeAmt>0.24)

			$SurchargeAmt=ceil($SurchargeAmt);

		else

			$SurchargeAmt=floor($SurchargeAmt);

		if($SurchargePer>0)

		{

			$pdf->Cell(10,5,'','LR','','');

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'Surcharge @ '.$SurchargePer.' %','R','','C');

			$pdf->Cell(25,5,number_format($SurchargeAmt,'2','.',''),'RTB','1','R');

		}		

}
		$MiscAmt=(($TotalPrice * $MiscTaxPer)/100);

		$MiscAmt=ceil($MiscAmt);	

		$TotalWithtax= round($TotalPrice +  $VatAmt +  $MiscAmt + $SurchargeAmt + ceil($TotalPrice*$CGSTPer/100) + ceil($TotalPrice*$SGSTPer/100) + ceil($TotalPrice*$IGSTPer/100));

		

		if ($MiscTaxPer>0)

		{

			$pdf->Cell(10,5,'','LR','','');

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,$MiscTaxName.' @ '.$MiscTaxPer.' %','R','','C');

			$pdf->Cell(25,5,number_format($MiscAmt,'2','.',''),'RTB','1','R');

		}

		

		$pdf->SetFont('Arial','','10');

		$pdf->Cell(132,5,'ITC is avaliable to a taxable person against Original Copy only.','TBLR','','');

		$pdf->Cell(15,5,'','B','','C');

		$pdf->SetFont('Arial','B','11');

		$pdf->Cell(23,5,'G.TOTAL','RB','','C');

		$pdf->Cell(25,5,number_format(round($TotalWithtax),'2','.',''),'RTB','1','R');

		

		// Amount in words.......
		require('../../general/amountinwords.php');
		
		$invamtwords=amountinwords($TotalWithtax);

		$invamtwords=$invamtwords." Only";

		$pdf->SetFont('Arial','B','11');

		$pdf->Cell(195,15,'Amount in words :'.$invamtwords,'LRB','1','');

		

		$pdf->SetFont('Arial','B','9');

		$pdf->Cell(10,4,'','LT','0','');

		$pdf->SetFont('Arial','','9');

		$pdf->Cell(125,4,'','TR','0','');

		if ($State=="yes")

		{

			$pdf->Cell(10,4,'FOR ','T','','R');

			$pdf->SetFont('Arial','B','9');

			if (trim($CompanyName)=="PARKASH INDUSTRIAL CORPORATION")

				$CompanyName="PARKASH INDUSTRIAL CORP.";

			$pdf->Cell(50,4,strtoupper($CompanyName).'   ','TR','1','L');

			$pdf->SetFont('Arial','','9');

		}

		else

		{

			$pdf->Cell(60,4,'','TR','1','R');

		}

		$pdf->Cell(135,4,'Terms :Subject to Ludhiana Courts only','LR','0','');

		$pdf->Cell(60,4,'','R','1','');

		$pdf->Cell(135,8,'Our risk and responsibility ceases once the goods Dispatched','LR','0','');

		$pdf->Cell(60,8,'','R','1','');

		$pdf->Cell(135,4,'','LRB','0','');

		$pdf->SetFont('Arial','B','8');

		$pdf->Cell(60,4,'Auth. Signatory','RB','1','R');

	}

	else

	{

		$pdf->SetFont('Arial','B','15');

		$pdf->Cell(195,4,'Error in  Printing','','','C');

	}

	

	

	// Duplicate Copy With ITC removed 

	$pdf->addpage();

	$pdf->SetAutoPageBreak(on ,1); 

	$TotalPrice=0;

	$height=0;

	$Billingqry=mysqli_query($con2,"select cBillingCode , dBillingDate , iCompanyCode , iCompanySNo ,iPartyCode , iFirmSNo ,fVatFactor, fVatPer,iCGSTVal ,iSGSTVal ,iIGSTVal , cMiscTaxName,fMiscTaxPer, fSurcharge from {$tablePrefix}party_billing where iBillingID ='$BillingID'");

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

		$MiscTaxName=$Billingrow['cMiscTaxName'];

		$MiscTaxPer=$Billingrow['fMiscTaxPer'];

		$SurchargePer=$Billingrow['fSurcharge'];

		

		if ($CompanySNo>0)

		{

			$CmpDetailQry=mysqli_query($con2,"select cFirmName as Name ,cHSNCode as HSN , cGSTIn as GST,cFirmAddress as Address from  company_master_detail where iPartyID='$CompanyCode' and iSNo='$CompanySNo'");

		}

		else

		{

			$CmpDetailQry=mysqli_query($con2,"select cPartyName as Name ,cHSNCode as HSN , cGSTIn as GST, cAddress as Address from company_master where iPartyID='$CompanyCode'")or die(mysqli_error($con2));

		}

		if ($CmpDetailQry && mysqli_num_rows($CmpDetailQry)>0)

		{

			$CmpDetailrow=mysqli_fetch_array($CmpDetailQry);

			$CompanyName=$CmpDetailrow['Name'];

			$CompanyAddress=$CmpDetailrow['Address'];

		}



		if ($PartySNo>0)

		{

			$PaDetailQry=mysqli_query($con2,"select cFirmName as Name , cFirmAddress as Address , cGSTIn as GST, cStateCode as StateCode from party_master_detail where iPartyID='$PartyCode' and iSNo='$PartySNo'");

		}

		else

		{

			$PaDetailQry=mysqli_query($con2,"select cPartyName as Name , cAddress as Address , cGSTIn as GST, cStateCode as StateCode from party_master where  iPartyID='$PartyCode'");

		}

		if ($PaDetailQry && mysqli_num_rows($PaDetailQry)>0)

		{

			$PaDetailrow=mysqli_fetch_array($PaDetailQry);

			$PartyName=$PaDetailrow['Name'];

			$PartyAddress=$PaDetailrow['Address'];

			$PartyGST=$PaDetailrow['GST'];
			
			$PartyStateCode=$PaDetailrow['StateCode'];

		}

		

		$pdf->SetFont('Arial','B','12');

		$pdf->Ln(6);

		$pdf->Cell(180,5,'Duplicate Copy ','','1','R');

		$pdf->Ln(41);
		
		$pdf->Cell(50,5,'GST IN :'.$CompanyGST,'','0','L');
		
		$pdf->Cell(70,5,'SAC/HSN Code :'.$CompanyHSN,'','1','R');

		$pdf->Ln(5);
		

			//  1st Row.................

		$pdf->SetFont('Arial','B','13');

		$pdf->Cell(10,10,'M/S','LT','0','C');

		$pdf->Cell(115,10,$PartyName,'RT','','');

		$pdf->Cell(30,10,'Invoice No:','T',0,'R');

		$BillingCode=substr(strrchr($BillingCode,'/'),1);

		$pdf->Cell(40,10,$BillingCode,'TR',1,'L');

			//  2nd Row...................

		$PrevY=$pdf->GetY();

		$pdf->SetFont('Arial','B','11');

		$pdf->MultiCell(125,6,$PartyAddress,'LR','0','L');

		$pdf->SetFont('Arial','B','13');

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

		

		$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp from {$tablePrefix}party_billing_detail where iBillingID ='$BillingID' and iOrderID=0 and cItemType ='MISC'");

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

		

		$BDetailqry=mysqli_query($con2,"select * from {$tablePrefix}party_billing_detail where iBillingID ='$BillingID' and cItemType<>'MISC'");

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

			$pdf->SetFont('Arial','','11');

			$SNO=1;

			while ($BDetailrow=mysqli_fetch_array($BDetailqry))

			{

				$ItemType=$BDetailrow['cItemType'];

				$ItemCode=$BDetailrow['iItemCode'];

				$Rate=$BDetailrow['fRate'];

				$Disp=$BDetailrow['iNoPcsDisp'];

				$PartType=$BDetailrow['cPartType'];

				$Pcs=$BDetailrow['cPcs'];

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

							$Price=$MinValue * $Disp;

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

		$Totalheight=120;

		$Totalheight=$Totalheight - $height;

		$pdf->Cell(10,$Totalheight,'','LR','','');

		$pdf->Cell(122,$Totalheight,'','LR','','');

		$pdf->Cell(15,$Totalheight,'','R','','C');

		$pdf->Cell(23,$Totalheight,'','R','','C');

		$pdf->Cell(25,$Totalheight,'','R','1','C');

		

		// Round Off Total

		$pdf->SetFont('Arial','B','11');
		$TotalPrice=round($TotalPrice);

		$pdf->Cell(10,5,'','LR','','');

		$pdf->Cell(122,5,'','LR','','');

		$pdf->Cell(38,5,'TOTAL','R','','C');

		$pdf->Cell(25,5,number_format($TotalPrice,'2','.',''),'RTB','1','R');

	
if(date($BillingDate)>=date('2017-07-01')){
	
	if($CGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'CGST @ '.$CGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$CGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($SGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'SGST @ '.$SGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$SGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($IGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'IGST @ '.$IGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$IGSTPer/100),'2','.',''),'RTB','1','R');
	}
	
		
}else{
		// Vat Percent

		$pdf->Cell(10,5,'','LR','','');

		if($VatFactor==100)

		{

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'VAT @ '.$VatPer.' %','R','','C');

		}

		else

		{

			$pdf->Cell(160,5,'VAT @ '.$VatPer.' % on material consumed for this '.$VatFactor.'% of the work Rs'.number_format($TotalPrice*$VatFactor/100,'2','.','').'/=','LR','','R');

		}

		$VatAmt=(($TotalPrice*$VatFactor/100 * $VatPer)/100);

		$VatAmt=ceil($VatAmt);

		$pdf->Cell(25,5,number_format($VatAmt,'2','.',''),'RTB','1','R');

		

		// Surcharge

		$SurchargeAmt=(($VatAmt * $SurchargePer)/100);

		$FSurchargeAmt=floor($SurchargeAmt);

		$DSurchargeAmt= $SurchargeAmt - $FSurchargeAmt;

		if ($DSurchargeAmt>0.24)

			$SurchargeAmt=ceil($SurchargeAmt);

		else

			$SurchargeAmt=floor($SurchargeAmt);

		if($SurchargePer>0)

		{

			$pdf->Cell(10,5,'','LR','','');

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'Surcharge @ '.$SurchargePer.' %','R','','C');

			$pdf->Cell(25,5,number_format($SurchargeAmt,'2','.',''),'RTB','1','R');

		}
}
		$MiscAmt=(($TotalPrice * $MiscTaxPer)/100);

		$MiscAmt=ceil($MiscAmt);	

		$TotalWithtax= round($TotalPrice +  $VatAmt +  $MiscAmt + $SurchargeAmt + ceil($TotalPrice*$CGSTPer/100) + ceil($TotalPrice*$SGSTPer/100) + ceil($TotalPrice*$IGSTPer/100));

		if ($MiscTaxPer>0)

		{

			$pdf->Cell(10,5,'','LR','','');

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,$MiscTaxName.' @ '.$MiscTaxPer.' %','R','','C');

			$pdf->Cell(25,5,number_format($MiscAmt,'2','.',''),'RTB','1','R');

		}

		

		$pdf->SetFont('Arial','','10');

		$pdf->Cell(132,5,'NOTE : This copy does not entitle the holder to claim Input Tax Credit','TBLR','','');

		$pdf->Cell(15,5,'','B','','C');

		$pdf->SetFont('Arial','B','11');

		$pdf->Cell(23,5,'G.TOTAL','RB','','C');

		$pdf->Cell(25,5,number_format(round($TotalWithtax),'2','.',''),'RTB','1','R');



		// Amount in words.......

		

		$invamtwords=amountinwords($TotalWithtax);

		$invamtwords=$invamtwords." Only";

		$pdf->SetFont('Arial','B','11');

		$pdf->Cell(195,15,'Amount in words :'.$invamtwords,'LRB','1','');

		

		$pdf->SetFont('Arial','B','9');

		$pdf->Cell(10,4,'','LT','0','');

		$pdf->SetFont('Arial','','9');

		$pdf->Cell(125,4,'','TR','0','');

		if ($State=="yes")

		{

			$pdf->Cell(10,4,'FOR ','T','','R');

			$pdf->SetFont('Arial','B','9');

			if (trim($CompanyName)=="PARKASH INDUSTRIAL CORPORATION")

				$CompanyName="PARKASH INDUSTRIAL CORP.";

			$pdf->Cell(50,4,strtoupper($CompanyName).'   ','TR','1','L');

			$pdf->SetFont('Arial','','9');

		}

		else

		{

			$pdf->Cell(60,4,'','TR','1','R');

		}

		$pdf->Cell(135,4,'Terms :Subject to Ludhiana Courts only','LR','0','');

		$pdf->Cell(60,4,'','R','1','');

		$pdf->Cell(135,8,'Our risk and responsibility ceases once the goods Dispatched','LR','0','');

		$pdf->Cell(60,8,'','R','1','');

		$pdf->Cell(135,4,'','LRB','0','');

		$pdf->SetFont('Arial','B','8');

		$pdf->Cell(60,4,'Auth. Signatory','RB','1','R');

	}

	else

	{

		$pdf->SetFont('Arial','B','15');

		$pdf->Cell(195,4,'Error in  Printing','','','C');

	}

	

	// Office Copy with ITC removed

	$pdf->addpage();

	$pdf->SetAutoPageBreak(on ,1); 

	$TotalPrice=0;

	$height=0;

	$Billingqry=mysqli_query($con2,"select cBillingCode , dBillingDate , iCompanyCode , iCompanySNo ,iPartyCode , iFirmSNo ,fVatFactor, fVatPer,iCGSTVal ,iSGSTVal ,iIGSTVal , cMiscTaxName,fMiscTaxPer, fSurcharge  from {$tablePrefix}party_billing where iBillingID ='$BillingID'");

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

		$MiscTaxName=$Billingrow['cMiscTaxName'];

		$MiscTaxPer=$Billingrow['fMiscTaxPer'];

		$SurchargePer=$Billingrow['fSurcharge'];



		if ($CompanySNo>0)

		{

			$CmpDetailQry=mysqli_query($con2,"select cFirmName as Name ,cHSNCode as HSN , cGSTIn as GST,cFirmAddress as Address from  company_master_detail where iPartyID='$CompanyCode' and iSNo='$CompanySNo'");

		}

		else

		{

			$CmpDetailQry=mysqli_query($con2,"select cPartyName as Name ,cHSNCode as HSN , cGSTIn as GST, cAddress as Address from company_master where iPartyID='$CompanyCode'")or die(mysqli_error($con2));

		}

		if ($CmpDetailQry && mysqli_num_rows($CmpDetailQry)>0)

		{

			$CmpDetailrow=mysqli_fetch_array($CmpDetailQry);

			$CompanyName=$CmpDetailrow['Name'];

			$CompanyAddress=$CmpDetailrow['Address'];

		}



		if ($PartySNo>0)

		{

			$PaDetailQry=mysqli_query($con2,"select cFirmName as Name , cFirmAddress as Address , cGSTIn as GST, cStateCode as StateCode from party_master_detail where iPartyID='$PartyCode' and iSNo='$PartySNo'");

		}

		else

		{

			$PaDetailQry=mysqli_query($con2,"select cPartyName as Name , cAddress as Address , cGSTIn as GST, cStateCode as StateCode from party_master where  iPartyID='$PartyCode'");

		}

		if ($PaDetailQry && mysqli_num_rows($PaDetailQry)>0)

		{

			$PaDetailrow=mysqli_fetch_array($PaDetailQry);

			$PartyName=$PaDetailrow['Name'];

			$PartyAddress=$PaDetailrow['Address'];

			$PartyGST=$PaDetailrow['GST'];
			
			$PartyStateCode=$PaDetailrow['StateCode'];

		}

		

		$pdf->SetFont('Arial','B','12');

		$pdf->Ln(6);

		$pdf->Cell(180,5,'Office Copy ','','1','R');

		$pdf->Ln(41);
		
		$pdf->Cell(50,5,'GST IN :'.$CompanyGST,'','0','L');
		
		$pdf->Cell(70,5,'SAC/HSN Code :'.$CompanyHSN,'','1','R');

		$pdf->Ln(5);

			//  1st Row.................

		$pdf->SetFont('Arial','B','13');

		$pdf->Cell(10,10,'M/S','LT','0','C');

		$pdf->Cell(115,10,$PartyName,'RT','','');

		$pdf->Cell(30,10,'Invoice No:','T',0,'R');

		$BillingCode=substr(strrchr($BillingCode,'/'),1);

		$pdf->Cell(40,10,$BillingCode,'TR',1,'L');



			//  2nd Row...................

		//$pdf->SetFont('Arial','','8');

		$PrevY=$pdf->GetY();

		$pdf->SetFont('Arial','B','11');

		$pdf->MultiCell(125,6,$PartyAddress,'LR','0','L');

		$NewY=$pdf->GetY();

		$h=($NewY - $PrevY);

		$pdf->SetY($pdf->GetY()- $h);

		$pdf->SetX(135);

		$pdf->SetFont('Arial','B','13');

		$pdf->Cell(20,$h,'Dated :','',0,'R');

		$pdf->Cell(50,$h,$InvDate,'R',1,'L');

					

			// 3rd Row....................	

		$pdf->Cell(125,8,'GST IN:'.$PartyGST.'  State Code:'.$PartyStateCode,'BLR','','L');

		//$pdf->Cell(60,5,'TIN NO :'.$PartyTINNO,'RL','1','');

		$pdf->Cell(20,8,'','B',0,'R');

		$pdf->Cell(50,8,'','BR',1,'L');

		

		$Miscqry=mysqli_query($con2,"select cMiscItemName, fRate, iNoPcsDisp from {$tablePrefix}party_billing_detail where iBillingID ='$BillingID' and iOrderID=0 and cItemType ='MISC'");

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

		

		$BDetailqry=mysqli_query($con2,"select * from {$tablePrefix}party_billing_detail where iBillingID ='$BillingID' and cItemType<>'MISC'");

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

			$pdf->SetFont('Arial','','11');

			$SNO=1;

			while ($BDetailrow=mysqli_fetch_array($BDetailqry))

			{

				$ItemType=$BDetailrow['cItemType'];

				$ItemCode=$BDetailrow['iItemCode'];

				$Rate=$BDetailrow['fRate'];

				$Disp=$BDetailrow['iNoPcsDisp'];

				$PartType=$BDetailrow['cPartType'];

				$Pcs=$BDetailrow['cPcs'];

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

					if ($PartType=="PowerPress")

						$Itemqry=mysqli_query($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType) as cName , iTeeth from pinion_master where iId='$ItemCode'");	

					else

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

		$Totalheight=120;

		$Totalheight=$Totalheight - $height;

		$pdf->Cell(10,$Totalheight,'','LR','','');

		$pdf->Cell(122,$Totalheight,'','LR','','');

		$pdf->Cell(15,$Totalheight,'','R','','C');

		$pdf->Cell(23,$Totalheight,'','R','','C');

		$pdf->Cell(25,$Totalheight,'','R','1','C');

		

		// Round Off Total

		$pdf->SetFont('Arial','B','11');
		$TotalPrice=round($TotalPrice);

		$pdf->Cell(10,5,'','LR','','');

		$pdf->Cell(122,5,'','LR','','');

		$pdf->Cell(38,5,'TOTAL','R','','C');

		$pdf->Cell(25,5,number_format($TotalPrice,'2','.',''),'RTB','1','R');

if(date($BillingDate)>=date('2017-07-01')){
	
	if($CGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'CGST @ '.$CGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$CGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($SGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'SGST @ '.$SGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$SGSTPer/100),'2','.',''),'RTB','1','R');
	}
	if($IGSTPer > 0){

			$pdf->Cell(10,5,'','LR','','');
		
			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'IGST @ '.$IGSTPer.' %','R','','C');

		$pdf->Cell(25,5,number_format(ceil($TotalPrice*$IGSTPer/100),'2','.',''),'RTB','1','R');
	}
	
		
}else{

		// Vat Percent

		$pdf->Cell(10,5,'','LR','','');

		if($VatFactor==100)

		{

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'VAT @ '.$VatPer.' %','R','','C');

		}

		else

		{

			$pdf->Cell(160,5,'VAT @ '.$VatPer.' % on material consumed for this '.$VatFactor.'% of the work Rs'.number_format($TotalPrice*$VatFactor/100,'2','.','').'/=','LR','','R');

		}

		$VatAmt=(($TotalPrice*$VatFactor/100 * $VatPer)/100);

		$VatAmt=ceil($VatAmt);

		$pdf->Cell(25,5,number_format($VatAmt,'2','.',''),'RTB','1','R');

		

		// Surcharge

		$SurchargeAmt=(($VatAmt * $SurchargePer)/100);

		$FSurchargeAmt=floor($SurchargeAmt);

		$DSurchargeAmt= $SurchargeAmt - $FSurchargeAmt;

		if ($DSurchargeAmt>0.24)

			$SurchargeAmt=ceil($SurchargeAmt);

		else

			$SurchargeAmt=floor($SurchargeAmt);

		if($SurchargePer>0)

		{

			$pdf->Cell(10,5,'','LR','','');

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,'Surcharge @ '.$SurchargePer.' %','R','','C');

			$pdf->Cell(25,5,number_format($SurchargeAmt,'2','.',''),'RTB','1','R');

		}		
}
		$MiscAmt=(($TotalPrice * $MiscTaxPer)/100);

		$MiscAmt=ceil($MiscAmt);	

		$TotalWithtax= round($TotalPrice +  $VatAmt +  $MiscAmt + $SurchargeAmt + ceil($TotalPrice*$CGSTPer/100) + ceil($TotalPrice*$SGSTPer/100) + ceil($TotalPrice*$IGSTPer/100));

		if ($MiscTaxPer>0)

		{

			$pdf->Cell(10,5,'','LR','','');

			$pdf->Cell(122,5,'','LR','','');

			$pdf->Cell(38,5,$MiscTaxName.' @ '.$MiscTaxPer.' %','R','','C');

			$pdf->Cell(25,5,number_format($MiscAmt,'2','.',''),'RTB','1','R');

		}

		

		$pdf->SetFont('Arial','','10');

		$pdf->Cell(132,5,'NOTE : This copy does not entitle the holder to claim Input Tax Credit','TBLR','','');

		$pdf->Cell(15,5,'','B','','C');

		$pdf->SetFont('Arial','B','11');

		$pdf->Cell(23,5,'G.TOTAL','RB','','C');

		$pdf->Cell(25,5,number_format(round($TotalWithtax),'2','.',''),'RTB','1','R');

		

		// Amount in words.......

		

		$invamtwords=amountinwords($TotalWithtax);

		$invamtwords=$invamtwords." Only";

		$pdf->SetFont('Arial','B','11');

		$pdf->Cell(195,15,'Amount in words :'.$invamtwords,'LRB','1','');

		

		$pdf->SetFont('Arial','B','9');

		$pdf->Cell(10,4,'','LT','0','');

		$pdf->SetFont('Arial','','9');

		$pdf->Cell(125,4,'','TR','0','');

		if ($State=="yes")

		{

			$pdf->Cell(10,4,'FOR ','T','','R');

			$pdf->SetFont('Arial','B','9');

			if (trim($CompanyName)=="PARKASH INDUSTRIAL CORPORATION")

				$CompanyName="PARKASH INDUSTRIAL CORP.";

			$pdf->Cell(50,4,strtoupper($CompanyName).'   ','TR','1','L');

			$pdf->SetFont('Arial','','9');

		}

		else

		{

			$pdf->Cell(60,4,'','TR','1','R');

		}

		$pdf->Cell(135,4,'Terms :Subject to Ludhiana Courts only','LR','0','');

		$pdf->Cell(60,4,'','R','1','');

		$pdf->Cell(135,8,'Our risk and responsibility ceases once the goods Dispatched','LR','0','');

		$pdf->Cell(60,8,'','R','1','');

		$pdf->Cell(135,4,'','LRB','0','');

		$pdf->SetFont('Arial','B','8');

		$pdf->Cell(60,4,'Auth. Signatory','RB','1','R');



	}

	else

	{

		$pdf->SetFont('Arial','B','15');

		$pdf->Cell(195,4,'Error in  Printing','','','C');

	}

	

	$pdf->Output();

?>