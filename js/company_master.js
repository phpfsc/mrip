var FirmArray=new Array();
var stdModCount=-1;
$("document").ready(function(){
	
	PageLoad();
});
function PageLoad()
{
	var PageMode=document.getElementById("hfPageMode").value;
	if (PageMode=="Edit")
	{
		document.getElementById("txtPartyName").disabled=true;
		document.getElementById("txtContactPerson").focus();
	}
	Display();
}

function CheckBlank()
{
	preloadfadeIn();
	var validata=true;
	var errmsg="";
	var focusid="";
	if ($("#txtPartyName").val()==="")
	{
		validata=false;
		focusid="txtPartyName";
		if(errmsg==='')
		{
			errmsg="Please Enter Company Name..";
		}
		else
		{
			errmsg="<br>Please Enter Company Name..";
		}
	}
	else if ($("#txtContactPerson").val()==="")
	{
		validata=false;
		focusid="txtContactPerson";
		if(errmsg==='')
		{
			errmsg="Please Enter Contact Person Name...";
		}
		else
		{
			errmsg="<br>Please Enter Contact Person Name...";
		}
		
	}
	else if ($("#txtInvoiceCode").val()==="")
	{
		validata=false;
		focusid="txtInvoiceCode";
		if(errmsg==='')
		{
			errmsg="Please Enter Invoice No...";
		}
		else
		{
			errmsg="<br>Please Enter Invoice No...";
		}
		
	}
	else if ($("#txtInvoiceSerial").val()==="")
	{
		validata=false;
		focusid="txtInvoiceSerial";
		if(errmsg==='')
		{
			errmsg="Please Enter Invoice Serial No...";
		}
		else
		{
			errmsg="<br>Please Enter Invoice Serial No...";
		}
		
	}
	else if ($("#txtTINNo").val()==="")
	{
		validata=false;
		focusid="txtTINNo";
		if(errmsg==='')
		{
			errmsg="Please Enter TIN No...";
		}
		else
		{
			errmsg="<br>Please Enter TIN No...";
		}
		
	}
	else if ($("#txtAddress").val()==="")
	{
		validata=false;
		focusid="txtAddress";
		if(errmsg==='')
		{
			errmsg="Please Enter Company Address...";
		}
		else
		{
			errmsg="<br>Please Enter Company Address...";
		}
		
	}
	// else if ($("#hdBillingFirm").val()==="")
	// {
		// validata=false;
		// focusid="txtFirmName";
		// if(errmsg==='')
		// {
			// errmsg="Please Add Firm Name";
		// }
		// else
		// {
			// errmsg="<br>Please Add Firm Name";
		// }
		
	// }
	
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		preloadfadeOut();
	}
	else
	{
		        var formData = new FormData();
        		var other_data = $('#frmCompanyMaster').serializeArray();
				$.each(other_data,function(key,input){
					
        			formData.append(input.name,input.value);
        		});
				
				
		$.ajax({
					url:'masters/save_company_master.php',
					type:'POST',
					data:formData,
					async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
					
					success:function(response)
					{
						
						preloadfadeOut();
						var response2=JSON.parse(response);
						if(response2.error)
						{
							toastr.error(response2.error_msg);
						}
						else
						{
							toastr.success(response2.error_msg);
							MenuClick2($("#auth_info").val(),btoa(btoa("masters/company_master.php")));
							
						}
					},
                    error: function (jqXHR, status, err) {
						if(jqXHR.status=='404')
						{
							toastr.error("Page not found");
							$("#btnAdd").prop('disabled',false);
							preloadfadeOut();
						}
						if(jqXHR.status=='500')
						{
							toastr.error("internal server error");
							$("#btnAdd").prop('disabled',false);
							preloadfadeOut();
						}
						
                        
                    },
                    complete: function (jqXHR, status) {
						$("#btnAdd").prop('disabled',false);
                        preloadfadeOut();
                    }
				})
	}
	
	
	
	
	
	document.getElementById("txtPartyName").disabled=false;
}

function CheckName(Id)
{
	lbl=document.getElementById("lblErrMsg");
	
	ctrl=document.getElementById(Id);
	if (ctrl.value!="")
	{
		lbl.innerHTML="";
		if(document.getElementById("hfPageMode").value=="New")
		{
			url="../general/checkduplicate.php?table=company_master &code= where cPartyName=\"" + escape(ctrl.value)+"\"";
		}
		else
		{
			url="../general/checkduplicate.php?table=company_master & code= where cPartyName=\"" + escape(ctrl.value)+"\" and iPartyID<>'"+code+"'";
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
					toastr.error("Company name already exists");
					$("#"+ctrl).focus();
				}
				if(http.responseText=='Error' || http.responseText=='')
				{
					toastr.error("Error in checking Company Name! Please try again...");
					$("#"+ctrl).focus();
					
				}
			}
		}
		http.send(null);	
	}
}

function AddFirm()
{
	preloadfadeIn();
	var validata=true;
	var errmsg="";
	var focusid="";
	if ($("#txtFirmName").val()==="")
	{
		focusid="txtFirmName";
		validata=false;
		if(errmsg==='')
		{
			errmsg="Please enter Firm Name...";
		}
		else
		{
			errmsg="<br>Please enter Firm Name...";
		}
	}
	else if ($("#txtFirmInvoiceCode").val()==="")
	{
		focusid="txtFirmInvoiceCode";
		validata=false;
		if(errmsg==='')
		{
			errmsg="Please enter Firm Invoice Code...";
		}
		else
		{
			errmsg="<br>Please enter Firm Invoice Code...";
		}
	}
	else if($("#txtFirmGSTCode").val()==="")
	{
		focusid="txtFirmGSTCode";
		validata=false;
		if(errmsg==='')
		{
			errmsg="Please enter Firm GST Code...";
		}
		else
		{
			errmsg="<br>Please enter Firm GST Code...";
		}
	}
	else if($("#txtFirmHSNCode").val()==="")
	{
		focusid="txtFirmHSNCode";
		validata=false;
		if(errmsg==='')
		{
			errmsg="Please enter Firm HSN Code...";
		}
		else
		{
			errmsg="<br>Please enter Firm HSN Code...";
		}
	}
	else if($("#txtFirmInvoiceSerial").val()==="")
	{
		focusid="txtFirmInvoiceSerial";
		validata=false;
		if(errmsg==='')
		{
			errmsg="Please enter Firm Invoice Serial No...";
		}
		else
		{
			errmsg="<br>Please enter Firm Invoice Serial No...";
		}
	}
	else if($("#txtFirmInvoiceSerial").val()==="")
	{
		focusid="txtFirmInvoiceSerial";
		validata=false;
		if(errmsg==='')
		{
			errmsg="Please enter Firm Invoice Serial No...";
		}
		else
		{
			errmsg="<br>Please enter Firm Invoice Serial No...";
		}
	}
	
	
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		preloadfadeOut();
	}
	else
	{
		
		add=true;
		for (x=0;x<FirmArray.length ;x++ )
		{
			if($('#btnAdd').val()=='Modify' && x==stdModCount)
			{
				continue;
			}
			if(FirmArray[x][0]==$('#txtFirmName').val())
			{
				toastr.error("Firm Name already added");
				$("#txtFirmName").focus();
				preloadfadeOut();
				
				add=false;
			}
		}
		if (add)
		{
			if($('#btnAdd').val()=="Add")
			{
				len=FirmArray.length;
				FirmArray[len]=new Array();
				FirmArray[len][0]=$('#txtFirmName').val();
				FirmArray[len][1]=$('#txtFirmPhoneNo').val();
				FirmArray[len][2]=$('#txtFirmAddress').val();
				FirmArray[len][3]=$('#txtFirmTINNO').val();
				FirmArray[len][4]=$("#txtFirmInvoiceCode").val();
				FirmArray[len][5]=$("#txtFirmInvoiceSerial").val();
				FirmArray[len][6]=$("#txtFirmGSTCode").val();
				FirmArray[len][7]=$("#txtFirmHSNCode").val();
				$("#dvBillingFirm").html("");
				concatData();
			}
			else
			{
				FirmArray[stdModCount][0]=$('#txtFirmName').val();
				FirmArray[stdModCount][1]=$('#txtFirmPhoneNo').val();
				FirmArray[stdModCount][2]=$('#txtFirmAddress').val();
				FirmArray[stdModCount][3]=$('#txtFirmTINNO').val();
				FirmArray[stdModCount][4]=$("#txtFirmInvoiceCode").val();
				FirmArray[stdModCount][5]=$("#txtFirmInvoiceSerial").val();
				FirmArray[stdModCount][6]=$("#txtFirmGSTCode").val();
				FirmArray[stdModCount][7]=$("#txtFirmHSNCode").val();
				$("#dvBillingFirm").html("");
				concatData();
				
				$("#btnAdd").val("Add");
				
			}
			
				$.ajax({
					url:'masters/company_master.php',
					type:'POST',
					data:{"auth_info":$("#auth_info").val(),"updatedData":$("#hdBillingFirm").val()},
					success:function(data)
					{
						$(".page-content").html(data);
						preloadfadeOut();
					}
				})
			DispFirmInfo();
			do
			{
			}while(typeof(document.getElementById('txtFirmName')) == "undefined");
			
			document.getElementById('txtFirmName').value="";
			document.getElementById('txtFirmPhoneNo').value="";
			document.getElementById('txtFirmAddress').value="";
			document.getElementById('txtFirmTINNO').value="";
			document.getElementById("txtFirmInvoiceCode").value="";
			document.getElementById("txtFirmInvoiceSerial").value="";
			document.getElementById("txtFirmGSTCode").value="";
			document.getElementById("txtFirmHSNCode").value="";
			document.getElementById('txtFirmName').focus();
		}
	}
}

function DispFirmInfo()
{
	
	
	var DataArray=$("#hdBillingFirm").val();
	var singleData=DataArray.split("~Array~");
	
	var y;
	if(DataArray)
	{
		var Data="<table id=\"datatable\" class=\"table table-bordered dt-responsive nowrap\" style=\"border-collapse: collapse; border-spacing: 0; width: 100%;\">";
	Data+="<thead><tr><th>SNO</th><th>Firm Name</th><th>Firm Phone</th><th>Firm Address</th><th>Firm TINNO</th><th>GST IN</th><th>HSN Code</th><th>Invoice Code</th><th>Invoice Serial</th><th>Options</th></tr></thead><tbody>";
	
		for(y=0;y<singleData.length;y++)
		{
		  	var tdData=singleData[y].split("~ArrayItem~");
			Data+="<tr>";
			Data+="<td>"+(y+1)+"</td>";
			Data+="<td>"+tdData[0]+"</td>";
			Data+="<td>"+tdData[1]+"&nbsp;</td>";
			Data+="<td>"+tdData[2]+"&nbsp;</td>";	
			Data+="<td>"+tdData[3]+"&nbsp;</td>";
			Data+="<td>"+tdData[6]+"&nbsp;</td>";	
			Data+="<td>"+tdData[7]+"&nbsp;</td>";	
			Data+="<td>"+tdData[4]+"&nbsp;</td>";
			Data+="<td>"+tdData[5]+"&nbsp;</td>";	
			Data+="<td><input type=\"button\"   class=\"btn btn-primary waves-effect waves-light\" value=\"Edit\" onClick=\"Edit("+y+")\">&nbsp;&nbsp;&nbsp;<input type=\"button\" value=\"Delete\" class=\"btn btn-danger waves-effect waves-light\" onclick=\"Delete("+y+")\"></td>";
			Data+="</tr>";
			
		}
		Data=Data+"</tbody></table>";
		
		
		$("#dvBillingFirm").html(Data);
	}
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
				packed=FirmArray[x][0]+"~ArrayItem~"+FirmArray[x][1]+"~ArrayItem~"+FirmArray[x][2]+"~ArrayItem~"+FirmArray[x][3]+"~ArrayItem~"+FirmArray[x][4]+"~ArrayItem~"+FirmArray[x][5]+"~ArrayItem~"+FirmArray[x][6]+"~ArrayItem~"+FirmArray[x][7];
			}
			else
			{
				packed=packed+"~Array~"+FirmArray[x][0]+"~ArrayItem~"+FirmArray[x][1]+"~ArrayItem~"+FirmArray[x][2]+"~ArrayItem~"+FirmArray[x][3]+"~ArrayItem~"+FirmArray[x][4]+"~ArrayItem~"+FirmArray[x][5]+"~ArrayItem~"+FirmArray[x][6]+"~ArrayItem~"+FirmArray[x][7];
			}
		}
	}
	document.getElementById('hdBillingFirm').value=packed;
}

function Delete(r1)
{
	preloadfadeIn();
	var DataArray=$("#hdBillingFirm").val();
	var singleArray=DataArray.split('~Array~');
	if(singleArray.length=='1')
	{
		
		$("#hdBillingFirm").val("");
		$("#dvBillingFirm").html('');
		preloadfadeOut();
	}
	else
	{
		
		var k; 
		var items=[];
		for(k=0;k<singleArray.length;k++)
		{
			if(k!=r1)
			{
				items.push(singleArray[k]);
				
			}
			
		}
		var finalData=items.join("~Array~");
		
		$("#hdBillingFirm").val(finalData);
		/**          ajax Request to refresh page **/
				$.ajax({
					url:'masters/company_master.php',
					type:'POST',
					data:{"auth_info":$("#auth_info").val(),"updatedData":$("#hdBillingFirm").val()},
					success:function(data)
					{
						$(".page-content").html(data);
					}
				})
				preloadfadeOut();
		
	}
	
	
}

function Edit(row)
{
	document.getElementById('txtFirmName').value=FirmArray[row][0];
	document.getElementById('txtFirmPhoneNo').value=FirmArray[row][1];
	document.getElementById('txtFirmAddress').value=FirmArray[row][2];
	document.getElementById('txtFirmTINNO').value=FirmArray[row][3];
	document.getElementById("txtFirmInvoiceCode").value=FirmArray[row][4];
	document.getElementById("txtFirmInvoiceSerial").value=FirmArray[row][5];
	document.getElementById("txtFirmGSTCode").value=FirmArray[row][6];
	document.getElementById("txtFirmHSNCode").value=FirmArray[row][7];
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
			FirmArray[x][6]=dataitem[6];
			FirmArray[x][7]=dataitem[7];
		}
	}
	DispFirmInfo();
}
function resetPage()
{
	MenuClick2($("#auth_info").val(),btoa(btoa("masters/company_master.php")));
}
