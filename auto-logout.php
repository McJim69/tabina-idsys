<?php
	$inactive_time = 1800; 

	if (isset($_SESSION['last_activity'])) {
		$elapsed_time = time() - $_SESSION['last_activity'];

		if ($elapsed_time > $inactive_time) {
			// Update logout_time in users_sessions table before clearing session
			if (isset($_SESSION['session_token']) && isset($link)) {
				$token_escaped = mysqli_real_escape_string($link, $_SESSION['session_token']);
				$link->query("UPDATE users_sessions SET logout_time = NOW() WHERE session_token = '$token_escaped' AND logout_time IS NULL");
			}

			session_unset(); 
			session_destroy();  
			header("Location: login.php?timeout"); 
			exit();
		}
	}

	$_SESSION['last_activity'] = time();
?>