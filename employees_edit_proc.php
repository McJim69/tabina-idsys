<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$idn = mysqli_real_escape_string($link, $_POST['idn']);		
		$name_1st = mysqli_real_escape_string($link, $_POST['name_1st']);
		$name_mid = mysqli_real_escape_string($link, $_POST['name_mid']);
		$name_fam = mysqli_real_escape_string($link, $_POST['name_fam']);
		$agency = mysqli_real_escape_string($link, $_POST['agency']);
		$department = mysqli_real_escape_string($link, $_POST['department']);
		$position = mysqli_real_escape_string($link, $_POST['position']);
		$date_appointed = mysqli_real_escape_string($link, $_POST['date_appointed']);	
		$purok = mysqli_real_escape_string($link, $_POST['purok']);
		$barangay = mysqli_real_escape_string($link, $_POST['barangay']);
		$city_mun = mysqli_real_escape_string($link, $_POST['city_mun']);
		$date_birth = mysqli_real_escape_string($link, $_POST['date_birth']);
		$contact = mysqli_real_escape_string($link, $_POST['contact']);
		$emailadd = mysqli_real_escape_string($link, $_POST['emailadd']);
		$pagibig = mysqli_real_escape_string($link, $_POST['pagibig']);
		$philhealth = mysqli_real_escape_string($link, $_POST['philhealth']);
		$gsis = mysqli_real_escape_string($link, $_POST['gsis']);
		$tin = mysqli_real_escape_string($link, $_POST['tin']);
		$contactperson = mysqli_real_escape_string($link, $_POST['contactperson']);
		$relationship = mysqli_real_escape_string($link, $_POST['relationship']);
		$emergencyno = mysqli_real_escape_string($link, $_POST['emergencyno']);

	$update = $link->query("UPDATE employees set
		idn = '$idn',	
		name_1st = '$name_1st',
		name_mid = '$name_mid',
		name_fam = '$name_fam',			
		agency = '$agency',
		department = '$department',
		position = '$position',
		date_appointed = '$date_appointed',
		purok = '$purok',
		barangay = '$barangay',
		city_mun = '$city_mun',
		date_birth = '$date_birth',
		contact = '$contact',
		emailadd = '$emailadd',
		pagibig = '$pagibig',
		philhealth = '$philhealth',
		gsis = '$gsis',
		tin = '$tin',
		contactperson = '$contactperson',
		relationship = '$relationship',
		emergencyno = '$emergencyno' where idn = '$idn'");
		
		include('qrlib/qrlib.php');		

		if(($update)==TRUE){

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			
			$app_year = !empty($date_appointed) ? date("Y", strtotime($date_appointed)) : date("Y");
			$data = "".$_POST["name_1st"]." ".$_POST["name_mid"].". ".$_POST["name_fam"]."\n".$_POST["department"]."-".$idn."-".$_POST["position"]."-".$app_year."";		
			$tempDir = "images/employees/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						
			
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Employee record: " . $fullname . " (ID: " . $idn . ")");
	
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
