<?php
	require ('../general/dbconnect.php');
	$CompanyID=$_GET['Company'];
	$CompanySNo=$_GET['CompanySno'];
	$ItemData="";
	$qry=mysql_query("select distinct(iPartyCode), iFirmSNo  from party_account where iCompanyCode='$CompanyID' and iCompanySNo='$CompanySNo'");
	if ($qry && mysql_num_rows($qry)>0)	
	{
		while ($row=mysql_fetch_array($qry))
		{
			if ($row['iFirmSNo']==0)
			{
				if ($ItemData=="")
					$ItemData="$row[iPartyCode]~ArrayItem~$row[iFirmSNo]";
				else
					$ItemData=$ItemData."~Array~$row[iPartyCode]~ArrayItem~$row[iFirmSNo]";
			}
		}
		
	}
	print $ItemData;
?>