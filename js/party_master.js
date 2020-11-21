var FirmArray=new Array();
var stdModCount=-1;
PageLoad();

function PageLoad()
{
	var PageMode=document.getElementById("hfPageMode").value;
	if (PageMode=="Edit")
	{
		$("#txtPartyName").attr("readonly",true);
		
		
	}
	Display();
}

function CheckBlank()
{
	var validata=true;
	var focusid="";
	var errmsg="";
	if($("#txtPartyName").val()==='')
	{
		validata=false;
		focusid="txtPartyName";
		if(errmsg==='')
		{
			errmsg="Please enter party name";
		}
		else
		{
			errmsg="<br>Please enter party name";
		}
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
	}
	else
	{
		$("#txtPartyName").attr("readonly",true);
		submitData();
		
	}
	
}

function CheckName(Id)
{
	
	var code=atob(atob($("#iPartyId").val()));
	
	ctrl=document.getElementById(Id);
	if (ctrl.value!="")
	{
		
		if(document.getElementById("hfPageMode").value=="New")
		{
			url="../general/checkduplicate.php?table=party_master &code= where cPartyName=\"" + escape(ctrl.value)+"\"";
		}
		else
		{
			url="../general/checkduplicate.php?table=party_master & code= where cPartyName=\"" + escape(ctrl.value)+"\" and iPartyID<>'"+code+"'";
		}
		var http = false;

		if(navigator.appName == "Microsoft Internet Explorer") 
		{
			http = new ActiveXObject("Microsoft.XMLHTTP");
		} 
		else 
		{
			http = new XMLHttpRequest();
		}
			
		http.open("GET", url);
		http.onreadystatechange=function() 
		{
			if(http.readyState == 4  && http.status==200) 
			{
				if(http.responseText=='Record Exist')
				{
					toastr.error("Party Name already exist...");
					ctrl.focus();
				}
				if(http.responseText=='Error' || http.responseText=='')
				{
					toastr.error("Error in checking Party Name! Please try again...");
					ctrl.focus();
				}
			}
		}
		http.send(null);	
	}
}

function AddFirm()
{
	var validata=true;
	var errmsg="";
	var focusid="txtFirmName";
	if($("#txtFirmName").val()==='')
	{
		validata=false;
		focusid="";
		if(errmsg==='')
		{
			errmsg="Please enter Firm Name...";
		}
		else
		{
			errmsg="<br>Please enter Firm Name...";
		}
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
	}
	
	else
	{
		
		add=true;
		for (x=0;x<FirmArray.length ;x++ )
		{
			if($("#btnAdd").val()=='Modify' && x==stdModCount)
			{
				continue;
			}
			if(FirmArray[x][0]==document.getElementById('txtFirmName').value)
			{
				toastr.error("Firm Name Already Exists");
				$("#txtFirmName").focus();
				add=false;
			}
		}
		if (add)
		{
			if (document.getElementById('btnAdd').value=="Add")
			{
				len=FirmArray.length;
				FirmArray[len]=new Array();
				FirmArray[len][0]=document.getElementById('txtFirmName').value;
				FirmArray[len][1]=document.getElementById('txtFirmPhoneNo').value;
				FirmArray[len][2]=document.getElementById('txtFirmAddress').value;
				FirmArray[len][3]=document.getElementById('txtFirmTINNO').value;
				FirmArray[len][4]=document.getElementById('txtFirmGSTCode').value;
				FirmArray[len][5]=document.getElementById('txtFirmStateCode').value;
			}
			else
			{
				FirmArray[stdModCount][0]=document.getElementById('txtFirmName').value;
				FirmArray[stdModCount][1]=document.getElementById('txtFirmPhoneNo').value;
				FirmArray[stdModCount][2]=document.getElementById('txtFirmAddress').value;
				FirmArray[stdModCount][3]=document.getElementById('txtFirmTINNO').value;
				FirmArray[stdModCount][4]=document.getElementById('txtFirmGSTCode').value;
				FirmArray[stdModCount][5]=document.getElementById('txtFirmStateCode').value;
				document.getElementById('btnAdd').value="Add";
			}
			concatData();
			DispFirmInfo();
			do
			{
			}while(typeof(document.getElementById('txtFirmName')) == "undefined");
			
			document.getElementById('txtFirmName').value="";
			document.getElementById('txtFirmPhoneNo').value="";
			document.getElementById('txtFirmAddress').value="";
			document.getElementById('txtFirmTINNO').value="";
			document.getElementById('txtFirmGSTCode').value="";
			document.getElementById('txtFirmStateCode').value="";
			document.getElementById('txtFirmName').focus();
		}
	}
}

function DispFirmInfo()
{
	$("#dvBillingFirm").html('');
	
	var x;
	var Data="<table id=\"example2\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
    Data+="<thead><tr><th>SNO</th><th>Firm Name</th><th>Firm Phone</th><th>Firm Address</th><th>Firm TINNO</th><th>Firm GST IN</th><th>Firm State Code</th><th>Options</th></tr></thead><tbody>";for (x=0;x<FirmArray.length ;x++ )
	{
		
		Data+="<tr>";
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+FirmArray[x][0]+"</td>";
		Data+="<td>"+FirmArray[x][1]+"&nbsp;</td>";
		Data+="<td>"+FirmArray[x][2]+"&nbsp;</td>";	
		Data+="<td>"+FirmArray[x][3]+"&nbsp;</td>";	
		Data+="<td>"+FirmArray[x][4]+"&nbsp;</td>";	
		Data+="<td>"+FirmArray[x][5]+"&nbsp;</td>";	
		Data+="<td><input type=\"button\" class=\"btn btn-primary waves-effect waves-light\"  value=\"Edit\" onClick=\"Edit("+x+")\">&nbsp;&nbsp;<input type=\"button\" value=\"Delete\" class=\"btn btn-danger waves-effect waves-light\" onclick=\"Delete("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</tbody></table>";
	
	$("#dvBillingFirm").html(Data);
	tableCss();
	concatData();
	//BillingFirm.innerHTML="";
	
	stdModCount=-1;
	
}

function concatData()
{
	packed="";
	if(FirmArray.length>0)
	{
		for(x=0;x<FirmArray.length;x++)
		{
			if(packed=='')
			{
				packed=FirmArray[x][0]+"~ArrayItem~"+FirmArray[x][1]+"~ArrayItem~"+FirmArray[x][2]+"~ArrayItem~"+FirmArray[x][3]+"~ArrayItem~"+FirmArray[x][4]+"~ArrayItem~"+FirmArray[x][5];
			}
			else
			{
				packed=packed+"~Array~"+FirmArray[x][0]+"~ArrayItem~"+FirmArray[x][1]+"~ArrayItem~"+FirmArray[x][2]+"~ArrayItem~"+FirmArray[x][3]+"~ArrayItem~"+FirmArray[x][4]+"~ArrayItem~"+FirmArray[x][5];
			}
		}
	}
	document.getElementById('hdBillingFirm').value=packed;
}

function Delete(r1)
{
	var k;
	len=FirmArray.length;
	if(r1< len)
	{
		for(k=r1;k<len;k++)
		{
			if( (k+1)==len)
			{
				FirmArray[k][0]="";
				FirmArray[k][1]="";
				FirmArray[k][2]="";
				FirmArray[k][3]="";
				FirmArray[k][4]="";
				FirmArray[k][5]="";
			}
			else
			{
				FirmArray[k][0]=FirmArray[k+1][0];
				FirmArray[k][1]=FirmArray[k+1][1];
				FirmArray[k][2]=FirmArray[k+1][2];
				FirmArray[k][3]=FirmArray[k+1][3];
				FirmArray[k][4]=FirmArray[k+1][4];
				FirmArray[k][5]=FirmArray[k+1][5];
			}
		}				
	}
	FirmArray.length=FirmArray.length-1;
	DispFirmInfo();
}

function Edit(row)
{
	document.getElementById('txtFirmName').value=FirmArray[row][0];
	document.getElementById('txtFirmPhoneNo').value=FirmArray[row][1];
	document.getElementById('txtFirmAddress').value=FirmArray[row][2];
	document.getElementById('txtFirmTINNO').value=FirmArray[row][3];
	document.getElementById('txtFirmGSTCode').value=FirmArray[row][4];
	document.getElementById('txtFirmStateCode').value=FirmArray[row][5];
	stdModCount=row;
	document.getElementById('btnAdd').value="Modify";
	document.getElementById('txtFirmName').focus();
}

function Display()
{
	
	if(document.getElementById('hdBillingFirm').value!='')
	{
		data=(document.getElementById('hdBillingFirm').value).split('~Array~');
		for(x=0;x<data.length;x++)
		{
			dataitem=data[x].split('~ArrayItem~');
			FirmArray[x]=new Array();
			FirmArray[x][0]=dataitem[0];
			FirmArray[x][1]=dataitem[1];
			FirmArray[x][2]=dataitem[2];
			FirmArray[x][3]=dataitem[3];
			FirmArray[x][4]=dataitem[4];
			FirmArray[x][5]=dataitem[5];
		}
	}
	DispFirmInfo();
}
function submitData()
{
	            $("#btnSave").attr("disabled",true);
	            preloadfadeIn()
	            var formData = new FormData();
        		var other_data = $('#frmPartyMaster').serializeArray();
				
				$.each(other_data,function(key,input){
					
        			formData.append(input.name,input.value);
        		});
				
				
		$.ajax({
					url:'masters/save_party_master.php',
					type:'POST',
					data:formData,
					async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
					
					success:function(response)
					{
						
						
						var response2=JSON.parse(response);
						if(response2.error)
						{
							toastr.error(response2.error_msg);
							$("#btnSave").attr("disabled",true);
						}
						else
						{
							toastr.success(response2.error_msg);
							MenuClick2($("#auth_info").val(),btoa(btoa("masters/party_master_view.php")));
							$("#btnSave").attr("disabled",false);
							
						}
					},
                    error: function (jqXHR, status, err) {
						if(jqXHR.status=='404')
						{
							toastr.error("Page not found");
							$("#btnAdd").prop('disabled',false);
							preloadfadeOut();
							$("#btnSave").attr("disabled",false);
						}
						if(jqXHR.status=='500')
						{
							toastr.error("internal server error");
							$("#btnAdd").prop('disabled',false);
							preloadfadeOut();
							$("#btnSave").attr("disabled",false);
						}
						
                        
                    },
                    complete: function (jqXHR, status) {
						$("#btnSave").attr("disabled",false);
                        preloadfadeOut();
                    }
				})
}
function resetForm()
{
   preloadfadeIn();
	$.ajax({
		    url:'masters/party_master.php',
			type:'POST',
			data:{"auth_info":$("#auth_info").val(),"iPartyID":$("#iPartyID").val()},
			success:function(response)
			{
				$('.page-content').html(response);
				preloadfadeOut();
			},error(response)
			{
				toastr.error("communication Error");
				preloadfadeOut();
			}
	})
}
function tableCss()
{
	
	$("#example2").dataTable().fnDestroy();
	$('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
	
  
}