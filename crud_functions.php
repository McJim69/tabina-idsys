<?php
	require_once("connect2.php");

	// Table → primary key mapping
	if (!isset($db_tables)) {
		$db_tables = [
			'pwd'                => 'idn',
			'users'              => 'uno',
			'senior'             => 'idn',
			'kinder'             => 'idn',
			'msgout'             => 'idn',
			'session'            => 'gid',
			'sap_ben'            => 'idn',
			'messages'           => 'idn',
			'visitors'           => 'idn',
			'indigents'          => 'idn',
			'employees'          => 'idn',
			'clearances'         => 'idn',
			'hh_members'         => 'hmid',
			'households'         => 'hhid',
			'reg_fishing'        => 'idn', 
			'solo_parent'        => 'idn',
			'chat_messages'      => 'id',
			'message_board'      => 'mbid',
			'users_sessions'     => 'id',
			'permit_operate'     => 'idn',
			'cert_indigency'     => 'idn',
			'permit_business'    => 'idn',
			'private_messages'   => 'id',
			'message_reactions'  => 'id'
		];
	}

	// Audit trail
	if (!function_exists('logAuditTrail')) {
		function logAuditTrail($link, $admin, $action) {
			$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
			$timestamp = date("Y-m-d H:i:s");
			$stmt = $link->prepare("INSERT INTO audit_trail (admin_user, action, ip_address, timestamp) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("ssss", $admin, $action, $ip, $timestamp);
			$stmt->execute();
		}
	}

	// CREATE
	if (!function_exists('createRecord')) {
		function createRecord($link, $table, $data) {
			global $db_tables;
			if (!isset($db_tables[$table])) return "Invalid table.";

			$columns = implode(", ", array_keys($data));
			$placeholders = implode(", ", array_fill(0, count($data), "?"));
			$sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
			$stmt = $link->prepare($sql);
			$stmt->bind_param(str_repeat("s", count($data)), ...array_values($data));
			return $stmt->execute() ? "Created successfully" : "Error: ".$stmt->error;
		}
	}

	// READ
	if (!function_exists('readRecord')) {
		function readRecord($link, $table, $id_value) {
			global $db_tables;
			if (!isset($db_tables[$table])) return "Invalid table.";
			$id_column = $db_tables[$table];
			$sql = "SELECT * FROM $table WHERE $id_column = ?";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $id_value);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}
	}

	// UPDATE
	if (!function_exists('updateRecord')) {
		function updateRecord($link, $table, $id_value, $data) {
			global $db_tables;
			if (!isset($db_tables[$table])) return "Invalid table.";
			$id_column = $db_tables[$table];

			$set = implode(", ", array_map(fn($col) => "$col = ?", array_keys($data)));
			$sql = "UPDATE $table SET $set WHERE $id_column = ?";
			$stmt = $link->prepare($sql);
			$params = array_merge(array_values($data), [$id_value]);
			$stmt->bind_param(str_repeat("s", count($data))."s", ...$params);
			return $stmt->execute() ? "Updated successfully" : "Error: ".$stmt->error;
		}
	}

	// DELETE
	if (!function_exists('deleteRecord')) {
		function deleteRecord($link, $table, $id_value) {
			global $db_tables;
			if (!isset($db_tables[$table])) return "Invalid table.";
			$id_column = $db_tables[$table];
			$sql = "DELETE FROM $table WHERE $id_column = ?";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $id_value);
			$delete = $stmt->execute();

			if ($delete) {
				$admin = $_SESSION['user'] ?? 'Unknown';
				$action = "Deleted from $table where $id_column = $id_value";
				logAuditTrail($link, $admin, $action);
				return "Deleted successfully";
			} else {
				return "Error deleting record: ".$stmt->error;
			}
		}
	}

	// Only process AJAX requests if this script is targeted directly
	if (basename($_SERVER['SCRIPT_FILENAME']) === 'crud_functions.php') {
		// Fallback for backward compatibility of simple delete POST (table & id only)
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action']) && isset($_POST['table']) && isset($_POST['id'])) {
			$result = deleteRecord($link, $_POST['table'], $_POST['id']);
			if (strpos($result, "Deleted successfully") !== false) {
				echo "Success";
			} else {
				echo $result;
			}
			exit;
		}

		header('Content-Type: application/json');
		
		$action = $_POST['action'] ?? $_GET['action'] ?? '';
		$table = $_POST['table'] ?? $_GET['table'] ?? '';
		
		if (empty($action) || empty($table)) {
			echo json_encode(['status' => 'error', 'message' => 'Missing action or table.']);
			exit;
		}
		
		if (!isset($db_tables[$table])) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid table.']);
			exit;
		}
		
		if ($action === 'create') {
			$data = $_POST['data'] ?? [];
			if (empty($data)) {
				echo json_encode(['status' => 'error', 'message' => 'No data provided.']);
				exit;
			}
			$result = createRecord($link, $table, $data);
			if (strpos($result, "Created successfully") !== false) {
				echo json_encode(['status' => 'success', 'message' => 'Record created.', 'id' => $link->insert_id]);
			} else {
				echo json_encode(['status' => 'error', 'message' => $result]);
			}
		} elseif ($action === 'read') {
			$id = $_POST['id'] ?? $_GET['id'] ?? '';
			if (empty($id)) {
				echo json_encode(['status' => 'error', 'message' => 'Missing ID.']);
				exit;
			}
			$record = readRecord($link, $table, $id);
			if ($record) {
				echo json_encode(['status' => 'success', 'data' => $record]);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Record not found.']);
			}
		} elseif ($action === 'update') {
			$id = $_POST['id'] ?? '';
			$data = $_POST['data'] ?? [];
			if (empty($id) || empty($data)) {
				echo json_encode(['status' => 'error', 'message' => 'Missing ID or data.']);
				exit;
			}
			$result = updateRecord($link, $table, $id, $data);
			if (strpos($result, "Updated successfully") !== false) {
				echo json_encode(['status' => 'success', 'message' => 'Record updated.']);
			} else {
				echo json_encode(['status' => 'error', 'message' => $result]);
			}
		} elseif ($action === 'delete') {
			$id = $_POST['id'] ?? '';
			if (empty($id)) {
				echo json_encode(['status' => 'error', 'message' => 'Missing ID.']);
				exit;
			}
			$result = deleteRecord($link, $table, $id);
			if (strpos($result, "Deleted successfully") !== false) {
				echo json_encode(['status' => 'success', 'message' => 'Record deleted.']);
			} else {
				echo json_encode(['status' => 'error', 'message' => $result]);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
		}
		exit;
	}
?>