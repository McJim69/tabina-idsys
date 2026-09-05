<?php
	require("connect.php");

	function logAuditTrail($link, $admin, $action) {
		$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
		$timestamp = date("Y-m-d H:i:s");

		$stmt = $link->prepare("INSERT INTO audit_trail (admin_user, action, ip_address, timestamp) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $admin, $action, $ip, $timestamp);
		$stmt->execute();
	}

	if(isset($_GET['mbid'])){
		$mbid = (int)$_GET['mbid']; // safe cast to int

		$stmt = $link->prepare("DELETE FROM message_board WHERE mbid = ?");
		$stmt->bind_param("i", $mbid);
		$delete = $stmt->execute();

		if($delete){
			$admin = $_SESSION['user'] ?? 'Unknown';
			$action = "Deleted message ID $mbid";
			logAuditTrail($link, $admin, $action);
			echo "Success";
		} else {
			echo "Error deleting message.";
		}
	}
?>
