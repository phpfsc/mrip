<?php
	require ('../general/dbconnect.php');
	if (isset($_GET['ChallanID']))
	{
		$rollback=false;
		$Err=true;
		$ChallanID=$_GET['ChallanID'];
		mysql_query ("begin");
		$check=mysql_query ("select * from party_challan_detail  where iChallanID ='$ChallanID'");
		if ($check)
		{
			if (mysql_num_rows($check)>0)
			{
				while ($checkrow=mysql_fetch_array($check))
				{
					$OrderId=$checkrow['iOrderID'];
					$qry=mysql_query ("select * from party_billing_detail where iOrderID='$OrderId'");
					if ($qry)
					{
						if (mysql_num_rows($qry)>0)
						{
							$Err=false;
						}
					}
					
					$qry1=mysql_query ("select * from k_party_billing_detail where iOrderID='$OrderId'");
					if ($qry1)
					{
						if (mysql_num_rows($qry1)>0)
						{
							$Err=false;
						}
					}
				}
				if (!$Err)
				{
					$Msg="This Delivery Challan is already Billed...";
				}
			}
			else
			{
				$Err=true;	
			}
		}
		else
		{
			$Err=false;
			$Msg="Error in getting detail";
		}
		
		
		if ($Err)
		{
			$result=mysql_query ("select * from party_challan_detail  where iChallanID ='$ChallanID'");
			if ($result)
			{
				if (mysql_num_rows($result)>0)
				{
					while ($rrow=mysql_fetch_array($result))
					{
						$OrderID1=$rrow['iOrderID'];
						$ItemType=$rrow['cItemType'];
						$ItemCode=$rrow['iItemCode'];
						$PartType=$rrow['cPartType'];
						$Disp=$rrow['iNoPcsDisp'];
						$Return=$rrow['iNoPcsReturn'];
						$Pcs=$rrow['cPcs'];
						
						$result1=mysql_query("select iNoPcsDisp  from party_order_detail where iOrderID='$OrderID1' and cItemType='$ItemType' and iItemCode ='$ItemCode' and cPartType ='$PartType' and cPcs='$Pcs' ");
						if ($result1) 
						{
							if (mysql_num_rows($result1)>0)
							{
								$rrow1=mysql_fetch_array($result1);
								$TotalDisp=$rrow1['iNoPcsDisp'] - ($Disp + $Return);
								$Updateqry=mysql_query ("UPDATE party_order_detail set iNoPcsDisp='$TotalDisp' where iOrderID='$OrderID1' and cItemType='$ItemType' and iItemCode ='$ItemCode' and cPartType ='$PartType' and cPcs='$Pcs'");
								if (! $Updateqry)
								{
									$rollback=true;
									print "Error in Updating";
								}
							}
						}
					}	
				}
			}
			else
			{	
				echo "Error in getting detail";
			}
			$deleteqry=mysql_query ("DELETE from party_challan where iChallanID ='$ChallanID'");
			if (! $deleteqry)
			{
				$rollback=true;
			}
			else
			{
				$Msg="Delivery Challan Deleted ...";	
			}
			$deleteqry=mysql_query ("DELETE from party_challan_detail where iChallanID ='$ChallanID'");
			if (! $deleteqry)
			{
				$rollback=true;
			}
			else
			{
				$Msg="Delivery Challan Deleted ...";	
			}
		}
		else
		{
			print $Msg;
		}
	}

	if ($rollback)
	{
		mysql_query ("rollback");
		$Msg="Error in Deleting Delivery Challan...";
	}
	else
	{
		mysql_query("commit");
		print "<span class=\"legend\" > $Msg</span>";
	}
	
?>