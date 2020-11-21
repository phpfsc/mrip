<?php
error_reporting(1);
function get_menu()
{
	require_once("../config.php");
	if(empty(trim($__userImage)))
	{
		$__userImage="../assets/images/users/avatar-2.jpg";
	}
	
	$menuArray="";
	$menuArray.="<div class=\"vertical-menu\">";
	$menuArray.="<div class=\"h-100\">";
	
	
	$menuArray.='<div class="user-wid text-center py-4">
            <div class="user-img">
                <img src="'.$__userImage.'" alt="Logo" class="avatar-md mx-auto rounded-circle"
           style="opacity: .8">
            </div>

            <div class="mt-3">

                <a href="#" class="text-dark font-weight-medium font-size-16">'.$__FirstName." ".$__LastName."<br>(".$cLoginName.')</a>
                

            </div>
        </div>';
		
	$menuArray.='<div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">';
			$query=mysqli_query($con2,"select * from menu_master a inner join user_rights b on a.iMenuId=b.iMenuId where a.bActive='1'  && a.iParentId='0' && b.bView='1'  && b.iUserId=\"$__UserId\" order by iSNo");
	        
			while($row=mysqli_fetch_array($query))
			{
				  $temp_url =$row['cDir'].$__DS.$row['cPageUrl'];
				  $temp_xrl=$temp_url;
				  $url=base64_encode(base64_encode($temp_xrl));
				  $_id=md5($row['iMenuId']);
				  $permissions=base64_encode(base64_encode($row['bView'].",".$row['bAdd'].",".$row['bUpdate'].",".$row['bDelete']));
				$iMenuId=$row['iMenuId'];
				if($row['cPageUrl']=='')
				{
					$menuArray.='<li>
                    <a href="javascript: void(0)" class="has-arrow waves-effect">
                        <i class="'.$row['cIcon'].'"></i>
                        <span>'.$row['cMenuName'].'</span>
                    </a>';
					$sub_query=mysqli_query($con2,"select a.*,b.bView,b.bAdd,b.bUpdate,b.bDelete from menu_master a inner join user_rights b on a.iMenuId=b.iMenuId where a.iParentId=\"$iMenuId\" && b.bView='1' order by iSNo");
					if(mysqli_num_rows($sub_query))
					{
						$menuArray.='<ul class="sub-menu" aria-expanded="false">';
						while($row_subquery=mysqli_fetch_array($sub_query))
						{
							  $temp_url =$row_subquery['cDir'].$__DS.$row_subquery['cPageUrl'];
							  $temp_xrl=$temp_url;
							  $url=base64_encode(base64_encode($temp_xrl));
							  $_id=md5($row_subquery['iMenuId']);
							  
							$permissions=base64_encode(base64_encode($row_subquery['bView'].",".$row_subquery['bAdd'].",".$row_subquery['bUpdate'].",".$row_subquery['bDelete']));
							$menuArray.='<li><a href="javascript: void(0)" class="waves-effect" id="'.$_id.'" onClick="MenuClick(this.id,'."'$permissions'".','."'$url'".')">'.$row_subquery['cMenuName'].'</a></li>';
						}
						$menuArray.='</ul>';
						
					}
					
                    $menuArray.='</li>';
				}
				else
				{
					$menuArray.='<li>
                    <a href="javascript:void(0)" class="waves-effect" id="'.$_id.'" onClick="MenuClick(this.id,'."'$permissions'".','."'$url'".')">
                        <i class="'.$row['cIcon'].'"></i>
                        <span>'.$row['cMenuName'].'</span>
                    </a>
                </li>';
				}
				
			}
	
	        

               
	$menuArray.='</div></ul>';
	
	
	
	$menuArray.="</div>";
	$menuArray.="</div>";
	echo $menuArray;
	
}
get_menu();

?>