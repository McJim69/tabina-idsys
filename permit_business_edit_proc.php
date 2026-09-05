<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$idn = $_POST['idn'];
		$date_issued = $_POST['date_issued'];
		$tradename = $_POST['tradename'];
		$activity = $_POST['activity'];
		$name_fam= $_POST['name_fam'];
		$name_1st= $_POST['name_1st'];
		$name_mid= $_POST['name_mid'];
		$purok = $_POST['purok'];
		$barangay = $_POST['barangay'];
		$city_mun = $_POST['city_mun'];
		$isorno = $_POST['isorno'];
		$oramount = $_POST['oramount'];
		$date_or = $_POST['date_or'];
		$is_mode = $_POST['is_mode'];

		$update = $link->query("UPDATE permit_business set
			idn = '$idn',	
			date_issued = '$date_issued',	
			tradename = '$tradename',
			activity = '$activity',
			name_fam = '$name_fam', 
			name_1st = '$name_1st', 
			name_mid = '$name_mid', 
			purok = '$purok',
			barangay = '$barangay',
			city_mun = '$city_mun',
			isorno = '$isorno',
			oramount = '$oramount',
			date_or = '$date_or',
			is_mode = '$is_mode' where idn = '$idn'");
		
		include('qrlib/qrlib.php');

		if(($update)==TRUE){

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
					
			$idn = "".$_POST["idn"]."";
			$data = "".$_POST["tradename"]."\nBP-".$_POST["idn"]."-".date("d-m-Y", strtotime($_POST["date_issued"]))."";		
			$tempDir = "images/permit_business/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 
			
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Business Permit record: " . $_POST['tradename'] . " (Owned by: " . $fullname . ", ID: " . $idn . ")");
	
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
