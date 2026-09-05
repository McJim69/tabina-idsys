<?php
	require("connect.php");	
	require("header.php");
	require("menu.php");
?>	

<script>setActive("employee")</script>
<script>setActive("empcard")</script>

<?php include_once("display_notice.php");?>

<div class="t_controls">
	<table width="100%">
		<tr style="background:url('images/bg.jpg');border:0px">
		  <form method="post" enctype="multipart/form-data">
			<td colspan="14">
				<table align="center">
					<tr>
						<td><input type="button" value="Refresh" onclick="jump('employees_workpass.php')"/></td>
						<td><input type="button" value="List View" onclick="jump('employees_list.php')"/></td>
						<td><input type="button" value="Grid View" onclick="jump('employees_grid.php')"/></td>
						<td><input type='button' value='Print' onclick='printF()'/></td>	
						<?php
							if(!isset($_SESSION['user'])){
								echo"";
							}else
								echo"
							<td><a rel='facebox' href='employees_add.php'><input type='button' value='+Add Employee'/></a></td>";
						?>			
						<td>					
					</tr>
				</table>
			</td>
		  </form>
		</tr>
	</table>
</div>

<script>
	var table=0;
	function printF(){
		getID('menu_').style.display='none';	
		$('.grid').css("display","none");
		$('.t_controls').css("display","none");
	window.print(); 
		getID('menu_').style.display='block';	
		$('.grid').css("display","block");
		$('.t_controls').css("display","block");
	}
</script>

<div class="grid"></div><center>

<div style="width:100%;margin:0 auto">

<?php
	$rec=1;
	$p = isset($_GET['page']) ? intval($_GET['page']) : 1;
	if($p>1){
		$to=$rec;
		$from=($p*$rec)-$rec;
		$i=(($p-1)*$rec)+1;
	}else{
		$to=$rec;
		$from=0;
		$i=1;
		$p=1;
	}			
				
	$mem="";
	if(isset($_GET["employees"]) && $_GET["employees"]!="")
		$mem=" and idn='".$_GET["employees"]."' ";
														
	$ex=$link->query("select * from employees where idn=idn $mem order by idn limit $from,$to ")or die(mysqli_error($link));
	$ex1=$link->query("select * from employees where idn=idn $mem order by idn ")or die(mysqli_error($link));
			
			while($rs=mysqli_fetch_array($ex)){
				$app_time = !empty($rs['date_appointed']) && $rs['date_appointed'] !== '0000-00-00' ? strtotime($rs['date_appointed']) : time();
				$rs['app_day'] = date('d', $app_time);
				$rs['app_month'] = date('m', $app_time);
				$rs['app_year'] = date('Y', $app_time);
				
			$ex=$link->query("select * from employees where employees.idn='".$rs[0]."' and employees.idn=employees.idn ")or die(mysqli_error($link));
			$ii=1;
						
			while(mysqli_fetch_array($ex)){

			$exp=$link->query("select * from positions where pscode='".$rs["position"]."'")or die(mysqli_error($link));
			while($row=mysqli_fetch_array($exp)){
				$pcd = "".$row["pscode"]."";
				$psn = "".$row["psname"]."";
			}
				
			$exo=$link->query("select * from offices where ofcode='".$rs["department"]."'")or die(mysqli_error($link));
			while($row=mysqli_fetch_array($exo)){			
				$ofc = "".$row["ofcode"]."";
				$ofn = "".$row["ofname"]."";
			}
						
			$birthDate = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
			$birthDate = explode("-", $birthDate);
			$age=(date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0])))> date("md") ? ((date("Y")-$birthDate[0])-0):(date("Y")-$birthDate[0]));
						
			$exsch=$link->query("select * from employees l where l.idn='".$rs[0]." ' and l.idn=l.idn ")or die(mysqli_error($link));
						
			$exp=$link->query("select * from positions where pscode='".$rs["position"]."'")or die(mysqli_error($link));
			while($row=mysqli_fetch_array($exp)){
				$pcd = "".$row["pscode"]."";
				$psn = "".$row["psname"]."";
			}
			
			$exo=$link->query("select * from offices where ofcode='".$rs["department"]."'")or die(mysqli_error($link));
			while($row=mysqli_fetch_array($exo)){			
				$ofc = "".$row["ofcode"]."";
				$ofn = "".$row["ofname"]."";
			}

			echo"
			<div class='tombid'>
				<img src='images/working_pass.jpg' width='353px' height='530px' style='border-radius:10px'/>
					
			<div style='position:absolute;left:8px;top:188px'>";			
			echo"<img ";
				if(file_exists("images/employees/$rs[0].jpg")){
					echo" src='images/employees/$rs[0].jpg?".date("h:i:s")."' style='border-radius:4px;height:164px;width:164px'/>";
				} else
					echo" src='images/blank.jpg' style='border-radius:4px;height:164px;width:164px'/>";
			echo"</div>";

			echo"<div style='font-size:14px;position:absolute;right:10px;top:188px;height:152px;width:152px;padding:5px'>				
					<div style='color:#000;text-transform:uppercase;font-weight:bold;font-size:15px'>
						".str_replace($val,"$rep",$rs["name_1st"])."";
						if($rs["name_mid"]==""){
							echo" ";
						}else{
							echo" ".substr($rs["name_mid"],0).".";
						}						
						echo" ".str_replace($val,"$rep",$rs["name_fam"])."								
					</div>
					<div><small>$psn</small></div>
					<div style='padding:2px'></div>
					<div><small>".$rs["barangay"].", ".$rs["city_mun"].", ZDS</small></div>
					<div style='padding:2px'></div>
					<div>
						<small>ID No.:</small><br>
						<b>";
							$apm=$rs["app_month"];
							printf("%02d", $apm);
							$apd=$rs["app_day"];
							printf("%02d", $apd);
							echo"".$rs["app_year"]."-";
							$fdid = "".$rs[0]."";
							printf("%03d", $fdid); 
							echo"-".date ("Y")."
						</b>
					</div>
					<div style='padding:2px'></div>
					<div><small>Office:</small><b>";
						if($rs["agency"]=="BLGU"){
							echo"BLGU-".$rs["barangay"]."";
						}
						
						else if($rs["agency"]=="MLGU"){
							echo"LGU-".$rs["city_mun"]."";
						}

						else if(($rs["agency"]!=="MLGU") or ($rs["department"]!=="BLGU")){
							echo"".$ofn."";
						}
						
						echo"
						</b>
					</div>
					<div style='padding:2px'></div>					
					<div><small>Purpose:</small> <b>FRONTLINERS</b></div>
				</div>
	
				<div style='text-align:left;position:absolute;left:15px;top:110px;z-index:2'>
					<div style='margin-left:45px;font-family:Haettenschweiler;text-transform:uppercase;font-size:63px'>
						WORKING PASS
					</div>
				</div>
	
				<div style='text-align:left;position:absolute;left:0px;top:116px'>";
					if(file_exists("images/employees/qrcodes/$rs[0].png")){
						echo"<div><img src='images/employees/qrcodes/$rs[0].png' style='height:60px' /></div>";
					}else{
						echo"<div><img src='images/no_qrcode.png' style='height:60px' /></div>";
					}
				echo"
				</div>
			</div>
			<div class='idgrid' style='background:transparent;border:0;'></div>";
			if($i%3==0)
				echo "<div style='margin-bottom:10px;width:99.3%;'></div>";
			$i++;
		}
	}
?>
</div>
</center>

<br><br><br><br>
	<div style="position:fixed; left:0;width:100%;bottom:0;height:50px;background:#bbb;z-index:1000">
		<div style="padding:1px;text-align:center;" >
			<table style="margin:0 auto;">
				<tr style="background:transparent" >
				<td><input style="height:35px" type=image value="Previous" src="images/prev.png" onclick="jump('?page=<?php echo ($p-1)."&barangays=".urlencode($_GET["barangays"])."&employees=".urlencode($_GET["employees"]); ?>')" /></td>
				<td><select style='height:30px;padding:1px;text-align:center' id='s_pn' onchange="jump('?page='+this.value+'<?php echo "&barangays=".urlencode($_GET["barangays"])."&employees=".urlencode($_GET["employees"]); ?>')" >
					<option>Page</option>
					<?php
						for($j=1;$j<=mysqli_num_rows($ex1)/$rec+1;$j++){
							echo "<option ";
						
						if($p==$j)
							echo "selected";
							
						ECHO" >$j</option>";
						}
					?>
				</select>
				</td>
				<td><input style="height:35px" type=image value="Next" src="images/next.png" onclick="jump('?page=<?php echo ($p+1)."&barangays=".urlencode($_GET["barangays"])."&employees=".urlencode($_GET["employees"]); ?>')" /></td>
				</tr>
			</table>
		</div>
	</div>

</body>

</html>