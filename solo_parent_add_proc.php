<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST["bSave"])) {
		$is_private = (isset($_SESSION['access']) && $_SESSION['access'] === 'Private');
		$user_id = null;

		if ($is_private) {
			$user_id = $_SESSION['uno'];

			// Enforce unique application per user
			$stmt_chk = $link->prepare("SELECT idn FROM solo_parent WHERE user_id = ? LIMIT 1");
			$stmt_chk->bind_param("s", $user_id);
			$stmt_chk->execute();
			$stmt_chk->store_result();

			if ($stmt_chk->num_rows > 0) {
				echo '
				<script type="text/javascript">
					swal({
						title: "Already Applied",
						text: "You have already submitted an application for the Solo Parent Card. Citizens are restricted to a single application.",
						type: "warning"
					}).then(function() {
						window.history.back();
					});
				</script>';
				exit;
			}
		}

		// Prepared statement for insert
		$sql = "INSERT INTO solo_parent (
			idn, name_1st, name_mid, name_fam, sex, purok, barangay, city_mun, province,
			civilstatus, date_birth, birth_place, emailadd, mobileno, association, position,
			education, occupation, assoc_id_no, date_assoc_reg, contactperson, relationship,
			emergencyno, interviewer, date_interview, ispicset, user_id, status, timestamp
		) VALUES (
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP
		)";

		$stmt = $link->prepare($sql);

		$some_flag = 0;
		$status = "Pending";
		$date_assoc_reg = null; // Registration date is NULL initially on add
		$email_val = isset($_POST["email"]) ? $_POST["email"] : (isset($_POST["emailadd"]) ? $_POST["emailadd"] : "");

		$date_birth = !empty($_POST["date_birth"]) ? $_POST["date_birth"] : null;
		$date_interview = !empty($_POST["date_interview"]) ? $_POST["date_interview"] : null;

		$stmt->bind_param("ssssssssssssssssssssssssisss",
			$_POST["idn"], $_POST["name_1st"], $_POST["name_mid"], $_POST["name_fam"],
			$_POST["sex"], $_POST["purok"], $_POST["barangay"], $_POST["city_mun"],
			$_POST["province"], $_POST["civilstatus"], $date_birth, $_POST["birth_place"],
			$email_val, $_POST["mobileno"], $_POST["association"], $_POST["position"],
			$_POST["education"], $_POST["occupation"], $_POST["assoc_id_no"], $date_assoc_reg,
			$_POST["contactperson"], $_POST["relationship"], $_POST["emergencyno"], $_POST["interviewer"],
			$date_interview, $some_flag, $user_id, $status
		);

		if ($stmt->execute()) {
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new Solo Parent record: " . $fullname . " (ID: " . $_POST["idn"] . ")");

			include('qrlib/qrlib.php');

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			$sidn = $_POST['idn'];
			$spin = str_pad($sidn, 5, '0', STR_PAD_LEFT);

			$data = $_POST['name_1st']." ".$fullname."\nID Number: ".$spin;
			$tempDir = "images/pwd/qrcodes/";
			$fileName = $sidn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($data, $pngAbsoluteFilePath);

			echo '
			<script type="text/javascript">
				swal({
				  title: "Success!",
				  text: "Data processed successfully for '.$fullname.'",
				  type: "success"
				}).then(function() {
					window.history.back();
				})
			</script>';
		} else {
			echo '
			<script type="text/javascript">
				swal({
					title: "ERROR!",
					text: "' . addslashes($stmt->error) . '",
					icon: "warning",
					button: "Close",
					type: "warning"
				}).then(() => {
					window.history.back();
				});
			</script>';
		}
	}
?>
