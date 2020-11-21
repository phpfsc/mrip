var GearArray=new Array();
var GearHelicalArray=new Array();
var GearModCount=-1;
var GearHelicalModCount=-1;


function Pageload()
{
	DispalyGear();
}

		// Plain Gear Starts here

function AddGearRate()
{
	lbl=document.getElementById("lblGearPlainMsg");
	lbl.innerHTML="";
	if (document.getElementById("txtGearProcessingPlain").value=="" || parseFloat(document.getElementById("txtGearProcessingPlain").value)==0)
	{
		lbl.innerHTML="Please enter Gear Processing Type...";
		document.getElementById("txtGearProcessingPlain").focus();
		event.returnValue=false;
	}	
	else if (document.getElementById("txtGearDiaFromPlain").value=="" )
	{
		lbl.innerHTML="Please enter Gear Dia From...";
		document.getElementById("txtGearDiaFromPlain").focus();
		event.returnValue=false;
	}
	else if (document.getElementById("txtGearDiaToPlain").value=="" || parseFloat(document.getElementById("txtGearDiaToPlain").value)==0)
	{
		lbl.innerHTML="Please enter Gear Dia To...";
		document.getElementById("txtGearDiaToPlain").focus();
		event.returnValue=false;
	}
	else if (document.getElementById("txtGearRatePlain").value=="" || parseFloat(document.getElementById("txtGearRatePlain").value)==0)
	{
		lbl.innerHTML="Please enter Gear Rate...";
		document.getElementById("txtGearRatePlain").focus();
		event.returnValue=false;
	}
	else
	{
		if (document.getElementById("btnGearAddPlain").value=="Add")
		{
			lenGear=GearArray.length;
			GearArray[lenGear]=new Array();
			GearArray[lenGear][0]="0";
			GearArray[lenGear][1]=parseFloat(document.getElementById("txtGearProcessingPlain").value);
			GearArray[lenGear][2]=document.getElementById("cmbGearProcessingTypePlain").value;
			GearArray[lenGear][3]=parseFloat(document.getElementById("txtGearDiaFromPlain").value);
			GearArray[lenGear][4]=parseFloat(document.getElementById("txtGearDiaToPlain").value);
			GearArray[lenGear][5]=document.getElementById("cmbGearDiaTypePlain").value;
			GearArray[lenGear][6]=parseFloat(document.getElementById("txtGearRatePlain").value);
			GearArray[lenGear][7]="New";
		}
		else
		{
			GearArray[GearModCount][1]=parseFloat(document.getElementById("txtGearProcessingPlain").value);
			GearArray[GearModCount][2]=document.getElementById("cmbGearProcessingTypePlain").value;
			GearArray[GearModCount][3]=parseFloat(document.getElementById("txtGearDiaFromPlain").value);
			GearArray[GearModCount][4]=parseFloat(document.getElementById("txtGearDiaToPlain").value);
			GearArray[GearModCount][5]=document.getElementById("cmbGearDiaTypePlain").value;
			GearArray[GearModCount][6]=parseFloat(document.getElementById("txtGearRatePlain").value);
			document.getElementById("btnGearAddPlain").value="Add";
		}
		DispGearInfo();
		document.getElementById("txtGearProcessingPlain").value="";
		document.getElementById("txtGearDiaFromPlain").value="";
		document.getElementById("txtGearDiaToPlain").value="";
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
			GearArray[x][7]=dataitem[7];    //  Type of entry
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
			GearHelicalArray[y][7]=HelicalDataItem[7];      //  Type of entry
		}
		DispGearHelicalInfo();
	}
}

function DispGearInfo()
{
	GearDataInfo=document.getElementById("dvGearDataPlain");
	GearDataInfo.innerHTML="";
	var x;
	var Data="<table cellpadding=\"0\" cellspacing=\"0\" width=\"520px\" class=\"labeltext\">";
	Data+="<tr class=\"tableheadingleft\"><td width=\"50\" height=\"25px\">SNO</td><td width=\"110px\" height=\"25px\">Processing Type</td><td width=\"80px\" height=\"25px\">Dia From</td><td width=\"80px\" height=\"25px\">Dia To</td><td width=\"80px\" height=\"25px\">Rate</td><td width=\"70\">Option</td></tr>";
	for (x=0;x<GearArray.length ;x++ )
	{
		Data+="<tr class=\"tableheadingleft\">";
		Data+="<td height=\"25px\" class=\"tablecell\">"+(x+1)+"</td>";
		Data+="<td  class=\"tablecell\">"+GearArray[x][1]+"&nbsp;"+GearArray[x][2]+"</td>";
		Data+="<td class=\"tablecell\">"+GearArray[x][3]+"&nbsp;</td>";
		Data+="<td class=\"tablecell\">"+GearArray[x][4]+"&nbsp;"+GearArray[x][5]+"</td>";
		txt="txtGearPlain"+x; 
		Data+="<td class=\"tablecell\"><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+parseFloat(GearArray[x][6]).toFixed(2)+"\" size=\"7\" onChange=\"FillGearArray(this.id,"+x+")\" onblur=\"DecimalNum(this.id)\">&nbsp;PT</td>";	
		txt1="btnGearPlain"+x;
		Data+="<td class=\"tablecell\"><input type=\"button\" name=\""+txt1+"\" id=\""+txt1+"\" value=\"Edit\" class=\"btncss\" onClick=\"EditGearPlain("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</table>";
	
	GearDataInfo.innerHTML=Data;
	GearDataInfo.style['height']='100%';
	
	GearconcatData();
}

function EditGearPlain(row)
{
	GearModCount=row;
	document.getElementById("txtGearProcessingPlain").value=GearArray[row][1];
	document.getElementById("cmbGearProcessingTypePlain").value=GearArray[row][2];
	document.getElementById("txtGearDiaFromPlain").value=GearArray[row][3];
	document.getElementById("txtGearDiaToPlain").value=GearArray[row][4];
	document.getElementById("cmbGearDiaTypePlain").value=GearArray[row][5];
	document.getElementById("txtGearRatePlain").value=GearArray[row][6];
	document.getElementById("txtGearProcessingPlain").focus();
	document.getElementById("btnGearAddPlain").value="Modify";	
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
	lbl=document.getElementById("lblGearHelicalMsg");
	lbl.innerHTML="";
	if (document.getElementById("txtGearProcessingHelical").value=="" || parseFloat(document.getElementById("txtGearProcessingHelical").value)==0)
	{
		lbl.innerHTML="Please enter Gear Processing Type...";
		document.getElementById("txtGearProcessingHelical").focus();
		event.returnValue=false;
	}	
	else if (document.getElementById("txtGearTeethFromHelical").value=="" )
	{
		lbl.innerHTML="Please enter Gear Face From...";
		document.getElementById("txtGearTeethFromHelical").focus();
		event.returnValue=false;
	}
	else if (document.getElementById("txtGearTeethToHelical").value=="" || parseFloat(document.getElementById("txtGearTeethToHelical").value)==0)
	{
		lbl.innerHTML="Please enter Gear Face To...";
		document.getElementById("txtGearTeethToHelical").focus();
		event.returnValue=false;
	}
	else if (document.getElementById("txtGearRateHelical").value=="" || parseFloat(document.getElementById("txtGearRateHelical").value)==0)
	{
		lbl.innerHTML="Please enter Gear Rate...";
		document.getElementById("txtGearRateHelical").focus();
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
			GearHelicalArray[lenGearHelical][3]=parseFloat(document.getElementById("txtGearTeethFromHelical").value);
			GearHelicalArray[lenGearHelical][4]=parseFloat(document.getElementById("txtGearTeethToHelical").value);
			GearHelicalArray[lenGearHelical][5]=parseFloat(document.getElementById("txtGearRateHelical").value);
			GearHelicalArray[lenGearHelical][6]="New";
		}
		else
		{
			GearHelicalArray[GearHelicalModCount][1]=parseFloat(document.getElementById("txtGearProcessingHelical").value);
			GearHelicalArray[GearHelicalModCount][2]=document.getElementById("cmbGearProcessingTypeHelical").value;
			GearHelicalArray[GearHelicalModCount][3]=parseFloat(document.getElementById("txtGearTeethFromHelical").value);
			GearHelicalArray[GearHelicalModCount][4]=parseFloat(document.getElementById("txtGearTeethToHelical").value);
			GearHelicalArray[GearHelicalModCount][5]=parseFloat(document.getElementById("txtGearRateHelical").value);
			document.getElementById("btnGearAddHelical").value="Add";
		}
		DispGearHelicalInfo();
		document.getElementById("txtGearProcessingHelical").value="";
		document.getElementById("txtGearTeethFromHelical").value="";
		document.getElementById("txtGearTeethToHelical").value="";
		document.getElementById("txtGearRateHelical").value="";
	}
}

function DispGearHelicalInfo()
{
	GearDataInfo=document.getElementById("dvGearDataHelical");
	GearDataInfo.innerHTML="";
	var x;

	var Data="<table cellpadding=\"0\" cellspacing=\"0\" width=\"450px\" class=\"labeltext\">";
	Data+="<tr class=\"tableheadingleft\"><td width=\"50\" height=\"25px\">SNO</td><td width=\"110px\" height=\"25px\">Processing Type</td><td width=\"80px\" height=\"25px\">Teeth From</td><td width=\"80px\" height=\"25px\">Teeth To</td><td width=\"80px\">Rate</td><td width=\"70\">Option</td></tr>";
	for (x=0;x<GearHelicalArray.length ;x++ )
	{
		Data+="<tr class=\"tableheadingleft\">";
		Data+="<td height=\"25px\" class=\"tablecell\">"+(x+1)+"</td>";
		Data+="<td  class=\"tablecell\">"+GearHelicalArray[x][1]+"&nbsp;"+GearHelicalArray[x][2]+"</td>";
		Data+="<td class=\"tablecell\">"+GearHelicalArray[x][3]+"&nbsp;</td>";
		Data+="<td class=\"tablecell\">"+GearHelicalArray[x][4]+"&nbsp;</td>";
		txt="txtGearHelical"+x; 
		Data+="<td class=\"tablecell\"><input type=\"text\" name=\""+txt+"\" id=\""+txt+"\" value=\""+parseFloat(GearHelicalArray[x][5]).toFixed(2)+"\" size=\"7\"   onChange=\"FillGearHelicalArray(this.id,"+x+")\" onblur=\"DecimalNum(this.id)\">&nbsp;PT</td>";	
		txt1="btnGearHelical"+x;
		Data+="<td class=\"tablecell\"><input type=\"button\" name=\""+txt1+"\" id=\""+txt1+"\" value=\"Edit\" class=\"btncss\" onClick=\"EditGearHelical("+x+")\"></td>";
		Data+="</tr>";
	}
	Data=Data+"</table>";
	GearDataInfo.innerHTML=Data;
	GearDataInfo.style['height']='100%';
	GearHelicalconcatData();
}

function EditGearHelical(row)
{
	GearHelicalModCount=row;
	document.getElementById("txtGearProcessingHelical").value=GearHelicalArray[row][1];
	document.getElementById("cmbGearProcessingTypeHelical").value=GearHelicalArray[row][2];
	document.getElementById("txtGearTeethFromHelical").value=GearHelicalArray[row][3];
	document.getElementById("txtGearTeethToHelical").value=GearHelicalArray[row][4];
	document.getElementById("txtGearRateHelical").value=GearHelicalArray[row][5];
	document.getElementById("btnGearAddHelical").value="Modify";
	document.getElementById("txtGearProcessingHelical").focus();
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
		GearHelicalArray[x][5]=val;	
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
				packed=GearHelicalArray[x][0]+"~ArrayItem~"+GearHelicalArray[x][1]+"~ArrayItem~"+GearHelicalArray[x][2]+"~ArrayItem~"+GearHelicalArray[x][3]+"~ArrayItem~"+GearHelicalArray[x][4]+"~ArrayItem~"+GearHelicalArray[x][5]+"~ArrayItem~"+GearHelicalArray[x][6];
			}
			else
			{
				packed=packed+"~Array~"+GearHelicalArray[x][0]+"~ArrayItem~"+GearHelicalArray[x][1]+"~ArrayItem~"+GearHelicalArray[x][2]+"~ArrayItem~"+GearHelicalArray[x][3]+"~ArrayItem~"+GearHelicalArray[x][4]+"~ArrayItem~"+GearHelicalArray[x][5]+"~ArrayItem~"+GearHelicalArray[x][6];
			}
		}
	}
	document.getElementById('hdGearRateHelical').value=packed;
}

	
		// Bevel Gear ends here
function checkBlank(Id)
{
	lbl=document.getElementById("lblErrMsg");
	if (Id=="btnGearDataPlain")
	{
		if (document.getElementById("hdGearRatePlain").value=="")
		{
			lbl.innerHTML="Please The Data...";
			document.getElementById("txtGearProcessingPlain").focus();
			event.returnValue=false;
		}
	}
	else if (Id=="btnGearDataHelical")
	{

		if (document.getElementById("txtMinGearRateHelical").value=="" || parseFloat(document.getElementById("txtMinGearRateHelical").value)==0)
		{
			lbl.innerHTML="Please enter Min. Rate...";
			document.getElementById("txtMinGearRateHelical").focus();
			event.returnValue=false;
		}
		else if (document.getElementById("hdGearRateHelical").value=="")
		{
			lbl.innerHTML="Please The Data...";
			document.getElementById("txtGearProcessingHelical").focus();
			event.returnValue=false;
		}
	}
}