<?php
	require("connect.php");
	require("header2.php");
	
	if(isset($_POST["bSave"])){
		$is_private = (isset($_SESSION['access']) && $_SESSION['access'] === 'Private');
		$session_user_val = null;
		
		if ($is_private) {
			$session_user_val = $_SESSION['uno'];
			
			// Enforce unique application per user for ID cards (Private role only)
			$session_user_escaped = mysqli_real_escape_string($link, $session_user_val);
			$chk = $link->query("SELECT idn FROM pwd WHERE user_id = '$session_user_escaped' LIMIT 1");
			if ($chk && $chk->num_rows > 0) {
				echo '
				<script type="text/javascript">
					swal({
						title: "Already Applied",
						text: "You have already submitted an application for the PWD Registration. Citizens are restricted to a single application.",
						type: "warning"
					}).then(function() {
						window.history.back();
					});
				</script>';
				exit;
			}
		}

		$session_user_sql = $session_user_val ? intval($session_user_val) : "NULL";
		
		$idn = mysqli_real_escape_string($link, $_POST["idn"]);
		$name_1st = mysqli_real_escape_string($link, $_POST["name_1st"]);
		$name_mid = mysqli_real_escape_string($link, $_POST["name_mid"]);
		$name_fam = mysqli_real_escape_string($link, $_POST["name_fam"]);
		$disability = mysqli_real_escape_string($link, $_POST["disability"]);
		$sex = mysqli_real_escape_string($link, $_POST["sex"]);
		$purok = mysqli_real_escape_string($link, $_POST["purok"]);
		$barangay = mysqli_real_escape_string($link, $_POST["barangay"]);
		$city_mun = mysqli_real_escape_string($link, $_POST["city_mun"]);
		$province = mysqli_real_escape_string($link, $_POST["province"]);
		$civilstatus = mysqli_real_escape_string($link, $_POST["civilstatus"]);
		$date_birth = mysqli_real_escape_string($link, $_POST["date_birth"]);
		$date_birth_sql = !empty($date_birth) ? "'$date_birth'" : "NULL";
		$birth_place = mysqli_real_escape_string($link, $_POST["birth_place"]);
		$emailadd = mysqli_real_escape_string($link, $_POST["emailadd"]);
		$mobileno = mysqli_real_escape_string($link, $_POST["mobileno"]);
		$association = mysqli_real_escape_string($link, $_POST["association"]);
		$position = mysqli_real_escape_string($link, $_POST["position"]);
		$education = mysqli_real_escape_string($link, $_POST["education"]);
		$occupation = mysqli_real_escape_string($link, $_POST["occupation"]);
		$assoc_id_no = mysqli_real_escape_string($link, $_POST["assoc_id_no"]);
		$contactperson = mysqli_real_escape_string($link, $_POST["contactperson"]);
		$relationship = mysqli_real_escape_string($link, $_POST["relationship"]);
		$emergencyno = mysqli_real_escape_string($link, $_POST["emergencyno"]);
		$interviewer = mysqli_real_escape_string($link, $_POST["interviewer"]);
		$date_interview = mysqli_real_escape_string($link, $_POST["date_interview"]);
		$date_interview_sql = !empty($date_interview) ? "'$date_interview'" : "NULL";

		$insert = $link->query("INSERT INTO pwd (
			idn, name_1st, name_mid, name_fam, disability, sex, purok, barangay, city_mun, province,
			civilstatus, date_birth, birth_place, emailadd, mobileno, association, position, education,
			occupation, assoc_id_no, date_assoc_reg, contactperson, relationship,
			emergencyno, interviewer, date_interview, ispicset, user_id, app_status, created_at
		) VALUES (
			'$idn', '$name_1st', '$name_mid', '$name_fam', '$disability', '$sex', '$purok', '$barangay', '$city_mun', '$province',
			'$civilstatus', $date_birth_sql, '$birth_place', '$emailadd', '$mobileno', '$association', '$position', '$education',
			'$occupation', '$assoc_id_no', NULL, '$contactperson', '$relationship',
			'$emergencyno', '$interviewer', $date_interview_sql, 0, $session_user_sql, 'Pending', CURRENT_TIMESTAMP
		)") or die (mysqli_error($link));	

		include('qrlib/qrlib.php');

		if(($insert)==TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new PWD record: " . $fullname . " (ID: " . $_POST["idn"] . ")");

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$pwid = "".$_POST['assoc_id_no']."";
			$data = "".$_POST['name_1st']." ".$_POST['name_mid'].". ".$_POST['name_fam']."\nPWD ID No: ".$pwid."";		
			$tempDir = "images/pwd/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$pwid.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						

			echo'
				<script type="text/javascript">
					swal({
					  title: "Success!",
					  text: "Data processed successfully for '.$fullname.'",
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
							type: "warning"
						}).then(() => {
							window.history.back();
						});
					});
				</script>';		
		}	
	}
?>
