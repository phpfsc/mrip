<?php
	require_once("../../config.php");
	
	 $PartyId=$_GET['PartyId'];
	 $ToDate=$_GET['ToDate'];
	
    
	$Price=0;
	$ItemArray="";
	$ItemData="";
	$Billed="";
	$ChallanData="";
	$tablePrefixArray=array();
	
		
	if($PartyId==41)
	{
		
		$result=mysqli_query($con2,"select iOrderID , dOrderDate, cOrderCode,fMinValue, tablePrefix from (select iOrderID , dOrderDate, cOrderCode,fMinValue, '' as tablePrefix from party_order where iPartyCode ='".$PartyId."' and  dOrderDate <='$ToDate') as tbl order by tbl.dOrderDate") or die(mysqli_error($con2));
	}
	else
	{
		
		
		$result=mysqli_query($con2,"select iOrderID , dOrderDate, cOrderCode,fMinValue, tablePrefix from (select iOrderID , dOrderDate, cOrderCode,fMinValue, '' as tablePrefix from party_order where iPartyCode ='".$PartyId."'  and dOrderDate <='$ToDate') as tbl order by tbl.dOrderDate") or die(mysqli_error($con2));	
	}
	
	
	if ($result && mysqli_num_rows($result)>0)
	{
		while ($row=mysqli_fetch_array($result))	
		{
			
		    $OrderID=$row['iOrderID'];
			$dDate=explode("-",$row['dOrderDate']);
			$OrderDate="$dDate[2]/$dDate[1]/$dDate[0]";
			$OrderCode=$row['cOrderCode'];
			$MinValue=$row['fMinValue'];
			
			
				// Getting Item , Item Code , No. of Pcs Dispatch from Order 
				// where Pcs Dispatch is greater than Zero 
			$result1=mysqli_query($con2,"select cItemRemarks , cItemType ,fRate, iItemCode , iNoPcsRec , iNoPcsDisp , cPartType , party_order.iOrderID, party_order_detail.cPcs , party_order_detail.cCollar from party_order join party_order_detail on party_order.iOrderID= party_order_detail.iOrderID where bDeleted <>1 and iNoPcsDisp>0  and party_order.iOrderID ='".$row['iOrderID']."'")or die(mysqli_error($con2));
			$Data="";
			if ($result1)
			{
				if (mysqli_num_rows($result1)>0)
				{
					$ItemData="$OrderCode~Caption~$OrderID~Caption~$OrderDate~Caption~$MinValue~Caption~$tablePrefix~Heading~";
					while ($row1=mysqli_fetch_array($result1))
					{
						
					    $ItemType=$row1['cItemType'];
						$ItemCode=$row1['iItemCode'];
						$TotalQtyRec=$row1['iNoPcsRec'];
					    $TotalDisp=$row1['iNoPcsDisp'];
						$PartType=$row1['cPartType'];
						$Pcs=$row1['cPcs'];
						$Collar=$row1['cCollar'];
						$Rate=$row1['fRate'];
						$ItemRemarks=$row1['cItemRemarks'];
						//echo "select sum(iNoPcsReturn) as PcsReturn  from {$tablePrefix}party_challan join {$tablePrefix}party_challan_detail on {$tablePrefix}party_challan.iChallanID = {$tablePrefix}party_challan_detail.iChallanID where cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID' and cPartType='$PartType' and cPcs='$Pcs' group by cItemType, iItemCode,cPartType,cPcs";
						//.......... Challan Qty Return to find out how many items are returned
						$Challanqry1=mysqli_query($con2,"select sum(iNoPcsReturn) as PcsReturn  from party_challan join party_challan_detail on party_challan.iChallanID = party_challan_detail.iChallanID where cItemType='$ItemType' and iItemCode='$ItemCode' and iOrderID ='$OrderID' and cPartType='$PartType' and cPcs='$Pcs' group by cItemType, iItemCode,cPartType,cPcs");		
						if ($Challanqry1 && mysqli_num_rows($Challanqry1)>0)
						{
							$Challanrow1=mysqli_fetch_array($Challanqry1);
						    $Return=$Challanrow1['PcsReturn'];
						}
						else
						{
							$Return=0;
						}
						$TotalDisp=$TotalDisp - $Return;
						$BilledQty1=0;
						// Billed Qty .......
						$Billingqry1=mysqli_query($con2,"select sum(iNoPcsDisp) as billed , dBillingDate from k_party_billing join k_party_billing_detail on k_party_billing.iBillingID = k_party_billing_detail.iBillingID  where iOrderID ='$OrderID' and cItemType='$ItemType' and iItemCode ='$ItemCode' and cPartType='$PartType' and cPcs='$Pcs' group by k_party_billing.iBillingID");
						if ($Billingqry1 && mysqli_num_rows($Billingqry1)>0)
						{
							while ($Billingrow1=mysqli_fetch_array($Billingqry1))
							{
								 $BilledQty1=$BilledQty1 + $Billingrow1['billed'];
							}
							
						}
						else
						{
							$BilledQty1=0;
						}
						$Billingqry=mysqli_query($con2,"select sum(iNoPcsDisp) as billed , dBillingDate from party_billing join party_billing_detail on party_billing.iBillingID =party_billing_detail.iBillingID  where iOrderID ='$OrderID' and cItemType='$ItemType' and iItemCode ='$ItemCode' and cPartType='$PartType' and cPcs='$Pcs' group by party_billing.iBillingID");
						if ($Billingqry && mysqli_num_rows($Billingqry)>0)
						{
							$BilledQty=0;
							while ($Billingrow=mysqli_fetch_array($Billingqry))
							{
								$BilledQty=$BilledQty + $Billingrow['billed'];
							}
							
						}
						else
						{
							$BilledQty=0;
						}
						$BilledQty=$BilledQty + $BilledQty1;
					     $BilledQty;
					     $RemainingQty=$TotalDisp - $BilledQty ; 
						
						if ($RemainingQty>0)
						{
							
							if ($ItemType=="Gear")
							{
								$Itemqry=mysqli_query($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType,' (',fDMValue , cType,')' ) as cName,iTeeth , cItemType from gear_master where iId ='$ItemCode'");
								if ($Itemqry && mysqli_num_rows($Itemqry)>0)
								{	
									$Itemrow=mysqli_fetch_array($Itemqry);
									$ItemName=$Itemrow['cName'];
								}
								if ($Pcs=="PT")
									$Price=$RemainingQty*$Itemrow['iTeeth']* $Rate; 
								else
									$Price=$RemainingQty*$Rate; 
							}
							else if ($ItemType=="Pinion")
							{
								$Itemqry=mysqli_query($con2,"select iTeeth , cItemType ,fDia ,cDiaType , fFace ,cFaceType , iTeeth , cItemType, fDMValue , cType  from pinion_master where iId='$ItemCode'");	
								if ($Itemqry && mysqli_num_rows($Itemqry)>0)
								{	
									$Itemrow=mysqli_fetch_array($Itemqry);
									//$ItemName=$Itemrow['cName'];
									$ItemName=$Itemrow['iTeeth'].' teeth '.$Itemrow['cItemType'].' dia '.$Itemrow['fDia'].' '.$Itemrow['cDiaType'].' face '.$Itemrow['fFace'].' ('.$Itemrow['fDMValue'].$Itemrow['cType'].')';
									if ($Collar!="")
									{
										$ItemName=$ItemName." + ".$Collar;	
									}
									$ItemName=$ItemName.' '.$Itemrow['cFaceType'];
								}
								
								if ($Pcs=="PT")
									$Price=$RemainingQty*$Itemrow['iTeeth']* $Rate; 
								else
									$Price=$RemainingQty* $Rate; 
								if ($PartType=="PowerPress")
								{
									$IPrice=$Price/$RemainingQty;
									if ($IPrice<$MinValue)
									{
										$Price=$MinValue*$RemainingQty;
									}
								}
							}
							else if ($ItemType=="Bevel Pinion")
							{
								$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType ,'(', fDMValue , cType,')') as cName , iTeeth , '' as cItemType from bevel_pinion_master where iId='$ItemCode'");			
								if ($Itemqry && mysqli_num_rows($Itemqry)>0)
								{	
									$Itemrow=mysqli_fetch_array($Itemqry);
									$ItemName=$Itemrow['cName'];
								}
								if ($Pcs=="PT")
									$Price=$RemainingQty * $Rate * $Itemrow['iTeeth'];
								else
									$Price=$RemainingQty * $Rate;
							}
							else if ($ItemType=="Bevel Gear")
							{
								$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType,'(', fDMValue , cType,')') as cName , iTeeth , '' as cItemType from bevel_gear_master where iId='$ItemCode'");			
								if ($Itemqry && mysqli_num_rows($Itemqry)>0)
								{	
									$Itemrow=mysqli_fetch_array($Itemqry);
									$ItemName=$Itemrow['cName'];
								}
								if ($Pcs=="PT")
									$Price=$RemainingQty * $Rate * $Itemrow['iTeeth'];
								else
									$Price=$RemainingQty * $Rate;
							}
							else if ($ItemType=="Shaft Pinion")
							{
								$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth ',cItemType ,' dia ',fDia ,' ',cDiaType , ' face ',fFace ,' ', cFaceType, '(', fDMValue , cType,')') as cName, iTeeth, cItemType from shaft_pinion_master where iId='$ItemCode'");
								if ($Itemqry && mysqli_num_rows($Itemqry)>0)
								{	
									$Itemrow=mysqli_fetch_array($Itemqry);
									$ItemName=$Itemrow['cName'];
								}
								if ($Pcs=="PCS")
									$Price=$RemainingQty * $Rate;
								else
									$Price=$RemainingQty * $Rate*$Itemrow['iTeeth'];
							}
							else if ($ItemType=="Chain Wheel")
							{
								$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia , ' ',cDiaType , ' pitch ', fPitch ,' ', cPitchType,' (',cItemType,')' ) as cName, iTeeth , cItemType from chain_gear_master where iId='$ItemCode'");
								if ($Itemqry && mysqli_num_rows($Itemqry)>0)
								{	
									$Itemrow=mysqli_fetch_array($Itemqry);
									$ItemName=$Itemrow['cName'];
								}
								if ($Pcs=="PT")
									$Price=$RemainingQty * $Itemrow['iTeeth']*$Rate;
								else
									$Price=$RemainingQty * $Rate;
							}
							else if ($ItemType=="Worm Gear")
							{
								$Itemqry=mysqli_query($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType,'(', fDMValue , cType,')') as cName,iTeeth , '' as cItemType from worm_gear_master where iId='$ItemCode'");			
								if ($Itemqry && mysqli_num_rows($Itemqry)>0)
								{	
									$Itemrow=mysqli_fetch_array($Itemqry);
									$ItemName=$Itemrow['cName'];
								}
								if ($Pcs=="PCS")
									$Price=$RemainingQty * $Rate ;
								else
									$Price=$RemainingQty * $Rate * $Itemrow['iTeeth'];
							}
							
							if (empty($Data))
							{
								
								$Data="$ItemType~ArrayItem~$ItemCode~ArrayItem~$ItemName~ArrayItem~$Itemrow[iTeeth]~ArrayItem~$TotalQtyRec~ArrayItem~$TotalDisp~ArrayItem~$PartType~ArrayItem~$BilledQty~ArrayItem~$RemainingQty~ArrayItem~$Rate~ArrayItem~$Price~ArrayItem~$Itemrow[cItemType]~ArrayItem~$Pcs~ArrayItem~$Collar~ArrayItem~$ItemRemarks";	
							}
							else
							{
							    
								$Data=$Data."~Array~$ItemType~ArrayItem~$ItemCode~ArrayItem~$ItemName~ArrayItem~$Itemrow[iTeeth]~ArrayItem~$TotalQtyRec~ArrayItem~$TotalDisp~ArrayItem~$PartType~ArrayItem~$BilledQty~ArrayItem~$RemainingQty~ArrayItem~$Rate~ArrayItem~$Price~ArrayItem~$Itemrow[cItemType]~ArrayItem~$Pcs~ArrayItem~$Collar~ArrayItem~$ItemRemarks";
							}
							
						}
						
						$BilledQty1=0;
						$RemainingQty=0; 
						$BilledQty=0;
						$Rate=0;
						$TotalDisp=0;
						
						
					}
					
					if (!empty($Data))
					{
						
						if ($ItemArray=="")
						{
							$ItemArray=$ItemData.$Data;
						}
						else
						{
							$ItemArray=$ItemArray."~ItemData~".$ItemData.$Data;
						}
					}
					$Data="";
				}
			}
			array_push($tablePrefixArray,$tablePrefix);
		}
	}
	$_SESSION['tablePrefix']=$tablePrefixArray;
	//$_SESSION['tablePrefix']="hello";
	print_r($ItemArray);
?>