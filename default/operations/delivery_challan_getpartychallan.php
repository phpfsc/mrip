<?php
	require ('../general/dbconnect.php');
	$PartyID=$_GET['PartyID'];
	$result=mysql_query ("select cChallanCode , iChallanID from party_challan where iPartyCode='$PartyID' and bBilled <>'1'");
	if ($result)
	{
		if (mysql_num_rows($result)>0)
		{
			while ($row=mysql_fetch_array($result))
			{
				if ($ItemData=="")
					$ItemData=$row['iChallanID']."~ArrayItem~".$row['cChallanCode'];
				else
					$ItemData= $ItemData ."~Array~".$row['iChallanID']."~ArrayItem~".$row['cChallanCode'];
			}
		}
		else
		{
			$ItemData="";
		}
	}
	else
	{
		$ItemData="Error";
	}
	print $ItemData;
?>