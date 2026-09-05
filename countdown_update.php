<?php
require("connect.php");
	
$sql = $link->query("SELECT * FROM countdown");
while($rs = mysqli_fetch_array($sql)) {
	$id="".$rs['id']."";
	$name="".$rs['name']."";
	$y="".$rs['year']."";	
	$m="".$rs['month']."";
	$d="".$rs['day']."";
	$h="".$rs['hour']."";
	$i="".$rs['min']."";
	$s="".$rs['sec']."";	
?>

<div style='color:#FFF;text-align:center;font-size:14px;padding:10px;width:280px;border:1px solid #c4c4c4;border-radius:5px;background:#757070'><b>Update Countdown</b></div><br/>

<table>

<form action="countdown_new_proc.php" method="POST">
   <tr style="display:none">
		<td style='color:#3d3d3d'>ID<br/>
			<input style="padding:6px 5px 6px 5px;width:285px" name="id" type="text" value="<?php echo"".$id.""?>" />
		</td>
	</tr>
   <tr>
		<td style='color:#3d3d3d'>Countdown Tile<br/>
			<input style="padding:6px 5px 6px 5px;width:285px" required name="name" type="text" value="<?php echo"".$name.""?>" />
		</td>
	</tr>
	<tr>
		<td style='color:#3d3d3d'>Countdown Date<br/>
			<input style="padding:6px 5px 6px 5px;width:85px" required name="year" type="text" value="<?php echo"".$y.""?>" />
			<input style="padding:6px 5px 6px 5px;width:85px" required name="month" type="text" value="<?php echo"".$m.""?>" />
			<input style="padding:6px 5px 6px 5px;width:85px" required name="day" type="text" value="<?php echo"".$d.""?>" />			
		</td>
    </tr>
	<tr>
		<td style='color:#3d3d3d'>Countdown Time<br/>
			<input style="padding:6px 5px 6px 5px;width:85px" required name="hour" type="text" value="<?php echo"".$h.""?>" />
			<input style="padding:6px 5px 6px 5px;width:85px" required name="min" type="text" value="<?php echo"".$i.""?>" />
			<input style="padding:6px 5px 6px 5px;width:85px" required name="sec" type="text" value="<?php echo"".$s.""?>" />			
		</td>
    </tr>	
		<td align="center" width="50%"><br/>
			<input style="padding:5px; width:65px" type="SUBMIT" value="Save" name='update' style="cursor:pointer">&nbsp;&nbsp;
			<a href="countdown.php"><input style="padding:5px; width:65px" type="button" value="Cancel"></a>
		</td>
	</form>
</table>
<?php } ?>