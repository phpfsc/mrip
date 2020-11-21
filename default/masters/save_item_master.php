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
			if(!empty(trim($_POST['txtgear'])))
			{
				$ArrayData=explode("~Array~",trim($_POST['txtgear']));	
				for ($i=0;$i<sizeof($ArrayData);$i++)
				{
					$ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
					$GearId=$ArrayItem[0];
					$GearTeeth=$ArrayItem[2];
					$GearType=$ArrayItem[3];
					$GearDia=$ArrayItem[4];
					$GearDiaMeasure=$ArrayItem[5];
					$GearFace=$ArrayItem[6];
					$GearFaceType=$ArrayItem[7];
					$GearDMValue=$ArrayItem[8];
					$GearDMType=$ArrayItem[9];
					
					$result=mysqli_query($con2,"UPDATE gear_master  set iTeeth= $GearTeeth , cItemType='$GearType' , fDia=$GearDia , cDiaType='$GearDiaMeasure' , fFace=$GearFace  , cFaceType='$GearFaceType' , fDMValue=$GearDMValue , cType='$GearDMType' where iId=$GearId");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error : Please try again";
						break;
					}
                    else
					{
						$ErrMsg="Data Updated successfully";
					}						
				}
			}
			else if(!empty(trim($_POST['txtpinion'])))
			{
				$ArrayData=explode("~Array~",$_POST['txtpinion']);	
				for ($i=0;$i<sizeof($ArrayData);$i++)
				{
					$ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
					$PinionId=$ArrayItem[0];
					$PinionTeeth=$ArrayItem[2];
					$PinionType=$ArrayItem[3];
					$PinionDia=$ArrayItem[4];
					$PinionDiaMeasure=$ArrayItem[5];
					$PinionFace=$ArrayItem[6];
					$PinionFaceType=$ArrayItem[7];
					$PinionDMValue=$ArrayItem[8];
					$PinionDMType=$ArrayItem[9];
					$result=mysqli_query ($con2,"UPDATE pinion_master  set iTeeth= $PinionTeeth , cItemType='$PinionType' , fDia=$PinionDia , cDiaType='$PinionDiaMeasure' , fFace=$PinionFace  , cFaceType='$PinionFaceType' , fDMValue=$PinionDMValue , cType='$PinionDMType' where iId=$PinionId");
					if (! $result)
					{
						$Err=true;
						$ErrMsg="Error : Please try again";
						break;

					}
                    else
					{
						$ErrMsg=" Pinion data saved sucessfully";
					}						
				}
			}
			
			else if(!empty(trim($_POST['txtshaftpinion'])))
			{
				$ArrayData=explode("~Array~",$_POST['txtshaftpinion']);	
				for ($i=0;$i<sizeof($ArrayData);$i++)
				{
					$ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
					$ShaftPinionId=$ArrayItem[0];
					$ShaftPinionTeeth=$ArrayItem[2];
					$ShaftPinionType=$ArrayItem[3];
					$ShaftPinionDia=$ArrayItem[4];
					$ShaftPinionDiaMeasure=$ArrayItem[5];
					$ShaftPinionFace=$ArrayItem[6];
					$ShaftPinionFaceType=$ArrayItem[7];
					$ShaftPinionDMValue=$ArrayItem[8];
					$ShaftPinionDMType=$ArrayItem[9];
					$result=mysqli_query ($con2,"UPDATE shaft_pinion_master  set iTeeth= $ShaftPinionTeeth , cItemType='$ShaftPinionType' , fDia=$ShaftPinionDia , cDiaType='$ShaftPinionDiaMeasure' , fFace=$ShaftPinionFace  , cFaceType='$ShaftPinionFaceType' , fDMValue=$ShaftPinionDMValue , cType='$ShaftPinionDMType' where iId=$ShaftPinionId");
					if (!$result)
					{
						$Err=true;
						$ErrMsg="Error : Please try again";
						break;
					}
                    else
					{
						$ErrMsg="Shaft pinion data saved sucessfully";
					}					
				}
			}
			else if(!empty(trim($_POST['txtBevelGear'])))
			{
				$ArrayData=explode("~Array~",$_POST['txtBevelGear']);	
				for ($i=0;$i<sizeof($ArrayData);$i++)
				{
					$ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
					$BevelGearId=$ArrayItem[0];
					$BevelGearTeeth=$ArrayItem[2];
					$BevelGearDia=$ArrayItem[3];
					$BevelGearDiaMeasure=$ArrayItem[4];
					$BevelGearDMValue=$ArrayItem[5];
					$BevelGearDMType=$ArrayItem[6];
					$result=mysqli_query ($con2,"UPDATE bevel_gear_master  set iTeeth= $BevelGearTeeth , fDia=$BevelGearDia , cDiaType='$BevelGearDiaMeasure' ,fDMValue=$BevelGearDMValue , cType='$BevelGearDMType' where iId=$BevelGearId");
					if (!$result)
					{
						$Err=true;
						$ErrMsg="Error : Please try again";
						break;
					}
                    else
					{
						$ErrMsg="Bevel Gear data saved sucessfully";
					}					
				}
			}
			else if(!empty(trim($_POST['txtBevelPinion'])))
			{
				$ArrayData=explode("~Array~",$_POST['txtBevelPinion']);	
				for ($i=0;$i<sizeof($ArrayData);$i++)
				{
					$ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
					$BevelPinionId=$ArrayItem[0];
					$BevelPinionTeeth=$ArrayItem[2];
					$BevelPinionDia=$ArrayItem[3];
					$BevelPinionDiaMeasure=$ArrayItem[4];
					$BevelPinionDMValue=$ArrayItem[5];
					$BevelPinionDMType=$ArrayItem[6];
					$result=mysqli_query ($con2,"UPDATE bevel_pinion_master  set iTeeth= $BevelPinionTeeth , fDia=$BevelPinionDia , cDiaType='$BevelPinionDiaMeasure' ,fDMValue=$BevelPinionDMValue , cType='$BevelPinionDMType' where iId=$BevelPinionId");
					if (!$result)
					{
						$Err=true;
						$ErrMsg="Error : Please try again";
						break;
					}
                    else
					{
						$ErrMsg="Bevel pinion data saved sucessfully";
					}					
				}
			}
			else if(!empty(trim($_POST['txtchaingear'])))
			{
				$ArrayData=explode("~Array~",$_POST['txtchaingear']);	
				for ($i=0;$i<sizeof($ArrayData);$i++)
				{
					$ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
					$ChainGearId=$ArrayItem[0];
					$ChainGearTeeth=$ArrayItem[2];
					$ChainGearType=$ArrayItem[3];
					$ChainGearDia=$ArrayItem[4];
					$ChainGearDiaMeasure=$ArrayItem[5];
					$ChainGearPitch=$ArrayItem[6];
					$ChainGearPitchType=$ArrayItem[7];
					$result=mysqli_query ($con2,"UPDATE chain_gear_master  set iTeeth= $ChainGearTeeth ,cItemType='$ChainGearType', fDia=$ChainGearDia , cDiaType='$ChainGearDiaMeasure' ,fPitch=$ChainGearPitch , cPitchType='$ChainGearPitchType' where iId=$ChainGearId");
					if (!$result)
					{
						$Err=true;
						$ErrMsg="Error : Please try again";
						break;
					}
                    else
					{
						$ErrMsg="Chain Wheel data saved sucessfully";
					}			
				}	
			}
			else if(!empty(trim($_POST['txtWormGear'])))
			{
				$ArrayData=explode("~Array~",$_POST['txtWormGear']);	
				for ($i=0;$i<sizeof($ArrayData);$i++)
				{
					$ArrayItem=explode("~ArrayItem~",$ArrayData[$i]);
					$WormGearId=$ArrayItem[0];
					$WormGearTeeth=$ArrayItem[2];
					$WormGearDia=$ArrayItem[3];
					$WormGearDiaMeasure=$ArrayItem[4];
					$WormGearDMValue=$ArrayItem[5];
					$WormGearDMType=$ArrayItem[6];
					$result=mysqli_query ($con2,"UPDATE worm_gear_master  set iTeeth= $WormGearTeeth , fDia=$WormGearDia , cDiaType='$WormGearDiaMeasure' ,fDMValue=$WormGearDMValue , cType='$WormGearDMType' where iId=$WormGearId");
					if (!$result)
					{
						$Err=true;
						$ErrMsg="Error : Please try again";
						break;
					}
                    else
					{
						$ErrMsg="Worm gear data saved sucessfully";
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