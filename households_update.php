<?php
	require("connect.php");
	$link->query("update households set remarks='".$_GET["remarks"]."' where hhid='".$_GET["hhid"]."'") or die (mysqli_error($link));
	
	$ex=$link->query("select * from households where hhid='".$_GET["hhid"]."'");
	
	while($rs=$ex->fetch_array()){
		if($rs["remarks"]=="null"){
			$link->query("update households set remarks='' where hhid='".$_GET["hhid"]."'");
		}
	}
?>