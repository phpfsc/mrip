<?php
require_once("../../config.php");
$Err=false;
$ErrMsg="";
if($_POST)
{
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="Invalid Request1";
	}
	else
	{
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
		if($__update==true)
		{
			mysqli_query($con2,"begin");
			if(!empty(trim($_POST['hdGearRatePlain'])))
			{
				$GearInfo=explode("~Array~",$_POST['hdGearRatePlain']);
		
				for ($i=0;$i<sizeof($GearInfo);$i++)
				{
					$DataItemGear= explode("~ArrayItem~",$GearInfo[$i]);
					if ($DataItemGear[7]=="New")
					{

						$result=mysqli_query ($con2,"insert into gear_rate_master (cItemType , fDMValue, cType, fFaceFrom,fFaceTo, cFaceType,fRate) values ('Plain',\"".$DataItemGear[1]."\",\"".$DataItemGear[2]."\",\"".$DataItemGear[3]."\",\"".$DataItemGear[4]."\",\"".$DataItemGear[5]."\",\"".$DataItemGear[6]."\")");	
						if (!$result)
						{
							
							$Err=true;
							$ErrMsg="Error in Inserting records...";
                            
							//print "Error in Inserting records...";
						}
						else
						{
							$ErrMsg="records sucessfully inserted";
						}
					}
					else
					{
						$result=mysqli_query ($con2,"update gear_rate_master set  fDMValue=\"".$DataItemGear[1]."\" , cType =\"".$DataItemGear[2]."\" , fFaceFrom =\"".$DataItemGear[3]."\", fFaceTo=\"".$DataItemGear[4]."\" , cFaceType =\"".$DataItemGear[5]."\" , fRate=\"".$DataItemGear[6]."\"   where iId=\"".$DataItemGear[0]."\" and cItemType='Plain'");
						if (!$result)
						{
							$Err=true;
							$ErrMsg="Error in updating records...";
							//print "Error in updating records...";
						}
						else
						{
							$ErrMsg="records sucessfully updated";
						}
					}
				}
			}
			else if(!empty(trim($_POST['hdGearRateHelical'])))
			{
					$GearInfo=explode("~Array~",$_POST['hdGearRateHelical']);
						for ($i=0;$i<sizeof($GearInfo);$i++)
							{
								$DataItemGear= explode("~ArrayItem~",$GearInfo[$i]);
								if ($DataItemGear[7]=="New")
								{
									$result=mysqli_query ($con2,"insert into gear_rate_master (cItemType , fDMValue, cType, fFaceFrom,fFaceTo, cFaceType, fRate) values ('Helical',\"".$DataItemGear[1]."\",\"".$DataItemGear[2]."\",\"".$DataItemGear[3]."\",\"".$DataItemGear[4]."\",\"".$DataItemGear[5]."\",\"".$DataItemGear[6]."\")");	
									if (!$result)
									{
										$Err=true;
										$ErrMsg="Error in Inserting records...";
											
									}
									else
									{
										$ErrMsg="records sucessfully inserted";
									}
								}
								else
								{
									$result=mysqli_query ($con2,"update gear_rate_master set fDMValue=\"".$DataItemGear[1]."\" , cType =\"".$DataItemGear[2]."\" , fFaceFrom =\"".$DataItemGear[3]."\", fFaceTo=\"".$DataItemGear[4]."\" , cFaceType =\"".$DataItemGear[5]."\" , fRate=\"".$DataItemGear[6]."\"  where iId=\"".$DataItemGear[0]."\" and cItemType='Helical'");
									if (!$result)
									{
										$Err=true;
										$ErrMsg="Error in updating records...";
										
									}
									else
									{
										$ErrMsg="records sucessfully updated";
									}
								}
							}
			}
			
			else if(!empty(trim($_POST['hdShaftPinion'])))
			{
				   $ShaftPinionInfo=explode("~Array~",$_POST['hdShaftPinion']);
		
					for ($i=0;$i<sizeof($ShaftPinionInfo);$i++) 
					{
						$DataShaftPinion=explode('~ArrayItem~',$ShaftPinionInfo[$i]);
						if ($DataShaftPinion[8]=="New")
						{
							$result=mysqli_query ($con2,"Insert into shaft_pinion_rate_master (cItemType, fDMValue, cType, fFaceFrom,fFaceTo, cFaceType,iTeeth, fRate) values ('Plain',\"".$DataShaftPinion[1]."\",\"".$DataShaftPinion[2]."\",\"".$DataShaftPinion[3]."\",\"".$DataShaftPinion[4]."\",\"".$DataShaftPinion[5]."\",\"".$DataShaftPinion[6]."\",\"".$DataShaftPinion[7]."\")");
							if (!$result)
							{
								$Err=true;
								$ErrMsg= "Error in Inserting records...";
							}
							else
							{
								$ErrMsg= "records ineserted sucessfully";
							}
						}
						else
						{
							$result=mysqli_query ($con2,"update shaft_pinion_rate_master set fDMValue = \"".$DataShaftPinion[1]."\", cType= \"".$DataShaftPinion[2]."\", fFaceFrom= \"".$DataShaftPinion[3]."\",fFaceTo= \"".$DataShaftPinion[4]."\", cFaceType= \"".$DataShaftPinion[5]."\",iTeeth= \"".$DataShaftPinion[6]."\", fRate=\"".$DataShaftPinion[7]."\" where iId=\"".$DataShaftPinion[0]."\"")	 ;
							if (! $result)
							{
								$Err=true;
								$ErrMsg="Error in updating records...";
								
							}
							else
							{
								$ErrMsg="Records updated sucessfully...";
							}
						}
					}
			}
			else if(!empty(trim($_POST['hdShaftPHelical'])))
			{
				 $ShaftPHelicalInfo=explode("~Array~",$_POST['hdShaftPHelical']);
		
				for ($i=0;$i<sizeof($ShaftPHelicalInfo);$i++) 
				{
					$DataShaftPHelical=explode('~ArrayItem~',$ShaftPHelicalInfo[$i]);
					if ($DataShaftPHelical[7]=="New")
					{
						$result=mysqli_query ($con2,"Insert into shaft_pinion_rate_master (cItemType, fDMValue, cType, fFaceFrom,fFaceTo, cFaceType,iTeeth,  fRate) values ('Helical',\"".$DataShaftPHelical[1]."\",\"".$DataShaftPHelical[2]."\",\"".$DataShaftPHelical[3]."\",\"".$DataShaftPHelical[4]."\",\"".$DataShaftPHelical[5]."\",'0',\"".$DataShaftPHelical[6]."\")");
						if(!$result)
						{
							$Err=true;
							$ErrMsg="Error in Inserting records...";
						}
						else
						{
							$ErrMsg="records Inserted sucessfully...";
						}
					}
					else
					{
						$result=mysqli_query ($con2,"update shaft_pinion_rate_master set fDMValue=\"".$DataShaftPHelical[1]."\", cType=\"".$DataShaftPHelical[2]."\", fFaceFrom=\"".$DataShaftPHelical[3]."\",fFaceTo=\"".$DataShaftPHelical[4]."\", cFaceType=\"".$DataShaftPHelical[5]."\",fRate=\"".$DataShaftPHelical[6]."\" where iId=\"".$DataShaftPHelical[0]."\"")	 ;
						if (!$result)
						{
							$Err=true;
							$ErrMsg="Error in updating records...";
						}
						else
						{
							
								$ErrMsg="records updated sucessfully...";
							
						}
					}
				}
			}
			else if(!empty(trim($_POST['hdChainGear'])))
			{
				 $ChainGearInfo=explode("~Array~",$_POST['hdChainGear']);
					for ($i=0;$i<sizeof($ChainGearInfo);$i++) 
					{
						$DataChainGear=explode('~ArrayItem~',$ChainGearInfo[$i]);
						if ($DataChainGear[4]=="New")
						{
							$result=mysqli_query ($con2,"Insert into chain_gear_rate_master (fPitchValue, cPitchType, fRate) values (\"".$DataChainGear[1]."\",\"".$DataChainGear[2]."\",\"".$DataChainGear[3]."\")");
							if(!$result)
							{
								$Err=true;
								$ErrMsg="Error in Inserting records...";
							}
							else
							{
								$ErrMsg="Records inserted sucessfully...";
							}
						}
						else
						{
							$result=mysqli_query ($con2,"update chain_gear_rate_master set fPitchValue=\"".$DataChainGear[1]."\", cPitchType=\"".$DataChainGear[2]."\",fRate=\"".$DataChainGear[3]."\" where iId=\"".$DataChainGear[0]."\"")	 ;
							if(!$result)
							{
								$Err=true;
								$ErrMsg="Error in updating records...";
							}
							else
							{
								$ErrMsg="Records updated sucessfully...";
							}
						}
					}
			}
			else if(!empty(trim($_POST['hdBevelGear'])))
			{
					$BevelGearInfo=explode("~Array~",$_POST['hdBevelGear']);
					for ($i=0;$i<sizeof($BevelGearInfo);$i++) 
					{
						$DataBevelGear=explode('~ArrayItem~',$BevelGearInfo[$i]);
						if ($DataBevelGear[5]=="New")
						{
							$result=mysqli_query ($con2,"Insert into bevel_gear_rate_master (fDMValue, cType, iTeeth, fRate) values (\"".$DataBevelGear[1]."\",\"".$DataBevelGear[2]."\",\"".$DataBevelGear[3]."\",\"".$DataBevelGear[4]."\")");
							if(!$result)
								{
									$Err=true;
									$ErrMsg="Error in inserting records...";
								}
							else
								{
									$ErrMsg="Records inserted sucessfully...";
								}
						}
						else
						{
							$result=mysqli_query ($con2,"update bevel_gear_rate_master set fDMValue=\"".$DataBevelGear[1]."\", cType=\"".$DataBevelGear[2]."\", iTeeth=\"".$DataBevelGear[3]."\",fRate=\"".$DataBevelGear[4]."\" where iId=\"".$DataBevelGear[0]."\"")	 ;
							if(!$result)
							{
								$Err=true;
								$ErrMsg="Error in updating records...";
							}
							else
							{
								$ErrMsg="Records updated sucessfully...";
							}
						}
					}
			}
			
		}
		else
		{
			$Err=true;
			$ErrMsg="You dont have permission to perform this action";
		}
		
	}
	
}
else
{
	$Err=true;
	$ErrMsg="Invalid Request";
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