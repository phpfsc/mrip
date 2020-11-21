<?php
require_once("../../config.php");
$Err=false;
$ErrMsg="";

$target_dir = "../../uploads";
if(!file_exists($target_dir))
{
	mkdir($target_dir);
	
}
if(!file_exists($target_dir."/".$__UserId))
{
	mkdir($target_dir."/".$__UserId);
	
}
$target_dir=$target_dir."/".$__UserId."/";
$filename=$_FILES["fileInput"]["name"];
if(empty($filename))
{
	$Err=true;
	$ErrMsg="Please choose a file first";
}
else
{
	$allowed_array=array("jpg","png");
	
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
    $target_file = $target_dir . basename("image.".$ext);
    $target_file2 = $target_dir .$filename;
	if(!in_array($ext,$allowed_array))
	{
		$Err=true;
		$ErrMsg=$ext." image type not allowed";
	}
	else
	{
		if($Err==false)
		{
			if(move_uploaded_file($_FILES["fileInput"]["tmp_name"], $target_file2))
			{
				
				
				$target_file="../uploads/".$__UserId."/".$filename;
				$query=mysqli_query($con2,"update user_master set userImage=\"$target_file\" where cUserId=\"$__UserId\"");
				if(!$query)
				{
					$Err=true;
					$ErrMsg="Error : Please try Again";
				}
				else
				{
					$ErrMsg="Picuture Uploaded successfully";
				}
			}

		}
	}
}


	


// if($Err==true)
// {
	// mysqli_query($con2,"rollback");
// }
// else
// {
	// mysqli_query($con2,"commit");
// }
$response_array['error']=$Err;
$response_array['error_msg']=$ErrMsg;
echo json_encode($response_array);
?>