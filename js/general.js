function Reset()
{	
	document.location=document.location.href;
	return true;
}
var pageurl='';
var MenuArray=new Array();
/*function setFrameHeight(Id)
{
	ctrl=document.getElementById(Id);
	height=ctrl.contentWindow.document.body.offsetHeight;
	ctrl.height=height;
	alert(height);
}*/
function focusFirstElement() 
{
	ctrl=document.getElementById(document.getElementById("formfocuselement").value);
	ctrl.disabled=false;
	ctrl.focus();
}
function MenuEnable(page) 
{
	for(i=0;i<MenuArray.length;i++)
	{
		if(MenuArray[i][0]==page)
		{
			document.getElementById(MenuArray[i][0]).style['color']="#4262CA";
			height=parseInt(MenuArray[i][2]); 
		}
		else
		{
			document.getElementById(MenuArray[i][0]).style['color']="#006666";
		}
	}
}
function GetLeftMenu()
{
	menuData=document.getElementById('MenuData').value;
	tempdata=menuData.split('~MenuArray~');
	for(i=0;i<tempdata.length;i++)
	{
		MenuArray[i]=new Array();
		temp=tempdata[i].split('~MenuItem~');
		for(j=0;j<temp.length;j++)
		{
			MenuArray[i][j]=temp[j];
		}
	}
}
function menuclick(Id,url)
{
	var height=0; 
	if(pageurl!=url)
	{
		for(i=0;i<MenuArray.length;i++)
		{
			if(MenuArray[i][0]==Id)
			{
				document.getElementById(MenuArray[i][0]).style['color']="#4262CA";
			}
			else
			{
				document.getElementById(MenuArray[i][0]).style['color']="#006666";
			}
		}
		pageurl=url;
		
		document.getElementById('iframe1').src=url;
		//event.returnValue=false;
	}
	//event.returnValue=false;
}
function NumericFieldFocus(Id)
{
	if(document.getElementById(Id).value!='' && parseFloat(document.getElementById(Id).value)==0)
	{
		document.getElementById(Id).value='';
		document.getElementById(Id).select();
	}
}
function EnterKeyClick(Id)
{
	if(event.keyCode==13)
	{
		event.keyCode=32;
		return event.keyCode;
	}
}
var sft=false;
function EnterKeyHandle()
{
	if (event.keyCode==13) 
	{
		event.keyCode=9; 
		return event.keyCode; 
	}
	if(event.keyCode==16)
	{
		sft=true;
	}
	if(event.keyCode==222 && sft==true)
	{
		event.returnValue=false;
	}
}
function ShiftUp()
{
	if(event.keyCode==16)
	{
		sft=false;
	}
}
function onlyCaps(Id)
{	
	if((event.keyCode>=65&&event.keyCode<=90)||(event.keyCode>=97&&event.keyCode<=122))
    {
        var txtData=document.getElementById(Id).value;			        	        
        document.getElementById(Id).value=txtData.toUpperCase();
    }
}

function onlyLower(Id)
{
	if((event.keyCode>=65&&event.keyCode<=90)||(event.keyCode>=97&&event.keyCode<=122))
    {
        var txtData=document.getElementById(Id).value;			        	        
        document.getElementById(Id).value=txtData.toLowerCase();
    }
}
function OnlyNumericWithMinus(Id)
{ 
	lbl='lblErrMsg';
	lbl.innerHTML='';
	
	if((event.keyCode>=48 && event.keyCode<=57)|| (event.keyCode>=96 && event.keyCode<=105)|| (event.keyCode == 110 || event.keyCode == 109 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 35|| event.keyCode == 36|| event.keyCode == 37|| event.keyCode == 39|| event.keyCode == 13 || event.keyCode == 16 || event.keyCode == 17 || event.keyCode == 18 || event.keyCode == 20 || event.keyCode == 116 || event.keyCode ==144 ||event.keyCode ==190 ))	
	{
	document.getElementById(lbl).innerHTML='';
	}
	else
	{      
		document.getElementById(lbl).innerHTML="Please Enter Numeric Value...";
		event.returnValue=false;
	}
}
function OnlyNumeric(Id)
{ 
	lbl=Id.replace('txt','lbl');
	lbl.innerHTML='';
	if((event.keyCode>=48 && event.keyCode<=57)|| (event.keyCode>=96 && event.keyCode<=105)|| (event.keyCode == 110 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 35|| event.keyCode == 36|| event.keyCode == 37|| event.keyCode == 39|| event.keyCode == 13 || event.keyCode == 16 || event.keyCode == 17 || event.keyCode == 18 || event.keyCode == 20 || event.keyCode == 116 || event.keyCode ==144 ||event.keyCode ==190 ))	
	{
		document.getElementById(lbl).innerHTML='';
	}
	else
	{      
		document.getElementById(lbl).innerHTML="Please Enter Numeric Value...";
		event.returnValue=false;
	}
}

function OnlyNumeric1(Id)
{ 
	
	if((event.keyCode>=48 && event.keyCode<=57)|| (event.keyCode>=96 && event.keyCode<=105)|| (event.keyCode == 110 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 35|| event.keyCode == 36|| event.keyCode == 37|| event.keyCode == 39|| event.keyCode == 13 || event.keyCode == 16 || event.keyCode == 17 || event.keyCode == 18 || event.keyCode == 20 || event.keyCode == 116 || event.keyCode ==144 ||event.keyCode ==190 ))	
	{
	//document.getElementById(lbl).innerHTML='';
	}
	else
	{    
        toastr.error("Please Enter Numeric Value...");
		//document.getElementById(lbl).innerHTML="Please Enter Numeric Value...";
		event.returnValue=false;
	}
}

function OnlyInt(Id)
{ 	
	// lbl=Id.replace('txt','lbl');
	// lbl.innerHTML='';
	if((event.keyCode>=48 && event.keyCode<=57)|| (event.keyCode>=96 && event.keyCode<=105) || (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 35 || event.keyCode == 36 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 144 || event.keyCode == 13 || event.keyCode == 116))	
	{
	//document.getElementById(lbl).innerHTML="";
	}
	else
	{      
        toastr.error("Please Enter Numeric Value...");
		//document.getElementById(lbl).innerHTML="Please Enter Numeric Value...";
		event.returnValue=false;
	}
}

function OnlyInt1(Id)
{ 	
	
	if(isNaN($("#"+Id).val()) || $("#"+Id).val()<0)
	{
		toastr.error("Please enter valid value");
		$("#"+Id).focus();
		return false;
	}
}

function OnlyNumber(Id)
{
	var numtofix=parseFloat(document.getElementById(Id).value);
	if(!isNaN(numtofix))
	{
	numtofix=numtofix.toFixed(0);
	document.getElementById(Id).value=numtofix;
	}
	else
	{
		document.getElementById(Id).value=(0.00).toFixed(0);
	}
}
function DecimalNum(Id)
{
	var numtofix=parseFloat(document.getElementById(Id).value);
	if(!isNaN(numtofix))
	{
		numtofix=numtofix.toFixed(2);
		document.getElementById(Id).value=numtofix;
	}
	else
	{
		document.getElementById(Id).value=(0.00).toFixed(2);
	}
}
function onlyGrams(Id)
{
	var numtofix=parseFloat(document.getElementById(Id).value);
	if(!isNaN(numtofix))
	{
	numtofix=numtofix.toFixed(3);
	document.getElementById(Id).value=numtofix;
	}
	else
	{
	document.getElementById(Id).value=(0.000).toFixed(3);
	}
}

// date comparison
function getDateObject(dateString,dateSeperator)
{
	//This function return a date object after accepting 
	//a date string ans dateseparator as arguments
	var curValue=dateString;
	var sepChar=dateSeperator;
	var curPos=0;
	var cDate,cMonth,cYear;

	//extract day portion
	curPos=dateString.indexOf(sepChar);
	cDate=dateString.substring(0,curPos);
	
	//extract month portion				
	endPos=dateString.indexOf(sepChar,curPos+1);
	
	cMonth=dateString.substring(curPos+1,endPos);
	
	//extract year portion				
	curPos=endPos;
	endPos=curPos+5;			
	cYear=curValue.substring(curPos+1,endPos);
	
	//Create Date Object
	dtObject=new Date(cYear,cMonth,cDate);	
	return dtObject;
}

function ChkDate(txtFromDate, txtToDate)
{	
	var dt1=getDateObject(txtFromDate,"/");
	var dt2=getDateObject(txtToDate,"/");
	
	if(dt1 > dt2)
	{
		alert("Date should not be greater than Current date...");
		//document.getElementById(txtToDate).focus();
		//event.returnValue=false;
		return 1;
	}			
}


var alt=false;
function TextAreaNewLine(Id)
{
	if(event.keyCode==18)
	{
		alt=true;
	}
	else if(alt && event.keyCode==13)
	{
		val=document.getElementById(Id).value;
		val=val+"\n"+"";
		document.getElementById(Id).value=val;
		alt=false;
	}
	else if((!alt && event.keyCode==13) || event.keyCode==9)
	{
		alt=false;
	}
	else
	{
		alt=false;
	}
}

function TextAreaNewLine1(Id)
{
	lbl=document.getElementById('lblalert');
	if(event.keyCode==18)
	{
		alt=true;
	}
	else if(alt && event.keyCode==13)
	{
		val=document.getElementById(Id).value;
		val=val+"\n"+"";
		document.getElementById(Id).value=val;
		alt=false;
		lbl.innerHTML='';
	}
	else if((!alt && event.keyCode==13) || event.keyCode==9)
	{
		alt=false;
		lbl.innerHTML='';
	}
	else
	{
		alt=false;
		lbl.innerHTML='(Use Alt+Enter for new line...)';
	}
}

function MaxCharCount(Id,length)
{
	var maxlen=length;
	var len=document.getElementById(Id).value.length;
	if (len>maxlen)
	{
		document.getElementById(Id).value=document.getElementById(Id).value.substr(0,maxlen);
		event.returnValue=false;
	}
}

function PhCharCount(Id)
{
	var maxlen=100;
	var len=document.getElementById(Id).value.length;
	lbl=Id.replace('txt','lbl');
	if (len>maxlen)
	{
		document.getElementById(lbl).innerHTML='No more Characters can be Entered...';
		document.getElementById(Id).value=document.getElementById(Id).value.substr(0,100);
		//alert ("No more Characters can be Entered...");
		event.returnValue=false;
	}
	else
	{
	document.getElementById(lbl).innerHTML="";
	}	

}

function AddCharCount(Id)
{
	var maxlen=300;
	var len=document.getElementById(Id).value.length;
	lbl=Id.replace('txt','lbl');
	if (len>maxlen)
	{
		document.getElementById(lbl).innerHTML="No more Characters can be Entered...";
		document.getElementById(Id).value=document.getElementById(Id).value.substr(0,300);
		//alert ("No more Characters can be Entered...");
		event.returnValue=false;

	}
	else
	{
		document.getElementById(lbl).innerHTML="";
	}	
	
}

function EmailValidate(Id)
{
	lbl=Id.replace('txt','lbl');
	if (document.getElementById(Id).value!="")
	{
		if (checkmail(document.getElementById(Id).value) == false)
		{
			document.getElementById(lbl).innerHTML="Please enter valid Email Id";
			//alert("Please enter valid Email Id");
			document.getElementById("txtEmail").focus();
			event.returnValue=false;
		}
		else
		{
		document.getElementById(lbl).innerHTML="";
		}
	}
	else
	{
	document.getElementById(lbl).innerHTML="";
	}
}

var emailfilter=/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i
function checkmail(eid)
{
	return emailfilter.test(eid)
}

/*function onlycode(Id)
{
	if(document.getElementById(Id).value!='')
	{
		var prefixfilter=/^([a-z]+[a-z0-9\/]*)$/i
		ctrl=document.getElementById(Id);
		lbl=document.getElementById(Id.replace('txt','lbl'));
		lbl.innerHTML='';
		val=ctrl.value;
		if(!prefixfilter.test(val))
		{
		lbl.innerHTML='Code can hold a-z and 0-9 only (without space)...';
		//alert('Prefix can hold without space a-z0-9 only');
		ctrl.focus();
		}
		else
		{
		CheckCode(Id);
		}
	}
} */

function onlyCode(Id)
{
	if(!sft)
	{
		if((event.keyCode>=65&&event.keyCode<=90) || (event.keyCode>=96&&event.keyCode<=105) || (event.keyCode== 111 || event.keyCode== 189 || event.keyCode== 191) || (event.keyCode>=48 && event.keyCode<=57)||(event.keyCode==8 ||event.keyCode==9 ||event.keyCode==13))
		{
		   var txtData=document.getElementById(Id).value;			        	        
		   document.getElementById(Id).value=txtData.toUpperCase();
		}
		else
		{
			event.returnValue=false;
		}
	}
	else
	{
		if((event.keyCode>=65&&event.keyCode<=90) || event.keyCode== 111 || (event.keyCode>=96&&event.keyCode<=105) || (event.keyCode==8 ||event.keyCode==9 ||event.keyCode==13))
		{
		   var txtData=document.getElementById(Id).value;			        	        
		   document.getElementById(Id).value=txtData.toUpperCase();
		}
		else
		{
			event.returnValue=false;
		}
	}
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
function sleep1(Id)
{
	do
	{
	}while(document.getElementById(Id).value=='');
	return;
}