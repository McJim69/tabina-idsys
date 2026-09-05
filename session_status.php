<?php 
	include("connect2.php");
	include("header2.php");

	if (!isset($_SESSION['user'])) {
		echo '
		<script type="text/javascript">
			swal({
				title: "Not Logged In",
				text: "Please login to continue.",
				icon: "warning",
				button: "Login"
			}).then(function() {
				window.location.href = "login.php";
			});
		</script>';
	}
	elseif (empty($_SESSION["purok"]) && empty($_SESSION["phone"])) {
		$edit="\"Edit Profile\"";
		echo '
		<script type="text/javascript">
			swal({
				title: "Incomplete Account",
				text: "Please complete your account details to update. Click the button '.addslashes($edit).' below your profile details to continue.",
				icon: "warning",
				button: "Close"
			}).then(function() {
				window.location.href = "public_dashboard.php";
			});
		</script>';
	}
?>