if (window.XMLHttpRequest)
	xmlhttp=new XMLHttpRequest();
else
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");				
function getID(id){
	return document.getElementById(id);
}
function conf(){
	return confirm("Are you sure?");
}
function jump(page){
	window.location=page;
}
function setActive(id){
	getID(id).style.background="lightblue";
	getID(id).style.color="red";
	getID(id).style.fontWeight="bold";
}