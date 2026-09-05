<?php
	require("connect.php");
	require("header2.php");
		
	if(isset($_POST["bSave"])){
		
	$insert = $link->query("insert into indigents (idn, fullname, barangay, city_mun, period, amount, signature, date_paid, remarks, ispicset) values (
		'".$_POST['idn']."',
		'".$_POST['fullname']."',
		'".$_POST['purok']."',		
		'".$_POST['barangay']."',
		'".$_POST['city_mun']."',
		'".$_POST['period']."',
		'".$_POST['amount']."',
		'".$_POST['signature']."',
		'".$_POST['date_paid']."',
		'".$_POST['remarks']."',0)");
		
		include('qrlib/qrlib.php');		
		
		if(($insert)==TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Created new Indigent record (ID: " . $_POST["idn"] . ")");

			
			$fullname=$_POST['fullname'];

			$idn = $_POST["idn"];
			$data = "".$_POST["fullname"]."\nID No.: ".$idn."-".$_POST["barangay"]."";		
			$tempDir = "images/indigents/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						
			
		echo'
			<script type="text/javascript">
				swal({
				  title: "Success!",
				  text: "Data process successfully for '.$fullname.'",
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
	}
?>
