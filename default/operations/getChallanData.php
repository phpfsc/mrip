<?php

require_once("../../config.php");
$Err=false;
$ErrMsg="";

if(!empty(trim($_POST['PartyID'])) && (!empty($_POST['FromDate']) && !empty($_POST['ToDate'])))
{
	
	            //echo "select tbl.* from(select cPartyName ,fsYear, SUM(iNoPcsDisp + iNoPcsReturn) as Disp ,party_challan.cChallanCode ,party_challan.iChallanID as iChallanID, dChallanDate ,'' as yearprefix  from party_challan join party_challan_detail on party_challan.iChallanID  = party_challan_detail.iChallanID join party_master on party_challan.iPartyCode =party_master.iPartyID where party_challan.iPartyCode =\"".$_POST['PartyID']."\" and dChallanDate  between '$fDate' and '$tDate' group by party_challan.iChallanID)as tbl order by tbl.iChallanID DESC";die();
                $sDate=trim($_POST['FromDate']);
				$fDate=trim($_POST['FromDate']);
				$tDate=trim($_POST['ToDate']);
		        $i=1;
				$data="";
				$data.="<table class=\"table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable collapsed\" id=\"example3\">";
				$data.="<thead>";
				$data.="<tr>";
				$data.="<th>Sr No</th>";
				$data.="<th>Challan Code</th>";
				$data.="<th>Date</th>";
				$data.="<th>Party Name</th>";
				$data.="<th>Disp.</th>";
				$data.="<th>Action</th>";
				$data.="</tr>";
				$data.="</thead>";
				$data.="<tbody>";
				
				
				$queryData=mysqli_query($con2,"select tbl.* from(select cPartyName , SUM(iNoPcsDisp + iNoPcsReturn) as Disp ,party_challan.cChallanCode ,party_challan.iChallanID as iChallanID, dChallanDate ,'' as yearprefix  from party_challan join party_challan_detail on party_challan.iChallanID  = party_challan_detail.iChallanID join party_master on party_challan.iPartyCode =party_master.iPartyID where party_challan.iPartyCode =\"".$_POST['PartyID']."\" and dChallanDate  between '$fDate' and '$tDate' group by party_challan.iChallanID)as tbl order by tbl.iChallanID DESC");
				while($rowData=mysqli_fetch_array($queryData))
				{
					$Data=$rowData['fsYear']."~".$rowData['iChallanID']."~".$rowData['yearprefix'];
					if(empty($rowData['yearprefix']))
					{
					    $databaseYear="";
					}
					else
					{
					    $databaseYear="(Old)";
					}
				    $Data=base64_encode(base64_encode($Data));
				 $data.="<tr><td>".$i."</td><td>".$rowData['cChallanCode']. $databaseYear."</td><td>".$rowData['dChallanDate']."</td><td>".$rowData['cPartyName']."</td><td>".$rowData['Disp']."</td><td>
		  <button  type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light edit\" onclick=\"editChallan('$Data')\">Edit</button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light\" onclick=\"DeleteRecord('$Data')\"><i class=\"fas fa-trash\"></i></button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light delete\" onclick=\"LinkUrlClick('$Data')\"><i class=\"fas fa-print\"></i></button>
		  </td></tr>";
					$i++;
				}
				$data.="</thead><tbody>";
	
		
}

else if(!empty($_POST['FromDate']) && !empty($_POST['ToDate']))
{
	            $sDate=trim($_POST['FromDate']);
				$fDate=trim($_POST['FromDate']);
				$tDate=trim($_POST['ToDate']);
		        $i=1;
				$data="";
				$data.="<table class=\"table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable collapsed\" id=\"example3\">";
				$data.="<thead>";
				$data.="<tr>";
				$data.="<th>Sr No</th>";
				$data.="<th>Challan Code</th>";
				$data.="<th>Date</th>";
				$data.="<th>Party Name</th>";
				$data.="<th>Disp.</th>";
				$data.="<th>Action</th>";
				$data.="</tr>";
				$data.="</thead>";
				$data.="<tbody>";
				
				
				$queryData=mysqli_query($con2,"select tbl.* from(select cPartyName , SUM(iNoPcsDisp + iNoPcsReturn) as Disp ,party_challan.cChallanCode ,party_challan.iChallanID as iChallanID, dChallanDate ,'' as yearprefix  from party_challan join party_challan_detail on party_challan.iChallanID  = party_challan_detail.iChallanID join party_master on party_challan.iPartyCode =party_master.iPartyID where  dChallanDate  between \"$fDate\" and \"$tDate\" group by party_challan.iChallanID )as tbl order by tbl.iChallanID DESC");
				
				
					
				
				while($rowData=mysqli_fetch_array($queryData))
				{
					$Data=$rowData['fsYear']."~".$rowData['iChallanID']."~".$rowData['yearprefix'];
					if(empty($rowData['yearprefix']))
					{
					    $databaseYear="";
					}
					else
					{
					    $databaseYear="(Old)";
					}
				    $Data=base64_encode(base64_encode($Data));
				 $data.="<tr><td>".$i."</td><td>".$rowData['cChallanCode']. $databaseYear."</td><td>".$rowData['dChallanDate']."</td><td>".$rowData['cPartyName']."</td><td>".$rowData['Disp']."</td><td>
		  <button  type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light edit\" onclick=\"editChallan('$Data')\">Edit</button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light\" onclick=\"DeleteRecord('$Data')\"><i class=\"fas fa-trash\"></i></button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light delete\" onclick=\"LinkUrlClick('$Data')\"><i class=\"fas fa-print\"></i></button>
		  </td></tr>";
					$i++;
				}
				$data.="</thead><tbody>";
	
}
else if(!empty(trim($_POST['PartyID'])))
{
	
		 
		    $sDate=trim($_POST['FromDate']);
				$fDate=trim($_POST['FromDate']);
				$tDate=trim($_POST['ToDate']);
		        $i=1;
				$data="";
				$data.="<table class=\"table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable collapsed\" id=\"example3\">";
				$data.="<thead>";
				$data.="<tr>";
				$data.="<th>Sr No</th>";
				$data.="<th>Challan Code</th>";
				$data.="<th>Date</th>";
				$data.="<th>Party Name</th>";
				$data.="<th>Disp.</th>";
				$data.="<th>Action</th>";
				$data.="</tr>";
				$data.="</thead>";
				$data.="<tbody>";
				
				
				$queryData=mysqli_query($con2,"select tbl.* from(select cPartyName , SUM(iNoPcsDisp + iNoPcsReturn) as Disp ,party_challan.cChallanCode ,party_challan.iChallanID as iChallanID, dChallanDate ,'' as yearprefix  from party_challan join party_challan_detail on party_challan.iChallanID  = party_challan_detail.iChallanID join party_master on party_challan.iPartyCode =party_master.iPartyID where party_challan.iPartyCode =\"".$_POST['PartyID']."\"  group by party_challan.iChallanID)as tbl order by tbl.iChallanID DESC");
				
				
					
				
				while($rowData=mysqli_fetch_array($queryData))
				{
					$Data=$rowData['fsYear']."~".$rowData['iChallanID']."~".$rowData['yearprefix'];
					if(empty($rowData['yearprefix']))
					{
					    $databaseYear="";
					}
					else
					{
					    $databaseYear="(Old)";
					}
				    $Data=base64_encode(base64_encode($Data));
				 $data.="<tr><td>".$i."</td><td>".$rowData['cChallanCode']. $databaseYear."</td><td>".$rowData['dChallanDate']."</td><td>".$rowData['cPartyName']."</td><td>".$rowData['Disp']."</td><td>
		  <button  type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light edit\" onclick=\"editChallan('$Data')\">Edit</button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light\" onclick=\"DeleteRecord('$Data')\"><i class=\"fas fa-trash\"></i></button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light delete\" onclick=\"LinkUrlClick('$Data')\"><i class=\"fas fa-print\"></i></button>
		  </td></tr>";
					$i++;
				}
				$data.="</thead><tbody>";			
			
		
		
	
}
else
{
	echo "Invalid Request";
}
$response_array['error']=$Err;
$response_array['error_msg']=$ErrMsg;
$response_array['data']=$data;
echo json_encode($response_array);
?>