<?php
$Err=false;
$ErrMsg="";
require_once("../../config.php");
if($_POST)
{
	if(empty(trim($_POST['txtPassword'])))
	{
		$Err=true;
		$ErrMsg="Please Enter Password";
	}
	else if(empty(trim($_POST['txtConfirm'])))
	{
		$Err=true;
		$ErrMsg="Please Re enter password";
	}
	else if(strlen(trim($_POST['txtConfirm']))!=strlen(trim($_POST['txtPassword'])))
	{
		$Err=true;
		$ErrMsg="Password length doesn't match";
	}
	else if(trim($_POST['txtConfirm'])!=trim($_POST['txtPassword']))
	{
		$Err=true;
		$ErrMsg="Password doesn't match";
	}
	else
	{
		if($Err==false)
		{
			mysqli_query($con2,"begin");
			$password=base64_encode(base64_encode(trim($_POST['txtPassword'])));
			$query=mysqli_query($con2,"update user_master set cPassword=\"$password\" where cUserId=\"$__UserId\"");
			if($query)
			{
				$ErrMsg="Password updated successfully";
			}
			else
			{
				$Err=true;
				$ErrMsg="Error :please try again later";
			}
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