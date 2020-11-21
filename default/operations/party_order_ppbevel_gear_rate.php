<?php
	require_once("../../config.php");
    $DMValue=$_GET['DMvalue'];
    $DMValueType=$_GET['DMValueType'];
	$PartyID=$_GET['PartyID'];
	$Teeth=$_GET['Teeth'];
	$ItemData=0;
	
	$qry=mysqli_query ($con2,"select fRate from party_rates_master where iPartyID='$PartyID' and cItemName ='BevelGear' and fDMValue =$DMValue and cType ='$DMValueType' and iTeeth =$Teeth");
	if ($qry)
	{
		if (mysqli_num_rows($qry)>0)
		{	
			$row=mysqli_fetch_array($qry);
			$ItemData=$row['fRate'];
		}
		else
		{
			$result=mysqli_query ($con2,"select fRate from pp_bevel_gear_rate_master where fDMValue =$DMValue and cType ='$DMValueType' and iTeeth =$Teeth");
			if ($result && mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				$ItemData=$row['fRate'];
			}
			else
			{
				$ItemData=0;
			}
			$ItemData=0;
		}
	}
	print $ItemData;
?>