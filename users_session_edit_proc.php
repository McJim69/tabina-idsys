<?php
	require("connect.php");
		
	if (isset($_POST['update']) || $_SERVER["REQUEST_METHOD"] === "POST") {
		$uno      = isset($_POST['uno'])        ? intval($_POST['uno'])      : 0;
		$name_1st = isset($_POST['name_1st'])   ? trim($_POST['name_1st'])   : '';
		$name_mid = isset($_POST['name_mid'])   ? trim($_POST['name_mid'])   : '';
		$name_fam = isset($_POST['name_fam'])   ? trim($_POST['name_fam'])   : '';
		$access   = isset($_POST['access'])     ? trim($_POST['access'])     : '';
		$username = isset($_POST['username'])   ? trim($_POST['username'])   : '';
		$password = isset($_POST['password'])   ? trim($_POST['password'])   : '';
		$birth    = isset($_POST['date_birth']) ? trim($_POST['date_birth']) : '';
		$email    = isset($_POST['email'])      ? trim($_POST['email'])      : '';
		$phone    = isset($_POST['phone'])      ? trim($_POST['phone'])      : '';
		$purok    = isset($_POST['purok'])      ? trim($_POST['purok'])      : '';
		$barangay = isset($_POST['barangay'])   ? trim($_POST['barangay'])   : '';
		$city_mun = isset($_POST['city_mun'])   ? trim($_POST['city_mun'])   : '';

		if ($uno <= 0 || empty($username)) {
			echo "<script>alert('ERROR! Invalid user record.'); window.history.back();</script>";
			exit;
		}

		// Check if username is used by another user
		$stmt_check = $link->prepare("SELECT uno FROM users WHERE username = ? AND uno != ?");
		$stmt_check->bind_param("si", $username, $uno);
		$stmt_check->execute();
		$res_check = $stmt_check->get_result();

		if ($res_check && $res_check->num_rows > 0) {
			echo "<script>
				alert('ERROR! The username \"".addslashes($username)."\" is already used by another user. Please choose a unique username.');
				window.history.back();
			</script>";
			exit;
		}

		try {
			if (empty($password)) {
				$stmt = $link->prepare("UPDATE users 
					SET name_1st = ?, name_mid = ?, name_fam = ?, access = ?, username = ?, date_birth = ?, email = ?, phone = ?, purok = ?, barangay = ?, city_mun = ? 
					WHERE uno = ?");
				$stmt->bind_param("sssssssssssi", $name_1st, $name_mid, $name_fam, $access, $username, $birth, $email, $phone, $purok, $barangay, $city_mun, $uno);
			} else {
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$stmt = $link->prepare("UPDATE users 
					SET name_1st = ?, name_mid = ?, name_fam = ?, access = ?, username = ?, password = ?, date_birth = ?, email = ?, phone = ?, purok = ?, barangay = ?, city_mun = ? 
					WHERE uno = ?");
				$stmt->bind_param("ssssssssssssi", $name_1st, $name_mid, $name_fam, $access, $username, $hashed_password, $birth, $email, $phone, $purok, $barangay, $city_mun, $uno);
			}
			$update = $stmt->execute();

			if ($update) {
				header("Location: relogin.php");
			} else {
				echo "<script>
					alert('ERROR! Could not update user account. \"".addslashes(mysqli_error($link))."\"');
					window.history.back();
				</script>";
			}
		} catch (Exception $e) {
			echo "<script>
				alert('ERROR! Duplicate entry. The username \"".addslashes($username)."\" is already taken.');
				window.history.back();
			</script>";
		}
	}
?>