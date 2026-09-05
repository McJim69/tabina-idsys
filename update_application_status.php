<?php
	require("connect2.php");

	// Security: Only $_SESSION["access"] == "Administrator" and "Executive" can Approve/Denied to the services
	if (!isset($_SESSION['user']) || ($_SESSION['access'] !== 'Administrator' && $_SESSION['access'] !== 'Executive')) {
		http_response_code(403);
		die("Access Denied.");
	}

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$table = isset($_POST['table']) ? trim($_POST['table']) : '';
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$status = isset($_POST['status']) ? trim($_POST['status']) : '';

		$allowed_tables = ['senior', 'pwd', 'solo_parent', 'cert_indigency', 'clearances', 'permit_business', 'reg_fishing', 'permit_operate'];
		$allowed_statuses = ['Pending', 'Approved', 'Denied'];

		if (!in_array($table, $allowed_tables) || !in_array($status, $allowed_statuses) || $id <= 0) {
			http_response_code(400);
			die("Invalid parameters.");
		}

		$status_col = ($table === 'cert_indigency') ? 'app_status' : 'status';

		$stmt = $link->prepare("UPDATE `$table` SET `$status_col` = ? WHERE idn = ?");
		$stmt->bind_param("si", $status, $id);
		$update = $stmt->execute();

		if ($update) {
			echo json_encode(["status" => "success", "message" => "Application status updated successfully."]);
		} else {
			http_response_code(500);
			echo json_encode(["status" => "error", "message" => mysqli_error($link)]);
		}
	} else {
		http_response_code(405);
		die("Method Not Allowed.");
	}
?>
