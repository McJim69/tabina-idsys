<?php
session_start();
require("connect.php");

if (isset($_SESSION['session_token'])) {
	$token_escaped = mysqli_real_escape_string($link, $_SESSION['session_token']);
	$link->query("UPDATE users_sessions SET logout_time = NOW() WHERE session_token = '$token_escaped' AND logout_time IS NULL");
}

session_unset();  
session_destroy();

header("Location: public_home.php?logout=success");
exit();
?>