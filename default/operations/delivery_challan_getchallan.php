<?php
$ChallanID=$_GET['ChallanId'];

require ('../general/dbconnect.php');
$ItemData="";
$result=mysql_query ("select * from party_challan join party_challan_detail on party_challan.iChallanID=party_challan_detail.iChallanID where party_challan.iChallanID='$ChallanID'");
if ($result)
{
	if (mysql_num_rows($result)>0)
	{
		while ($row=mysql_fetch_array($result))
		{
			$ItemType=$row['cItemType']; 
			$ItemCode=$row['iItemCode'];
			$OrderID=$row['iOrderID'];
			$Disp=$row['iNoPcsDisp'];
			$PartyID=$row['iPartyCode'];
			$ChallanID=$row['iChallanID'];
			$FirmID=$row['iFirmSNo'];
			
			if ($ItemType=="Gear")
			{
				$Itemqry=mysql_query ("select * from gear_master where iId ='$ItemCode'");
				if ($Itemqry && mysql_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysql_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
				}
			}
			else if ($ItemType=="Pinion")
			{
				$Itemqry=mysql_query ("select * from pinion_master where iId='$ItemCode'");	
				if ($Itemqry && mysql_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysql_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
				}
			}
			else if ($ItemType=="Bevel Pinion")
			{
				$Itemqry=mysql_query ("select * from bevel_pinion_master where iId='$ItemCode'");			
				if ($Itemqry && mysql_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysql_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
				}
			}
			else if ($ItemType=="Bevel Gear")
			{
				$Itemqry=mysql_query ("select * from bevel_gear_master where iId='$ItemCode'");			
				if ($Itemqry && mysql_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysql_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth  (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
				}
			}
			else if ($ItemType=="Shaft Pinion")
			{
				$Itemqry=mysql_query ("select * from shaft_pinion_master where iId='$ItemCode'");
				if ($Itemqry && mysql_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysql_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
				}
			}
			else if ($ItemType=="Chain Wheel")
			{
				$Itemqry=mysql_query ("select * from chain_gear_master where iId='$ItemCode'");
				if ($Itemqry && mysql_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysql_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." pitch ".$Itemrow['fPitch']."&nbsp;".$Itemrow['cPitchType'];
				}
			}
			else if ($ItemType=="Worm Gear")
			{
				$Itemqry=mysql_query ("select * from worm_gear_master where iId='$ItemCode'");			
				if ($Itemqry && mysql_num_rows($Itemqry)>0)
				{	
					$Itemrow=mysql_fetch_array($Itemqry);
					$ItemName=$Itemrow['iTeeth']." teeth  (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
				}
			}
			
			$ItemData="$ItemType~Caption~$ItemName~Heading~";
			
			$result1=mysql_query ("select party_order_detail.iOrderID,cOrderCode , iNoPcsRec, party_order_detail.iNoPcsDisp ,cChallanNo ,  party_order_detail.iItemCode ,  party_order_detail.fRate , party_challan_detail.cItemRemarks, (party_challan_detail.iNoPcsDisp) as Dispatch   from party_order join party_order_detail on party_order.iOrderID=party_order_detail.iOrderID  join party_challan_detail on party_order_detail.iOrderID=party_challan_detail.iOrderID and party_order_detail.cItemType=party_challan_detail.cItemType and party_order_detail.iItemCode=party_challan_detail.iItemCode  where party_order_detail.iOrderID='$OrderID' and party_challan_detail.iChallanID='$ChallanID' and party_challan_detail.cItemType='$ItemType' and party_challan_detail.iItemCode='$ItemCode' ") or die(mysql_error());
			if ($result1 && mysql_num_rows($result1)>0)
			{
				
				while ($row1=mysql_fetch_array($result1))
				{
					$Dispatched=$row1['iNoPcsDisp'] - $row1['Dispatch']; 
						
					$Data="$row1[iOrderID]~ArrayItem~$row1[cOrderCode]~ArrayItem~$row1[iNoPcsRec]~ArrayItem~$Dispatched~ArrayItem~$row1[cChallanNo]~ArrayItem~$row1[iItemCode]~ArrayItem~$row1[fRate]~ArrayItem~$row1[cItemRemarks]~ArrayItem~$row1[Dispatch]";
				}
			}
			if ($ItemArray=="")
				$ItemArray=$ItemData.$Data;
			else
				$ItemArray=$ItemArray."~ItemData~".$ItemData.$Data;
			$Data="";
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

