<?php
require_once("connect.php");
 	
	$sid = session_id();
	$ipc = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	$time = date('h:i:s A');
	$date = date('l d.M.Y');

	if (!isset($_SESSION['user'])){
		$user = "Guest";
		$guest_flag = '1';
	} else {
		$user = $_SESSION["user"];
		$guest_flag = '0';
	}

	$user_escaped = $link->real_escape_string($user);
	$sid_escaped = $link->real_escape_string($sid);
	$ipc_escaped = $link->real_escape_string($ipc);

	// Safe atomic insert/update for the session table (avoids duplicate key exceptions on concurrent requests)
	$link->query("INSERT INTO `session`(user, time, date, sid, ipc, guest, last_active) 
		VALUES ('$user_escaped', '$time', '$date', '$sid_escaped', '$ipc_escaped', '$guest_flag', NOW()) 
		ON DUPLICATE KEY UPDATE 
		user = '$user_escaped', time = '$time', date = '$date', ipc = '$ipc_escaped', guest = '$guest_flag', last_active = NOW()");

	if (isset($_SESSION['user'])) {
		$sess_token_escaped = mysqli_real_escape_string($link, $_SESSION['session_token'] ?? session_id());
		$link->query("UPDATE `users_sessions` SET last_active = NOW() WHERE session_token = '$sess_token_escaped' AND logout_time IS NULL");
	}
?>	