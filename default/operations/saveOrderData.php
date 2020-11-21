<?php
require_once("../../config.php");
$Err=false;
$ErrMsg="";

//print_r($_POST);
if(empty(trim($_POST['auth_info'])))
{
	$Err=true;
	$ErrMsg="Invalid Request";
}
else if(empty(trim($_POST['cmbParty'])))
{
	$Err=true;
	$ErrMsg="Please select Party Name...";
}
else if(empty(trim($_POST['hdOrderData'])))
{
	$Err=true;
	$ErrMsg="Please Place an Order...";
}
else if(empty(trim($_POST['txtDate'])))
{
	$Err=true;
	$ErrMsg="Please Select Date...";
}
else
{
	$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
	$__view=$auth_info[0]; //view
	$__add=$auth_info[1]; //add 
	$__update=$auth_info[2]; //update
	$__delete=$auth_info[3]; //delete
}
       if ($Err==false)
        {

			$PartyCodeType=explode("~ArrayItem~",$_POST['cmbParty']);
            $PartyCode=$PartyCodeType[0];
            $ItemData=$_POST['hdOrderData'];
		    $MinValue=$_POST['hdMinValue'];
            $dArr=explode("/",$_POST['txtDate']);
            $date="$dArr[2]-$dArr[1]-$dArr[0]";
            $Month=$dArr[1];
            $OdDate=$dArr[0];
            $PartyChallanNo=$_POST['txtChallan'];
		    $Remarks=$_POST['txtRemarks'];
            mysqli_query($con2,"begin");
            if (trim($_POST['hdPageMode']=="Edit"))
            {
				
                $hdOrderId=base64_decode(base64_decode(trim($_POST['hdOrderId'])));
                $YearPrefix=trim($_POST['hdDatabase']);
				$result=mysqli_query ($con2,"update {$Yearstring}party_order set dOrderDate=\"".$date."\",cChallanNo=\"".$PartyChallanNo."\",cRemarks=\"".$Remarks."\" where iOrderID=\"$hdOrderId\"");
                if (! $result)
                {

					$Err=true;
                    $ErrMsg="Error in updating party order data";					

				}
				
				

				$OrderData=explode('~Array~',$ItemData);

				for ($x=0;$x<sizeof($OrderData);$x++)
                {
                    $Data=explode('~ArrayItem~',$OrderData[$x]) ;
                    if ($Data[14]=="Deleted")
                    {
                        $result=mysqli_query ($con2,"Update {$YearPrefix}party_order_detail set bDeleted='1' where iOrderID=\"$hdOrderId\" and cItemType=\"".$Data[11]."\" and iItemCode=\"".$Data[15]."\"");
                        if (! $result)
                        {
                            $Err=true;
							$ErrMsg="Error in updating party order details";
                        }
					}
                    else if ($Data[14]=="Edit")
                    {
                       $result=mysqli_query($con2,"Update {$YearPrefix}party_order_detail set fRate=\"".$Data[1]."\" ,cItemRemarks=\"".$Data[16]."\", iNoPcsRec =\"".$Data[0]."\" where iOrderID=\"$hdOrderId\" and cItemType=\"".$Data[11]."\" and iItemCode=\"".$Data[15]."\"");
                        if (!$result)
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

							$result=mysqli_query ($con2,"Insert into {$YearPrefix}party_order_detail (iOrderID, cItemType, iItemCode,fRate, iNoPcsRec,cItemRemarks , cPartType, cPcs, cCollar) values (\"$hdOrderId\",\"".$Data[11]."\" ,'$ItemCode',\"".$Data[1]."\",\"".$Data[0]."\",\"".$Data[16]."\", \"".$Data[17]."\", \"".$Data[18]."\", \"".$Data[19]."\")");

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
           //new order starts
			else

			{
               
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
                
				if(! $result)

				{

					$Err=true;
					$ErrMsg="Insert into party_order values('','$OrderCode','$date','$PartyCode',\"".$PartyChallanNo."\",'$MinValue',\"".$Remarks."\")";

				}

                

				$OrderData=explode('~Array~',$ItemData);

				for ($x=0;$x<count($OrderData);$x++)

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
		if($Err==true)
		{
			mysqli_query($con2,"rollback");
			$ErrMsg="error in updating data";
		}
		else
		{
			mysqli_query($con2,"commit");
			$ErrMsg="data updated successfully";
		}
		$response_array['error']=$Err;
		$response_array['error_msg']=$ErrMsg;
		echo json_encode($response_array);
?>
