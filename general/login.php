<?php
/** used for login screen **/
error_reporting(1);
session_start();
$error = true;
$error_msg = 'Error in login.';
$focus_id='txtUserName';
$check_login='';
$response_array = array();
 date_default_timezone_set("Asia/Calcutta"); 
 $time=date('H:i:s',time());
//Check For Any Data To Process   
if (isset($_POST)) 
{
    include('db_connect.php');
	$error=false;
	$txtfsyear=$_POST['txtfsyear'];
	$username=$_POST['username'];
	$userpassword=$_POST['userpassword'];
	if($txtfsyear==''){
		$error=true;
		$error_msg='Please enter your username or Email Id.';
		$focus_id='txtUserName';
	}else if($username==''){
		$error=true;
		$error_msg='Please enter your password.';
		$focus_id='txtUserName';
	}
	else{
	     $queryFiscalYear=mysqli_query($con1,"select * from fiscal_year where cCurrentSession=\"$txtfsyear\"");
	if(!mysqli_num_rows($queryFiscalYear) || !$queryFiscalYear)
	{
		$error=true;
		$error_msg="Unable to connect";
	}
	else
	{
		 $rowFiscalYear=mysqli_fetch_array($queryFiscalYear);
		 $cYearDatabasePath=base64_decode(base64_decode($rowFiscalYear['cYearDatabasePath']));  //database name
		 $cDatabaseUserName=base64_decode(base64_decode($rowFiscalYear['cDatabaseUserName']));  //database username
		 $cDatabasePassword=base64_decode(base64_decode($rowFiscalYear['cDatabasePassword']));  //database username
		 $cCurrentSession=$rowFiscalYear['cCurrentSession'];  //year string
		 $cSessionCode=$rowFiscalYear['cSessionCode'];  //year string
		 $dStartDate=$rowFiscalYear['dStartDate'];  //start date
		 $dEndDate=$rowFiscalYear['dEndDate'];  //end date
		 $con_temp=mysqli_connect("localhost",$cDatabaseUserName,$cDatabasePassword);
		 if(!$con_temp)
		 {
			 $error=true;
			 $error_msg="Failed to connect to MySQL: " . mysqli_connect_error();
		 }
		 else
		 {
			 $query1="select * from $cYearDatabasePath.user_master where cLoginName=\"$username\"";
	    $result1=mysqli_query($con_temp,$query1);
	    if($result1 && mysqli_num_rows($result1)>0)
	    {
			
	        $rowcount1=0;
	        while($row1=mysqli_fetch_array($result1))
	        {
	            $rowcount1++;
	            if(base64_encode(base64_encode($userpassword))==$row1['cPassword'])
	            {
	                $query2="select * from $cYearDatabasePath.activedate";
	                $result2=mysqli_query($con_temp,$query2);
	                if($result2 && mysqli_num_rows($result2)>0)
	                {
	                    $rowcount2=0;
	                    while($row2=mysqli_fetch_array($result2))
	                    {
	                        
	                       
	                           
	                           
        	                        $result4=mysqli_query($con_temp,"select * from $cYearDatabasePath.company_master");
        	                        if($result4 && mysqli_num_rows($result4)>0)
        	                        {
        	                            $rowcount4=0;
        	                            while($row4=mysqli_fetch_array($result4))
        	                            {
        	                               
                	                            
                	                            
                        	                        $result6=mysqli_query($con_temp,"SELECT * FROM $cYearDatabasePath.company_master");
                        	                        if($result6 && mysqli_num_rows($result6)>0)
                        	                        {
                        	                           
                        	                            while($row6=mysqli_fetch_array($result6))
                        	                            {
                        	                                
                        	                               
                                	                               
                                    	                                session_start();
    																	session_cache_expire(1440);
    																	session_start();
    																	session_regenerate_id();
    																	$_SESS_ID=session_id();
    																	
    																	if($_SESS_ID !='')
    																	{
    																	   
    																	         
																				 $cSessionCode=$rowFiscalYear['cSessionCode'];  //year string
																				 $dStartDate=$rowFiscalYear['dStartDate'];  //start date
																				 $dEndDate=$rowFiscalYear['dEndDate'];  //end date
					
																				$_SESSION['Login_Success']=true;
																				$_SESSION['YearString']=$cSessionCode; //year code
																				$_SESSION['Current_Year']=$cCurrentSession; //full year string
																				$_SESSION['CurrentDate']=$row2['sysDate'];  //current date from database
																				$_SESSION['SessionStartDate']=$dStartDate;  //session start date
																				$_SESSION['SessionEndDate']=$dEndDate;  //session end date
																				$_SESSION['UserImage']=$row1['userImage'];  //session start date
																				
																				$_SESSION['DbUsername']=$cDatabaseUserName;
																				$_SESSION['DbPassword']=$cDatabasePassword;
																				$_SESSION['Database']=$cYearDatabasePath;
																				
																				
																				$_SESSION['login']=$row1['cLoginName'];					//set session
																				$_SESSION['first_name']=$row1['first_name'];
																				$_SESSION['last_name']=$row1['last_name'];
																				$_SESSION['cUserId']=$row1['cUserId'];
																				$_SESSION['Month']=Date('m');
    																	        
    																	        $_SESSION['session_id']=$_SESS_ID;
    																	        $_SESSION['last_action'] = time();
    																	        if($_POST['remember'] == 1){
    																	            $_SESSION['expire_time'] = 60;
    																	        }else{
    																	            $_SESSION['expire_time'] = 15;
    																	        }
    																	        
                																
                																
            																    $error_msg="Login Successfully.";
            																    
    																	    
    																	}
                                	                                
                                	                            
                        										
                                	                       
                        	                            }
                        	                            
                        	                        }else{
                        	                            $error=true;
                                    		            $error_msg='Error in login,Please try again 6.';
                                    		            $focus_id='txtSchoolName';
                        	                        }
                	                            
                	                            
                	                        
        	                            }
        	                            
        	                        }else{
        	                            $error=true;
                    		            $error_msg='Error in login,Please try again 4.';
                    		            $focus_id='txtSchoolName';
        	                        }
	                            
	                            
	                        
	                    }
	                    
	                }else{
	                    $error=true;
    		            $error_msg='Error in login,Please try again 2.';
    		            $focus_id='txtUserName';
	                }
	               
	            }else{
	                $error=true;
		            $error_msg="Password did not matched.";
		            $focus_id="txtPassword";
	            }
	        }
	        if($rowcount1==0)
			{
				$error=true;
				$error_msg="Error in login1...";
			}
	    }else{
	        $error=true;
            $error_msg="Please enter correct username or password.".mysqli_error($con1);
            $focus_id="txtUserName";
	    }
	}
	}
	    
	}
}

$response_array['error'] = $error;
$response_array['error_msg'] = $error_msg;
$response_array['focus_id'] = $focus_id;
$response_array['check_login'] =$check_login;
$response_array['page'] =$__PanelAdminUrl;
echo json_encode($response_array);
?>