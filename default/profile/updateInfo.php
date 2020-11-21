<?php
require_once("../../config.php");
$Err=false;
$ErrMsg="";
if($_POST)
{
	if(empty(trim($_POST['firstname'])))
	{
		$Err=true;
		$ErrMsg="Please enter first name";
		
	}
	else if(empty(trim($_POST['lastname'])))
	{
		$Err=true;
		$ErrMsg="Please enter last name";
	}
	else if(empty(trim($_POST['username'])))
	{
		$Err=true;
		$ErrMsg="Please enter username";
	}
	else 
	{
		if($Err==false)
		{
			$firstname=trim($_POST['firstname']);
			$lastname=trim($_POST['lastname']);
			$username=trim($_POST['username']);
			mysqli_query($con2,"begin");
			echo "select * from user_master where cLoginName=\"$username\" && cUserId!=\"$__UserId\"";
			$query=mysqli_query($con2,"select * from user_master where cLoginName=\"$username\" && cUserId!=\"$__UserId\"");
			if(mysqli_num_rows($query) || !$query)
			{
				$Err=true;
				$ErrMsg="Error : username already taken";
			}
			else
			{
				$update=mysqli_query($con2,"update user_master set first_name=\"$firstname\",last_name=\"$lastname\",cLoginName=\"$username\" where cUserId=\"$__UserId\"");
				if(!$update)
				{
					$Err=true;
					$ErrMsg="Error :please try again later";
				}
				else
				{
					$ErrMsg="Info Updated successfully";
				}
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