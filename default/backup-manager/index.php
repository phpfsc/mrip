<?php
$Err=false;
$ErrMsg="";
error_reporting(1);
ini_set('max_execution_time', 600);
require ("spectre.zip.php");
if(empty(trim($_POST['auth_info'])))
{
	$Err=true;
	$ErrMsg="";
}

function create_backup_sql($User, $Pass, $Db)
{
	$ccyymmdd = date("d-m-Y");
	if (! is_dir("backup".$Db.$ccyymmdd))
	{
		mkdir("backup".$Db.$ccyymmdd,0777);
		
	}
	
	$line_count = 0;
	

	 $db_connection = mysqli_connect("localhost",$User,$Pass,$Db) or die("could not Connect");
	// mysqli_select_db (db_name($Db)) 
	// or die("Could not open $db: " .mysqli_error());
	
	 $tables = mysqli_query($db_connection,"SHOW TABLES");
	 $sql_string = NULL;
	while ($table = mysqli_fetch_array($tables)) 
	{   
		$table_name = $table[0];
		$sql_string .=" DROP TABLE IF EXISTS `$table_name`;";
		$result = mysqli_query($db_connection,"SELECT * FROM `$table_name`") or die(mysqli_error($db_connection)); 
		$num_fields = mysqli_num_fields($result); 
		
		$row2 = mysqli_fetch_row(mysqli_query($db_connection,"SHOW CREATE TABLE `$table_name`")); 
		$sql_string.= "\n\n".$row2[1].";\n\n"; 		
	
		$table_query = mysqli_query($db_connection,"SELECT * FROM `$table_name`");
		$num_fields = mysqli_num_fields($table_query);
		$recordsize = 0; 
		$sqlstrsize = 0;
		if (mysqli_num_rows($table_query)<=0)
		{
			if ($sql_string != "")
			{
				$file = fopen("backup".$Db.$ccyymmdd."/".$table_name.".sql","a+");
				$line_count = write_backup_sql($file,$sql_string,$line_count);   
			}
			$sql_string1=NULL;
			$sql_string = NULL;
		}
		while ($fetch_row = mysqli_fetch_array($table_query)) 
		{
			$sql_string .= "INSERT INTO $table_name VALUES(";
			$first = TRUE;
			
			for ($field_count=1;$field_count<=$num_fields;$field_count++)
			{
			    $recordsize += mysqli_fetch_field_direct($table_query, ($field_count-1));		
				if (TRUE == $first) 
				{
					$sql_string .= "'".mysqli_real_escape_string($db_connection,$fetch_row[($field_count - 1)])."'";
					$first = FALSE;            
				}
				else
				{
					$sql_string .= ", '".mysqli_real_escape_string($db_connection,$fetch_row[($field_count - 1)])."'";
				}
			}
			$sql_string .= ");";
			/*if( (int)(strlen($sql_string1))>256000)
			{
				$i++;
				$file = fopen("backup1".$Db.$ccyymmdd."/".$table_name.$i.".sql","a+");
				$line_count = write_backup_sql($file,$sql_string,$line_count);
				$sql_string1=NULL;
				$sql_string1="";
			}
			else
			{*/
				if ($sql_string != "")
				{
					$file = fopen("backup".$Db.$ccyymmdd."/".$table_name.".sql","a+");
					//$line_count = write_backup_sql($file,base64_encode($sql_string),$line_count);   
					$line_count = write_backup_sql($file,$sql_string,$line_count);   
				}
			//}
			$sql_string1=$sql_string1.$sql_string;
			$sql_string = NULL;
		}
		
		$sql_string1= NULL;
		$i="";
					
		//echo "Backup of $table_name created : $line_count rows <br/>";  
		 
		$recordsize=0;
		$line_count=0;    
	}
		//return $line_count;
}
	
function write_backup_sql($file, $string_in, $line_count) 
{ 
	fwrite($file, $string_in);
	
	return ++$line_count;
}

function db_name($Db) 
{
	return ($Db);
}

function db_connect($User, $Pass) 
{
	$db_connection = mysqli_connect("localhost", $User, $Pass) or die(mysqli_error());
	if (!$db_connection)
	{
		die("Could not Connect to MySQL");
	}
	else
	{
		return $db_connection;
	}
}  
?>

<div class="row">
<div class="col-sm-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Operations</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Delivery Challan</li>
			</ol>
		</div>

	</div>
</div>
</div>
<div class="card">
		<div class="card-body">
<?php
if($Err==true)
{
	die($ErrMsg);
}
?>
<?php

echo "<u>Database to be downloaded :</u><br/>";

require_once("../../general/db_connect.php");
$query=mysqli_query($con1,"select * from fiscal_year order by serial DESC");
while($row=mysqli_fetch_array($query))
{
	$zip=new spectreZip();
    
	
	
	$Db = base64_decode(base64_decode($row['cYearDatabasePath']));
	
	$User = base64_decode(base64_decode($row['cDatabaseUserName']));
	
	$Pass = base64_decode(base64_decode($row['cDatabasePassword']));
	
	$ccyymmdd = date("d-m-Y");

	$line_count =create_backup_sql($User, $Pass, $Db);
	
	$zip->addDir("backup$Db$ccyymmdd/");
	$zip->render("backup$Db$ccyymmdd.zip",$type = 'save');
	
	$FolderPath="backup"."$Db$ccyymmdd";
	if (is_dir("backup"."$Db$ccyymmdd"))
	{
		$d = dir($FolderPath) or die("Wrong path: $FolderPath");
		while (false !== ($entry = $d->read())) 
		{
			if($entry != '.' && $entry != '..' && !is_dir($dir.$entry)) 
			unlink("backup"."$Db$ccyymmdd/".$entry);
		}
		$d->close();
		
		rmdir("backup"."$Db$ccyymmdd");
	}
	$FolderPath= "backup".$Db.$ccyymmdd."/";	

	$dl_url="backup-manager/backup"."$Db$ccyymmdd.zip";
	?>
	<a href="<?=$dl_url?>" style=\"text-decoration:none; color:#FF0033\"><?=$i. $Db." Database"?></a><br/>
	<?php
	$i++;
	break;
}
?>

</div>
</div>

