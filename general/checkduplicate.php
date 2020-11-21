<?php
$Table=$_GET['table']; 
include("../config.php"); 
 if(isset($_GET['code']))
 {

	$Value=rawurldecode($_GET['code']);
	$Value=str_replace("\\\'","\'",$Value);
	$Value=str_replace("\\\"","\"",$Value);
	$Value=str_replace("\\","",$Value);


	$query="select * from $Table ".$Value;
	
	$result=mysqli_query($con2,$query);
	if($result)
	{
		$num_rows=mysqli_num_rows($result);
		if ($num_rows>0)
		{
			echo 'Record Exist';
		}
		else
		{
			echo 'Record Not Exist';
		}
	} 
	else
	{
		echo "Error";
	}
		
}
?>