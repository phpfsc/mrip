<?php
	session_start();	
	require ('../general/dbconnect.php');
	$PartyID=$_GET['PartyID'];
	$Gear="";
	$Pinion="";
	$SpecialItem="";
	$result=mysql_query ("select distinct(cItemName) from pp_party_rates_master where iPartyID=$PartyID");
	if ($result && mysql_num_rows($result)>0)
	{
		while ($row=mysql_fetch_array($result))
		{
			$itemqry=mysql_query ("select * from pp_party_rates_master where iPartyID=$PartyID and cItemName=\"".$row['cItemName']."\"");
			if ($itemqry && mysql_num_rows($itemqry)>0)
			{
				while ($itemrow=mysql_fetch_array($itemqry))
				{
					if ($row['cItemName']=="Gear")
					{
						if ($Gear=="")
						{
							$Gear="$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fDiaFrom]~ArrayItem~$itemrow[fDiaTo]~ArrayItem~$itemrow[cDiaType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
						}
						else
						{
							$Gear=$Gear."~Array~$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[fDiaFrom]~ArrayItem~$itemrow[fDiaTo]~ArrayItem~$itemrow[cDiaType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
						}	
					}
					else if ($row['cItemName']=="Pinion")
					{
						if ($Pinion=="")
						{
							$Pinion="$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[iTeethFrom]~ArrayItem~$itemrow[iTeethTo]~ArrayItem~$itemrow[cTeethType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
						}
						else
						{
							$Pinion=$Pinion."~Array~$itemrow[fDMValue]~ArrayItem~$itemrow[cType]~ArrayItem~$itemrow[iTeethFrom]~ArrayItem~$itemrow[iTeethTo]~ArrayItem~$itemrow[cTeethType]~ArrayItem~$itemrow[fRate]~ArrayItem~$itemrow[iId]";
						}	
					}
				}
			}
		}
	}
	$SpecialItemqry=mysql_query ("select * from special_item_rate_master where iPartyID =$PartyID");
	if ($SpecialItemqry && mysql_num_rows($SpecialItemqry)>0)
	{
		while ($SpecialItemrow=mysql_fetch_array($SpecialItemqry))
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
	$ItemData=$Gear."~ItemData~".$Pinion."~ItemData~".$SpecialItem;
	print $ItemData;
	
?>