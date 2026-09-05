<?php
include_once("index.php");
	if (isset($_POST['submit'])) {
		$Name = stripslashes($_POST['name']);
		$Email = stripslashes($_POST['email']);
		$Message = stripslashes($_POST['message']);
		$Date = date('l d.M.Y h:i A');
		
		$Name = str_replace("~", "-", $Name);
		$Email = str_replace("~", "-", $Email);
		$Message = str_replace("~", "-", $Message);
		$Date = str_replace("~", "-", $Date);
		$MessageRecord = "$Name~$Email~$Message~$Date\n";
		$MessageFile = fopen("message_board/forget_pass.txt", "ab");

	if ($MessageFile === FALSE)
		echo "<script>alert('There was an error saving your message! Please try again.');
			window.location.href='';</script>";
	else {
		fwrite($MessageFile, $MessageRecord);
			fclose($MessageFile);
		echo "<script>alert('Your request has been received and processed. Please check your email later. NOTE: For security reason, it is strongly recommended that you change your password after you login.');
			window.location.href='index.php';</script>";
		}
	}
?>