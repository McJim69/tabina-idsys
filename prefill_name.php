<?php
	$Fname_1st = "";
	$Mname_mid = "";
	$Lname_fam = "";
	$phone_val = "";
	$email_val = "";
	$birth_val = ""; 
	
	if (isset($_SESSION['user'])) {
		if (!empty($_SESSION['Fname'])) {
			$Fname_1st = $_SESSION['Fname'];
		}
		if (!empty($_SESSION['Mname'])) {
			$Mname_mid = $_SESSION['Mname'];
		}
		if (!empty($_SESSION['Lname'])) {
			$Lname_fam = $_SESSION['Lname'];
		}
		if (!empty($_SESSION['phone'])) {
			$phone_val = $_SESSION['phone'];
		}
		if (!empty($_SESSION['email'])) {
			$email_val = $_SESSION['email'];
		}
		if (!empty($_SESSION['birth'])) {
			$birth_val = $_SESSION['birth'];
		}
	}
?>
