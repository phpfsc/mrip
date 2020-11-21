<?php
function amountinwords($AMT)
{
	$amount=array();
	$amt['1']="One";
	$amt['2']="Two";
	$amt['3']="Three";
	$amt['4']="Four";
	$amt['5']="Five";
	$amt['6']="Six";
	$amt['7']="Seven";
	$amt['8']="Eight";
	$amt['9']="Nine";
	$amt['10']="Ten";
	$amt['11']="Eleven";
	$amt['12']="Twevle";
	$amt['13']="Thirteen";
	$amt['14']="Fourteen";
	$amt['15']="Fifteen";
	$amt['16']="Sixteen";
	$amt['17']="Seventeen";
	$amt['18']="Eighteen";
	$amt['19']="Ninteen";
	$amt['20']="Twenty";
	$amt['30']="Thirty";
	$amt['40']="Fourty";
	$amt['50']="Fifty";
	$amt['60']="Sixty";
	$amt['70']="Seventy";
	$amt['80']="Eighty";
	$amt['90']="Ninty";
	$amt['100']="Hundred";
	$amt['1000']="Thousand";
	$amt['100000']="Lac";
	$amt['10000000']="Crore";
	switch($AMT)
	{
		case ($AMT>=10000000):
								{	
									$amt2=floor($AMT/10000000);
									$amt1=fmod($AMT,10000000)*10000000;
									$amtinwords1=amountinwords($amt2);
									if($amt1>0)
									{
										$amtinwords2=amountinwords($amt1);
										$amtinwords=$amtinwords1." Crore ".$amtinwords2;
									}
									else
									{
										$amtinwords=$amtinwords1;
									}
									return $amtinwords;
									break;
								}
		case ($AMT>=100000):
								{
									$amt2=floor($AMT/100000);
									$amt1=fmod($AMT,100000);
									$amtinwords1=amountinwords($amt2);
									if($amt1>0)
										$amtinwords2=amountinwords($amt1);
									$amtinwords=$amtinwords1." Lac ".$amtinwords2;
									return $amtinwords;
									break;
								}		
		case ($AMT>=1000):
								{
									$amt2=floor($AMT/1000);
									$amt1=fmod($AMT,1000);
									$amtinwords1=amountinwords($amt2);
									if($amt1>0)
										$amtinwords2=amountinwords($amt1);
									$amtinwords=$amtinwords1." Thousand ".$amtinwords2;
									return $amtinwords;
									break;
								}
		case ($AMT>=100):
								{
									$amt2=floor($AMT/100);
									$amt1=fmod($AMT,100);
									$amtinwords1=amountinwords($amt2);
									if($amt1>0)
										$amtinwords2=amountinwords($amt1);
									$amtinwords=$amtinwords1." Hundred ".$amtinwords2;
									return $amtinwords;
									break;
								}
		case ($AMT>20):
								{
									$amt2=floor($AMT/10)*10;
									$amt1=fmod($AMT,10);
									$amtinwords1=$amt[$amt2];
									if($amt1>0)
										$amtinwords2=$amt[$amt1];
									$amtinwords=$amtinwords1." ".$amtinwords2;
									return $amtinwords;
									break;
								}
		case ($AMT>=10):
								{
									return $amt[$AMT];
									break;
								}
		case ($AMT>=0):
								{
									return $amt[$AMT];
									break;
								}
		default:				{
									return "";
									break;
								}
	}
//return	amtinwords;
}
?>