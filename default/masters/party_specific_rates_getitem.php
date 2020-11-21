<?php
	require_once("../../config.php");
    $PartyID=$_GET['PartyID'];
	$PlainGear="";
	$HelicalGear="";
	$PlainShaftPinion="";
	$HelicalShaftPinion="";
	$ChainGear="";
	$BevelGear="";
	$SpecialItem="";
	//echo "select distinct(cItemName) from party_rates_master where iPartyID='$PartyID'";
	$result=mysqli_query ($con2,"select distinct(cItemName) from party_rates_master where iPartyID='$PartyID'");
	if ($result && mysqli_num_rows($result)>0)
	{
		//echo "hello";
		while ($row=mysqli_fetch_array($result))
		{
			$itemqry=mysqli_query ($con2,"select * from party_rates_master where iPartyID='$PartyID' and cItemName=\"".$row['cItemName']."\"");
			if ($itemqry && mysqli_num_rows($itemqry)>0)
			{
				while ($itemrow=mysqli_fetch_array($itemqry))
				{
					if ($row['cItemName']=="Gear")
					{
						if ($itemrow['cItemType']=="Plain")
						{
							if ($PlainGear=="")
							{
								$PlainGear="$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fFaceFrom]~ArrayItem~$itemrow[fFaceTo]~ArrayItem~$itemrow[cFaceType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
							}
							else
							{
								$PlainGear=$PlainGear."~Array~$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fFaceFrom]~ArrayItem~$itemrow[fFaceTo]~ArrayItem~$itemrow[cFaceType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
							}						
						}
						else 
						{
							if ($HelicalGear=="")
							{
								$HelicalGear="$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fFaceFrom]~ArrayItem~$itemrow[fFaceTo]~ArrayItem~$itemrow[cFaceType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
							}
							else
							{
								$HelicalGear=$HelicalGear."~Array~$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fFaceFrom]~ArrayItem~$itemrow[fFaceTo]~ArrayItem~$itemrow[cFaceType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
							}	
						}
					}
					else if ($row['cItemName']=="Shaft Pinion")
					{
						if ($itemrow['cItemType']=="Plain")
						{
							if ($PlainShaftPinion=="")
							{
								$PlainShaftPinion="$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fFaceFrom]~ArrayItem~$itemrow[fFaceTo]~ArrayItem~$itemrow[cFaceType]~ArrayItem~$itemrow[iTeeth]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
							}
							else
							{
								$PlainShaftPinion=$PlainShaftPinion."~Array~$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fFaceFrom]~ArrayItem~$itemrow[fFaceTo]~ArrayItem~$itemrow[cFaceType]~ArrayItem~$itemrow[iTeeth]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
							}
						}
						else 
						{
							if ($HelicalShaftPinion=="")
							{
								$HelicalShaftPinion="$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fFaceFrom]~ArrayItem~$itemrow[fFaceTo]~ArrayItem~$itemrow[cFaceType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
							}
							else
							{
								$HelicalShaftPinion=$HelicalShaftPinion."~Array~$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fFaceFrom]~ArrayItem~$itemrow[fFaceTo]~ArrayItem~$itemrow[cFaceType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
							}	
						}
					}
					else if ($row['cItemName']=="Chain Gear")
					{
						if ($ChainGear=="")
						{
							$ChainGear="$itemrow[fPitchValue]~ArrayItem~$itemrow[cPitchType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
						}
						else
						{
							$ChainGear=$ChainGear."~Array~$itemrow[fPitchValue]~ArrayItem~$itemrow[cPitchType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
						}	
					}
					else if ($row['cItemName']=="Bevel Gear")
					{
						if ($BevelGear=="")
						{
							$BevelGear="$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[iTeeth]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
						}
						else
						{
							$BevelGear=$BevelGear."~Array~$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[iTeeth]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
						}
					}
				}
			}
		}
	}
	$SpecialItemqry=mysqli_query ($con2,"select * from special_item_rate_master where iPartyID =$PartyID");
	if ($SpecialItemqry && mysqli_num_rows($SpecialItemqry)>0)
	{
		while ($SpecialItemrow=mysqli_fetch_array($SpecialItemqry))
		{
			if ($SpecialItem=="")
			{
				$SpecialItem="$SpecialItemrow[iId]~ArrayItem~$SpecialItemrow[cItemName]~ArrayItem~$SpecialItemrow[fRate]~ArrayItem~$SpecialItemrow[cMeasurement]";
			}
			else
			{
				$SpecialItem=$SpecialItem."~Array~$SpecialItemrow[iId]~ArrayItem~$SpecialItemrow[cItemName]~ArrayItem~$SpecialItemrow[fRate]~ArrayItem~$SpecialItemrow[cMeasurement]";
			}		
		}
	}
			$ItemData=$PlainGear."~ItemData~".$HelicalGear."~ItemData~".$PlainShaftPinion."~ItemData~".$HelicalShaftPinion."~ItemData~".$ChainGear."~ItemData~".$BevelGear."~ItemData~".$SpecialItem;
	print $ItemData;
	
?>