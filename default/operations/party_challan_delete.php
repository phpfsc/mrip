<?php
require_once("../../config.php");
$Err=false;
$ErrMsg="";
if(empty(trim($_POST['Data'])))
	{
		$Err=true;
		$ErrMsg="invalid Request";
		
	}
	else 
	{
		    $challanData=explode("~",base64_decode(base64_decode(trim($_POST['Data']))));
			$fsYear=$challanData[0];
			$ChallanID=$challanData[1];
			$TablePrefix=$challanData[2]; //table Prefix old or blank
			$hostname=$_hostname;
			$User=$_username;
			$Pass=$_password;
		 if(empty(trim($ChallanID)) || empty(trim($ChallanID)))
		 {
			 $Err=true;
		     $ErrMsg="sorry something is wrong please try again later";
			 
		 }
		 else
		 {
			   
				
				
					           
								$Err=false;
								$code=$ChallanID;
								
								mysqli_query ($con2,"begin");

								$check=mysqli_query ($con2,"select * from {$TablePrefix}party_challan_detail  where iChallanID =\"$code\"");
								if ($check)
								{
											if (mysqli_num_rows($check)>0)
											{
												while ($checkrow=mysqli_fetch_array($check))
												{
													    $OrderId=$checkrow['iOrderID'];
													    $qry=mysqli_query ($con2,"select * from {$TablePrefix}party_billing_detail where iOrderID=\"$OrderId\"");
														if ($qry)
														{
															if (mysqli_num_rows($qry)>0)
															{
																$Err=true;
																$ErrMsg="This Delivery Challan is already Billed...";
															}
														}
														
														$qry1=mysqli_query ($con2,"select * from {$TablePrefix}k_party_billing_detail where iOrderID=\"$OrderId\"");
														if ($qry1)
														{
															if (mysqli_num_rows($qry1)>0)
															{
																$Err=true;
																$ErrMsg="This Delivery Challan is already Billed...";
															}
														}
														
														if ($Err==false)
														{
															$result=mysqli_query ($con2,"select * from {$TablePrefix}party_challan_detail  where iChallanID =\"$code\"");
															if ($result)
															{
																if (mysqli_num_rows($result)>0)
																{
																	while ($rrow=mysqli_fetch_array($result))
																	{
																		$OrderID1=$rrow['iOrderID'];
																		$ItemType=$rrow['cItemType'];
																		$ItemCode=$rrow['iItemCode'];
																		$PartType=$rrow['cPartType'];
																		$Disp=$rrow['iNoPcsDisp'];
																		$Return=$rrow['iNoPcsReturn'];
																		
																		$Pcs=$rrow['cPcs'];
																		$orderyear=$rrow['cYearPrefix'];
																		
																		$result1=mysqli_query($con2,"select iNoPcsDisp  from {$TablePrefix}party_order_detail where iOrderID='$OrderID1' and cItemType='$ItemType' and iItemCode =\"$ItemCode\" and cPartType =\"$PartType\" and cPcs=\"$Pcs\" ");
																		if ($result1) 
																		{
																			if (mysqli_num_rows($result1)>0)
																			{
																				
																				$rrow1=mysqli_fetch_array($result1);
																				$TotalDisp=$rrow1['iNoPcsDisp'] - ($Disp + $Return);
																				$Updateqry=mysqli_query ($con2,"UPDATE {$TablePrefix}party_order_detail set iNoPcsDisp=\"$TotalDisp\" where iOrderID=\"$OrderID1\" and cItemType=\"$ItemType\" and iItemCode =\"$ItemCode\" and cPartType =\"$PartType\" and cPcs=\"$Pcs\"");
																				if (!$Updateqry)
																				{
																					$Err=true;
																					$ErrMsg="Error in Updating";
																				}
																			}
																		}
																	}
																}
															}
															else
															{	
														        $Err=true;
																$ErrMsg="Error in getting detail";
																
															}
															
															$deleteqry=mysqli_query ($con2,"DELETE from {$TablePrefix}party_challan_detail where iChallanID =\"$code\"");
															if (!$deleteqry)
															{
																$Err=true;
																$ErrMsg="Error in deleting Party challan details";
																
															}
															else
															{
																$deleteqry=mysqli_query ($con2,"DELETE from {$TablePrefix}party_challan where iChallanID =\"$code\"");
																if (!$deleteqry)
																{
																  $Err=true;
																  $ErrMsg="Error in deleting Party challan";
																}
																else
																{
																	$ErrMsg="Delivery Challan Deleted ...";	
																}	
															}
														}
														
													
													
												}
											}
											
											else
											{
												$Err=true;
                                                $ErrMsg="Error in getting details";												
											}
										}
		                    
	      }
	}
	if($Err==true)
	{
		mysqli_query($con2,"rollback");
	}
	else
	{
		mysqli_query($con2,"commit");
	}
	$response_array['error']=$Err;
	$response_array['error_msg']=$ErrMsg;
	echo json_encode($response_array);
?>