<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST["bSave"])){
		$session_user_val = isset($_SESSION['uno']) ? intval($_SESSION['uno']) : 0;

		$insert = $link->query("INSERT INTO clearances (
			idn, name_fam, name_1st, name_mid, sex, civil_status, purok, barangay, city_mun, province, date_issued, isorno, oramount, ispicset, user_id, status, timestamp
		) VALUES (
			'".$_POST["idn"]."',
			'".$_POST["name_fam"]."',
			'".$_POST["name_1st"]."',
			'".$_POST["name_mid"]."',
			'".$_POST["sex"]."',
			'".$_POST["civil_status"]."',
			'".$_POST["purok"]."',
			'".$_POST["barangay"]."',
			'".$_POST["city_mun"]."',
			'".$_POST["province"]."',
			'".$_POST["date_issued"]."',
			'".$_POST["isorno"]."',
			'".$_POST["oramount"]."',
			0,
			$session_user_val,
			'Pending',
			CURRENT_TIMESTAMP
		)") or die(mysqli_error($link));

		include('qrlib/qrlib.php');

		if(($insert) == TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new Mayor's Clearance record: " . $fullname . " (ID: " . $_POST["idn"] . ")");
			
			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$idn = $_POST['idn'];
			$data = "".$_POST["name_1st"]." ".$_POST["name_mid"]." ".$_POST["name_fam"]."\nMC-".$idn."-".$_POST["isorno"]."";		
			$tempDir = "images/clearances/qrcodes/";
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
