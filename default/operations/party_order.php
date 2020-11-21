<?php

	/*

	   1. Through this screen we can enter Purchase Order by clicking on 'New Order' radio button and we can edit 

	      all Old purchase order by clicking on 'All Order' radio button . We can add , delete and modify old items . In case of 

	   	  deletion item will not be deleted from Database it will be marked as deleted.

	   2. If an item is not present in the given Combo, then we can make a new item by entering all its detail.

	   3. collar is added for pinion only

	   4. Now while making order he can select that he wants to apply the rates of Powerpress/ Expeller for particular item

	   5. Individual item rate can be calculated basis on PT/PCS

	   6. For Pinion under Powerpress rate can not be less than 60.

	   		

	   Tables Used

	   1. party_order

	   2  party_order_detail

	   3. gear_master

	   4. pinion_master

	   5. bevel_gear_master

	   6. bevel_pinion_master

	   7. chain_gear_master

	   

	   Files Used

	   1. party_order.php

	   2. party_order.js

	   3. party_order_edit.php

	   4. party_order_bevel_gear_rate.php

	   5. party_order_chaingear_rate.php

	   6. party_order_gear_rate.php

	   7. party_order_shaft_pinion_rate.php

	   8. party_order_edit_getpo.php

	   9. party_order_all_order.php

	   10. purchase_order_getpo.php

	   

	*/

	require_once("../../config.php");
	$PageMode="New";
	$Err=false;
	$ErrMsg="";
    if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="Invalid Request";
	}
	else
	{
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
	}
	

	
    
   
	$CurrDate=date ("d/m/Y");

	$firstday = date ("m/d/Y", mktime(0, 0, 0, date("m") , date("d")-10 , date("Y")));

	$startDate=explode("/",$firstday);

	$BeforeDate = $startDate[1]."/".$startDate[0]."/".$startDate[2];	

	

	$Month=$_SESSION['Month'];

	$YearString=$_SESSION['YearString'];

	

	$PPrateQry=mysqli_query ($con2,"select fMinValue  from party_master where fMinValue>0 and cPartType ='PowerPress'");	

	if ($PPrateQry && mysqli_num_rows($PPrateQry)>0)

	{

		$PPrateRow=mysqli_fetch_array($PPrateQry);

		$MinValue=$PPrateRow['fMinValue'];

	}

	else

	{

		$MinValue=0;

	}

	
    //$iOrderID=base64_encode("055539");
	if (!empty(trim($_POST['Data'])))
    {
		
        $OrderID=base64_decode(base64_decode(trim($_POST['Data'])));
		
	    
	   
		
		
		
     
			
			
			
				$ItemData="";
				$result=mysqli_query ($con2,"select * from party_order join party_order_detail  where party_order.iOrderID=party_order_detail.iOrderID and party_order.iOrderID='$OrderID' and bDeleted <>1 ");
				if ($result && mysqli_num_rows($result)>0)	 
				{
				   while ($row=mysqli_fetch_array($result))
				   {
					  $PartyCode=$row['iPartyCode'];
					  $Remarks=$row['cRemarks'];
					  $ChallanNo=$row['cChallanNo'];
					  $dOrderdate=explode("-",$row['dOrderDate']);
					  $CurrDate= "$dOrderdate[2]/$dOrderdate[1]/$dOrderdate[0]";
					  $ItemType =$row['cItemType'];
					  $Rate=$row['fRate'];
					  $Disp=$row['iNoPcsRec'];
					  $PartType=$row['cPartType'];
					  $Pcs=$row['cPcs'];
					  $Collar=$row['cCollar'];
					  $MinValue=$row['fMinValue'];
					  if ($row['cItemType']=="Gear")
					  {

						$qry=mysqli_query ($con2,"select * from gear_master where iId='$row[iItemCode]'");

					  }

					  else if ($row['cItemType']=="Pinion")

					  {

						$qry=mysqli_query ($con2,"select * from pinion_master where iId='$row[iItemCode]'");

					  }

					  else if ($row['cItemType']=="Shaft Pinion")

					  {

						$qry=mysqli_query ($con2,"select * from shaft_pinion_master where iId='$row[iItemCode]'");

					  }

					  else if ($row['cItemType']=="Bevel Gear")

					  {

						$qry=mysqli_query ($con2,"select * from bevel_gear_master where iId='$row[iItemCode]'");

					  }

					   else if ($row['cItemType']=="Bevel Pinion")

					   {

							$qry=mysqli_query ($con2,"select * from bevel_pinion_master where iId='$row[iItemCode]'");

					   }

					   else if ($row['cItemType']=="Chain Wheel")

					   {

						   $qry=mysqli_query ($con2,"select * from chain_gear_master where iId='$row[iItemCode]'");

					   }

					   else if ($row['cItemType']=="Worm Gear")

					   {

							$qry=mysqli_query ($con2,"select * from worm_gear_master where iId='$row[iItemCode]'");

					   }



					   if ($qry && mysqli_num_rows($qry)>0)

					   {

							$row1=mysqli_fetch_array($qry);

						   if ($ItemData=="")

						   {

								$ItemData ="$row[iNoPcsRec]~ArrayItem~$row[fRate]~ArrayItem~$row1[iTeeth]~ArrayItem~$row1[fDMValue]~ArrayItem~$row1[cType]~ArrayItem~$row1[fDia]~ArrayItem~$row1[cDiaType]~ArrayItem~$row1[fFace]~ArrayItem~$row1[cFaceType]~ArrayItem~$row1[cItemType]~ArrayItem~$row1[fPitch]~ArrayItem~$row[cItemType]~ArrayItem~$row1[cPitchType]~ArrayItem~$row1[iId]~ArrayItem~$row[cItemRemarks]~ArrayItem~$PartType~ArrayItem~$Pcs~ArrayItem~$Collar";

						   }

						   else

						   {

								$ItemData=$ItemData. "~Array~$row[iNoPcsRec]~ArrayItem~$row[fRate]~ArrayItem~$row1[iTeeth]~ArrayItem~$row1[fDMValue]~ArrayItem~$row1[cType]~ArrayItem~$row1[fDia]~ArrayItem~$row1[cDiaType]~ArrayItem~$row1[fFace]~ArrayItem~$row1[cFaceType]~ArrayItem~$row1[cItemType]~ArrayItem~$row1[fPitch]~ArrayItem~$row[cItemType]~ArrayItem~$row1[cPitchType]~ArrayItem~$row1[iId]~ArrayItem~$row[cItemRemarks]~ArrayItem~$PartType~ArrayItem~$Pcs~ArrayItem~$Collar";

						   }

					   }

				   }
                $PageMode="Edit";
			   }
               else
			   {
				   $PageMode="New";
			   }
			   
			
		
		

		

	}

	else

	{

		$PageMode="New";

	}

	

	if (isset($_POST['btnOrder']))

	{

		if (trim($_POST['cmbParty'])=="")

		{

			$Err=true;

			$ErrMsg[]="Please select the Party Name...";

		}

		else if (trim($_POST['hdOrderData']==""))

		{

			$Err=true;

			$ErrMsg[]="Please Place an Order...";

		}

		else if (trim($_POST['txtDate']==""))

		{

			$Err=true;

			$ErrMsg[]="Please Select Date...";

		}

		

		$PartyCodeType=explode("~ArrayItem~",$_POST['cmbParty']);

		$PartyCode=$PartyCodeType[0];

		$ItemData=$_POST['hdOrderData'];

		

		$dArr=explode("/",$_POST['txtDate']);

		$date="$dArr[2]-$dArr[1]-$dArr[0]";

		$Month=$dArr[1];

		$OdDate=$dArr[0];

		$PartyChallanNo=$_POST['txtChallan'];

		$Remarks=$_POST['txtRemarks'];



		if (trim($_POST['rdb']=="Pending"))

		{

			if (trim($_POST['cmbOrder']==""))

			{

				$Err=true;

				$ErrMsg[]="Please Select the Order...";

			}

		}

		$Validate=false;



		if (! $Err)

			$Validate=true;



		if ($Validate)

		{

			$Err=false;

			$OrderNo="";

			$OrderCode="";

			mysqli_query($con2,"begin");

			if (trim($_POST['hdPageMode']=="Edit"))

			{

				// Updating Old Order

				

				$PendingOrderNo=explode("~ArrayItem~",$_POST['cmbOrder']);

				$Yearprefix=$PendingOrderNo[5];

				$result=mysqli_query($con2,"update {$Yearprefix}party_order set dOrderDate=\"".$date."\",cChallanNo=\"".$PartyChallanNo."\",cRemarks=\"".$Remarks."\" where iOrderID=\"".$PendingOrderNo[0]."\" ");

				if (! $result)

				{

					$Err=true;			

				}

				$OrderData=explode('~Array~',$ItemData);

				for ($x=0;$x<sizeof($OrderData);$x++)

				{

					$Data=explode('~ArrayItem~',$OrderData[$x]) ;

					

					if ($Data[14]=="Deleted")

					{

						$result=mysqli_query($con2,"Update {$Yearprefix}party_order_detail set bDeleted='1' where iOrderID=\"".$PendingOrderNo[0]."\" and cItemType=\"".$Data[11]."\" and iItemCode=\"".$Data[15]."\"");

						if (! $result)

						{

							$Err=true;

						}

					}

					else if ($Data[14]=="Edit")

					{

						$result=mysqli_query($con2,"Update {$Yearprefix}party_order_detail set fRate=\"".$Data[1]."\" ,cItemRemarks=\"".$Data[16]."\", iNoPcsRec =\"".$Data[0]."\" where iOrderID=\"".$PendingOrderNo[0]."\" and cItemType=\"".$Data[11]."\" and iItemCode=\"".$Data[15]."\"");

						if (! $result)

						{

							$Err=true;

						}

					}

					else

					{

						if ($Data[11]=="Gear")

						{

							$result=mysqli_query ($con2,"select iId from gear_master where iTeeth =$Data[2] and cItemType =\"".$Data[9]."\" and fDia =$Data[5] and cDiaType =\"".$Data[6]."\" and fFace =$Data[7] and cFaceType =\"".$Data[8]."\" and fDMValue =$Data[3] and cType =\"".$Data[4]."\"");

							if ($result)

							{

								if (mysqli_num_rows($result)>0)

								{	// Item already exist

									$row=mysqli_fetch_array($result);	

									$ItemCode=$row['iId'];

								}

								else

								{

									// New entry

									$Insertqry=mysqli_query ($con2,"Insert into gear_master values ('',\"".$Data[2]."\", \"".$Data[9]."\", \"".$Data[5]."\", \"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\") ");

									if (!$Insertqry)

									{

										$Err=true;

									}

									// Find iId

									$Findqry=mysqli_query ($con2,"select iId from gear_master where iTeeth =$Data[2] and cItemType =\"".$Data[9]."\" and fDia =$Data[5] and cDiaType =\"".$Data[6]."\" and fFace =$Data[7] and cFaceType =\"".$Data[8]."\" and fDMValue =$Data[3] and cType =\"".$Data[4]."\"");

									if ($Findqry && mysqli_num_rows($Findqry)>0)

									{	

										$row1=mysqli_fetch_array($Findqry);	

										$ItemCode=$row1['iId'];

									}

								}

							}

						}

						else if ($Data[11]=="Pinion")

						{

							$result=mysqli_query($con2,"select iId from pinion_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

							if ($result)

							{

								if (mysqli_num_rows($result)>0)

								{	// Item already exist

									$row=mysqli_fetch_array($result);

									$ItemCode=$row['iId'];

								}

								else

								{

									// New entry

									$Insertqry=mysqli_query($con2,"Insert into pinion_master values('',\"".$Data[2]."\", \"".$Data[9]."\", \"".$Data[5]."\", \"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\")");

									if (! $Insertqry)

									{

										$Err=true;

									}

									// Find iId

									$Findqry=mysqli_query($con2,"select iId from pinion_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

									if ($Findqry && mysqli_num_rows($Findqry)>0)

									{	

										$row1=mysqli_fetch_array($Findqry);	

										$ItemCode=$row1['iId'];

									}

								}

							}

						}

						else if ($Data[11]=="Shaft Pinion")

						{

							$result=mysqli_query($con2,"select iId from shaft_pinion_master where iTeeth =$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

							if ($result)

							{

								if (mysqli_num_rows($result)>0)

								{

									// Item already exist

									$row=mysqli_fetch_array($result);

									$ItemCode=$row['iId'];

								}

								else

								{	 // New entry

									$Insertqry=mysqli_query($con2,"Insert into shaft_pinion_master values ('',\"".$Data[2]."\",\"".$Data[9]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\")");

									if (! $Insertqry)

									{

										$Err=true;

									}

									// Find iId

									$Findqry=mysqli_query($con2,"select iId from shaft_pinion_master where iTeeth =$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

									if ($Findqry && mysqli_num_rows($Findqry)>0)

									{	

										$row1=mysqli_fetch_array($Findqry);	

										$ItemCode=$row1['iId'];

									}

								}

							}

						}

						else if ($Data[11]=="Bevel Gear")

						{

							$result=mysqli_query($con2,"select iId from bevel_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

							if ($result)

							{

								if (mysqli_num_rows($result)>0)

								{

									// Item already exist

									$row=mysqli_fetch_array($result);

									$ItemCode=$row['iId'];

								}

								else

								{

									// New entry

									$Insertqry=mysqli_query($con2,"Insert into bevel_gear_master values ('',\"".$Data[2]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\",\"".$Data[4]."\")");

									if (! $Insertqry)

									{

										$Err=true;

									}

									// Find iId

									$Findqry=mysqli_query ($con2,"select iId from bevel_gear_master where iTeeth=$Data[2] and fData=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

									if ($Findqry && mysqli_num_rows($Findqry)>0)

									{	

										$row1=mysqli_fetch_array($Findqry);	

										$ItemCode=$row1['iId'];

									}

								}

							}

						}

						else if ($Data[11]=="Bevel Pinion")

						{

							$result=mysqli_query($con2,"select iId from bevel_pinion_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

							if ($result)

							{

								if (mysqli_num_rows($result)>0)

								{

									// Item already exist

									$row=mysqli_fetch_array($result);

									$ItemCode=$row['iId'];

								}

								else

								{

									// New entry

									$Insertqry=mysqli_query($con2,"Insert into bevel_pinion_master values ('',\"".$Data[2]."\" ,\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\" ,\"".$Data[4]."\")");

									if (! $Insertqry)

									{

										$Err=true;

									}

									 // Find iId

									 $Findqry=mysqli_query ($con2,"select iId from bevel_pinion_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

									if ($Findqry && mysqli_num_rows($Findqry)>0)

									{	

										$row1=mysqli_fetch_array($Findqry);	

										$ItemCode=$row1['iId'];

									}

								}

							}			

						}

						else if ($Data[11]=="Chain Wheel")

						{

							$result=mysqli_query($con2,"select iId from chain_gear_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fPitch=$Data[10] and  cPitchType=\"".$Data[12]."\"");

							if ($result)

							{

								if (mysqli_num_rows($result)>0)

								{

									// Item already exist

									$row=mysqli_fetch_array($result);

									$ItemCode=$row['iId'];

								}

								else

								{

									// New entry

									$Insertqry=mysqli_query($con2,"Insert into chain_gear_master values ('',\"".$Data[2]."\",\"".$Data[9]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[10]."\",\"".$Data[12]."\")");

									if (! $Insertqry)

									{

										$Err=true;

									}

										// Find Id

									$Findqry=mysqli_query($con2,"select iId from chain_gear_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fPitch=$Data[10] and cPitchType=\"".$Data[12]."\"");

									if ($Findqry && mysqli_num_rows($Findqry)>0)

									{	

										$row1=mysqli_fetch_array($Findqry);	

										$ItemCode=$row1['iId'];

									}

								}

							}

						}

						else if ($Data[11]=="Worm Gear")

						{

							$result=mysqli_query($con2,"select iId from worm_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

							if ($result)

							{

								if (mysqli_num_rows($result)>0)

								{

									// Item already exist

									$row=mysqli_fetch_array($result);

									$ItemCode=$row['iId'];

								}

								else

								{

									// New entry

									$Insertqry=mysqli_query($con2,"Insert into worm_gear_master values ('',\"".$Data[2]."\" ,\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\" ,\"".$Data[4]."\")");

									if (! $Insertqry)

									{

										$Err=true;

									}

									 // Find iId

									 $Findqry=mysqli_query ($con2,"select iId from worm_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

									if ($Findqry && mysqli_num_rows($Findqry)>0)

									{	

										$row1=mysqli_fetch_array($Findqry);	

										$ItemCode=$row1['iId'];

									}

								}

							}			

						}

						if ($ItemCode>0)

						{

							$result=mysqli_query ($con2,"Insert into {$Yearprefix}party_order_detail (iOrderID, cItemType, iItemCode,fRate, iNoPcsRec,cItemRemarks , cPartType, cPcs, cCollar) values (\"".$PendingOrderNo[0]."\",\"".$Data[11]."\" ,'$ItemCode',\"".$Data[1]."\",\"".$Data[0]."\",\"".$Data[16]."\", \"".$Data[17]."\", \"".$Data[18]."\", \"".$Data[19]."\")");

							if (! $result)

							{

								$Err=true;

							}

						}

						else

						{	

							$Err=true;

						}

						$ItemCode=0;

					}

				}

			}

			else

			{

				// New Order

				//$result1=mysqli_query($con2,"select cOrderCode from party_order where iOrderID=(Select Max(iOrderID) from party_order)");
				$result1=mysqli_query($con2,"select cOrderCode from party_order order by iOrderID DESC limit 0,1"); 
				if ($result1)

				{

					if (mysqli_num_rows($result1)>0)

					{

						$row1=mysqli_fetch_array($result1);

						$orderCode=$row1['cOrderCode'];

						$orderCode= substr($orderCode,strlen("OD/$OdDate/$Month/$YearString/")) +1;

						//$orderCode= substr($orderCode,strlen("OD/$OdDate/$Month/")) +1;

					}

					else

					{

						$orderCode=1;

					}

				}

				//$NewOrderCode="OD/$OdDate/$Month/$YearString/";

				if ($orderCode < 10)			 

					$OrderCode="OD/$OdDate/$Month/$YearString/00000".$orderCode;

				else if ($orderCode < 100)

					$OrderCode="OD/$OdDate/$Month/$YearString/0000".$orderCode;	

				else if ($orderCode < 1000)

					$OrderCode="OD/$OdDate/$Month/$YearString/000".$orderCode;

				else if ($orderCode < 10000)

					$OrderCode="OD/$OdDate/$Month/$YearString/00".$orderCode;

				else if ($orderCode < 100000)

					$OrderCode="OD/$OdDate/$Month/$YearString/0".$orderCode;

				else

					$OrderCode="OD/$OdDate/$Month/$YearString/".$orderCode;



				$result=mysqli_query($con2,"Insert into party_order values('','$OrderCode','$date','$PartyCode',\"".$PartyChallanNo."\",'$MinValue',\"".$Remarks."\")");

				if (! $result)

				{

					$Err=true;

				}



				$OrderData=explode('~Array~',$ItemData);

				for ($x=0;$x<sizeof($OrderData);$x++)

				{

					$iteeth=$Data[2];

					$fdia=$Data[5];

					$fface=$Data[7];

					$fdmvalue=$Data[3];

					$Pcs=$Data[18];

					$Collar=$Data[19];

					$Data=explode('~ArrayItem~',$OrderData[$x]) ;

					if ($Data[11]=="Gear")

					{

						$result=mysqli_query ($con2,"select iId from gear_master where iTeeth =$Data[2] and cItemType =\"".$Data[9]."\" and fDia =$Data[5] and cDiaType =\"".$Data[6]."\" and fFace =$Data[7] and cFaceType =\"".$Data[8]."\" and fDMValue =$Data[3] and cType =\"".$Data[4]."\"");

						if ($result)

						{

							if (mysqli_num_rows($result)>0)

							{	// Item already exist

								$row=mysqli_fetch_array($result);	

								$ItemCode=$row['iId'];

							}

							else

							{

								// New entry

								$Insertqry=mysqli_query ($con2,"Insert into gear_master values ('',\"".$Data[2]."\", \"".$Data[9]."\", \"".$Data[5]."\", \"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\") ");

								if (!$Insertqry)

								{

									$Err=true;

								}

								// Find iId

								$Findqry=mysqli_query ($con2,"select iId from gear_master where iTeeth =$Data[2] and cItemType =\"".$Data[9]."\" and fDia =$Data[5] and cDiaType =\"".$Data[6]."\" and fFace =$Data[7] and cFaceType =\"".$Data[8]."\" and fDMValue =$Data[3] and cType =\"".$Data[4]."\"");

								if ($Findqry && mysqli_num_rows($Findqry)>0)

								{	

									$row1=mysqli_fetch_array($Findqry);	

									$ItemCode=$row1['iId'];

								}

							}

						}

					}

					else if ($Data[11]=="Pinion")

					{

						$result=mysqli_query($con2,"select iId from pinion_master where iTeeth=$Data[2] and cItemType='".$Data[9]."' and fDia=$Data[5] and cDiaType='".$Data[6]."' and fFace=$Data[7] and cFaceType='".$Data[8]."' and fDMValue=$Data[3] and cType='".$Data[4]."' ");

						

						if ($result)

						{

							if (mysqli_num_rows($result)>0)

							{	// Item already exist

								$row=mysqli_fetch_array($result);

								$ItemCode=$row['iId'];

							}

							else

							{

								// New entry

								$Insertqry=mysqli_query($con2,"Insert into pinion_master values('',\"".$Data[2]."\", \"".$Data[9]."\", \"".$Data[5]."\", \"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\")");

								if (! $Insertqry)

								{

									$Err=true;

								}

								// Find iId

								$Findqry=mysqli_query($con2,"select iId from pinion_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

								if ($Findqry && mysqli_num_rows($Findqry)>0)

								{	

									$row1=mysqli_fetch_array($Findqry);

									$ItemCode=$row1['iId'];

								}

							}

						}

					}

					else if ($Data[11]=="Shaft Pinion")

					{

						$result=mysqli_query($con2,"select iId from shaft_pinion_master where iTeeth =$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\"  and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

						if ($result)

						{

							if (mysqli_num_rows($result)>0)

							{

								// Item already exist

								$row=mysqli_fetch_array($result);

								$ItemCode=$row['iId'];

							}

							else

							{	 // New entry

								$Insertqry=mysqli_query($con2,"Insert into shaft_pinion_master values ('',\"".$Data[2]."\",\"".$Data[9]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[7]."\",\"".$Data[8]."\",\"".$Data[3]."\",\"".$Data[4]."\")");

								if (! $Insertqry)

								{

									$Err=true;

								}

								// Find iId

								$Findqry=mysqli_query($con2,"select iId from shaft_pinion_master where iTeeth =$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fFace=$Data[7] and cFaceType=\"".$Data[8]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

								if ($Findqry && mysqli_num_rows($Findqry)>0)

								{	

									$row1=mysqli_fetch_array($Findqry);	

									$ItemCode=$row1['iId'];

								}

							}

						}

					}

					else if ($Data[11]=="Bevel Gear")

					{

						$result=mysqli_query($con2,"select iId from bevel_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

						if ($result)

						{

							if (mysqli_num_rows($result)>0)

							{

								// Item already exist

								$row=mysqli_fetch_array($result);

								$ItemCode=$row['iId'];

							}

							else

							{

								// New entry

								$Insertqry=mysqli_query($con2,"Insert into bevel_gear_master values ('',\"".$Data[2]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\",\"".$Data[4]."\")")or die(mysql_error());

								if (!$Insertqry)

								{

									$Err=true;

								}

								// Find iId

								$Findqry=mysqli_query ($con2,"select iId from bevel_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

								if ($Findqry && mysqli_num_rows($Findqry)>0)

								{	

									$row1=mysqli_fetch_array($Findqry);	

									$ItemCode=$row1['iId'];

								}

							}

						}

					}

					else if ($Data[11]=="Bevel Pinion")

					{

						$result=mysqli_query($con2,"select iId from bevel_pinion_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

						if ($result)

						{

							if (mysqli_num_rows($result)>0)

							{

								// Item already exist

								$row=mysqli_fetch_array($result);

								$ItemCode=$row['iId'];

							}

							else

							{

								// New entry

								$Insertqry=mysqli_query($con2,"Insert into bevel_pinion_master values ('',\"".$Data[2]."\" ,\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\" ,\"".$Data[4]."\")");

								if (! $Insertqry)

								{

									$Err=true;

								}

								 // Find iId

								$Findqry=mysqli_query ($con2,"select iId from bevel_pinion_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

								if ($Findqry && mysqli_num_rows($Findqry)>0)

								{	

									$row1=mysqli_fetch_array($Findqry);	

									$ItemCode=$row1['iId'];

								}

							}

						}			

					}

					else if ($Data[11]=="Chain Wheel")

					{

						$result=mysqli_query($con2,"select iId from chain_gear_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fPitch=$Data[10] and  cPitchType=\"".$Data[12]."\"");

						if ($result)

						{

							if (mysqli_num_rows($result)>0)

							{

								// Item already exist

								$row=mysqli_fetch_array($result);

								$ItemCode=$row['iId'];

							}

							else

							{

								// New entry

								$Insertqry=mysqli_query($con2,"Insert into chain_gear_master values ('',\"".$Data[2]."\",\"".$Data[9]."\",\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[10]."\",\"".$Data[12]."\")");

								if (! $Insertqry)

								{

									$Err=true;

								}

									// Find Id

								$Findqry=mysqli_query($con2,"select iId from chain_gear_master where iTeeth=$Data[2] and cItemType=\"".$Data[9]."\" and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fPitch=$Data[10] and  cPitchType=\"".$Data[12]."\"");

								if ($Findqry && mysqli_num_rows($Findqry)>0)

								{	

									$row1=mysqli_fetch_array($Findqry);	

									$ItemCode=$row1['iId'];

								}

							}

						}

					}

					else if ($Data[11]=="Worm Gear")

					{

						$result=mysqli_query($con2,"select iId from worm_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

						if ($result)

						{

							if (mysqli_num_rows($result)>0)

							{

								// Item already exist

								$row=mysqli_fetch_array($result);

								$ItemCode=$row['iId'];

							}

							else

							{

								// New entry

								$Insertqry=mysqli_query($con2,"Insert into worm_gear_master values ('',\"".$Data[2]."\" ,\"".$Data[5]."\",\"".$Data[6]."\",\"".$Data[3]."\" ,\"".$Data[4]."\")");

								if (! $Insertqry)

								{

									$Err=true;

								}

								 // Find iId

								$Findqry=mysqli_query ($con2,"select iId from worm_gear_master where iTeeth=$Data[2] and fDia=$Data[5] and cDiaType=\"".$Data[6]."\" and fDMValue=$Data[3] and cType=\"".$Data[4]."\"");

								if ($Findqry && mysqli_num_rows($Findqry)>0)

								{	

									$row1=mysqli_fetch_array($Findqry);	

									$ItemCode=$row1['iId'];

								}

							}

						}			

					}

					

					if ($ItemCode>0)

					{

						$result=mysqli_query ($con2,"Insert into party_order_detail (iOrderID, cItemType, iItemCode,fRate, iNoPcsRec,cItemRemarks, cPartType, cPcs, cCollar) values ((select iOrderID from party_order where cOrderCode='$OrderCode'),\"".$Data[11]."\" ,'$ItemCode',\"".$Data[1]."\",\"".$Data[0]."\",\"".$Data[16]."\", \"".$Data[17]."\", \"".$Data[18]."\", \"".$Data[19]."\")");

						if (! $result)

						{

							$Err=true;

						}

					}

					else

					{

						$Err=true;

					}

					$ItemCode=0;

				}

			}

		}

		if ($Err)

		{

			mysqli_query($con2,'rollback');

			print mysql_error();

			$Err=true;

			$ErrMsg[]="Error in Saving Purchase Order...";

		}

		else

		{

			mysqli_query ($con2,'commit');

			$host=$_SERVER['HTTP_HOST'];

			$uri=rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

			$extra="party_order.php";

			header("Location: http://$host$uri/$extra");

		}

	}

?>
<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Operations</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Party Orders</li>
			</ol>
		</div>

	</div>
</div>
</div> 


<div class="card">
<div class="card-body">
<?php
if($Err==true)
{
	die($ErrMsg);
}
?>
<Form name="frmPartyOrder" id="frmPartyOrder" method="POST">
<input type="hidden" name="auth_info" id="auth_info" value="<?=trim($_POST['auth_info'])?>">
<input type="hidden" name="hdPageMode" id="hdPageMode" value="<?=$PageMode ?>">
<input type="hidden" name="hfCurrDate" id="hfCurrDate">
<input type="hidden" name="hdMinValue"	id="hdMinValue" value="<?=$MinValue ?>">
<input type="hidden" name="hdOrderItemRate" id="hdOrderItemRate">
<input type="hidden" name="hdOrderData" id="hdOrderData" value="<?=$ItemData ?>">
<input type="hidden" id='formfocuselement' value='<?php if($PageMode=='New')echo 'cmbParty'; else echo 'cmbParty'; ?>'/>
<input type="hidden" name="hdLastOrder" id="hdLastOrder">
<input type="hidden" name="hdOrderId" id="hdOrderId" value="<?=base64_encode(base64_encode($OrderID))?>">
<input type="hidden" name="hdExtra" id="hdExtra" value="<?=$extraInfo?>">
<input type="hidden" name="hdDatabase" id="hdDatabase" value="<?=$YearPrefix?>">

    <div class="row">
	  <div class="col-md-6">
	  
	  </div>
	  <div class="col-md-6">
	  <span class="float-right">
	    <?php
		$qry=mysqli_query($con2,"select cOrderCode from party_order order by iOrderID DESC limit 0,1"); 

			if ($qry && mysqli_num_rows($qry)>0)
            {
                $row=mysqli_fetch_array($qry);
                $CodeMsg="Last Purchase Order :".$row['cOrderCode'];

			}

			else

			{

				$CodeMsg="No Purchase Order Saved before";

			}

			echo $CodeMsg;

		   ?>
		   </span>
	  </div>
	</div>
	<br>
	<div class="row">
       <div class="col-sm-4">
	      <input type="radio" name="rdb" id="rdNew" value="New" checked onClick="Switch(this.id)">&nbsp;&nbsp;New Order

		  <input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)">&nbsp;&nbsp;List Orders
	   </div>
	   <div class="col-sm-4">
	     <label for="">Order Date</label>
		 <input type="text" name="txtDate" id="txtDate" class="divDate"  class="form-control"  value="<?=$CurrDate; ?>" size="12" onFocus="this.blur()" readonly>
	   </div>
	   <div class="col-sm-4">
	    <label for="">Party Type</label>
		<label id="lblPartyType"></label>
	   </div>
    </div>
	<br>
    <div class="row">
	<!--
	<?php

				if (!empty($OrderID))

				{

			?>
      <div class="col-sm-6">
	      

			  <div id="dvPendingOrder">

				<label for="cmbOrder">Order :</label>

				<select name="cmbOrder" id="cmbOrder" class="form-control">

					<option value=""><?=str_repeat("-",10) ?>Select Order<?=str_repeat("-",10) ?></option>

					<?php

						
                        echo "SELECT distinct($cYearDatabasePath1.party_order.iOrderID), cOrderCode, dOrderDate , iPartyCode , cChallanNo, cRemarks, '' as yearprefix  FROM $cYearDatabasePath1.party_order join $cYearDatabasePath1.`party_order_detail` where $cYearDatabasePath1.party_order_detail.bDeleted<>1 and $cYearDatabasePath1.party_order.iOrderID=$cYearDatabasePath1.party_order_detail.iOrderID order by iOrderID Desc";
						$Orderqry=mysqli_query($con2,"SELECT distinct($cYearDatabasePath1.party_order.iOrderID), cOrderCode, dOrderDate , iPartyCode , cChallanNo, cRemarks, '' as yearprefix  FROM $cYearDatabasePath1.party_order join $cYearDatabasePath1.`party_order_detail` where $cYearDatabasePath1.party_order_detail.bDeleted<>1 and $cYearDatabasePath1.party_order.iOrderID=$cYearDatabasePath1.party_order_detail.iOrderID order by iOrderID Desc");

						if ($Orderqry)

						{

							if (mysqli_num_rows($Orderqry)>0)

							{

								while ($Orderrow=mysqli_fetch_array($Orderqry))

								{

									echo  "<option value=\"$Orderrow[iOrderID]~ArrayItem~$Orderrow[dOrderDate]~ArrayItem~$Orderrow[iPartyCode]~ArrayItem~$Orderrow[cChallanNo]~ArrayItem~$Orderrow[cRemarks]~ArrayItem~$Orderrow[yearprefix]\"";

									if ($OrderID==$Orderrow['iOrderID'] && $yearprefix==$Orderrow['yearprefix'])	echo  "selected";

									echo  ">$Orderrow[cOrderCode]</option>";

								}

							}

						}

					?>

				</select>

				</div>

				
	  </div>
	  <?php

				}

				?>
				-->
	  <div class="col-md-6">
	                      <label for="">Select Party :</label>
	                      <select name="cmbParty"id="cmbParty" class="form-control" onChange="selOrder(this.id)" tabindex="1" <?=($PageMode=='Edit')?'disabled':''?>>

								<option value=""><?=str_repeat("-",20) ?>Select Party<?=str_repeat("-",20) ?></option>

								<?php

									

									$result=mysqli_query ($con2,"select iPartyID as PartyID , cPartyName ,cPartType  from party_master order by cPartyName");

									if ($result && mysqli_num_rows($result)>0)

									{

										while ($row=mysqli_fetch_array($result))

										{

											echo  "<option value=\"$row[PartyID]~ArrayItem~$row[cPartType]\"";

											if ( $PartyCode==$row['PartyID']) echo  "selected";

											echo  ">$row[cPartyName]</option>";

										}

									}

								?>

							</select>
	  </div>
    </div>
    <div class="row">
	  <div class="col-sm-4">
	                        <label for="cmbItem">Item Type :</label><br>

							<select name="cmbItem" id="cmbItem"  class="form-control" onChange="CallDiv()" tabindex="2">

								<option value="">---------Select Item--------</option>

								<?php

								   

								   $Itemqry=mysqli_query ($con2,"select * from item_master");

								   if ($Itemqry && mysqli_num_rows($Itemqry)>0)

								   {

									   while ($Itemrow=mysqli_fetch_array($Itemqry))

									   {

										   if ($Itemrow['cItemCode']=="Gear")

										   {

											   echo  "<option value=\"$Itemrow[cItemCode]\" selected>$Itemrow[cItemName]</option>";

										   }

										   else

										   {

												echo  "<option value=\"$Itemrow[cItemCode]\">$Itemrow[cItemName]</option>";

										   }

									   }

								   } 

								?>

							</select>
	  </div>
	  <div class="col-sm-4">
	   <label for="txtChallan">Party Challan No :</label>
             <input type="text" name="txtChallan" id="txtChallan" class="form-control"  value="<?=$ChallanNo; ?>" tabindex="3">

	  </div>
	  <div class="col-sm-4">
	    <input type="button" name="btnSpecialItem" id="btnSpecialItem" value="Special Item" style="display:<?=($PageMode=='Edit')?'':'none'?>;" class="btn btn-light float-right" onclick="ShowSpecialItem()">
	  </div>
	</div>
    	
	

							 <div id="dvgear">

								<fieldset style="width:100%;border:1px solid #A487A7;padding:20px" >
                                <legend>Gear</legend>
								<div  class="row">
									<div class="col-sm-6">
									   <label for="cmbGear">Gear</label>
										<select name="cmbGear" id="cmbGear" class="form-control" onChange="selGear(this.id)" tabindex="4">

													<option value=""><?=str_repeat("-",20) ?>  Select Gear  <?=str_repeat("-",20) ?></option>

													<?php

													   //require ('../general/dbconnect.php');

													   $Gearqry=mysqli_query ($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fFace , cFaceType , fDMValue , cType from gear_master order by iTeeth, cItemType");

													   if ($Gearqry && mysqli_num_rows($Gearqry)>0)

													   {

														   while ($Gearrow=mysqli_fetch_array($Gearqry))

														   {

															 //  echo  "<option value=\"$Gearrow[iId]~ArrayItem~$Gearrow[iTeeth]~ArrayItem~$Gearrow[cItemType]~ArrayItem~$Gearrow[fDia]~ArrayItem~$Gearrow[cDiaType]~ArrayItem~$Gearrow[fFace]~ArrayItem~$Gearrow[cFaceType]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]\">$Gearrow[iTeeth] teeth"."&nbsp;"."$Gearrow[cItemType] Gear"."&nbsp;Dia"."$Gearrow[fDia]"."&nbsp;"."$Gearrow[cDiaType]"."&nbsp;Face"."$Gearrow[fFace]"."&nbsp;"."$Gearrow[cFaceType]"."&nbsp;"."$Gearrow[fDMValue]"."&nbsp;"."$Gearrow[cType]</option>";

															   echo  "<option value=\"$Gearrow[iId]~ArrayItem~$Gearrow[iTeeth]~ArrayItem~$Gearrow[cItemType]~ArrayItem~$Gearrow[fDia]~ArrayItem~$Gearrow[cDiaType]~ArrayItem~$Gearrow[fFace]~ArrayItem~$Gearrow[cFaceType]~ArrayItem~$Gearrow[fDMValue]~ArrayItem~$Gearrow[cType]\">$Gearrow[cName] $Gearrow[fDMValue] $Gearrow[cType]</option>";

														   }

													   }

													?>

												</select>
	                        </div>
							 <div class="col-sm-6">
							   <label for="cmbGearPartyType">Party type</label>
								   <select name="cmbGearPartyType" id="cmbGearPartyType" class="form-control" tabindex="5">
										<option value="Expeller">Expeller</option>
										<option value="PowerPress">Power Press</option>
								   </select>
							 </div>
	                 </div>

									<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
                                       <tr>

											<td>

												<label for="txtGearTeeth">Teeth :</label>
                                                <input type="text" name="txtGearTeeth" id="txtGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="6">
                                                
												<label for="cmbGearType">Type :</label>
                                                <select name="cmbGearType" id="cmbGearType" class="form-control" tabindex="7">

													<option value="Plain">Plain</option>

													<option value="Helical">Helical</option>

													<option value="Spur Hobb">Spur Hobb</option>

												</select>
                                                
											</td>

											

											<td>
                                                <label for="txtGearDia">Dia :</label>
												<input type="text" name="txtGearDia" class="form-control" id="txtGearDia"  tabindex="8" onblur="onlyGrams(this.id)">													
                                                <br>
												<select name="cmbGearDiaType" id="cmbGearDiaType" class="form-control">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>

												<label for="txtGearFace">Face :</label>
												<input type="text" name="txtGearFace" id="txtGearFace" class="form-control" tabindex="10">											
                                                <br>
												<select name="cmbGearFaceType" id="cmbGearFaceType" class="form-control" tabindex="11">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>
                                                <label for="txtGearProcessing">DP/Module :</label>
												<input type="text" name="txtGearProcessing" id="txtGearProcessing" class="form-control" tabindex="12" >											
                                                <br>
												<select name="cmbGearProcessingType" id="cmbGearProcessingType" class="form-control" tabindex="13">

													<option value="DP">DP</option>

													<option value="Module">Module</option>

												</select>

											</td>

											<td>
											<label for="txtGearPcs">	No. of Pcs :</label>
                                            <input type="text" name="txtGearPcs" id="txtGearPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="14">											
                                             
											  <label for="cmbGearCal">PT/PCS</label>
											  <select name="cmbGearCal" id="cmbGearCal" class="form-control" tabindex="15">

													<option value="PT">PT</option>

													<option value="PCS">PCS</option>

												</select>
											</td>

											


										</tr>
										<tr>
										  <td colspan="5">
										  <?php
										  if($__add==true)
										  {
										  ?>
										  <input type="Button" name="btnAddGear" id="btnAddGear" value="Add" class="btn btn-primary waves-effect waves-light float-right" onkeydown="CheckBlankOrder(this.id)" onClick="CheckBlankOrder(this.id)" tabindex="16">
										  <?php
										  }
										  ?>
										 </td>
										</tr>

									</table>	  

								</fieldset>

							 </div>	

							 <div id="dvpinion"  style="display:none;">

								<fieldset style="width:100%;border:1px solid #A487A7;padding:20px">
                                    <legend class="legend">Pinion</legend>
                                    <div class="row">
									   <div class="col-sm-6">
									      <select name="cmbPinion" id="cmbPinion" class="form-control" onchange="selPinion(this.id)" tabindex="17">

													<option value=""><?=str_repeat("-",20) ?>  Select Pinion  <?=str_repeat("-",20) ?></option>

													<?php

													   $Pinionqry=mysqli_query ($con2,"select concat(iTeeth ,' teeth ', cItemType ,' dia ',fDia ,' ',cDiaType ,' face ', fFace ,' ',cFaceType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fFace , cFaceType , fDMValue , cType from pinion_master order by iTeeth, cItemType");

													   if ($Pinionqry && mysqli_num_rows($Pinionqry)>0)

													   {

														   while ($Pinionrow=mysqli_fetch_array($Pinionqry))

														   {

															    echo  "<option value=\"$Pinionrow[iId]~ArrayItem~$Pinionrow[iTeeth]~ArrayItem~$Pinionrow[cItemType]~ArrayItem~$Pinionrow[fDia]~ArrayItem~$Pinionrow[cDiaType]~ArrayItem~$Pinionrow[fFace]~ArrayItem~$Pinionrow[cFaceType]~ArrayItem~$Pinionrow[fDMValue]~ArrayItem~$Pinionrow[cType]\">$Pinionrow[cName] $Pinionrow[fDMValue] $Pinionrow[cType]</option>";
																

														   }

													   }

													?>

												</select>

												
									   </div>
									   <div class="col-sm-6">
									     <select name="cmbPinionPartyType" id="cmbPinionPartyType" class="form-control" tabindex="18">

													<option value="Expeller">Expeller</option>

													<option value="PowerPress">Power Press</option>

												</select>
									   </div>
									</div>
									<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">

										

										
										<tr>

											<td>

												<label for="txtPinionTeeth">Teeth :</label>
												<input type="text" name="txtPinionTeeth" id="txtPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="19">
                                                <label for="txtPinionTeeth">Type :</label>
												<select name="cmbPinionType" id="cmbPinionType" class="form-control" tabindex="20">

													<option value="Plain">Plain</option>

													<option value="Helical">Helical</option>

													<option value="Spur Hobb">Spur Hobb</option>

												</select>

											</td>

											

											<td>

												<label for="txtPinionDia">Dia :</label>
												<input type="text" name="txtPinionDia" id="txtPinionDia" class="form-control" tabindex="21" onblur="onlyGrams(this.id)">																<br>
												
												<select name="cmbPinionDiaType" id="cmbPinionDiaType" class="form-control" tabindex="22">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>
                                                <label for="">Face :</label>
												<input type="text" name="txtPinionFace" id="txtPinionFace" class="form-control" tabindex="23">
												+
												<label for="txtPinionCollar">Collar :</label>
												 <input type="text" name="txtPinionCollar" id="txtPinionCollar" class="form-control" tabindex="24">												
                                                <br>
												<select name="cmbPinionFaceType" id="cmbPinionFaceType" class="form-control" tabindex="25">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>

												<label for="txtPinionProcessing">DP/Module :</label>
												<input type="text" name="txtPinionProcessing" id="txtPinionProcessing" class="form-control" tabindex="26">												
                                               <br>
												<select name="cmbPinionProcessingType" class="form-control" id="cmbPinionProcessingType" tabindex="27">

													<option value="DP">DP</option>

													<option value="Module">Module</option>

												</select>

											</td>

											<td>

												<label for="txtPinionPcs">No. of Pcs :</label>
												<input type="text" name="txtPinionPcs" id="txtPinionPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="28">

											   

												<label for="cmbPinionCal">PT/PCS:</label>
												<select name="cmbPinionCal" id="cmbPinionCal" class="form-control" tabindex="29">

													<option value="PT">PT</option>

													<option value="PCS">PCS</option>

												</select>

 											</td>

											

										</tr>
                                        <tr>
										 <td colspan="5">
										   <input type="Button" name="btnAddPinion" id="btnAddPinion" value="Add" class="btn btn-primary waves-effect waves-light float-right" onkeydown="CheckBlankOrder(this.id)" onClick="CheckBlankOrder(this.id)"  tabindex="30">
										 </td>
										</tr>
									</table>

								</fieldset>

							 </div>

							 <div id="dvshaftpinion" style="display:none;">

								<fieldset style="width:100%;border:1px solid #A487A7;padding:20px">
                                
									<legend class="legend">Shaft Pinion</legend>
                                    <div class="row">
									  <div class="col-sm-6">
									  <select name="cmbShaftPinion" id="cmbShaftPinion" class="form-control" onchange="selShaftPinion(this.id)" tabindex="31">

													<option value=""><?=str_repeat("-",20) ?>  Select Shaft Pinion  <?=str_repeat("-",20) ?></option>

													<?php

													$result=mysqli_query ($con2,"select concat(iTeeth, ' teeth ',cItemType ,' dia ',fDia ,' ',cDiaType , ' face ',fFace ,' ', cFaceType) as cName, iId, iTeeth,cItemType, fDia, cDiaType, fFace , cFaceType, fDMValue , cType from shaft_pinion_master order by iTeeth, cItemType");

													if ($result && mysqli_num_rows($result)>0)

													{

														while ($row=mysqli_fetch_array($result))

														{

															//echo  "<option value=\"$row[iId]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[cItemType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]\">$row[iTeeth] teeth"." Shaft Pinion&nbsp; Dia $row[fDia]&nbsp;$row[cDiaType] "."$row[cItemType]"."&nbsp;Face "."$row[fFace]"."&nbsp;"."$row[cFaceType]"."&nbsp;"."$row[fDMValue]"."&nbsp;"."$row[cType]</option>";

															echo  "<option value=\"$row[iId]~ArrayItem~$row[iTeeth]~ArrayItem~$row[fDia]~ArrayItem~$row[cDiaType]~ArrayItem~$row[cItemType]~ArrayItem~$row[fFace]~ArrayItem~$row[cFaceType]~ArrayItem~$row[fDMValue]~ArrayItem~$row[cType]\">$row[cName] $row[fDMValue] $row[cType]</option>";	

														}

													}

													?>

												</select>

												
									  </div>
									  <div class="col-sm-6">
									            <select name="cmbShaftPinionPartyType" id="cmbShaftPinionPartyType" class="form-control" tabindex="32">

													<option value="Expeller">Expeller</option>

													<option value="PowerPress">Power Press</option>

												</select>
									  </div>
									</div>
									<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
                                      <tr>

											<td>

												<label for="txtShaftPinionTeeth">Teeth :</label>
												<input type="text" name="txtShaftPinionTeeth" id="txtShaftPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="33">															
                                                <label for="cmbShaftPinionType">Type : </label>
												<select name="cmbShaftPinionType" id="cmbShaftPinionType" class="form-control" tabindex="34">
													<option value="Plain">Plain</option>
													<option value="Helical">Helical</option>
													<option value="Spur Hobb">Spur Hobb</option>

												</select>

											</td>

											

											<td>

												<label for="txtShaftPinionDia">Dia :</label>
												<input type="text" name="txtShaftPinionDia" id="txtShaftPinionDia" class="form-control" tabindex="35" onblur="onlyGrams(this.id)">
												<br>
												<select name="cmbShaftPinionDiaType" id="cmbShaftPinionDiaType" class="form-control" tabindex="36">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>

												<label for="">Face :</label>
												<input type="text" name="txtShaftPinionFace" id="txtShaftPinionFace" class="form-control" tabindex="37">													

												<br>
												<select name="cmbShaftPinionFaceType" id="cmbShaftPinionFaceType" class="form-control" tabindex="38">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>

												<label for="txtShaftPinionProcessing">DP/Module :</label>
												

												<input type="text" name="txtShaftPinionProcessing" id="txtShaftPinionProcessing" class="form-control" tabindex="39">
												<br>
												<select name="cmbShaftPinionProcessingType" id="cmbShaftPinionProcessingType" class="form-control" tabindex="40">

													<option value="DP">DP</option>

													<option value="Module">Module</option>

												</select>

											</td>

											<td>

												<label for="txtShaftPinionPcs">No.of Pcs :</label>
												<input type="text" name="txtShaftPinionPcs" id="txtShaftPinionPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="41">
												<label for="cmbShaftPinionCal">PT/PCS:</label>
												<select name="cmbShaftPinionCal" id="cmbShaftPinionCal" class="form-control" tabindex="42">

													<option value="PCS">PCS</option>

													<option value="PT">PT</option>

												</select>

											</td>

											

											 

											 

										</tr>
										<tr>
										  <td colspan="5">

												<input type="Button" name="btnAddShaftPinion" id="btnAddShaftPinion" value="Add" class="btn btn-primary waves-effect waves-light float-right" onkeydown="CheckBlankOrder(this.id)" onClick="CheckBlankOrder(this.id)"  tabindex="43">

											 </td>
										</tr>

									</table>

								</fieldset>

							 </div>

							 <div id="dvbevelgear" style="display:none;">

								<fieldset style="width:100%;border:1px solid #A487A7;padding:20px">

									<legend class="legend">Bevel Gear</legend>
									<div class="row">
									 <div class="col-sm-6">
									    <select name="cmbBevelGear" id="cmbBevelGear" class="form-control" onChange="selBevelGear(this.id)" tabindex="44">

													<option value=""><?php print str_repeat("-",20) ?>  Select Bevel Gear  <?php print str_repeat("-",20) ?></option>

													<?php

														//require ('../general/dbconnect.php');

														

														$BevelGearqry=mysqli_query ($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from bevel_gear_master order by iTeeth");

														if ($BevelGearqry && mysqli_num_rows($BevelGearqry)>0)

														{

															while ($BevelGearrow=mysqli_fetch_array($BevelGearqry))

															{

																//echo  "<option value=\"$BevelGearrow[iId]~ArrayItem~$BevelGearrow[iTeeth]~ArrayItem~$BevelGearrow[fDia]~ArrayItem~$BevelGearrow[cDiaType]~ArrayItem~$BevelGearrow[fDMValue]~ArrayItem~$BevelGearrow[cType]\">$BevelGearrow[iTeeth]&nbsp;Teeth Bevel Gear Dia $BevelGearrow[fDia]&nbsp;$BevelGearrow[cDiaType]"."&nbsp;"."$BevelGearrow[fDMValue]"."&nbsp;"."$BevelGearrow[cType]</option>";

																echo  "<option value=\"$BevelGearrow[iId]~ArrayItem~$BevelGearrow[iTeeth]~ArrayItem~$BevelGearrow[fDia]~ArrayItem~$BevelGearrow[cDiaType]~ArrayItem~$BevelGearrow[fDMValue]~ArrayItem~$BevelGearrow[cType]\">$BevelGearrow[cName] $BevelGearrow[fDMValue] $BevelGearrow[cType]</option>";

															}

														}

													?>

												</select>

												
									 </div>
									 <div class="col-sm-6">
									   <select name="cmbBevelGearPartyType" class="form-control" id="cmbBevelGearPartyType" tabindex="45">

													<option value="Expeller">Expeller</option>

													<option value="PowerPress">Power Press</option>

									   </select>
									 </div>
									</div>

									<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
                                       <tr>

											<td>

												<label for="txtBevelGearTeeth">Teeth :</label>
												<input type="text" name="txtBevelGearTeeth" id="txtBevelGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="46">
											</td>

											<td>
											<label for="txtBevelGearDia">Dia :</label>
											<input type="text" name="txtBevelGearDia" id="txtBevelGearDia" class="form-control" tabindex="47" onblur="onlyGrams(this.id)">
                                            <br>											

												<select name="cmbBevelGearDiaType" id="cmbBevelGearDiaType" class="form-control" tabindex="48">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>

												<label for="txtBevelGearProcessing">DP/Module :</label>
												<input type="text" name="txtBevelGearProcessing" id="txtBevelGearProcessing" class="form-control" tabindex="49">
                                                 <br>
												<select name="cmbBevelGearProcessingType" id="cmbBevelGearProcessingType" class="form-control" tabindex="50">

													<option value="DP">DP</option>

													<option value="Module">Module</option>

												</select>

											</td>

											<td>

												<label for="txtBevelGearPcs">No. of Pcs :</label>
												<input type="text" name="txtBevelGearPcs" id="txtBevelGearPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="51">
												<label for="cmbBevelGearCal">PT/PCS:</label>
												<select name="cmbBevelGearCal" id="cmbBevelGearCal" class="form-control" tabindex="52">

													<option value="PT">PT</option>

													<option value="PCS">PCS</option>

												</select>

											</td>

											
											

										</tr>
										<tr>
										  <td colspan="4">

												<input type="Button" name="btnAddBevelGear" id="btnAddBevelGear" value="Add" class="btn btn-primary waves-effect waves-light float-right" onkeydown="CheckBlankOrder(this.id)" onClick="CheckBlankOrder(this.id)"  tabindex="53">

										  </td>
										</tr>

									</table>

								</fieldset>

							 </div>

							 <div id="dvbevelpinion" style="display:none;">

								<fieldset style="width:100%;border:1px solid #A487A7;padding:20px">

									<legend class="legend">Bevel Pinion</legend>
									<div class="row">
									  <div class="col-md-6">
									    <select name="cmbBevelPinion" id="cmbBevelPinion" class="form-control" onChange="selBevelPinion(this.id)" tabindex="54">

													<option value=""><?=str_repeat("-",20) ?>  Select Bevel Pinion  <?=str_repeat("-",20) ?></option>

													<?php

														

														$BevelPinionqry=mysqli_query ($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from bevel_pinion_master order by iTeeth");

														if ($BevelPinionqry && mysqli_num_rows($BevelPinionqry)>0)

														{

															while ($BevelPinionrow=mysqli_fetch_array($BevelPinionqry))

															{

																//echo  "<option value=\"$BevelPinionrow[iId]~ArrayItem~$BevelPinionrow[iTeeth]~ArrayItem~$BevelPinionrow[fDia]~ArrayItem~$BevelPinionrow[cDiaType]~ArrayItem~$BevelPinionrow[fDMValue]~ArrayItem~$BevelPinionrow[cType]\">$BevelPinionrow[iTeeth]"." Teeth&nbsp;Bevel Pinion Dia $BevelPinionrow[fDia]&nbsp; $BevelPinionrow[cDiaType] &nbsp;"."$BevelPinionrow[fDMValue]"."&nbsp;"."$BevelPinionrow[cType]</option>";							

																echo  "<option value=\"$BevelPinionrow[iId]~ArrayItem~$BevelPinionrow[iTeeth]~ArrayItem~$BevelPinionrow[fDia]~ArrayItem~$BevelPinionrow[cDiaType]~ArrayItem~$BevelPinionrow[fDMValue]~ArrayItem~$BevelPinionrow[cType]\">$BevelPinionrow[cName] $BevelPinionrow[fDMValue] $BevelPinionrow[cType]</option>";							

															}

														}

													?>

												</select>

												
									  </div>
									  <div class="col-md-6">
									   <select name="cmbBevelPinionPartyType" class="form-control" id="cmbBevelPinionPartyType" tabindex="55">

													<option value="Expeller">Expeller</option>

													<option value="PowerPress">Power Press</option>

												</select>
									  </div>
									</div>

									<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">

										

										<tr>

											<td>
                                                <label for="txtBevelPinionTeeth">Teeth :</label>
												<input type="text" name="txtBevelPinionTeeth" id="txtBevelPinionTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="56">

											</td>

											<td>

												<label for="txtBevelPinionDia">Dia :</label>
												

												<input type="text" name="txtBevelPinionDia" id="txtBevelPinionDia" class="form-control" tabindex="57" onblur="onlyGrams(this.id)">	
                                                 <br>												

												<select name="cmbBevelPinionDiaType" id="cmbBevelPinionDiaType" class="form-control" tabindex="58">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>

												<label for="txtBevelPinionProcessing">DP/Module :</label>
												<input type="text" name="txtBevelPinionProcessing" id="txtBevelPinionProcessing" class="form-control" tabindex="59">
												<br>

												<select name="cmbBevelPinionProcessingType" class="form-control" id="cmbBevelPinionProcessingType" tabindex="60">

													<option value="DP">DP</option>

													<option value="Module">Module</option>

												</select>

											</td>

											<td>
                                             <label for="txtBevelPinionPcs">No. of Pcs :</label>
												<input type="text" name="txtBevelPinionPcs" id="txtBevelPinionPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="61">	
												<label for="cmbBevelPinionCal">PT/PCS:</label>
                                                <select name="cmbBevelPinionCal" id="cmbBevelPinionCal" class="form-control" tabindex="62">

													<option value="PT">PT</option>

													<option value="PCS">PCS</option>

												</select>												

											</td>

											

											

										</tr>
										<tr>
										<td  colspan="4">

												<input type="Button" name="btnAddBevelPinion" id="btnAddBevelPinion" value="Add" class="btn btn-primary waves-effect waves-light float-right" onkeydown="CheckBlankOrder(this.id)" onClick="CheckBlankOrder(this.id)" tabindex="63">

											</td>
										</tr>

									</table>

								</fieldset>

							 </div>

							 <div id="dvchainwheel" style="display:none;">

								<fieldset style="width:100%;border:1px solid #A487A7;padding:20px">

									<legend class="legend">Chain Wheel</legend>
									<div class="row">
									  <div class="col-md-6">
									    <select name="cmbChainWheel" id="cmbChainWheel" class="form-control" onchange="selChainGear(this.id)" tabindex="64">

													<option value=""><?=str_repeat("-",20) ?>  Select Chain Wheel  <?=str_repeat("-",20) ?></option>

													<?php

														

														$ChainGearqry=mysqli_query($con2,"select concat(iTeeth, ' teeth (',cItemType ,' ) dia ',fDia , ' ',cDiaType , ' pitch ', fPitch ,' ', cPitchType ) as cName ,iId , iTeeth , cItemType , fDia , cDiaType , fPitch , cPitchType  from chain_gear_master order by iTeeth");

														if ($ChainGearqry && mysqli_num_rows($ChainGearqry)>0)

														{

															while ($ChainGearrow=mysqli_fetch_array($ChainGearqry))

															{

																
																echo  "<option value=\"$ChainGearrow[iId]~ArrayItem~$ChainGearrow[iTeeth]~ArrayItem~$ChainGearrow[cItemType]~ArrayItem~$ChainGearrow[fDia]~ArrayItem~$ChainGearrow[cDiaType]~ArrayItem~$ChainGearrow[fPitch]~ArrayItem~$ChainGearrow[cPitchType]\">$ChainGearrow[cName]</option>";

															}

														}

													?>

												</select>

												
									  </div>
									  <div class="col-md-6">
									    <select name="cmbChainGearPartyType" class="form-control" id="cmbChainGearPartyType" tabindex="65">

													<option value="Expeller">Expeller</option>

													<option value="PowerPress">Power Press</option>

										</select>
									  </div>
									</div>

									<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">

										

										<tr>

											<td>

												<label for="txtChainWheelTeeth">Teeth :</label>
												<input type="text" name="txtChainWheelTeeth" id="txtChainWheelTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="66">															
                                                <label for="cmbChainWheelType">Type :</label>
												<select name="cmbChainWheelType" id="cmbChainWheelType" class="form-control" tabindex="67">

													<option value="Single">Single</option>

													<option value="Duplex">Duplex</option>

													<option value="Triplex">Triplex</option>

													<option value="Fourplex">Fourplex</option>

												</select>
											</td>

											

											<td>

												<label for="txtChainWheelDia">Dia :</label>
												<input type="text" name="txtChainWheelDia" id="txtChainWheelDia" class="form-control" tabindex="68" onblur="onlyGrams(this.id)"><br>
												<select name="cmbChainWheelDia" id="cmbChainWheelDia" class="form-control" tabindex="69">
                                                    <option value="inches">inches</option>
                                                    <option value="mm">mm</option>
												</select>

											</td>

											<td>

												<label for="txtChainWheelPitch">Pitch :</label>
												<input type="text" name="txtChainWheelPitch" id="txtChainWheelPitch" class="form-control" tabindex="70">
												<br>
												<select name="cmbChainWheelPitchType" id="cmbChainWheelPitchType" class="form-control" tabindex="71">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>

												<label for="txtChainWheelPcs">No. of Pcs :</label>
												<input type="text" name="txtChainWheelPcs" id="txtChainWheelPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="72">
												<label for="cmbChainWheelCal">PT/PCS:</label>
												<select name="cmbChainWheelCal" id="cmbChainWheelCal" class="form-control" tabindex="73">

													<option value="PT">PT</option>

													<option value="PCS">PCS</option>

												</select>

											</td>

											</tr>
                                            <tr>
											  <td colspan="4">

												<input type="Button" name="btnAddChainWheel" id="btnAddChainWheel" value="Add" class="btn btn-primary waves-effect waves-light float-right" onkeydown="CheckBlankOrder(this.id)" onClick="CheckBlankOrder(this.id)" tabindex="74">	

											</td>
											</tr>
									</table>

								</fieldset>

							 </div>

							 <div id="dvwormgear" style="display:none;">

							 	<fieldset style="width:100%;border:1px solid #A487A7;padding:20px">	

									<legend>Worm Gear</legend>							 
                                    <div class="row">
									  <div class="col-sm-6">
									     <select name="cmbWormGear" id="cmbWormGear" class="form-control" onchange="selWormGear(this.id)" tabindex="75">

													<option value=""><?=str_repeat("-",20) ?> Select Worm Gear <?=str_repeat("-",20) ?> </option>

													<?php

														//require ('../general/dbconnect.php');

														$WormGearqry=mysqli_query ($con2,"select concat(iTeeth, ' teeth dia ',fDia, ' ',cDiaType) as cName, iId, iTeeth, fDia,cDiaType,fDMValue,cType from worm_gear_master order by iTeeth");

														if ($WormGearqry && mysqli_num_rows($WormGearqry)>0)

														{

															while ($WormGearrow=mysqli_fetch_array($WormGearqry))

															{

																echo  "<option value=\"$WormGearrow[iId]~ArrayItem~$WormGearrow[iTeeth]~ArrayItem~$WormGearrow[fDia]~ArrayItem~$WormGearrow[cDiaType]~ArrayItem~$WormGearrow[fDMValue]~ArrayItem~$WormGearrow[cType]\">$WormGearrow[cName] $WormGearrow[fDMValue] $WormGearrow[cType]</option>";							

															}

														}

													?>

												</select>
									  </div>
									  <div class="col-sm-6">
									    <!--<select name="cmbWormGearPartyType" id="cmbWormGearPartyType">

													<option value="Expeller">Expeller</option>

													<option value="PowerPress">Power Press</option>

												</select>	-->
									  </div>
									</div>
									<table class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">

										<tr>

											<td>

												<label for="txtWormGearTeeth">Teeth :</label>
												<input type="text" name="txtWormGearTeeth" id="txtWormGearTeeth" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="76">
                                            </td>

											<td>
												<label class="txtWormGearDia">Dia :</label>
												<input type="text" name="txtWormGearDia" id="txtWormGearDia" class="form-control" tabindex="77"><br>																

												<select name="cmbWormGearDiaType" id="cmbWormGearDiaType" class="form-control" tabindex="78">

													<option value="inches">inches</option>

													<option value="mm">mm</option>

												</select>

											</td>

											<td>

												<label for="txtWormGearProcessing">DP/Module :</label>
												<input type="text" name="txtWormGearProcessing" id="txtWormGearProcessing" class="form-control"  tabindex="79">
                                                <br>
												<select name="cmbWormGearProcessingType" id="cmbWormGearProcessingType" class="form-control" tabindex="80">

													<option value="DP">DP</option>

													<option value="Module">Module</option>

												</select>

											</td>

											<td>

												<label for="txtWormGearPcs">No. of Pcs :</label>
                                                <input type="text" name="txtWormGearPcs" id="txtWormGearPcs" class="form-control" onKeyDown="OnlyInt1(this.id)" tabindex="81">	
                                                <label for="cmbWormGearCal">PT/PCS:</label>
												<select name="cmbWormGearCal" id="cmbWormGearCal"  class="form-control" tabindex="82">

													<option value="PCS">PCS</option>

													<option value="PT">PT</option>

												</select>
                                                												

											</td>

											

											

										</tr>
										<tr>
										    <td colspan="4">

												<input type="Button" name="btnAddWormGear" id="btnAddWormGear" value="Add" class="btn btn-primary waves-effect waves-light float-right" onkeydown="CheckBlankOrder(this.id)" onClick="CheckBlankOrder(this.id)" tabindex="83">

											</td>
										</tr>

							 		</table>

							 	</fieldset>

							 </div>

						

					<div id="dvOrderItem">

					</div>
					<br>
                    <div class="row">
					  <div class="col-sm-8">
					    <label for="txtRemarks">Remarks :</label>
						<textarea name="txtRemarks" id="txtRemarks" class="form-control" tabindex="84" ><?=$Remarks; ?></textarea>
					  </div>
					  <div class="col-sm-4"></div>
					</div>
					<br>
					<div class="row">
					  <div class="col-sm-12">
					            <span class="float-right">
								 <?php
								  if($__add==true && $__update==true)
								  {
								  ?>
					            <button type="button" name="btnOrder" id="btnOrder"  class="btn btn-primary waves-effect waves-light"  tabindex="85">	Save</button>	
                                 <?php
								  }
								 ?>
								<input type="button" name="btnReset" id="btnReset" value="Reset" class="btn btn-light"   onKeyDown="EnterKeyClick(this.id)" onClick="resetForm()" onBlur="focusFirstElement();" tabindex="86">
								</span>
					  </div>
					</div>
					<br>
					<div class="row">
					  <div class="col-sm-12" id="dvCount" style="display:none;">
					     
					  </div>
					  
					  <div class="col-sm-12" id="dvLastOrder">
					     
					  </div>
					</div>
					

			

</form>
</div>
</div>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script type="text/javascript" language="javascript" src="../js/party_order.js"></script>
<script>
$("#document").ready(function(){
	PageLoad2();
})
</script>
<div class="modal fade" id="modalSpecial" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title mt-0" id="exampleModalScrollableTitle">Special Item</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modalBody">
			<!--modal-body-content here---->
			 </div>
			
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>