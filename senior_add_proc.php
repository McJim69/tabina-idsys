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
			$chk = $link->query("SELECT idn FROM senior WHERE user_id = '$session_user_escaped' LIMIT 1");
			if ($chk && $chk->num_rows > 0) {
				echo '
				<script type="text/javascript">
					swal({
						title: "Already Applied",
						text: "You have already submitted an application for the Senior Citizen Card. Citizens are restricted to a single application.",
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
		$province = mysqli_real_escape_string($link, $_POST["province"]);
		$city_mun = mysqli_real_escape_string($link, $_POST["city_mun"]);
		$barangay = mysqli_real_escape_string($link, $_POST["barangay"]);
		$purok = mysqli_real_escape_string($link, $_POST["purok"]);
		$civilstatus = mysqli_real_escape_string($link, $_POST["civilstatus"]);
		$name_1st = mysqli_real_escape_string($link, $_POST["name_1st"]);
		$name_mid = mysqli_real_escape_string($link, $_POST["name_mid"]);
		$name_fam = mysqli_real_escape_string($link, $_POST["name_fam"]);
		$sex = mysqli_real_escape_string($link, $_POST["sex"]);
		$age = mysqli_real_escape_string($link, $_POST["age"]);
		$birth_place = mysqli_real_escape_string($link, $_POST["birth_place"]);
		$emailadd = mysqli_real_escape_string($link, $_POST["emailadd"]);
		$mobileno = mysqli_real_escape_string($link, $_POST["mobileno"]);
		$education = mysqli_real_escape_string($link, $_POST["education"]);
		$occupation = mysqli_real_escape_string($link, $_POST["occupation"]);
		$pensioner = mysqli_real_escape_string($link, $_POST["pensioner"]);
		$association = mysqli_real_escape_string($link, $_POST["association"]);
		$position = mysqli_real_escape_string($link, $_POST["position"]);
		$assoc_id_no = mysqli_real_escape_string($link, $_POST["assoc_id_no"]);
		$ncsc_rrn = mysqli_real_escape_string($link, $_POST["ncsc_rrn"]);
		$contactperson = mysqli_real_escape_string($link, $_POST["contactperson"]);
		$relationship = mysqli_real_escape_string($link, $_POST["relationship"]);
		$emergencyno = mysqli_real_escape_string($link, $_POST["emergencyno"]);
		$interviewer = mysqli_real_escape_string($link, $_POST["interviewer"]);

		$date_birth = mysqli_real_escape_string($link, $_POST["date_birth"]);
		$date_birth_sql = !empty($date_birth) ? "'$date_birth'" : "NULL";

		$assoc_reg_date = mysqli_real_escape_string($link, $_POST["assoc_reg_date"]);
		$assoc_reg_date_sql = !empty($assoc_reg_date) ? "'$assoc_reg_date'" : "NULL";

		$inter_date = mysqli_real_escape_string($link, $_POST["inter_date"]);
		$inter_date_sql = !empty($inter_date) ? "'$inter_date'" : "NULL";

		$insert = $link->query("INSERT INTO senior (
			idn, province, city_mun, barangay, purok, civilstatus, name_1st, name_mid, name_fam, sex, age, date_birth, birth_place, emailadd, mobileno, education, occupation, pensioner, association, position, assoc_id_no, assoc_reg_date, ncsc_rrn, contactperson, relationship, emergencyno, interviewer, inter_date, ispicset, user_id, status, timestamp
		) VALUES (
			'$idn', '$province', '$city_mun', '$barangay', '$purok', '$civilstatus', '$name_1st', '$name_mid', '$name_fam', '$sex', '$age', $date_birth_sql, '$birth_place', '$emailadd', '$mobileno', '$education', '$occupation', '$pensioner', '$association', '$position', '$assoc_id_no', $assoc_reg_date_sql, '$ncsc_rrn', '$contactperson', '$relationship', '$emergencyno', '$interviewer', $inter_date_sql, 0, $session_user_sql, 'Pending', CURRENT_TIMESTAMP
		)") or die (mysqli_error($link));

		include('qrlib/qrlib.php');

		if(($insert)==TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new Senior Citizen record: " . $fullname . " (ID: " . $_POST["idn"] . ")");

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$sidn = "".$_POST['assoc_id_no']."";
			$data = "".$_POST['name_1st']." ".$_POST['name_mid'].". ".$_POST['name_fam']."\nSCA ID No: ".$_POST['assoc_id_no']."\nSystem ID: ".$idn."";		
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