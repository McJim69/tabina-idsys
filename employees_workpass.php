<?php
	require("connect.php");
	require("header.php");
	require("menu.php");
?>	

<script>setActive("employee")</script>
<script>setActive("emppass")</script>

<?php include_once("display_notice.php");?>

<div class="t_controls">

<table width="100%">
	<tr style="background:url('images/bg.jpg');border:0px">
	<form method="post" enctype="multipart/form-data">
		<td colspan="14">
			<table align="center">
				<tr>
					<td style="padding:0 0 0 15px;"><input placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" size="27" /></td>
					<td><input type="submit" name="b_search" value="Search"/></td>
					<td><input type="button" value="Refresh" onclick="getID('t_search').value='';jump('employees_workpass.php')"/></td>
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
					<td>
						<select style="padding:5px;" onchange="if(this.value=='All departments')jump('employees_grid.php'); else jump('employees_grid.php?departments='+this.value+'&positions=<?php echo $_GET["positions"];?>&barangays=<?php echo $_GET["barangays"];?>')">
							<option>All departments</option>
							<?php
								$ex2 = $link->query("select department from employees where position='".$_GET["positions"]."' group by department order by department");
								if($_GET["positions"]=="" || $_GET["positions"]=="All positions")
								$ex2 = $link->query("select department from employees group by department order by department");										
								while($rs2 = mysqli_fetch_array($ex2)){					
									echo"<option ";
									if($_GET["departments"]==="$rs2[0]")
									echo "selected";
									echo">$rs2[0]</option>";
								}
							?>
						</select>
					</td>
					<td>	
						<select style="padding:5px;" onchange="jump('?departments=<?php echo $_GET["departments"];?>&barangays=<?php echo $_GET["barangays"];?>&positions='+this.value)">
							<option>All positions</option>
							<?php
								$ex2 = $link->query("select position from employees where department='".$_GET["departments"]."' group by position order by position");
								if($_GET["departments"]=="" || $_GET["departments"]=="All departments")
								$ex2 = $link->query("select position from employees group by position order by position");										
								while($rs2 = mysqli_fetch_array($ex2)){					
									echo"<option ";
									if($_GET["positions"]==="$rs2[0]")
									echo "selected";
									echo">$rs2[0]</option>";
								}
							?>
						</select>
					</td>	
					<td>	
						<select style="padding:5px;" onchange="jump('?departments=<?php echo $_GET["departments"];?>&positions=<?php echo $_GET["positions"];?>&barangays='+this.value)">
							<option>All barangays</option>
							<?php
								$ex2 = $link->query("select barangay from employees where position='".$_GET["positions"]."' and department='".$_GET["departments"]."' group by barangay order by barangay");
								if($_GET["positions"]=="" || $_GET["positions"]=="All positions")
								$ex2 = $link->query("select barangay from employees group by barangay order by barangay");										
								while($rs2 = mysqli_fetch_array($ex2)){	
									echo"<option ";
									if($_GET["barangays"]==="$rs2[0]")
									echo "selected";
									echo">$rs2[0]</option>";
								}
							?>
						</select>
					</td>
				</tr>
  			</table>
		</td>
	</tr>
</table>

</div>

<script>
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
	$value=$_GET['value'];
				
	$dep="";
	if($_GET["departments"]!="All departments" && $_GET["departments"]!="")
	$dep=" and department='".$_GET["departments"]."'";
				
	$pos="";
		if($_GET["positions"]!="All Positions" && $_GET["positions"]!="")
			$pos=" and position='".$_GET["positions"]."'";

	$bar="";
		if($_GET["barangays"]!="All Barangays" && $_GET["barangays"]!="")
			$bar=" and barangay='".$_GET["barangays"]."'";
						
	if(isset($_POST["b_search"])){
	$value=$_POST["t_search"];
	}

	$rec=21;
	$p=$_GET['page'];
	if($p>1){
		$to=$p*$rec;
		$from=$to-$rec;
		$i=$to+1-$rec;
	}else{
	$to=$rec;
		$from=0;
		$i=1;
		$p=1;
	}
									
	$ex=$link->query("select * from employees l where 
	   (l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or		
		l.address like'%".$value."%' or			
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%') $dep $pos $bar order by idn DESC LIMIT $from,$to");		
				
	$ex1=$link->query("select * from employees l where 
	   (l.idn like'%".$value."%' or l.barangay like'%".$value."%') $dep $pos $bar order by idn DESC");		

	$value=strtoupper($_POST["t_search"]);
	$rep="<b style='color:#0014d0;background:#ffa0a0'>$value</b>";
	
	while($rs=mysqli_fetch_array($ex)){	
	$exem=$link->query("select * from employees l where l.idn='$rs[0]' and l.idn=l.idn");
	$ii=1;

	$exp=$link->query("select * from positions where pscode='".$rs["position"]."'");
	while($row=mysqli_fetch_array($exp)){
		$pcd = "".$row["pscode"]."";
		$psn = "".$row["psname"]."";
	}

	$aex=$link->query("select * from offices where ofcode='".$rs["department"]."'");
	while($row=mysqli_fetch_array($aex)){
		$ofc = "".$row["ofcode"]."";
		$ofn = "".$row["ofname"]."";
	}

	while($rs=mysqli_fetch_array($exem)){
		$app_time = !empty($rs['date_appointed']) && $rs['date_appointed'] !== '0000-00-00' ? strtotime($rs['date_appointed']) : time();
		$rs['app_day'] = date('d', $app_time);
		$rs['app_month'] = date('m', $app_time);
		$rs['app_year'] = date('Y', $app_time);
	
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
					<div><small>Agency:</small>
						<b>";
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
			<div class='tomb' style='background:transparent;border:0;'></div>";
			if($i%3==0)
				echo "<div style='margin-bottom:10px;width:99.3%;'></div>";
			$i++;
		}
	}
?>
</div>
</center>

<?php include("footerNAV.php");?>

</body>

</html>