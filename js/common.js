$(document).keydown(function (event) {
    if (event.keyCode == 123) { // Prevent F12
        return false;
    } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
        return false;
    }
});
// $(document).keydown(function (event) {
    // if (event.keyCode == 123) { // Prevent F12
        // return false;
    // } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
        // return false;
    // }
// });
function validateEmail(field) {
    var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,5}$/;
    return (regex.test(field)) ? true : false;
}
function validateMultipleEmails(email) {
    var value = email;
    if (value != '') {
        var result = value.split(",");
        for (var i = 0; i < result.length; i++) {
            if (result[i] != '') {
                if (!validateEmail(result[i])) {
                    
                   
                    return false;
                }
            }
        }
    }
    return true;
}
    function validateMobile(mobile) {
	  if(isNaN(mobile))
	  {
		  return false;
	  }
	  else if(mobile.length!=10)
	  {
		  return false;
	  }
	  
	  else
	  {
		  return true;
	  }
     }
     function validateMobile2(mobile) {
	    return /^(\d{10},)*\d{10}$/.test(mobile);
     }
     
function MenuClick2(credentails,xUrl)
{
	preloadfadeIn();
	
	$.ajax({
    url:atob(atob(xUrl)), 
    data: {auth_info: credentails},
    type: 'post',
    error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			$('.page-content').load("404.php");
			preloadfadeOut();
			
		}
		else if(XMLHttpRequest.status==500)
		{
			$('.page-content').load("500.php");
			preloadfadeOut();
		}
		else
		{
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			preloadfadeOut();
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    },
    success: function(response)
	{
		
		
        $(".page-content").html(response);
		$(window).scrollTop(0);
		preloadfadeOut();
		
	}
});
}

function edit(auth_info,id)
	{
	    
	    $.ajax({
        url:'../masters/blower_type_master.php', 
        data: {"auth_info":auth_info,"blower_code":id},
        type: 'post',
        error: function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.status==404)
		{
			$('#page-content').load("404.php");
			
		}
		else if(XMLHttpRequest.status==500)
		{
			$('#page-content').load("500.php");
		}
		else
		{
			//alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
		}
		
    },
    success: function(data)
	{
		
		$("#page-content").html(data);
		
	}
		
	});
	
}

// window.setInterval(function(){
  // notify();
  // email_delivery();
  
// }, 5000);
// $("document").ready(function(){
	// notify();
	// getMenu();
	
// })
function getMenu()
{
	$.ajax({
					url:'operation/GetProfileSettingsMenu.php', 
                    type: 'POST',
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
						//alert(result);
						$("#profileSettings").html(result);
                        
                       
                    },
                    error: function (jqXHR, status, err) {
						if(jqXHR.status=='404')
						{
							// toastr.error("Page Not Found");
							// $(".Save").attr("disabled",false);
						}
						if(jqXHR.status=='500')
						{
							// toastr.error("Internal Server Error");
							// $(".Save").attr("disabled",false);
						}
						
                        
                    },
                    complete: function (jqXHR, status) {
                        //$('.theme-loader').fadeOut();
                    }
                });
}
function email_delivery()
{
	$.ajax({
					url:'../PHPMailer/index.php', 
                    type: 'POST',
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
						//alert(result);
						//$("#profileSettings").html(result);
                        
                       
                    },
                    error: function (jqXHR, status, err) {
						if(jqXHR.status=='404')
						{
							//toastr.error("Page Not Found");
							//$(".Save").attr("disabled",false);
						}
						if(jqXHR.status=='500')
						{
							//toastr.error("Internal Server Error");
							//$(".Save").attr("disabled",false);
						}
						
                        
                    },
                    complete: function (jqXHR, status) {
                        //$('.theme-loader').fadeOut();
                    }
                });
}
function notify()
{
	$.ajax({
					url:'operation/notifications.php', 
                    type: 'POST',
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
						$("#notification").html(result);
                        
                       
                    },
                    error: function (jqXHR, status, err) {
						if(jqXHR.status=='404')
						{
							// toastr.error("Page Not Found");
							// $(".Save").attr("disabled",false);
						}
						if(jqXHR.status=='500')
						{
							// toastr.error("Internal Server Error");
							// $(".Save").attr("disabled",false);
						}
						
                        
                    },
                    complete: function (jqXHR, status) {
                        //$('.theme-loader').fadeOut();
                    }
                });
}

    function AvoidSpace(event) {
		
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
    if (k == 34) return false;
    if (k == 39) return false;
}

    function AvoidChars(event) {
		
    var k = event ? event.which : window.event.keyCode;
   
    if (k == 34) return false;
    
}
$("#Profile").click(function(){
	MenuClick2('',btoa(btoa("profile/settings.php")));
})
