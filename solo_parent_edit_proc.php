<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
	  $idn = mysqli_real_escape_string($link, $_POST['idn']);
	  $name_1st = mysqli_real_escape_string($link, $_POST['name_1st']);
	  $name_mid = mysqli_real_escape_string($link, $_POST['name_mid']);
	  $name_fam = mysqli_real_escape_string($link, $_POST['name_fam']);
	  $date_birth = mysqli_real_escape_string($link, $_POST['date_birth']);
	  $date_birth_sql = !empty($date_birth) ? "'$date_birth'" : "NULL";
	  $birth_place = mysqli_real_escape_string($link, $_POST['birth_place']);
	  $mobileno = mysqli_real_escape_string($link, $_POST['mobileno']);
	  $email = mysqli_real_escape_string($link, $_POST['email']);
	  $purok = mysqli_real_escape_string($link, $_POST['purok']);
	  $barangay = mysqli_real_escape_string($link, $_POST['barangay']);
	  $city_mun = mysqli_real_escape_string($link, $_POST['city_mun']);
	  $province = mysqli_real_escape_string($link, $_POST['province']);
	  $civilstatus = mysqli_real_escape_string($link, $_POST['civilstatus']);
	  $sex = mysqli_real_escape_string($link, $_POST['sex']);
	  $occupation = mysqli_real_escape_string($link, $_POST['occupation']);
	  $association = mysqli_real_escape_string($link, $_POST['association']);
	  $position = mysqli_real_escape_string($link, $_POST['position']);
	  $education = mysqli_real_escape_string($link, $_POST['education']);
	  $assoc_id_no = mysqli_real_escape_string($link, $_POST['assoc_id_no']);
	  $date_assoc_reg = mysqli_real_escape_string($link, $_POST['date_assoc_reg']);
	  $date_assoc_reg_sql = !empty($date_assoc_reg) ? "'$date_assoc_reg'" : "NULL";
	  $contactperson = mysqli_real_escape_string($link, $_POST['contactperson']);
	  $relationship = mysqli_real_escape_string($link, $_POST['relationship']);
	  $emergencyno = mysqli_real_escape_string($link, $_POST['emergencyno']);
	  $interviewer = mysqli_real_escape_string($link, $_POST['interviewer']);
	  $date_interview = mysqli_real_escape_string($link, $_POST['date_interview']);
	  $date_interview_sql = !empty($date_interview) ? "'$date_interview'" : "NULL";

	$update = $link->query("UPDATE solo_parent set
		idn = '$idn',
		name_1st = '$name_1st',
		name_mid = '$name_mid',
		name_fam = '$name_fam',
		date_birth = $date_birth_sql,
		birth_place = '$birth_place',
		mobileno = '$mobileno',
		emailadd = '$email',
		purok = '$purok',
		barangay = '$barangay',
		city_mun = '$city_mun',
		province = '$province',
		civilstatus = '$civilstatus',
		sex = '$sex',
		occupation = '$occupation',
		association = '$association',
		position = '$position',
		education = '$education',
		assoc_id_no = '$assoc_id_no',
		date_assoc_reg = $date_assoc_reg_sql,
		contactperson = '$contactperson',
		relationship = '$relationship',
		emergencyno = '$emergencyno',
		interviewer = '$interviewer',
		date_interview = $date_interview_sql where idn = '$idn'");

		include('qrlib/qrlib.php');

		$spin=str_pad($idn, 5, '0', STR_PAD_LEFT); 	

		if(($update)== TRUE){

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Solo Parent record: " . $fullname . " (ID: " . $idn . ")");
			
			$data = "".$fullname."\nID Number: ".$spin."";		
			$tempDir = "images/solo_parent/qrcodes/";
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
