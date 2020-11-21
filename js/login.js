function login()
{
	
	var validata=true;
	var errmsg="";
	var focusid="";
	if($("#txtfsyear").val()==='')
	{
		validata=false;
		focusid="txtfsyear";
		if(errmsg==='')
		{
			errmsg="Please choose financial year";
		}
		else
		{
			errmsg="<br>Please choose financial year";
		}
	}
	else if($("#username").val()==='')
	{
		validata=false;
		focusid="username";
		if(errmsg==='')
		{
			errmsg="Please enter username";
		}
		else
		{
			errmsg="<br>Please enter username";
		}
	}
	else if($("#userpassword").val()==='')
	{
		validata=false;
		focusid="userpassword";
		if(errmsg==='')
		{
			errmsg="Please enter password";
		}
		else
		{
			errmsg="<br>Please enter password";
		}
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		return false;
	}
	else
	{
		if($('#remember').is(":checked"))
		{
			var remember=1;
		}
		else
		{
			var remember=0;
		}
		var formData={"txtfsyear":$("#txtfsyear").val(),"username":$("#username").val(),"userpassword":$("#userpassword").val(),"remember":remember};
    
		
		
	
		$.ajax({
			     url:'general/login.php',
				 type:'post',
				 data:formData,
				 success:function(response)
				 {
					 
					 var data=JSON.parse(response);
					 if(data.error)
					 {
					    toastr.error(data.error_msg);
					 }
					 /**-------------------------Fresh Login Success---------**/
					 else
					 {
					    toastr.success(data.error_msg);
						$('#frm_login')[0].reset();
                        window.location.href=window.location.href=data.page;;
					 }
					 
					 
					 
				 },
					error: function (jqXHR, status, err) {
						toastr.error("Error Code".toUpperCase()+" : 2" );
						$(".login").removeClass("disabled");
						},
					complete: function (jqXHR, status) {
						//$('.theme-loader').fadeOut();
					}
		      });
			  return false;
	}
	
}