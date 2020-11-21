<?php
    require_once("../../config.php");
	$Err=false;
	$ErrMsg="";
	$focusid="";
	$PageMode="New";
    if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="";
	}
	else
	{
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
	}
	



	
	$CurrDate=date("Y-m-d");
	$str2 = date('Y-m-d', strtotime('-10 days', strtotime($CurrDate))); 
    $firstday = date ("m/d/Y", mktime(0, 0, 0, date("m") , date("d")-10 , date("Y")));
    $startDate=explode("/",$firstday);
    $BeforeDate = $str2;

if(isset($_POST['PartyID']) && $_POST['PartyID']!='')

{

	$FromDate=trim($_POST['FromDate']);

	$BeforeDate=trim($_POST['FromDate']);

	$dArr=explode("-",$FromDate);

	$fdate="$dArr[0]-$dArr[1]-$dArr[2]";

	$ToDate=$_POST['ToDate'];

	$CurrDate=$_GET['ToDate'];

	$tArr=explode("/",$ToDate);

	$tdate="$tArr[0]-$tArr[1]-$tArr[2]";

	

	$query=mysqli_query($con2,"select cPartyName  SUM(iNoPcsRec) as Rec ,party_order.cOrderCode ,concat(party_order.iOrderID,'') as iOrderID, dOrderDate, '' as yearprefix from party_order join party_order_detail on party_order.iOrderID  = party_order_detail.iOrderID join party_master on party_order.iPartyCode =party_master.iPartyID where party_order.iPartyCode =\"".$_GET['PartyID']."\" and bDeleted <>'1' and dOrderDate  between '$fdate' and '$tdate' group by party_order.iOrderID");
    
}

else if(isset($_POST['PartyID']) && $_POST['PartyID']=='')

{

	$FromDate=$_POST['FromDate'];

	$BeforeDate=$_POST['FromDate'];

	$dArr=explode("/",$FromDate);

	$fdate="$dArr[0]-$dArr[1]-$dArr[2]";

	$ToDate=$_POST['ToDate'];

	$CurrDate=$_POST['ToDate'];

	$tArr=explode("-",$ToDate);

	$tdate="$tArr[0]-$tArr[1]-$tArr[2]";



	$query=mysqli_query($con2,"select cPartyName ,fsYear, SUM(iNoPcsRec) as Rec ,party_order.cOrderCode ,concat(party_order.iOrderID,'') as iOrderID, dOrderDate, '' as yearprefix  from party_order join party_order_detail on party_order.iOrderID  = party_order_detail.iOrderID join party_master on party_order.iPartyCode =party_master.iPartyID where dOrderDate  between '$fdate' and '$tdate' and bDeleted <>'1' group by party_order.iOrderID");

	

}

else
{
    
	$query=mysqli_query($con2,"select tbl.* from(select cPartyName , SUM(iNoPcsRec) as Rec ,party_order.cOrderCode, concat(party_order.iOrderID,'') as iOrderID, dOrderDate,'' as yearprefix  from party_order join party_order_detail on party_order.iOrderID  = party_order_detail.iOrderID join party_master on party_order.iPartyCode =party_master.iPartyID  where bDeleted <>'1' and dOrderDate  between '$dStartDate' and '$CurrDate' group by party_order.iOrderID) as tbl order by tbl.dOrderDate DESC");
}	

?>
<script>
function LinkUrlClick(link,Data)
{
	var viewurl=atob(atob(link));
	URL=viewurl+"?Data="+Data;
    OpenWin=window.open(URL,"Report","toolbar=no,menubar=no,resizable=yes,location=no,scrollbars=no,width=1024,height=700");
    event.returnValue=false;
	
}
function deleteOrder(link,Data)
{
	$(".delete").attr("disabled",true);
	preloadfadeIn();
	$.ajax({
		url:'operations/party_order_delete.php',
		type:'POST',
		data:{"Data":Data},
		success:function(response)
		{
			var responseText=JSON.parse(response);
			if(responseText.error)
			{
				toastr.error(responseText.error_msg);
				preloadfadeOut();
			    $(".delete").attr("disabled",false);
			}
			else
			{
				toastr.success(responseText.error_msg);
				$(".delete").attr("disabled",false);
				MenuClick2('<?=$_POST[auth_info]?>','<?=base64_encode(base64_encode("operations/party_order_edit.php"))?>');
				
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			
			$('.page-content').load("404.php");
			preloadfadeOut();
			$(".delete").attr("disabled",false);
			
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			$(".delete").attr("disabled",false);
			 
		}
		else
		{
			preloadfadeOut();
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			$(".delete").attr("disabled",false);
			
		}
		
    }
		
	})
}
function editOrder(link,Data)
{
	$(".edit").attr("disabled",true);
	preloadfadeIn();
	$.ajax({
		url:'operations/party_order.php',
		type:'POST',
		data:{"auth_info":'<?=$_POST[auth_info]?>',"Data":Data},
		success:function(response)
		{
			
			$('.page-content').html(response);
			preloadfadeOut();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			
			$('.page-content').load("404.php");
			preloadfadeOut();
			$(".edit").attr("disabled",false);
			
			
		}
		else if(XMLHttpRequest.status==500)
		{
			toastr.error("Internal server error");
			preloadfadeOut();
			$(".edit").attr("disabled",false);
			 
		}
		else
		{
			preloadfadeOut();
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			$(".edit").attr("disabled",false);
			
		}
		
    }
		
	})
}

// function LinkUrlClick(viewurl)
// {
    // URL=viewurl+"&user=<?php echo $user; ?>&pass=<?php echo $pass; ?>&db=<?php echo $db; ?>";
    // OpenWin=window.open(URL,"Report","toolbar=no,menubar=no,resizable=yes,location=no,scrollbars=no,width=1024,height=700");
    // event.returnValue=false;

// }

</script>

<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Operations</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Party Orders</li>
			</ol>
		</div>

	</div>
</div>
</div>

<script>
$("document").ready(function(){
	//PageLoad('<?php if(isset($_GET['PartyID'])&& ($_GET['PartyID']!='')) echo "party_order_edit.php?FromDate=$_GET[FromDate]&ToDate=$_GET[ToDate]&PartyID=$_GET[PartyID]"; else if(isset($_GET['PartyID']) && ($_GET['PartyID']=='')) echo "party_order_edit.php?FromDate=$_GET[FromDate]&ToDate=$_GET[ToDate]"; else echo "party_order_edit.php"; ?>');
})
</script>

<div id="disptable">

<div class="card">
		<div class="card-body">
		<?php
		if($Err==true)
		{
			die($ErrMsg);
		}
		?>
<Form name="frmPartyOrder" method="POST">
    <input type="hidden" name="auth_info" id="auth_info" value="<?=$_POST['auth_info']?>">
	<input type="hidden" name="hfCurrDate" id="hfCurrDate" >
	
	<div class="row">
	  <div class="col-sm-6"></div>
	  <div class="col-sm-6">
	  <span class=" float-right">
	  <?php
        
		//$qry=mysqli_query($con2,"select cOrderCode from party_order where iOrderID IN (select max(iOrderID) from party_order)"); 
		$qry=mysqli_query($con2,"select cOrderCode from party_order order by iOrderID DESC limit 0,1");
		if ($qry && mysqli_num_rows($qry)>0)

		{

			$row=mysqli_fetch_array($qry);

			$CodeMsg="Last Purchase Order :".$row['cOrderCode'];

		}

		else

		{

			$CodeMsg="No Purchase Order Saved before";

		}

		echo $CodeMsg;

	   ?>
	   </span>
	  </div>
	</div>
	
	<div class="row">
	  <div class="col-sm-6">
	   
	      <input type="radio" name="rdb" id="rdNew" value="New"  onClick="Switch(this.id)">&nbsp;&nbsp;New Order
          <input type="radio" name="rdb" id="rdPending" value="Pending" onClick="Switch(this.id)" checked>&nbsp;&nbsp;List Orders
	   
	  </div>
	  <div class="col-sm-6"></div>
	</div>
	<br>
	<div class="row">
	  <div class="col-sm-6">
	    <label for="cmbSelParty">Party List</label>
		<select name="cmbSelParty" id="cmbSelParty" class="form-control"  tabindex=1>

												<option value=""><?=str_repeat("-",20) ?>Select Party<?=str_repeat("-",20) ?></option>

												<?php

													

													$result=mysqli_query ($con2,"select (iPartyID) as PartyID , cPartyName from party_master  order by cPartyName");

													if ($result && mysqli_num_rows($result)>0)

													{

														while ($row=mysqli_fetch_array($result))

														{

															echo  "<option value=\"$row[PartyID]\"";

															if ($row['PartyID']==$_GET['PartyID']) print "selected";

 															echo  ">$row[cPartyName]</option>";

														}

													}

												?>

											</select>
	  </div>
	  <div class="col-sm-3">
	    <label for="txtFromDate">From Date</label>
	    <input class="form-control" type="date"  value="<?=$BeforeDate; ?>" onFocus="this.blur()"  max="<?=$CurrDate?>" name="txtFromDate" id="txtFromDate">
	  </div>
	  <div class="col-sm-3">
	  <label for="txtToDate">To Date</label>
	   <input class="form-control" type="date"  value="<?=$CurrDate; ?>" onFocus="this.blur()"  max="<?=$CurrDate?>" name="txtToDate" id="txtToDate">
	  </div>
	  
	  <div class="col-sm-12" style="margin-top:30px">
	    <input type="button" name="btnGetData" id="btnGetData" value="Get Data" class="btn btn-primary waves-effect waves-light float-right" onClick="getData(this.id)">
	  </div>
	</div>
	<br>
	<div class="row">
	  <div class="col-sm-12" id="orderData">
	  <table id="example3" class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
	  <thead>
	   <tr>
	      <th>Sr No</th>
	      <th>Order Code</th>
	      <th>Order Date</th>
	      <th>Party Name</th>
		  <th>Rec.</th>
	      <th>Action</th>
	      
	   </tr>
	  </thead>
	  <tbody>
	  <?php
	  $i=1;
	  while($row=mysqli_fetch_array($query))
	  {
		  $print_url=base64_encode(base64_encode("operations/party_order_showpdf.php"));
		  $dataPrint=base64_encode(base64_encode($row['iOrderID']));
		  
		  ?>
		  <tr>
		  <td><?=$i?></td>
		  <td><?=$row['cOrderCode']?></td>
		  <td><?=$row['dOrderDate']?></td>
	      <td><?=$row['cPartyName'];?></td>
	      <td><?=$row['Rec'];?></td>
		  <td>
		   <?php
		  if($__view==true)
		  {
		  ?>
		  <button  type="button" class="btn btn-primary btn-sm waves-effect waves-light edit" onclick="editOrder('<?=$print_url?>','<?=$dataPrint?>')">Edit</button>
		  <?php
		  }
		  ?>
		  &nbsp;
		   <?php
		  if($__delete==true)
		  {
		  ?>
		  <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" onclick="deleteOrder('<?=$print_url?>','<?=$dataPrint?>')"><i class="fas fa-trash"></i></button>
		  <?php
		  }
		  ?>
		  &nbsp;
		  <?php
		  if($__delete==true)
		  {
		  ?>
		  <button type="button" class="btn btn-primary btn-sm waves-effect waves-light delete" onclick="LinkUrlClick('<?=$print_url?>','<?=$dataPrint?>')"><i class="fas fa-print"></i></button></td>
		  <?php
		  }
		  ?>
		  </tr>
		  <?php
		  $i++;
	  }
	  ?>
	  </tbody>
	  </table>
	  </div>
	</div>
	

</form>	
</div>

</div>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>

<script type="text/javascript" language="javascript" src="../js/party_order.js"></script>
<script>
$("document").ready(function(){
	$("#example3").dataTable().fnDestroy();
	$('#example3').DataTable({
		
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
})
</script>
<?php

	//$DispObj->DisplayTable();

?>

</div>
<!--
<iframe width=132 height=142 name="gToday:contrast:agenda.js" id="gToday:contrast:agenda.js" src="../js-calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>
-->
