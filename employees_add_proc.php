<?php
	require("connect.php");
	require("header2.php");
		
	if(isset($_POST["bSave"])){
		
	$idn = mysqli_real_escape_string($link, $_POST["idn"]);
	$name_fam = mysqli_real_escape_string($link, $_POST["name_fam"]);
	$name_1st = mysqli_real_escape_string($link, $_POST["name_1st"]);
	$name_mid = mysqli_real_escape_string($link, $_POST["name_mid"]);
	$agency = mysqli_real_escape_string($link, $_POST["agency"]);
	$department = mysqli_real_escape_string($link, $_POST["department"]);
	$date_appointed = mysqli_real_escape_string($link, $_POST["date_appointed"]);
	$position = mysqli_real_escape_string($link, $_POST["position"]);
	$purok = mysqli_real_escape_string($link, $_POST["purok"]);
	$barangay = mysqli_real_escape_string($link, $_POST["barangay"]);
	$city_mun = mysqli_real_escape_string($link, $_POST["city_mun"]);
	$province = mysqli_real_escape_string($link, $_POST["province"]);
	$contact = mysqli_real_escape_string($link, $_POST["contact"]);
	$emailadd = mysqli_real_escape_string($link, $_POST["emailadd"]);
	$sex = mysqli_real_escape_string($link, $_POST["sex"]);
	$date_birth = mysqli_real_escape_string($link, $_POST["date_birth"]);
	$pagibig = mysqli_real_escape_string($link, $_POST["pagibig"]);
	$philhealth = mysqli_real_escape_string($link, $_POST["philhealth"]);
	$gsis = mysqli_real_escape_string($link, $_POST["gsis"]);
	$tin = mysqli_real_escape_string($link, $_POST["tin"]);
	$contactperson = mysqli_real_escape_string($link, $_POST["contactperson"]);
	$relationship = mysqli_real_escape_string($link, $_POST["relationship"]);
	$emergencyno = mysqli_real_escape_string($link, $_POST["emergencyno"]);

	$insert = $link->query("INSERT INTO employees (
		idn, name_fam, name_1st, name_mid, agency, department,
		date_appointed, position, purok, barangay,
		city_mun, province, contact, emailadd, sex, date_birth,
		pagibig, philhealth, gsis, tin, contactperson, relationship,
		emergencyno, ispicset
	) VALUES (
		'$idn', '$name_fam', '$name_1st', '$name_mid', '$agency', '$department',
		'$date_appointed', '$position', '$purok', '$barangay',
		'$city_mun', '$province', '$contact', '$emailadd', '$sex', '$date_birth',
		'$pagibig', '$philhealth', '$gsis', '$tin', '$contactperson', '$relationship',
		'$emergencyno', 0
	)") or die (mysqli_error($link));

		include('qrlib/qrlib.php');			

		if(($insert) == TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new Employee record: " . $fullname . " (ID: " . $_POST["idn"] . ")");


			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$idn = "".$_POST['idn']."";
			$data = "".$_POST["name_1st"]." ".$_POST["name_mid"]." ".$_POST["name_fam"]."\nCOI-".$idn."-".date("md-Y")."";		
			$tempDir = "images/employees/qrcodes/";
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
