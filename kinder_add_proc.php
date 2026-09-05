<?php
	require("connect.php");
	require("header2.php");
	
	if(isset($_POST["bSave"])){

	$idn = mysqli_real_escape_string($link, $_POST["idn"]);
	$name_1st = mysqli_real_escape_string($link, $_POST["name_1st"]);
	$name_mid = mysqli_real_escape_string($link, $_POST["name_mid"]);
	$name_fam = mysqli_real_escape_string($link, $_POST["name_fam"]);
	$purok = mysqli_real_escape_string($link, $_POST["purok"]);
	$barangay = mysqli_real_escape_string($link, $_POST["barangay"]);
	$city_mun = mysqli_real_escape_string($link, $_POST["city_mun"]);
	$province = mysqli_real_escape_string($link, $_POST["province"]);
	$date_birth = mysqli_real_escape_string($link, $_POST["date_birth"]);
	$sex = mysqli_real_escape_string($link, $_POST["sex"]);
	$parent = mysqli_real_escape_string($link, $_POST["parent"]);
	$contact = mysqli_real_escape_string($link, $_POST["contact"]);

	$insert=$link->query("INSERT INTO kinder (
		idn, name_1st, name_mid, name_fam, purok, barangay,
		city_mun, province, date_birth, sex, parent, contact,
		ispicset
	) VALUES (
		'$idn', '$name_1st', '$name_mid', '$name_fam', '$purok', '$barangay',
		'$city_mun', '$province', '$date_birth', '$sex', '$parent', '$contact',
		0
	)");
  
		include('qrlib/qrlib.php');
		
		if(($insert)==TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new Kindergarten record: " . $fullname . " (ID: " . $_POST["idn"] . ")");


			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			
			$idn=$_POST["idn"];
			$data = "".$_POST["name_1st"]." ".$_POST["name_mid"].". ".$_POST["name_fam"]."\nID Number: ".$idn." Parent: ".$_POST["parent"]."";		
			$tempDir = "images/kinder/qrcodes/";
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
