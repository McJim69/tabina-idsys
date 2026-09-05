<?php
require("connect.php");
	$startyear = date("Y")-10;
	$endyear=date("Y"); 

	$months=array('','01-Jan','02-Feb','03-Mar','04-Apr','05-May','06-Jun','07-Jul','08-Aug', '09-Sep','10-Oct','11-Nov','12-Dec');	
?>

<div style='text-align:center;font-size:14px;padding:5px;width:290px;border:1px solid #c4c4c4;border-radius:5px;background:#bbb'>Set New Countdown</div><br/>

<table>

<form action="countdown_new_proc.php" method="POST">
   <tr style="display:none">
		<td>ID<br/>
			<?php
				$sql = $link->query("SELECT * FROM countdown");
				while($rs = mysqli_fetch_array($sql)){
				$id="".$rs[0]."";
			?>
			<input style="padding:6px 5px 6px 5px;width:290px" name="id" type="text" value="<?php echo"".$id.""?>" /><br/>
			<?php } ?>
		</td>
	</tr>
   <tr>
		<td>Countdown Tile<br/>
			<input style="padding:6px 5px 6px 5px;width:290px" required name="name" type="text" placeholder="Countdown Title" /><br/>
		</td>
	</tr>
   <tr>
		<td>Countdown Date<br/>
			<select required style="padding:5px; width:98px" name="year">
				<option value="" selected="1">Year</option>
				<?php
					for($i=$startyear;$i<=$endyear;$i++){      
					echo"<option value='$i'>$i</option>";
					}
				?>
			</select>
			<select required style="padding:5px; width:98px" name="month">
				<option value="" selected="1">Month</option>
				<?php
					for($i=1;$i<=12;$i++){
					echo"<option value='$i'>$months[$i]</option>";
					}
				?>
			</select>			
			<select required style="padding:5px; width:98px" name="day">
				<option value="" selected="1">Day</option>
				<?php
					for($i=1;$i<=31;$i++){
					echo"<option $selected value='$i'>$i</option>";
					} 
				?>
			</select>
		</td>
    </tr>
   <tr>
		<td>Countdown Hour<br/>
			<select required style="padding:5px; width:98px" name="hour">
				<option value="" selected="1">Hour</option>
				<?php
					for($i=1;$i<=24;$i++){
					echo"<option value='$i'>$i</option>";
					}
				?>
			</select>
			<select required style="padding:5px; width:98px" name="min">
				<option value="" selected="1">Mimute</option>
				<?php
					for($i=1;$i<=60;$i++){
					echo"<option value='$i'>$i</option>";
					}
				?>
			</select>			
			<select required style="padding:5px; width:98px" name="sec">
				<option value="" selected="1">Second</option>
				<?php
					for($i=1;$i<=60;$i++){
					echo"<option value='$i'>$i</option>";
					}
				?>
			</select>
		</td>
    </tr>
		<td align="center" width="50%"><br/>
			<input style="padding:5px; width:65px" type="SUBMIT" value="Save" name='update' style="cursor:pointer">&nbsp;&nbsp;
			<a href="countdown.php"><input style="padding:5px; width:65px" type="button" value="Cancel"></a>
		</td>
	</form>
</table>
