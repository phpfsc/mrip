function MenuClick(xRfc,credentails,xUrl)
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
			toastr.error("Internal server error");
			preloadfadeOut();
			 
		}
		else
		{
			preloadfadeOut();
			toastr.error('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
			
		}
		
    },
    success: function(data)
	{
		
		$(".waves-effect").removeClass("active");
		$(".previous").removeClass("active");
        $(".page-content").html(data);
		$("#" + xRfc).addClass("active");
		$(window).scrollTop(0); 
		
	},
	complete: function (jqXHR, status) {
                        preloadfadeOut(); 
                    }
});


}
function preloadfadeIn()
{
	$("#preloader").fadeIn();
	$("#status").fadeIn();
}
function preloadfadeOut()
{
	$("#preloader").fadeOut();
	$("#status").fadeOut();
}
