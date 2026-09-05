<center>
<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
	  $idn = $_POST['idn'];
	  $name_1st = $_POST['name_1st'];
	  $name_mid = $_POST['name_mid'];
	  $name_fam = $_POST['name_fam'];
	  $birth_place = $_POST['birth_place'];
	  $date_birth = $_POST['date_birth'];
	  $date_birth_sql = !empty($date_birth) ? "'$date_birth'" : "NULL";
	  $age = $_POST['age'];
	  $emailadd = $_POST['emailadd'];
	  $mobileno = $_POST['mobileno'];
	  $purok = $_POST['purok'];
	  $barangay = $_POST['barangay'];
	  $city_mun = $_POST['city_mun'];
	  $province = $_POST['province'];
	  $education = $_POST['education'];
	  $occupation = $_POST['occupation'];
	  $pensioner = $_POST['pensioner'];
	  $civilstatus = $_POST['civilstatus'];
	  $sex = $_POST['sex'];
	  $association = $_POST['association'];
	  $position = $_POST['position'];
	  $assoc_id_no = $_POST['assoc_id_no'];
	  $assoc_reg_date = $_POST['assoc_reg_date'];
	  $assoc_reg_date_sql = !empty($assoc_reg_date) ? "'$assoc_reg_date'" : "NULL";
	  $ncsc_rrn = $_POST['ncsc_rrn'];
	  $contactperson = $_POST['contactperson'];
	  $relationship = $_POST['relationship'];
	  $emergencyno = $_POST['emergencyno'];
	  $interviewer = $_POST['interviewer'];
	  $inter_date = $_POST['inter_date'];
	  $inter_date_sql = !empty($inter_date) ? "'$inter_date'" : "NULL";

	$update = $link->query("UPDATE senior set
		idn = '$idn',
		name_1st = '$name_1st',
		name_mid = '$name_mid',
		name_fam = '$name_fam',
		birth_place = '$birth_place',
		date_birth = $date_birth_sql,
		age = '$age',
		emailadd = '$emailadd',
		mobileno = '$mobileno',
		purok = '$purok',
		barangay = '$barangay',
		city_mun = '$city_mun',
		province = '$province',
		education = '$education',
		occupation = '$occupation',
		pensioner = '$pensioner',
		civilstatus = '$civilstatus',
		sex = '$sex',
		association = '$association',
		position = '$position',
		assoc_id_no = '$assoc_id_no',
		assoc_reg_date = $assoc_reg_date_sql,
		ncsc_rrn = '$ncsc_rrn',
		contactperson = '$contactperson',
		relationship = '$relationship',
		emergencyno = '$emergencyno',
		interviewer = '$interviewer',
		inter_date = $inter_date_sql where idn = '$idn'");

		include('qrlib/qrlib.php');

		if(($update)== TRUE){

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Senior Citizen record: " . $fullname . " (ID: " . $idn . ")");

			$data = "".$name_1st." ".$name_mid.". ".$name_fam."\nSCA ID No: ".$assoc_id_no."\nSystem ID: ".$idn."";		
			$tempDir = "images/senior/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						

			if (!empty($_POST["date_birth"])) {
				$birthDate = $_POST["date_birth"];
				$birthDate = explode("-", $birthDate);
				if (count($birthDate) === 3) {
					$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-0):(date("Y")-$birthDate[0]));
					$link->query("UPDATE senior set age = '$age' where idn='".$idn."'");			
				}
			}

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
</center>

<script>
  setInterval(() => {
    const el = document.getElementById("jsBlink");
    el.style.visibility = el.style.visibility === "hidden" ? "visible" : "hidden";
  }, 600); 
</script>