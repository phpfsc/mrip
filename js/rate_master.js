$("document").ready(function(){
	Pageload();
})
var GearArray=new Array();
var GearHelicalArray=new Array();
var ShaftPinionArray=new Array();
var ShaftPHelicalArray=new Array();
var ChainGearArray=new Array();
var BevelGearArray= new Array();
var GearModCount=-1;
var GearHelicalModCount=-1;
var ShaftPinionModCount=-1;
var ShaftPHelicalModCount=-1;
var ChainGearModCount=-1;
var BevelGearModCount=-1;

function Pageload()
{
	DispalyGear();
}

		// Plain Gear Starts here

function AddGearRate()
{	
	validata=true;
	errmsg="";
	focusid="";
	
	if (document.getElementById("txtGearProcessingPlain").value=="" || parseFloat(document.getElementById("txtGearProcessingPlain").value)==0)
	{
		validata=false;
		errmsg="Please enter Gear Processing Type...";
		focusid="txtGearProcessingPlain";
		// lbl.innerHTML="Please enter Gear Processing Type...";
		// document.getElementById("txtGearProcessingPlain").focus();
		// event.returnValue=false;
	}	
	else if (document.getElementById("txtGearFaceFromPlain").value=="" )
	{
		validata=false;
		errmsg="Please enter Gear Face From...";
		focusid="txtGearFaceFromPlain";
		
		// lbl.innerHTML="Please enter Gear Face From...";
		// document.getElementById("txtGearFaceFromPlain").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtGearFaceToPlain").value=="" || parseFloat(document.getElementById("txtGearFaceToPlain").value)==0)
	{
		validata=false;
		errmsg="Please enter Gear Face To...";
		focusid="txtGearFaceToPlain";
		// lbl.innerHTML="Please enter Gear Face To...";
		// document.getElementById("txtGearFaceToPlain").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtGearRatePlain").value=="" || parseFloat(document.getElementById("txtGearRatePlain").value)==0)
	{
		validata=false;
		errmsg="Please enter Gear Rate...";
		focusid="txtGearRatePlain";
		// lbl.innerHTML="Please enter Gear Rate...";
		// document.getElementById("txtGearRatePlain").focus();
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
		if (document.getElementById("btnGearAddPlain").value=="Add")
		{
			lenGear=GearArray.length;
			GearArray[lenGear]=[];
			GearArray[lenGear][0]="0";
			GearArray[lenGear][1]=parseFloat(document.getElementById("txtGearProcessingPlain").value);
			GearArray[lenGear][2]=document.getElementById("cmbGearProcessingTypePlain").value;
			GearArray[lenGear][3]=parseFloat(document.getElementById("txtGearFaceFromPlain").value);
			GearArray[lenGear][4]=parseFloat(document.getElementById("txtGearFaceToPlain").value);
			GearArray[lenGear][5]=document.getElementById("cmbGearFaceTypePlain").value;
			GearArray[lenGear][6]=parseFloat(document.getElementById("txtGearRatePlain").value);
			GearArray[lenGear][7]="New";
		}
		else
		{
			GearArray[GearModCount][1]=parseFloat(document.getElementById("txtGearProcessingPlain").value);
			GearArray[GearModCount][2]=document.getElementById("cmbGearProcessingTypePlain").value;
			GearArray[GearModCount][3]=parseFloat(document.getElementById("txtGearFaceFromPlain").value);
			GearArray[GearModCount][4]=parseFloat(document.getElementById("txtGearFaceToPlain").value);
			GearArray[GearModCount][5]=document.getElementById("cmbGearFaceTypePlain").value;
			GearArray[GearModCount][6]=parseFloat(document.getElementById("txtGearRatePlain").value);
			document.getElementById("btnGearAddPlain").value="Add"
		}
		DispGearInfo();
		document.getElementById("txtGearProcessingPlain").value="";
		document.getElementById("txtGearFaceFromPlain").value="";
		document.getElementById("txtGearFaceToPlain").value="";
		document.getElementById("txtGearRatePlain").value="";
	}
}

function DispalyGear()
{
	if(document.getElementById('hdGearRatePlain').value!='')
	{
		data=(document.getElementById('hdGearRatePlain').value).split('~Array~');
		for(x=0;x<data.length;x++)
		{
			dataitem=data[x].split('~ArrayItem~');
			GearArray[x]=new Array();
			GearArray[x][0]=dataitem[0];	   //  Id
			GearArray[x][1]=dataitem[1];	   //  Dp or Module value
			GearArray[x][2]=dataitem[2];	   //  Type of Processsing
			GearArray[x][3]=dataitem[3];	   //  Face From
			GearArray[x][4]=dataitem[4];	   //  Face To
			GearArray[x][5]=dataitem[5];	   //  Type of Face
			GearArray[x][6]=dataitem[6];	   //  Rate
			GearArray[x][7]=dataitem[7];       //  Type of entry
		}
		DispGearInfo();
	}
	if (document.getElementById("hdGearRateHelical").value!="")
	{
		GearHelical=(document.getElementById("hdGearRateHelical").value).split('~Array~');
		for (y=0;y<GearHelical.length ;y++)
		{
			HelicalDataItem=GearHelical[y].split('~ArrayItem~');
			GearHelicalArray[y]=new Array();
			GearHelicalArray[y][0]=HelicalDataItem[0];	   //  Id
			GearHelicalArray[y][1]=HelicalDataItem[1];	   //  Dp or Module value
			GearHelicalArray[y][2]=HelicalDataItem[2];	   //  Type of Processsing
			GearHelicalArray[y][3]=HelicalDataItem[3];	   //  Face From
			GearHelicalArray[y][4]=HelicalDataItem[4];	   //  Face To
			GearHelicalArray[y][5]=HelicalDataItem[5];	   //  Type of Face
			GearHelicalArray[y][6]=HelicalDataItem[6];	   //  Rate
			GearHelicalArray[y][7]=HelicalDataItem[7];     //  Type of entry
		}
		DispGearHelicalInfo();
	}
	if (document.getElementById("hdShaftPinion").value!="")
	{
		ShaftPinion=(document.getElementById("hdShaftPinion").value).split('~Array~');
		for (x=0;x< ShaftPinion.length;x++ )
		{
			ShaftPinionItem=ShaftPinion[x].split('~ArrayItem~');
			ShaftPinionArray[x]= new Array();
			ShaftPinionArray[x][0]=ShaftPinionItem[0];	  // Id
			ShaftPinionArray[x][1]=ShaftPinionItem[1];
			ShaftPinionArray[x][2]=ShaftPinionItem[2];
			ShaftPinionArray[x][3]=ShaftPinionItem[3];
			ShaftPinionArray[x][4]=ShaftPinionItem[4];
			ShaftPinionArray[x][5]=ShaftPinionItem[5];
			ShaftPinionArray[x][6]=ShaftPinionItem[6];
			ShaftPinionArray[x][7]=ShaftPinionItem[7];
			ShaftPinionArray[x][8]=ShaftPinionItem[8];	 // Entry Type
		}
		DispShaftPinionInfo();
	}
	if (document.getElementById("hdShaftPHelical").value!="")
	{
		ShaftPHelical=(document.getElementById("hdShaftPHelical").value).split('~Array~');
		for (x=0;x<ShaftPHelical.length ;x++ )
		{
			ShaftPHelicalItem=ShaftPHelical[x].split('~ArrayItem~');
			ShaftPHelicalArray[x]=new Array();
			ShaftPHelicalArray[x][0]=ShaftPHelicalItem[0];   // Id
			ShaftPHelicalArray[x][1]=ShaftPHelicalItem[1];
			ShaftPHelicalArray[x][2]=ShaftPHelicalItem[2];
			ShaftPHelicalArray[x][3]=ShaftPHelicalItem[3];
			ShaftPHelicalArray[x][4]=ShaftPHelicalItem[4];
			ShaftPHelicalArray[x][5]=ShaftPHelicalItem[5];
			ShaftPHelicalArray[x][6]=ShaftPHelicalItem[6];
			ShaftPHelicalArray[x][7]=ShaftPHelicalItem[7];	  // Entry Type
		}
		DispShaftPHelicalInfo();
	}
	if (document.getElementById("hdChainGear").value!="")
	{
		ChainGear=(document.getElementById("hdChainGear").value).split('~Array~');
		for (x=0;x< ChainGear.length;x++ )
		{
			ChainGearItem=ChainGear[x].split('~ArrayItem~');
			ChainGearArray[x]= new Array();
			ChainGearArray[x][0]=ChainGearItem[0];	   // Id
			ChainGearArray[x][1]=ChainGearItem[1];	   // Pitch Value
			ChainGearArray[x][2]=ChainGearItem[2];	   // Pitch Type
			ChainGearArray[x][3]=ChainGearItem[3];	   // Rate
			ChainGearArray[x][4]=ChainGearItem[4];	   // Entry Type
		}
		 DispChainGearInfo();
	}
	if (document.getElementById("hdBevelGear").value!="")
	{
		BevelGear=(document.getElementById("hdBevelGear").value).split('~Array~');
		for (x=0;x< BevelGear.length;x++ )
		{
			BevelGearItem=BevelGear[x].split("~ArrayItem~");
			BevelGearArray[x]=new Array();
			BevelGearArray[x][0]=BevelGearItem[0];	// Id;
			BevelGearArray[x][1]=BevelGearItem[1];	// Processing Value
			BevelGearArray[x][2]=BevelGearItem[2];  // Processing Type
			BevelGearArray[x][3]=BevelGearItem[3];  // Teeth
			BevelGearArray[x][4]=BevelGearItem[4];  // Rate
			BevelGearArray[x][5]=BevelGearItem[5];  // Entry Type
		}
		DispBevelGearInfo();
	}
}

function DispGearInfo()
{
	$("#dvGearDataPlain").html("");
	
	var x;
	var Data="<table id=\"example2\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNO</th><th>Processing Type</th><th>Face From</th><th>Face To</th><th>Rate</th><th>Option</th></tr></thead><tbody>";
	for (x=0;x<GearArray.length ;x++ )
	{
		Data+="<tr>";
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+GearArray[x][1]+"&nbsp;"+GearArray[x][2]+"</td>";
		Data+="<td>"+GearArray[x][3]+"&nbsp;</td>";
		Data+="<td>"+GearArray[x][4]+"&nbsp;"+GearArray[x][5]+"</td>";
		txt="txtGearPlain"+x; 
		Data+="<td><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+parseFloat(GearArray[x][6]).toFixed(2)+"\" size=\"7\" onChange=\"FillGearArray(this.id,"+x+")\" onblur=\"DecimalNum(this.id)\">&nbsp;PT</td>";	
		txt1="btnGearPlain"+x;
		Data+="<td><input type=\"button\" name=\""+txt1+"\" id=\""+txt1+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick=\"EditGearPlain("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</tbody></table>";
	$("#dvGearDataPlain").html(Data);
	tableCss()
	GearModCount=-1;
	GearconcatData();
}

function EditGearPlain(row)
{
	document.getElementById("txtGearProcessingPlain").value=GearArray[row][1];
	document.getElementById("cmbGearProcessingTypePlain").value=GearArray[row][2];
	document.getElementById("txtGearFaceFromPlain").value=GearArray[row][3];
	document.getElementById("txtGearFaceToPlain").value=GearArray[row][4];
	document.getElementById("cmbGearFaceTypePlain").value=GearArray[row][5];
	document.getElementById("txtGearRatePlain").value=GearArray[row][6];
	document.getElementById("btnGearAddPlain").value="Modify";
	document.getElementById("txtGearProcessingPlain").focus();
	GearModCount=row;
}

function FillGearArray(Id, x)
{
	val=parseFloat(document.getElementById(Id).value);
	if (val==0)
	{
		alert ("Rate can not be zero");
		event.returnValue=false;
	}
	else if (isNaN(val))
	{
		alert ("Value can not be blank");
		event.returnValue=false;
	}
	else
	{
		GearArray[x][6]=val;	
		GearconcatData();
	}
}

function GearconcatData()
{
	packed="";
	document.getElementById('hdGearRatePlain').value="";
	if(GearArray.length>0)
	{
		for(x=0;x<GearArray.length;x++)
		{
			if(packed=='')
			{
				packed=GearArray[x][0]+"~ArrayItem~"+GearArray[x][1]+"~ArrayItem~"+GearArray[x][2]+"~ArrayItem~"+GearArray[x][3]+"~ArrayItem~"+GearArray[x][4]+"~ArrayItem~"+GearArray[x][5]+"~ArrayItem~"+GearArray[x][6]+"~ArrayItem~"+GearArray[x][7];
			}
			else
			{
				packed=packed+"~Array~"+GearArray[x][0]+"~ArrayItem~"+GearArray[x][1]+"~ArrayItem~"+GearArray[x][2]+"~ArrayItem~"+GearArray[x][3]+"~ArrayItem~"+GearArray[x][4]+"~ArrayItem~"+GearArray[x][5]+"~ArrayItem~"+GearArray[x][6]+"~ArrayItem~"+GearArray[x][7];
			}
		}
	}
	document.getElementById('hdGearRatePlain').value=packed;
}
		// Plain Gear Ends Here

		// Helical Gear Starts Here

function AddGearHelicalRate()
{
	var validata=true;
	var errmsg="";
	var focusid="";
	
	if (document.getElementById("txtGearProcessingHelical").value=="" || parseFloat(document.getElementById("txtGearProcessingHelical").value)==0)
	{
		validata=false;
		errmsg="Please enter Gear Processing Type...";
		focusid="txtGearProcessingHelical";
		// lbl.innerHTML="";
		// document.getElementById("txtGearProcessingHelical").focus();
		// event.returnValue=false;
	}	
	else if (document.getElementById("txtGearFaceFromHelical").value=="" )
	{
		validata=false;
		errmsg="Please enter Gear Face From...";
		focusid="txtGearFaceFromHelical";
		
		// lbl.innerHTML="Please enter Gear Face From...";
		// document.getElementById("txtGearFaceFromHelical").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtGearFaceToHelical").value=="" || parseFloat(document.getElementById("txtGearFaceToHelical").value)==0)
	{
		validata=false;
		errmsg="Please enter Gear Face To...";
		focusid="txtGearFaceToHelical";
		
		// lbl.innerHTML="Please enter Gear Face To...";
		// document.getElementById("txtGearFaceToHelical").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtGearRateHelical").value=="" || parseFloat(document.getElementById("txtGearRateHelical").value)==0)
	{
		validata=false;
		errmsg="Please enter Gear Rate...";
		focusid="txtGearRateHelical";
		
		// lbl.innerHTML="Please enter Gear Rate...";
		// document.getElementById("txtGearRateHelical").focus();
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
		if (document.getElementById("btnGearAddHelical").value=="Add")
		{
			lenGearHelical=GearHelicalArray.length;
			GearHelicalArray[lenGearHelical]=new Array();
			GearHelicalArray[lenGearHelical][0]="0";
			GearHelicalArray[lenGearHelical][1]=parseFloat(document.getElementById("txtGearProcessingHelical").value);
			GearHelicalArray[lenGearHelical][2]=document.getElementById("cmbGearProcessingTypeHelical").value;
			GearHelicalArray[lenGearHelical][3]=parseFloat(document.getElementById("txtGearFaceFromHelical").value);
			GearHelicalArray[lenGearHelical][4]=parseFloat(document.getElementById("txtGearFaceToHelical").value);
			GearHelicalArray[lenGearHelical][5]=document.getElementById("cmbGearFaceTypeHelical").value;
			GearHelicalArray[lenGearHelical][6]=parseFloat(document.getElementById("txtGearRateHelical").value);
			GearHelicalArray[lenGearHelical][7]="New";
		}
		else
		{
			GearHelicalArray[GearHelicalModCount][1]=parseFloat(document.getElementById("txtGearProcessingHelical").value);
			GearHelicalArray[GearHelicalModCount][2]=document.getElementById("cmbGearProcessingTypeHelical").value;
			GearHelicalArray[GearHelicalModCount][3]=parseFloat(document.getElementById("txtGearFaceFromHelical").value);
			GearHelicalArray[GearHelicalModCount][4]=parseFloat(document.getElementById("txtGearFaceToHelical").value);
			GearHelicalArray[GearHelicalModCount][5]=document.getElementById("cmbGearFaceTypeHelical").value;
			GearHelicalArray[GearHelicalModCount][6]=parseFloat(document.getElementById("txtGearRateHelical").value);
			document.getElementById("btnGearAddHelical").value="Add";
		}
		
		DispGearHelicalInfo();
		document.getElementById("txtGearProcessingHelical").value="";
		document.getElementById("txtGearFaceFromHelical").value="";
		document.getElementById("txtGearFaceToHelical").value="";
		document.getElementById("txtGearRateHelical").value="";
	}
}

function DispGearHelicalInfo()
{
	$("#dvGearDataHelical").html("");
	
	var x;

	var Data="<table  id=\"example3\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNO</th><th>Processing Type</th><th>Face From</th><th>Face To</th><th>Rate</th><th>Option</th></tr></thead><tbody>";
	for (x=0;x<GearHelicalArray.length ;x++ )
	{
		Data+="<tr>";
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+GearHelicalArray[x][1]+"&nbsp;"+GearHelicalArray[x][2]+"</td>";
		Data+="<td>"+GearHelicalArray[x][3]+"&nbsp;</td>";
		Data+="<td>"+GearHelicalArray[x][4]+"&nbsp;"+GearHelicalArray[x][5]+"</td>";
		txt="txtGearHelical"+x; 
		Data+="<td><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+parseFloat(GearHelicalArray[x][6]).toFixed(2)+"\" size=\"7\"   onChange=\"FillGearHelicalArray(this.id,"+x+")\" onblur=\"DecimalNum(this.id)\">&nbsp;PT</td>";	
		txt1="btnGearHelical"+x;
		Data+="<td><input type=\"button\" name=\""+txt1+"\" id=\""+txt1+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick=\"EditGearHelical("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</tbody></table>";
	$("#dvGearDataHelical").html(Data);
	tableCss();
	GearHelicalconcatData();
}

function EditGearHelical(row)
{
	document.getElementById("txtGearProcessingHelical").value=GearHelicalArray[row][1];
	document.getElementById("cmbGearProcessingTypeHelical").value=GearHelicalArray[row][2];
	document.getElementById("txtGearFaceFromHelical").value=GearHelicalArray[row][3];
	document.getElementById("txtGearFaceToHelical").value=GearHelicalArray[row][4];
	document.getElementById("cmbGearFaceTypeHelical").value=GearHelicalArray[row][5];
	document.getElementById("txtGearRateHelical").value=GearHelicalArray[row][6];
	document.getElementById("btnGearAddHelical").value="Modify";
	document.getElementById("txtGearProcessingHelical").focus();
	GearHelicalModCount=row;
}

function FillGearHelicalArray(Id, x)
{
	val=parseFloat(document.getElementById(Id).value);
	if (val==0)
	{
		alert ("Rate can not be zero");
		event.returnValue=false;
	}
	else if (isNaN(val))
	{
		alert ("Value can not be blank");
		event.returnValue=false;
	}
	else
	{
		GearHelicalArray[x][6]=val;	
		GearHelicalconcatData();
	}
}

function GearHelicalconcatData()
{
	packed="";
	document.getElementById('hdGearRateHelical').value="";
	if(GearHelicalArray.length>0)
	{
		for(x=0;x<GearHelicalArray.length;x++)
		{
			if(packed=='')
			{
				packed=GearHelicalArray[x][0]+"~ArrayItem~"+GearHelicalArray[x][1]+"~ArrayItem~"+GearHelicalArray[x][2]+"~ArrayItem~"+GearHelicalArray[x][3]+"~ArrayItem~"+GearHelicalArray[x][4]+"~ArrayItem~"+GearHelicalArray[x][5]+"~ArrayItem~"+GearHelicalArray[x][6]+"~ArrayItem~"+GearHelicalArray[x][7];
			}
			else
			{
				packed=packed+"~Array~"+GearHelicalArray[x][0]+"~ArrayItem~"+GearHelicalArray[x][1]+"~ArrayItem~"+GearHelicalArray[x][2]+"~ArrayItem~"+GearHelicalArray[x][3]+"~ArrayItem~"+GearHelicalArray[x][4]+"~ArrayItem~"+GearHelicalArray[x][5]+"~ArrayItem~"+GearHelicalArray[x][6]+"~ArrayItem~"+GearHelicalArray[x][7];
			}
		}
	}
	document.getElementById('hdGearRateHelical').value=packed;
}

		// Helical Gear Ends here

		// Shaft Pinion Starts here
function AddShaftPinionRate()
{
	var validata=true;
	var focusid="";
	var errmsg="";
	
	if (document.getElementById("txtShaftPinionProcessing").value=="" || parseFloat(document.getElementById("txtShaftPinionProcessing").value)==0)
	{
		validata=false;
		errmsg="lease enter Shaft Pinion Processing Type...";
		focusid="txtShaftPinionProcessing";
		
		// lbl.innerHTML="Please enter Shaft Pinion Processing Type...";
		// document.getElementById("txtShaftPinionProcessing").focus();
		// event.returnValue=false;
	}	
	else if (document.getElementById("txtShaftPinionFaceFrom").value=="" )
	{
		validata=false;
		errmsg="Please enter Shaft Pinion Face From...";
		focusid="txtShaftPinionFaceFrom";
		// lbl.innerHTML="Please enter Shaft Pinion Face From...";
		// document.getElementById("txtShaftPinionFaceFrom").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtShaftPinionFaceTo").value=="" || parseFloat(document.getElementById("txtShaftPinionFaceTo").value)==0)
	{
		validata=false;
		errmsg="Please enter Shaft Pinion Face To...";
		focusid="txtShaftPinionFaceTo";
		
		// lbl.innerHTML="Please enter Shaft Pinion Face To...";
		// document.getElementById("txtShaftPinionFaceTo").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtShaftPinionTeeth").value=="" || parseInt(document.getElementById("txtShaftPinionTeeth").value)==0)
	{
		validata=false;
		errmsg="Please enter Shaft Pinion Teeth...";
		focusid="txtShaftPinionTeeth";
		
		// lbl.innerHTML="Please enter Shaft Pinion Teeth...";
		// document.getElementById("txtShaftPinionTeeth").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtShaftPinionRate").value=="" || parseFloat(document.getElementById("txtShaftPinionRate").value)==0)
	{
		validata=false;
		errmsg="Please enter Shaft Pinion Rate...";
		focusid="txtShaftPinionRate";
		
		// lbl.innerHTML="Please enter Shaft Pinion Rate...";
		// document.getElementById("txtShaftPinionRate").focus();
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
		if (document.getElementById("btnShaftPinionAdd").value=="Add")
		{
			lenShaftPinion=ShaftPinionArray.length;
			ShaftPinionArray[lenShaftPinion]=[];
			ShaftPinionArray[lenShaftPinion][0]="0";
			ShaftPinionArray[lenShaftPinion][1]=parseFloat(document.getElementById("txtShaftPinionProcessing").value);
			ShaftPinionArray[lenShaftPinion][2]=document.getElementById("cmbShaftPinionProcessingType").value;
			ShaftPinionArray[lenShaftPinion][3]=parseFloat(document.getElementById("txtShaftPinionFaceFrom").value);
			ShaftPinionArray[lenShaftPinion][4]=parseFloat(document.getElementById("txtShaftPinionFaceTo").value);
			ShaftPinionArray[lenShaftPinion][5]=document.getElementById("cmbShaftPinionFaceType").value;
			ShaftPinionArray[lenShaftPinion][6]=parseInt(document.getElementById("txtShaftPinionTeeth").value);
			ShaftPinionArray[lenShaftPinion][7]=parseFloat(document.getElementById("txtShaftPinionRate").value);
			ShaftPinionArray[lenShaftPinion][8]="New";
		}
		else
		{
			ShaftPinionArray[ShaftPinionModCount][1]=parseFloat(document.getElementById("txtShaftPinionProcessing").value);
			ShaftPinionArray[ShaftPinionModCount][2]=document.getElementById("cmbShaftPinionProcessingType").value;
			ShaftPinionArray[ShaftPinionModCount][3]=parseFloat(document.getElementById("txtShaftPinionFaceFrom").value);
			ShaftPinionArray[ShaftPinionModCount][4]=parseFloat(document.getElementById("txtShaftPinionFaceTo").value);
			ShaftPinionArray[ShaftPinionModCount][5]=document.getElementById("cmbShaftPinionFaceType").value;
			ShaftPinionArray[ShaftPinionModCount][6]=parseInt(document.getElementById("txtShaftPinionTeeth").value);
			ShaftPinionArray[ShaftPinionModCount][7]=parseFloat(document.getElementById("txtShaftPinionRate").value);
			document.getElementById("btnShaftPinionAdd").value="Add";
		}
		DispShaftPinionInfo();
		document.getElementById("txtShaftPinionProcessing").value="";
		document.getElementById("txtShaftPinionFaceFrom").value="";
		document.getElementById("txtShaftPinionFaceTo").value="";
		document.getElementById("txtShaftPinionTeeth").value="";
		document.getElementById("txtShaftPinionRate").value="";
	}
}

function DispShaftPinionInfo()
{
	ShaftPinionInfo=document.getElementById("dvShaftPinionData");
	ShaftPinionInfo.innerHTML="";
	var x;

	var Data="<table id=\"example4\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNO</th><th>Processing Type</th><th>Face From</th><th>Face To</th><th>Teeth</th><th>Rate</th><th>Option</th></tr></thead><tbody>";
	for (x=0;x<ShaftPinionArray.length ;x++ )
	{
		Data+="<tr>";
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+ShaftPinionArray[x][1]+"&nbsp;"+ShaftPinionArray[x][2]+"</td>";
		Data+="<td>"+ShaftPinionArray[x][3]+"&nbsp;</td>";
		Data+="<td>"+ShaftPinionArray[x][4]+"&nbsp;"+ShaftPinionArray[x][5]+"</td>";
		Data+="<td>"+ShaftPinionArray[x][6]+"&nbsp;</td>";
		txt="txtShaftPinion"+x; 
		Data+="<td><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+parseFloat(ShaftPinionArray[x][7]).toFixed(2)+"\" size=\"7\"   onChange=\"FillShaftPinionArray(this.id,"+x+")\" onblur=\"DecimalNum(this.id)\" >&nbsp;Per PC</td>";	
		txt1="btnShaftPinion"+x;
		Data+="<td><input type=\"button\" name=\""+txt1+"\" id=\""+txt1+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick=\"EditShaftPinion("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</tbody></table>";
	ShaftPinionInfo.innerHTML=Data;
	tableCss();
	ShaftPinionconcatData();
}

function EditShaftPinion(row)
{
	ShaftPinionModCount=row;
	document.getElementById("txtShaftPinionProcessing").value=ShaftPinionArray[row][1];	
	document.getElementById("cmbShaftPinionProcessingType").value=ShaftPinionArray[row][2];	
	document.getElementById("txtShaftPinionFaceFrom").value=ShaftPinionArray[row][3];	
	document.getElementById("txtShaftPinionFaceTo").value=ShaftPinionArray[row][4];	
	document.getElementById("cmbShaftPinionFaceType").value=ShaftPinionArray[row][5];	
	document.getElementById("txtShaftPinionTeeth").value=ShaftPinionArray[row][6];	
	document.getElementById("txtShaftPinionRate").value=ShaftPinionArray[row][7];
	document.getElementById("btnShaftPinionAdd").value="Modify";
	document.getElementById("txtShaftPinionProcessing").focus();	
}

function FillShaftPinionArray(Id, x)
{
	val=parseFloat(document.getElementById(Id).value);
	if (val==0)
	{
		alert ("Rate can not be zero");
		event.returnValue=false;
	}
	else if (isNaN(val))
	{
		alert ("Value can not be blank");
		event.returnValue=false;
	}
	else
	{
		ShaftPinionArray[x][7]=val;	
		ShaftPinionconcatData();
	}
}

function ShaftPinionconcatData()
{
	packed="";
	document.getElementById('hdShaftPinion').value="";
	if(ShaftPinionArray.length>0)
	{
		for(x=0;x<ShaftPinionArray.length;x++)
		{
			if(packed=='')
			{
				packed=ShaftPinionArray[x][0]+"~ArrayItem~"+ShaftPinionArray[x][1]+"~ArrayItem~"+ShaftPinionArray[x][2]+"~ArrayItem~"+ShaftPinionArray[x][3]+"~ArrayItem~"+ShaftPinionArray[x][4]+"~ArrayItem~"+ShaftPinionArray[x][5]+"~ArrayItem~"+ShaftPinionArray[x][6]+"~ArrayItem~"+ShaftPinionArray[x][7]+"~ArrayItem~"+ShaftPinionArray[x][8];
			}
			else
			{
				packed=packed+"~Array~"+ShaftPinionArray[x][0]+"~ArrayItem~"+ShaftPinionArray[x][1]+"~ArrayItem~"+ShaftPinionArray[x][2]+"~ArrayItem~"+ShaftPinionArray[x][3]+"~ArrayItem~"+ShaftPinionArray[x][4]+"~ArrayItem~"+ShaftPinionArray[x][5]+"~ArrayItem~"+ShaftPinionArray[x][6]+"~ArrayItem~"+ShaftPinionArray[x][7]+"~ArrayItem~"+ShaftPinionArray[x][8];
			}
		}
	}
	
	document.getElementById('hdShaftPinion').value=packed;
}

		// Shaft Pinion Ends Here

		// Helical Shaft Pinion Starts here

function AddShaftPHelicalRate()
{
	var validata=true;
	var errmsg="";
	var focusid="";
	if (document.getElementById("txtShaftPProcessingHelical").value=="" || parseFloat(document.getElementById("txtShaftPProcessingHelical").value)==0)
	{
		validata=false;
		errmsg="Please enter Helical Shaft Pinion Processing Type...";
		focusid="txtShaftPProcessingHelical";
		// document.getElementById("txtShaftPProcessingHelical").focus();
		// event.returnValue=false;
	}	
	else if (document.getElementById("txtShaftPFaceFromHelical").value=="" )
	{
		validata=false;
		errmsg="Please enter Helical Shaft Pinion Face From...";
		focusid="txtShaftPFaceFromHelical";
		
		// lbl.innerHTML="Please enter Helical Shaft Pinion Face From...";
		// document.getElementById("txtShaftPFaceFromHelical").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtShaftPFaceToHelical").value=="" || parseFloat(document.getElementById("txtShaftPFaceToHelical").value)==0)
	{
		validata=false;
		errmsg="Please enter Helical Shaft Pinion Face To...";
		focusid="txtShaftPFaceToHelical";
		
		// lbl.innerHTML="Please enter Helical Shaft Pinion Face To...";
		// document.getElementById("txtShaftPFaceToHelical").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtShaftPRateHelical").value=="" || parseFloat(document.getElementById("txtShaftPRateHelical").value)==0)
	{
		validata=false;
		errmsg="Please enter Helical Shaft Pinion Rate...";
		focusid="txtShaftPRateHelical";
		
		// lbl.innerHTML="Please enter Helical Shaft Pinion Rate...";
		// document.getElementById("txtShaftPRateHelical").focus();
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
		if (document.getElementById("btnShaftPHelicalAdd").value=="Add")
		{
			lenShaftPHelical=ShaftPHelicalArray.length;
			ShaftPHelicalArray[lenShaftPHelical]=[];
			ShaftPHelicalArray[lenShaftPHelical][0]="0";
			ShaftPHelicalArray[lenShaftPHelical][1]=parseFloat(document.getElementById("txtShaftPProcessingHelical").value);
			ShaftPHelicalArray[lenShaftPHelical][2]=document.getElementById("cmbShaftPProcessingTypeHelical").value;
			ShaftPHelicalArray[lenShaftPHelical][3]=parseFloat(document.getElementById("txtShaftPFaceFromHelical").value);
			ShaftPHelicalArray[lenShaftPHelical][4]=parseFloat(document.getElementById("txtShaftPFaceToHelical").value);
			ShaftPHelicalArray[lenShaftPHelical][5]=document.getElementById("cmbShaftPFaceTypeHelical").value;
			ShaftPHelicalArray[lenShaftPHelical][6]=parseFloat(document.getElementById("txtShaftPRateHelical").value);
			ShaftPHelicalArray[lenShaftPHelical][7]="New";
		}
		else
		{
			ShaftPHelicalArray[ShaftPHelicalModCount][1]=parseFloat(document.getElementById("txtShaftPProcessingHelical").value);
			ShaftPHelicalArray[ShaftPHelicalModCount][2]=document.getElementById("cmbShaftPProcessingTypeHelical").value;
			ShaftPHelicalArray[ShaftPHelicalModCount][3]=parseFloat(document.getElementById("txtShaftPFaceFromHelical").value);
			ShaftPHelicalArray[ShaftPHelicalModCount][4]=parseFloat(document.getElementById("txtShaftPFaceToHelical").value);
			ShaftPHelicalArray[ShaftPHelicalModCount][5]=document.getElementById("cmbShaftPFaceTypeHelical").value;
			ShaftPHelicalArray[ShaftPHelicalModCount][6]=parseFloat(document.getElementById("txtShaftPRateHelical").value);
			document.getElementById("btnShaftPHelicalAdd").value="Add";
		}
		DispShaftPHelicalInfo();
		document.getElementById("txtShaftPProcessingHelical").value="";
		document.getElementById("txtShaftPFaceFromHelical").value="";
		document.getElementById("txtShaftPFaceToHelical").value="";
		document.getElementById("txtShaftPRateHelical").value="";
	}
}

function DispShaftPHelicalInfo()
{ 
    $("#dvShaftPHelicalData").html("");
	
	var x;
	var Data="<table id=\"example5\" class=\"table table-bordered dt-responsive nowrap no-footer dtr-inline collapsed dataTable\">";
	Data+="<thead><tr><th>SNO</th><th>Processing Type</th><th>Face From</th><th>Face To</th><th>Rate</th><th>Option</th></tr></thead><tbody>";
	for (x=0;x<ShaftPHelicalArray.length ;x++ )
	{
		Data+="<tr>";
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+ShaftPHelicalArray[x][1]+"&nbsp;"+ShaftPHelicalArray[x][2]+"</td>";
		Data+="<td>"+ShaftPHelicalArray[x][3]+"&nbsp;</td>";
		Data+="<td>"+ShaftPHelicalArray[x][4]+"&nbsp;"+ShaftPHelicalArray[x][5]+"</td>";
		txt="txtShaftPHelical"+x; 
		Data+="<td><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+parseFloat(ShaftPHelicalArray[x][6]).toFixed(2)+"\" size=\"7\"   onChange=\"FillShaftPHelicalArray(this.id,"+x+")\" onblur=\"DecimalNum(this.id)\">&nbsp;PT</td>";	
		txt1="btnShaftPHelical"+x;
		Data+="<td><input type=\"button\" name=\""+txt1+"\" id=\""+txt1+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick=\"EditShaftPHelical("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</tbody></table>";
	$("#dvShaftPHelicalData").html(Data);
	tableCss();
	ShaftPHelicalconcatData();
}

function EditShaftPHelical(row)
{
	ShaftPHelicalModCount=row;
	document.getElementById("txtShaftPProcessingHelical").value=ShaftPHelicalArray[row][1];	
	document.getElementById("cmbShaftPProcessingTypeHelical").value=ShaftPHelicalArray[row][2];	
	document.getElementById("txtShaftPFaceFromHelical").value=ShaftPHelicalArray[row][3];	
	document.getElementById("txtShaftPFaceToHelical").value=ShaftPHelicalArray[row][4];	
	document.getElementById("cmbShaftPFaceTypeHelical").value=ShaftPHelicalArray[row][5];	
	document.getElementById("txtShaftPRateHelical").value=ShaftPHelicalArray[row][6];
	document.getElementById("btnShaftPHelicalAdd").value="Modify";
	document.getElementById("txtShaftPProcessingHelical").focus();	
}

function FillShaftPHelicalArray(Id, x)
{	
	val=parseFloat(document.getElementById(Id).value);
	
	if (val==0)
	{
		alert ("Rate can not be zero");
		event.returnValue=false;
	}
	else if (isNaN(val))
	{
		alert ("Value can not be blank");
		event.returnValue=false;
	}
	else
	{
		ShaftPHelicalArray[x][6]=val;	
		ShaftPHelicalconcatData();
	}
}

function ShaftPHelicalconcatData()
{
	packed="";
	document.getElementById('hdShaftPHelical').value="";
	if(ShaftPHelicalArray.length>0)
	{
		for(x=0;x<ShaftPHelicalArray.length;x++)
		{
			if(packed=='')
			{
				packed=ShaftPHelicalArray[x][0]+"~ArrayItem~"+ShaftPHelicalArray[x][1]+"~ArrayItem~"+ShaftPHelicalArray[x][2]+"~ArrayItem~"+ShaftPHelicalArray[x][3]+"~ArrayItem~"+ShaftPHelicalArray[x][4]+"~ArrayItem~"+ShaftPHelicalArray[x][5]+"~ArrayItem~"+ShaftPHelicalArray[x][6]+"~ArrayItem~"+ShaftPHelicalArray[x][7];
			}
			else
			{
				packed=packed+"~Array~"+ShaftPHelicalArray[x][0]+"~ArrayItem~"+ShaftPHelicalArray[x][1]+"~ArrayItem~"+ShaftPHelicalArray[x][2]+"~ArrayItem~"+ShaftPHelicalArray[x][3]+"~ArrayItem~"+ShaftPHelicalArray[x][4]+"~ArrayItem~"+ShaftPHelicalArray[x][5]+"~ArrayItem~"+ShaftPHelicalArray[x][6]+"~ArrayItem~"+ShaftPHelicalArray[x][7];
			}
		}
	}
	document.getElementById('hdShaftPHelical').value=packed;
}
			// Helical Shaft Pinion Ends Here

			// Chain Gear Starts here

function AddChainGearRate()
{
	var validata=true;
	var errmsg="";
	var focusid="";
	
	if (document.getElementById("txtChainGearPitch").value=="" || parseFloat(document.getElementById("txtChainGearPitch").value)==0)
	{
		validata=false;
		errmsg="Please enter Pitch Type...";
		focusid="txtChainGearPitch";
		
		// lbl.innerHTML="Please enter Pitch Type...";
		// document.getElementById("txtChainGearPitch").focus();
		// event.returnValue=false;
	}	
	else if (document.getElementById("txtChainGearRate").value=="" || parseFloat(document.getElementById("txtChainGearRate").value)==0)
	{
		validata=false;
		errmsg="Please enter Chain Gear Rate...";
		focusid="txtChainGearRate";
		
		// lbl.innerHTML="Please enter Chain Gear Rate...";
		// document.getElementById("txtChainGearRate").focus();
		// event.returnValue=false;
	}
	if(validata==false)
	{
		toastr.eroor(errmsg);
		$("#"+focusid).focus();
		event.returnValue=false;
	}
	else
	{
		if (document.getElementById("btnChainGearAdd").value=="Add")
		{
			lenChainGear=ChainGearArray.length;
			ChainGearArray[lenChainGear]=[];
			ChainGearArray[lenChainGear][0]="0";
			ChainGearArray[lenChainGear][1]=parseFloat(document.getElementById("txtChainGearPitch").value);
			ChainGearArray[lenChainGear][2]=document.getElementById("cmbChainGearType").value;
			ChainGearArray[lenChainGear][3]=parseFloat(document.getElementById("txtChainGearRate").value);
			ChainGearArray[lenChainGear][4]="New";
		}
		else
		{
			ChainGearArray[ChainGearModCount][1]=parseFloat(document.getElementById("txtChainGearPitch").value);
			ChainGearArray[ChainGearModCount][2]=document.getElementById("cmbChainGearType").value;
			ChainGearArray[ChainGearModCount][3]=parseFloat(document.getElementById("txtChainGearRate").value);
			document.getElementById("btnChainGearAdd").value="Add";
		}
		DispChainGearInfo();
		document.getElementById("txtChainGearPitch").value="";
		document.getElementById("txtChainGearRate").value="";
	}
}

function DispChainGearInfo()
{
	$("#dvChainGearData").html("");
	
	var x;
	var Data="<table id=\"example6\" class=\"table table-bordered dt-responsive nowrap no-footer dtr-inline collapsed dataTable\">";
	Data+="<thead><tr><th>SNO</th><th>Pitch</th><th>Rate</th><th>Option</th></tr></thead><tbody>";
	for (x=0;x<ChainGearArray.length ;x++ )
	{
		Data+="<tr>";
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+ChainGearArray[x][1]+"&nbsp;"+ChainGearArray[x][2]+"</td>";
		txt="txtChainGear"+x; 
		Data+="<td><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+parseFloat(ChainGearArray[x][3]).toFixed(2)+"\" size=\"7\"   onChange=\"FillChainGearArray(this.id,"+x+")\" onblur=\"DecimalNum(this.id)\">&nbsp;PT</td>";	
		txt1="btnChainGear"+x;
		Data+="<td><input type=\"button\" name=\""+txt1+"\" id=\""+txt1+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick=\"EditChainGear("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</tbody></table>";
	$("#dvChainGearData").html(Data);
	tableCss();
	ChainGearconcatData();
}

function EditChainGear(row)
{
	ChainGearModCount=row;
	document.getElementById("txtChainGearPitch").value=ChainGearArray[row][1];
	document.getElementById("cmbChainGearType").value=ChainGearArray[row][2];	
	document.getElementById("txtChainGearRate").value=ChainGearArray[row][3];
	document.getElementById("btnChainGearAdd").value="Modify";
	document.getElementById("txtChainGearPitch").focus();
			
}

function FillChainGearArray(Id,x)
{
	val=parseFloat(document.getElementById(Id).value);
	
	if (val==0)
	{
		alert ("Rate can not be zero");
		event.returnValue=false;
	}
	else if (isNaN(val))
	{
		alert ("Value can not be blank");
		event.returnValue=false;
	}
	else
	{
		ChainGearArray[x][3]=val;	
		ChainGearconcatData();
	}
}

function ChainGearconcatData()
{
	packed="";
	document.getElementById('hdChainGear').value="";
	if(ChainGearArray.length>0)
	{
		for(x=0;x<ChainGearArray.length;x++)
		{
			if(packed=='')
			{
				packed=ChainGearArray[x][0]+"~ArrayItem~"+ChainGearArray[x][1]+"~ArrayItem~"+ChainGearArray[x][2]+"~ArrayItem~"+ChainGearArray[x][3]+"~ArrayItem~"+ChainGearArray[x][4];
			}
			else
			{
				packed=packed+"~Array~"+ChainGearArray[x][0]+"~ArrayItem~"+ChainGearArray[x][1]+"~ArrayItem~"+ChainGearArray[x][2]+"~ArrayItem~"+ChainGearArray[x][3]+"~ArrayItem~"+ChainGearArray[x][4];
			}
		}
	}
	document.getElementById('hdChainGear').value=packed;
}

			// Chain Gear ends here

			// Bevel Gear starts here
function AddBevelGearRate()
{
	var validata=true;
	var focusid="";
	var errmsg="";
	if (document.getElementById("txtBevelGearProcessing").value=="" || parseFloat(document.getElementById("txtBevelGearProcessing").value)==0)
	{
		validata=false;
		focusid="txtBevelGearProcessing";
		errmsg="Please enter Bevel Gear Processing Type...";
		// lbl.innerHTML="Please enter Bevel Gear Processing Type...";
		// document.getElementById("txtBevelGearProcessing").focus();
		// event.returnValue=false;
	}	
	else if (document.getElementById("txtBevelGearTeeth").value=="" || parseInt(document.getElementById("txtBevelGearTeeth").value==0))
	{
		validata=false;
		focusid="txtBevelGearTeeth";
		errmsg="Please enter Bevel Gear Teeth...";
		
		// lbl.innerHTML="Please enter Bevel Gear Teeth...";
		// document.getElementById("txtBevelGearTeeth").focus();
		// event.returnValue=false;
	}
	else if (document.getElementById("txtBevelGearRate").value=="" || parseFloat(document.getElementById("txtBevelGearRate").value)==0)
	{
		validata=false;
		focusid="txtBevelGearRate";
		errmsg="Please enter Bevel Gear Rate...";
		
		// lbl.innerHTML="Please enter Bevel Gear Rate...";
		// document.getElementById("txtBevelGearRate").focus();
		// event.returnValue=false;
	}
	if(validata==false)
	{
		toastr.error(errmsg);
		$("#"+focusid).focus();
	}
	else
	{
		if (document.getElementById("btnBevelGearAdd").value=="Add")
		{
			lenBevelGear=BevelGearArray.length;
			BevelGearArray[lenBevelGear]=[];
			BevelGearArray[lenBevelGear][0]="0";
			BevelGearArray[lenBevelGear][1]=parseFloat(document.getElementById("txtBevelGearProcessing").value);
			BevelGearArray[lenBevelGear][2]=document.getElementById("cmbBevelGearProcessingType").value;
			BevelGearArray[lenBevelGear][3]=parseInt(document.getElementById("txtBevelGearTeeth").value);
			BevelGearArray[lenBevelGear][4]=parseFloat(document.getElementById("txtBevelGearRate").value);
			BevelGearArray[lenBevelGear][5]="New";
		}
		else
		{
			BevelGearArray[BevelGearModCount][1]=parseFloat(document.getElementById("txtBevelGearProcessing").value);
			BevelGearArray[BevelGearModCount][2]=document.getElementById("cmbBevelGearProcessingType").value;
			BevelGearArray[BevelGearModCount][3]=parseInt(document.getElementById("txtBevelGearTeeth").value);
			BevelGearArray[BevelGearModCount][4]=parseFloat(document.getElementById("txtBevelGearRate").value);
			document.getElementById("btnBevelGearAdd").value="Add";
		}
		DispBevelGearInfo();
		document.getElementById("txtBevelGearProcessing").value="";
		document.getElementById("txtBevelGearTeeth").value="";
		document.getElementById("txtBevelGearRate").value="";
	}
}

function DispBevelGearInfo()
{
	$("#dvBevelGearData").html("");
	BevelGearInfo=document.getElementById("dvBevelGearData");
	BevelGearInfo.innerHTML="";
	var x;
	var Data="<table id=\"example7\" class=\"table table-bordered dt-responsive nowrap no-footer dtr-inline collapsed dataTable\">";
	Data+="<thead><tr><th>SNO</th><th>Processing Type</th><th>Teeth</th><th>Rate</th><th>Option</th></tr></thead><tbody>";
	for (x=0;x<BevelGearArray.length ;x++ )
	{
		Data+="<tr>";
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+BevelGearArray[x][1]+"&nbsp;"+BevelGearArray[x][2]+"</td>";
		Data+="<td>"+BevelGearArray[x][3]+"</td>";
		txt="txtBevelGear"+x; 
		Data+="<td><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+parseFloat(BevelGearArray[x][4]).toFixed(2)+"\" size=\"7\"   onChange=\"FillBevelGearArray(this.id,"+x+")\" onblur=\"DecimalNum(this.id)\">&nbsp;PT</td>";	
		txt1="btnBevelGear"+x;
		Data+="<td><input type=\"button\" name=\""+txt1+"\" id=\""+txt1+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick=\"EditBevelGear("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</tbody></table>";
	$("#dvBevelGearData").html(Data);
	tableCss();
	BevelGearconcatData();
}

function EditBevelGear(row)
{
	BevelGearModCount=row;
	document.getElementById("txtBevelGearProcessing").value=BevelGearArray[row][1];
	document.getElementById("cmbBevelGearProcessingType").value=BevelGearArray[row][2];
	document.getElementById("txtBevelGearTeeth").value=BevelGearArray[row][3];
	document.getElementById("txtBevelGearRate").value=BevelGearArray[row][4];
	document.getElementById("btnBevelGearAdd").value="Modify";
	document.getElementById("txtBevelGearProcessing").focus();
}

function FillBevelGearArray(Id,x)
{
	val=parseFloat(document.getElementById(Id).value);
	
	if (val==0)
	{
		alert ("Rate can not be zero");
		event.returnValue=false;
	}
	else if (isNaN(val))
	{
		alert ("Value can not be blank");
		event.returnValue=false;
	}
	else
	{
		BevelGearArray[x][4]=val;	
		BevelGearconcatData();
	}
}

function BevelGearconcatData()
{
	packed="";
	document.getElementById('hdBevelGear').value="";
	if(BevelGearArray.length>0)
	{
		for(x=0;x<BevelGearArray.length;x++)
		{
			if(packed=='')
			{
				packed=BevelGearArray[x][0]+"~ArrayItem~"+BevelGearArray[x][1]+"~ArrayItem~"+BevelGearArray[x][2]+"~ArrayItem~"+BevelGearArray[x][3]+"~ArrayItem~"+BevelGearArray[x][4]+"~ArrayItem~"+BevelGearArray[x][5];
			}
			else
			{
				packed=packed+"~Array~"+BevelGearArray[x][0]+"~ArrayItem~"+BevelGearArray[x][1]+"~ArrayItem~"+BevelGearArray[x][2]+"~ArrayItem~"+BevelGearArray[x][3]+"~ArrayItem~"+BevelGearArray[x][4]+"~ArrayItem~"+BevelGearArray[x][5];
			}
		}
	}
	document.getElementById('hdBevelGear').value=packed;
}
function tableCss()
{
	$("#example2").dataTable().fnDestroy();
	$("#example3").dataTable().fnDestroy();
	$("#example4").dataTable().fnDestroy();
	$("#example5").dataTable().fnDestroy();
	$("#example6").dataTable().fnDestroy();
	$("#example7").dataTable().fnDestroy();
	$("#example8").dataTable().fnDestroy();
	
	
    $("#datatable").DataTable({
      "responsive": true,
      "autoWidth": false,
	  "paging": true,
	  "searching": true,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	$('#example3').DataTable({
		
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	$('#example4').DataTable({
		
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	$('#example5').DataTable({
		
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	$('#example6').DataTable({
		
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	$('#example7').DataTable({
		
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	$('#example8').DataTable({
		
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  
}