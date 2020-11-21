<?php
require_once("../../config.php");
$Err=false;
$ErrMsg="";
$focusid="";

if(empty(trim($_POST['auth_info'])))
{
	$Err=true;
	$ErrMsg="Invalid request";
}
else if(empty(trim($_POST['cmbParty'])))
{
	$Err=true;
	$ErrMsg="Invalid Requst";
}
else if(empty(trim($_POST['txtDeliveryChallan'])))
{
	$Err=true;
	$ErrMsg="Please enter Delivery Challan No...";
}
else if(empty(trim($_POST['txtFromDate'])))
{
	$Err=true;
	$ErrMsg="Please Select Date...";
}
else if(empty(trim($_POST['hdPODetails'])))
{
	$Err=true;
	$ErrMsg="Please enter Items...";
}

else 
	{
		if($Err==false)
		{
			        mysqli_query($con2,"begin");
					$PartyCode=trim($_POST['cmbParty']);
                    $ItemData=trim($_POST['hdPODetails']);
                    $TablePrefix=trim($_POST['TablePrefix']);
					$dArr=explode("/",$_POST['txtFromDate']);
				    $date="$dArr[2]-$dArr[1]-$dArr[0]";
					$Month=$dArr[1];
					$OdDate=$dArr[0];
				    $PartyChallanNo=$_POST['txtDeliveryChallan'];
					$Remarks=$_POST['txtRemarks'];
					
					if (trim($_POST['rdb']=="Pending"))
					{
						if (trim($_POST['txtChallanId']==""))
						{
							$Err=true;
							$ErrMsg="Please Select the Challan...";
						}
					}
                        $ChallanNo="";
						$ChallanCode="";
						
						

						if (trim($_POST['hdPageMode']=="Edit"))
						{
							
							// Editing delivery challan
							// No. of Pcs Disp will be edited in both party_order_detail , party_challan_detail 
                            //$TablePrefix contains value that record of old record or old record
							$txtChallanId=base64_decode(base64_decode($_POST['txtChallanId']));
							
							
							
							$result=mysqli_query($con2,"update party_challan set  cChallanNo=\"".$PartyChallanNo."\" , cRemarks=\"".$Remarks."\"  where iChallanID =\"$txtChallanId\"");
							if(!$result)
							{
								$Err=true;
                                $ErrMsg="error in updating challan details";								
							}
							$ChallanData=explode('~ItemData~',$ItemData);
							for ($x=0;$x<sizeof($ChallanData);$x++)
							{
								$opt=explode('~Heading~',$ChallanData[$x]);
								$caption=explode('~Caption~',$opt[0]);
								$ItemType=$caption[0];	// Item Type
								$ItemName=$caption[1];	// Item Name
								$opt1=explode('~Array~',$opt[1]);
								for ($y=0;$y<sizeof ($opt1);$y++)
								{
									$sel=explode('~ArrayItem~',$opt1[$y]);
									
									
									$sel=explode('~ArrayItem~',$opt1[$y]);
									$OrderID=$sel[1];  // Order Id
									$QtyDisp=$sel[4];  //  Pcs dispatch
									$DispRemarks=$sel[5];  //  Remarks
									$QtyToDisp=$sel[6];  //  Pcs to be dispatch
									$ItemCode=$sel[8];	//	ItemCode
									$Rate=$sel[9];	//	 Rate
									$Returned=$sel[10];
									$Date=$sel[11];  // Date
									$PartType=$sel[12]; // Power Press  or Expeller Parts
									$Pcs=$sel[13]; // PT or PCS
									$Collar=$sel[14]; // Collar
								   
			
									
									
									$result=mysqli_query($con2,"update party_challan_detail set iNoPcsDisp ='$QtyToDisp', iNoPcsReturn='$Returned' , cItemRemarks =\"$DispRemarks\" where iChallanID =\"$txtChallanId\" and cItemType=\"$ItemType\"  and iItemCode=\"$ItemCode\"  and iOrderID=\"$OrderID\" and cPartType=\"$PartType\"  and  cPcs =\"$Pcs\"");
									if(!$result)
									{
										$Err=true;
										$ErrMsg="error in updating party challan details";
									}
									
									$TotalDisp=$QtyDisp + $QtyToDisp + $Returned;
									$queryExceed=mysqli_query($con2,"select iNoPcsRec from party_order_detail  where iOrderID=\"$OrderID\"  and cItemType =\"".$ItemType."\" and iItemCode =\"$ItemCode\" and cPartType=\"$PartType\"  and  cPcs =\"$Pcs\" and iNoPcsRec <\"$TotalDisp\"");
									if(mysqli_num_rows($queryExceed))
									{
										$Err=true;
										$ErrMsg="Dispatch quantity exceeded than order quantity";
									}
									
									$result=mysqli_query($con2,"update party_order_detail set iNoPcsDisp =\"$TotalDisp\" where iOrderID=\"$OrderID\"  and cItemType =\"".$ItemType."\" and iItemCode =\"$ItemCode\" and cPartType=\"$PartType\"  and  cPcs =\"$Pcs\"");
									
									if (!$result)
									{
										$Err=true;
										$ErrMsg="Error in updating party order details";
										
									}
									else
									{
										$ErrMsg="challan details updated successfully";
									}
								}
							}
						}
						else
						{
							
							// New Challan
						    $date=date("Y-m-d");
							$OdDate=date("d",strtotime($date));
							$Month=date("m",strtotime($date));
							
							
							// Insert into main table
                             $result=mysqli_query($con2,"select cChallanCode from party_challan where iChallanID = (select MAX(iChallanID) from party_challan)");
											if ($result)
											{
												
												
												if (mysqli_num_rows($result)>0)
												{
													$row=mysqli_fetch_array($result);
													$ChallanCodeNew=$row['cChallanCode'];
													
													$ChallanCodeNew= substr($ChallanCodeNew,strlen("DC/$OdDate/$Month/$YearString/")) +1;
												//	$challancode= substr($challancode,strlen("DC/$OdDate/$Month/$YearString/")) +1;
												}
												else
												{
													$ChallanCodeNew=1;
												}
												if ($ChallanCodeNew < 10)			 
													$ChallanCodeNew="DC/$OdDate/$Month/$YearString/00000".$ChallanCodeNew;
												else if ($ChallanCodeNew < 100)
													$ChallanCodeNew="DC/$OdDate/$Month/$YearString/0000".$ChallanCodeNew;	
												else if ($ChallanCodeNew < 1000)
													$ChallanCodeNew="DC/$OdDate/$Month/$YearString/000".$ChallanCodeNew;
												else if ($ChallanCodeNew < 10000)
													$ChallanCodeNew="DC/$OdDate/$Month/$YearString/00".$ChallanCodeNew;
												else if ($ChallanCodeNew < 100000)
													$ChallanCodeNew="DC/$OdDate/$Month/$YearString/0".$ChallanCodeNew;
												else
													$ChallanCodeNew="DC/$OdDate/$Month/$YearString/".$ChallanCodeNew;
											}
											
										
										
										   
											$result=mysqli_query($con2,"INSERT into party_challan (iChallanID,cChallanCode,dChallanDate,iPartyCode,cChallanNo,cRemarks) values('','$ChallanCodeNew','$date','$PartyCode',\"$PartyChallanNo\", \"$Remarks\")");
											if (!$result)
											{
												
												$Err=true;
												$ErrMsg="error in inserting party challan data".mysqli_error($con2)." ".$ChallanCodeNew;
											}
							
							// Insert into detail table
                           
							$ChallanData=explode('~ItemData~',$ItemData);
							for ($x=0;$x<sizeof($ChallanData);$x++)
							{
								
								$opt=explode('~Heading~',$ChallanData[$x]);
								$caption=explode('~Caption~',$opt[0]);
								
								$ItemType=$caption[0];	// Item Type
								$ItemName=$caption[1];	// Item Name
								$opt1=explode('~Array~',$opt[1]);
								for ($y=0;$y<sizeof ($opt1);$y++)
								{
									$sel=explode('~ArrayItem~',$opt1[$y]);
									$OrderID=$sel[1];  // Order Id
									$QtyDisp=$sel[4];  //  Pcs dispatch
									$DispRemarks=$sel[5];  //  Remarks
									$QtyToDisp=$sel[6];  //  Pcs to be dispatch
									$ItemCode=$sel[8];	//	ItemCode
									$Rate=$sel[9];	//	 Rate
									$Returned=$sel[10];
									$Date=$sel[11];  // Date
									$PartType=$sel[12]; // Power Press  or Expeller Parts
									$Pcs=$sel[13]; // PT or PCS
									$Collar=$sel[14]; // Collar
								    $YearPrefix=$sel[15]; // Year Prefix
								    $TablePrefix=$sel[17]; // TablePrefix
									
									
									
									
									if ($QtyToDisp>0 || $Returned>0)
									{
										
										//echo $ChallanCode;
										$result=mysqli_query($con2,"INSERT into party_challan_detail (iChallanID,iOrderID,cItemType,iItemCode,fRate,iNoPcsDisp,iNoPcsReturn,cItemRemarks,cPartType,cPcs,cCollar,cYearPrefix) values ((select iChallanID from party_challan where cChallanCode='$ChallanCodeNew'),'$OrderID','$ItemType','$ItemCode','$Rate','$QtyToDisp','$Returned',\"".$DispRemarks."\",'$PartType','$Pcs','$Collar','$YearPrefix')");
										if (!$result)
										{
										
											$Err=true;
											$ErrMsg="error in inserting party challan details data".mysqli_error($con2);
										}
										# party_order_detail  table is updated for No. of Pcs Disp.
										// Total Disp= Prev disp + disp + Return (as Pcs are returned by Puneet)
										
										$TotalDisp=$QtyDisp + $QtyToDisp + $Returned;
										$result=mysqli_query($con2,"UPDATE party_order_detail set iNoPcsDisp ='$TotalDisp' where iOrderID='$OrderID' and cItemType ='$ItemType' and iItemCode ='$ItemCode' and cPartType='$PartType'  and  cPcs ='$Pcs'");
										if (!$result)
										{
										
											$Err=true;
											$ErrMsg="error in updating party order details ".mysqli_error($con2);
										}
                                        else
										{
											$ErrMsg="challan details added successfully";
										}											
									}
								}
							}
						}
						
					
				}
	}
	if($Err==false)
	{
		mysqli_query($con2,"commit");
		
	}
	else
	{
		mysqli_query($con2,"rollback");
		
	}
	$response_array['error']=$Err;
	$response_array['error_msg']=$ErrMsg;
	echo json_encode($response_array);
	
	?>