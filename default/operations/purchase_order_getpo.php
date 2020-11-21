<?php
    require_once("../../config.php");
	$PartyID=$_GET['PartyId'];
	
	$ItemData="";
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
	$Price=0;
	$result=mysqli_query ($con2,"select distinct(party_order.iOrderID), cOrderCode,dOrderDate , fMinValue from party_order join party_order_detail on party_order.iOrderID=party_order_detail.iOrderID where iPartyCode='$PartyID'  order by party_order.iOrderID desc LIMIT 0,5");
	if ($result)
	{
		if (mysqli_num_rows($result)>0)
		{
			while ($row=mysqli_fetch_array($result))
			{
				$Order="$row[cOrderCode]~Caption~$row[dOrderDate]~Heading~";
				$MinValue=$row['fMinValue'];

				$result1=mysqli_query ($con2,"select cItemType , iItemCode , iNoPcsRec ,iNoPcsDisp ,fRate ,cPartType , cPcs , cCollar from party_order_detail where iOrderID=\"".$row['iOrderID']."\" and bDeleted<>'1'") ;
				if ($result1 && mysqli_num_rows($result1)>0)
				{
					while ($row1=mysqli_fetch_array($result1))
					{
						$ItemType=$row1['cItemType'];
						$ItemCode=$row1['iItemCode'];
						$NoOfPcs=$row1['iNoPcsRec'];
						$NoOfDisp=$row1['iNoPcsDisp'];
						$PartType=$row1['cPartType'];
						$Pcs=$row1['cPcs'];
						$Collar=$row1['cCollar'];
						
						$Balance=$NoOfPcs - $NoOfDisp;
						
						$Disp=$NoOfPcs;
						$Rate=$row1['fRate'];
						if ($ItemType=="Gear")
						{
							$Itemqry=mysqli_query ($con2,"select * from gear_master where iId ='$ItemCode'");
							if ($Itemqry && mysqli_num_rows($Itemqry)>0)
							{	
								$Itemrow=mysqli_fetch_array($Itemqry);
								$ItemName=$ItemType." ".$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
							}
							if ($Pcs=="PT")
								$Price=$Disp*$Itemrow['iTeeth']* $Rate; 
							else
								$Price=$Disp*$Rate; 
						}
						else if ($ItemType=="Pinion")
						{
							$Itemqry=mysqli_query ($con2,"select * from pinion_master where iId='$ItemCode'");	
							if ($Itemqry && mysqli_num_rows($Itemqry)>0)
							{	
								$Itemrow=mysqli_fetch_array($Itemqry);
								$ItemName=$ItemType." ".$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
							}
							if ($Pcs=="PT")
								$Price=$Disp*$Itemrow['iTeeth']* $Rate; 
							else
								$Price=$Disp* $Rate; 
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
							$Itemqry=mysqli_query ($con2,"select * from bevel_pinion_master where iId='$ItemCode'");			
							if ($Itemqry && mysqli_num_rows($Itemqry)>0)
							{	
								$Itemrow=mysqli_fetch_array($Itemqry);
								$ItemName=$ItemType." ".$Itemrow['iTeeth']." teeth (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
							}
							if ($Pcs=="PT")
								$Price=$Disp * $Rate * $Itemrow['iTeeth'];
							else
								$Price=$Disp * $Rate;
						}
						else if ($ItemType=="Bevel Gear")
						{
							$Itemqry=mysqli_query ($con2,"select * from bevel_gear_master where iId='$ItemCode'");			
							if ($Itemqry && mysqli_num_rows($Itemqry)>0)
							{	
								$Itemrow=mysqli_fetch_array($Itemqry);
								$ItemName=$ItemType." ".$Itemrow['iTeeth']." teeth  (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
							}
							if ($Pcs=="PT")
								$Price=$Disp * $Rate * $Itemrow['iTeeth'];
							else
								$Price=$Disp * $Rate;
						}
						else if ($ItemType=="Shaft Pinion")
						{
							$Itemqry=mysqli_query ($con2,"select * from shaft_pinion_master where iId='$ItemCode'");
							if ($Itemqry && mysqli_num_rows($Itemqry)>0)
							{	
								$Itemrow=mysqli_fetch_array($Itemqry);
								$ItemName=$ItemType." ".$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
							}
							if ($Pcs=="PCS")
								$Price=$Disp * $Rate;
							else
								$Price=$Disp * $Rate * $Itemrow['iTeeth'];
						}
						else if ($ItemType=="Chain Wheel")
						{
							$Itemqry=mysqli_query ($con2,"select * from chain_gear_master where iId='$ItemCode'");
							if ($Itemqry && mysqli_num_rows($Itemqry)>0)
							{	
								$Itemrow=mysqli_fetch_array($Itemqry);
								$ItemName=$ItemType." ".$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." pitch ".$Itemrow['fPitch']."&nbsp;".$Itemrow['cPitchType'];
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
						else if ($ItemType=="Worm Gear")
						{
							$Itemqry=mysqli_query ($con2,"select * from worm_gear_master where iId='$ItemCode'");			
							if ($Itemqry && mysqli_num_rows($Itemqry)>0)
							{	
								$Itemrow=mysqli_fetch_array($Itemqry);
								$ItemName=$ItemType." ".$Itemrow['iTeeth']." teeth  (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
							}
							if ($Pcs=="PCS")
								$Price=$Disp * $Rate;
							else
								$Price=$Disp * $Rate*$Itemrow['iTeeth'];
						}

						$Price=round($Price,'2');

						if ($ItemData=="")
						{
							$ItemData="$ItemName~ArrayItem~$NoOfPcs~ArrayItem~$Rate~ArrayItem~$Price~ArrayItem~$NoOfDisp~ArrayItem~$Balance~ArrayItem~$Pcs~ArrayItem~$Collar";		
						}
						else
						{
							$ItemData=$ItemData."~Array~$ItemName~ArrayItem~$NoOfPcs~ArrayItem~$Rate~ArrayItem~$Price~ArrayItem~$NoOfDisp~ArrayItem~$Balance~ArrayItem~$Pcs~ArrayItem~$Collar";		
						}
					}
				}
				if ($ItemArray=="")
				{
					$ItemArray=$Order.$ItemData;
				}
				else
				{
					$ItemArray=$ItemArray."~ItemArray~".$Order.$ItemData;
				}
				$ItemData="";
			}
		}
		else
		{
			$ItemArray="";
		}
	}
	else
	{
		$ItemArray="Error";
	}

	print $ItemArray;
?>