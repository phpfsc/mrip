<?php
/** used for  login popup on billing screen screen **/
require_once("../config.php");
$Err=false;
$ErrMsg="";
date_default_timezone_set("Asia/Calcutta"); 
$time=date('H:i:s',time());
$billing_type="";
//Check For Any Data To Process   
if (isset($_POST)) 
{
	
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="invalid request";
	}
	else if(empty(trim($_POST['username'])))
	{
		$Err=true;
		$ErrMsg="please enter username";
	}
	else if(empty(trim($_POST['password'])))
	{
		$Err=true;
		$ErrMsg="Please enter password";
	}
	else
	{
		
	
		$username=trim($_POST['username']);
		$password=base64_encode(base64_encode(trim($_POST['password'])));
		$result1=mysqli_query($con2,"select * from user_master where cLoginName=\"$username\" && cPassword=\"$password\" && bActive='1'");
	    if($result1 && mysqli_num_rows($result1)>0)
	    {
			
	        $row=mysqli_fetch_array($result1);
		    $billing_type=base64_encode(base64_encode($row['bBilling']));
			$ErrMsg="loged in successfully";
	    }
		else
		{
	        $Err=true;
            $ErrMsg="Please enter correct username or password.".mysqli_error($con1);
            $focus_id="txtUserName";
	    }
	  }
	
	    
	}


$response_array['error'] = $Err;
$response_array['error_msg'] = $ErrMsg;
$response_array['billing_type'] = $billing_type;
$response_array['focus_id'] = $focus_id;
echo json_encode($response_array);
?>