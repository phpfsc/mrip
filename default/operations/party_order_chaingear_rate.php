<?php
	require_once("../../config.php");
    $PitchValue=$_GET['PitchValue'];
    $PitchType=$_GET['PitchType'];
	$PartyID=$_GET['PartyID'];
	$ItemType=trim($_GET['ItemType']);
    $ItemData=0;
	$qry=mysqli_query ($con2,"select fRate from party_rates_master where iPartyID ='$PartyID'  and cItemName ='Chain Gear' and fPitchValue=$PitchValue  and cPitchType ='$PitchType'");
	if ($qry)
	{
		if (mysqli_num_rows($qry)>0)
		{
			$row=mysqli_fetch_array($qry);
			$ItemData=$row['fRate'];
		}
		else
		{
			$result=mysqli_query($con2,"select fRate  from chain_gear_rate_master where fPitchValue =$PitchValue and cPitchType ='$PitchType'");
			if ($result && mysqli_num_rows($result)>0)
			{
			   $row=mysqli_fetch_array($result);
			   $ItemData=$row['fRate'];
			}
			else
			{
			   $ItemData=0;
			}
		}
	}

	if ($ItemType=="Single")
	{
		$fac=1;
	}
	else if ($ItemType=="Duplex")
	{
		$fac=2;
	}
	else if ($ItemType=="Triplex")
	{
		$fac=3;
	}
	else 
	{
		$fac=4;
	}
	$ItemData=$ItemData * $fac;	
    print $ItemData;

?>