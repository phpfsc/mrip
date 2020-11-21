<?php
   require_once("../../config.php");
   $Teeth=$_GET['Teeth'];
   $GearType=$_GET['Type'];
   $Dia=$_GET['Dia'];
   $DiaType=$_GET['DiaType'];
   $Face=$_GET['Face'];
   $FaceType=$_GET['FaceType'];
   $DMValue=$_GET['DMValue'];
   $DMValueType=$_GET['DMValueType'];
   $PartyID=$_GET['PartyID'];
   $ItemData=0;


	$qry=mysqli_query ($con2,"select fRate from pp_party_rates_master where iPartyID='$PartyID' and cItemName='Gear' and fDMValue =$DMValue  and cType='$DMValueType' and fDiaFrom<=$Dia  and fDiaTo >=$Dia  and cDiaType ='$DiaType'");
	if ($qry)
	{
		if (mysqli_num_rows($qry)>0)
		{
			$row=mysqli_fetch_array($qry);
			$ItemData=$row['fRate'];	
		}
		else
		{
			
		   $result=mysqli_query ($con2,"select fRate from pp_gear_rate_master where fDMValue =$DMValue and cType ='$DMValueType' and fDiaFrom <=$Dia  and fDiaTo >=$Dia and cDiaType ='$DiaType'");
		   if ($result && mysqli_num_rows($result)>0)
		   {
				$row=mysqli_fetch_array($result);		
				$ItemData=$row['fRate'];
		   }
		   else
		   {
				$ItemData="0";
		   }
		}
   }
   print  $ItemData;
?>