<?php
    if($_SERVER['HTTP_HOST']=='localhost')
	{
		$_hostname="localhost";
		$_username="root";
		$_password="";
		$_dbcompanyname="cloudvxm_mrip_sys";
		
		$__DS='/';

		$__PanelPreFix='general';
		
		$__PanelBaseUrl='E:/xampp/htdocs/mrip';
		$__PanelWebUrl='http://localhost/mrip';
		$__PanelAdminDir=$__PanelBaseUrl;
		$__PanelClassesDir=$__PanelBaseUrl.$__DS.'default'.$__DS.'Classes'.$__DS;
		$__PanelAdminUrl=$__PanelWebUrl.$__DS.'default';
	}
	else
	{
		/**  need to set up at server **/
		$_hostname="localhost";
		$_dbcompanyname="cloudvxm_mrip_sys";
		$_username="cloudvxm_mrip";
		$_password="cloudvxm_mrip";
		$__DS='/';
        $__PanelPreFix='general';
		$__PanelBaseUrl='/home2/cloudvxm/public_html/mrip';
		$__PanelWebUrl='http://cloudganga.in/mrip';
		$__PanelAdminDir=$__PanelBaseUrl;
		$__PanelClassesDir=$__PanelWebUrl.$__DS.'default'.$__DS.'Classes'.$__DS;
		$__PanelAdminUrl=$__PanelWebUrl.$__DS.'default';
		/**  need to set up at server **/
	}
    $SysDB=$_dbcompanyname;
    $con1 = mysqli_connect($_hostname, $_username, $_password,$SysDB);
    if(!$con1 && mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        
    }
?>
