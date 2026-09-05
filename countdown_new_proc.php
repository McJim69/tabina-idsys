<?php
require("connect.php");

		if(isset($_POST['update'])){	
			$id = $_POST['id'];
			$name= $_POST['name'];
			$year = $_POST['year'];
			$month = $_POST['month'];
			$day = $_POST['day'];
			$hour = $_POST['hour'];
			$min = $_POST['min'];
			$sec = $_POST['sec'];

		$update = $link->query("UPDATE countdown set
			id = '$id',	
			name= '$name', 
			year = '$year',
			month = '$month',
			day = '$day',
			hour = '$hour',
			min = '$min',
			sec = '$sec' where id = '$id'");
		
		if(($update)== TRUE){
			echo"<script>alert('New Countdown was Succesfully!');
			window.location.href = 'countdown.php';</script>";
			
			}else{
			
			echo"<script>alert('Ooppss! Cannot Set New Countdown');
			window.location.href = 'countdown.php';</script>";
		}	
	}	
?>
