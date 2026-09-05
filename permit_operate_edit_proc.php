<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$idn = $_POST['idn'];
		$tradename = $_POST['tradename'];
		$activity = $_POST['activity'];
		$name_fam= $_POST['name_fam'];
		$name_1st= $_POST['name_1st'];
		$name_mid= $_POST['name_mid'];
		$purok = $_POST['purok'];
		$barangay = $_POST['barangay'];
		$city_mun = $_POST['city_mun'];
		$is_mode = $_POST['is_mode'];
		$date_issued = $_POST['date_issued'];
		$isorno = $_POST['isorno'];
		$oramount = $_POST['oramount'];		

		$update = $link->query("UPDATE permit_operate set
			idn = '$idn',	
			tradename = '$tradename',
			activity = '$activity',
			name_fam = '$name_fam', 
			name_1st = '$name_1st', 
			name_mid = '$name_mid', 
			purok = '$purok',
			barangay = '$barangay',
			city_mun = '$city_mun',
			is_mode = '$is_mode',
			date_issued = '$date_issued',
			isorno = '$isorno',
			oramount = '$oramount' where idn = '$idn'");
		
		include('qrlib/qrlib.php');

		if(($update)== TRUE){

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
					
			$data = "".$_POST["tradename"]."\nPO-".$idn."-".date("d-m-Y", strtotime($_POST["date_issued"]))."";		
			$tempDir = "images/permit_operate/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						

			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Permit to Operate record: " . $_POST['tradename'] . " (Owned by: " . $fullname . ", ID: " . $idn . ")");

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
