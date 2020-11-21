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
		 $DataArray=base64_decode(base64_decode(trim($_POST['Data'])));
		 $DataArray=explode("~",$DataArray);
		 $OrderID=trim($DataArray[0]);
		 $yearString=trim($DataArray[1]);
		 $YearPrefix=trim($DataArray[2]);//year prefix
		 if(empty(trim($OrderID)) || empty(trim($OrderID)))
		 {
			 $Err=true;
		     $ErrMsg="sorry something is wrong please try again later";
			 
		 }
		 else
		 {
			
				
					      mysqli_query ($con2,"begin");
						  $qry=mysqli_query ($con2,"select * from {$YearPrefix}party_challan_detail where iOrderID =\"$OrderID\"");	
						   if ($qry)
							{
								if (mysqli_num_rows($qry)>0)
								{
									$Odelete=false;		
								}
								else
								{
									$Odelete=true;
								}
							}
							else
							{
								$Err=true;
								$ErrMsg="sorry something is wrong please try again later";
							}
							if ($Odelete)
									{
										$result=mysqli_query($con2,"select * from {$YearPrefix}party_order_detail where iOrderID in ($OrderID) and iNoPcsDisp<>'0'");
										if ($result)
										{
											if(mysqli_num_rows($result)>0)
											{
												 $ErrMsg="Delivery challan Has already been built against this Purchase Order";

											}
											else
											{
												$dqry=mysqli_query($con2,"DELETE from {$YearPrefix}party_order_detail where iOrderID in ($OrderID)");
												if ($dqry)
												{
													$dqry=mysqli_query ($con2,"DELETE from {$YearPrefix}party_order where iOrderID in ($OrderID)");
													
													if (!$dqry)
													{
														$Err=true;
														$ErrMsg="Error in deleting purchase order";
													}
													else
													{
														$ErrMsg= "Purchase Order Deleted";
													}

												}
                                                else
                                                {
                                                    $Err=true;
													$ErrMsg="Error :please try again later";

												}

												

											}

										}

										else

										{

											$ErrMsg= "Error :".mysqli_error($con2);

										}

										

									}

		else

		{
            $Err=true;
			$ErrMsg="First delete the Delivery Challan";
			
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