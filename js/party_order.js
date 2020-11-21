var DeliveryArr=new Array();
var ItemArr=new Array();
var OrderArr=new Array();
var ModCount=-1;
var url;

function PageLoad2()
{
	if (document.getElementById("hdPageMode").value=="Edit")
	{
		lbl1=document.getElementById('lblPartyType');
		DispalyEdit();
		PartyIdType=(document.getElementById("cmbParty").value).split("~ArrayItem~");
		lbl1.innerHTML=PartyIdType[1];
		document.getElementById("cmbGearPartyType").value=PartyIdType[1];
		document.getElementById("cmbPinionPartyType").value=PartyIdType[1];
		document.getElementById("cmbShaftPinionPartyType").value=PartyIdType[1];
		document.getElementById("cmbBevelGearPartyType").value=PartyIdType[1];
		document.getElementById("cmbBevelPinionPartyType").value=PartyIdType[1];
		document.getElementById("cmbChainGearPartyType").value=PartyIdType[1];
	}
	else
	{
		document.getElementById('cmbParty').focus();
		document.getElementById("cmbItem").disabled=true;
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
		document.getElementById("btnAddChainWheel").disabled=true;
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
		document.getElementById("txtFromDate").disabled=true;
		document.getElementById("txtToDate").disabled=true;
	}
	else
	{
		document.getElementById("cmbSelParty").disabled=true;
		document.getElementById("txtFromDate").disabled=false;
		document.getElementById("txtToDate").disabled=false;
	}
}

function calculate (Pcs, Rate, Teeth, ItemType, Category, PartType)
{
	MinValue=parseFloat(document.getElementById("hdMinValue").value);
	var IPrice=0;
	var Price=0;
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
			
			/*if (Price<MinValue)
			{
				Price=MinValue;
			}*/
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
		if (ItemType=="Single")
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
		}
		Price=parseInt(Pcs)* parseInt(Teeth)*parseFloat(Rate);
	}
	else if (Category=="Worm Gear")
	{
		Price=parseInt(Pcs)*parseFloat(Rate);
	}
	Price=Price.toFixed(2);
	return Price;
}

function Switch(Id)
{
	
	if (document.getElementById(Id).value=="Pending")
	{
		OrderArr.length=0;
		document.getElementById("txtRemarks").value="";
		document.getElementById("txtChallan").value="";
		document.getElementById("dvOrderItem").innerHTML="";
		document.getElementById("cmbParty").value="";
		document.getElementById("hdOrderData").value="";
		document.getElementById("hdOrderItemRate").value="";
		document.getElementById("hdLastOrder").value="";
		document.getElementById("dvLastOrder").innerHTML="";
		document.getElementById("cmbParty").disabled=false;
		//document.getElementById('dvPendingOrder').style['display']="inline";
		DispOrderInfo();
		
		MenuClick2($("#auth_info").val(),btoa(btoa("operations/party_order_edit.php")));
		
		//document.location.href="../operations/party_order_edit.php";
	}
	else
	{
		OrderArr.length=0;
		MenuClick2($("#auth_info").val(),btoa(btoa("operations/party_order.php")));
		//document.location.href="../operations/party_order.php";
	}
}

function ShowOrder(OrderID)
{
	if (OrderID!="")
	{
		var http=false;
		url="operations/party_order_all_order.php?OrderID="+OrderID;
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
					opt=(http.responseText).split('~Array~');	
					if (OrderArr.length>0)
					{
						OrderArr.length=0;
					}
					
					for (x=0;x<opt.length ;x++ )
					{
						opts=opt[x].split('~ArrayItem~');
						len=OrderArr.length;
						OrderArr[len]=new Array();
						OrderArr[len][0]=opts[0];
						OrderArr[len][1]=opts[1];
						OrderArr[len][2]=opts[2];
						OrderArr[len][3]=opts[3];
						OrderArr[len][4]=opts[4];
						OrderArr[len][4]=opts[4];
						OrderArr[len][5]=opts[5];
						OrderArr[len][6]=opts[6];
						OrderArr[len][7]=opts[7];
						OrderArr[len][8]=opts[8];
						OrderArr[len][9]=opts[9];
						OrderArr[len][10]=opts[10];
						OrderArr[len][11]=opts[11];
						OrderArr[len][12]=opts[12];
						OrderArr[len][13]="";
						OrderArr[len][14]="Edit";
						OrderArr[len][15]=opts[13]; // Item Code
						OrderArr[len][16]=opts[14]; // Item Remarks
					}
					DispOrderInfo();
				}
			}
		}
		http.send(null);	
	}
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
		//alert (val[1]+ val[2]+val[3]+ val[4]+ val[5]);
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
}

function CheckBlankOrder(Id)
{
	var validata=true;
	var focusid="";
	var errmsg="";
	
	var add=false;
	// lbl=document.getElementById('lblErrMsg');
	// lbl.innerHTML="";
	PartyType=(document.getElementById("cmbParty").value).split("~ArrayItem~");
	PartyID=PartyType[0];
	//PartyPartType=PartyType[1];
	
	if (Id=="btnAddGear")
	{
		if (document.getElementById("txtGearTeeth").value=="" || (parseInt(document.getElementById('txtGearTeeth').value)==0))
		{
			validata=false;
			errmsg="Please enter Teeth...";
			focusid="txtGearTeeth";
			// lbl.innerHTML="Please enter Teeth...";	
			// document.getElementById('txtGearTeeth').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtGearDia").value=="" || (parseFloat(document.getElementById("txtGearDia").value)==0))
		{
			validata=false;
			errmsg="Please enter Dia...";
			focusid="txtGearDia";
			// lbl.innerHTML="Please enter Dia...";	
			// document.getElementById('txtGearDia').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtGearFace").value=="" || (parseFloat(document.getElementById("txtGearFace").value)==0))
		{
			validata=false;
			errmsg="Please enter Face...";
			focusid="txtGearFace";
			
			// lbl.innerHTML="Please enter Face...";	
			// document.getElementById('txtGearFace').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtGearProcessing").value=="")
		{
			validata=false;
			errmsg="Please enter Processing Type...";
			focusid="txtGearProcessing";
			// lbl.innerHTML="Please enter Processing Type...";	
			// document.getElementById('txtGearProcessing').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById('txtGearPcs').value=="" || (parseInt(document.getElementById('txtGearPcs').value)==0))
		{
			validata=false;
			errmsg="Please enter Pcs...";
			focusid="txtGearPcs";
			// lbl.innerHTML="Please enter Pcs...";	
			// document.getElementById('txtGearPcs').focus();
			// event.returnValue=false;
		}
		if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
			event.returnValue=false;
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
		if (document.getElementById("txtPinionTeeth").value=="" || (parseInt(document.getElementById('txtPinionTeeth').value)==0))
		{
			validata=false;
			focusid="txtPinionTeeth";
			errmsg="Please enter Teeth...";
			// lbl.innerHTML="Please enter Teeth...";	
			// document.getElementById('txtPinionTeeth').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtPinionDia").value=="" || (parseFloat(document.getElementById("txtPinionDia").value)==0))
		{
			validata=false;
			focusid="txtPinionDia";
			errmsg="Please enter Dia...";
			
			// lbl.innerHTML="Please enter Dia...";	
			// document.getElementById('txtPinionDia').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtPinionFace").value=="" || (parseFloat(document.getElementById("txtPinionFace").value)==0))
		{
			validata=false;
			focusid="txtPinionFace";
			errmsg="Please enter Face...";
			
			// lbl.innerHTML="Please enter Face...";	
			// document.getElementById('txtPinionFace').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtPinionProcessing").value=="")
		{
			validata=false;
			focusid="txtPinionProcessing";
			errmsg="Please enter Processing Type...";
			
			// lbl.innerHTML="Please enter Processing Type...";	
			// document.getElementById('txtPinionProcessing').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById('txtPinionPcs').value=="" || (parseInt(document.getElementById('txtPinionPcs').value)==0))
		{
			validata=false;
			focusid="txtPinionPcs";
			errmsg="Please enter Pcs...";
			// lbl.innerHTML="Please enter Pcs...";	
			// document.getElementById('txtPinionPcs').focus();
			// event.returnValue=false;
		}
		if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
			event.returnValue=false;
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
				url="operations/party_order_pppinion_rate.php?Teeth="+teeth+"&Type="+type+"&Dia="+dia+"&DiaType="+diatype+"&Face="+face+"&FaceType="+facetype+"&DMValue="+dmvalue+"&DMValueType="+dmvaluetype+"&PartyID="+PartyID;
		}
	}
	else if (Id=="btnAddShaftPinion")
	{
		if (document.getElementById("txtShaftPinionTeeth").value=="" || (parseInt(document.getElementById('txtShaftPinionTeeth').value)==0))
		{
			validata=false;
			focusid="txtShaftPinionTeeth";
			errmsg="Please enter Teeth...";
			
			// lbl.innerHTML="Please enter Teeth...";	
			// document.getElementById('txtShaftPinionTeeth').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtShaftPinionDia").value=="" || (parseFloat(document.getElementById("txtShaftPinionDia").value)==0))
		{
			validata=false;
			focusid="txtShaftPinionDia";
			errmsg="Please enter Dia...";
			// lbl.innerHTML="Please enter Dia...";	
			// document.getElementById('txtShaftPinionDia').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtShaftPinionFace").value=="" || (parseFloat(document.getElementById("txtShaftPinionFace").value)==0))
		{
			validata=false;
			focusid="txtShaftPinionFace";
			errmsg="Please enter Face...";
			// lbl.innerHTML="Please enter Face...";	
			// document.getElementById('txtShaftPinionFace').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtShaftPinionProcessing").value=="")
		{
			validata=false;
			focusid="txtShaftPinionProcessing";
			errmsg="Please enter Processing Type...";
			
			// lbl.innerHTML="Please enter Processing Type...";	
			// document.getElementById('txtShaftPinionProcessing').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById('txtShaftPinionPcs').value=="" || (parseInt(document.getElementById('txtShaftPinionPcs').value)==0))
		{
			validata=false;
			focusid="txtShaftPinionPcs";
			errmsg="Please enter Pcs...";
			// lbl.innerHTML="Please enter Pcs...";	
			// document.getElementById('txtShaftPinionPcs').focus();
			// event.returnValue=false;
		}
		if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
			event.returnValue=false;
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
		
		if (document.getElementById("txtBevelGearTeeth").value=="" || (parseInt(document.getElementById('txtBevelGearTeeth').value)==0))
		{
			validata=false;
			focusid="txtBevelGearTeeth";
			errmsg="Please enter Teeth...";
			// lbl.innerHTML="Please enter Teeth...";	
			// document.getElementById('txtBevelGearTeeth').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtBevelGearDia").value=="" || (parseFloat(document.getElementById("txtBevelGearDia").value)==0))
		{
			validata=false;
			focusid="txtBevelGearDia";
			errmsg="Please enter Dia...";
			
			// lbl.innerHTML="Please enter Dia...";	
			// document.getElementById('txtBevelGearDia').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtBevelGearProcessing").value=="")
		{
			validata=false;
			focusid="txtBevelGearProcessing";
			errmsg="Please enter Processing Type...";
			// lbl.innerHTML="Please enter Processing Type...";	
			// document.getElementById('txtBevelGearProcessing').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById('txtBevelGearPcs').value=="" || (parseInt(document.getElementById('txtBevelGearPcs').value)==0))
		{
			validata=false;
			focusid="txtBevelGearPcs";
			errmsg="Please enter Pcs...";
			
			// lbl.innerHTML="Please enter Pcs...";	
			// document.getElementById('txtBevelGearPcs').focus();
			// event.returnValue=false;
		}
		if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
			event.returnValue=false;
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
		if (document.getElementById("txtBevelPinionTeeth").value=="" || (parseInt(document.getElementById('txtBevelPinionTeeth').value)==0))
		{
			validata=false;
			focusid="txtBevelPinionTeeth";
			errmsg="Please enter Teeth...";
			
			// lbl.innerHTML="Please enter Teeth...";	
			// document.getElementById('txtBevelPinionTeeth').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtBevelPinionDia").value=="" || (parseFloat(document.getElementById("txtBevelPinionDia").value)==0))
		{
			validata=false;
			focusid="txtBevelPinionDia";
			errmsg="Please enter Dia...";
			
			// lbl.innerHTML="Please enter Dia...";	
			// document.getElementById('txtBevelPinionDia').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtBevelPinionProcessing").value=="")
		{
			validata=false;
			focusid="txtBevelPinionProcessing";
			errmsg="Please enter Processing Type...";
			// lbl.innerHTML="Please enter Processing Type...";	
			// document.getElementById('txtBevelPinionProcessing').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById('txtBevelPinionPcs').value=="" || (parseInt(document.getElementById('txtBevelPinionPcs').value)==0))
		{
			validata=false;
			focusid="txtBevelPinionPcs";
			errmsg="Please enter Pcs...";
			
			// lbl.innerHTML="Please enter Pcs...";	
			// document.getElementById('txtBevelPinionPcs').focus();
			// event.returnValue=false;
		}
		if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
			event.returnValue=false;
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
		if (document.getElementById("txtChainWheelTeeth").value=="" || (parseInt(document.getElementById('txtChainWheelTeeth').value)==0))
		{
			validata=false;
			errmsg="Please enter Teeth...";
			focusid="txtChainWheelTeeth";
			
			// lbl.innerHTML="Please enter Teeth...";	
			// document.getElementById('txtChainWheelTeeth').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtChainWheelDia").value=="" || (parseFloat(document.getElementById("txtChainWheelDia").value)==0))
		{
			validata=false;
			errmsg="Please enter Dia...";
			focusid="txtChainWheelDia";
			
			// lbl.innerHTML="Please enter Dia...";	
			// document.getElementById('txtChainWheelDia').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtChainWheelPitch").value=="" || (parseFloat(document.getElementById("txtChainWheelPitch").value)==0))
		{
			validata=false;
			errmsg="Please enter Pitch...";
			focusid="txtChainWheelPitch";
			
			// lbl.innerHTML="Please enter Pitch...";	
			// document.getElementById('txtChainWheelPitch').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById('txtChainWheelPcs').value=="" || (parseInt(document.getElementById('txtChainWheelPcs').value)==0))
		{
			validata=false;
			errmsg="Please enter Pcs...";
			focusid="txtChainWheelPcs";
			
			// lbl.innerHTML="Please enter Pcs...";	
			// document.getElementById('txtChainWheelPcs').focus();
			// event.returnValue=false;
		}
		if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
			event.returnValue=false;
		}
		else
		{
			add=true;
			pitchvalue=document.getElementById("txtChainWheelPitch").value;
			pitchtype=document.getElementById("cmbChainWheelPitchType").value;
			PartyPartType=document.getElementById("cmbChainGearPartyType").value;
			pcs=document.getElementById("cmbChainWheelCal").value
			Itemtype=document.getElementById('cmbChainWheelType').value;
			if (PartyPartType=="Expeller")
				url="operations/party_order_chaingear_rate.php?PitchValue="+pitchvalue+"&PitchType="+pitchtype+"&PartyID="+PartyID + "&ItemType="+Itemtype;
			else
				url="operations/party_order_ppchaingear_rate.php?PitchValue="+pitchvalue+"&PitchType="+pitchtype+"&PartyID="+PartyID+ "&ItemType="+Itemtype;
		}
	}
	else if (Id=="btnAddWormGear")
	{
		if (document.getElementById("txtWormGearTeeth").value=="" || (parseInt(document.getElementById('txtWormGearTeeth').value)==0))
		{
			validata=false;
			errmsg="Please enter Teeth...";
			focusid="txtWormGearTeeth";
			
			// lbl.innerHTML="Please enter Teeth...";	
			// document.getElementById('txtWormGearTeeth').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtWormGearDia").value=="" || (parseFloat(document.getElementById("txtWormGearDia").value)==0))
		{
			validata=false;
			errmsg="Please enter Dia...";
			focusid="txtWormGearDia";
			
			// lbl.innerHTML="Please enter Dia...";	
			// document.getElementById('txtWormGearDia').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById("txtWormGearProcessing").value=="")
		{
			validata=false;
			errmsg="Please enter Processing Type...";
			focusid="txtWormGearProcessing";
			
			// lbl.innerHTML="Please enter Processing Type...";	
			// document.getElementById('txtWormGearProcessing').focus();
			// event.returnValue=false;
		}
		else if (document.getElementById('txtWormGearPcs').value=="" || (parseInt(document.getElementById('txtWormGearPcs').value)==0))
		{
			validata=false;
			errmsg="Please enter Pcs...";
			focusid="txtWormGearPcs";
			
			// lbl.innerHTML="Please enter Pcs...";	
			// document.getElementById('txtWormGearPcs').focus();
			// event.returnValue=false;
		}
		if(validata==false)
		{
			toastr.error(errmsg);
			$("#"+focusid).focus();
			event.returnValue=false;
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
	// lbl=document.getElementById('lblErrMsg');
	
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
		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType ,PartyPartType ,TypeCalculation)==true)
		{
			len=OrderArr.length;
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
			OrderArr[len][17]=PartyPartType;	// Power Press or Expeller Parts
			OrderArr[len][18]=document.getElementById("cmbGearCal").value;               // PT or PCS 
			OrderArr[len][19]="";
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
		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType , PartyPartyType,Pcs )==true)
		{
			len=OrderArr.length;
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
			OrderArr[len][19]="";
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
		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType, PartyPartyType, Pcs)==true)
		{
			len=OrderArr.length;
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
			OrderArr[len][19]="";
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
		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType, PartyPartyType, Pcs )==true)
		{
			len=OrderArr.length;
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
			OrderArr[len][19]="";
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
		if (TestDuplicate(OrderArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType, PartyPartyType, Pcs )==true)
		{
			len=OrderArr.length;
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
			OrderArr[len][19]="";
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
			OrderArr[len][19]="";
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

function DispalyEdit()
{
	opt=(document.getElementById("hdOrderData").value).split('~Array~');	
	if (OrderArr.length>0)
	{
		OrderArr.length=0;
	}
	for (x=0;x<opt.length ;x++ )
	{
		opts=opt[x].split('~ArrayItem~');
		len=OrderArr.length;
		OrderArr[len]=new Array();
		OrderArr[len][0]=opts[0];
		OrderArr[len][1]=opts[1];
		OrderArr[len][2]=opts[2];
		OrderArr[len][3]=opts[3];
		OrderArr[len][4]=opts[4];
		OrderArr[len][4]=opts[4];
		OrderArr[len][5]=opts[5];
		OrderArr[len][6]=opts[6];
		OrderArr[len][7]=opts[7];
		OrderArr[len][8]=opts[8];
		OrderArr[len][9]=opts[9];
		OrderArr[len][10]=opts[10];
		OrderArr[len][11]=opts[11];
		OrderArr[len][12]=opts[12];
		OrderArr[len][13]="";
		OrderArr[len][14]="Edit";
		OrderArr[len][15]=opts[13]; // Item Code
		OrderArr[len][16]=opts[14]; // Item Remarks
		OrderArr[len][17]=opts[15]; // Expeller Parts / Power Press Parts
		OrderArr[len][18]=opts[16]; // PT or PCS
		OrderArr[len][19]=opts[17]; // Collar
	}
	DispOrderInfo();
}

function DispOrderInfo()
{
	OrderItem=document.getElementById("dvOrderItem");
	OrderItem.innerHTML="";
	var x;
	var j=0;
	
	var TotalPrice=0;
	var Data="<table class=\"table table-bordered\">";
	Data+="<thead><tr>";
	Data+="<th width=\"7%\">SNO</th>";
	Data+="<th width=\"50%\">Item Name</th>";
	Data+="<th width=\"20%\">Rate</th>";
	Data+="<th width=\"13%\">Price</label></th>";
	Data+="<th width=\"10%\">Options</th>";
	Data+="</tr></thead><tbody>";
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
				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth "+OrderArr[x][9]+" gear dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;face "+OrderArr[x][7]+"&nbsp;"+OrderArr[x][8]+ " ("+OrderArr[x][3]+"&nbsp;"+OrderArr[x][4]+") @ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";
			}
			else if (OrderArr[x][11]=="Pinion")
			{
				/*Data+="<td class=\"tablecell\" colspan=\"8\"><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth "+OrderArr[x][9]+" pinion dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;face "+OrderArr[x][7]+"&nbsp;"+OrderArr[x][8]+ " ("+OrderArr[x][3]+"&nbsp;"+OrderArr[x][4]+") @ "+(parseFloat(OrderArr[x][1])).toFixed(2);
				if (OrderArr[x][19]!="")
					Data+=" Collar "+OrderArr[x][19];*/
				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth "+OrderArr[x][9]+" pinion dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;face "+OrderArr[x][7];
				if (OrderArr[x][19]!="")
					Data+=" + "+OrderArr[x][19];
				Data+="&nbsp;"+OrderArr[x][8]+ " ("+OrderArr[x][3]+"&nbsp;"+OrderArr[x][4]+") @ "+(parseFloat(OrderArr[x][1])).toFixed(2);
				Data+="<br>";	
			}
			else if (OrderArr[x][11]=="Shaft Pinion")
			{
				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth "+OrderArr[x][9]+" shaft pinion dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;face "+OrderArr[x][7]+"&nbsp;"+OrderArr[x][8]+ " ("+OrderArr[x][3]+"&nbsp;"+OrderArr[x][4]+") @ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";
			}
			else if (OrderArr[x][11]=="Bevel Gear")
			{
				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;("+OrderArr[x][3]+"&nbsp;"+OrderArr[x][4]+") @ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";
			}
			else if (OrderArr[x][11]=="Bevel Pinion")
			{
				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;("+OrderArr[x][3]+"&nbsp;"+OrderArr[x][4]+") @ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";
			}
			else if (OrderArr[x][11]=="Chain Wheel")
			{
				/*if (OrderArr[x][9]=="Single")
					fac=1;
				else if (OrderArr[x][9]=="Duplex")
					fac=2;
				else if (OrderArr[x][9]=="Triplex")
					fac=3;
				else 
					fac=4;
				itemrate= parseFloat(OrderArr[x][1]) * fac;
				OrderArr[x][1]=itemrate;*/
				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth ("+OrderArr[x][9]+") dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;pitch "+OrderArr[x][10]+"&nbsp;"+OrderArr[x][12]+ " @ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";
			}
			else if (OrderArr[x][11]=="Worm Gear")
			{
				Data+="<td><input type=\"Text\" id=\""+txtPcs+"\" name=\""+txtPcs+"\" value=\""+OrderArr[x][0]+"\" size=\"2\" onChange=\"FillPcs(this.id,"+x+")\" onKeyDown=\"OnlyInt1(this.id)\">&nbsp;"+OrderArr[x][11]+"&nbsp;"+OrderArr[x][2]+"&nbsp;teeth dia &nbsp;"+OrderArr[x][5]+"&nbsp;"+OrderArr[x][6]+"&nbsp;("+OrderArr[x][3]+"&nbsp;"+OrderArr[x][4]+") @ "+(parseFloat(OrderArr[x][1])).toFixed(2)+"<br>";				
			}
			
			txtRemarks="txtRemarks"+x;
			Data+="<b>Remarks :</b> <input type=\"text\" name=\""+txtRemarks+"\" id=\""+txtRemarks+"\" value=\""+OrderArr[x][16]+"\" size=\"35\" onChange=\"FillRemarks(this.id,"+x+")\"></td>";
			txt="txtRate"+x;
			Data+="<td><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" style=\"text-align:right;\" value=\""+OrderArr[x][1]+"\" size=\"7\" onChange=\"FillOrderArr(this.id,"+x+")\">("+OrderArr[x][18]+")</td>";
			
			if (OrderArr[x][11]=="Shaft Pinion" || OrderArr[x][11]=="Worm Gear")
			{
				if (OrderArr[x][18]=="PCS")
					OrderArr[x][13]=calculate (OrderArr[x][0], OrderArr[x][1], OrderArr[x][2], OrderArr[x][9], OrderArr[x][11],OrderArr[x][17] );
				else
					OrderArr[x][13]=parseFloat(OrderArr[x][0]* OrderArr[x][1] * OrderArr[x][2]);
			}
			else
			{
				
				if (OrderArr[x][18]=="PT")
				{
					Price=calculate (OrderArr[x][0], OrderArr[x][1], OrderArr[x][2], OrderArr[x][9], OrderArr[x][11],OrderArr[x][17] );
					OrderArr[x][13]=Price;
				}
				else
				{
					MinValue=parseFloat(document.getElementById("hdMinValue").value);
					Price=parseFloat(OrderArr[x][0]* OrderArr[x][1]);
					
					if (OrderArr[x][17]=="PowerPress" && OrderArr[x][11]=="Pinion"  && OrderArr[x][1]<MinValue)
					{
						Price=parseFloat(OrderArr[x][0]*MinValue);
					}
					OrderArr[x][13]=Price;
				}
			}
			Data+="<td style=\"text-align:right;\">"+(parseFloat(OrderArr[x][13])).toFixed(2)+"</td>";
			btn="btn"+x;
			Data+="<td><input type=\"button\" value=\"Delete\" name=\""+btn+"\" id=\""+btn+"\" class=\"btn btn-primary waves-effect waves-light\" onBlur=\"ShiftDelete("+x+")\" onclick=\"Delete("+x+")\"></td></tr>";
			TotalPrice=parseFloat(TotalPrice) + parseFloat(OrderArr[x][13]);
			j++;	
		}
	}
	Data+="<tr><td></td><td></td><td style=\"text-align:right;\">Total value :</td><td style=\"text-align:right;\">"+(TotalPrice).toFixed(2)+"</td><td></td></tr>";
	Data=Data+"</tbody></table>";
	OrderItem.innerHTML=Data;
	
	concatData();
	if (OrderArr.length>0)
	{
		num=(OrderArr.length - 1);
		Name="txtPcs"+num;
		do{
			// document.getElementById(Name).focus();
		}while(document.getElementById(Name).focus());
	}
	
}

function ShiftDelete(pos)
{
	x=OrderArr.length ;
	x=x-1;
	if (x==pos)
	{
		document.getElementById("txtRemarks").focus();
	}
}

function FillPcs(Id,x)
{
	var validata=true;
	var errmsg="";
	var focusid="";
	val=parseInt(document.getElementById(Id).value);
	if (val==0)
	{
		validata=true;
		errmsg="Pcs cannot be zero...";
		focusid=Id;
		// lbl.innerHTML="Pcs cannot be zero...";
		// document.getElementById(Id).focus();
		// event.returnValue=false;
	}
	else if (isNaN(val))
	{
		validata=true;
		errmsg="value cannot be blank...";
		focusid=Id;
		// lbl.innerHTML="value cannot be blank...";
		// document.getElementById(Id).focus();
		// event.returnValue=false;
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
	}
	else
	{
		OrderArr[x][0]=val;
		DispOrderInfo();
	}
}

function FillOrderArr(Id,x)
{
	var validata=true;
	var errmsg="";
	var focusid="";
	
	val=parseFloat(document.getElementById(Id).value);
	if (val==0)
	{
		validata=false;
		errmsg="Rate cannot be zero...";
		focusid=Id;
		// lbl.innerHTML="Rate cannot be zero...";
		// document.getElementById(Id).focus();
		// event.returnValue=false;
	}
	else if (isNaN(val))
	{
		validata=false;
		errmsg="Rate cannot be blank...";
		focusid=Id;
		
		// lbl.innerHTML="Rate cannot be blank...";
		// document.getElementById(Id).focus();
		// event.returnValue=false;
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		event.returnValue=false;
	}
	else
	{
		OrderArr[x][1]=val;
		DispOrderInfo();
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
	if (document.getElementById("hdPageMode").value=="Edit" && OrderArr[r1][14]=="Edit")
	{
		OrderArr[r1][14]="Deleted";
	}
	else
	{
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
	}
	DispOrderInfo();
}

function concatData()
{
	var packed="";
	document.getElementById("hdOrderData").value="";
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
	document.getElementById("hdOrderData").value=packed;
}

function CheckBlank()
{
	var validata=true;
	var focusid="";
	var errmsg="";
	if (document.getElementById('cmbParty').value=="")
	{
		validata=false;
		errmsg="Please Select Party...";
		focusid="cmbParty";
		// lbl.innerHTML="Please Select Party...";
		// document.getElementById('cmbParty').focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("hdOrderData").value=="")
	{
		validata=false;
		errmsg="Please Place an Order...";
		focusid="cmbItem";
		// lbl.innerHTML="Please Place an Order...";
		// document.getElementById('cmbItem').focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtDate").value=="")
	{
		validata=false;
		errmsg="Please Select Date...";
		focusid="txtDate";
		// lbl.innerHTML="Please Select Date...";
		// document.getElementById('txtDate').focus();
		// event.returnValue=false;
	}
	if (document.getElementById("rdPending").checked==true)
	{
		if (document.getElementById("cmbOrder").value=="")
		{
			validata=false;
			errmsg="Please Select the Order...";
			focusid="cmbOrder";
			// lbl.innerHTML="Please Select the Order...";
			// document.getElementById('cmbOrder').focus();
			// event.returnValue=false;
		}
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
		event.returnValue=false;
	}
	document.getElementById("cmbParty").disabled=false;
}

		// To show Last 5 Orders
function selOrder(Id)
{
	
	
	lbl1=document.getElementById('lblPartyType');
	lbl1.innerHTML="";
	// lbl2=document.getElementById('lblName');
	// lbl2.innerHTML="";
	
	OrderArr.length=0;
	document.getElementById("dvCount").style['display']="none";
	document.getElementById("dvOrderItem").innerHTML="";
	if (document.getElementById(Id).value!="")
	{
		document.getElementById("cmbItem").disabled=false;
		document.getElementById("cmbGear").disabled=false;
		document.getElementById("btnAddGear").disabled=false;
		document.getElementById("cmbPinion").disabled=false;
		document.getElementById("btnAddPinion").disabled=false;
		document.getElementById("cmbShaftPinion").disabled=false;
		document.getElementById("btnAddShaftPinion").disabled=false;
		document.getElementById("cmbBevelGear").disabled=false;
		document.getElementById("btnAddBevelGear").disabled=false;
		document.getElementById("cmbBevelPinion").disabled=false;
		document.getElementById("btnAddBevelPinion").disabled=false;
		document.getElementById("cmbChainWheel").disabled=false;
		document.getElementById("btnAddChainWheel").disabled=false;
		document.getElementById("cmbWormGear").disabled=false;
		document.getElementById("btnAddWormGear").disabled=false;
		//lbl2.innerHTML=document.getElementById(Id).options[document.getElementById(Id).selectedIndex].text;
		PartyIdType=(document.getElementById(Id).value).split("~ArrayItem~");
		PartyId=PartyIdType[0];
		lbl1.innerHTML=PartyIdType[1];
		
		document.getElementById("cmbGearPartyType").value=PartyIdType[1];
		document.getElementById("cmbPinionPartyType").value=PartyIdType[1];
		document.getElementById("cmbShaftPinionPartyType").value=PartyIdType[1];
		document.getElementById("cmbBevelGearPartyType").value=PartyIdType[1];
		document.getElementById("cmbBevelPinionPartyType").value=PartyIdType[1];
		document.getElementById("cmbChainGearPartyType").value=PartyIdType[1];
		//document.getElementById("cmbWormGearPartyType").value=PartyIdType[1];
		
		document.getElementById("btnSpecialItem").style['display']="inline";
		
		url="operations/purchase_order_getpo.php?PartyId="+PartyId;
		
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
				DeliveryArr.length=0;
				ItemArr.length=0;
				POrderItem=document.getElementById("dvLastOrder");
				POrderItem.innerHTML="";
				
				if (http.responseText=="Error")
				{
					//lbl.innerHTML="Error in getting Details...";	
				}
				else if (http.responseText=="")
				{
					//lbl.innerHTML="No Purchase Order Found...";	
				}
				else
				{
					
					document.getElementById('hdLastOrder').value=http.responseText;
					AddPODetails();
				}
			}
		}
		http.send(null);	
	}
	else
	{
		POrderItem=document.getElementById("dvLastOrder");
		POrderItem.innerHTML="";
		document.getElementById("cmbItem").disabled=true;
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
		document.getElementById("btnAddChainWheel").disabled=true;
		document.getElementById("cmbWormGear").disabled=true;
		document.getElementById("btnAddWormGear").disabled=true;
		
		lblPartyType.innerHTML="";
		document.getElementById("btnSpecialItem").style['display']="none";
	}
}

function AddPODetails()
{
	lbl=document.getElementById('lblErrMsg');
	var x;
	ItemData=(document.getElementById("hdLastOrder").value).split('~ItemArray~');
	
	for (x=0;x<ItemData.length ;x++ )
	{	
		opt=(ItemData[x]).split('~Heading~');
		caption=opt[0].split('~Caption~');			
		len1=ItemArr.length;
		ItemArr[len1]=new Array();
		ItemArr[len1][0]=caption[0];	 // Order Code
		ItemArr[len1][1]=caption[1];	 // Order date

		//alert (opt[1]);		
		opts=opt[1].split('~Array~');
		for (y=0;y<opts.length ;y++ )
		{	
			sel=(opts[y]).split('~ArrayItem~');
			len=DeliveryArr.length;
			
			DeliveryArr[len]=new Array();
			DeliveryArr[len][0]=ItemArr[len1][0];// Order Code 
			DeliveryArr[len][1]=sel[0];// Item Name
			DeliveryArr[len][2]=sel[1];// Pcs Rec
			DeliveryArr[len][3]=sel[2];// Rate
			DeliveryArr[len][4]=sel[3];// Price 
			DeliveryArr[len][5]=sel[4];// Pcs Disp 
			DeliveryArr[len][6]=sel[5];// Balance
			DeliveryArr[len][7]=sel[6];// PT or PCS
			DeliveryArr[len][8]=sel[7];// Collar
		}
	}
	DispDeliveryInfo();
}

function DispDeliveryInfo()
{
	POrderItem=document.getElementById("dvLastOrder");
	POrderItem.innerHTML="";
	var x;
	var j=0;
	var Total=0;
	var value=0;
	var TotalValue=0;
	var TotalItem=0;
	var Data="";
	if (ItemArr.length>0)
	{
		document.getElementById("dvCount").style['display']="inline";
	}
	
	dvcount=document.getElementById("dvCount");
	dvcount.innerHTML="<b>Last "+ItemArr.length+" Orders</b><br>";
	for (x=0;x<ItemArr.length ;x++ )
	{
		y=1;
		dArr=(ItemArr[x][1]).split('-');
		PODate=dArr[2]+"/"+dArr[1]+"/"+dArr[0];
		Data+="<table class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
		Data+="<tr>";
		Data+="<td><b>Date </b>" +PODate+ "</td>";
		Data+="<td><b>Purchase Order No </b>" +ItemArr[x][0]+ "</td>";
		Data+="</tr>";
   		
		//Data+="<tr><td>"+PODate+"</td><td>"+ItemArr[x][0]+"</td></tr>";
		Data+="</table>";
		Data+="<table class=\"table table-bordered dt-responsive\" width=\"100%\">";
		Data+="<thead><tr>";
		Data+="<th width=\"5%\">SNO</th>";
		Data+="<th>Item Name</th>";
		Data+="<th>Pcs Recd.</th>";
		Data+="<th>Pcs Disp.</th>";
		Data+="<th>Balance</th>";
		Data+="<th>Rate</th>";
		Data+="<th>Value</th></tr></thead><tbody>";

		for (j=0;j<DeliveryArr.length ;j++ )
		{
			if (ItemArr[x][0]==DeliveryArr[j][0])
			{
				Data+="<tr>";		
				Data+="<td>"+(y)+"</td>";
				Data+="<td>"+DeliveryArr[j][1];
				if (DeliveryArr[j][8]!="")
					Data+=" Collar :"+DeliveryArr[j][8];
				Data+="</td>";
				Data+="<td>"+DeliveryArr[j][2]+"</td>";
				Data+="<td>"+DeliveryArr[j][5]+"</td>";
				Data+="<td>"+DeliveryArr[j][6]+"</td>";
				Data+="<td>"+DeliveryArr[j][3]+"("+DeliveryArr[j][7]+")</td>";
				value=(DeliveryArr[j][4]);
				Data+="<td>"+(parseFloat(value)).toFixed(2)+"</td></tr>";
				TotalItem=	parseInt(TotalItem) + parseInt(DeliveryArr[j][2]);
				TotalValue=	 parseFloat(TotalValue) + parseFloat(value);
				y++;
			}
		}
		Data+="<tr>";		
		Data+="<td></td><td>Total :</td>";
		Data+="<td>"+TotalItem+"</td>";
		Data+="<td>&nbsp;</td>";
		Data+="<td>&nbsp;</td>";
		Data+="<td>&nbsp;</td>";
		Data+="<td>"+TotalValue.toFixed(2)+"</td></tr>";
		TotalItem=0;
		TotalValue=0;
		Data=Data+"</tbody></table>";
	}
	POrderItem.innerHTML=Data;
	POrderItem.style['height']='100%';
}

function ShowPO(OrderID)
{
	document.location.href="../operations/party_order.php?OrderID="+OrderID;
}

function PODelete(OrderID)
{
	lbl=document.getElementById('lblErrMsg');
	lbl.innerHTML="";
	var x;
	x=confirm("Are you Sure! You want to DELETE this record?");
	if (x==true)
	{
		url="operations/party_order_delete.php?OrderID="+OrderID;
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

function TestDuplicate(OriginalArr,Teeth,PrMeasure,PrType,Dia,DiaMeasure, Face,FaceType, ItemType, ItemName, Pitch,PitchType, PartyPartType, Pcs)
{
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
	var PartyID=(document.getElementById("cmbParty").value).split('~ArrayItem~');
	$('#modalSpecial').find('.modal-body').html("");

	$.ajax({
		     url:'operations/show_special_items.php',
			 type:'POST',
			 data:{"PartyID":PartyID[0]},
			 success:function(response)
			 {
				     
					 $('#modalSpecial').modal('toggle');
					 $("#modalBody").html(response);
 
			 
			 }
	})
	
}
function getData(Id)
{
	$("#btnGetData").attr("disabled",true);
	preloadfadeIn();
	var validata=true;
	var errmsg="";
	var focusid="";
	if($("#cmbSelParty").val()==='' && ($("#txtFromDate").val()==='' || $("#txtToDate").val()===''))
	{
		validata=false;
		errmsg="Please select date range or party name";
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#btnGetData").attr("disabled",false);
	    preloadfadeOut();
		
	}
	else
	{
		$.ajax({
		url:'operations/getOrderData.php',
		type:'POST',
		data:{"FromDate":$("#txtFromDate").val(),"ToDate":$("#txtToDate").val(),"PartyID":$("#cmbSelParty").val()},
		success:function(response)
		{
			   
			   var responseText=JSON.parse(response);
			   if(responseText.error)
			   {
				   toastr.error(responseText.error_msg);
				   $("#btnGetData").attr("disabled",false);
	               preloadfadeOut();
			   }
			   else
			   {
				   $("#orderData").html("");
				   $("#orderData").html(responseText.data);
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
					$("#btnGetData").attr("disabled",false);
	                preloadfadeOut();
			   }
		},error()
		{
			toastr.error("Error :Please try again Later");
		}
		})
	}
	
    //lbl=document.getElementById('lblErrMsg');
    // lbl.innerHTML="";
    // FromDate=document.getElementById('txtFromDate').value;
    // ToDate=document.getElementById('txtToDate').value;
    // Party=document.getElementById("cmbSelParty").value;
    //document.location.href="../operations/party_order_edit.php?Type=Date&FromDate="+FromDate+"&ToDate="+ToDate+"&PartyID="+Party ;

}
$("#btnOrder").click(function(){
	var validata=true;
	var errmsg="";
	var focusid="";
	if($("#cmbParty").val()==='')
	{
		validata=false;
		errmsg="Please select Party";
		focusid="cmbParty";
	}
	else if($("#hdOrderData").val()==='')
	{
		validata=false;
		errmsg="Please place an order";
		focusid="hdOrderData";
	}
	else if($("#txtDate").val()==='')
	{
		validata=false;
		errmsg="Please Select Date...";
		focusid="txtDate";
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
	}
	else
	{
		       
		var formData = new FormData();
        		var other_data = $('#frmChallanOrder').serializeArray();
				$.each(other_data,function(key,input){
					
        			formData.append(input.name,input.value);
        		});		
				
		$.ajax({
					url:'operations/saveOrderData.php',
					type:'POST',
					data:{"auth_info":$("#auth_info").val(),"cmbParty":$("#cmbParty").val(),"hdOrderData":$("#hdOrderData").val(),"txtDate":$("#txtDate").val(),"hdPageMode":$("#hdPageMode").val(),"cmbOrder":$("#cmbOrder").val(),"txtChallan":$("#txtChallan").val(),"txtRemarks":$("#txtRemarks").val(),"rdb":$("#rdb").val(),"hdMinValue":$("#hdMinValue").val(),"hdOrderId":$("#hdOrderId").val(),"hdExtra":$("#hdExtra").val(),"hdDatabase":$("#hdDatabase").val()},
					
					
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
							MenuClick2($("#auth_info").val(),btoa(btoa("operations/party_order_edit.php")));
							
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
	
})
function resetForm()
{
   preloadfadeIn();
	$.ajax({
		    url:'operations/party_order.php',
			type:'POST',
			data:{"auth_info":$("#auth_info").val(),"Data":$("#hdOrderId").val()},
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