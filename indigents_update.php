<?php
	require("connect.php");
	include("header2.php");
	include("menu.php"); 
	include("footer.php"); 
	
	$update=$link->query("update indigents set date_paid='".$_GET["remarks"]."' where idn='".$_GET["idn"]."'");

	$ex=$link->query("select * from indigents where idn='".$_GET["idn"]."'");
	
	while($rs=$ex->fetch_array()){
		if(($rs["date_paid"]=="") or ($rs["date_paid"]=="null")){
			$link->query("update indigents set amount='' where idn='".$_GET["idn"]."'");
			$link->query("update indigents set period='' where idn='".$_GET["idn"]."'");
		}else{
			$link->query("update indigents set amount='5000' where idn='".$_GET["idn"]."'");
			$link->query("update indigents set period='April 2020' where idn='".$_GET["idn"]."'");
		}
	}

	if(($update)==TRUE){

	echo'
		<script type="text/javascript">
			swal({
			  title: "Success!",
			  text: "Ingigents Data Updated Successfully!",
			  type: "success"
			}).then(function() {
				window.history.back();
			})
		</script>';
	}else{
	echo '
		<script type="text/javascript">
			jQuery(function validation(){
				swal({
					title: "ERROR!",
					text: "' . addslashes(mysqli_error($link)) . '",
					icon: "warning",
					button: "Close",
				}).then(() => {
					window.history.back();
				});
			});
		</script>';
	}
?>