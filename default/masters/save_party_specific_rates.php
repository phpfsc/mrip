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
	else if(empty(trim($_POST['PartyID'])))
	{
		$Err=true;
		$ErrMsg="Please select party first";
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
		    $PartyID=trim($_POST['PartyID']);
			if(!empty(trim($_POST['hdGearRatePlain'])))
			{
				$hdGearPlainData=trim($_POST['hdGearRatePlain']);
				$ItemArray=explode('~Array~', $hdGearPlainData);
				//print_r($ItemArray);
			
			for ($i=0;$i<sizeof($ItemArray);$i++)
			{
				
				$data=explode('~ArrayItem~',$ItemArray[$i]);
				
				if ($data[7]=="New")
				{
					// New Case............
					$result=mysqli_query ($con2,"INSERT into party_rates_master (iPartyID, cItemName, cItemType, fDMValue, cType ,fFaceFrom, fFaceTo, cFaceType, fRate) values ('$PartyID','Gear','Plain',\"".$data[1]."\",\"".$data[2]."\",\"".$data[3]."\",\"".$data[4]."\",\"".$data[5]."\", \"".$data[6]."\")");
					if (!$result)
					{
						$Err=true;
						$ErrMsg="Error in inserting data";
					}
					else
					{
						$ErrMsg="Data inserted sucessfully";
					}
					
				}
				else
				{
					$result = mysqli_query ($con2,"UPDATE party_rates_master set fDMValue=\"".$data[1]."\" , cType=\"".$data[2]."\" , fFaceFrom=\"".$data[3]."\" , fFaceTo=\"".$data[4]."\" , cFaceType=\"".$data[5]."\" ,fRate=\"".$data[6]."\" where iPartyID='$PartyID' and cItemName='Gear' and cItemType='Plain' and iId=\"".$data[0]."\" ");
					if (!$result)
					{
						$Err=true;
						$ErrMsg="Error in updating data";
					}
					else
					{
						$ErrMsg="Data updated sucessfully";
					}
				}
			}
			}
			else if(!empty(trim($_POST['hdGearRateHelical'])))
			{
					    $hdGearHelicalData=trim($_POST['hdGearRateHelical']);
						$ItemArray=explode('~Array~', $hdGearHelicalData);
						for ($i=0;$i<sizeof($ItemArray);$i++)
						{
							$data=explode('~ArrayItem~',$ItemArray[$i]);
							if ($data[7]=="New")
							{
								$result=mysqli_query ($con2,"INSERT into party_rates_master (iPartyID, cItemName, cItemType, fDMValue, cType ,fFaceFrom, fFaceTo, cFaceType, fRate) values ('$PartyID','Gear','Helical',\"".$data[1]."\",\"".$data[2]."\",\"".$data[3]."\",\"".$data[4]."\",\"".$data[5]."\", \"".$data[6]."\")");
								if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in inserting data";
									break;
								}
								else
								{
									$ErrMsg="data inserted sucessfully";
								}
							}
							else
							{
								// Update Case...........
								$result = mysqli_query ($con2,"UPDATE party_rates_master set fDMValue=\"".$data[1]."\" , cType=\"".$data[2]."\" , fFaceFrom=\"".$data[3]."\" , fFaceTo=\"".$data[4]."\" , cFaceType=\"".$data[5]."\", fRate=\"".$data[6]."\" where iPartyID='$PartyID' and cItemName='Gear' and cItemType='Helical' and iId=\"".$data[0]."\"");
								if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in updating data";
									break;
								}
								else
								{
									$ErrMsg="data updated sucessfully";
								}
							}
						}
			}
			
			else if(!empty(trim($_POST['hdShaftPinion'])))
			{
				   $hdShaftPinionData=trim($_POST['hdShaftPinion']);	
			$ItemArray=explode('~Array~', $hdShaftPinionData);
			
			for ($i=0;$i<sizeof($ItemArray);$i++)
			{
				$data=explode('~ArrayItem~',$ItemArray[$i]);
				if ($data[8]=="New")
				{
					          $result=mysqli_query ($con2,"INSERT into party_rates_master (iPartyID, cItemName, cItemType, fDMValue, cType ,fFaceFrom, fFaceTo, cFaceType,iTeeth , fRate) values ('$PartyID','Shaft Pinion','Plain',\"".$data[1]."\",\"".$data[2]."\",\"".$data[3]."\",\"".$data[4]."\",\"".$data[5]."\", \"".$data[6]."\",\"".$data[7]."\" )");
					          if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in inserting data";
									break;
								}
								else
								{
									$ErrMsg="data inserted sucessfully";
								}
				}
				else
				{
					           $result = mysqli_query ($con2,"UPDATE party_rates_master set fDMValue=\"".$data[1]."\" , cType=\"".$data[2]."\" , fFaceFrom=\"".$data[3]."\" , fFaceTo=\"".$data[4]."\" , cFaceType=\"".$data[5]."\" , iTeeth=\"".$data[6]."\" , fRate=\"".$data[7]."\" where iPartyID='$PartyID' and cItemName='Shaft Pinion' and cItemType='Plain' and iId=\"".$data[0]."\"");
					            if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in updating data";
									break;
								}
								else
								{
									$ErrMsg="data updated sucessfully";
								}
				}
			}
			}
			else if(!empty(trim($_POST['hdShaftPHelical'])))
			{
				    $hdShaftPinionHData=trim($_POST['hdShaftPHelical']);
					$ItemArray=explode('~Array~', $hdShaftPinionHData);
					for ($i=0;$i<sizeof($ItemArray);$i++)
					{
						$data=explode('~ArrayItem~',$ItemArray[$i]);
						if ($data[7]=="New")
						{
							$result=mysqli_query ($con2,"INSERT into party_rates_master (iPartyID, cItemName, cItemType, fDMValue, cType ,fFaceFrom, fFaceTo, cFaceType, fRate) values ('$PartyID','Shaft Pinion','Helical',\"".$data[1]."\",\"".$data[2]."\",\"".$data[3]."\",\"".$data[4]."\",\"".$data[5]."\", \"".$data[6]."\" )");
							   if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in inserting data";
									break;
								}
								else
								{
									$ErrMsg="data inserted sucessfully";
								}
						}
						else
						{
							// Update Case...........
							//$result = mysqli_query ($con2,"UPDATE party_rates_master set fRate=\"".$data[6]."\" where iPartyID='$PartyID' and cItemName='Shaft Pinion' and cItemType='Helical' and  fDMValue=$data[1] and cType=\"".$data[2]."\" and fFaceFrom=$data[3] and fFaceTo=$data[4] and cFaceType=\"".$data[5]."\"");
							$result = mysqli_query ($con2,"UPDATE party_rates_master set fDMValue=\"".$data[1]."\" , cType=\"".$data[2]."\" , fFaceFrom=\"".$data[3]."\" , fFaceTo=\"".$data[4]."\" , cFaceType=\"".$data[5]."\" , fRate=\"".$data[6]."\" where iPartyID='$PartyID' and cItemName='Shaft Pinion' and cItemType='Helical' and iId=\"".$data[0]."\"");	
							    if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in updating data";
									break;
								}
								else
								{
									$ErrMsg="data updated sucessfully";
								}
						}
					}
			}
			else if(!empty(trim($_POST['hdChainGear'])))
			{
				    $hdChainGearData=trim($_POST['hdChainGear']);
					$ItemArray=explode('~Array~', $hdChainGearData);
					
					for ($i=0;$i<sizeof($ItemArray);$i++)
					{
						$data=explode('~ArrayItem~',$ItemArray[$i]);
						if ($data[4]=="New")
						{
							$result=mysqli_query ($con2,"INSERT into party_rates_master (iPartyID, cItemName,  fRate ,fPitchValue, cPitchType) values ('$PartyID','Chain Gear',\"".$data[3]."\",\"".$data[1]."\",\"".$data[2]."\")");
							
								if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in inserting data";
									break;
								}
								else
								{
									$ErrMsg="data inserted sucessfully";
								}
							
						}
						else
						{
							// Update Case...........
							//$result = mysqli_query ($con2,"UPDATE party_rates_master set fRate=\"".$data[3]."\" where iPartyID='$PartyID' and cItemName='Chain Gear' and  fPitchValue=$data[1] and cPitchType=\"".$data[2]."\" ");
							$result = mysqli_query ($con2,"UPDATE party_rates_master set fPitchValue=\"".$data[1]."\" , cPitchType=\"".$data[2]."\" , fRate=\"".$data[3]."\" where iPartyID='$PartyID' and cItemName='Chain Gear' and iId=\"".$data[0]."\"");	
							    if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in updating data";
									break;
								}
								else
								{
									$ErrMsg="data updated sucessfully";
								}
						}
					}
			}
			else if(!empty(trim($_POST['hdBevelGear'])))
			{
					$hdBevelGearData=trim($_POST['hdBevelGear']);
					$ItemArray=explode('~Array~', $hdBevelGearData);
					
					for ($i=0;$i<sizeof($ItemArray);$i++)
					{
						$data=explode('~ArrayItem~',$ItemArray[$i]);
						if ($data[5]=="New")
						{
							$result=mysqli_query ($con2,"INSERT into party_rates_master (iPartyID, cItemName, fDMValue, cType , fRate,iTeeth  ) values ('$PartyID','Bevel Gear',\"".$data[1]."\",\"".$data[2]."\",\"".$data[4]."\",\"".$data[3]."\")");
							    if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in inserting data";
									break;
								}
								else
								{
									$ErrMsg="data inserted sucessfully";
								}
						}
						else
						{
							// Update Case...........
							//$result = mysqli_query ($con2,"UPDATE party_rates_master set fRate=\"".$data[4]."\" where iPartyID='$PartyID' and cItemName='Bevel Gear' and  fDMValue=$data[1] and cType=\"".$data[2]."\" and iTeeth=$data[3]");
							$result = mysqli_query ($con2,"UPDATE party_rates_master set  fDMValue=\"".$data[1]."\" , cType=\"".$data[2]."\" , iTeeth=\"".$data[3]."\" , fRate=\"".$data[4]."\" where iPartyID='$PartyID' and cItemName='Bevel Gear' and iId=\"".$data[0]."\"");
							if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in updating data";
									break;
								}
								else
								{
									$ErrMsg="data updated sucessfully";
								}
						}
					}
			}
			else if(!empty(trim($_POST['hdSpecialItem'])))
			{
					$SpecialItemInfo=explode("~Array~",$_POST['hdSpecialItem']);
					for ($i=0;$i<sizeof($SpecialItemInfo);$i++)
					{
						$DataSpecialItem=explode("~ArrayItem~",$SpecialItemInfo[$i]);
						
						if ($DataSpecialItem[4]=="New")
						{
							$result=mysqli_query ($con2,"INSERT into special_item_rate_master (iPartyID, cItemName,fRate,cMeasurement) values (\"".$PartyID."\" ,\"".$DataSpecialItem[1]."\",\"".$DataSpecialItem[2]."\",\"".$DataSpecialItem[3]."\")");				
							    if (!$result)
								{
									$Err=true;
									$ErrMsg="Error in inserting data";
									break;
								}
								else
								{
									$ErrMsg="data inserted sucessfully";
								}
						}
						else
						{
							$result=mysqli_query ($con2,"UPDATE special_item_rate_master set  cItemName=\"".$DataSpecialItem[1]."\" , fRate=\"".$DataSpecialItem[2]."\", cMeasurement=\"".$DataSpecialItem[3]."\" where iId=\"".$DataSpecialItem[0]."\"");
							    if(!$result)
								{
									$Err=true;
									$ErrMsg="Error in updating data";
									break;
								}
								else
								{
									$ErrMsg="data updated sucessfully";
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