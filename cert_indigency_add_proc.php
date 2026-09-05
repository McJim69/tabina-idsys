<?php
	require("connect.php");
	require("header2.php");
	
	if(isset($_POST["bSave"])){
	
	$user_id    = mysqli_real_escape_string($link, isset($_SESSION['uno']) ? $_SESSION['uno'] : '0');
	$idn        = mysqli_real_escape_string($link, $_POST["idn"]);
	$date_birth = mysqli_real_escape_string($link, $_POST["date_birth"]);
	$date_app   = mysqli_real_escape_string($link, $_POST["date_app"]);
	$name_fam   = mysqli_real_escape_string($link, $_POST["name_fam"]);
	$name_1st   = mysqli_real_escape_string($link, $_POST["name_1st"]);
	$name_mid   = mysqli_real_escape_string($link, $_POST["name_mid"]);
	$status     = mysqli_real_escape_string($link, $_POST["status"]);
	$sex        = mysqli_real_escape_string($link, $_POST["sex"]);
	$purok      = mysqli_real_escape_string($link, $_POST["purok"]);
	$barangay   = mysqli_real_escape_string($link, $_POST["barangay"]);
	$city_mun   = mysqli_real_escape_string($link, $_POST["city_mun"]);
	$province   = mysqli_real_escape_string($link, $_POST["province"]);

	$insert=$link->query("INSERT INTO cert_indigency (idn, name_fam, name_1st, name_mid, date_birth, status, sex, purok, barangay, city_mun, province, date_issued, user_id, app_status) VALUES (
		'$idn',
		'$name_fam',
		'$name_1st',
		'$name_mid',
		'$date_birth',
		'$status',
		'$sex',
		'$purok',
		'$barangay',
		'$city_mun',
		'$province',
		'$date_app',
		'$user_id',
		'Pending')");
					
		include('qrlib/qrlib.php');			

		if(($insert)==TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new Certificate of Indigency record: " . $fullname . " (ID: " . $_POST["idn"] . ")");

			
			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$idn = "".$_POST['idn']."";
			$data = "".$fullname."\nCOI-".$idn."-".$_POST['date_app']."";		
			$tempDir = "images/cert_indigency/qrcodes/";
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
