<?php
	include_once("connect2.php");
	
	if (isset($_POST['submit'])) {
		$Name = trim($_POST['name'] ?? '');
		$Email = trim($_POST['email'] ?? '');
		$Attention = trim($_POST['attention'] ?? 'All Citizens');
		$Subject = trim($_POST['subject'] ?? '');
		$Message = trim($_POST['message'] ?? '');

		if ($Subject !== '' && $Message !== '') {
			$combined_content = $Subject . " ~ " . $Message;

			$stmt = $link->prepare("INSERT INTO message_board (msgb_from, msgb_email, msgb_attnto, msgb_content) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("ssss", $Name, $Email, $Attention, $combined_content);
			
			if ($stmt->execute()) {
				echo "<script>
					alert('Your message has been saved.');
					window.location.href='message_board.php';
				</script>";
			} else {
				echo "<script>
					alert('Error saving message to database: " . addslashes($link->error) . "');
					window.location.href='message_board.php';
				</script>";
			}
			$stmt->close();
		} else {
			echo "<script>
				alert('Error: Subject and Message content cannot be empty.');
				window.location.href='message_board.php';
			</script>";
		}
	}
?>