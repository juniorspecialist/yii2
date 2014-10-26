<!--
function myclock(){
	dayarray=new Array("sunday","monday","tuesday","wednesday","thursday","friday","Saturday")
	montharray=new Array ("january","february","march","april","may","june","july","august","september","october","november","december")
	ndata=new Date();
	day=dayarray[ndata.getDay()];
	month=montharray[ndata.getMonth()];
	date=ndata.getDate();
	year=ndata.getFullYear();
	hours = ndata.getHours();
	mins = ndata.getMinutes();
	secs = ndata.getSeconds();
	if (hours < 10) {hours = "0" + hours }
	if (mins < 10) {mins = "0" + mins }
	if (secs < 10) {secs = "0" + secs }

	datastr=(day+", "+ month +" "+ date +", "+ year +" "+ hours + ":" + mins)
	document.getElementById("clock").value = " "+datastr;
	setTimeout("myclock()", 1000);
}
-->