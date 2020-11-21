<?php
	require_once("../../config.php");
	$Type=$_GET['Type'];
	$Teeth=$_GET['Teeth'];
	$Face=$_GET['Face'];
	$FaceType=$_GET['FaceType'];
	$DMValue=$_GET['DMValue'];
	$DMType=$_GET['DMType'];
	$PartyID=$_GET['PartyID'];
	$ItemData=0;
	
   if ($Type=="Plain")
		$qry=mysqli_query ($con2,"select fRate from party_rates_master where iPartyID='$PartyID' and cItemName='Shaft Pinion' and fDMValue =$DMValue  and cType='$DMType'  and fFaceFrom<=$Face  and fFaceTo >=$Face  and cFaceType ='$FaceType' and iTeeth =$Teeth");
	else
		$qry=mysqli_query ($con2,"select fRate from party_rates_master where iPartyID='$PartyID' and cItemName='Shaft Pinion' and fDMValue =$DMValue  and cType='$DMType'  and fFaceFrom<=$Face  and fFaceTo >=$Face  and cFaceType ='$FaceType'");
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