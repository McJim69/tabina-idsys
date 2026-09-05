<?php
	require("connect.php");
	require("header2.php");

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$uno = isset($_POST['uno']) ? intval($_POST['uno']) : 0;
		$access   = isset($_POST['access'])   ? trim($_POST['access'])   : '';
		$username = isset($_POST['username']) ? trim($_POST['username']) : '';
		$password = isset($_POST['password']) ? trim($_POST['password']) : '';

		if ($uno <= 0 || empty($username)) {
			echo "<script>alert('ERROR! Invalid user record.'); window.location.href = 'users_list.php';</script>";
			exit;
		}

		// Check if username is used by another user
		$stmt_check = $link->prepare("SELECT uno FROM users WHERE username = ? AND uno != ?");
		$stmt_check->bind_param("si", $username, $uno);
		$stmt_check->execute();
		$res_check = $stmt_check->get_result();

		if ($res_check && $res_check->num_rows > 0) {
			echo "<script>
				alert('ERROR! The username \"".addslashes($username)."\" is already used by another user.');
				window.history.back();
			</script>";
			exit;
		}

		try {
			if (empty($password)) {
				$stmt = $link->prepare("UPDATE users SET access = ?, username = ? WHERE uno = ?");
				$stmt->bind_param("sss", $access, $username, $uno);
			} else {
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$stmt = $link->prepare("UPDATE users SET access = ?, username = ?, password = ? WHERE uno = ?");
				$stmt->bind_param("ssss", $access, $username, $hashed_password, $uno);
			}

			if ($stmt->execute()) {
				echo '
					<script type="text/javascript">
						swal({
						  title: "Success!",
						  text: "Data updated successfully!",
						  icon: "success"
						}).then(() => {
							window.history.back();
						});
					</script>';
			} else {
				echo '
					<script type="text/javascript">
						swal({
							title: "ERROR!",
							text: "' . addslashes($stmt->error) . '",
							icon: "warning",
							button: "Close",
						}).then(() => {
							window.history.back();
						});
					</script>';
			}
		} catch (Exception $e) {
			echo "<script>
				alert('ERROR! Duplicate entry. The username \"".addslashes($username)."\" is already taken.');
				window.history.back();
			</script>";
		}
	}
?>
