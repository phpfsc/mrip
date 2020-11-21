<?php
    require_once("../../config.php");
	
	$Err=false;
	$ErrMsg="";
	$ItemArray="";
	$PartyID=trim($_POST['PartyId']);
    $fromDate=trim($_POST['fromDate']);
	//$toDate=trim($_POST['toDate']);
	if(empty(trim($PartyID)))
	{
		$Err=true;
		$ErrMsg="Invalid Request2";
	}
	else if(empty(trim($fromDate)))
	{
		$Err=true;
		$ErrMsg="Please select from date";
	}
	else
	{
	
   
	$ItemData="";
	$result=mysqli_query($con2,"select distinct(iItemCode),cItemType,TablePrefix from (select distinct(iItemCode),cItemType,dOrderDate,'' as TablePrefix
	 from party_order join party_order_detail on party_order.iOrderID =party_order_detail.iOrderID where iPartyCode=\"$PartyID\" and dOrderDate>=\"$fromDate\" and bDeleted <>'1') as tbl order by  tbl.cItemType, tbl.dOrderDate");
	if ($result)
	{
		
		if (mysqli_num_rows($result)>0)
		{	 
	       
			while ($row=mysqli_fetch_array($result))
			{
				 
				 $ItemCode=$row['iItemCode'];
				 $TablePrefix=$row['TablePrefix'];
				 $ItemType=$row['cItemType'];
				if ($ItemType=="Gear")
				{
					$Itemqry=mysqli_query($con2,"select * from gear_master where iId ='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						 $ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."  ".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."  ".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."  ".$Itemrow['cType'].")";
					}
				}
				else if ($ItemType=="Pinion")
				{
					$Itemqry=mysqli_query($con2,"select * from pinion_master where iId='$ItemCode'");	
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						 $ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."  ".$Itemrow['cDiaType']." face ".$Itemrow['fFace']."  ".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."  ".$Itemrow['cType'].")";
					}
				}
				else if ($ItemType=="Bevel Pinion")
				{
					$Itemqry=mysqli_query($con2,"select * from bevel_pinion_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						 $ItemName=$Itemrow['iTeeth']." teeth dia $Itemrow[fDia] $Itemrow[cDiaType](".$Itemrow['fDMValue']."  ".$Itemrow['cType'].")";
					}
				}
				else if ($ItemType=="Bevel Gear")
				{
					$Itemqry=mysqli_query($con2,"select * from bevel_gear_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						 $ItemName=$Itemrow['iTeeth']." teeth dia $Itemrow[fDia] $Itemrow[cDiaType] (".$Itemrow['fDMValue']."  ".$Itemrow['cType'].")";
					}
				}
				else if ($ItemType=="Shaft Pinion")
				{
					$Itemqry=mysqli_query($con2,"select * from shaft_pinion_master where iId='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						 $ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia $Itemrow[fDia] $Itemrow[cDiaType] face ".$Itemrow['fFace']."  ".$Itemrow['cFaceType']." (".$Itemrow['fDMValue']."  ".$Itemrow['cType'].")";
					}
				}
				else if ($ItemType=="Chain Wheel")
				{
					$Itemqry=mysqli_query($con2,"select * from chain_gear_master where iId='$ItemCode'");
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						 $ItemName=$Itemrow['iTeeth']." teeth ".$Itemrow['cItemType']." dia ".$Itemrow['fDia']."  ".$Itemrow['cDiaType']." pitch ".$Itemrow['fPitch']."  ".$Itemrow['cPitchType'];
					}
				}
				else if ($ItemType=="Worm Gear")
				{
					$Itemqry=mysqli_query($con2,"select * from worm_gear_master where iId='$ItemCode'");			
					if ($Itemqry && mysqli_num_rows($Itemqry)>0)
					{	
						$Itemrow=mysqli_fetch_array($Itemqry);
						 $ItemName=$Itemrow['iTeeth']." teeth dia $Itemrow[fDia] $Itemrow[cDiaType] (".$Itemrow['fDMValue']."  ".$Itemrow['cType'].")";
					}
				}
				
			     
				$result1=mysqli_query($con2,"select iOrderID,dOrderDate, cOrderCode , iNoPcsRec , iNoPcsDisp , cChallanNo , iItemCode , fRate , cPartType, cPcs, cCollar,cItemRemarks , PrefixYear from (select {$TablePrefix}party_order.iOrderID ,{$TablePrefix}party_order.dOrderDate ,cOrderCode , iNoPcsRec , iNoPcsDisp , cChallanNo , iItemCode , fRate , cPartType, cPcs, cCollar,cItemRemarks , '' as PrefixYear from {$TablePrefix}party_order join {$TablePrefix}party_order_detail on {$TablePrefix}party_order.iOrderID ={$TablePrefix}party_order_detail.iOrderID where iPartyCode='$PartyID' and dOrderDate>='$fromDate'  and cItemType ='$ItemType' and iItemCode ='$ItemCode' and bDeleted<>'1' and iNoPcsRec>iNoPcsDisp) as tbl order by tbl.dOrderDate, tbl.iOrderID");
				if ($result1 && mysqli_num_rows($result1)>0)
				{
					
					while ($row1=mysqli_fetch_array($result1))
					{
						$dArr=explode("-",$row1['dOrderDate']);
						$date="$dArr[2]/$dArr[1]/$dArr[0]";
						
						if ($Data=="")
							$Data="$row1[iOrderID]~ArrayItem~$row1[cOrderCode]~ArrayItem~$row1[iNoPcsRec]~ArrayItem~$row1[iNoPcsDisp]~ArrayItem~$row1[cChallanNo]~ArrayItem~$row1[iItemCode]~ArrayItem~$row1[fRate]~ArrayItem~$date~ArrayItem~$row1[cPartType]~ArrayItem~$row1[cPcs]~ArrayItem~$row1[cCollar]~ArrayItem~$row1[cItemRemarks]~ArrayItem~$row1[PrefixYear]~ArrayItem~$row1[fsYear]~ArrayItem~$row[TablePrefix]";
						else
							$Data=$Data."~Array~$row1[iOrderID]~ArrayItem~$row1[cOrderCode]~ArrayItem~$row1[iNoPcsRec]~ArrayItem~$row1[iNoPcsDisp]~ArrayItem~$row1[cChallanNo]~ArrayItem~$row1[iItemCode]~ArrayItem~$row1[fRate]~ArrayItem~$date~ArrayItem~$row1[cPartType]~ArrayItem~$row1[cPcs]~ArrayItem~$row1[cCollar]~ArrayItem~$row1[cItemRemarks]~ArrayItem~$row1[PrefixYear]~ArrayItem~$row1[fsYear]~ArrayItem~$row[TablePrefix]";
					}
					$ItemData="$ItemType~Caption~$ItemName~Heading~";
					
					if ($ItemArray=="")
					{
						$ItemArray=$ItemData.$Data;
					}
					else
					{
						$ItemArray=$ItemArray."~ItemData~".$ItemData.$Data;
					}
					$Data="";
				}
				
				
			}
			
		}
		else
		{
			$Err=true;
		    $ErrMsg="No challan details found";
		}
		
	}
	else
	{
		$Err=true;
		$ErrMsg="No Purchase Order Found...";
	}
  
  }
  $response_array['error']=$Err;
  $response_array['error_msg']=$ErrMsg;
  $response_array['dataChallan']=$ItemArray;
  echo json_encode($response_array); 
?>