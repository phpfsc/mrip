<?php
	require_once("../../config.php");
    $PitchValue=$_GET['PitchValue'];
    $PitchType=$_GET['PitchType'];
	$PartyID=$_GET['PartyID'];
    $ItemData=0;

	$qry=mysqli_query ($con2,"select fRate from party_rates_master where iPartyID ='$PartyID' and cItemName ='Chain Gear' and fPitchValue=$PitchValue  and cPitchType ='$PitchType'");
	if ($qry)
	{
		if (mysqli_num_rows($qry)>0)
		{
			$row=mysqli_fetch_array($qry);
			$ItemData=$row['fRate'];
		}
		else
		{
			$ItemData=0;
		}
	}
    print $ItemData;

?>