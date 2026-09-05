<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$idn = mysqli_real_escape_string($link, $_POST['idn']);
		$name_fam = mysqli_real_escape_string($link, $_POST['name_fam']);
		$name_1st = mysqli_real_escape_string($link, $_POST['name_1st']);
		$name_mid = mysqli_real_escape_string($link, $_POST['name_mid']);
		$position = mysqli_real_escape_string($link, $_POST['position']);
		$office = mysqli_real_escape_string($link, $_POST['office']);
		$address = mysqli_real_escape_string($link, $_POST['address']);
		$contact = mysqli_real_escape_string($link, $_POST['contact']);
		$visit_purpose = mysqli_real_escape_string($link, $_POST['visit_purpose']);
		$emailadd = mysqli_real_escape_string($link, $_POST['emailadd']);

	$update = $link->query("UPDATE visitors set
		idn = '$idn',	
		name_fam = '$name_fam', 
		name_1st = '$name_1st', 
		name_mid = '$name_mid', 
		position = '$position',
		office = '$office',
		address = '$address',
		contact = '$contact',
		visit_purpose = '$visit_purpose',
		emailadd = '$emailadd' where idn = '$idn'");

		include('qrlib/qrlib.php');

		$spin=str_pad($idn, 5, '0', STR_PAD_LEFT); 	

		if(($update)==TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Updated Visitor record: " . $fullname . " (ID: " . $idn . ")");


			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			
			$data = "".$name_1st." ".$name_mid.". ".$name_fam."\n".$position."\n".$office."\nCA Number: ".$spin."";		
			$tempDir = "images/visitors/qrcodes/";
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
