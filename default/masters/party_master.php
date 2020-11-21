<?php
    require ('../../config.php');
    $Err=false;
    $ErrMsg="";
	if(empty(trim($_POST['auth_info'])))
	{
		$Err=true;
		$ErrMsg="Sorry No Direct Access Allowed";
	}
	else
	{
		$auth_info=explode(",",base64_decode(base64_decode(trim($_POST['auth_info']))));
		$__view=$auth_info[0]; //view
		$__add=$auth_info[1]; //add 
		$__update=$auth_info[2]; //update
		$__delete=$auth_info[3]; //delete
		if (!empty($_POST['iPartyID']))
					{
					$PageMode="Edit";
					$iPartyID=base64_decode(base64_decode(($_POST['iPartyID'])));
					$result=mysqli_query($con2,"select * from party_master where iPartyID=\"$iPartyID\"");
					if ($result && mysqli_num_rows($result)>0)
					{
						while ($row=mysqli_fetch_array($result))
						{
							$PartyName=$row['cPartyName'];
							$ContactPerson=$row['cContactPerson'];
							$PhoneNo=$row['cPhone'];
							$MobileNo=$row['cMobile'];
							$FaxNo=$row['cFax'];
							$TINNo=$row['cTINNo'];
							$gstCode=$row['cGSTIn'];
							$stateCode=$row['cStateCode'];
							$STNo=$row['cSTNo'];
							$CSTNo =$row['cCSTNo'];
							$Address=$row['cAddress'];
							$Transporter=$row['cTransporter'];
							$Remarks=$row['cRemarks'];
							$PartType=$row['cPartType'];
							$Active=$row['bActive'];
						}
						$result1=mysqli_query($con2,"select * from party_master_detail where iPartyID='$iPartyID' ORDER BY  `iSNo`");
						if ($result1 && mysqli_num_rows($result1)>0)
						{
							while ($row1=mysqli_fetch_array($result1))
							{
								
								$FirmName=$row1['cFirmName'];
								$FrimGSTCode=$row1['cGSTIn'];
								$FrimStateCode=$row1['cStateCode'];
								$FrimPhNo=$row1['cFirmPhNo'];
								$FirmAddress=$row1['cFirmAddress'];
								$FirmTINNO=$row1['cFirmTINNO'];
								if (empty($ItemData))
								{
									//echo "<script>alert('array is empty')</script>";
									$ItemData="$FirmName~ArrayItem~$FrimPhNo~ArrayItem~$FirmAddress~ArrayItem~$FirmTINNO~ArrayItem~$FrimGSTCode~ArrayItem~$FrimStateCode";
								}
								else
								{
									//echo "<script>alert('array is not empty')</script>";
									$ItemData.="~Array~".$FirmName."~ArrayItem~".$FrimPhNo."~ArrayItem~".$FirmAddress."~ArrayItem~".$FirmTINNO."~ArrayItem~".$FrimGSTCode."~ArrayItem~".$FrimStateCode;
								}
							}
						}
						else
						{
							
							$ItemData="";
						}
						
					}
					else
				   {
						$Err=true;
						$ErrMsg[]="Error in Getting data...";
				   }
			   }
			   else
			   {
					$PageMode="New";
			   }
}
	
	
	

  

   if (isset($_POST['btnSave']))
   {
	   if (trim($_POST['txtPartyName'])=="")
	   {
			$Err=true;
			$ErrMsg[]="Please enter the Party Name...";
	   }
	   else
	   {
			if($PageMode=='New')
		    {
				$CheckName=mysqli_query($con2,"select * from party_master where cPartyName=\"".trim($_POST['txtPartyName'])."\"");
		    }
			else
		   {
				$CheckName=mysqli_query ($con2,"select * from party_master where cPartyName=\"".trim($_POST['txtPartyName'])."\" and iPartyID<>'$iPartyID'");
		   }
		   if ($CheckName)
		   {
			   if (mysqli_num_rows($CheckName)>0)
			   {
					$Err=true;
					$ErrMsg[]="Party Name already exist..";
			   }
		   }
		   else
		   {
				$Err=true;
				$ErrMsg[]="Error in checking Duplicacy of Party Name...";
		   }
	   }
	   $PartyName=trim($_POST['txtPartyName']);
	   $ContactPerson=trim($_POST['txtContactPerson']);
	   $PhoneNo=trim($_POST['txtPhoneNo']);
	   $MobileNo=trim($_POST['txtMobileNo']);
	   $FaxNo=trim($_POST['txtFaxNo']);
	   $TINNo=trim($_POST['txtTINNo']);
	   $gstCode=trim($_POST['txtGSTCode']);
	   $stateCode=trim($_POST['txtStateCode']);
	   $STNo=trim($_POST['txtSTNo']);
	   $CSTNo =trim($_POST['txtCSTNo']);
	   $Address=trim($_POST['txtAddress']);
	   $Transporter=trim($_POST['txtTransporter']);
	   $Remarks=trim($_POST['txtRemarks']);
	   $PartType=trim($_POST['cmbPartType']);
	   if($_POST[chkActive]=='On' || $_POST[chkActive]=='on')
			$Active=1;
		else
			$Active=0;

	   $ItemData=trim($_POST['hdBillingFirm']);

		$validate=false;
		if(!$Err)
			$validate=true;
		if ($validate)
	    {
			$rollback=false;
			if (isset($_GET['iPartyID']))
			{
				$ErrMsg[]="Error in Updating Records...";
				mysqli_query($con2,"begin");
				$result=mysqli_query ($con2,"update party_master set cContactPerson=\"".$ContactPerson."\" ,cAddress=\"".$Address."\", cPhone=\"".$PhoneNo."\",cMobile=\"".$MobileNo."\",cFax=\"".$FaxNo."\", cSTNo=\"".$STNo."\", cCSTNo=\"".$CSTNo."\", cTINNo=\"".$TINNo."\", cTransporter=\"".$Transporter."\", cRemarks=\"".$Remarks."\" , cGSTIn=\"".$gstCode."\" , cStateCode=\"".$stateCode."\" ,cPartType=\"".$PartType."\", bActive='$Active' where iPartyID ='$iPartyID'");
				if(!$result)
				{
					$rollback=true;
					$Err=true;
				}
				$result=mysqli_query ($con2,"DELETE from party_master_detail where iPartyID='$iPartyID'");
				if(!$result)
				{
					$rollback=true;
					$Err=true;
				}
			}
			else
			{
				$ErrMsg[]="Error in Inserting Records...";
				mysqli_query($con2,"begin");
				$result=mysqli_query($con2,"INSERT into party_master (cPartyName, cContactPerson,cAddress, cPhone, cMobile, cFax, cSTNo, cCSTNo, cTINNo, cTransporter, cRemarks,cPartType,cGSTIn,cStateCode, bActive) values (\"".$PartyName."\",\"".$ContactPerson."\",\"".$Address."\",\"".$PhoneNo."\",\"".$MobileNo."\",\"".$FaxNo."\",\"".$STNo."\",\"".$CSTNo."\",\"".$TINNo."\",\"".$Transporter."\",\"".$Remarks."\",\"".$PartType."\",\"".$gstCode."\",\"".$stateCode."\",'$Active')");
				if(!$result)
				{
					$rollback=true;
					$Err=true;
				}
			}
			$j=0;
			if($ItemData!="")
			{
				$FirmData=explode("~Array~",$ItemData);
				for ($i=0;$i<sizeof($FirmData);$i++)
				{
					$j=$j+1;
					$Data=explode("~ArrayItem~",$FirmData[$i]);
					
					$result1=mysqli_query($con2,"INSERT into party_master_detail values ((select iPartyID from party_master where cPartyName=\"".$PartyName."\"),'$j',\"".$Data[0]."\",\"".$Data[3]."\",\"".$Data[4]."\",\"".$Data[5]."\", \"".$Data[1]."\",\"".$Data[2]."\")");
					if(!$result1)
					{
						$rollback=true;
						$Err=true;
					}
				}
			}
			
			if($rollback)
			{
				mysqli_query($con2,'rollback');
				$Err=true;
			}
			else
			{
				mysqli_query($con2,'commit');
				$host=$_SERVER['HTTP_HOST'];
				$uri=rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$extra="party_master_view.php";
				header("Location: http://$host$uri/$extra");
			}
		}
   }
?>
<div class="row">
<div class="col-12">
	<div class="page-title-box d-flex align-items-center justify-content-between">
		<h4 class="page-title mb-0 font-size-18">Masters</h4>

		<div class="page-title-right">
			<ol class="breadcrumb m-0">
				<li class="breadcrumb-item active">Party Master</li>
			</ol>
		</div>

	</div>
</div>
</div>


<div class="row">

<?php
if($Err==true)
{
	die($ErrMsg);
}
?>
<div class="col-xl-12">
	<div class="card">
		<div class="card-body">
		
<form name="frmPartyMaster" id="frmPartyMaster" method="POST" enctype="multipart/form-data">
<input type="hidden" name="hfPageMode" id='hfPageMode' value="<?=$PageMode; ?>" />
<input type="hidden" name="iPartyId" id="iPartyId" value="<?=trim($_POST['iPartyID'])?>">
<input type="hidden" name="hdBillingFirm" id="hdBillingFirm" value="<?=$ItemData;?>">
<input type="hidden" id="formfocuselement" name="formfocuselement" value="<?=($PageMode=='New')?'txtPartyName':'txtContactPerson'; ?>"/>
<input type="hidden" id="auth_info" name="auth_info" value="<?=$_POST['auth_info'] ?>"/>
        <div class="row">
		  <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Part Type</label>
				
					<select name="cmbPartType" id="cmbPartType" class="form-control">
						<option value="Expeller" <?=($PartType=="Expeller")?"selected":""?>>Expeller Parts</option>
						<option value="PowerPress" <?=($PartType=="PowerPress")?"selected":""?>>Power Press Parts</option>
					</select>
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Party name</label>
				
					<input type="text" class="form-control"  name="txtPartyName" id="txtPartyName" maxlength="200"  onBlur="CheckName(this.id)" value="<?=$PartyName ?>">
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Contact Person</label>
				    
					<input type="text" class="form-control"  name="txtContactPerson" id="txtContactPerson" maxlength="200"  onBlur="CheckName(this.id)" value="<?=$ContactPerson ?>">
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">GST In</label>
				    
					<input type="text" class="form-control"  name="txtGSTCode" id="txtGSTCode" maxlength="15"  value="<?=$gstCode ?>">
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">State Code</label>
				    
					<input type="text"  class="form-control" name="txtStateCode" id="txtStateCode" maxlength="50"  value="<?=$stateCode ?>">
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">TIN No</label>
				    
					<input type="text"  class="form-control" name="txtTINNo" id="txtTINNo" maxlength="50"  value="<?=$TINNo ?>">
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Mobile No</label>
				    <input type="text" class="form-control" name="txtMobileNo" id="txtMobileNo" maxlength="50"  value="<?=$MobileNo ?>">
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Fax No</label>
				    <input type="text"  name="txtFaxNo" class="form-control" id="txtFaxNo" maxlength="50"  value="<?=$FaxNo ?>">
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Phone No</label>
				    <input type="text" class="form-control"  name="txtPhoneNo" id="txtPhoneNo" maxlength="50"  value="<?=$PhoneNo ?>">
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">STNo</label>
				   <input type="text" class="form-control"  name="txtSTNo" id="txtSTNo" maxlength="50"  value="<?=$STNo ?>">
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">CST No</label>
				   <input type="text" class="form-control"  name="txtCSTNo" id="txtCSTNo" maxlength="50" value="<?=$CSTNo ?>">
			</div>
		   </div>
		   
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Address</label>
				   <textarea class="form-control" name="txtAddress" id="txtAddress"   onKeyDown="TextAreaNewLine(this.id)" onKeyUp="MaxCharCount(this.id,250)"><?=$Address ?></textarea>
			</div>
		   </div>

		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Transporter</label>
				   <textarea name="txtTransporter" class="form-control" id="txtTransporter"  onKeyDown="TextAreaNewLine(this.id)" onKeyUp="MaxCharCount(this.id,200)"><?=$Transporter ?></textarea>
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Remarks</label>
				   <textarea name="txtRemarks"  class="form-control" id="txtRemarks"   onKeyDown="TextAreaNewLine(this.id)" onKeyUp="MaxCharCount(this.id,200)"><?=$Remarks ?></textarea>
			</div>
		   </div>
		   <div class="col-xl-4">
		   <div class="form-group">
				<label for="example-text-input">Active</label>
				   <input type="checkbox" class="form-control-checkbox" name="chkActive" id="chkActive" <?=($Active)?"checked":""?>>
			</div>
		   </div>
		   
		</div>
		
		   <fieldset style="width:100%;border:1px solid #A487A7;padding:20px">
		      <legend>Billing Firm Details</legend>
			  <div class="row">
			  <div class="col-xl-6">
				   <div class="form-group">
						<label for="example-text-input">Firm Name</label>
						
							<input type="text" class="form-control"  name="txtFirmName" id="txtFirmName" maxlength="200">
					</div>
					</div>
					<div class="col-xl-6">
					   <div class="form-group">
						<label for="example-text-input">Firm TIN No</label>
						
							<input type="text" class="form-control" name="txtFirmTINNO" id="txtFirmTINNO" maxlength="100" >
					    </div>
		            </div>
					<div class="col-xl-6">
					   <div class="form-group">
						<label for="example-text-input">GST IN</label>
						    <input type="text" class="form-control" name="txtFirmGSTCode" id="txtFirmGSTCode" >
					    </div>
		            </div>
					<div class="col-xl-6">
					   <div class="form-group">
						<label for="example-text-input">State Code</label>
						    <input type="text" class="form-control" name="txtFirmStateCode" id="txtFirmStateCode">
					    </div>
		            </div>
					<div class="col-xl-6">
					   <div class="form-group">
						<label for="example-text-input">Firm Address</label>
						    <textarea name="txtFirmAddress" class="form-control" id="txtFirmAddress"   onKeyDown="TextAreaNewLine(this.id)" onKeyUp="MaxCharCount(this.id,200)"></textarea>
					    </div>
		            </div>
					<div class="col-xl-6">
					   <div class="form-group">
						<label for="example-text-input">Firm Phone No </label>
						    <input type="text" class="form-control" name="txtFirmPhoneNo" id="txtFirmPhoneNo" maxlength="100" >
					    </div>
		            </div>
					<br>
					<div class="col-xl-12">
					<?php
					if($__add==true)
					{
					?>
					   <input type="button" name="btnAdd" id="btnAdd" value="Add" class="btn btn-primary waves-effect waves-light"  onClick="AddFirm()">
					 <?php
					}
					 ?>   
		            </div>
					</div>
		   </fieldset>
		
		<div class="row">
		<div id="dvBillingFirm" class="col-xl-12"></div>
		</div>
		<br>
		<div class="row">
		<div class="col-xl-12">
		<?php
		if($__add==true)
		{
		?>
		 <button type="button"  class="btn btn-primary waves-effect waves-light" name="btnSave" id="btnSave" onClick="CheckBlank()" onKeyDown="EnterKeyClick(this.id)">Save</button>
		<?php
		}
		?> 
		 <input type="reset" value="Reset" class="btn btn-secondary waves-effect waves-light" name="btnReset" id="btnReset" onclick="resetForm()" onKeyDown="EnterKeyClick(this.id)">
		 </div>
		</div>
		
	
</form>
</div>
</div>
</div>
</div>
<script type="text/javascript" language="javascript" src="../js/general.js"></script>
<script language="javascript" type="text/javascript" src="../js/party_master.js"></script>

