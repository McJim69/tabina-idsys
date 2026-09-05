<?php
	require("connect2.php");
	require("header2.php");
	
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$name_1st = isset($_POST['name_1st'])   ? trim($_POST['name_1st'])   : '';
		$name_mid = isset($_POST['name_mid'])   ? trim($_POST['name_mid'])   : '';
		$name_fam = isset($_POST['name_fam'])   ? trim($_POST['name_fam'])   : '';
		$access   = isset($_POST['access'])     ? trim($_POST['access'])     : '';
		$username = isset($_POST['username'])   ? trim($_POST['username'])   : '';
		$birth    = isset($_POST['date_birth']) ? trim($_POST['date_birth']) : '';
		$email    = isset($_POST['email'])      ? trim($_POST['email'])      : '';
		$phone    = isset($_POST['phone'])      ? trim($_POST['phone'])      : '';
		$city_mun = isset($_POST['city_mun'])   ? trim($_POST['city_mun'])   : '';
		$barangay = isset($_POST['barangay'])   ? trim($_POST['barangay'])   : '';
		$purok    = isset($_POST['purok'])      ? trim($_POST['purok'])      : '';
		$raw_pass = isset($_POST['password'])   ? trim($_POST['password'])   : '';
		$password = password_hash($raw_pass, PASSWORD_DEFAULT);
		
		if (empty($username)) {
			echo '
				<script type="text/javascript">
					swal({
						title: "ERROR!",
						text: "Username cannot be empty.",
						icon: "warning",
						button: "Close",
					}).then(() => {
						window.history.back();
					});
				</script>';			
			exit;
		}

		// Pre-check for duplicate username
		$stmt_check = $link->prepare("SELECT uno FROM users WHERE username = ?");
		$stmt_check->bind_param("s", $username);
		$stmt_check->execute();
		$res_check = $stmt_check->get_result();

		if ($res_check && $res_check->num_rows > 0) {
			echo '
				<script type="text/javascript">
					swal({
						title: "ERROR!",
						text: "The username \"".addslashes($username)."\" already exists. Please enter a different username.",
						icon: "warning",
						button: "Close",
					}).then(() => {
						window.history.back();
					});
				</script>';		
			exit;
		}

		// Handle profile photo upload
		$location = "";
		if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['tmp_name'])) {
			$location = $_FILES["image"]["name"];
			@move_uploaded_file($_FILES["image"]["tmp_name"], "images/users/" . $location);
		}

		try {
			$stmt = $link->prepare("INSERT INTO users (name_1st, name_mid, name_fam, access, username, password, date_birth, email, phone, city_mun, barangay, purok, imgUrl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssssssssssss", $name_1st, $name_mid, $name_fam, $access, $username, $password, $birth, $email, $phone, $city_mun, $barangay, $purok, $location);
			$register = $stmt->execute();

			if ($register) {
				$fullname = trim($name_1st . " " . $name_mid . " " . $name_fam);
				logAuditTrail($link, $username, "Registered new public user account: " . $fullname . " (Username: " . $username . ")");

				echo '
				<script type="text/javascript">
					swal({
					  title: "Success!",
					  text: "Data process successfully for '.addslashes($username).'",
					  type: "success"
					}).then(function() {
						window.location="public_dashboard.php";
					})
				</script>';
			} else {
				echo '
				<script type="text/javascript">
					swal({
						title: "ERROR!",
						text: "' . addslashes(mysqli_error($link)) . '",
						icon: "warning",
						button: "Close",
					}).then(() => {
						window.history.back();
					});
				</script>';
			}
		} catch (Exception $e) {
			echo '
			<script type="text/javascript">
				swal({
					title: "ERROR!",
					text: "' . addslashes(mysqli_error($link)) . '",
					icon: "warning",
					button: "Close",
				}).then(() => {
					window.history.back();
				});
			</script>';
		}
	}
?>