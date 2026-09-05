<?php
	require("connect.php");
	require("header2.php");
	
	if(isset($_POST['update'])){	
		$idn = mysqli_real_escape_string($link, $_POST['idn']);
		$name_fam = mysqli_real_escape_string($link, $_POST['name_fam']);
		$name_1st = mysqli_real_escape_string($link, $_POST['name_1st']);
		$name_mid = mysqli_real_escape_string($link, $_POST['name_mid']);
		$date_birth = mysqli_real_escape_string($link, $_POST['date_birth']);
		$sex = mysqli_real_escape_string($link, $_POST['sex']);
		$status = mysqli_real_escape_string($link, $_POST['status']);
		$purok = mysqli_real_escape_string($link, $_POST['purok']);
		$barangay = mysqli_real_escape_string($link, $_POST['barangay']);
		$city_mun = mysqli_real_escape_string($link, $_POST['city_mun']);
		$province = mysqli_real_escape_string($link, $_POST['province']);

	$update = $link -> query("UPDATE cert_indigency set
		idn = '$idn',	
		name_fam = '$name_fam', 
		name_1st = '$name_1st', 
		name_mid = '$name_mid', 
		date_birth = '$date_birth', 
		sex = '$sex',
		status = '$status',
		purok = '$purok',
		barangay = '$barangay',
		city_mun = '$city_mun', 
		province = '$province' where idn = '$idn'");

		include('qrlib/qrlib.php');			
		
		if(($update)==TRUE){	

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Certificate of Indigency record: " . $fullname . " (ID: " . $idn . ")");
		
			$data = "".$_POST["name_1st"]." ".$_POST["name_mid"]." ".$_POST["name_fam"]."\nCOI-".$idn."-".date("md-Y")."";		
			$tempDir = "images/cert_indigency/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 

		echo'
			<script type="text/javascript">
				swal({
				  title: "Success!",
				  text: "Data updated successfully for '.$fullname.'",
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
