var DeliveryArr=new Array();
var ItemArr=new Array();
function PageLoad2()
{
	document.getElementById('cmbParty').focus();
	if (document.getElementById("hdPageMode").value=="Edit")
	{
		DispalyEdit();
	}
	else
	{
		 DispDeliveryInfo();
	}
}

function PageLoad1()
{
	document.getElementById("cmbSelParty").disabled=true;
}

function EnableThis(Id)
{
	if (document.getElementById(Id).value=="Party")
	{
		document.getElementById("cmbSelParty").disabled=false;
		document.getElementById("txtToDate").disabled=true;
	}
	else
	{
		document.getElementById("cmbSelParty").disabled=true;
		document.getElementById("txtToDate").disabled=false;
	}
}

function Switch(Id)
{
	
	if ($("#"+Id).val()=="Pending")
	{
		DeliveryArr.length=0;
		ItemArr.length=0;
		$("#cmbParty").val('');
		$("#txtDeliveryChallan").val('');
		$("#txtRemarks").val('');
		$("#hdPODetails").val('');
		$("#dvPoDetails").val('');
		$("#cmbParty").attr("disabled",false);
		$("#cmbParty").attr("readonly",false);
		MenuClick2($("#auth_info").val(),btoa(btoa("operations/delivery_challan_edit.php")));
		// document.getElementById("cmbParty").value="";
		// document.getElementById("txtDeliveryChallan").value="";
		// document.getElementById("txtRemarks").value="";
		// document.getElementById("hdPODetails").value="";
		// document.getElementById("dvPoDetails").innerHTML="";
		// document.getElementById("cmbParty").disabled=false;
		// document.location.href="../operations/delivery_challan_edit.php";
	}
	else
	{
		DeliveryArr.length=0;
		ItemArr.length=0;
		MenuClick2($("#auth_info").val(),btoa(btoa("operations/delivery_challan.php")));
		
	}
}

function DispalyEdit()
{
	ItemData=(document.getElementById("hdPODetails").value).split('~ItemData~');
	if (DeliveryArr.length>0)
	{
		DeliveryArr.length=0;
	}
	if (ItemArr.length>0)
	{
		ItemArr.length=0;
	}
	for (x=0;x<ItemData.length ;x++ )
	{
		opt=(ItemData[x]).split('~Heading~');
		caption=opt[0].split('~Caption~');
		len1=ItemArr.length;
		ItemArr[len1]=new Array();
		ItemArr[len1][0]=caption[0];
		ItemArr[len1][1]=caption[1];
		
		sel=opt[1].split('~ArrayItem~');
		len= DeliveryArr.length
		DeliveryArr[len]=new Array();
		DeliveryArr[len][0]=ItemArr[len1][0] + ItemArr[len1][1]+ len1;
		DeliveryArr[len][1]=sel[0];  // Order Id
		DeliveryArr[len][2]=sel[1];  // Order Code
		DeliveryArr[len][3]=sel[2];  // Pcs Rec
		DeliveryArr[len][4]=sel[3];  // Pcs Disp
		DeliveryArr[len][5]=sel[7];  // Remarks
		DeliveryArr[len][6]=sel[8];  // Pcs to be dispatch
		DeliveryArr[len][7]=sel[4];	 // Challan No
		DeliveryArr[len][8]=sel[5];	 // Item Code
		DeliveryArr[len][9]=sel[6];	 //	Rate
		DeliveryArr[len][10]=sel[9]; // Returned
		DeliveryArr[len][11]=sel[10]; // PO Date
		DeliveryArr[len][12]=sel[11];  // Expeller Part or Power Press
		DeliveryArr[len][13]=sel[12];  // PT or PCS
		DeliveryArr[len][14]=sel[13];  // Collar
		DeliveryArr[len][15]=sel[14];  // Purchase Order Item Remarks
		DeliveryArr[len][16]=sel[15];  // Year Prefix
 	} 
	DispDeliveryInfo();
}

function PrintPdf(ChallanID , user, pass, db)
{

	page="../operations/delivery_challan_showpdf.php?ChallanID="+encodeURI(ChallanID)+"&user="+user+"&pass="+pass+"&db="+db;
	OpenWin = window.open(page, "Report", "toolbar=no,menubar=no,location=no,scrollbars=no,resizable=yes,width=550,height=550"); 
}

function ShowOrder(Id)
{
	order=(document.getElementById(Id).value).split('~ArrayItem~');
	dArr=order[1].split('-');
	document.getElementById('txtDate').value=dArr[2]+"/"+dArr[1]+"/"+dArr[0];
	document.getElementById('cmbParty').value=order[2]+"~ArrayItem~"+order[3];
	document.getElementById("txtDeliveryChallan").value=order[4];
	document.getElementById("txtRemarks").value=order[5];
	document.getElementById("cmbParty").disabled=true;

	if (order[0]!="")
	{
		url="../operations/delivery_challan_getchallan.php?ChallanId="+order[0];
		var http=false;
		if (navigator.appName=="Microsoft Internet Explorer")
		{
			http= new ActiveXObject ("Microsoft.XMLHTTP");
		}
		else
		{
			http= new XMLHttpRequest();
		}
		http.open ("GET",url);

		http.onreadystatechange=function()
		{
			if (http.readyState==4 && http.status==200)
			{
				if (http.responseText!="")
				{
					ItemData=(http.responseText).split('~ItemData~');
					if (DeliveryArr.length>0)
					{
						DeliveryArr.length=0;
					}
					if (ItemArr.length>0)
					{
						ItemArr.length=0;
					}
					for (x=0;x<ItemData.length ;x++ )
					{
						opt=(ItemData[x]).split('~Heading~');
						caption=opt[0].split('~Caption~');
						len1=ItemArr.length;
						ItemArr[len1]=new Array();
						ItemArr[len1][0]=caption[0];
						ItemArr[len1][1]=caption[1];
						sel=opt[1].split('~ArrayItem~');
						len= DeliveryArr.length
						DeliveryArr[len]=new Array();
						DeliveryArr[len][0]=ItemArr[len1][1];
						DeliveryArr[len][1]=sel[0];// Order Id
						DeliveryArr[len][2]=sel[1];// Order Code
						DeliveryArr[len][3]=sel[2];// Pcs Rec
						DeliveryArr[len][4]=sel[3];// Pcs Disp
						DeliveryArr[len][5]=sel[7];// Remarks
						DeliveryArr[len][6]=sel[8];// Pcs to be dispatch
						DeliveryArr[len][7]=sel[4];	 // Challan No
						DeliveryArr[len][8]=sel[5];	 // Item Code
						DeliveryArr[len][9]=sel[6];	 //	 Rate
					} 
					DispDeliveryInfo();
				}
			}
		}
		http.send(null);
	}
}

function selPODetails()
{
	$("#btnGetData").attr("disabled",true);
	var validata=true;
	var errmsg="";
	var focusid="";
	if($("#cmbParty").val()==='')
	{
		validata=false;
		errmsg="please select party first";
		focusid="cmbParty";
	}
	else if($("#txtFromDate").val()==='')
	{
		validata=false;
		errmsg="please select from date";
		focusid="txtFromDate";
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		$("#btnGetData").attr("disabled",false);
	}
	
	else
	{
		preloadfadeIn();
		$.ajax({
			url:'operations/delivery_challan_getpo.php',
			type:'POST',
			data:{"PartyId":$("#cmbParty").val(),"fromDate":$("#txtFromDate").val()},
			success:function(response)
			{
				
				DeliveryArr.length=0;
				ItemArr.length=0;
				$("#dvPoHeading").html("");
				
				document.getElementById('hdPODetails').value="";
				document.getElementById("txtDeliveryChallan").focus();
				var responseText=JSON.parse(response);
				
				if(responseText.error)
				{
					toastr.error(responseText.error_msg);
					$("#hdPODetails").val('');
					$("#txtDeliveryChallan").focus();
					preloadfadeOut();
				    $("#btnGetData").attr("disabled",false);
				}
				else
				{
					
					$("#hdPODetails").val(responseText.dataChallan);
					$("#btnGetData").attr("disabled",false);
					AddPODetails();
				}
			}
		})
			
	}
	
}

function AddPODetails()
{
	
	var x;
	ItemData=($("#hdPODetails").val()).split('~ItemData~');
	if($("#hdPODetails").val()!='')
	{
		for (x=0;x<ItemData.length ;x++ )
	{	
		opt=(ItemData[x]).split('~Heading~');
		caption=opt[0].split('~Caption~');			
		len1=ItemArr.length;
		ItemArr[len1]=new Array();
		ItemArr[len1][0]=caption[0];	 // Item Type
		ItemArr[len1][1]=caption[1];	 // Item Name
		opts=opt[1].split('~Array~');
		for (y=0;y<opts.length ;y++ )
		{	
			sel=(opts[y]).split('~ArrayItem~');
			len=DeliveryArr.length;
			DeliveryArr[len]=new Array();
			DeliveryArr[len][0]=ItemArr[len1][0] + ItemArr[len1][1]+ len1;// ItemType + Item Name + sno
			DeliveryArr[len][1]=sel[0];// Order Id
			DeliveryArr[len][2]=sel[1];// Order Code
			DeliveryArr[len][3]=sel[2];// Pcs Rec
			DeliveryArr[len][4]=sel[3];// Pcs Disp
			DeliveryArr[len][5]="";    // Remarks
			DeliveryArr[len][6]=0;     // Pcs to be dispatch
			DeliveryArr[len][7]=sel[4];// Challan No
			DeliveryArr[len][8]=sel[5];// Item Code
			DeliveryArr[len][9]=sel[6];// Rate
			DeliveryArr[len][10]=0;    // Pcs Returned
			DeliveryArr[len][11]=sel[7]; // Order Date
			DeliveryArr[len][12]=sel[8]; // Expeller Parts or Power Press 
			DeliveryArr[len][13]=sel[9];  // PT or PCS
			DeliveryArr[len][14]=sel[10]; // Collar
			DeliveryArr[len][15]=sel[11]; // Party Order Remarks
			DeliveryArr[len][16]=sel[12]; // PrefixYear
			DeliveryArr[len][17]=sel[13]; // financial year
			DeliveryArr[len][18]=sel[14]; // old or new table info
		}
	}
	DispDeliveryInfo();
	}
	else
	{
		toastr.error("No Order details found");
		preloadfadeOut();
	}
	
}

function DispDeliveryInfo()
{
	preloadfadeOut();
	$("#btnGetData").attr("disabled",false);
	$("#dvPoDetails").html("");
	$("#dvPoHeading").html("");
	var x;
	var j=0;
	var Total=0;
	var Itemname="";
	var check;
	var Data="";
	var Data1="<table class=\"table-bordered dt-responsive  no-footer dtr-inline dataTable\"><thead>";
	Data1+="<tr>";
	Data1+="<th width=\"3%\">SNO</th>";
	Data1+="<th width=\"20%\">Item Name</th>";
	Data1+="<th width=\"20%\">Purchase Order Date</th>";
	Data1+="<th width=\"18%\">Pcs Rec.</td>";
	Data1+="<th width=\"10%\">Pcs Dis.</td>";
	Data1+="<th width=\"10%\">Balance.</td>";
	
	Data1+="<th width=\"13%\">Pcs Dispatch</th>";
	Data1+="<th width=\"13%\">Pcs Returned</th>";
	Data1+="<th width=\"13%\">Remarks</th></tr></thead><tbody>";
	
	//$("#dvPoHeading").html(Data1);
	
	
	for (x=0;x<ItemArr.length ;x++ )
	{
		if (Itemname!=ItemArr[x][0])
		{
			Data+="<tr>";
			Data+="<td colspan=\"9\"><b><font color='green'>"+ItemArr[x][0]+"</font></b></td>";
			Data+="</tr>";
			Itemname=ItemArr[x][0];
		}
		
		Data+="<tr>";
		Data+="<td width=\"5%\">"+(x+1)+"</td>";
		Data+="<td><b>"+ItemArr[x][0]+"&nbsp;"+ItemArr[x][1]+"</b>";
		
		Data+="</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
		Data+="</tr>";
		for (j=0;j<DeliveryArr.length ;j++ )
		{
			check=(ItemArr[x][0]+ItemArr[x][1]+x) ;
			if (check==DeliveryArr[j][0])
			{
				Data+="<tr>";
				Data+="<td>&nbsp;</td>";
				Data+="<td>&nbsp;</td>";
				Data+="<td><font color=\"red\">"+DeliveryArr[j][11];
				if (DeliveryArr[j][7]!="")
				{
					Data+="<br>Party Challan No : "+DeliveryArr[j][7];
				}
					// Collar added for pinion only and check whether collar is empty or not. If empty then do not print collar
				if (DeliveryArr[j][14]!="")
				{
					Data+="<br> Collar :"+DeliveryArr[j][14];
				}
				if (DeliveryArr[j][15]!="")
				{
					Data+="<br> Remarks :"+DeliveryArr[j][15];
				}
				Data+="</font></td>";
				Data+="<td>PCS Recd. : <b>"+DeliveryArr[j][3]+"</b></font><br></td>";
				Data+="<td><font color=\"green\"><b>"+DeliveryArr[j][4]+"</b></font></td>";
				Total=parseInt(DeliveryArr[j][3]) - parseInt(DeliveryArr[j][4]);
				Data+="<td><font color=\"red\"><b>"+Total+"</b></font></td>";
				txt="txtDispatch"+j;
				Data+="<td><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+DeliveryArr[j][6]+"\"  style=\"border:1px solid red;\" class=\"form-control\" onChange=\"FillArray(this.id,"+j+")\" onkeydown=\"OnlyInt1(this.id)\"></td>";
				txtR="txtReturned"+j;
				Data+="<td><input type=\"text\" name=\""+txtR+"\" id=\""+txtR+"\" value=\""+DeliveryArr[j][10]+"\" style=\"border:1px solid blue;\" class=\"form-control\" onChange=\"FillReturn(this.id, "+j+")\" onkeydown=\"OnlyInt1(this.id)\"></td>";
				txt1="txtRemarks"+j;
				Data+="<td><input type=\"text\" name=\""+txt1+"\" id=\""+txt1+"\" value=\""+DeliveryArr[j][5]+"\" class=\"form-control\"  onChange=\"FillRemarks(this.id,"+j+")\"></td>";
				Data+="</tr>";
			}
		}
	}
	Data=Data1+Data+"</tbody></table>";
	$("#dvPoHeading").html("");
	$("#dvPoHeading").html(Data);
	
	ConcatData();
	
} 

function ConcatData()
{
	var packed="";
	var ItemData="";
	var Data="";
	var check="";
	$("#hdPODetails").val("");
	
	if (ItemArr.length>0 && DeliveryArr.length>0)
	{
		for (x=0;x<ItemArr.length;x++ )
		{
			 ItemData = ItemArr[x][0]+"~Caption~"+ItemArr[x][1]+"~Heading~";
			 for (y=0;y<DeliveryArr.length ;y++ )
			 {
			 	check=(ItemArr[x][0]+ItemArr[x][1]+x) ;
				
				if (check==DeliveryArr[y][0])
				{
					if (Data=="")
					{
						Data=DeliveryArr[y][0]+"~ArrayItem~"+DeliveryArr[y][1]+"~ArrayItem~"+DeliveryArr[y][2]+"~ArrayItem~"+DeliveryArr[y][3]+"~ArrayItem~"+DeliveryArr[y][4]+"~ArrayItem~"+DeliveryArr[y][5]+"~ArrayItem~"+DeliveryArr[y][6]+"~ArrayItem~"+DeliveryArr[y][7]+"~ArrayItem~"+DeliveryArr[y][8]+"~ArrayItem~"+DeliveryArr[y][9]+"~ArrayItem~"+DeliveryArr[y][10]+"~ArrayItem~"+DeliveryArr[y][11]+"~ArrayItem~"+DeliveryArr[y][12]+"~ArrayItem~"+DeliveryArr[y][13]+"~ArrayItem~"+DeliveryArr[y][14]+"~ArrayItem~"+DeliveryArr[y][16]+"~ArrayItem~"+DeliveryArr[y][17]+"~ArrayItem~"+DeliveryArr[y][18];		
					}
					else
					{
						Data=Data+"~Array~"+DeliveryArr[y][0]+"~ArrayItem~"+DeliveryArr[y][1]+"~ArrayItem~"+DeliveryArr[y][2]+"~ArrayItem~"+DeliveryArr[y][3]+"~ArrayItem~"+DeliveryArr[y][4]+"~ArrayItem~"+DeliveryArr[y][5]+"~ArrayItem~"+DeliveryArr[y][6]+"~ArrayItem~"+DeliveryArr[y][7]+"~ArrayItem~"+DeliveryArr[y][8]+"~ArrayItem~"+DeliveryArr[y][9]+"~ArrayItem~"+DeliveryArr[y][10]+"~ArrayItem~"+DeliveryArr[y][11]+"~ArrayItem~"+DeliveryArr[y][12]+"~ArrayItem~"+DeliveryArr[y][13]+"~ArrayItem~"+DeliveryArr[y][14]+"~ArrayItem~"+DeliveryArr[y][16]+"~ArrayItem~"+DeliveryArr[y][17]+"~ArrayItem~"+DeliveryArr[y][18];		
					}
				    if (packed=="")
				    {
						packed=ItemData + Data;
				 	}
				 	else
				 	{
						packed= packed +"~ItemData~"+ItemData + Data;
				 	}
					
				}
				 
				Data="";
			 }
			
		}
	}
	$("#hdPODetails").val(packed);
	
}

	// Pcs Dispatch
function FillArray(Id,z)
{
	limit=parseInt(DeliveryArr[z][3])-parseInt(DeliveryArr[z][4]);
	val=parseInt(document.getElementById(Id).value);
	if (isNaN(val))
	{
		val=0;
	}
	if (val>limit)
	{
		toastr.error("Pcs to be dispatch can not be more than Total pcs...");
		$("#"+Id).val("0");
		document.getElementById(Id).focus();	
		event.returnValue=false;
		
	}
	else
	{
		Id1=(Id).replace('txtDispatch','txtReturned');
		Disp=parseInt(document.getElementById(Id1).value);
			
		Disp=parseInt(document.getElementById(Id1).value);
		if (isNaN(Disp))
		{
			Disp=0;
		}
		val1=parseInt(val) + parseInt(Disp);
		if (val1 >limit)
		{
			toastr.error("Pcs to be dispatch can not be more than Total pcs...");
			document.getElementById(Id).focus();
		    $("#"+Id).val("0");
			event.returnValue=false;
			
		}
		else
		{
			DeliveryArr[z][6]=val;	
			DispDeliveryInfo();
			do{
			document.getElementById(Id1).focus();
			}while (document.getElementById(Id1).focus());
		}
	}
}	

/*function FillArray(Id,z)
{	
	val=parseInt(document.getElementById(Id).value);
	Id1=(Id).replace('txtDispatch','txtReturned');
	if (val==0)
	{
		returnedQty=parseInt(document.getElementById(Id1).value);
		if (returnedQty>0)
		{
			alert ("Return Qty can not be more than Dispatch Qty")
			event.returnValue=false;
			document.getElementById(Id1).focus();
		}
		else
		{
			DeliveryArr[z][6]=val;
			DispDeliveryInfo();
		}
	}
	else if (isNaN(val))
	{
		DeliveryArr[z][6]=0;	
		DispDeliveryInfo();
	}
	else
	{	
		limit=parseInt(DeliveryArr[z][3])-parseInt(DeliveryArr[z][4]);
		
		Disp=parseInt(document.getElementById(Id1).value);
		val1=parseInt(val) - parseInt(Disp);
		if (val1 >limit)
		{
			alert ("Pcs to be dispatch can not be more than Total pcs...");
			event.returnValue=false;
			document.getElementById(Id).focus();
		}
		else
		{
			DeliveryArr[z][6]=val;	
			DispDeliveryInfo();
			document.getElementById(Id1).focus();	
		}
	}
}*/

function FillRemarks(Id,z)
{
	if (document.getElementById(Id).value!="")
	{
		DeliveryArr[z][5]=document.getElementById(Id).value;	
		DispDeliveryInfo();
		document.getElementById(Id).focus();	
	}
	else
	{
		DeliveryArr[z][5]="";
		DispDeliveryInfo();
		document.getElementById(Id).focus();
	}
}
	// Pcs Returned
function FillReturn(Id,z)
{
	var val1;
	var val;
	var limit;
	var Disp;
	
	limit=parseInt(DeliveryArr[z][3])-parseInt(DeliveryArr[z][4]);
	val=parseInt(document.getElementById(Id).value);
	Id1=(Id).replace('txtReturned','txtDispatch');
	Disp=parseInt(document.getElementById(Id1).value);
	
	if (isNaN(val))
	{
		val=0;
	}
	if (isNaN(Disp))
	{
		Disp=0;
	}
	if (val>limit)
	{
		toastr.error("Pcs to be Returned can not be more than Total pcs...");
		document.getElementById(Id).focus();
		$("#"+Id).val("0");
        event.returnValue=false;		
	}
	else
	{
		va11=parseInt(Disp) +  parseInt(val);
		
		if (va11>limit)
		{
			toastr.error("Pcs Returned cannot be greater than Total pcs...");
			document.getElementById(Id).focus();
			$("#"+Id).val("0");
			event.returnValue=false;
			
		}
		else
		{
			DeliveryArr[z][10]=document.getElementById(Id).value;
			DispDeliveryInfo();
			Id2=(Id).replace('txtReturned','txtRemarks');
			do{
			document.getElementById(Id2).focus();	
			}while (document.getElementById(Id2).focus());
		}
	}
}

/*function FillReturn(Id,z)
{
	var val1;
	var val;
	var limit;
	var Disp;
	val=parseInt(document.getElementById(Id).value);
	Id1=(Id).replace('txtReturned','txtDispatch');
	if (val==0)
	{
		Disp=parseInt(document.getElementById(Id1).value);	
		
		va11=parseInt(Disp) + parseInt(val);
		limit=parseInt(DeliveryArr[z][3])-parseInt(DeliveryArr[z][4]);
		if (va11>limit)
		{
			alert ("Pcs Returned cannot be greater than Total pcs...");
			event.returnValue=false;
			document.getElementById(Id).focus();
		}
		else
		{	
			DeliveryArr[z][10]=val;
			DispDeliveryInfo();
		}
	}
	else if (isNaN(val))
	{
		DeliveryArr[z][10]=0;	
		DispDeliveryInfo();
	}
	else
	{
		limit=parseInt(DeliveryArr[z][3])-parseInt(DeliveryArr[z][4]);
		
		Disp=parseInt(document.getElementById(Id1).value);
		if (val > Disp)
		{
			alert ("Pcs returned cannot be greater than Pcs Dispatch");
			event.returnValue=false;
			document.getElementById(Id).focus();
		}
		else
		{
			if (isNaN(Disp))
			{
				Disp=0;
			}
			va11=parseInt(Disp)- parseInt(val);
			if (val1>limit)
			{
				alert ("Pcs Returned cannot be greater than Total pcs...");
				event.returnValue=false;
				document.getElementById(Id).focus();
			}
			else
			{
				DeliveryArr[z][10]=document.getElementById(Id).value;	
				DispDeliveryInfo();
				Id2=(Id).replace('txtReturned','txtRemarks');
				document.getElementById(Id2).focus();
			}
		}
	}
}*/




function ShowPO(ChallanID)
{
	document.location.href="../operations/delivery_challan.php?ChallanID="+ChallanID;
}

function DCDelete(ChallanID)
{
	lbl=document.getElementById('lblErrMsg');
	lbl.innerHTML="";
	var x;
	x=confirm("Are you Sure! You want to DELETE this record?");
	if (x==true)
	{
		url="../operations/delivery_challan_delete.php?ChallanID="+ChallanID;
		var http=false;
		if (navigator.appName=="Microsoft Internet Explorer")
		{
			http=new ActiveXObject('Microsoft.XMLHTTP'); 
		}
		else
		{
			http=new  XMLHttpRequest();
		}
		http.open('GET',url);
		http.onreadystatechange=function ()
		{
			if (http.readyState==4 && http.status==200)
			{
				if (http.responseText!='')
				{
					alert (http.responseText);
				}
			}
		}
		http.send(null);
	}
	document.location=document.location.href;
	return true;
}

/*function showExecute()
{
	message="";
	msg="";
	ItemData= (document.getElementById("hdPODetails").value).split("~ItemData~");	
	for (i=0;i<ItemData.length;i++)
	{
		
		opt=(ItemData[i]).split("~Heading~");
		caption=(opt[0]).split("~Caption~");
		ItemType=caption[0];
		ItemName=caption[1];
		opt1=(opt[1]).split("~Array~");
		for (y=0; y<opt1.length;y++)
		{
			sel=(opt1[y]).split('~ArrayItem~');
			OrderID=sel[1];  // Order Id
			QtyDisp=sel[4];  //  Pcs dispatch
			DispRemarks=sel[5];  //  Remarks
			QtyToDisp=sel[6];  //  Pcs to be dispatch
			ItemCode=sel[8];	//	ItemCode
			Rate=sel[9];	//	 Rate
			Returned=sel[10];
			OrderDate=sel[11];  // Date
			PartType=sel[12]; // Power Press  or Expeller Parts
			
			if (QtyToDisp>0 || Returned >0)
			{
				msg+= "Date : "+OrderDate+"  Dispatch Qty : "+QtyToDisp + " Return Qty : " + Returned+" Remarks : "+ DispRemarks+"\n\n";
			}
		}
		if (msg!="")
		{
			varRegExp=/&nbsp;/;
			ItemName=(ItemName).replace(varRegExp, "  ");
			message +=ItemType +" "+ItemName + " " +msg;
		}
		
		msg="";
	}
	alert (message);
}*/

function PrintReport()
 {	
	var Total=0;
	var WinPrint=window.open('','','left=30,top=10,width=800,height=600,scrollbars=1','0');
	date=document.getElementById("txtFromDate").value;
	PartyName=document.getElementById("cmbParty").options[document.getElementById("cmbParty").selectedIndex].text;
	var msg="";
	var message="";
	var SN0=1;
	message="<span class=\"tablecell\">Party :"+PartyName +"</span><span style=\"margin-left:100px;\"> <span class=\"tablecell\">Date :"+date+"</span>";
	message+="<table cellpadding=\"0\" cellspacing=\"0\" width=\"620\" class=\"labeltext\">";
	message+="<tr class=\"tableheadingleft\">";
	message+="<td width=\"30\" height=\"25px\">SNO</td>";
	message+="<td width=\"390\">Item Name</td>";
	message+="<td width=\"90\">Qty</td></tr>";
	
	var ItemData=($("#hdPODetails").val()).split("~ItemData~");
	for (i=0;i<ItemData.length;i++)
	{
		var opt=(ItemData[i]).split("~Heading~");
		var caption=(opt[0]).split("~Caption~");
		var ItemType=caption[0];
		var ItemName=caption[1];
		var opt1=(opt[1]).split("~Array~");
		for (y=0; y<opt1.length;y++)
		{
			sel=(opt1[y]).split('~ArrayItem~');
			OrderID=sel[1];  // Order Id
			QtyDisp=sel[4];  //  Pcs dispatch
			DispRemarks=sel[5];  //  Remarks
			QtyToDisp=sel[6];  //  Pcs to be dispatch
			ItemCode=sel[8];	//	ItemCode
			Rate=sel[9];	//	 Rate
			Returned=sel[10];
			OrderDate=sel[11];  // Date
			PartType=sel[12]; // Power Press  or Expeller Parts
			
			
			if (QtyToDisp>0 || Returned >0)
			{
				//msg+="<td class=\"tablecell\">"+OrderDate+"</td><td class=\"tablecell\">"+QtyToDisp+"</td><td class=\"tablecell\">"+ Returned+"</td><td class=\"tablecell\">"+ DispRemarks+"&nbsp;</td>";
				msg+="<td class=\"tablecell\">"+QtyToDisp;
				if (Returned>0)
				{
					msg+="<br>"+Returned +"(Returned)";	
				}
				msg+="</td>";
				Total=Total + parseInt(QtyToDisp) + parseInt(Returned);
			}
		}
		if (msg!="")
		{
			message+="<tr><td class=\"tablecell\">"+SN0+"</td><td class=\"tablecell\">"+ItemType +" "+ItemName;
			if (DispRemarks!='')
				message+="<br><b>"+DispRemarks+"</b>";
			message+="</td>"+ " " +msg+"</tr>";
			SN0++;
		}
		msg="";
	}
	message+="<tr>";
	message+="<td class=\"tablecell\" colspan=\"2\" >Total :</td><td class=\"tablecell\" colspan=\"2\">"+Total+"</td>";
	message+="</tr>";
	message+="</table>";
	WinPrint.document.write("<html>");
	WinPrint.document.write("<head>");
	WinPrint.document.write("<title>");
	WinPrint.document.write("Party Challan Order");
	WinPrint.document.write("</title>");
	WinPrint.document.write("<link rel='Stylesheet' href='../games.css' type='text/css'/>");
	WinPrint.document.write("<style type='text/css'>body{margin-left:5px;margin-top:0px;margin-right:0px;margin-bottom:0px;}</style>");
	WinPrint.document.write("</head>");
	WinPrint.document.write("<body>");
	WinPrint.document.write("<br><br>");
	WinPrint.document.write(message);
	WinPrint.document.write("</body>");
	WinPrint.document.write("</html>");
	WinPrint.document.close();
	WinPrint.focus();
}

$("#btnSave").click(function(){
	var validata=true;
	var focusid="";
	var errmsg="";
	
	if ($("#cmbParty").val()==="")
	{
		validata=false;
		errmsg="Please select Party";
		focusid="cmbParty";
		
	}
	else  if($("#txtDeliveryChallan").val()==="")
	{
		validata=false;
		errmsg="Please enter Delivery Challan No";
		focusid="txtDeliveryChallan";
		
		
	}
	else if ($("#txtFromDate").val()==="")
	{
		validata=false;
		errmsg="Please select Date";
		focusid="txtFromDate";
		
		
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		event.returnValue=false;
		$("#cmbParty").attr("disabled",false);
	}
	else
	{
		var formData = new FormData();
        		var other_data = $('#frmChallanOrder').serializeArray();
				$.each(other_data,function(key,input){
					
        			formData.append(input.name,input.value);
        		});
				
				
		$.ajax({
					url:'operations/save_delivery_challan.php',
					type:'POST',
					data:formData,
					async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
					
					success:function(response)
					{
						
						preloadfadeOut();
						alert(response);
						// var response2=JSON.parse(response);
						// if(response2.error)
						// {
							// toastr.error(response2.error_msg);
						// }
						// else
						// {
							// toastr.success(response2.error_msg);
							// MenuClick2($("#auth_info").val(),btoa(btoa("operations/delivery_challan.php")));
							
						// }
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
	
	
})


