<?php
	require("connect.php");
	$link->query("update sap_ben set date_paid='".$_GET["remarks"]."' where idn='".$_GET["idn"]."'")or die(mysqli_error($link));

	$ex=$link->query("select * from sap_ben where idn='".$_GET["idn"]."'")or die(mysqli_error($link));
	
	while($rs=mysqli_fetch_array($ex)){
		if(($rs["date_paid"]=="") or ($rs["date_paid"]=="null")){
			$link->query("update sap_ben set amount='' where idn='".$_GET["idn"]."'")or die(mysqli_error($link));
			$link->query("update sap_ben set period='' where idn='".$_GET["idn"]."'")or die(mysqli_error($link));
		}else{
			$link->query("update sap_ben set amount='5000' where idn='".$_GET["idn"]."'")or die(mysqli_error($link));
			$link->query("update sap_ben set period='April 2020' where idn='".$_GET["idn"]."'")or die(mysqli_error($link));
		}
	}
?>