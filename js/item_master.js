$("dcoument").ready(function(){
	Pageload();
	
})
var GearArray=[];
var PinionArray=[];
var ShaftPinionArray=[];
var BevelGearArray=[];
var BevelPinionArray=[];
var ChainGearArray=[];
var WormGearArray=[];

var GearModcount=-1;
var PinionModcount=1;
var ShaftPinionModcount=-1;
var BevelGearModcount=-1;
var BevelPinionModcount=-1;
var ChainGearModcount=-1;
var WormGearModcount=-1;

function Pageload()
{
	supercombo_1 = new TypeAheadCombo("cmbItem");
	document.getElementById("cmbItem").focus();
	EditGearInfo();
	EditPinionInfo();
	EditShaftPinionInfo();
	EditBevelGearInfo();
	EditBevelPinionInfo();
	EditChainGearInfo();
	EditWormGearInfo();
}

function selDiv(Id)
{
	val=document.getElementById(Id).value;
	if (val=="Gear")
	{
		document.getElementById("dvGear").style['display']="inline";	
		document.getElementById("dvPinion").style['display']="none";	
		document.getElementById("dvShaftPinion").style['display']="none";	
		document.getElementById("dvBevelGear").style['display']="none";	
		document.getElementById("dvBevelPinion").style['display']="none";	
		document.getElementById("dvChainGear").style['display']="none";
		document.getElementById("dvWormGear").style['display']="none";	
	}
	else if (val=="Pinion")
	{
		document.getElementById("dvGear").style['display']="none";	
		document.getElementById("dvPinion").style['display']="inline";	
		document.getElementById("dvShaftPinion").style['display']="none";	
		document.getElementById("dvBevelGear").style['display']="none";	
		document.getElementById("dvBevelPinion").style['display']="none";	
		document.getElementById("dvChainGear").style['display']="none";
		document.getElementById("dvWormGear").style['display']="none";		
	}
	else if (val=="Shaft Pinion")
	{
		document.getElementById("dvGear").style['display']="none";	
		document.getElementById("dvPinion").style['display']="none";	
		document.getElementById("dvShaftPinion").style['display']="inline";	
		document.getElementById("dvBevelGear").style['display']="none";	
		document.getElementById("dvBevelPinion").style['display']="none";	
		document.getElementById("dvChainGear").style['display']="none";
		document.getElementById("dvWormGear").style['display']="none";		
	}
	else if (val=="Bevel Gear")
	{
		document.getElementById("dvGear").style['display']="none";	
		document.getElementById("dvPinion").style['display']="none";	
		document.getElementById("dvShaftPinion").style['display']="none";	
		document.getElementById("dvBevelGear").style['display']="inline";	
		document.getElementById("dvBevelPinion").style['display']="none";	
		document.getElementById("dvChainGear").style['display']="none";
		document.getElementById("dvWormGear").style['display']="none";		
	}
	else if (val=="Bevel Pinion")
	{
		document.getElementById("dvGear").style['display']="none";	
		document.getElementById("dvPinion").style['display']="none";	
		document.getElementById("dvShaftPinion").style['display']="none";	
		document.getElementById("dvBevelGear").style['display']="none";	
		document.getElementById("dvBevelPinion").style['display']="inline";	
		document.getElementById("dvChainGear").style['display']="none";
		document.getElementById("dvWormGear").style['display']="none";		
	}
	else if (val=="Chain Wheel")
	{
		document.getElementById("dvGear").style['display']="none";	
		document.getElementById("dvPinion").style['display']="none";	
		document.getElementById("dvShaftPinion").style['display']="none";	
		document.getElementById("dvBevelGear").style['display']="none";	
		document.getElementById("dvBevelPinion").style['display']="none";	
		document.getElementById("dvChainGear").style['display']="inline";
		document.getElementById("dvWormGear").style['display']="none";		
	}
	else if (val=="Worm Gear")
	{
		document.getElementById("dvGear").style['display']="none";	
		document.getElementById("dvPinion").style['display']="none";	
		document.getElementById("dvShaftPinion").style['display']="none";	
		document.getElementById("dvBevelGear").style['display']="none";	
		document.getElementById("dvBevelPinion").style['display']="none";	
		document.getElementById("dvChainGear").style['display']="none";
		document.getElementById("dvWormGear").style['display']="inline";		
	}
	else
	{
		document.getElementById("dvGear").style['display']="none";	
		document.getElementById("dvPinion").style['display']="none";	
		document.getElementById("dvShaftPinion").style['display']="none";	
		document.getElementById("dvBevelGear").style['display']="none";	
		document.getElementById("dvBevelPinion").style['display']="none";	
		document.getElementById("dvChainGear").style['display']="none";
		document.getElementById("dvWormGear").style['display']="none";	
	}
}

function EditGearInfo()
{
	val=document.getElementById("hdGearData").value;
	if (val!="")
	{
		geardata=val.split("~Array~");
		for (i=0;i<geardata.length;i++)
		{
			sel=(geardata[i]).split("~ArrayItem~");
			lenGear=GearArray.length;
			GearArray[lenGear]=new Array();
			GearArray[lenGear][0]=sel[0];  // Id
			
			GearArray[lenGear][1]=sel[1];  // Whole Item Name
			GearArray[lenGear][2]=sel[2];  // teeth
			GearArray[lenGear][3]=sel[3];  // Item Type
			GearArray[lenGear][4]=sel[4];  //  Dia
			GearArray[lenGear][5]=sel[5];  // Dia Type
			GearArray[lenGear][6]=sel[6];  // Face 
			GearArray[lenGear][7]=sel[7];	// Face Type
			GearArray[lenGear][8]=sel[8];	// DMValue
			GearArray[lenGear][9]=sel[9];   // DMType
		}
		DisplayGear();
	}
}
function ModifyGear()
{
	GearArray[GearModcount][2]=document.getElementById("txtGearTeeth").value;
	GearArray[GearModcount][3]=document.getElementById("cmbGearType").value;
	GearArray[GearModcount][4]=document.getElementById("txtGearDia").value;
	GearArray[GearModcount][5]=document.getElementById("cmbGearDiaType").value;
	GearArray[GearModcount][6]=document.getElementById("txtGearFace").value;
	GearArray[GearModcount][7]=document.getElementById("cmbGearFaceType").value;
	GearArray[GearModcount][8]=document.getElementById("txtGearProcessing").value;
	GearArray[GearModcount][9]=document.getElementById("cmbGearProcessingType").value;
	document.getElementById("txtGearTeeth").value="";
	document.getElementById("cmbGearType").value="";
	document.getElementById("txtGearDia").value="";
	document.getElementById("cmbGearDiaType").value="";
	document.getElementById("txtGearFace").value="";
	document.getElementById("cmbGearFaceType").value="";
	document.getElementById("txtGearProcessing").value="";
	document.getElementById("cmbGearProcessingType").value="";
	DisplayGear();
}

function DisplayGear()
{
	$("#dvGearShow").html('');
	var x;
	//var Data;
	var Data="<table id=\"example2\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNo</th><th>Item Name</th><th>Edit</th></tr></thead><tbody>";
	for (x=0;x<GearArray.length;x++)
	{
		Data+="<tr>";	
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+GearArray[x][2]+" teeth "+GearArray[x][3]+" gear dia "+GearArray[x][4]+" "+GearArray[x][5]+" face "+GearArray[x][6]+" "+GearArray[x][7]+" ("+GearArray[x][8]+GearArray[x][9]+")</td>";
		txt="Edit"+x;
		Data+="<td><input type=\"button\" name=\""+txt+"\" id=\""+txt+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light float-right\" onClick='EditGear("+x+")' ></td>";
		Data+="</tr>";
	}
	Data+="</tbody></table>";
	$("#dvGearShow").html(Data);
	Gearconcat();
	tableCss();
	
}

function EditGear(x)
{
	document.getElementById("txtGearTeeth").value=GearArray[x][2];
	document.getElementById("cmbGearType").value=GearArray[x][3];
	document.getElementById("txtGearDia").value=GearArray[x][4];
	document.getElementById("cmbGearDiaType").value=GearArray[x][5];
	document.getElementById("txtGearFace").value=GearArray[x][6];
	document.getElementById("cmbGearFaceType").value=GearArray[x][7];
	document.getElementById("txtGearProcessing").value=GearArray[x][8];
	document.getElementById("cmbGearProcessingType").value=GearArray[x][9];
	GearModcount=x;
	document.getElementById("txtGearTeeth").focus();
}

function Gearconcat()
{
	var packed="";
	var x;
	document.getElementById("hdGearData").value="";
	for (x=0;x<GearArray.length;x++)
	{
		if (packed=='')
		{
			packed=GearArray[x][0]+"~ArrayItem~"+GearArray[x][1]+"~ArrayItem~"+GearArray[x][2]+"~ArrayItem~"+GearArray[x][3]+"~ArrayItem~"+GearArray[x][4]+"~ArrayItem~"+GearArray[x][5]+"~ArrayItem~"+GearArray[x][6]+"~ArrayItem~"+GearArray[x][7]+"~ArrayItem~"+GearArray[x][8]+"~ArrayItem~"+GearArray[x][9];
		}
		else
		{
			packed=packed+"~Array~"+GearArray[x][0]+"~ArrayItem~"+GearArray[x][1]+"~ArrayItem~"+GearArray[x][2]+"~ArrayItem~"+GearArray[x][3]+"~ArrayItem~"+GearArray[x][4]+"~ArrayItem~"+GearArray[x][5]+"~ArrayItem~"+GearArray[x][6]+"~ArrayItem~"+GearArray[x][7]+"~ArrayItem~"+GearArray[x][8]+"~ArrayItem~"+GearArray[x][9];
		}
	}
	
	document.getElementById("hdGearData").value=packed;
}

	// Pinion Starts here

function EditPinionInfo()
{
	val=document.getElementById("hdPinionData").value;
	if (val!="")
	{
		geardata=val.split("~Array~");
		for (i=0;i<geardata.length;i++)
		{
			sel=(geardata[i]).split("~ArrayItem~");
			lenGear=PinionArray.length;
			PinionArray[lenGear]=new Array();
			PinionArray[lenGear][0]=sel[0];  // Id
			PinionArray[lenGear][1]=sel[1];  // Whole Item Name
			PinionArray[lenGear][2]=sel[2];  // teeth
			PinionArray[lenGear][3]=sel[3];  // Item Type
			PinionArray[lenGear][4]=sel[4];  //  Dia
			PinionArray[lenGear][5]=sel[5];  // Dia Type
			PinionArray[lenGear][6]=sel[6];  // Face 
			PinionArray[lenGear][7]=sel[7];	// Face Type
			PinionArray[lenGear][8]=sel[8];	// DMValue
			PinionArray[lenGear][9]=sel[9];   // DMType
		}
		DisplayPinion();
	}
}
function ModifyPinion(Id)
{

	$("#"+Id).attr("disabled",true);
	
	PinionArray[PinionModcount][2]=document.getElementById("txtPinionTeeth").value;
	PinionArray[PinionModcount][3]=document.getElementById("cmbPinionType").value;
	PinionArray[PinionModcount][4]=document.getElementById("txtPinionDia").value;
	PinionArray[PinionModcount][5]=document.getElementById("cmbPinionDiaType").value;
	PinionArray[PinionModcount][6]=document.getElementById("txtPinionFace").value;
	PinionArray[PinionModcount][7]=document.getElementById("cmbPinionFaceType").value;
	PinionArray[PinionModcount][8]=document.getElementById("txtPinionProcessing").value;
	PinionArray[PinionModcount][9]=document.getElementById("cmbPinionProcessingType").value;
	document.getElementById("txtPinionTeeth").value="";
	document.getElementById("cmbPinionType").value="";
	document.getElementById("txtPinionDia").value="";
	document.getElementById("cmbPinionDiaType").value="";
	document.getElementById("txtPinionFace").value="";
	document.getElementById("cmbPinionFaceType").value="";
	document.getElementById("txtPinionProcessing").value="";
	document.getElementById("cmbPinionProcessingType").value="";
	DisplayPinion();
}

function DisplayPinion()
{
	$("#dvPinionShow").html('');
	dvGearInfo=document.getElementById("dvPinionShow");
	dvGearInfo.innerHTML="";
	var x;
	//var Data;
	var Data="<table id=\"example3\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNo</th><th> Item Name</th><th>Edit</th></tr><thead><tbody>";
	for (x=0;x<PinionArray.length;x++)
	{
		Data+="<tr>";	
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+PinionArray[x][2]+" teeth "+PinionArray[x][3]+" pinion dia "+PinionArray[x][4]+" "+PinionArray[x][5]+" face "+PinionArray[x][6]+" "+PinionArray[x][7]+" ("+PinionArray[x][8]+PinionArray[x][9]+")</td>";
		txt="Edit"+x;
		Data+="<td><input type=\"button\" name=\""+txt+"\" id=\""+txt+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick='EditPinion("+x+")' ></td>";
		Data+="</tr>";
	}
	Data+="</tbody></table>";
	$("#dvPinionShow").html(Data);
	$("#btnPinionEdit").attr("disabled",false);
	Pinionconcat();
	tableCss();
}

function EditPinion(x)
{
	document.getElementById("txtPinionTeeth").value=PinionArray[x][2];
	document.getElementById("cmbPinionType").value=PinionArray[x][3];
	document.getElementById("txtPinionDia").value=PinionArray[x][4];
	document.getElementById("cmbPinionDiaType").value=PinionArray[x][5];
	document.getElementById("txtPinionFace").value=PinionArray[x][6];
	document.getElementById("cmbPinionFaceType").value=PinionArray[x][7];
	document.getElementById("txtPinionProcessing").value=PinionArray[x][8];
	document.getElementById("cmbPinionProcessingType").value=PinionArray[x][9];
	PinionModcount=x;
	document.getElementById("txtPinionTeeth").focus();
}

function Pinionconcat()
{
	var packed="";
	var x;
	document.getElementById("hdPinionData").value="";
	for (x=0;x<PinionArray.length;x++)
	{
		if (packed=="")
		{
			packed=PinionArray[x][0]+"~ArrayItem~"+PinionArray[x][1]+"~ArrayItem~"+PinionArray[x][2]+"~ArrayItem~"+PinionArray[x][3]+"~ArrayItem~"+PinionArray[x][4]+"~ArrayItem~"+PinionArray[x][5]+"~ArrayItem~"+PinionArray[x][6]+"~ArrayItem~"+PinionArray[x][7]+"~ArrayItem~"+PinionArray[x][8]+"~ArrayItem~"+PinionArray[x][9];
		}
		else
		{
			packed=packed+"~Array~"+PinionArray[x][0]+"~ArrayItem~"+PinionArray[x][1]+"~ArrayItem~"+PinionArray[x][2]+"~ArrayItem~"+PinionArray[x][3]+"~ArrayItem~"+PinionArray[x][4]+"~ArrayItem~"+PinionArray[x][5]+"~ArrayItem~"+PinionArray[x][6]+"~ArrayItem~"+PinionArray[x][7]+"~ArrayItem~"+PinionArray[x][8]+"~ArrayItem~"+PinionArray[x][9];
		}
	}
	document.getElementById("hdPinionData").value=packed;
}

	// Pinion ends here
	
	
	// Shaft pinion starts here
	
function EditShaftPinionInfo()
{
	val=document.getElementById("hdShaftPinionData").value;
	if (val!="")
	{
		geardata=val.split("~Array~");
		for (i=0;i<geardata.length;i++)
		{
			sel=(geardata[i]).split("~ArrayItem~");
			lenGear=ShaftPinionArray.length;
			ShaftPinionArray[lenGear]=new Array();
			ShaftPinionArray[lenGear][0]=sel[0];  // Id
			ShaftPinionArray[lenGear][1]=sel[1];  // Whole Item Name
			ShaftPinionArray[lenGear][2]=sel[2];  // teeth
			ShaftPinionArray[lenGear][3]=sel[3];  // Item Type
			ShaftPinionArray[lenGear][4]=sel[4];  //  Dia
			ShaftPinionArray[lenGear][5]=sel[5];  // Dia Type
			ShaftPinionArray[lenGear][6]=sel[6];  // Face 
			ShaftPinionArray[lenGear][7]=sel[7];	// Face Type
			ShaftPinionArray[lenGear][8]=sel[8];	// DMValue
			ShaftPinionArray[lenGear][9]=sel[9];   // DMType
		}
		DisplayShaftPinion();
	}
}
function ModifyShaftPinion()
{
	ShaftPinionArray[ShaftPinionModcount][2]=document.getElementById("txtShaftPinionTeeth").value;
	ShaftPinionArray[ShaftPinionModcount][3]=document.getElementById("cmbShaftPinionType").value;
	ShaftPinionArray[ShaftPinionModcount][4]=document.getElementById("txtShaftPinionDia").value;
	ShaftPinionArray[ShaftPinionModcount][5]=document.getElementById("cmbShaftPinionDiaType").value;
	ShaftPinionArray[ShaftPinionModcount][6]=document.getElementById("txtShaftPinionFace").value;
	ShaftPinionArray[ShaftPinionModcount][7]=document.getElementById("cmbShaftPinionFaceType").value;
	ShaftPinionArray[ShaftPinionModcount][8]=document.getElementById("txtShaftPinionProcessing").value;
	ShaftPinionArray[ShaftPinionModcount][9]=document.getElementById("cmbShaftPinionProcessingType").value;
	document.getElementById("txtShaftPinionTeeth").value="";
	document.getElementById("cmbShaftPinionType").value="";
	document.getElementById("txtShaftPinionDia").value="";
	document.getElementById("cmbShaftPinionDiaType").value="";
	document.getElementById("txtShaftPinionFace").value="";
	document.getElementById("cmbShaftPinionFaceType").value="";
	document.getElementById("txtShaftPinionProcessing").value="";
	document.getElementById("cmbShaftPinionProcessingType").value="";
	DisplayShaftPinion();
}

function DisplayShaftPinion()
{
	$("#dvShaftPinionShow").html('');
	
	var x;
	//var Data;
	var Data="<table id=\"example4\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNo</th><th> Item Name</th><th>Edit</th></tr></thead><tbody>";
	for (x=0;x<ShaftPinionArray.length;x++)
	{
		Data+="<tr>";	
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+ShaftPinionArray[x][2]+" teeth "+ShaftPinionArray[x][3]+" dia "+ShaftPinionArray[x][4]+" "+ShaftPinionArray[x][5]+" face "+ShaftPinionArray[x][6]+" "+ShaftPinionArray[x][7]+" ("+ShaftPinionArray[x][8]+ShaftPinionArray[x][9]+")</td>";
		txt="Edit"+x;
		Data+="<td><input type=\"button\" name=\""+txt+"\" id=\""+txt+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick='EditShaftPinion("+x+")' ></td>";
		Data+="</tr>";
	}
	Data+="</tbody></table>";
	$("#dvShaftPinionShow").html(Data);
	ShaftPinionconcat();
	tableCss();
}

function EditShaftPinion(x)
{
	document.getElementById("txtShaftPinionTeeth").value=ShaftPinionArray[x][2];
	document.getElementById("cmbShaftPinionType").value=ShaftPinionArray[x][3];
	document.getElementById("txtShaftPinionDia").value=ShaftPinionArray[x][4];
	document.getElementById("cmbShaftPinionDiaType").value=ShaftPinionArray[x][5];
	document.getElementById("txtShaftPinionFace").value=ShaftPinionArray[x][6];
	document.getElementById("cmbShaftPinionFaceType").value=ShaftPinionArray[x][7];
	document.getElementById("txtShaftPinionProcessing").value=ShaftPinionArray[x][8];
	document.getElementById("cmbShaftPinionProcessingType").value=ShaftPinionArray[x][9];
	ShaftPinionModcount=x;
	document.getElementById("txtShaftPinionTeeth").focus();
}

function ShaftPinionconcat()
{
	var packed="";
	var x;
	document.getElementById("hdShaftPinionData").value="";
	for (x=0;x<ShaftPinionArray.length;x++)
	{
		if (packed=="")
		{
			packed=ShaftPinionArray[x][0]+"~ArrayItem~"+ShaftPinionArray[x][1]+"~ArrayItem~"+ShaftPinionArray[x][2]+"~ArrayItem~"+ShaftPinionArray[x][3]+"~ArrayItem~"+ShaftPinionArray[x][4]+"~ArrayItem~"+ShaftPinionArray[x][5]+"~ArrayItem~"+ShaftPinionArray[x][6]+"~ArrayItem~"+ShaftPinionArray[x][7]+"~ArrayItem~"+ShaftPinionArray[x][8]+"~ArrayItem~"+ShaftPinionArray[x][9];
		}
		else
		{
			packed=packed+"~Array~"+ShaftPinionArray[x][0]+"~ArrayItem~"+ShaftPinionArray[x][1]+"~ArrayItem~"+ShaftPinionArray[x][2]+"~ArrayItem~"+ShaftPinionArray[x][3]+"~ArrayItem~"+ShaftPinionArray[x][4]+"~ArrayItem~"+ShaftPinionArray[x][5]+"~ArrayItem~"+ShaftPinionArray[x][6]+"~ArrayItem~"+ShaftPinionArray[x][7]+"~ArrayItem~"+ShaftPinionArray[x][8]+"~ArrayItem~"+ShaftPinionArray[x][9];
		}
	}
	document.getElementById("hdShaftPinionData").value=packed;
}
		// Shaft pinion ends here	
	
		// Bevel Gears starts here
		
function EditBevelGearInfo()
{
	val=document.getElementById("hdBevelGearData").value;
	if (val!="")
	{
		geardata=val.split("~Array~");
		for (i=0;i<geardata.length;i++)
		{
			sel=(geardata[i]).split("~ArrayItem~");
			lenGear=BevelGearArray.length;
			BevelGearArray[lenGear]=new Array();
			BevelGearArray[lenGear][0]=sel[0];  // Id
			BevelGearArray[lenGear][1]=sel[1];  // Whole Item Name
			BevelGearArray[lenGear][2]=sel[2];  // teeth
			BevelGearArray[lenGear][3]=sel[3];  //  Dia
			BevelGearArray[lenGear][4]=sel[4];  // Dia Type
			BevelGearArray[lenGear][5]=sel[5];	// DMValue
			BevelGearArray[lenGear][6]=sel[6];   // DMType
		}
		DisplayBevelGear();
	}
}
function ModifyBevelGear()
{
	BevelGearArray[BevelGearModcount][2]=document.getElementById("txtBevelGearTeeth").value;
	BevelGearArray[BevelGearModcount][3]=document.getElementById("txtBevelGearDia").value;
	BevelGearArray[BevelGearModcount][4]=document.getElementById("cmbBevelGearDiaType").value;
	BevelGearArray[BevelGearModcount][5]=document.getElementById("txtBevelGearProcessing").value;
	BevelGearArray[BevelGearModcount][6]=document.getElementById("cmbBevelGearProcessingType").value;
	document.getElementById("txtBevelGearTeeth").value="";
	document.getElementById("txtBevelGearDia").value="";
	document.getElementById("cmbBevelGearDiaType").value="";
	document.getElementById("txtBevelGearProcessing").value="";
	document.getElementById("cmbBevelGearProcessingType").value="";
	DisplayBevelGear();
}

function DisplayBevelGear()
{
	$("#dvBevelGearShow").html('');
	
	var x;
	
	var Data="<table id=\"example5\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNo</th><th>Item Name</th><th>Edit</th></tr></thead><tbody>";
	for (x=0;x<BevelGearArray.length;x++)
	{
		Data+="<tr>";	
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+BevelGearArray[x][2]+" teeth dia "+BevelGearArray[x][3]+" "+BevelGearArray[x][4]+" ("+BevelGearArray[x][5]+BevelGearArray[x][6]+")</td>";
		txt="Edit"+x;
		Data+="<td><input type=\"button\" name=\""+txt+"\" id=\""+txt+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick='EditBevelGear("+x+")' ></td>";
		Data+="</tr>";
	}
	Data+="</tbody></table>";
	$("#dvBevelGearShow").html(Data);
	
	BevelGearconcat();
	tableCss();
}

function EditBevelGear(x)
{
	document.getElementById("txtBevelGearTeeth").value=BevelGearArray[x][2];
	document.getElementById("txtBevelGearDia").value=BevelGearArray[x][3];
	document.getElementById("cmbBevelGearDiaType").value=BevelGearArray[x][4];
	document.getElementById("txtBevelGearProcessing").value=BevelGearArray[x][5];
	document.getElementById("cmbBevelGearProcessingType").value=BevelGearArray[x][6];
	BevelGearModcount=x;
	document.getElementById("txtBevelGearTeeth").focus();
}

function BevelGearconcat()
{
	var packed="";
	var x;
	document.getElementById("hdBevelGearData").value="";
	for (x=0;x<BevelGearArray.length;x++)
	{
		if (packed=="")
		{
			packed=BevelGearArray[x][0]+"~ArrayItem~"+BevelGearArray[x][1]+"~ArrayItem~"+BevelGearArray[x][2]+"~ArrayItem~"+BevelGearArray[x][3]+"~ArrayItem~"+BevelGearArray[x][4]+"~ArrayItem~"+BevelGearArray[x][5]+"~ArrayItem~"+BevelGearArray[x][6];
		}
		else
		{
			packed=packed+"~Array~"+BevelGearArray[x][0]+"~ArrayItem~"+BevelGearArray[x][1]+"~ArrayItem~"+BevelGearArray[x][2]+"~ArrayItem~"+BevelGearArray[x][3]+"~ArrayItem~"+BevelGearArray[x][4]+"~ArrayItem~"+BevelGearArray[x][5]+"~ArrayItem~"+BevelGearArray[x][6];
		}
	}
	document.getElementById("hdBevelGearData").value=packed;
}
	
			// Bevel Gears ends here
			
			// Bevel Pinion starts here
function EditBevelPinionInfo()
{
	val=document.getElementById("hdBevelPinionData").value;
	if (val!="")
	{
		geardata=val.split("~Array~");
		for (i=0;i<geardata.length;i++)
		{
			sel=(geardata[i]).split("~ArrayItem~");
			lenGear=BevelPinionArray.length;
			BevelPinionArray[lenGear]=new Array();
			BevelPinionArray[lenGear][0]=sel[0];  // Id
			BevelPinionArray[lenGear][1]=sel[1];  // Whole Item Name
			BevelPinionArray[lenGear][2]=sel[2];  // teeth
			BevelPinionArray[lenGear][3]=sel[3];  //  Dia
			BevelPinionArray[lenGear][4]=sel[4];  // Dia Type
			BevelPinionArray[lenGear][5]=sel[5];	// DMValue
			BevelPinionArray[lenGear][6]=sel[6];   // DMType
		}
		DisplayBevelPinion();
	}
}
function ModifyBevelPinion()
{
	BevelPinionArray[BevelPinionModcount][2]=document.getElementById("txtBevelPinionTeeth").value;
	BevelPinionArray[BevelPinionModcount][3]=document.getElementById("txtBevelPinionDia").value;
	BevelPinionArray[BevelPinionModcount][4]=document.getElementById("cmbBevelPinionDiaType").value;
	BevelPinionArray[BevelPinionModcount][5]=document.getElementById("txtBevelPinionProcessing").value;
	BevelPinionArray[BevelPinionModcount][6]=document.getElementById("cmbBevelPinionProcessingType").value;
	document.getElementById("txtBevelPinionTeeth").value="";
	document.getElementById("txtBevelPinionDia").value="";
	document.getElementById("cmbBevelPinionDiaType").value="";
	document.getElementById("txtBevelPinionProcessing").value="";
	document.getElementById("cmbBevelPinionProcessingType").value="";
	DisplayBevelPinion();
}

function DisplayBevelPinion()
{
	$("#dvBevelPinionShow").html('');
	
	var x;
	//var Data;
	var Data="<table id=\"example6\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNo</th><th> Item Name</th><th>Edit</th></tr></thead><tbody>";
	for (x=0;x<BevelPinionArray.length;x++)
	{
		Data+="<tr>";	
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+BevelPinionArray[x][2]+" teeth dia "+BevelPinionArray[x][3]+" "+BevelPinionArray[x][4]+" ("+BevelPinionArray[x][5]+BevelPinionArray[x][6]+")</td>";
		txt="Edit"+x;
		Data+="<td><input type=\"button\" name=\""+txt+"\" id=\""+txt+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick='EditBevelPinion("+x+")' ></td>";
		Data+="</tr>";
	}
	Data+="</tbody></table>";
	
	$("#dvBevelPinionShow").html(Data);
	BevelPinionconcat();
	
}

function EditBevelPinion(x)
{
	document.getElementById("txtBevelPinionTeeth").value=BevelPinionArray[x][2];
	document.getElementById("txtBevelPinionDia").value=BevelPinionArray[x][3];
	document.getElementById("cmbBevelPinionDiaType").value=BevelPinionArray[x][4];
	document.getElementById("txtBevelPinionProcessing").value=BevelPinionArray[x][5];
	document.getElementById("cmbBevelPinionProcessingType").value=BevelPinionArray[x][6];
	BevelPinionModcount=x;
	document.getElementById("txtBevelPinionTeeth").focus();
}

function BevelPinionconcat()
{
	var packed="";
	var x;
	document.getElementById("hdBevelPinionData").value="";
	for (x=0;x<BevelPinionArray.length;x++)
	{
		if (packed=="")
		{
			packed=BevelPinionArray[x][0]+"~ArrayItem~"+BevelPinionArray[x][1]+"~ArrayItem~"+BevelPinionArray[x][2]+"~ArrayItem~"+BevelPinionArray[x][3]+"~ArrayItem~"+BevelPinionArray[x][4]+"~ArrayItem~"+BevelPinionArray[x][5]+"~ArrayItem~"+BevelPinionArray[x][6];
		}
		else
		{
			packed=packed+"~Array~"+BevelPinionArray[x][0]+"~ArrayItem~"+BevelPinionArray[x][1]+"~ArrayItem~"+BevelPinionArray[x][2]+"~ArrayItem~"+BevelPinionArray[x][3]+"~ArrayItem~"+BevelPinionArray[x][4]+"~ArrayItem~"+BevelPinionArray[x][5]+"~ArrayItem~"+BevelPinionArray[x][6];
		}
	}
	document.getElementById("hdBevelPinionData").value=packed;
}
		// Bevel Pinion ends here
		
		// Chain Gears starts here
function EditChainGearInfo()
{
	val=document.getElementById("hdChainGearData").value;
	if (val!="")
	{
		geardata=val.split("~Array~");
		for (i=0;i<geardata.length;i++)
		{
			sel=(geardata[i]).split("~ArrayItem~");
			lenGear=ChainGearArray.length;
			ChainGearArray[lenGear]=new Array();
			ChainGearArray[lenGear][0]=sel[0];  // Id
			ChainGearArray[lenGear][1]=sel[1];  // Whole Item Name
			ChainGearArray[lenGear][2]=sel[2];  // teeth
			ChainGearArray[lenGear][3]=sel[3];  //  Item Type
			ChainGearArray[lenGear][4]=sel[4];  // Dia 
			ChainGearArray[lenGear][5]=sel[5];	// Dia Type
			ChainGearArray[lenGear][6]=sel[6];   // Pitch
			ChainGearArray[lenGear][7]=sel[7];  // Pitch Type
		}
		DisplayChainGear();
	}
}
function ModifyChainGear()
{
	ChainGearArray[ChainGearModcount][2]=document.getElementById("txtChainGearTeeth").value;
	ChainGearArray[ChainGearModcount][3]=document.getElementById("cmbChainGearType").value;
	ChainGearArray[ChainGearModcount][4]=document.getElementById("txtChainGearDia").value;
	ChainGearArray[ChainGearModcount][5]=document.getElementById("cmbChainGearDiaType").value;
	ChainGearArray[ChainGearModcount][6]=document.getElementById("txtChainGearPitch").value;
	ChainGearArray[ChainGearModcount][7]=document.getElementById("cmbChainGearPitchType").value;
	document.getElementById("txtChainGearTeeth").value="";
	document.getElementById("cmbChainGearType").value="";
	document.getElementById("txtChainGearDia").value="";
	document.getElementById("cmbChainGearDiaType").value="";
	document.getElementById("txtChainGearPitch").value="";
	document.getElementById("cmbChainGearPitchType").value="";
	DisplayChainGear();
}

function DisplayChainGear()
{
	$("#dvChainGearShow").html('');
	var x;
	//var Data;
	var Data="<table id=\"example7\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNo</th><th>Item Name</th><th>Edit</th></tr></thead><tbody>";
	for (x=0;x<ChainGearArray.length;x++)
	{
		Data+="<tr>";	
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+ChainGearArray[x][2]+" teeth chain wheel "+ChainGearArray[x][3]+" dia "+ChainGearArray[x][4]+" "+ChainGearArray[x][5]+" pitch "+ChainGearArray[x][6]+ChainGearArray[x][7]+"</td>";
		txt="Edit"+x;
		Data+="<td><input type=\"button\" name=\""+txt+"\" id=\""+txt+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick='EditChainGear("+x+")' ></td>";
		Data+="</tr>";
	}
	Data+="</tbody></table>";
	$("#dvChainGearShow").html(Data);
	ChainGearconcat();
	tableCss();
	
}

function EditChainGear(x)
{
	document.getElementById("txtChainGearTeeth").value=ChainGearArray[x][2];
	document.getElementById("cmbChainGearType").value=ChainGearArray[x][3];
	document.getElementById("txtChainGearDia").value=ChainGearArray[x][4];
	document.getElementById("cmbChainGearDiaType").value=ChainGearArray[x][5];
	document.getElementById("txtChainGearPitch").value=ChainGearArray[x][6];
	document.getElementById("cmbChainGearPitchType").value=ChainGearArray[x][7];
	ChainGearModcount=x;
	document.getElementById("txtChainGearTeeth").focus();
}

function ChainGearconcat()
{
	var packed="";
	var x;
	document.getElementById("hdChainGearData").value="";
	for (x=0;x<ChainGearArray.length;x++)
	{
		if (packed=="")
		{
			packed=ChainGearArray[x][0]+"~ArrayItem~"+ChainGearArray[x][1]+"~ArrayItem~"+ChainGearArray[x][2]+"~ArrayItem~"+ChainGearArray[x][3]+"~ArrayItem~"+ChainGearArray[x][4]+"~ArrayItem~"+ChainGearArray[x][5]+"~ArrayItem~"+ChainGearArray[x][6]+"~ArrayItem~"+ChainGearArray[x][7];
		}
		else
		{
			packed=packed+"~Array~"+ChainGearArray[x][0]+"~ArrayItem~"+ChainGearArray[x][1]+"~ArrayItem~"+ChainGearArray[x][2]+"~ArrayItem~"+ChainGearArray[x][3]+"~ArrayItem~"+ChainGearArray[x][4]+"~ArrayItem~"+ChainGearArray[x][5]+"~ArrayItem~"+ChainGearArray[x][6]+"~ArrayItem~"+ChainGearArray[x][7];
		}
	}
	document.getElementById("hdChainGearData").value=packed;
}
		// Chain gears ends here	
			
		// Worm gears starts here
function EditWormGearInfo()
{
	val=document.getElementById("hdWormGearData").value;
	if (val!="")
	{
		geardata=val.split("~Array~");
		for (i=0;i<geardata.length;i++)
		{
			sel=(geardata[i]).split("~ArrayItem~");
			lenGear=WormGearArray.length;
			WormGearArray[lenGear]=new Array();
			WormGearArray[lenGear][0]=sel[0];  // Id
			WormGearArray[lenGear][1]=sel[1];  // Whole Item Name
			WormGearArray[lenGear][2]=sel[2];  // teeth
			WormGearArray[lenGear][3]=sel[3];  //  Dia
			WormGearArray[lenGear][4]=sel[4];  // Dia Type
			WormGearArray[lenGear][5]=sel[5];	// DMValue
			WormGearArray[lenGear][6]=sel[6];   // DMType
		}
		DisplayWormGear();
	}
}

function ModifyWormGear()
{
	WormGearArray[WormGearModcount][2]=document.getElementById("txtWormGearTeeth").value;
	WormGearArray[WormGearModcount][3]=document.getElementById("txtWormGearDia").value;
	WormGearArray[WormGearModcount][4]=document.getElementById("cmbWormGearDiaType").value;
	WormGearArray[WormGearModcount][5]=document.getElementById("txtWormGearProcessing").value;
	WormGearArray[WormGearModcount][6]=document.getElementById("cmbWormGearProcessingType").value;
	document.getElementById("txtWormGearTeeth").value="";
	document.getElementById("txtWormGearDia").value="";
	document.getElementById("cmbWormGearDiaType").value="";
	document.getElementById("txtWormGearProcessing").value="";
	document.getElementById("cmbWormGearProcessingType").value="";
	DisplayWormGear();
}
		
function DisplayWormGear()
{
	$("#dvWormGearShow").html('');
	
	var x;
	
	var Data="<table id=\"example8\" class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
	Data+="<thead><tr><th>SNo</th><th>Item Name</th><th>Edit</th></tr></thead><tbody>";
	for (x=0;x<WormGearArray.length;x++)
	{
		Data+="<tr>";	
		Data+="<td>"+(x+1)+"</td>";
		Data+="<td>"+WormGearArray[x][2]+" teeth dia "+WormGearArray[x][3]+" "+WormGearArray[x][4]+" ("+WormGearArray[x][5]+WormGearArray[x][6]+")</td>";
		txt="Edit"+x;
		Data+="<td><input type=\"button\" name=\""+txt+"\" id=\""+txt+"\" value=\"Edit\" class=\"btn btn-primary waves-effect waves-light\" onClick='EditWormGear("+x+")' ></td>";
		Data+="</tr>";
	}
	Data+="</tbody></table>";
	$("#dvWormGearShow").html(Data);
	tableCss();
	WormGearconcat();
	
	
	
}

function EditWormGear(x)
{
	document.getElementById("txtWormGearTeeth").value=WormGearArray[x][2];
	document.getElementById("txtWormGearDia").value=WormGearArray[x][3];
	document.getElementById("cmbWormGearDiaType").value=WormGearArray[x][4];
	document.getElementById("txtWormGearProcessing").value=WormGearArray[x][5];
	document.getElementById("cmbWormGearProcessingType").value=WormGearArray[x][6];
	WormGearModcount=x;
	document.getElementById("txtWormGearTeeth").focus();
}

function WormGearconcat()
{
	var packed="";
	var x;
	$("#hdWormGearData").val("");
	
	for (x=0;x<WormGearArray.length;x++)
	{
		if (packed==="")
		{
			packed=WormGearArray[x][0]+"~ArrayItem~"+WormGearArray[x][1]+"~ArrayItem~"+WormGearArray[x][2]+"~ArrayItem~"+WormGearArray[x][3]+"~ArrayItem~"+WormGearArray[x][4]+"~ArrayItem~"+WormGearArray[x][5]+"~ArrayItem~"+WormGearArray[x][6];
		}
		else
		{
			
			packed=packed+"~Array~"+WormGearArray[x][0]+"~ArrayItem~"+WormGearArray[x][1]+"~ArrayItem~"+WormGearArray[x][2]+"~ArrayItem~"+WormGearArray[x][3]+"~ArrayItem~"+WormGearArray[x][4]+"~ArrayItem~"+WormGearArray[x][5]+"~ArrayItem~"+WormGearArray[x][6];
		}
		
	}
	
	$("#hdWormGearData").val(packed);
	
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