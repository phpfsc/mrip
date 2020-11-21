<?php
    error_reporting(1);
    session_start();
	$_SESS_ID= session_id();
	date_default_timezone_set("Asia/Calcutta"); 
	$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
	
	 $cDatabaseUserName=$_SESSION['DbUsername'];
	 $cDatabasePassword=$_SESSION['DbPassword'];
	 $cYearDatabasePath=$_SESSION['Database'];
	if($_SERVER['HTTP_HOST']=='localhost')
	{
		$_hostname="localhost";
		$_username="root";
		$_password="";
		$_db=$_SESSION['Database'];
		
		$__DS='/';

		$__PanelPreFix='general';
		
		$__PanelBaseUrl='E:/xampp/htdocs/mrip';
		$__PanelWebUrl='http://localhost/mrip';
		$__PanelAdminDir=$__PanelBaseUrl;
		$__PanelClassesDir=$__PanelBaseUrl.$__DS.'default'.$__DS.'Classes'.$__DS;
		$__PanelAdminUrl=$__PanelWebUrl;
	}
	else
	{
		
		$_hostname="localhost";
		
		$_db=$_SESSION['Database'];
		$_username=$_SESSION['DbUsername'];
		$_password=$_SESSION['DbPassword'];
		
		
		$__DS='/';

		$__PanelPreFix='general';
		
		$__PanelBaseUrl='/home2/cloudvxm/public_html/mrip';
		$__PanelWebUrl='http://cloudganga.in/mrip';
		$__PanelAdminDir=$__PanelBaseUrl;
		$__PanelClassesDir=$__PanelWebUrl.$__DS.'default'.$__DS.'Classes'.$__DS;
		$__PanelAdminUrl=$__PanelWebUrl;
	}
	
	if(!(isset($_SESSION['session_id']) && $_SESSION['session_id']!='')){
	    if($pageWasRefreshed==true) 
		{
           header('Location:'.$__PanelWebUrl.$__DS.'index.php');
        } else
			{
                echo "<script>
                
        			window.location.href='".$__PanelWebUrl.$__DS."index.php';
        		</script>";
            }
	}
	
     
    
    
    
    $__FirstName=$_SESSION['first_name'];   //first_name
    $__LastName=$_SESSION['last_name'];   //last Name
	$cLoginName=$_SESSION['login'];
    $__CompanyCode='Prakash Gears';             //companyCode Need To change 
    $__sysDate=$_SESSION['CurrentDate'];
    
    $__userImage=$_SESSION['UserImage'];  //user Image   
	$_yearCode=$_SESSION['YearString'];
	$YearString=$_SESSION['YearString'];
    $__YearString=$_SESSION['Current_Year'];   //full year string
    
	$sysDate=$_SESSION['CurrentDate'];  //current date from database
	$dStartDate=$_SESSION['SessionStartDate'];  //session start date
    $__UserId=$_SESSION['cUserId'];

	$_de_date_format="d/m/Y";
	$_de_date_time_format="d/m/Y H:i:s";
	$_de_date_format_short="d/m/Y";
	
	$_CurrentDate_Ymd=date('Y-m-d');
	$_CurrentDate_dmY=date('d-m-Y');
	$_CurrentDate_YmdHis=date('Y-m-d H:i:s');
	
	$con2=mysqli_connect($_hostname,$_username,$_password,$_db);
	if(!$con2)
	{
		die("Sorry unable to connect");
	}
	
	
	//include($__PanelAdminDir.$__DS.$__PanelPreFix.'/authenticate.php');
	//include($__PanelAdminDir.$__DS.$__PanelPreFix.'/amountinwords.php'); 
?>
