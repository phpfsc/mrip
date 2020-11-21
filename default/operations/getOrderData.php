<?php
//require_once("../../general/db_connect.php");
require_once("../../config.php");
$Err=false;
$ErrMsg="";
//print_r($_POST);
if(!empty(trim($_POST['PartyID'])) && (!empty($_POST['FromDate']) && !empty($_POST['ToDate'])))
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
				$data.="<th>Order Code</th>";
				$data.="<th>Order Date</th>";
				$data.="<th>Party Name</th>";
				$data.="<th>Rec</th>";
				$data.="<th>Action</th>";
				$data.="</tr>";
				$data.="</thead>";
				$data.="<tbody>";
				
				
				
				$queryData=mysqli_query($con2,"select tbl.* from(select cPartyName , SUM(iNoPcsRec) as Rec ,party_order.cOrderCode, concat(party_order.iOrderID,'') as iOrderID, dOrderDate,'' as yearprefix  from party_order join party_order_detail on party_order.iOrderID  = party_order_detail.iOrderID join party_master on party_order.iPartyCode =party_master.iPartyID   where party_order.iPartyCode =\"".trim($_POST['PartyID'])."\" and bDeleted <>'1' and dOrderDate  between \"$fDate\" and \"$tDate\" group by party_order.iOrderID) as tbl order by tbl.dOrderDate DESC");
				
				
					
				
				while($rowData=mysqli_fetch_array($queryData))
				{
					$print_url=base64_encode(base64_encode("operations/party_order_showpdf.php"));
		            $dataPrint=base64_encode(base64_encode($rowData['iOrderID']));
					$data.="<tr><td>".$i."</td><td>".$rowData['cOrderCode']."</td><td>".$rowData['dOrderDate']."</td><td>".$rowData['cPartyName']."</td><td>".$rowData['Rec']."</td><td>
		  <button  type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light edit\" onclick=\"editOrder('$print_url','$dataPrint')\">Edit</button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light\" onclick=\"deleteOrder('$print_url','$dataPrint')\"><i class=\"fas fa-trash\"></i></button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light delete\" onclick=\"LinkUrlClick('$print_url','$dataPrint')\"><i class=\"fas fa-print\"></i></button>
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
				$data.="<th>Order Code</th>";
				$data.="<th>Order Date</th>";
				$data.="<th>Party Name</th>";
				$data.="<th>Rec</th>";
				$data.="<th>Action</th>";
				$data.="</tr>";
				$data.="</thead>";
				$data.="<tbody>";
				
				
				
				$queryData=mysqli_query($con2,"select tbl.* from(select cPartyName , SUM(iNoPcsRec) as Rec ,party_order.cOrderCode, concat(party_order.iOrderID,'') as iOrderID, dOrderDate,'' as yearprefix  from party_order join party_order_detail on party_order.iOrderID  = party_order_detail.iOrderID join party_master on party_order.iPartyCode =party_master.iPartyID   where  bDeleted <>'1' and dOrderDate  between \"$fDate\" and \"$tDate\" group by party_order.iOrderID) as tbl order by tbl.dOrderDate DESC");
						
				
				while($rowData=mysqli_fetch_array($queryData))
				{
					$print_url=base64_encode(base64_encode("operations/party_order_showpdf.php"));
		            $dataPrint=base64_encode(base64_encode($rowData['iOrderID']));
					$data.="<tr><td>".$i."</td><td>".$rowData['cOrderCode']."</td><td>".$rowData['dOrderDate']."</td><td>".$rowData['cPartyName']."</td><td>".$rowData['Rec']."</td><td>
		  <button  type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light edit\" onclick=\"editOrder('$print_url','$dataPrint')\">Edit</button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light\" onclick=\"deleteOrder('$print_url','$dataPrint')\"><i class=\"fas fa-trash\"></i></button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light delete\" onclick=\"LinkUrlClick('$print_url','$dataPrint')\"><i class=\"fas fa-print\"></i></button>
		  </td></tr>";
					$i++;
				}
				$data.="</thead><tbody>";
	
}
else if(!empty(trim($_POST['PartyID'])))
{
	
		 
		    $i=1;
			$data="";
			$data.="<table class=\"table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable collapsed\" id=\"example3\">";
			$data.="<thead>";
			$data.="<tr>";
			$data.="<th>Sr No</th>";
			$data.="<th>Order Code</th>";
			$data.="<th>Order Date</th>";
			$data.="<th>Party Name</th>";
			$data.="<th>Rec</th>";
			$data.="<th>Action</th>";
			$data.="</tr>";
			$data.="</thead>";
			$data.="<tbody>";
			
			   
				$queryData=mysqli_query($con2,"select tbl.* from(select cPartyName , SUM(iNoPcsRec) as Rec ,party_order.cOrderCode, concat(party_order.iOrderID,'') as iOrderID, dOrderDate,'' as yearprefix  from party_order join party_order_detail on party_order.iOrderID  = party_order_detail.iOrderID join party_master on party_order.iPartyCode =party_master.iPartyID where party_order.iPartyCode =\"".trim($_POST['PartyID'])."\" and    bDeleted <>'1'  group by party_order.iOrderID) as tbl order by tbl.dOrderDate DESC");
				
				
				while($rowData=mysqli_fetch_array($queryData))
				{
					$print_url=base64_encode(base64_encode("operations/party_order_showpdf.php"));
		            $dataPrint=base64_encode(base64_encode($rowData['iOrderID']));
					$data.="<tr><td>".$i."</td><td>".$rowData['cOrderCode']."</td><td>".$rowData['dOrderDate']."</td><td>".$rowData['cPartyName']."</td><td>".$rowData['Rec']."</td><td>
		  <button  type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light edit\" onclick=\"editOrder('$print_url','$dataPrint')\">Edit</button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light\" onclick=\"deleteOrder('$print_url','$dataPrint')\"><i class=\"fas fa-trash\"></i></button>
		  &nbsp;<button type=\"button\" class=\"btn btn-primary btn-sm waves-effect waves-light delete\" onclick=\"LinkUrlClick('$print_url','$dataPrint')\"><i class=\"fas fa-print\"></i></button>
		  </td></tr>";
					$i++;
				}

				
				
			
			
             $data.="</tbody></table>";			
			
		
		
	
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