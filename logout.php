<?php
    include($__PanelWebUrl.$__DS.'config.php');
    $check_auth=mysqli_query($con2,"select * from user_log where iUserId='$__UserId' and bActive=1 and cSessionId='$_SESS_ID'");
    if($check_auth && mysqli_num_rows($check_auth)>0){
        $log_query="update user_log set dSessionEndDate=now(),tSessionEndTime='$time',bActive=0,cLogoutBy='$__UserName' where iUserId='$__UserId' and cSessionId='$_SESS_ID'";
        if($__UserId != ''){
            $log=mysqli_query($con2,$log_query);
            if($log){
                session_unset();
                session_destroy();
                header('Location:'.$__PanelWebUrl.$__DS.'index.php');
            }
        }
    }else{
        session_unset();
        session_destroy();
        header('Location:'.$__PanelWebUrl.$__DS.'index.php');
    }
    
	
?>