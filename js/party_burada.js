// JavaScript Document
function ChangeLastBill(Id)
{
	company=(document.getElementById(Id).value).split('~ArrayItem~');
	if(company[2]!='')
	{
		document.getElementById('tdLastBillNo').innerHTML="Last Billing No : "+company[2]+"<br> Date : "+company[3];
	}
	else
	{
		document.getElementById('tdLastBillNo').innerHTML="No Billing saved before..."	
	}
}
function PageLoad2()
{
	if (document.getElementById("hdPageMode").value=="Edit")
	{
		SelParty(document.getElementById("hdPartySNo").value);
		CalBurada();
		CalGst();
		document.getElementById("txtBuradaRemarks").focus();
	}
	else
	{
		document.getElementById("cmbParty").focus();
	}
}
function Switch(Id)
{
	if (document.getElementById(Id).value=="Pending")
	{
		MenuClick2($("#auth_info").val(),btoa(btoa("operations/party_burada_edit.php")));
		//document.location.href="../operations/party_burada_edit.php";
	}
	else
	{
		MenuClick2($("#auth_info").val(),btoa(btoa("operations/party_burada.php")));
		//document.location.href="../operations/party_burada.php";
	}
}

function EnableThis(Id)
{
	if (document.getElementById(Id).value=="Party")
	{
		document.getElementById("cmbSelParty").disabled=false;
		document.getElementById("txtFromDate").disabled=true;
		document.getElementById("txtToDate").disabled=true;
		document.getElementById("cmbSelCompany").disabled=true;
	}
	else if (document.getElementById(Id).value=="Date")
	{
		document.getElementById("cmbSelParty").disabled=true;
		document.getElementById("cmbSelCompany").disabled=true;
		document.getElementById("txtFromDate").disabled=false;
		document.getElementById("txtToDate").disabled=false;
	}
	else
	{	
		document.getElementById("cmbSelParty").disabled=true;
		document.getElementById("cmbSelCompany").disabled=false;
		document.getElementById("txtFromDate").disabled=true;
		document.getElementById("txtToDate").disabled=true;
	}
}

function SelParty(ID)
{
	
	val=document.getElementById("cmbParty").value;
	Itemctrl=document.getElementById("cmbTo");
	Itemctrl.length=1;
	if (document.getElementById("cmbParty").value!="")
	{
		i=document.getElementById("cmbParty").options[document.getElementById("cmbParty").selectedIndex].text;
		
		opt=val.split("~ArrayItem~");
		document.getElementById('txtStateCode').value=opt[2];
		
		if(document.getElementById('txtStateCode').value=='03'){
			document.getElementById('SGSTdiv').style.display='table-row';
			document.getElementById('CGSTdiv').style.display='table-row';
			document.getElementById('IGSTdiv').style.display='none';
			document.getElementById('txtIGST').value=0;
		}else{
			document.getElementById('SGSTdiv').style.display='none';
			document.getElementById('CGSTdiv').style.display='none';
			document.getElementById('IGSTdiv').style.display='table-row';
			document.getElementById('txtCGST').value=0;
			document.getElementById('txtSGST').value=0;
		}
		if(document.getElementById('txtAmt').value != 0 || '' || NaN){
			CalGst();
		}
		opts=opt[1].split("~ItemData~");
		Itemctrl.options[1] = new Option(i,'0');
		if (ID=='0')
		{
			Itemctrl.options[1].selected=true;
		}
		else
		{
			Itemctrl.options[1].selected=false;
		}
		if (opts!="")
		{
			for (x=0;x<opts.length ;x++ )
			{
				sel=opts[x].split("~Array~");
				Itemctrl.options[x+2] = new Option(sel[1],sel[0]);
				if (ID==sel[0])
				{
					Itemctrl.options[x+2].selected=true;
				}
				else
				{
					Itemctrl.options[x+2].selected=false;
				}
			}
		}
	
	
	}
	
}

function CheckBlank()
{
	var validata=true;
	var errmsg="";
	var focusid="";
	if (document.getElementById("cmbParty").value=="")
	{
		validata=false;
		focusid="cmbParty";
		errmsg="Please Select Party...";
		// lbl.innerHTML="Please Select Party...";
		// document.getElementById('cmbParty').focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("cmbFrom").value=="")
	{
		validata=false;
		focusid="cmbFrom";
		errmsg="Please Select Company Name...";
		
		// lbl.innerHTML="Please Select Company Name...";
		// document.getElementById('cmbFrom').focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("cmbTo").value=="")
	{
		validata=false;
		focusid="cmbTo";
		errmsg="Please Select Party Name...";
		
		// lbl.innerHTML="Please Select Party Name...";
		// document.getElementById('cmbTo').focus();
		// event.returnValue=false;
	}
	else if (document.getElementById('txtBuradaRemarks').value=="")
	{
		validata=false;
		focusid="txtBuradaRemarks";
		errmsg="Please Enter Burada Remarks...";
		
		// lbl.innerHTML="Please Enter Burada Remarks...";
		// document.getElementById('txtBuradaRemarks').focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtBuradaQty").value=="" || parseInt(document.getElementById("txtBuradaQty").value)==0)
	{
		validata=false;
		focusid="txtBuradaQty";
		errmsg="Please Enter Burada Qty...";
		
		// lbl.innerHTML="Please Enter Burada Qty...";
		// document.getElementById('txtBuradaQty').focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtBuradaRate").value=="" || parseFloat(document.getElementById("txtBuradaRate").value)==0)
	{
		validata=false;
		focusid="txtBuradaRate";
		errmsg="Please Enter Burada Rate...";
		
		// lbl.innerHTML="Please Enter Burada Rate...";
		// document.getElementById('txtBuradaRate').focus();
		// event.returnValue=false;
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
	}
	else
	{
		var formData = new FormData();
		var other_data = $('#frmBurada').serializeArray();
		$.each(other_data,function(key,input){
			
			formData.append(input.name,input.value);
		});	
		$.ajax({
					url:'operations/saveBurada.php',
					type:'POST',
					data:formData,
					async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
					
					
					success:function(response)
					{
						
						
						 // alert(response);
						var response2=JSON.parse(response);
						if(response2.error)
						{
							toastr.error(response2.error_msg);
						}
						else
						{
							toastr.success(response2.error_msg);
							MenuClick2($("#auth_info").val(),btoa(btoa("operations/party_burada_edit.php")));
							
						}
					},
                    error: function (jqXHR, status, err) {
						if(jqXHR.status=='404')
						{
							toastr.error("Page not found");
							// $("#btnAdd").prop('disabled',false);
							// preloadfadeOut();
						}
						if(jqXHR.status=='500')
						{
							toastr.error("internal server error");
							// $("#btnAdd").prop('disabled',false);
							// preloadfadeOut();
						}
						
                        
                    },
                    complete: function (jqXHR, status) {
						// $("#btnAdd").prop('disabled',false);
                        // preloadfadeOut();
                    }
				})
	}
	
}

function CalBurada()
{
	qty=parseInt(document.getElementById("txtBuradaQty").value);
	rate=parseFloat(document.getElementById("txtBuradaRate").value);
	if (isNaN(qty))
	{
		document.getElementById("txtBuradaValue").value=0;
		document.getElementById("txtAmt").value=0;	
	}
	else if (qty==0)
	{
		document.getElementById("txtBuradaValue").value=0;
		document.getElementById("txtAmt").value=0;	
	}
	else if (isNaN(rate))
	{
		document.getElementById("txtBuradaValue").value=0;
		document.getElementById("txtAmt").value=0;	
	}
	else if (rate==0)
	{
		document.getElementById("txtBuradaValue").value=0;
		document.getElementById("txtAmt").value=0;	
	}
	else
	{
		val=Math.ceil(parseFloat(qty * rate));
		document.getElementById("txtBuradaValue").value=(val).toFixed(2);
		document.getElementById("txtAmt").value=(val).toFixed(2);
		CalGst();
	}
}
/* 
function CalVat(Id)
{
	vat=parseFloat(document.getElementById(Id).value);	
	if (isNaN(vat))
	{
		document.getElementById("txtAmt").value=(val).toFixed(2);
		document.getElementById("txtVatAmt").value=0;
	}
	else if (vat==0)
	{
		document.getElementById("txtAmt").value=(val).toFixed(2);
		document.getElementById("txtVatAmt").value=0;
	}
	else
	{
		val=parseFloat(document.getElementById("txtBuradaValue").value);
		var vatfactor=parseFloat(document.getElementById("hdVatFactor").value);
		if (val==0)
		{
			
		}
		else if (isNaN(val))
		{
		
		}
		else
		{
			vatAmt=Math.ceil(parseFloat((val*vatfactor/100 * vat)/100));
			document.getElementById("txtVatAmt").value=(vatAmt).toFixed(2);
			TotalAmt=parseFloat(vatAmt  + val);
			document.getElementById("txtAmt").value=(TotalAmt).toFixed(2);
		}
	}

} */

function CalGst()
{
	val=parseFloat(document.getElementById("txtBuradaValue").value);
	CGSTAmt=parseFloat(document.getElementById("txtCGST").value);
	if(isNaN(CGSTAmt))
	{
		CGSTAmt=0
	}
	SGSTAmt=parseFloat(document.getElementById("txtSGST").value);
	if(isNaN(SGSTAmt))
	{
		SGSTAmt=0
	}
	IGSTAmt=parseFloat(document.getElementById("txtIGST").value);
	if(isNaN(IGSTAmt))
	{
		IGSTAmt=0
	}
	gst=Number(IGSTAmt)+Number(CGSTAmt)+Number(SGSTAmt);	
	if (isNaN(gst))
	{
		document.getElementById("txtAmt").value=(val).toFixed(2);
		//document.getElementById(Id).value=0;
	}
	else if (gst==0)
	{
		document.getElementById("txtAmt").value=(val).toFixed(2);
		//document.getElementById(Id).value=0;
	}
	else
	{
		val=parseFloat(document.getElementById("txtBuradaValue").value);
		if (val==0)
		{
			
		}
		else if (isNaN(val))
		{
		
		}
		else
		{
			gstAmt=0;
			gstAmt=Math.ceil(parseFloat((val * gst)/100));
			//document.getElementById(Id).value=(gstAmt).toFixed(2);
			TotalAmt=parseFloat(gstAmt + val);
			document.getElementById("txtAmt").value=(TotalAmt).toFixed(2);
		}
	}

}