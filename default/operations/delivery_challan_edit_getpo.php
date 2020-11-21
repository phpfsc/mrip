<?php
require ('../general/dbconnect.php');
$user=$_SESSION['DbUsername'];
$pass=$_SESSION['DbPassword'];
$db=$_SESSION['Database'];
$Type= $_GET['Type'];
$Itemdata="";
$x=1;
if ($Type=="Date")
{
	$ToDate=$_GET['ToDate'];
	$tArr=explode("/",$ToDate);
	$tdate="$tArr[2]-$tArr[1]-$tArr[0]";
	$result=mysql_query ("select * from party_challan join party_master on party_challan.iPartyCode  = party_master.iPartyID  where dChallanDate <= '$tdate'  order by dChallanDate");
}
else
{
	$PartyCode=$_GET['PartyCode'];
	$result=mysql_query ("select * from party_challan join party_master on party_challan.iPartyCode  = party_master.iPartyID  where party_challan.iPartyCode ='$PartyCode'  order by dChallanDate");
}
if ($result && mysql_num_rows($result)>0)
{
	$Itemdata=$Itemdata. "<table cellpadding=\"0\" cellspacing=\"0\" width=\"920\" class=\"labeltext\" style=\"margin:30px;\">";
	$Itemdata=$Itemdata. "<tr class=\"tableheadingleft\"><td width=\"30\" height=\"25\">SNo</td><td width=\"170\"> Challan NO</td><td width=\"90\">Challan Date</td><td width=\"250\">Party Name</td><td width=\"90\">No. of Items</td><td width=\"80\">Value</td><td width=\"150\">Option</td>";
	while ($row=mysql_fetch_array($result))
	{
		$dArr=explode("-",$row['dChallanDate']);
		$Date="$dArr[2]/$dArr[1]/$dArr[0]";

		$Itemdata=$Itemdata. "<tr class=\"tableheadingleft\"><td height=\"25\" class=\"tablecell\">$x</td><td class=\"tablecell\">$row[cChallanCode]</td><td class=\"tablecell\">$Date</td><td class=\"tablecell\">$row[cPartyName]</td>";
		$itemqry=mysql_query ("select * from party_challan_detail where iChallanID ='$row[iChallanID]' ");
		if ($itemqry && mysql_num_rows($itemqry)>0)
		{
			while ($itemrow=mysql_fetch_array($itemqry))
			{
				$ItemType=$itemrow['cItemType'];
				$ItemCode=$itemrow['iItemCode'];
				$Rate=$itemrow['fRate'];
				$Disp=$itemrow['iNoPcsDisp'];
				$TotalNoPcs=$TotalNoPcs + $itemrow['iNoPcsDisp'];
				if ($ItemType=="Gear")
				{
					$Itemqry=mysql_query ("select * from gear_master where iId ='$ItemCode'");
					if ($Itemqry && mysql_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysql_fetch_array($Itemqry);
						$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
					}
					$Price=$Disp*$Itemrow['iTeeth']* $Rate; 
				}
				else if ($ItemType=="Pinion")
				{
					$Itemqry=mysql_query ("select * from pinion_master where iId='$ItemCode'");	
					if ($Itemqry && mysql_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysql_fetch_array($Itemqry);
						$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
					}
					$Price=$Disp*$Itemrow['iTeeth']* $Rate; 
				}
				else if ($ItemType=="Bevel Pinion")
				{
					$Itemqry=mysql_query ("select * from bevel_pinion_master where iId='$ItemCode'");			
					if ($Itemqry && mysql_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysql_fetch_array($Itemqry);
						$ItemName=$Itemrow['iTeeth']." teeth (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
					}
					$Price=$Disp * $Rate * $Itemrow['iTeeth'];
				}
				else if ($ItemType=="Bevel Gear")
				{
					$Itemqry=mysql_query ("select * from bevel_gear_master where iId='$ItemCode'");			
					if ($Itemqry && mysql_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysql_fetch_array($Itemqry);
						$ItemName=$Itemrow['iTeeth']." teeth  (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
					}
					$Price=$Disp * $Rate * $Itemrow['iTeeth'];
				}
				else if ($ItemType=="Shaft Pinion")
				{
					$Itemqry=mysql_query ("select * from shaft_pinion_master where iId='$ItemCode'");
					if ($Itemqry && mysql_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysql_fetch_array($Itemqry);
						$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." face ".$Itemrow['fFace']."&nbsp;".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
					}
					$Price=$Disp * $Rate;
				}
				else if ($ItemType=="Chain Wheel")
				{
					$Itemqry=mysql_query ("select * from chain_gear_master where iId='$ItemCode'");
					if ($Itemqry && mysql_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysql_fetch_array($Itemqry);
						$ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."&nbsp;".$Itemrow['cDiaType']." pitch ".$Itemrow['fPitch']."&nbsp;".$Itemrow['cPitchType'];
					}
					if ($Itemrow['cItemType']=="Single")
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
					}
					$Price=$Disp* $Itemrow['iTeeth']*$Rate* $fac;
				}
				else if ($ItemType=="Worm Gear")
				{
					$Itemqry=mysql_query ("select * from worm_gear_master where iId='$ItemCode'");			
					if ($Itemqry && mysql_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysql_fetch_array($Itemqry);
						$ItemName=$Itemrow['iTeeth']." teeth  (".$Itemrow['fDMValue']."&nbsp;".$Itemrow['cType'].")";
					}
					$Price=$Disp * $Rate;
				}
				
				$TotalValue=$TotalValue + $Price; 
			}
		}
		$Itemdata=$Itemdata. "<td class=\"tablecell\">$TotalNoPcs</td><td class=\"tablecell\">".number_format($TotalValue,'2','.','')."</td><td class=\"tablecell\" style=\"text-align:right\">";
		if ($row['bBilled']=="1")
		{
			$Itemdata=$Itemdata. "<span class=\"legend\" style=\"width:120px;text-align:center;\">Billed</span>";
		}
		else
		{
			$Itemdata=$Itemdata. "<input type=\"button\" value=\"Edit\" name=\"btnEditRec\" id=\"btnEditRec\" class=\"btncss\" onClick=\"ShowPO('$row[iChallanID]')\" style=\"width:60px;\"><input type=\"button\" name=\"btnDeleteRec\" id=\"btnDeleteRec\" value=\"Delete\" onClick=\"DCDelete('$row[iChallanID]')\" class=\"btncss\" style=\"width:60px;\">";
		}
		$Itemdata=$Itemdata."<input type=\"button\" name=\"btnPrint\" id=\"btnPrint\" value=\"Print\" onClick=\"PrintPdf('$row[iChallanID], $user, $pass, $db')\" class=\"btncss\" style=\"width:60px;\"></td></tr>";
		$TotalNoPcs=0;
		$TotalValue=0;
		$x++;

	}
	$Itemdata=$Itemdata. "</table>";
}
print $Itemdata;
?>