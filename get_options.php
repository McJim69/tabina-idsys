<?php
	require("connect.php");

	$type = $_GET['type'] ?? '';
	$options = [];

	if ($type === 'barangay' && isset($_GET['city_mun'])) {
		$city_mun = $link->real_escape_string($_GET['city_mun']);
		$res = $link->query("SELECT DISTINCT barangay FROM districts WHERE city_mun='$city_mun' ORDER BY barangay");
		while ($row = mysqli_fetch_array($res)) {
			$options[] = $row['barangay'];
		}
	}

	if ($type === 'purok' && isset($_GET['barangay'])) {
		$barangay = $link->real_escape_string($_GET['barangay']);
		$res = $link->query("SELECT DISTINCT purok FROM districts WHERE barangay='$barangay' ORDER BY purok");
		while ($row = mysqli_fetch_array($res)) {
			$options[] = $row['purok'];
		}
	}

	echo json_encode($options);
?>