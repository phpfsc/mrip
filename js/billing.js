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

var DcArr=new Array();

var ItemArr=new Array();

var OrderArr=new Array();



function PageLoad2()

{

	if (document.getElementById("hdPageMode").value=="Edit")

	{

		DispalyEdit();

		SelParty(document.getElementById("hdPartySNo").value);

		document.getElementById("dvVat").style["display"]="inline";

		document.getElementById('txtRemarks').focus();

		document.getElementById('cmbParty').disabled=true;

	}

	else

	{

		document.getElementById('cmbParty').focus();

	}

}



function PageLoad3()

{

	if (document.getElementById("hdPageMode").value=="Edit")

	{

		DispalyEdit1();

		SelParty(document.getElementById("hdPartySNo").value);

		document.getElementById("dvVat").style["display"]="inline";

		document.getElementById('cmbItem').focus();

		document.getElementById('cmbParty').disabled=true;

	}

	else

	{

	/*	document.getElementById("cmbItem").disabled=true;

		document.getElementById("cmbGear").disabled=true;

		document.getElementById("btnAddGear").disabled=true;

		document.getElementById("cmbPinion").disabled=true;

		document.getElementById("btnAddPinion").disabled=true;

		document.getElementById("cmbShaftPinion").disabled=true;

		document.getElementById("btnAddShaftPinion").disabled=true;

		document.getElementById("cmbBevelGear").disabled=true;

		document.getElementById("btnAddBevelGear").disabled=true;

		document.getElementById("cmbBevelPinion").disabled=true;

		document.getElementById("btnAddBevelPinion").disabled=true;

		document.getElementById("cmbChainWheel").disabled=true;

		document.getElementById("btnAddChainWheel").disabled=true;*/

		document.getElementById('cmbParty').focus();

	}

}



function SwitchDC(Id)

{

	
	if ($("#"+Id).val()=="With Challan")
    {
		
       preloadfadeIn();
		$.ajax({
		   url:'operations/billing.php',
		   type:'POST',
		   data:{"auth_info":$("#auth_info").val(),"txtBillingUser":$("#txtBillingUser").val()},
		   success:function(response)
		   {
			   
			   $('.page-content').html(response);
			   preloadfadeOut();
	               
		   },
		   error:function(response)
		   {
			   
			   if(response.status=='404')
			   {
				   toastr.error("Page not found");
				   preloadfadeOut();
	               
			   }
			   else if(response.status=='500')
			   {
				   toastr.error("Internal server error");
				   preloadfadeOut();
	               
			   }
			   else
			   {
				   toastr.error("Communication error");
				   preloadfadeOut();
	               
			   }
		   }
	})
		
        

	}

	 else

	{
        preloadfadeIn();
		$.ajax({
		   url:'operations/billing_withoutdc.php',
		   type:'POST',
		   data:{"auth_info":$("#auth_info").val(),"txtBillingUser":$("#txtBillingUser").val()},
		   success:function(response)
		   {
			   
			   $('.page-content').html(response);
			   preloadfadeOut();
	               
		   },
		   error:function(response)
		   {
			   
			   if(response.status=='404')
			   {
				   toastr.error("Page not found");
				   preloadfadeOut();
	              
			   }
			   else if(response.status=='500')
			   {
				   toastr.error("Internal server error");
				   preloadfadeOut();
	               
			   }
			   else
			   {
				   toastr.error("Communication error");
				   preloadfadeOut();
	              
			   }
		   }
	})

		

		

	}
	

}



function PageLoad1()

{

	document.getElementById("cmbSelParty").disabled=true;

	document.getElementById("cmbSelCompany").disabled=true;

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



function ShowBilledChallan(ChallanID) 

{ 		

	win = new Window({className: "alphacube", title: "Billed Challan", top:100, left:50, width:700, height:350,url: "operations/billing_showbilled.php?ChallanID="+encodeURI(ChallanID), showEffectOptions: {duration:1.5}});

	win.show();

} 



function Switch(Id)

{
    if ($("#"+Id).val()=="Pending")
    {
		
        DcArr.length=0;
        ItemArr.length=0;
        OrderArr.length=0;
		$("#cmbParty").attr("disabled",false);
		$("#txtRemarks").val("");
		$("#hdBillingData").val("");
		$("#dvChallanDetails").val("");
       
		preloadfadeIn();
		$.ajax({
		   url:'operations/billing_edit.php',
		   type:'POST',
		   data:{"auth_info":$("#auth_info").val(),"txtBillingUser":$("#txtBillingUser").val()},
		   success:function(response)
		   {
			   
			   $('.page-content').html(response);
			   preloadfadeOut();
	               
		   },
		   error:function(response)
		   {
			   
			   if(response.status=='404')
			   {
				   toastr.error("Page not found");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
			   else if(response.status=='500')
			   {
				   toastr.error("Internal server error");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
			   else
			   {
				   toastr.error("Communication error");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
		   }
	})
		//MenuClick2($("#auth_info").val(),btoa(btoa("operations/billing_edit.php")));
        

	}

	 else

	{
        preloadfadeIn();
		$.ajax({
		   url:'operations/billing.php',
		   type:'POST',
		   data:{"auth_info":$("#auth_info").val(),"txtBillingUser":$("#txtBillingUser").val()},
		   success:function(response)
		   {
			   
			   $('.page-content').html(response);
			   preloadfadeOut();
	               
		   },
		   error:function(response)
		   {
			   
			   if(response.status=='404')
			   {
				   toastr.error("Page not found");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
			   else if(response.status=='500')
			   {
				   toastr.error("Internal server error");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
			   else
			   {
				   toastr.error("Communication error");
				   preloadfadeOut();
	               $(".delete").attr("disabled",false);
			   }
		   }
	})

		

		DcArr.length=0;

		ItemArr.length=0;

		OrderArr.length=0;

	}

}

	// Displaying Info for modification



function DispalyEdit()

{

	if (DcArr.length>0)

	{

		DcArr.length=0;

	}

	if (ItemArr.length>0)

	{

		ItemArr.length=0;

	}


	if(document.getElementById("hdBillingData").value!='')
	{
		ItemData=(document.getElementById("hdBillingData").value).split('~ItemData~');
	
		
	
		for (x=0;x<ItemData.length ;x++ )
	
		{
	
			heading=(ItemData[x]).split('~Heading~');
	
			
	
			caption=(heading[0]).split('~Caption~');
	
			len=DcArr.length;
	
			DcArr[len]=new Array();
	
			DcArr[len][0]=caption[0];	  // OrderCode
	
			DcArr[len][1]=caption[1];	  // OrderID
	
			DcArr[len][2]=caption[2];	  // Order Date
	
			DcArr[len][3]=caption[3];
	
			DcArr[len][4]=caption[4];
	
			
	
			opt=(heading[1]).split('~Array~');
	
			for (y=0;y<opt.length ;y++ )
	
			{
	
				sel=opt[y].split('~ArrayItem~');
	
				len1=ItemArr.length;
	
				ItemArr[len1]=new Array();
	
				ItemArr[len1][0]=caption[1];		// OrderId
	
				ItemArr[len1][1]=sel[0];	// Category
	
				ItemArr[len1][2]=sel[1];	// ItemCode
	
				ItemArr[len1][3]=sel[2];	// ItemName
	
				ItemArr[len1][4]=sel[3];	// Teeth			
	
				ItemArr[len1][5]=sel[4];	// Total Qty Rec
	
				ItemArr[len1][6]=sel[5];	// Total Disp
	
				ItemArr[len1][7]=sel[6];	// PartType (Power Press or Expeller)
	
				ItemArr[len1][8]=sel[7];  // Billed Qty	
	
				ItemArr[len1][9]=sel[8];  // Remaining Qty
	
				ItemArr[len1][10]=sel[9]; // Rate
	
				ItemArr[len1][11]=sel[10]; // Price
	
				ItemArr[len1][12]=sel[11]; // Item Type (Plain or Helical)
	
				ItemArr[len1][13]=sel[12];  // PT or PCS
	
				ItemArr[len1][14]=sel[13];  // Collar
	
				ItemArr[len1][15]=sel[14];  // Item Remarks
	
			}
	
		}
	}
	DispDCInfo();

}



function ShowOrder(Id)

{

	billing=(document.getElementById(Id).value).split('~ArrayItem~');

	dArr=billing[1].split('-');

	document.getElementById('txtBillingDate').value=dArr[2]+"/"+dArr[1]+"/"+dArr[0];

	document.getElementById('cmbParty').value=billing[2]+"~ArrayItem~"+billing[3];

	document.getElementById("txtRemarks").value=billing[4];

	document.getElementById("cmbParty").disabled=true;



	if (billing[0]!="")

	{

		var http=false;

		party=(document.getElementById(Id).value).split('~ArrayItem~');

		PartyId=party[2];

		FirmId=party[3];



		url="operations/billing_getbilling.php?PartyId="+PartyId+"&FirmId="+FirmId;

		if (navigator.appName=="Microsoft Internet Explorer")

		{

			http= new ActiveXObject ("Microsoft.XMLHTTP");

		}

		else

		{

			http=	 new XMLHttpRequest();

		}

		http.open ("GET",url);



		http.onreadystatechange=function()

		{

			if (http.readyState==4 && http.status==200)

			{

				if (http.responseText!="")

				{

					if (DcArr.length>0)

					{

						DcArr.length=0;

					}

					if (ItemArr.length>0)

					{

						ItemArr.length=0;

					}



					ItemData=(http.responseText).split('~ItemData~');

					for (x=0;x<ItemData.length ;x++ )

					{

						heading=(ItemData[x]).split('~Heading~');

						caption=(heading[0]).split('~Caption~');

						len=DcArr.length;

						DcArr[len]=new Array();

						DcArr[len][0]=caption[0];	// ChallanId

						DcArr[len][1]=caption[1];	// challanCode	

						DcArr[len][2]=caption[2];	// Challandate

						DcArr[len][3]=caption[3];	// UnBilled item

						DcArr[len][4]=caption[4];  // checked or unchecked

						

						opt=(heading[1]).split('~Array~');

						for (y=0;y<opt.length ;y++ )

						{

							sel=opt[y].split('~ArrayItem~');

							len1=ItemArr.length;

							ItemArr[len1]=new Array();

							ItemArr[len1][0]=caption[0];	    // ChallanId

							ItemArr[len1][1]=sel[0];			// Item Type

							ItemArr[len1][2]=sel[1];			// Item Code

							ItemArr[len1][3]=sel[2];			// Item Name

							ItemArr[len1][4]=sel[3];			// Price

							ItemArr[len1][5]=sel[4];			// Disp Qty

							ItemArr[len1][6]=sel[5];			// value

						    ItemArr[len1][7]=sel[6];   // Teeth

							ItemArr[len1][8]=sel[7];	// 	 Item Type

						}

					}

					DispDCInfo();

				}

			}

		}

		http.send(null);

	} 

}



function calculate (Pcs, Rate, Teeth, ItemType, Category,PartType, OrderId, OriginalArr)

{

	for (i=0; i<OriginalArr.length;i++)

	{

		if (OriginalArr[i][1]== OrderId)

		{

			MinValue=OriginalArr[i][3];

			break;	

		}

	}

	

	var Price=0;

	var IPrice=0;

	if (Category=="Gear")

	{

		Price=parseInt(Pcs)*parseInt(Teeth)* parseFloat(Rate); 

	}

	else if (Category=="Pinion")

	{

		

		Price=parseInt(Pcs)*parseInt(Teeth)* parseFloat(Rate); 

		

		if (PartType=="PowerPress")

		{

			IPrice=parseFloat(Price)/parseInt(Pcs);

			if (IPrice<MinValue)

			{

				Price=parseInt(Pcs) * parseFloat(MinValue);

			}

		}

		

	}

	else if (Category=="Shaft Pinion")

	{

		Price=parseInt(Pcs)	* parseFloat(Rate);

	}

	else if (Category=="Bevel Gear")

	{

		Price=parseInt(Pcs) * parseFloat(Rate) * parseInt(Teeth);

	}

	else if (Category=="Bevel Pinion")

	{

		Price=parseInt(Pcs) * parseFloat(Rate) * parseInt(Teeth);

	}

	else if (Category=="Chain Wheel")

	{

		/*if (ItemType=="Single")

		{

			fac=1;

		}

		else if (ItemType=="Duplex")

		{

			 fac=2;

		}

		else if (ItemType=="Triplex")

		{

			 fac=3;

		}

		else 

		{

			fac=4;

		}*/

		Price=parseInt(Pcs)* parseInt(Teeth)*parseFloat(Rate);

	}

	else if (Category=="Worm Gear")

	{

		Price=parseInt(Pcs)*parseFloat(Rate);

	}

	Price=Price.toFixed(2);

	return Price;

}



function calculate1 (Pcs, Rate, Teeth, ItemType, Category,PartType)

{

	var Price=0;

	var IPrice=0;

	MinValue=document.getElementById("hdMinValue").value;

	if (Category=="Gear")

	{

		Price=parseInt(Pcs)*parseInt(Teeth)* parseFloat(Rate); 

	}

	else if (Category=="Pinion")

	{

		Price=parseInt(Pcs)*parseInt(Teeth)* parseFloat(Rate); 

		if (PartType=="PowerPress")

		{

			IPrice=parseFloat(Price)/parseInt(Pcs);

			if (IPrice<MinValue)

			{

				Price=parseInt(Pcs) * parseFloat(MinValue);

			}

		}

	}

	else if (Category=="Shaft Pinion")

	{

		Price=parseInt(Pcs)	* parseFloat(Rate);

	}

	else if (Category=="Bevel Gear")

	{

		Price=parseInt(Pcs) * parseFloat(Rate) * parseInt(Teeth);

	}

	else if (Category=="Bevel Pinion")

	{

		Price=parseInt(Pcs) * parseFloat(Rate) * parseInt(Teeth);

	}

	else if (Category=="Chain Wheel")

	{

		Price=parseInt(Pcs)* parseInt(Teeth)*parseFloat(Rate);

	}

	else if (Category=="Worm Gear")

	{

		Price=parseInt(Pcs)*parseFloat(Rate);

	}

	Price=Price.toFixed(2);

	return Price;

}





function selDCDetails(Id)

{

	var validata=true;
	var errmsg="";
	var focusid="";

	val=(document.getElementById(Id).value);
	if(document.getElementById('txtStateCode').value=='03'){
		document.getElementById('SGSTdiv').style.display='table-row';
		document.getElementById('CGSTdiv').style.display='table-row';
		document.getElementById('IGSTdiv').style.display='none';
	}else{
		document.getElementById('SGSTdiv').style.display='none';
		document.getElementById('CGSTdiv').style.display='none';
		document.getElementById('IGSTdiv').style.display='table-row';
	}
	

	if (val!="")

	{

		opt=val.split("~ArrayItem~");

		PartyId=opt[0];

		//FromDate=document.getElementById("txtFromDate").value;

		ToDate=document.getElementById("txtToDate").value;
		// $.ajax({
			// url:'operations/billing_getdc.php',
			// type:'get',
			// data:{"PartyId":PartyId,"ToDate":ToDate},
			// success:function(data)
			// {
				// alert(data);
			// }
			
		// });

		url="operations/billing_getdc.php?PartyId="+PartyId+"&ToDate="+ToDate;
        
       

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

					

					if ((http.responseText).length>10)

					{

						if (DcArr.length>0)

						{

							DcArr.length=0;

						}

						if (ItemArr.length>0)

						{

							ItemArr.length=0;

						}

						
                        
						opt=(($.trim(http.responseText)).split('~ItemData~'));
                        
						for (x=0;x<opt.length ;x++ )
                        {
                            
							sel=opt[x].split('~Heading~');
                            
							opts=sel[0].split('~Caption~');

							len1=DcArr.length;

							DcArr[len1]=[];
                            
							DcArr[len1][0]=opts[0];	  // OrderCode
                            
							DcArr[len1][1]=opts[1];	  // OrderID

							DcArr[len1][2]=opts[2];	  // Order Date

							DcArr[len1][3]=opts[3];   // fMinValue

							DcArr[len1][4]=opts[4];   // Year Prefix
                            
							item1=(sel[1]).split('~Array~');

							for (y=0;y<item1.length ;y++)

							{
                                
								arrayitem=(item1[y]).split('~ArrayItem~');

								len2=ItemArr.length;

								ItemArr[len2]=new Array();
      
								ItemArr[len2][0]=opts[1];		// OrderId
                                
								ItemArr[len2][1]=arrayitem[0];	// Category

								ItemArr[len2][2]=arrayitem[1];	// ItemCode
                                
								ItemArr[len2][3]=arrayitem[2];	// ItemName

								ItemArr[len2][4]=arrayitem[3];	// Teeth			

								ItemArr[len2][5]=arrayitem[4];	// Total Qty Rec

								ItemArr[len2][6]=arrayitem[5];	// Total Disp

								ItemArr[len2][7]=arrayitem[6];	// PartType (Power Press or Expeller)

								ItemArr[len2][8]=arrayitem[7];  // Billed Qty	

								ItemArr[len2][9]=arrayitem[8];  // Remaining Qty

								ItemArr[len2][10]=arrayitem[9]; // Rate

								ItemArr[len2][11]=arrayitem[10]; // Price

								ItemArr[len2][12]=arrayitem[11]; // Item Type (Plain or Helical)

								

								ItemArr[len2][13]=arrayitem[12];  // PT or PCS

								ItemArr[len2][14]=arrayitem[13];  // Collar

								ItemArr[len2][15]=arrayitem[14];  // Item Remarks

							}

						}

						document.getElementById(Id).disabled=true;

						document.getElementById("dvVat").style['display']="inline";

						DispDCInfo();

						var TotalValue;

						TotalValue=document.getElementById("hdGTWithoutTax").value;

						TotalValue=Math.round(TotalValue);

						document.getElementById("lblTotalAmt").innerHTML=TotalValue;

						document.getElementById("hdTotalWithTax").value=TotalValue;

					}

					else

					{

  						if (DcArr.length>0)

						{

							DcArr.length=0;

						}

						if (ItemArr.length>0)

						{

							ItemArr.length=0;

						}

						document.getElementById("hdBillingData").value="";

						document.getElementById("dvChallanDetails").innerHTML="";

						document.getElementById("dvVat").style['display']="none";

						lbl.innerHTML="No Order Found...";	

					}

				}

			}

			else

			{

				if (DcArr.length>0)

				{

					DcArr.length=0;

				}

				if (ItemArr.length>0)

				{

					ItemArr.length=0;

				}

				document.getElementById("hdBillingData").value="";

				document.getElementById("dvChallanDetails").innerHTML="";

				document.getElementById("dvVat").style['display']="none";

				//lbl.innerHTML="No Order Found...";

			}

		}

		http.send(null);

		SelParty("");

		document.getElementById('dvPendingItem').style['display']="inline";

		

	}

	else

	{

		document.getElementById("hdBillingData").value="";

		document.getElementById("dvChallanDetails").innerHTML="";

		document.getElementById("dvVat").style['display']="none";

		document.getElementById('dvPendingItem').style['display']="none";

		if (DcArr.length>0)

		{

			DcArr.length=0;

		}

		if (ItemArr.length>0)

		{

			ItemArr.length=0;

		}

	}

}



function SelParty(ID)

{

	val=document.getElementById("cmbParty").value;

	

	

	Itemctrl=document.getElementById("cmbTo");

	Itemctrl.length=1;

	if (val!="")

	{

		i=document.getElementById("cmbParty").options[document.getElementById("cmbParty").selectedIndex].text;

		

		opt=val.split("~ArrayItem~");

		opts=opt[1].split("~ItemData~");
		document.getElementById('txtStateCode').value=opt[3];
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



function DispDCInfo()

{

	DCItem=document.getElementById("dvChallanDetails");

	DCItem.innerHTML="";

	var x;

	

	var Total=0;

	var Data="";

	var TotalItem=0;

	var TotalValue=0;

	var TotalItem=0;

	var i=0;

	for (x=0;x<DcArr.length ;x++ )

	{
       
		Data+="<table class=\"table table-bordered dt-responsive nowrap no-footer dtr-inline dataTable\">";

		Data+="<tr>";

		Data+="<td style=\"text-align:right;\" width=\"200\">Order No :</td>";

		Data+="<td width=\"200\">"+DcArr[x][0]+"</td><td width=\"200\">Date :"+DcArr[x][2]+"</td><td width=\"200\"></td>";

		Data+="</tr>";

		Data+="</table>";

		Data+="<table class=\"table table-bordered dt-responsive\">";

		Data+="<thead><tr>";	

		Data+="<th width=\"5%\">SNO</th>";

		Data+="<th width=\"20%\">Item Name</th>";

		Data+="<th width=\"15%\">Total Qty Rec</th>";

		Data+="<th width=\"10%\">Pcs Disp</th>";

		Data+="<th width=\"10%\">Total Billed</th>";

		Data+="<th width=\"15%\">Qty To Billed</th>";

		Data+="<th width=\"10%\">Rate</th>";

		Data+="<th width=\"10%\">Value</th></tr></thead><tbody>";

		

		var j=0;
        
		for (j=0;j<ItemArr.length;j++ )

		{	
           
			if (ItemArr[j][0]==DcArr[x][1])

			{

				i++;

				Data+="<tr>";

				Data+="<td>"+(i)+"</td>";

				Data+="<td>"+ItemArr[j][1]+"&nbsp;&nbsp;"+ItemArr[j][3];

				if (ItemArr[j][15]!='')

				{

					Data+="<br>Remarks :"+ItemArr[j][15];

				}

				

				Data+="</td>";

				if (parseInt(ItemArr[j][5]) > parseInt(ItemArr[j][6]))

				{

					Data+="<td style=\"text-align:right ;color:red; background-color:#08e16b ;\"> <b>"+ItemArr[j][5]+"</b></td>"; //  Total Qty Rec

				}

				else

				{

					Data+="<td style=\"text-align:right\"> "+ItemArr[j][5]+"</td>"; //  Total Qty Rec

				}

				if (parseInt(ItemArr[j][5]) > parseInt(ItemArr[j][6]))

				{

					Data+="<td  style=\"text-align:right ;color:red; background-color:#08e16b;\"> <b>"+ItemArr[j][6]+"</b></td>"; // Total Disp

				}

				else

				{

					Data+="<td  style=\"text-align:right\"> "+ItemArr[j][6]+"</td>"; // Total Disp

				}

				Data+="<td  style=\"text-align:right\"> "+ItemArr[j][8]+"</td>"; // Billed

				var OrderID=ItemArr[j][0];

				var Category=ItemArr[j][1];

				var ItemCode=ItemArr[j][2];

				var ItemName=ItemArr[j][3];

				txt="txt"+j;																																

				Data+="<td style=\"text-align:right\"> <input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+ItemArr[j][9]+"\" class=\"form-control\" onChange=\"changeQty(this.id,"+OrderID+", '"+Category+"', "+ItemCode+",'"+ItemName+"', "+j+")\"></td>"; // Remaining Qty To be Billed

				txt1="txtRate"+j;																																							//	ID 		, order ID	, Category 		, ItemCode, 	, ItemName																																																	

				

				Data+="<td  style=\"text-align:left\"> <input type=\"text\" name=\""+txt1+"\" id=\""+txt1+"\" value=\""+ItemArr[j][10]+"\" class=\"form-control\" onChange=\"changeRate(this.id ,"+OrderID+", '"+Category+"',  "+ItemCode+", '"+ItemName+"',"+j+")\">("+ItemArr[j][13]+")</td>"; // Rate

				Data+="<td  style=\"text-align:right\"> "+(parseFloat(ItemArr[j][11])).toFixed(2)+"</td></tr>"; // Value				

				

				TotalItem=TotalItem + parseInt(ItemArr[j][9]);

				

				if (ItemArr[j][1]=="Shaft Pinion" || ItemArr[j][1]=="Worm Gear")

				{

					if (ItemArr[j][13]=="PCS")

						Price=calculate (ItemArr[j][9], ItemArr[j][10], ItemArr[j][4], ItemArr[j][12], ItemArr[j][1], ItemArr[j][7], ItemArr[j][0], DcArr);	

					else

						Price=(ItemArr[j][9] * ItemArr[j][10] * ItemArr[j][4]);

				}

				else

				{

					if (ItemArr[j][13]=="PT")

					{

						Price=calculate (ItemArr[j][9], ItemArr[j][10], ItemArr[j][4], ItemArr[j][12], ItemArr[j][1], ItemArr[j][7], ItemArr[j][0], DcArr);

						

					}

					else

					{

						MinValue=DcArr[x][3];

						Price=(ItemArr[j][9] * ItemArr[j][10]);

						//if (ItemArr[j][1]=="Pinion" && ItemArr[j][7]=="PowerPress" && ItemArr[j][10]<MinValue)

						if (ItemArr[j][1]=="Pinion" && ItemArr[j][7]=="PowerPress" && Price<MinValue)

						{

							Price=(ItemArr[j][9] * MinValue);

						}

						

					} 

				}

				//alert (Price);

				TotalValue=TotalValue + parseFloat(Price);

			}

		}

		Data+="</tbody></table>";

	}

	

	Data+="<table class=\"table table-bordered dt-responsive\">";

	Data+="<tr>";		

	Data+="<td style=\"text-align:right\" >Total :</td>";

	Data+="<td  style=\"text-align:right\">"+TotalItem+"</td>";

	hd="hdGTWithoutTax";

	TotalValue=(TotalValue).toFixed(2);

	Data+="<td><input type=\"hidden\" name=\""+hd+"\" id=\""+hd+"\" value=\""+TotalValue+"\">&nbsp;</td>";

	

	Data+="<td  style=\"text-align:right\">"+TotalValue+"</td></tr>";

	Data+="</table>";

	

	TotalItem=0;

	TotalValue=0;



	DCItem.innerHTML=Data;

	var TotalValue;

	TotalValue=document.getElementById("hdGTWithoutTax").value;

	

	TotalValue=Math.round(TotalValue);

	document.getElementById("lblTotalAmt").innerHTML=TotalValue;

	document.getElementById("hdTotalWithTax").value=TotalValue;

	DCItem.style['height']='100%';

	

	Dataconcat();

	if (i>24)

	{

		alert ("More than 24 Items are selected . Please select To Date less than the selected Date...");

	}

}



function changeRate(Id,OrderID, Category,ItemCode, ItemName, Sno)

{

	var x;

	var price;

	val=parseFloat(document.getElementById(Id).value);

	if (isNaN(val))

	{

		alert ("Rate can not be blank...");

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else if (val==0)

	{

		alert ("Rate can not be Zero...");

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else

	{

		for (x=0;x<ItemArr.length ;x++ )

		{

			if (ItemArr[x][0]==OrderID && ItemArr[x][1]==Category && ItemArr[x][2]==ItemCode && x==Sno && ItemArr[x][3]==ItemName)

			{

				ItemArr[x][10]=val;

					

				

				if (ItemArr[x][1]=="Shaft Pinion" || ItemArr[x][1]=="Worm Gear")

				{

					if (ItemArr[x][13]=="PCS")

						Price=calculate (ItemArr[x][9], ItemArr[x][10], ItemArr[x][4], ItemArr[x][12], ItemArr[x][1], ItemArr[x][7], ItemArr[x][0], DcArr);	

					else

						Price=(ItemArr[x][9] * ItemArr[x][10] * ItemArr[x][4]);

				}

				else

				{

					

					if (ItemArr[x][13]=="PT")

					{

						Price=calculate (ItemArr[x][9], ItemArr[x][10], ItemArr[x][4], ItemArr[x][12], ItemArr[x][1], ItemArr[x][7], ItemArr[x][0],DcArr);

					}

					else

					{

						for (l=0;l<DcArr.length;i++)

						{

							if (DcArr[l][1]==ItemArr[x][0])

							MinValue=DcArr[l][3];	

							break;

						}

						var IPrice=0;

						//Price=(ItemArr[x][9] * ItemArr[x][10] * ItemArr[x][4]);

						Price=(ItemArr[x][9] * ItemArr[x][10]);

						//IPrice=(ItemArr[x][10] * ItemArr[x][4]);

						IPrice=(ItemArr[x][9] * ItemArr[x][10]);

						if (ItemArr[x][1]=="Pinion" && ItemArr[x][7]=="PowerPress" && IPrice<MinValue)

						{

							Price=ItemArr[x][9] * MinValue;

						}

					} 

				}

				

				ItemArr[x][11]=Price;

			}

		}

		

		Dataconcat();

		DispDCInfo();

		document.getElementById(Id).focus();

	}

}



function changeQty(Id,OrderID, Category,ItemCode, ItemName, Sno)

{

	var x;

	val=parseFloat(document.getElementById(Id).value);

	if (isNaN(val))

	{

		alert ("Qty can not be blank...");

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else if (val==0)

	{	

		alert ("Qty can not zero...");

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else if (parseInt(val)>(parseInt(ItemArr[Sno][6])- parseInt(ItemArr[Sno][8])))

	{

		alert ("Qty To Billed can not be Greater than Qty available for Billing ("+(parseInt(ItemArr[Sno][6])- parseInt(ItemArr[Sno][8]))+")...");

		document.getElementById(Id).value=ItemArr[Sno][9];

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else

	{

		for (x=0;x<ItemArr.length ;x++ )

		{

			if (ItemArr[x][0]==OrderID && ItemArr[x][1]==Category && ItemArr[x][2]==ItemCode && x==Sno && ItemArr[x][3]==ItemName)

			{

				ItemArr[x][9]=val;

				if (ItemArr[x][1]=="Shaft Pinion" || ItemArr[x][1]=="Worm Gear")

				{

					if (ItemArr[x][13]=="PCS")

						Price=calculate (ItemArr[x][9], ItemArr[x][10], ItemArr[x][4], ItemArr[x][12], ItemArr[x][1], ItemArr[x][7], ItemArr[x][0], DcArr);	

					else

						Price=(ItemArr[x][9] * ItemArr[x][10] * ItemArr[x][4]);

				}

				else

				{

					if (ItemArr[x][13]=="PT")

					{

						Price=calculate (ItemArr[x][9], ItemArr[x][10], ItemArr[x][4], ItemArr[x][12], ItemArr[x][1], ItemArr[x][7], ItemArr[x][0], DcArr);

					}

					else

					{

						for (l=0;l<DcArr.length;i++)

						{

							if (DcArr[l][1]==ItemArr[x][0])

							MinValue=DcArr[l][3];	

							break;

						}

						

						Price=(ItemArr[x][9] * ItemArr[x][10] );

						var IPrice=0;

						IPrice=(ItemArr[x][9] * ItemArr[x][10]);

						if (ItemArr[x][1]=="Pinion" && ItemArr[x][7]=="PowerPress" && IPrice<MinValue)

						{

							Price=(ItemArr[x][9] * MinValue);

						}

					} 

				}

				ItemArr[x][11]=Price;

			}

		}

		Dataconcat();

		DispDCInfo();

		do{document.getElementById(Id).focus();}while (document.getElementById(Id).focus());

	}

}





function Dataconcat()

{

	var Data="";

	var ItemData="";

	var ItemArray="";

	var Billed="";

	var ChallanData="";

	document.getElementById("hdBillingData").value="";

	 

	if (DcArr.length>0)

	{

		for (x=0;x<DcArr.length ;x++ )

		{

			ItemData=DcArr[x][0]+"~Caption~"+DcArr[x][1]+"~Caption~"+DcArr[x][2]+"~Caption~"+DcArr[x][3]+"~Caption~"+DcArr[x][4]+"~Heading~";	

			for (y=0;y<ItemArr.length ;y++ )

			{

				if (ItemArr[y][0]==DcArr[x][1])

				{

					if (Data=="")

					{		 // Order ID				// Category					//ItemCode					//ItemName					//Teeth						// TotalQtyRec				// TotalDisp				// PartType					// BilledQty				// Remaining Qty			//Rate						// Price					// ItemType					// PT/PCS						// Collar					// Item Remarks																			

						Data=ItemArr[y][0]+"~ArrayItem~"+ItemArr[y][1]+"~ArrayItem~"+ItemArr[y][2]+"~ArrayItem~"+ItemArr[y][3]+"~ArrayItem~"+ItemArr[y][4]+"~ArrayItem~"+ItemArr[y][5]+"~ArrayItem~"+ItemArr[y][6]+"~ArrayItem~"+ItemArr[y][7]+"~ArrayItem~"+ItemArr[y][8]+"~ArrayItem~"+ItemArr[y][9]+"~ArrayItem~"+ItemArr[y][10]+"~ArrayItem~"+ItemArr[y][11]+"~ArrayItem~"+ItemArr[y][12]+"~ArrayItem~"+ItemArr[y][13]+"~ArrayItem~"+ItemArr[y][14]+"~ArrayItem~"+ItemArr[y][15];

					}

					else

					{

						Data=Data + "~Array~"+ItemArr[y][0]+"~ArrayItem~"+ItemArr[y][1]+"~ArrayItem~"+ItemArr[y][2]+"~ArrayItem~"+ItemArr[y][3]+"~ArrayItem~"+ItemArr[y][4]+"~ArrayItem~"+ItemArr[y][5]+"~ArrayItem~"+ItemArr[y][6]+"~ArrayItem~"+ItemArr[y][7]+"~ArrayItem~"+ItemArr[y][8]+"~ArrayItem~"+ItemArr[y][9]+"~ArrayItem~"+ItemArr[y][10]+"~ArrayItem~"+ItemArr[y][11]+"~ArrayItem~"+ItemArr[y][12]+"~ArrayItem~"+ItemArr[y][13]+"~ArrayItem~"+ItemArr[y][14]+"~ArrayItem~"+ItemArr[y][15];

					}

				}

			}

			if (ItemArray=="")

			{

				ItemArray = ItemData + Data;

			}

			else

			{

				ItemArray= ItemArray +"~ItemData~"+ItemData + Data;

			}

			Data="";

		}

	}

	document.getElementById("hdBillingData").value=ItemArray;

	CalculateTax();

}



function CheckBlank()

{
    $("#btnSave").attr("disabled",true);
	preloadfadeIn();
	var validata=true;
	var errmsg="";
	var focusid="";
    if (document.getElementById('cmbParty').value=="")
    {
          validata=false;
		  errmsg="Please Select Party...";
		  focusid="cmbParty";
		  
		

	}

	else if (document.getElementById("txtBillingDate").value=="")

	{
          validata=false;
		  errmsg="Please Select Date...";
		  focusid="txtBillingDate";
		  

	}

	else if (document.getElementById("cmbFrom").value=="")

	{
          validata=false;
		  errmsg="Please Select Company Name...";
		  focusid="cmbFrom";
		  

	}

	else if (document.getElementById("cmbTo").value=="")

	{
          validata=false;
		  errmsg="Please Select Party Name...";
		  focusid="cmbTo";
		  
		

	}

	/*else if (document.getElementById("txtVat").value=="" || document.getElementById("txtVat").value=="0")

	{

		lbl.innerHTML="Please enter the VAT tax %..";

		document.getElementById('txtVat').focus();

		event.returnValue=false;

	}*/

	else if (document.getElementById("txtItemName").value!="" && (document.getElementById("txtItemQty").value=="" ||document.getElementById("txtItemRate").value==""))

	{

		//lbl.innerHTML="Please enter the Item Detail ..";
          validata=false;
		  errmsg="Please enter the Item Detail ..";
		  
		

		if (document.getElementById("txtItemQty").value=="")

		{
            focusid="txtItemQty";
			//document.getElementById('txtItemQty').focus();

		}

		else if (document.getElementById("txtItemRate").value=="")

		{

			//document.getElementById('txtItemRate').focus();
             focusid="txtItemRate";
		}

		

	}									

	else if (parseInt(document.getElementById("txtItemQty").value)>0 && (document.getElementById("txtItemName").value=="" ||document.getElementById("txtItemRate").value==""))

	{
        validata=false;
		errmsg="Please enter the Item Detail ..";
		//lbl.innerHTML="Please enter the Item Detail ..";

		

		if (document.getElementById("txtItemName").value=="")

		{
            focusid="txtItemName";
			//document.getElementById('txtItemName').focus();

		}

		else if (document.getElementById("txtItemRate").value=="")

		{
            focusid="txtItemRate";
			//document.getElementById('txtItemRate').focus();

		}

		// event.returnValue=false;

	}

	else if (parseFloat(document.getElementById("txtItemRate").value)>0 && (document.getElementById("txtItemName").value=="" ||document.getElementById("txtItemQty").value==""))

	{
        validata=false;
		errmsg="Please enter the Item Detail ..";
		//lbl.innerHTML="Please enter the Item Detail ..";

		

		if (document.getElementById("txtItemName").value=="")

		{
            focusid="txtItemName";
			//document.getElementById('txtItemName').focus();

		}

		else if (document.getElementById("txtItemQty").value=="")

		{
            focusid="txtItemQty";
			//document.getElementById('txtItemQty').focus();

		}

		

	}
    if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		$("#btnSave").attr("disabled",false);
	    preloadfadeOut();
	}
	else
	{
		       preloadfadeOut();
		       $("#cmbParty").attr("disabled",false);
		       var formData = new FormData();
        		var other_data = $('#frmBilling1').serializeArray();
				$.each(other_data,function(key,input){
					
        			formData.append(input.name,input.value);
        		});
				$("#cmbParty").attr("disabled",true);
				$.ajax({
					    url:'operations/save_billing.php',
					    type:'POST',
					    data:formData,
						async: true,
						cache: false,
						contentType: false,
						processData: false,
					   success:function(response)
					   {
						   var responseText=JSON.parse(response);
						   if(responseText.error)
						   {
							   toastr.error(responseText.error_msg);
							   $("#btnSave").attr("disabled",false);
	                           preloadfadeOut();
						   }
						   else
						   {
							   toastr.success(responseText.error_msg);
							   $("#btnSave").attr("disabled",false);
	                           $.ajax({
								   url:'operations/billing_edit.php',
								   type:'POST',
								   data:{"auth_info":$("#auth_info").val(),"txtBillingUser":$("#txtBillingUser").val()},
								   success:function(response)
								   {
									   
									   $('.page-content').html(response);
									   preloadfadeOut();
										   
								   },
								   error:function(response)
								   {
									   
									   if(response.status=='404')
									   {
										   toastr.error("Page not found");
										   preloadfadeOut();
										   
									   }
									   else if(response.status=='500')
									   {
										   toastr.error("Internal server error");
										   preloadfadeOut();
										   
									   }
									   else
									   {
										   toastr.error("Communication error");
										   preloadfadeOut();
										   
									   }
								   }
	})
						   }
					   },error(response)
					   {
						   if(response.status=='404')
						   {
							   toastr.error("Page Not Found");
						   }
						   else if(response.status=='500')
						   {
							   toastr.error("Internal server error");
						   }
						   else
						   {
							   toastr.error("communication error");
						   }
					   }
				})
	}
	

}



function PrintPdf(BillingID)

{

	page="operations/billing_showpdf.php?BillingID="+encodeURI(BillingID);

	OpenWin = window.open(page, "Report", "toolbar=no,menubar=no,location=no,scrollbars=no,resizable=yes,width=550,height=550"); 

}





function ShowPO(BillingID, PartyID)

{

	document.location.href="operations/billing.php?BillingID="+BillingID+"&PartyID="+PartyID;

}



function CBDelete(BillingID)

{

	lbl=document.getElementById('lblErrMsg');

	lbl.innerHTML="";

	var x;

	x=confirm("Are you Sure! You want to DELETE this record?");

	if (x==true)

	{

		url="operations/billing_delete.php?BillingID="+BillingID;

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



function CalculateTax()

{

	var MiscItemQty;

	var MiscItemRate;

	var MiscItemPrice;

	var MiscTaxRate;

	var MiscTaxAmt;

	var VatFactor;

	var VatRate;

	var VatAmt;
	
	var CGSTAmt;
	var SGSTAmt;
	var IGSTAmt;

	var SurchargeRate;

	var SurchargeAmt;

	var AmtWithoutTax;

	var AmtWithTax;

	var FSurchargeAmt=0;

	var DSurchargeAmt=0;

	VatFactor=parseFloat(document.getElementById('hdVatFactor').value);

	AmtWithoutTax=parseFloat(document.getElementById("hdGTWithoutTax").value);

	

	lbl=document.getElementById('lblErrMsg');

	lbl.innerHTML="";

	// Misc Item 	

	if (document.getElementById("txtItemName").value!='' && document.getElementById("txtItemQty").value!='' && document.getElementById("txtItemRate").value!='' && parseInt(document.getElementById("txtItemQty").value)>0 && parseFloat(document.getElementById("txtItemRate").value)>0)

	{

		MiscItemQty=parseInt(document.getElementById("txtItemQty").value);

		MiscItemRate=parseFloat(document.getElementById("txtItemRate").value);

		MiscItemPrice = MiscItemQty * MiscItemRate;

		document.getElementById("lblItemAmt").innerHTML=MiscItemPrice;

	}

	else if ((document.getElementById("txtItemName").value=='' || document.getElementById("txtItemQty").value=='' || document.getElementById("txtItemRate").value=='' || parseInt(document.getElementById("txtItemQty").value)==0 || parseFloat(document.getElementById("txtItemRate").value)==0))

	{

		if (document.getElementById("txtItemName").value!='' && document.getElementById("txtItemQty").value!='' && parseInt(document.getElementById("txtItemQty").value)>0 && (parseFloat(document.getElementById("txtItemRate").value)==0 || document.getElementById("txtItemRate").value==''))

		{

			document.getElementById("lblItemAmt").innerHTML="0.00";

			lbl.innerHTML="Please enter the Rate...";	

		}

		else if (document.getElementById("txtItemName").value!='' && document.getElementById("txtItemRate").value!='' && parseInt(document.getElementById("txtItemRate").value)>0 && (parseFloat(document.getElementById("txtItemQty").value)==0 || document.getElementById("txtItemQty").value==''))

		{

			document.getElementById("lblItemAmt").innerHTML="0.00";

			lbl.innerHTML="Please enter the Qty...";	

		}

		else if (document.getElementById("txtItemName").value=='' && document.getElementById("txtItemQty").value!='' && document.getElementById("txtItemRate").value!='' && parseInt(document.getElementById("txtItemQty").value)>0 && parseFloat(document.getElementById("txtItemRate").value)>0)

		{

			document.getElementById("lblItemAmt").innerHTML="0.00";

			lbl.innerHTML="Please enter the Mics Item Name...";

		}

		else

		{

			document.getElementById("lblItemAmt").innerHTML="0.00";

			lbl.innerHTML="Please enter the Mics Information...";

		}

	}

	

	// Misc Tax

	if (document.getElementById("txtMiscName").value!='' && document.getElementById("txtMiscTax").value!='' && parseFloat(document.getElementById("txtMiscTax").value)>0 )

	{

		AmtWithoutTax=parseFloat(document.getElementById("hdGTWithoutTax").value);

		MiscTaxRate=parseFloat(document.getElementById("txtMiscTax").value);

		AmtWithoutTax= Math.round(AmtWithoutTax);

		MiscItemPrice=parseFloat(document.getElementById("lblItemAmt").innerHTML);

		AmtWithoutTax= AmtWithoutTax + MiscItemPrice;

		MiscTaxAmt = (AmtWithoutTax * MiscTaxRate)/100;

		MiscTaxAmt=Math.ceil(MiscTaxAmt);

		document.getElementById("lblMiscAmt").innerHTML=MiscTaxAmt;

	}

	else if (document.getElementById("txtMiscName").value=='' || document.getElementById("txtMiscTax").value=='' || parseFloat(document.getElementById("txtMiscTax").value)==0)

	{

		if ((document.getElementById("txtMiscName").value=='' && document.getElementById("txtMiscTax").value!='' && parseFloat(document.getElementById("txtMiscTax").value)>0))

		{

			document.getElementById("lblMiscAmt").innerHTML="0.00";	

			lbl.innerHTML="Please enter the Mics Tax Name...";

		}

		else if (document.getElementById("txtMiscName").value!='' && (document.getElementById("txtMiscTax").value=='' || parseFloat(document.getElementById("txtMiscTax").value)==0))

		{

			document.getElementById("lblMiscAmt").innerHTML="0.00";	

			lbl.innerHTML="Please enter the Mics Tax %...";

		} 

		else

		{

			document.getElementById("lblMiscAmt").innerHTML="0.00";	

			lbl.innerHTML="Please enter the Mics Tax Information...";

		}

	}

	

	// Vat

	if (document.getElementById("txtVat").value!='' && parseFloat(document.getElementById("txtVat").value)>0)

	{

		AmtWithoutTax=parseFloat(document.getElementById("hdGTWithoutTax").value);
		if(isNaN(AmtWithoutTax))
		{
			AmtWithoutTax=0;
		}
		VatRate=parseFloat(document.getElementById("txtVat").value);
		if(isNaN(VatRate))
		{
			VatRate=0;
		}
		AmtWithoutTax= Math.round(AmtWithoutTax);	
		if(isNaN(AmtWithoutTax))
		{
			AmtWithoutTax=0;
		}
		MiscItemPrice=parseFloat(document.getElementById("lblItemAmt").innerHTML);
		if(isNaN(MiscItemPrice))
		{
			MiscItemPrice=0;
		}
	

		AmtWithoutTax= AmtWithoutTax + MiscItemPrice;

		VatAmt= (AmtWithoutTax*VatFactor/100 * VatRate)/100;
		
		VatAmt= Math.ceil(VatAmt);

		document.getElementById("lblVatAmt").innerHTML=VatAmt;

	

	}

	else if (document.getElementById("txtVat").value=='' || parseFloat(document.getElementById("txtVat").value)==0)

	{

		document.getElementById("lblVatAmt").innerHTML="0.00";

		document.getElementById("lblSurchargeAmt").innerHTML="0.00";

		document.getElementById("txtSurcharge").value="0";	

		lbl.innerHTML="Please enter VAT %";

	}
	
	// CGST

	if (document.getElementById("txtCGST").value!='' && parseFloat(document.getElementById("txtCGST").value)>0)

	{

		AmtWithoutTax=parseFloat(document.getElementById("hdGTWithoutTax").value);
		if(isNaN(AmtWithoutTax))
		{
			AmtWithoutTax=0;
		}
		CGSTRate=parseFloat(document.getElementById("txtCGST").value);
		if(isNaN(CGSTRate))
		{
			CGSTRate=0;
		}
		AmtWithoutTax= Math.round(AmtWithoutTax);	
		if(isNaN(AmtWithoutTax))
		{
			AmtWithoutTax=0;
		}
		MiscItemPrice=parseFloat(document.getElementById("lblItemAmt").innerHTML);
		if(isNaN(MiscItemPrice))
		{
			MiscItemPrice=0;
		}
	

		AmtWithoutTax= AmtWithoutTax + MiscItemPrice;

		CGSTAmt= (AmtWithoutTax * CGSTRate)/100;
		
		CGSTAmt= Math.ceil(CGSTAmt);

		document.getElementById("lblCGSTAmt").innerHTML=CGSTAmt;

	

	}

	else if (document.getElementById("txtCGST").value=='' || parseFloat(document.getElementById("txtCGST").value)==0)

	{

		document.getElementById("lblCGSTAmt").innerHTML="0.00";

	}

	
	// SGST

	if (document.getElementById("txtSGST").value!='' && parseFloat(document.getElementById("txtSGST").value)>0)

	{

		AmtWithoutTax=parseFloat(document.getElementById("hdGTWithoutTax").value);
		if(isNaN(AmtWithoutTax))
		{
			AmtWithoutTax=0;
		}
		SGSTRate=parseFloat(document.getElementById("txtSGST").value);
		if(isNaN(SGSTRate))
		{
			SGSTRate=0;
		}
		AmtWithoutTax= Math.round(AmtWithoutTax);	
		if(isNaN(AmtWithoutTax))
		{
			AmtWithoutTax=0;
		}
		MiscItemPrice=parseFloat(document.getElementById("lblItemAmt").innerHTML);
		if(isNaN(MiscItemPrice))
		{
			MiscItemPrice=0;
		}
	

		AmtWithoutTax= AmtWithoutTax + MiscItemPrice;

		SGSTAmt= (AmtWithoutTax * SGSTRate)/100;
		
		SGSTAmt= Math.ceil(SGSTAmt);

		document.getElementById("lblSGSTAmt").innerHTML=SGSTAmt;

	

	}

	else if (document.getElementById("txtSGST").value=='' || parseFloat(document.getElementById("txtSGST").value)==0)

	{

		document.getElementById("lblSGSTAmt").innerHTML="0.00";

	}

	
	// CGST

	if (document.getElementById("txtIGST").value!='' && parseFloat(document.getElementById("txtIGST").value)>0)

	{

		AmtWithoutTax=parseFloat(document.getElementById("hdGTWithoutTax").value);
		if(isNaN(AmtWithoutTax))
		{
			AmtWithoutTax=0;
		}
		IGSTRate=parseFloat(document.getElementById("txtIGST").value);
		if(isNaN(IGSTRate))
		{
			IGSTRate=0;
		}
		AmtWithoutTax= Math.round(AmtWithoutTax);	
		if(isNaN(AmtWithoutTax))
		{
			AmtWithoutTax=0;
		}
		MiscItemPrice=parseFloat(document.getElementById("lblItemAmt").innerHTML);
		if(isNaN(MiscItemPrice))
		{
			MiscItemPrice=0;
		}
	

		AmtWithoutTax= AmtWithoutTax + MiscItemPrice;

		IGSTAmt= (AmtWithoutTax * IGSTRate)/100;
		
		IGSTAmt= Math.ceil(IGSTAmt);

		document.getElementById("lblIGSTAmt").innerHTML=IGSTAmt;

	

	}

	else if (document.getElementById("txtIGST").value=='' || parseFloat(document.getElementById("txtIGST").value)==0)

	{

		document.getElementById("lblIGSTAmt").innerHTML="0.00";

	}

	

	// Surcharge 

	if (document.getElementById("txtSurcharge").value!='' && parseFloat(document.getElementById("txtSurcharge").value)>0)

	{

		

		if (document.getElementById("txtVat").value!='' && parseFloat(document.getElementById("txtVat").value)>0)

		{	

			VatAmt=parseFloat(document.getElementById("lblVatAmt").innerHTML);	

			SurchargeRate=parseFloat(document.getElementById("txtSurcharge").value);

			SurchargeAmt= (VatAmt * SurchargeRate)/100;

			FSurchargeAmt=Math.floor(SurchargeAmt);

			DSurchargeAmt=SurchargeAmt - FSurchargeAmt;

			if (DSurchargeAmt >0.24)

				SurchargeAmt=Math.ceil(SurchargeAmt);

			else

				SurchargeAmt=Math.floor(SurchargeAmt);

			document.getElementById("lblSurchargeAmt").innerHTML=SurchargeAmt;

		}

		else

		{

			alert ("Please enter VAT % first...");

		}

	}

	else if (document.getElementById("txtSurcharge").value=='' || parseFloat(document.getElementById("txtSurcharge").value)==0)

	{

		document.getElementById("lblSurchargeAmt").innerHTML="0.00";

		lbl.innerHTML="Please enter the Surcharge Rate";

	}
	

	CalculateTotal();

}



function CalculateTotal()

{

	var TotalAmt;

	var AmtWithoutTax;

	AmtWithoutTax=parseFloat(document.getElementById("hdGTWithoutTax").value);
	if(isNaN(AmtWithoutTax))
	{
		AmtWithoutTax=0
	}
	MiscItemPrice=parseFloat(document.getElementById("lblItemAmt").innerHTML);
	if(isNaN(MiscItemPrice))
	{
		MiscItemPrice=0
	}
	MiscTaxAmt=parseFloat(document.getElementById("lblMiscAmt").innerHTML);
	if(isNaN(MiscTaxAmt))
	{
		MiscTaxAmt=0
	}
	VatAmt=parseFloat(document.getElementById("lblVatAmt").innerHTML);
	if(isNaN(VatAmt))
	{
		VatAmt=0
	}
	CGSTAmt=parseFloat(document.getElementById("lblCGSTAmt").innerHTML);
	if(isNaN(CGSTAmt))
	{
		CGSTAmt=0
	}
	SGSTAmt=parseFloat(document.getElementById("lblSGSTAmt").innerHTML);
	if(isNaN(SGSTAmt))
	{
		SGSTAmt=0
	}
	IGSTAmt=parseFloat(document.getElementById("lblIGSTAmt").innerHTML);
	if(isNaN(IGSTAmt))
	{
		IGSTAmt=0
	}
	SurchargeAmt=parseFloat(document.getElementById("lblSurchargeAmt").innerHTML);
	if(isNaN(SurchargeAmt))
	{
		SurchargeAmt=0
	}
	
	TotalAmt= MiscItemPrice + MiscTaxAmt + VatAmt + SurchargeAmt + AmtWithoutTax + CGSTAmt + SGSTAmt + IGSTAmt;

	TotalAmt= Math.round(TotalAmt);

	document.getElementById("hdTotalWithTax").value=TotalAmt;

	document.getElementById("lblTotalAmt").innerHTML=TotalAmt;

}





function OpenPDF(user, pass, db)

{

	val=document.getElementById("cmbParty").value;

	if (val!="")

	{

		date=document.getElementById("txtToDate").value;

		

		sel=val.split('~ArrayItem~');

		page="operations/billing_pendingpdf.php?PartyID="+encodeURI(sel[0])+"&Date="+date+ "&user="+user+"&pass="+pass+"&db="+db;

		

		OpenWin = window.open(page, "Report", "toolbar=no,menubar=no,location=no,scrollbars=no,resizable=yes,width=550,height=550"); 

	}

}

		

		// For Billing Without Delivery Challan

		// To show diff Items on selection of Item Type combobox

function CallDiv()

{

	val=document.getElementById("cmbItem").value;	

	if (val=="Gear")

	{

		document.getElementById('dvgear').style['display']="inline";

		document.getElementById('dvpinion').style['display']="none";

		document.getElementById('dvshaftpinion').style['display']="none";

		document.getElementById('dvbevelgear').style['display']="none";

		document.getElementById('dvbevelpinion').style['display']="none";

		document.getElementById('dvchainwheel').style['display']="none";

		document.getElementById('dvwormgear').style['display']="none";

	}

	else if (val=="Pinion")

	{

		document.getElementById('dvgear').style['display']="none";

		document.getElementById('dvpinion').style['display']="inline";

		document.getElementById('dvshaftpinion').style['display']="none";

		document.getElementById('dvbevelgear').style['display']="none";

		document.getElementById('dvbevelpinion').style['display']="none";

		document.getElementById('dvchainwheel').style['display']="none";

		document.getElementById('dvwormgear').style['display']="none";

	}

	else if (val=="Shaft Pinion")

	{

		document.getElementById('dvgear').style['display']="none";

		document.getElementById('dvpinion').style['display']="none";

		document.getElementById('dvshaftpinion').style['display']="inline";

		document.getElementById('dvbevelgear').style['display']="none";

		document.getElementById('dvbevelpinion').style['display']="none";

		document.getElementById('dvchainwheel').style['display']="none";

		document.getElementById('dvwormgear').style['display']="none";

	}

	else if (val=="Bevel Gear")

	{

		document.getElementById('dvgear').style['display']="none";

		document.getElementById('dvpinion').style['display']="none";

		document.getElementById('dvshaftpinion').style['display']="none";

		document.getElementById('dvbevelgear').style['display']="inline";

		document.getElementById('dvbevelpinion').style['display']="none";

		document.getElementById('dvchainwheel').style['display']="none";

		document.getElementById('dvwormgear').style['display']="none";

	}

	else if (val=="Bevel Pinion")

	{

		document.getElementById('dvgear').style['display']="none";

		document.getElementById('dvpinion').style['display']="none";

		document.getElementById('dvshaftpinion').style['display']="none";

		document.getElementById('dvbevelgear').style['display']="none";

		document.getElementById('dvbevelpinion').style['display']="inline";

		document.getElementById('dvchainwheel').style['display']="none";

		document.getElementById('dvwormgear').style['display']="none";

	}

	else if (val=="Chain Wheel")

	{

		document.getElementById('dvgear').style['display']="none";

		document.getElementById('dvpinion').style['display']="none";

		document.getElementById('dvshaftpinion').style['display']="none";

		document.getElementById('dvbevelgear').style['display']="none";

		document.getElementById('dvbevelpinion').style['display']="none";

		document.getElementById('dvchainwheel').style['display']="inline";

		document.getElementById('dvwormgear').style['display']="none";

	}

	else if (val=="Worm Gear")

	{

		document.getElementById('dvgear').style['display']="none";

		document.getElementById('dvpinion').style['display']="none";

		document.getElementById('dvshaftpinion').style['display']="none";

		document.getElementById('dvbevelgear').style['display']="none";

		document.getElementById('dvbevelpinion').style['display']="none";

		document.getElementById('dvchainwheel').style['display']="none";

		document.getElementById('dvwormgear').style['display']="inline";

	}

	else

	{

		document.getElementById('dvgear').style['display']="none";

		document.getElementById('dvpinion').style['display']="none";

		document.getElementById('dvshaftpinion').style['display']="none";

		document.getElementById('dvbevelgear').style['display']="none";

		document.getElementById('dvbevelpinion').style['display']="none";

		document.getElementById('dvchainwheel').style['display']="none";

		document.getElementById('dvwormgear').style['display']="none";

	}

	document.getElementById("dvVat").style['display']="inline";

}



function selGear(Id)

{

	if (document.getElementById(Id).value!="")

	{

		val=(document.getElementById(Id).value).split('~ArrayItem~');

		document.getElementById("txtGearTeeth").value=val[1];

		document.getElementById("cmbGearType").value=val[2];

		document.getElementById("txtGearDia").value=val[3];

		document.getElementById("cmbGearDiaType").value=val[4];

		document.getElementById("txtGearFace").value=val[5];

		document.getElementById("cmbGearFaceType").value=val[6];

		document.getElementById("txtGearProcessing").value=val[7];

		document.getElementById("cmbGearProcessingType").value=val[8];

		document.getElementById("dvVat").style['display']="inline";	

	}

}



function selPinion(Id)

{

	if (document.getElementById(Id).value!="")

	{

		val=(document.getElementById(Id).value).split('~ArrayItem~');

		document.getElementById("txtPinionTeeth").value=val[1];

		document.getElementById("cmbPinionType").value=val[2];

		document.getElementById("txtPinionDia").value=val[3];

		document.getElementById("cmbPinionDiaType").value=val[4];

		document.getElementById("txtPinionFace").value=val[5];

		document.getElementById("cmbPinionFaceType").value=val[6];

		document.getElementById("txtPinionProcessing").value=val[7];

		document.getElementById("cmbPinionProcessingType").value=val[8];

	}

}



function selBevelGear(Id)

{

	if (document.getElementById(Id).value!="")

	{

		val=(document.getElementById(Id).value).split('~ArrayItem~');

		document.getElementById("txtBevelGearTeeth").value=val[1];

		document.getElementById("txtBevelGearDia").value=val[2];

		document.getElementById("cmbBevelGearDiaType").value=val[3];

		document.getElementById("txtBevelGearProcessing").value=val[4];

		document.getElementById("cmbBevelGearProcessingType").value=val[5];

	}

}



function selBevelPinion(Id)

{

	if (document.getElementById(Id).value!="")

	{

		val=(document.getElementById(Id).value).split('~ArrayItem~');

		document.getElementById("txtBevelPinionTeeth").value=val[1];

		document.getElementById("txtBevelPinionDia").value=val[2];

		document.getElementById("cmbBevelPinionDiaType").value=val[3];

		document.getElementById("txtBevelPinionProcessing").value=val[4];

		document.getElementById("cmbBevelPinionProcessingType").value=val[5];

	}

}



function selChainGear(Id)

{

	if (document.getElementById(Id).value!="")

	{

		val=(document.getElementById(Id).value).split('~ArrayItem~');

		document.getElementById("txtChainWheelTeeth").value=val[1];

		document.getElementById("cmbChainWheelType").value=val[2];

		document.getElementById("txtChainWheelDia").value=val[3];

		document.getElementById("cmbChainWheelDia").value=val[4];

		document.getElementById("txtChainWheelPitch").value=val[5];

		document.getElementById("cmbChainWheelPitchType").value=val[6];

	}

}



function selShaftPinion(Id)

{

	if (document.getElementById(Id).value!="")

	{

		val=(document.getElementById(Id).value).split('~ArrayItem~');

		document.getElementById('txtShaftPinionTeeth').value=val[1];

		document.getElementById("txtShaftPinionDia").value=val[2];

		document.getElementById("cmbShaftPinionDiaType").value=val[3];

		document.getElementById('cmbShaftPinionType').value=val[4];

		document.getElementById('txtShaftPinionFace').value=val[5];

		document.getElementById('cmbShaftPinionFaceType').value=val[6];

		document.getElementById('txtShaftPinionProcessing').value=val[7];

		document.getElementById('cmbShaftPinionProcessingType').value=val[8];

	}

}

function selWormGear(Id)

{

	if (document.getElementById(Id).value!="")

	{

		val=(document.getElementById(Id).value).split('~ArrayItem~');

		document.getElementById("txtWormGearTeeth").value=val[1];

		document.getElementById("txtWormGearDia").value=val[2];

		document.getElementById("cmbWormGearDiaType").value=val[3];

		document.getElementById("txtWormGearProcessing").value=val[4];

		document.getElementById("cmbWormGearProcessingType").value=val[5];

	}

}





function CheckBlankOrder(Id)

{
document.getElementById('cmbParty').disabled=true;
	var add=false;
    
	lbl=document.getElementById('lblErrMsg');

	lbl.innerHTML="";

	PartyType=(document.getElementById("cmbParty").value).split("~ArrayItem~");

	

	PartyID=PartyType[0];

	PartyPartType=PartyType[2];
	
	if(document.getElementById('txtStateCode').value=='03'){
		document.getElementById('SGSTdiv').style.display='table-row';
		document.getElementById('CGSTdiv').style.display='table-row';
		document.getElementById('IGSTdiv').style.display='none';
	}else{
		document.getElementById('SGSTdiv').style.display='none';
		document.getElementById('CGSTdiv').style.display='none';
		document.getElementById('IGSTdiv').style.display='table-row';
	}


	if (Id=="btnAddGear")

	{
        var validata=true;
		var focusid="";
		var errmsg="";
		if (document.getElementById("txtGearTeeth").value=="" || (parseInt(document.getElementById('txtGearTeeth').value)==0))

		{
            validata=false;
			errmsg="Please enter Teeth...";
			focusid="txtGearTeeth";
			

		}

		else if (document.getElementById("txtGearDia").value=="" || (parseFloat(document.getElementById("txtGearDia").value)==0))

		{
                validata=false;
				errmsg="Please enter Dia...";
				focusid="txtGearDia";
			

		}

		else if (document.getElementById("txtGearFace").value=="" || (parseFloat(document.getElementById("txtGearFace").value)==0))

		{
                validata=false;
				errmsg="Please enter Face...";
				focusid="txtGearFace";
			

		}

		else if (document.getElementById("txtGearProcessing").value=="")

		{
            validata=false;
			errmsg="Please enter Processing Type...";
			focusid="txtGearProcessing";
			

		}

		else if (document.getElementById('txtGearPcs').value=="" || (parseInt(document.getElementById('txtGearPcs').value)==0))

		{
            validata=false;
			errmsg="Please enter Pcs...";
			focusid="txtGearPcs";
			

		}
        if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
		}
		else

		{

			add=true;

			teeth=document.getElementById("txtGearTeeth").value;

			type=document.getElementById("cmbGearType").value;

			dia=document.getElementById("txtGearDia").value;

			diatype=document.getElementById("cmbGearDiaType").value;

			face=document.getElementById("txtGearFace").value;

			facetype=document.getElementById("cmbGearFaceType").value;

		    dmvalue=document.getElementById("txtGearProcessing").value;

			dmvaluetype=document.getElementById("cmbGearProcessingType").value;

			pcs=document.getElementById("cmbGearCal").value;

			PartyPartType=document.getElementById("cmbGearPartyType").value;

			if (PartyPartType=="Expeller")

				url="operations/party_order_gear_rate.php?Teeth="+teeth+"&Type="+type+"&Dia="+dia+"&DiaType="+diatype+"&Face="+face+"&FaceType="+facetype+"&DMValue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID;

			else

				url="operations/party_order_ppgear_rate.php?Teeth="+teeth+"&Type="+type+"&Dia="+dia+"&DiaType="+diatype+"&Face="+face+"&FaceType="+facetype+"&DMValue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID;

		}

	}

	else if (Id=="btnAddPinion")

	{
        var validata=true;
		var errmsg="";
		var focusid="";
		if (document.getElementById("txtPinionTeeth").value=="" || (parseInt(document.getElementById('txtPinionTeeth').value)==0))
        {
            validata=false;
			errmsg="Please enter Teeth...";
			focusid="txtPinionTeeth";	
        }

		else if (document.getElementById("txtPinionDia").value=="" || (parseFloat(document.getElementById("txtPinionDia").value)==0))
        {
            validata=false;
			errmsg="Please enter Dia...";
			focusid="txtPinionDia";	
			
		}

		else if (document.getElementById("txtPinionFace").value=="" || (parseFloat(document.getElementById("txtPinionFace").value)==0))
        {
            validata=false;
			errmsg="Please enter Face...";
			focusid="txtPinionFace";

		}

		else if (document.getElementById("txtPinionProcessing").value=="")
        {
            validata=false;
			errmsg="Please enter Processing Type...";
			focusid="txtPinionProcessing";
			

		}

		else if (document.getElementById('txtPinionPcs').value=="" || (parseInt(document.getElementById('txtPinionPcs').value)==0))
        {
            validata=false;
			errmsg="Please enter Pcs...";
			focusid="txtPinionPcs";
			

		}
        if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
		}
		else

		{

			add=true;

			teeth=document.getElementById("txtPinionTeeth").value;

			type=document.getElementById("cmbPinionType").value;

			dia=document.getElementById("txtPinionDia").value;

			diatype=document.getElementById("cmbPinionDiaType").value;

			face=document.getElementById("txtPinionFace").value;

			facetype=document.getElementById("cmbPinionFaceType").value;

		    dmvalue=document.getElementById("txtPinionProcessing").value;

			dmvaluetype=document.getElementById("cmbPinionProcessingType").value;

			PartyPartType=document.getElementById("cmbPinionPartyType").value;

			pcs=document.getElementById("cmbPinionCal").value;

			if (PartyPartType=="Expeller")

				url="operations/party_order_gear_rate.php?Teeth="+teeth+"&Type="+type+"&Dia="+dia+"&DiaType="+diatype+"&Face="+face+"&FaceType="+facetype+"&DMValue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID;

			else

				url="operations/party_order_ppgear_rate.php?Teeth="+teeth+"&Type="+type+"&Dia="+dia+"&DiaType="+diatype+"&Face="+face+"&FaceType="+facetype+"&DMValue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID;

		}

	}

	else if (Id=="btnAddShaftPinion")

	{
         var validata=true;
		 var focusid="";
		 var errmsg="";
		if (document.getElementById("txtShaftPinionTeeth").value=="" || (parseInt(document.getElementById('txtShaftPinionTeeth').value)==0))
        {
            validata=false;
			errmsg="Please enter Teeth...";
			focusid="txtShaftPinionTeeth";
			

		}

		else if (document.getElementById("txtShaftPinionDia").value=="" || (parseFloat(document.getElementById("txtShaftPinionDia").value)==0))

		{
            validata=false;
			errmsg="Please enter Dia...";
			focusid="txtShaftPinionDia";
			
		}

		else if (document.getElementById("txtShaftPinionFace").value=="" || (parseFloat(document.getElementById("txtShaftPinionFace").value)==0))

		{
            validata=false;
			errmsg="Please enter Face...";
			focusid="txtShaftPinionFace";
			

		}

		else if (document.getElementById("txtShaftPinionProcessing").value=="")

		{
            validata=false;
			errmsg="Please enter Processing Type...";
			focusid="txtShaftPinionProcessing";
			

		}

		else if (document.getElementById('txtShaftPinionPcs').value=="" || (parseInt(document.getElementById('txtShaftPinionPcs').value)==0))

		{
            validata=false;
			errmsg="Please enter Pcs...";
			focusid="txtShaftPinionPcs";
			

		}
        if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
		}
		else

		{

			add=true;

		

		Type=document.getElementById("cmbShaftPinionType").value;

		Teeth=document.getElementById("txtShaftPinionTeeth").value;

		Face= document.getElementById("txtShaftPinionFace").value;

		FaceType=document.getElementById("cmbShaftPinionFaceType").value;

		DMValue=document.getElementById("txtShaftPinionProcessing").value;

		DMType=document.getElementById("cmbShaftPinionProcessingType").value;

		PartyPartType=document.getElementById("cmbShaftPinionPartyType").value;

		pcs=document.getElementById("cmbShaftPinionCal").value;

		if (PartyPartType=="Expeller")

			url="operations/party_order_shaft_pinion_rate.php?Type="+Type+"&Teeth="+Teeth+"&Face="+Face+"&FaceType="+FaceType+"&DMValue="+DMValue+"&DMType="+DMType+"&PartyID="+PartyID;

		else

			url="operations/party_order_ppshaft_pinion_rate.php?Type="+Type+"&Teeth="+Teeth+"&Face="+Face+"&FaceType="+FaceType+"&DMValue="+DMValue+"&DMType="+DMType+"&PartyID="+PartyID;
        }
	}

	else if (Id=="btnAddBevelGear")

	{
        var validata=true;
		var focusid="";
		var errmsg="";
		if (document.getElementById("txtBevelGearTeeth").value=="" || (parseInt(document.getElementById('txtBevelGearTeeth').value)==0))
        {
            validata=false;
			focusid="txtBevelGearTeeth";
			errmsg="Please enter Teeth...";
			
		}

		else if (document.getElementById("txtBevelGearDia").value=="" || (parseFloat(document.getElementById("txtBevelGearDia").value)==0))

		{
            validata=false;
			focusid="txtBevelGearDia";
			errmsg="Please enter Dia...";
			

		}

		else if (document.getElementById("txtBevelGearProcessing").value=="")
        {
            validata=false;
			focusid="txtBevelGearProcessing";
			errmsg="Please enter Processing Type...";
		}

		else if (document.getElementById('txtBevelGearPcs').value=="" || (parseInt(document.getElementById('txtBevelGearPcs').value)==0))
        {
            validata=false;
			focusid="txtBevelGearPcs";
			errmsg="Please enter Pcs...";
			

		}
        if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
		}
		else

		{

			add=true;

		



		dmvalue=document.getElementById("txtBevelGearProcessing").value;

		dmvaluetype=document.getElementById("cmbBevelGearProcessingType").value;

		teeth=document.getElementById("txtBevelGearTeeth").value;

		PartyPartType=document.getElementById("cmbBevelGearPartyType").value;

		pcs=document.getElementById("cmbBevelGearCal").value;

		if (PartyPartType=="Expeller")

			url="operations/party_order_bevel_gear_rate.php?DMvalue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID+"&Teeth="+teeth;

		else

			url="operations/party_order_ppbevel_gear_rate.php?DMvalue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID+"&Teeth="+teeth;
      }
	}

	else if (Id=="btnAddBevelPinion")

	{
        var validata=true;
		var focusid="";
		var errmsg="";
		if (document.getElementById("txtBevelPinionTeeth").value=="" || (parseInt(document.getElementById('txtBevelPinionTeeth').value)==0))
        {
            validata=true;
			errmsg="Please enter Teeth...";
			focusid="txtBevelPinionTeeth";
			
		}

		else if (document.getElementById("txtBevelPinionDia").value=="" || (parseFloat(document.getElementById("txtBevelPinionDia").value)==0))
        {
            validata=true;
			errmsg="Please enter Dia...";
			focusid="txtBevelPinionDia";
			

		}

		else if (document.getElementById("txtBevelPinionProcessing").value=="")

		{
            validata=true;
			errmsg="Please enter Processing Type...";
			focusid="txtBevelPinionProcessing";
			

		}

		else if (document.getElementById('txtBevelPinionPcs').value=="" || (parseInt(document.getElementById('txtBevelPinionPcs').value)==0))

		{
            validata=true;
			errmsg="Please enter Pcs...";
			focusid="txtBevelPinionPcs";
			

		}
        if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
		}
		else

		{

			add=true;

			dmvalue=document.getElementById("txtBevelPinionProcessing").value;

			dmvaluetype=document.getElementById("cmbBevelPinionProcessingType").value;

			teeth=document.getElementById("txtBevelPinionTeeth").value;

			PartyPartType=document.getElementById("cmbBevelPinionPartyType").value;

			pcs=document.getElementById("cmbBevelPinionCal").value;

			if (PartyPartType=="Expeller")

				url="operations/party_order_bevel_gear_rate.php?DMvalue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID+"&Teeth="+teeth;

			else

				url="operations/party_order_ppbevel_gear_rate.php?DMvalue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID+"&Teeth="+teeth;

		}

	}

	else if (Id=="btnAddChainWheel")

	{
        var validata=true;
		var focusid="";
		var errmsg="";
		if (document.getElementById("txtChainWheelTeeth").value=="" || (parseInt(document.getElementById('txtChainWheelTeeth').value)==0))
        {
            validata=false;
			errmsg="Please enter Teeth...";
			focusid="txtChainWheelTeeth";
			

		}

		else if (document.getElementById("txtChainWheelDia").value=="" || (parseFloat(document.getElementById("txtChainWheelDia").value)==0))
        {
            validata=false;
			errmsg="Please enter Dia...";
			focusid="txtChainWheelDia";
			
			

		}

		else if (document.getElementById("txtChainWheelPitch").value=="" || (parseFloat(document.getElementById("txtChainWheelPitch").value)==0))

		{
            validata=false;
			errmsg="Please enter Pitch...";
			focusid="txtChainWheelPitch";
			
			
		}

		else if (document.getElementById('txtChainWheelPcs').value=="" || (parseInt(document.getElementById('txtChainWheelPcs').value)==0))

		{
            validata=false;
			errmsg="Please enter Pcs...";
			focusid="txtChainWheelPcs";

		}
        if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
		}
		else
        {

			add=true;

			pitchvalue=document.getElementById("txtChainWheelPitch").value;

			pitchtype=document.getElementById("cmbChainWheelPitchType").value;

			PartyPartType=document.getElementById("cmbChainGearPartyType").value;

			pcs=document.getElementById("cmbChainWheelCal").value;

			Itemtype=document.getElementById('cmbChainWheelType').value;

			if (PartyPartType=="Expeller")

				url="operations/party_order_chaingear_rate.php?PitchValue="+pitchvalue+"&PitchType="+pitchtype+"&PartyID="+PartyID + "&ItemType="+Itemtype;

			else

				url="operations/party_order_ppchaingear_rate.php?PitchValue="+pitchvalue+"&PitchType="+pitchtype+"&PartyID="+PartyID + "&ItemType="+Itemtype;

		}

	}

	else if (Id=="btnAddWormGear")

	{
        var validata=true;
		var errmsg="";
		var focusid="";
		if (document.getElementById("txtWormGearTeeth").value=="" || (parseInt(document.getElementById('txtWormGearTeeth').value)==0))

		{
            validata=false;
			errmsg="Please enter Teeth...";
			focusid="txtWormGearTeeth";
			

		}

		else if (document.getElementById("txtWormGearDia").value=="" || (parseFloat(document.getElementById("txtWormGearDia").value)==0))

		{
            validata=false;
			errmsg="Please enter Dia...";
			focusid="txtWormGearDia";
			

		}

		else if (document.getElementById("txtWormGearProcessing").value=="")

		{
            validata=false;
			errmsg="Please enter Processing Type...";
			focusid="txtWormGearProcessing";
			

		}

		else if (document.getElementById('txtWormGearPcs').value=="" || (parseInt(document.getElementById('txtWormGearPcs').value)==0))

		{
            validata=false;
			errmsg="Please enter Pcs...";
			focusid="txtWormGearPcs";
			

		}

		else 

		{

			add=true;

			pcs=document.getElementById("cmbWormGearCal").value;

			url="operations/party_order_wormgear_rate.php";

		}

	}



	if (add)

	{

		var http=false;

		if (navigator.appName=="Microsoft Internet Explorer")

		{

			http= new ActiveXObject ("Microsoft.XMLHTTP");

		}

		else

		{

			http=	 new XMLHttpRequest();

		}

		http.open ("GET",url);

		http.onreadystatechange=function()

		{

			if (http.readyState==4 && http.status==200)

			{

				if (http.responseText!="")

				{

					document.getElementById("hdOrderItemRate").value=http.responseText;

					

					if (add)

					{

						AddOrder(Id);	

					}

				}

			}

		}

		http.send(null);	

	}	

}



function AddOrder(Id)

{

	lbl=document.getElementById('lblErrMsg');



	if (Id=="btnAddGear")

	{

		Teeth=document.getElementById('txtGearTeeth').value;

		gearprvalue=parseFloat(document.getElementById('txtGearProcessing').value);			

		PrMeasure=(gearprvalue).toFixed(2);

		PrType=document.getElementById('cmbGearProcessingType').value;

		geardia=parseFloat(document.getElementById('txtGearDia').value);

		Dia=(geardia).toFixed(3);

		DiaMeasure=document.getElementById('cmbGearDiaType').value;

		gearface=parseFloat(document.getElementById('txtGearFace').value);

		Face=(gearface).toFixed(2);

		FaceType=document.getElementById('cmbGearFaceType').value;

		ItemType=document.getElementById('cmbGearType').value;

		ItemName="Gear";

		Pitch="";

		PitchType="";

		Pcs=document.getElementById("cmbGearCal").value;

		PartyPartType=document.getElementById("cmbGearPartyType").value;

		TypeCalculation=document.getElementById("cmbGearCal").value;  

		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType,PartyPartType ,TypeCalculation )==true)

		{

			

			len=OrderArr.length;

			

			if (len>24)

			{

				alert ("You have already entered 25 Items...");

			}			

			else

			{

				OrderArr[len]=new Array();

				PartyPartType=document.getElementById("cmbGearPartyType").value;

				

				OrderArr[len][0]=document.getElementById('txtGearPcs').value;   // No. of Pcs.

				if (Pcs=="PT")

					OrderArr[len][1]=document.getElementById('hdOrderItemRate').value;   // rate of Item

				else

					OrderArr[len][1]=0;

				OrderArr[len][2]=document.getElementById('txtGearTeeth').value;   // Teeth

				gearprvalue=parseFloat(document.getElementById('txtGearProcessing').value);			

				gearprvalue=(gearprvalue).toFixed(2);

				OrderArr[len][3]=gearprvalue;   // Processing Type

				OrderArr[len][4]=document.getElementById('cmbGearProcessingType').value;	 // Processing Type Measurement

				geardia=parseFloat(document.getElementById('txtGearDia').value);

				geardia=(geardia).toFixed(3);

				OrderArr[len][5]=geardia;   // Dia

				OrderArr[len][6]=document.getElementById('cmbGearDiaType').value;   // Dia Measurement

				gearface=parseFloat(document.getElementById('txtGearFace').value);

				gearface=(gearface).toFixed(2);

				OrderArr[len][7]=gearface;	// Face

				OrderArr[len][8]=document.getElementById('cmbGearFaceType').value;	 // Face Measurement

				OrderArr[len][9]=document.getElementById('cmbGearType').value;	 // Item Type

				OrderArr[len][10]="";	 // Pitch

				OrderArr[len][11]="Gear";

				OrderArr[len][12]="";// PitchType

				OrderArr[len][13]="";// Price Of Item/s

				OrderArr[len][14]="New";

				OrderArr[len][16]="";

				OrderArr[len][17]=PartyPartType;

				OrderArr[len][18]=document.getElementById("cmbGearCal").value;               // PT or PCS 

				OrderArr[len][19]=""; // Collar

			}

		}

		else

		{

			alert ("Item Already exist Please modify existing item");

		}

	}

	else if (Id=="btnAddPinion")

	{

		Teeth=document.getElementById('txtPinionTeeth').value;

		pinionprvalue=parseFloat(document.getElementById('txtPinionProcessing').value);			

		PrMeasure=(pinionprvalue).toFixed(2);

		PrType=document.getElementById('cmbPinionProcessingType').value;

		piniondia=parseFloat(document.getElementById('txtPinionDia').value);

		Dia=(piniondia).toFixed(3);

		DiaMeasure=document.getElementById('cmbPinionDiaType').value;

		pinionface=parseFloat(document.getElementById('txtPinionFace').value);

		Face=(pinionface).toFixed(2);

		FaceType=document.getElementById('cmbPinionFaceType').value;

		ItemType=document.getElementById('cmbPinionType').value;

		ItemName="Pinion";

		Pitch="";

		PitchType=""; 

		PartyPartType=document.getElementById("cmbPinionPartyType").value;

		Pcs=document.getElementById("cmbPinionCal").value;



		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType , PartyPartType, Pcs)==true)

		{

			len=OrderArr.length;

			if (len>24)

			{

				alert ("You have already entered 25 Items...");

			}			

			else

			{

				OrderArr[len]=new Array();

				PartyPartType=document.getElementById("cmbPinionPartyType").value;	

				OrderArr[len][0]=document.getElementById('txtPinionPcs').value;	   // No. of Pcs.

				if (Pcs=="PT")

					OrderArr[len][1]=document.getElementById('hdOrderItemRate').value;   // rate of Item

				else

					OrderArr[len][1]=0;

				OrderArr[len][2]=document.getElementById('txtPinionTeeth').value;   // Teeth

				pinionprvalue=parseFloat(document.getElementById('txtPinionProcessing').value);			

				pinionprvalue=(pinionprvalue).toFixed(2);

				OrderArr[len][3]=pinionprvalue;   // Processing Type

				OrderArr[len][4]=document.getElementById('cmbPinionProcessingType').value;	 // Processing Type Measurement

				piniondia=parseFloat(document.getElementById('txtPinionDia').value);

				piniondia=(piniondia).toFixed(3);

				OrderArr[len][5]=piniondia;   // Dia

				OrderArr[len][6]=document.getElementById('cmbPinionDiaType').value;   // Dia Measurement

				pinionface=parseFloat(document.getElementById('txtPinionFace').value);

				pinionface=(pinionface).toFixed(2);

				OrderArr[len][7]=pinionface;	// Face

				OrderArr[len][8]=document.getElementById('cmbPinionFaceType').value;	 // Face Measurement

				OrderArr[len][9]=document.getElementById('cmbPinionType').value;	 // Item Type

				OrderArr[len][10]="";	 // Pitch

				OrderArr[len][11]="Pinion";

				OrderArr[len][12]="";// PitchType

				OrderArr[len][13]="";// Price Of Item/s

				OrderArr[len][14]="New";

				OrderArr[len][16]="";

				OrderArr[len][17]=PartyPartType;

				OrderArr[len][18]=document.getElementById("cmbPinionCal").value;              // PT or PCS 

				OrderArr[len][19]=document.getElementById("txtPinionCollar").value;			// Collar

			}

		}

		else

		{

			alert ("Item already exist Please modify existing item");			

		}

	}

	else if (Id=="btnAddShaftPinion")

	{

		Teeth=document.getElementById('txtShaftPinionTeeth').value;

		shaftpinionprvalue=parseFloat(document.getElementById('txtShaftPinionProcessing').value);			

		PrMeasure=(shaftpinionprvalue).toFixed(2);

		PrType=document.getElementById('cmbShaftPinionProcessingType').value;



		shaftpiniondia=parseFloat(document.getElementById('txtShaftPinionDia').value);

		Dia=(shaftpiniondia).toFixed(3);

		

		DiaMeasure=document.getElementById('cmbShaftPinionDiaType').value;



		shaftpinionface=parseFloat(document.getElementById('txtShaftPinionFace').value);

		Face=(shaftpinionface).toFixed(2);



		FaceType=document.getElementById('cmbShaftPinionFaceType').value;

		ItemType=document.getElementById('cmbShaftPinionType').value;

		ItemName="Shaft Pinion";

		Pitch="";

		PitchType=""; 

		Pcs=document.getElementById("cmbShaftPinionCal").value;

		PartyPartyType=document.getElementById("cmbShaftPinionPartyType").value;

		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType, PartyPartyType,Pcs )==true)		

		{

			len=OrderArr.length;

			if (len>24)

			{

				alert ("You have already entered 25 Items...");

			}			

			else

			{

				OrderArr[len]=new Array();

				PartyPartyType=document.getElementById("cmbShaftPinionPartyType").value;

				OrderArr[len][0]=document.getElementById('txtShaftPinionPcs').value;	// No. of Pcs.

				if (Pcs=="PCS")

					OrderArr[len][1]=document.getElementById('hdOrderItemRate').value;   // rate of Item

				else

					OrderArr[len][1]=0;

				OrderArr[len][2]=document.getElementById('txtShaftPinionTeeth').value;   // Teeth

				shaftpinionprvalue=parseFloat(document.getElementById('txtShaftPinionProcessing').value);			

				shaftpinionprvalue=(shaftpinionprvalue).toFixed(2);

				OrderArr[len][3]=shaftpinionprvalue;   // Processing Type

				OrderArr[len][4]=document.getElementById('cmbShaftPinionProcessingType').value;	 // Processing Type Measurement

				shaftpiniondia=parseFloat(document.getElementById('txtShaftPinionDia').value);

				shaftpiniondia=(shaftpiniondia).toFixed(3);

				

				OrderArr[len][5]=shaftpiniondia;	// Dia

				OrderArr[len][6]=document.getElementById('cmbShaftPinionDiaType').value;	 // Dia Measurement

				shaftpinionface=parseFloat(document.getElementById('txtShaftPinionFace').value);

				shaftpinionface=(shaftpinionface).toFixed(2);

				OrderArr[len][7]=shaftpinionface;	// Face

				OrderArr[len][8]=document.getElementById('cmbShaftPinionFaceType').value;	 // Face Measurement

				OrderArr[len][9]=document.getElementById("cmbShaftPinionType").value;

				OrderArr[len][10]="";	 // Pitch

				OrderArr[len][11]="Shaft Pinion";

				OrderArr[len][12]="";// PitchType

				OrderArr[len][13]="";// Price Of Item/s

				OrderArr[len][14]="New";

				OrderArr[len][16]="";

				OrderArr[len][17]=PartyPartType;

				OrderArr[len][18]=document.getElementById("cmbShaftPinionCal").value;              // PT or PCS 

				OrderArr[len][19]=""; // Collar

			}

		}

		else

		{

			alert ("Item already exist Please modify existing item");					

		}

	}

	else if (Id=="btnAddBevelGear")

	{

		Teeth=document.getElementById('txtBevelGearTeeth').value;

		bevelgearprvalue=parseFloat(document.getElementById('txtBevelGearProcessing').value);			

		PrMeasure=(bevelgearprvalue).toFixed(2);

		PrType=document.getElementById('cmbBevelGearProcessingType').value;

		bevelgeardia=parseFloat(document.getElementById('txtBevelGearDia').value);

		Dia=(bevelgeardia).toFixed(3);

		

		DiaMeasure=document.getElementById('cmbBevelGearDiaType').value;

		Face="";

		FaceType="";

		ItemType="";

		ItemName="Bevel Gear";

		Pitch="";

		PitchType=""; 

		Pcs=document.getElementById("cmbBevelGearCal").value;

		PartyPartyType=document.getElementById("cmbBevelGearPartyType").value;

		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType , PartyPartyType, Pcs)==true)

		{

			len=OrderArr.length;

			if (len>24)

			{

				alert ("You have already entered 25 Items...");

			}			

			else

			{

				OrderArr[len]=new Array();

				PartyPartyType=document.getElementById("cmbBevelGearPartyType").value;

				OrderArr[len][0]=document.getElementById('txtBevelGearPcs').value;	  // No. of Pcs.

				if (Pcs=="PT")

					OrderArr[len][1]=document.getElementById('hdOrderItemRate').value;   // rate of Item

				else

					OrderArr[len][1]=0;

				OrderArr[len][2]=document.getElementById('txtBevelGearTeeth').value;   // Teeth

				bevelgearprvalue=parseFloat(document.getElementById('txtBevelGearProcessing').value);			

				bevelgearprvalue=(bevelgearprvalue).toFixed(2);

				OrderArr[len][3]=bevelgearprvalue;   // Processing Type

	

				OrderArr[len][4]=document.getElementById('cmbBevelGearProcessingType').value;	 // Processing Type Measurement

				bevelgeardia=parseFloat(document.getElementById('txtBevelGearDia').value);

				bevelgeardia=(bevelgeardia).toFixed(3);

				OrderArr[len][5]=bevelgeardia;	// Dia

				OrderArr[len][6]=document.getElementById('cmbBevelGearDiaType').value;	// Dia Measurement

				OrderArr[len][7]="";	// Face

				OrderArr[len][8]="";	 // Face Measurement

				OrderArr[len][9]="";

				OrderArr[len][10]="";	 // Pitch

				OrderArr[len][11]="Bevel Gear";

				OrderArr[len][12]="";// PitchType

				OrderArr[len][13]="";// Price Of Item/s

				OrderArr[len][14]="New";

				OrderArr[len][16]="";

				OrderArr[len][17]=PartyPartType;

				OrderArr[len][18]=document.getElementById("cmbBevelGearCal").value;            // PT or PCS 

				OrderArr[len][19]=""; // Collar

			}

		}

		else

		{

			alert ("Item already exist Please modify existing item");

		}

	}

	else if (Id=="btnAddBevelPinion")

	{

		Teeth=document.getElementById('txtBevelPinionTeeth').value;

		bevelpinionprvalue=parseFloat(document.getElementById('txtBevelPinionProcessing').value);			

		PrMeasure=(bevelpinionprvalue).toFixed(2);

		PrMeasure=document.getElementById('txtBevelPinionProcessing').value;

		PrType=document.getElementById('cmbBevelPinionProcessingType').value;



		bevelpiniondia=parseFloat(document.getElementById('txtBevelPinionDia').value);

		Dia=(bevelpiniondia).toFixed(3);

		

		//Dia=document.getElementById('txtBevelPinionDia').value;

		DiaMeasure=document.getElementById('cmbBevelPinionDiaType').value;

		Face="";

		FaceType="";

		ItemType="";

		ItemName="Bevel Pinion";

		Pitch="";

		PitchType=""; 

		Pcs=document.getElementById("cmbBevelPinionCal").value;

		PartyPartyType=document.getElementById("cmbBevelPinionPartyType").value;

		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType,PartyPartyType, Pcs )==true)

		{

			len=OrderArr.length;

			if (len>24)

			{

				alert ("You have already entered 25 Items...");

			}			

			else

			{

				OrderArr[len]=new Array();

				PartyPartyType=document.getElementById("cmbBevelPinionPartyType").value;		

				OrderArr[len][0]=document.getElementById('txtBevelPinionPcs').value; // No. of Pcs.

				if (Pcs=="PT")

					OrderArr[len][1]=document.getElementById('hdOrderItemRate').value;   // rate of Item

				else

					OrderArr[len][1]=0;

				OrderArr[len][2]=document.getElementById('txtBevelPinionTeeth').value;   // Teeth

				bevelpinionprvalue=parseFloat(document.getElementById('txtBevelPinionProcessing').value);			

				bevelpinionprvalue=(bevelpinionprvalue).toFixed(2);

				OrderArr[len][3]=bevelpinionprvalue;   // Processing Type

				OrderArr[len][4]=document.getElementById('cmbBevelPinionProcessingType').value;	 // Processing Type Measurement

				bevelpiniondia=parseFloat(document.getElementById('txtBevelPinionDia').value);

				bevelpiniondia=(bevelpiniondia).toFixed(3);

				OrderArr[len][5]=bevelpiniondia;	// Dia

				OrderArr[len][6]=document.getElementById('cmbBevelPinionDiaType').value;	 // Dia Measurement

				OrderArr[len][7]="";	// Face

				OrderArr[len][8]="";	 // Face Measurement

				OrderArr[len][9]="";

				OrderArr[len][10]="";	 // Pitch

				OrderArr[len][11]="Bevel Pinion";

				OrderArr[len][12]="";// PitchType

				OrderArr[len][13]="";// Price Of Item/s

				OrderArr[len][14]="New";

				OrderArr[len][16]="";

				OrderArr[len][17]=PartyPartType;

				OrderArr[len][18]=document.getElementById("cmbBevelPinionCal").value;              // PT or PCS 

				OrderArr[len][19]=""; // Collar

			}

		}

		else

		{

			alert ("Item already exist Please modify existing item");

		}

	}

	else if (Id=="btnAddChainWheel")

	{

		Teeth=document.getElementById('txtChainWheelTeeth').value;

		PrMeasure="";

		PrType="";

		chainwheeldia=parseFloat(document.getElementById('txtChainWheelDia').value);

		Dia=(chainwheeldia).toFixed(3);

		

		DiaMeasure=document.getElementById('cmbChainWheelDia').value;

		Face="";

		FaceType="";

		ItemType=document.getElementById('cmbChainWheelType').value;

		ItemName="Chain Wheel";

		chainwheelpitch=parseFloat(document.getElementById('txtChainWheelPitch').value);

		Pitch=(chainwheelpitch).toFixed(2);

		Pitch=document.getElementById('txtChainWheelPitch').value;

		PitchType=document.getElementById('cmbChainWheelPitchType').value; 

		Pcs=document.getElementById("cmbChainWheelCal").value;

		PartyPartyType=document.getElementById("cmbChainGearPartyType").value;

		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType , PartyPartyType, Pcs )==true)

		{

			len=OrderArr.length;

			if (len>24)

			{

				alert ("You have already entered 25 Items...");

			}			

			else

			{

				OrderArr[len]=new Array();

				PartyPartyType=document.getElementById("cmbChainGearPartyType").value;

				OrderArr[len][0]=document.getElementById('txtChainWheelPcs').value;	// No. of Pcs.

				if (Pcs=="PT")

					OrderArr[len][1]=document.getElementById('hdOrderItemRate').value;   // rate of Item

				else

					OrderArr[len][1]=0;

				OrderArr[len][2]=document.getElementById('txtChainWheelTeeth').value;   // Teeth

				OrderArr[len][3]="";	// Processing Type

				OrderArr[len][4]="";	  // Processing Type Measurement

				chainwheeldia=parseFloat(document.getElementById('txtChainWheelDia').value);

				chainwheeldia=(chainwheeldia).toFixed(3);

				OrderArr[len][5]=chainwheeldia;   // Dia

	

				OrderArr[len][6]=document.getElementById('cmbChainWheelDia').value;   // Dia Measurement

				OrderArr[len][7]="";	// Face

				OrderArr[len][8]="";	 // Face Measurement						 

				OrderArr[len][9]=document.getElementById('cmbChainWheelType').value;	 // Item Type

				chainwheelpitch=parseFloat(document.getElementById('txtChainWheelPitch').value);

				chainwheelpitch=(chainwheelpitch).toFixed(2);

				OrderArr[len][10]=chainwheelpitch;	 // Pitch

				OrderArr[len][11]="Chain Wheel";

				OrderArr[len][12]=document.getElementById('cmbChainWheelPitchType').value;// PitchType

				OrderArr[len][13]="";// Price Of Item/s

				OrderArr[len][14]="New";

				OrderArr[len][16]="";

				OrderArr[len][17]=PartyPartType;

				OrderArr[len][18]=document.getElementById("cmbChainWheelCal").value;             // PT or PCS 

				OrderArr[len][19]=""; // Collar

			}

		}

		else

		{

			alert ("Item already exist Please modify existing item");			

		}

	}

	else if (Id=="btnAddWormGear")

	{

		Teeth=document.getElementById('txtWormGearTeeth').value;

		wormgearprvalue=parseFloat(document.getElementById('txtWormGearProcessing').value);			

		PrMeasure=(wormgearprvalue).toFixed(2);

		PrMeasure=document.getElementById('txtWormGearProcessing').value;

		PrType=document.getElementById('cmbWormGearProcessingType').value;

		wormgeardia=parseFloat(document.getElementById('txtWormGearDia').value);

		Dia=(wormgeardia).toFixed(3);

		DiaMeasure=document.getElementById('cmbWormGearDiaType').value;

		Face="";

		FaceType="";

		ItemType="";

		ItemName="Worm Gear";

		Pitch="";

		PitchType=""; 

		Pcs=document.getElementById("cmbWormGearCal").value;

		PartyPartyType="";

		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType, PartyPartyType, Pcs )==true)

		{

			len=OrderArr.length;

			if (len>24)

			{

				alert ("You have already entered 25 Items...");

			}			

			else

			{

				OrderArr[len]=new Array();		

				OrderArr[len][0]=document.getElementById('txtWormGearPcs').value; // No. of Pcs.

				if (Pcs=="PT")

					OrderArr[len][1]=document.getElementById('hdOrderItemRate').value;   // rate of Item

				else

					OrderArr[len][1]=0;

				OrderArr[len][2]=document.getElementById('txtWormGearTeeth').value;   // Teeth

	

				wormgearprvalue=parseFloat(document.getElementById('txtWormGearProcessing').value);			

				wormgearprvalue=(wormgearprvalue).toFixed(2);

				OrderArr[len][3]=wormgearprvalue;   // Processing Type

				OrderArr[len][4]=document.getElementById('cmbWormGearProcessingType').value;	 // Processing Type Measurement

	

				wormgeardia=parseFloat(document.getElementById('txtWormGearDia').value);

				wormgeardia=(wormgeardia).toFixed(3);

				OrderArr[len][5]=wormgeardia;	// Dia

				OrderArr[len][6]=document.getElementById('cmbWormGearDiaType').value;	 // Dia Measurement

				OrderArr[len][7]="";	// Face

				OrderArr[len][8]="";	 // Face Measurement

				OrderArr[len][9]="";

				OrderArr[len][10]="";	 // Pitch

				OrderArr[len][11]="Worm Gear";

				OrderArr[len][12]="";// PitchType

				OrderArr[len][13]="";// Price Of Item/s

				OrderArr[len][14]="New";

				OrderArr[len][16]="";

				OrderArr[len][17]="Expeller";

				OrderArr[len][18]=document.getElementById("cmbWormGearCal").value;            // PT or PCS 

				OrderArr[len][19]=""; // Collar

			}

		}

		else

		{

			alert ("Item already exist Please modify existing item");

		}

	}

	DispOrderInfo();

	document.getElementById('txtGearPcs').value="";

	document.getElementById('hdOrderItemRate').value="";

	document.getElementById('txtGearTeeth').value="";

	document.getElementById('txtGearProcessing').value="";

	document.getElementById('txtGearDia').value="";

	document.getElementById('txtGearFace').value="";

	document.getElementById('txtPinionPcs').value="";

	document.getElementById('txtPinionTeeth').value="";

	document.getElementById('txtPinionProcessing').value="";

	document.getElementById('txtPinionDia').value="";

	document.getElementById('txtPinionFace').value="";

	document.getElementById('txtShaftPinionTeeth').value="";

	document.getElementById("txtShaftPinionDia").value="";

	document.getElementById('txtShaftPinionPcs').value="";

	document.getElementById('txtShaftPinionProcessing').value="";

	document.getElementById('txtShaftPinionFace').value="";

	document.getElementById('txtBevelGearPcs').value="";

	document.getElementById('txtBevelGearTeeth').value="";

	document.getElementById("txtBevelGearDia").value="";

	document.getElementById('txtBevelGearProcessing').value="";

	document.getElementById('txtBevelPinionPcs').value="";

	document.getElementById('txtBevelPinionTeeth').value="";

	document.getElementById("txtBevelPinionDia").value="";

	document.getElementById('txtBevelPinionProcessing').value="";

	document.getElementById('txtChainWheelPcs').value="";

	document.getElementById('txtChainWheelTeeth').value="";

	document.getElementById('txtChainWheelDia').value="";

	document.getElementById('txtChainWheelPitch').value="";

	document.getElementById('txtWormGearTeeth').value="";

	document.getElementById('txtWormGearDia').value="";

	document.getElementById('txtWormGearProcessing').value="";

	document.getElementById('txtWormGearPcs').value="";

	document.getElementById('txtPinionCollar').value="";

}



function DispalyEdit1()

{

	if(document.getElementById("hdBillingData").value!='')
	{
		opt=(document.getElementById("hdBillingData").value).split('~Array~');	
	
		if (OrderArr.length>0)
	
		{
	
			OrderArr.length=0;
	
		}
	
		for (x=0;x<opt.length;x++ )
	
		{
	
			opts=opt[x].split('~ArrayItem~');
	
			len=OrderArr.length;
	
			OrderArr[len]=new Array();
	
			OrderArr[len][0]=opts[0];   // No. Of Pcs
	
			OrderArr[len][1]=opts[1];   // Rate of Item
	
			OrderArr[len][2]=opts[2];   // Teeth
	
			OrderArr[len][3]=opts[3];   // Processing Type
	
			OrderArr[len][4]=opts[4];   // Processing Type Measurement
	
			OrderArr[len][5]=opts[5];   // Dia
	
			OrderArr[len][6]=opts[6];    // Dia Measurement
	
			OrderArr[len][7]=opts[7];   //Face
	
			OrderArr[len][8]=opts[8];   // Face Measurement
	
			OrderArr[len][9]=opts[9];   // Item Type
	
			OrderArr[len][10]=opts[10];  // Pitch
	
			OrderArr[len][11]=opts[11];  // Category
	
			OrderArr[len][12]=opts[12];  // Pitch Type
	
			OrderArr[len][13]="";        // Price
	
			OrderArr[len][14]="Edit";	 // New or Edit
	
			OrderArr[len][15]=opts[13]; // Item Code
	
			OrderArr[len][16]=opts[14]; // Item Remarks
	
			OrderArr[len][17]=opts[15]; // PartType
	
			OrderArr[len][18]=opts[16];  // PT/PCS
	
			OrderArr[len][19]=opts[17];  // Collar
	
		}
	}
	DispOrderInfo();

}



function DispOrderInfo()

{

	

	OrderItem=document.getElementById("dvChallanDetails");

	OrderItem.innerHTML="";

	var x;

	var j=0;

	var TotalPrice=0;

	var Data="<table class=\"table table-bordered dt-responsive\">";

	Data+="<thead><tr>";

	Data+="<td>SNO</td>";

	Data+="<td>Item Name</td>";

	Data+="<td>Rate</td>";

	Data+="<td>Price</td>";

	Data+="<td>Options</td>";

	Data+="</thead></tr><tbody>";

	

	for (x=0;x<OrderArr.length ;x++ )

	{

		if (OrderArr[x][14]=="Deleted")

		{

			

		}

		else

		{

			Data+="<tr>";	

			Data+="<td>"+(j+1)+"</td>";

			txtPcs="txtPcs"+x;

			if (OrderArr[x][11]=="Gear")

			{

				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" class=\"form-control\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth "+OrderArr[x][9]+" gear dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;face "+OrderArr[x][7]+"&nbsp;"+OrderArr[x][8]+ "@ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";

			}

			else if (OrderArr[x][11]=="Pinion")

			{

				//Data+="<td class=\"tablecell\" colspan=\"8\"><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth "+OrderArr[x][9]+" pinion dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;face "+OrderArr[x][7]+"&nbsp;"+OrderArr[x][8]+ "@ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";

				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" class=\"form-control\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth "+OrderArr[x][9]+" pinion dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;face "+OrderArr[x][7];

				if (OrderArr[x][19]!="")

				Data+=" + "+OrderArr[x][19];

				Data+="&nbsp;"+OrderArr[x][8]+ "@ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";

			}

			else if (OrderArr[x][11]=="Shaft Pinion")

			{

				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" class=\"form-control\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth "+OrderArr[x][9]+" shaft pinion dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;face "+OrderArr[x][7]+"&nbsp;"+OrderArr[x][8]+ "@ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";

			}

			else if (OrderArr[x][11]=="Bevel Gear")

			{

				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" class=\"form-control\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;@ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";

			}

			else if (OrderArr[x][11]=="Bevel Pinion")

			{

				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" class=\"form-control\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;@ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";

			}

			else if (OrderArr[x][11]=="Chain Wheel")

			{

				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" class==\"form-control\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth ("+OrderArr[x][9]+") dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;pitch "+OrderArr[x][10]+"&nbsp;"+OrderArr[x][12]+ " @ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";

			}

			else if (OrderArr[x][11]=="Worm Gear")

			{

				Data+="<td class=\"tablecell\"><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" class=\"form-control\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;("+OrderArr[x][3]+"&nbsp;"+OrderArr[x][4]+") @ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";				

			}



			

			txtRemarks="txtRemarks"+x;

			//Data+="<b>Remarks :</b> <input type=\"text\" name=\""+txtRemarks+"\" id=\""+txtRemarks+"\" value=\""+OrderArr[x][16]+"\" size=\"35\" onChange=\"FillRemarks(this.id,"+x+")\"></td>";

			txt="txtRate"+x;

			Data+="<td class=\"tablecell\"><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+OrderArr[x][1]+"\" class==\"form-control\" onChange=\"FillOrderArr(this.id,"+x+")\">("+OrderArr[x][18]+")</td>";

			

			if (OrderArr[x][11]=="Shaft Pinion" || OrderArr[x][11]=="Worm Gear")

			{

				if (OrderArr[x][18]=="PCS")

					OrderArr[x][13]=calculate1 (OrderArr[x][0], OrderArr[x][1], OrderArr[x][2], OrderArr[x][9], OrderArr[x][11],OrderArr[x][17] );

				else

					OrderArr[x][13]=parseFloat(OrderArr[x][0]* OrderArr[x][1] * OrderArr[x][2]);

			}

			else

			{

				if (OrderArr[x][18]=="PT")

				{

					Price=calculate1 (OrderArr[x][0], OrderArr[x][1], OrderArr[x][2], OrderArr[x][9], OrderArr[x][11],OrderArr[x][17] );

					OrderArr[x][13]=Price;

				}

				else

				{

					

					MinValue=parseFloat(document.getElementById("hdMinValue").value);

					Price=parseFloat(OrderArr[x][0]* OrderArr[x][1]);

					if (OrderArr[x][17]=="PowerPress" && OrderArr[x][11]=="Pinion" && OrderArr[x][1]<MinValue)

					{

						Price=parseFloat(OrderArr[x][0] * MinValue);

					}

					OrderArr[x][13]=Price;

				}

			}

			

			Data+="<td class=\"tablecell\">"+(parseFloat(OrderArr[x][13])).toFixed(2)+"</td>";

			btn="btn"+x;

			Data+="<td class=\"tablecell\"><input type=\"button\" value=\"Delete\" name=\""+btn+"\" id=\""+btn+"\" class=\"btncss\" onclick=\"Delete("+x+")\" onblur=\"shiftFocus("+x+")\"></td></tr>";

			TotalPrice=parseFloat(TotalPrice) + parseFloat(OrderArr[x][13]);

			j++;	

		}

	}

	Data=Data+"<tr><td class=\"tablecell\" colspan=\"3\"  style=\"text-align:right;\">Total value :</td><td><label id=\"lblTotal\" name=\"lblTotal\">"+(TotalPrice).toFixed(2)+"</label>&nbsp;<input type=\"hidden\" id=\"hdGrandTotal\" name=\"hdGrandTotal\" value=\""+(TotalPrice).toFixed(2)+"\"></td><td></td></tr>";

	Data=Data+"</tbody></table>";

	OrderItem.innerHTML=Data;

	OrderItem.style['height']='100%';

	TotalPrice=Math.round(TotalPrice);

	document.getElementById("hdTotalWithTax").value=TotalPrice;

	document.getElementById("lblTotalAmt").innerHTML=TotalPrice;

	if (document.getElementById("hdPageMode").value=="Edit")

	{

		if (parseFloat(document.getElementById("txtItemRate").value)>0)

		{

			var EditTotal;

			EditTotal=parseFloat(document.getElementById("lblTotal").innerHTML) ;

			document.getElementById("hdGTWithoutTax").value=(EditTotal).toFixed(2);			

		}

		else

		{

			EditTotal=parseFloat(document.getElementById("lblTotal").innerHTML);

			document.getElementById("hdGTWithoutTax").value=(EditTotal).toFixed(2);

		}

	}

	else

	{

		document.getElementById("hdGTWithoutTax").value=(TotalPrice).toFixed(2);

	}

	CalculateTax();

	concatData();

}



function shiftFocus(pos)

{

	len=OrderArr.length;	

	len=len-1;

	if(len==pos)

	{

		document.getElementById("txtItemName").focus();

	}

}



function FillPcs(Id, x)

{

	lbl=document.getElementById('lblErrMsg');

	lbl.innerHTML="";

	val=parseInt(document.getElementById(Id).value);

	if (val==0)

	{

		lbl.innerHTML="Pcs cannot be zero...";

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else if (isNaN(val))

	{

		lbl.innerHTML="value cannot be blank...";

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else

	{

		OrderArr[x][0]=val;

		DispOrderInfo();

		document.getElementById(Id).focus();

	}

}



function FillOrderArr(Id,x)

{

	lbl=document.getElementById('lblErrMsg');

	lbl.innerHTML="";

	

	val=parseFloat(document.getElementById(Id).value);

	if (val==0)

	{

		lbl.innerHTML="value cannot be zero...";

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else if (isNaN(val))

	{

		lbl.innerHTML="value cannot be blank...";

		document.getElementById(Id).focus();

		event.returnValue=false;

	}

	else

	{

		OrderArr[x][1]=val;

		DispOrderInfo();

		document.getElementById(Id).focus();

	}

}



function FillRemarks(Id,x)

{

	text=document.getElementById(Id).value;

	OrderArr[x][16]=text;

	DispOrderInfo();

}



function Delete(r1)

{

	len=OrderArr.length;

	if(r1< len)

	{

		for(k=r1;k<len;k++)

		{

			if( (k+1)==len)

			{

				OrderArr[k][0]="";

				OrderArr[k][1]="";

				OrderArr[k][2]="";

				OrderArr[k][3]="";

				OrderArr[k][4]="";

				OrderArr[k][5]="";

				OrderArr[k][6]="";

				OrderArr[k][7]="";

				OrderArr[k][8]="";

				OrderArr[k][9]="";

				OrderArr[k][10]="";

				OrderArr[k][11]="";

				OrderArr[k][12]="";

				OrderArr[k][13]="";

				OrderArr[k][14]="";

				OrderArr[k][15]="";

				OrderArr[k][16]="";

				OrderArr[k][17]="";

				OrderArr[k][18]="";

				OrderArr[k][19]="";

			}

			else

			{

				OrderArr[k][0]=OrderArr[k+1][0];

				OrderArr[k][1]=OrderArr[k+1][1];

				OrderArr[k][2]=OrderArr[k+1][2];

				OrderArr[k][3]=OrderArr[k+1][3];

				OrderArr[k][4]=OrderArr[k+1][4];

				OrderArr[k][5]=OrderArr[k+1][5];

				OrderArr[k][6]=OrderArr[k+1][6];

				OrderArr[k][7]=OrderArr[k+1][7];

				OrderArr[k][8]=OrderArr[k+1][8];

				OrderArr[k][9]=OrderArr[k+1][9];

				OrderArr[k][10]=OrderArr[k+1][10];

				OrderArr[k][11]=OrderArr[k+1][11];

				OrderArr[k][12]=OrderArr[k+1][12];

				OrderArr[k][13]=OrderArr[k+1][13];

				OrderArr[k][14]=OrderArr[k+1][14];

				OrderArr[k][16]=OrderArr[k+1][16];

				OrderArr[k][17]=OrderArr[k+1][17];

				OrderArr[k][18]=OrderArr[k+1][18];

				OrderArr[k][19]=OrderArr[k+1][19];

			}

		}				

	}

	OrderArr.length=OrderArr.length-1;

	DispOrderInfo();

}



function concatData()

{

	var packed="";

	document.getElementById("hdBillingData").value="";

	if (OrderArr.length>0)

	{

		for (x=0;x<OrderArr.length ;x++ )

		{

			if (packed=="")

			{

				packed=OrderArr[x][0]+"~ArrayItem~"+OrderArr[x][1]+"~ArrayItem~"+OrderArr[x][2]+"~ArrayItem~"+OrderArr[x][3]+"~ArrayItem~"+OrderArr[x][4]+"~ArrayItem~"+OrderArr[x][5]+"~ArrayItem~"+OrderArr[x][6]+"~ArrayItem~"+OrderArr[x][7]+"~ArrayItem~"+OrderArr[x][8]+"~ArrayItem~"+OrderArr[x][9]+"~ArrayItem~"+OrderArr[x][10]+"~ArrayItem~"+OrderArr[x][11]+"~ArrayItem~"+OrderArr[x][12]+"~ArrayItem~"+OrderArr[x][13]+"~ArrayItem~"+OrderArr[x][14]+"~ArrayItem~"+OrderArr[x][15]+"~ArrayItem~"+OrderArr[x][16]+"~ArrayItem~"+OrderArr[x][17]+"~ArrayItem~"+OrderArr[x][18]+"~ArrayItem~"+OrderArr[x][19];

			}

			else

			{

				packed= packed + "~Array~"+OrderArr[x][0]+"~ArrayItem~"+OrderArr[x][1]+"~ArrayItem~"+OrderArr[x][2]+"~ArrayItem~"+OrderArr[x][3]+"~ArrayItem~"+OrderArr[x][4]+"~ArrayItem~"+OrderArr[x][5]+"~ArrayItem~"+OrderArr[x][6]+"~ArrayItem~"+OrderArr[x][7]+"~ArrayItem~"+OrderArr[x][8]+"~ArrayItem~"+OrderArr[x][9]+"~ArrayItem~"+OrderArr[x][10]+"~ArrayItem~"+OrderArr[x][11]+"~ArrayItem~"+OrderArr[x][12]+"~ArrayItem~"+OrderArr[x][13]+"~ArrayItem~"+OrderArr[x][14]+"~ArrayItem~"+OrderArr[x][15]+"~ArrayItem~"+OrderArr[x][16]+"~ArrayItem~"+OrderArr[x][17]+"~ArrayItem~"+OrderArr[x][18]+"~ArrayItem~"+OrderArr[x][19];

			}

		}

	}

	

	document.getElementById("hdBillingData").value=packed;

	

}



function CheckBlank1()

{
	$("#btnSave").attr("disabled",true);
	preloadfadeIn();
    var validata=true;
	var errmsg="";
	var focusid="";
	if (document.getElementById('cmbParty').value=="")

	{
        validata=false;
		errmsg="Please Select Party...";
		focusid="cmbParty";
		

	}

	else if (document.getElementById("txtBillingDate").value=="")

	{
		validata=false;
		errmsg="Please Select Date...";
		focusid="txtBillingDate";


	}

	else if (document.getElementById("cmbFrom").value=="")

	{
		validata=false;
		errmsg="Please Select Company...";
		focusid="cmbFrom";


	}	

	else if (document.getElementById("cmbTo").value=="")

	{
		validata=false;
		errmsg="Please Select Party...";
		focusid="cmbTo";


	}

	else if (!(document.getElementById("txtItemName").value!="" && parseFloat(document.getElementById("txtItemQty").value)>0 && parseFloat(document.getElementById("txtItemRate").value)>0) && document.getElementById("hdBillingData").value=="")

	{
        validata=false;
		errmsg="Please Place a Bill...";
		focusid="cmbItem";
		
		
	}

	/*else if (document.getElementById("txtVat").value=="" || document.getElementById("txtVat").value=="0")

	{

		lbl.innerHTML="Please enter the VAT tax %..";

		document.getElementById('txtVat').focus();

		event.returnValue=false;

	}*/

	else if (document.getElementById("txtItemName").value!="" && (document.getElementById("txtItemQty").value=="" ||document.getElementById("txtItemRate").value==""))

	{

		validata=false;
		

		if (document.getElementById("txtItemQty").value=="")

		{
            errmsg="Please enter item quantity";
		    focusid="txtItemQty";
			

		}

		else if (document.getElementById("txtItemRate").value=="")

		{
            errmsg="Please enter item rate";
		    focusid="txtItemRate";
			

		}

		

	}									

	else if (parseInt(document.getElementById("txtItemQty").value)>0 && (document.getElementById("txtItemName").value=="" ||document.getElementById("txtItemRate").value==""))

	{

		validata=false;

		

		if (document.getElementById("txtItemName").value=="")

		{
            errmsg="Please enter item name";
		    focusid="txtItemName";
			

		}

		else if (document.getElementById("txtItemRate").value=="" || parseFloat(document.getElementById("txtItemRate").value)==0)

		{
            errmsg="Please enter item rate";
		    focusid="txtItemRate";
			

		}

		

	}

	else if (parseFloat(document.getElementById("txtItemRate").value)>0 && (document.getElementById("txtItemName").value=="" ||document.getElementById("txtItemQty").value==""))

	{

		validata=false;

		if (document.getElementById("txtItemName").value=="")

		{
            errmsg="Please enter item name";
		    focusid="txtItemName";
			
		}

		else if (document.getElementById("txtItemQty").value=="" || parseInt(document.getElementById("txtItemQty").value)==0)

		{
            errmsg="Please enter item quantity";
		    focusid="txtItemQty";
			

		}

		

	}
    if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		document.getElementById('cmbParty').disabled=false;
		$("#btnSave").attr("disabled",false);
	    preloadfadeOut();
	}
	else
	{
		document.getElementById('cmbParty').disabled=false;
		var formData = new FormData();
        		var other_data = $('#frmBilling').serializeArray();
				$.each(other_data,function(key,input){
					
        			formData.append(input.name,input.value);
        		});
				$.ajax({
					    url:'operations/save_billing_widthoutdc.php',
					    type:'POST',
					    data:formData,
						async: true,
						cache: false,
						contentType: false,
						processData: false,
						   success:function(response)
						   {
							   var responseText=JSON.parse(response);
							   if(responseText.error)
							   {
								   toastr.error(responseText.error_msg);
								   $("#btnSave").attr("disabled",true);
								   preloadfadeOut();
							   }
							   else
							   {
								   $.ajax({
								   url:'operations/billing_edit.php',
								   type:'POST',
								   data:{"auth_info":$("#auth_info").val(),"txtBillingUser":$("#txtBillingUser").val()},
								   success:function(response)
								   {
									   
									   $('.page-content').html(response);
									   preloadfadeOut();
										   
								   },
								   error:function(response)
								   {
									   
									   if(response.status=='404')
									   {
										   toastr.error("Page not found");
										   preloadfadeOut();
										   
									   }
									   else if(response.status=='500')
									   {
										   toastr.error("Internal server error");
										   preloadfadeOut();
										   
									   }
									   else
									   {
										   toastr.error("Communication error");
										   preloadfadeOut();
										   
									   }
								   }
	})
							   }
						   },error(response)
						   {
							   if(response.text=='404')
							   {
								   toastr.error("Page not found");
							   }
							   else if(response.text=='500')
							   {
								   toastr.error("Internal server error");
							   }
						   }
				})
	}

}



function TestDuplicate(OriginalArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType , PartyPartType, Pcs)

{

	//alert ('teeth' + Teeth + '1--'+ PrMeasure+'2---'+PrType+'3----'+Dia+'4-----'+DiaMeasure+'5---'+ Face+'6----'+FaceType+'7---'+ ItemType+'8----'+ ItemName+'9---'+ Pitch+'10---'+PitchType +'11---'+ PartyPartType+'12-----'+ Pcs);

	returnval=true;

	for (i=0;i<OriginalArr.length;i++)

	{

		if (OriginalArr[i][2]==Teeth && OriginalArr[i][3]==PrMeasure && OriginalArr[i][4]==PrType && OriginalArr[i][5]==Dia && OriginalArr[i][6]==DiaMeasure && OriginalArr[i][7]==Face && OriginalArr[i][8]==FaceType && OriginalArr[i][9]==ItemType && OriginalArr[i][11]==ItemName && OriginalArr[i][10]==Pitch && OriginalArr[i][12]==PitchType && OriginalArr[i][17]==PartyPartType && OriginalArr[i][18]==Pcs)

		{

			returnval=false;

		}

	}

	return returnval;

}



function ShowSpecialItem()

{

	win = new Window({className: "alphacube", title: "Special Item Detail", top:80, left:50, width:500, height:350,url: "operations/show_special_items.php", showEffectOptions: {duration:1.5}});

	win.show();

}



function selPartType()

{

	PartyIdType=(document.getElementById("cmbParty").value).split("~ArrayItem~");

	document.getElementById("cmbGearPartyType").value=PartyIdType[2];

	document.getElementById("cmbPinionPartyType").value=PartyIdType[2];

	document.getElementById("cmbShaftPinionPartyType").value=PartyIdType[2];

	document.getElementById("cmbBevelGearPartyType").value=PartyIdType[2];

	document.getElementById("cmbBevelPinionPartyType").value=PartyIdType[2];

	document.getElementById("cmbChainGearPartyType").value=PartyIdType[2];

}
$("#btnLogin").click(function(){
	var validata=true;
	var errmsg="";
	var focusid="";
	$("#btnLogin").attr("disabled",true);
	if($("#username").val()==='')
	{
		validata=false;
		errmsg="Please enter username";
		focusid="username";
	}
	else if($("#password").val()==='')
	{
		validata=false;
		errmsg="please enter password";
		focusid="password";
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		$("#btnLogin").attr("disabled",false);
	}
	else
	{
		preloadfadeIn();
		$.ajax({
			   url:'../general/login2.php',
			   type:'POST',
			   data:{"auth_info":$("#auth_info").val(),"username":$("#username").val(),"password":$("#password").val()},
			   success:function(response)
			   {
				   
				   var responseText=JSON.parse(response);
				   if(responseText.error)
				   {
					   toastr.error(responseText.error_msg);
					   $("#btnLogin").attr("disabled",false);
					   preloadfadeOut();
				   }
				   else
				   {
					    //toastr.success(responseText.error_msg);
					    $("#btnLogin").attr("disabled",false);
					    $('.bs-example-modal-center').modal('hide');
						$('body').removeClass('modal-open');
						$('body').css('padding-right', '0px');
						$('.modal-backdrop').remove();
						$.ajax({
							   url:'operations/billing_edit.php',
							   type:'POST',
							   data:{"auth_info":$("#auth_info").val(),"txtBillingUser":responseText.billing_type},
							   success:function(response)
							   {
								   $('.page-content').html(response);
								   preloadfadeOut();
							   },
							   error:function()
							   {
								   toastr.error("communication error please try again later");
								   preloadfadeOut();
							   }
						})
				   }
			   },error:function(xhttpStatus)
			   {
			       //alert(JSON.stringify(xhttpStatus.statusText));
				   toastr.error(xhttpStatus.statusText);
				   preloadfadeOut();
			   }
		})
	}
})
