<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$idn = $_POST['idn'];
		$name_fam= $_POST['name_fam'];
		$name_1st= $_POST['name_1st'];
		$name_mid= $_POST['name_mid'];
		$sex = $_POST['sex'];
		$civil_status = $_POST['civil_status'];
		$date_issued = $_POST['date_issued'];
		$purok = $_POST['purok'];
		$barangay = $_POST['barangay'];
		$city_mun = $_POST['city_mun'];
		$isorno = $_POST['isorno'];
		$oramount = $_POST['oramount'];

		$update = $link->query("UPDATE clearances set
			idn = '$idn',	
			name_fam = '$name_fam', 
			name_1st = '$name_1st', 
			name_mid = '$name_mid', 
			sex = '$sex',
			civil_status = '$civil_status',
			purok = '$purok',
			barangay = '$barangay',
			city_mun = '$city_mun',
			date_issued = '$date_issued',
			isorno = '$isorno',
			oramount = '$oramount' where idn = '$idn'");
			
		include('qrlib/qrlib.php');

		if(($update)== TRUE){

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			
			$data = "".$_POST["name_1st"]." ".$_POST["name_mid"]." ".$_POST["name_fam"]."\nMC-".$_POST["idn"]."-".date("md-Y")."";		
			$tempDir = "images/clearances/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 

			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Mayor's Clearance record: " . $fullname . " (ID: " . $idn . ")");

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
