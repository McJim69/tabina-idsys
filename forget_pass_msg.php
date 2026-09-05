<center>
	<strong style="color:green;font-size:20px">Password Recovery Request</strong>
</center>
	
<?php
	if ((!file_exists("message_board/forget_pass.txt")) || (filesize("message_board/forget_pass.txt") == 0))
		echo "<center><p style='font-size:14px;color:#3c763d'>There are no messages posted.</p><br/>\n </center>";
	else {
	$MessageArray = file("message_board/forget_pass.txt");

		echo "<table>\n";
	
	$count = count($MessageArray); 		
	for ($i = 0; $i < $count; ++$i){
		$CurrMsg = explode("~", $MessageArray[$i]);
		$KeyMessageArray[$CurrMsg[0]] = $CurrMsg[1] . "~" . $CurrMsg[2] . "~" . $CurrMsg[3];
		echo "<tr style='background:transparent;border:0px'>\n";
		echo "<td width='5%' style='text-align:center;font-weight:bold;font-size:13px;color:#428bca;border:1px solid gray'>".($i + 1)."</td>\n";
		echo "<td width='90%' style='text-align:left; padding:5px 5px 5px 10px;font-size:13px; border:1px solid gray'><span style='font-weight:bold'>From&nbsp;: </span><b style='color:darkgreen'>".htmlentities($CurrMsg[0])."</b><br/>\n";
		echo "<span style='font-weight:bold;color:darkblue'>Email&nbsp;&nbsp;: </span><b><a href='#'>".htmlentities($CurrMsg[1])."</b></a><br/>\n";
		echo "<span style='text-align:center; font-weight:bold'>Message: </span>\n" . htmlentities($CurrMsg[2]) . "</td>\n";
		echo "<td width='5%' style='padding:5px;text-align:center; font-size:12px;font-weight:bold;border:1px solid gray'>".htmlentities($CurrMsg[3])."</td>\n";
		echo "
		</tr>\n";
	}
		echo "</table>\n ";
	}
?> 