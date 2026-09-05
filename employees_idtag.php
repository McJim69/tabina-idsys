<?php
	require("connect.php");	
	require("header.php");
	require("menu.php");

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
						
	$value = '';
	if (isset($_POST['t_search'])) {
		$value = $_POST['t_search'];
	} else if (isset($_GET['value'])) {
		$value = $_GET['value'];
	}

	$rec=21;
	$p = isset($_GET['page']) ? intval($_GET['page']) : 1;
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
		l.purok like'%".$value."%' or			
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%') $dep $pos $bar order by idn DESC LIMIT $from,$to ");		

	$ex1=$link->query("select * from employees l where 
	   (l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or		
		l.purok like'%".$value."%' or			
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%') $dep $pos $bar order by idn DESC ");		
				
	$value=strtoupper(isset($_POST["t_search"]) ? $_POST["t_search"] : (isset($_GET["value"]) ? $_GET["value"] : ""));
	$rep="<b style='color:#0014d0;background:#ffa0a0'>$value</b>";
?>	

<script>setActive("employee")</script>
<script> setActive("emptag"); </script>

<link href="fonts/style.css" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" size="27" /></td>
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('employees_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List</x></button>
				<select class="swid spad bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All departments')jump('employees_idcard.php'); else jump('employees_idcard.php?departments='+this.value+'&positions=<?php echo $_GET["positions"];?>&barangays=<?php echo $_GET["barangays"];?>')">
					<option>All departments</option>
					<?php
						$ex2=$link->query("select department from employees where position='".$_GET["positions"]."' group by department order by department");
							if($_GET["positions"]=="" || $_GET["positions"]=="All positions")
						$ex2=$link->query("select department from employees group by department order by department");										
							while($rs2=mysqli_fetch_array($ex2)){					
							echo"<option ";
							if($_GET["departments"]==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<select class="thid swid spad bmargin btn btn-sm btn-outline-dark" onchange="jump('?departments=<?php echo $_GET["departments"];?>&barangays=<?php echo $_GET["barangays"];?>&positions='+this.value)">
					<option>All positions</option>
					<?php
						$ex2=$link->query("select position from employees where department='".$_GET["departments"]."' group by position order by position");
							if($_GET["departments"]=="" || $_GET["departments"]=="All departments")
						$ex2=$link->query("select position from employees group by position order by position");										
							while($rs2=mysqli_fetch_array($ex2)){	
							echo"<option ";
							if($_GET["positions"]==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<select class="thid swid spad bmargin btn btn-sm btn-outline-dark" onchange="jump('?departments=<?php echo $_GET["departments"];?>&positions=<?php echo $_GET["positions"];?>&barangays='+this.value)">
					<option>All barangays</option>
					<?php
						$ex2=$link->query("select barangay from employees where position='".$_GET["positions"]."' and department='".$_GET["departments"]."' group by barangay order by barangay");
							if($_GET["positions"]=="" || $_GET["positions"]=="All positions")
						$ex2=$link->query("select barangay from employees group by barangay order by barangay");										
							while($rs2=mysqli_fetch_array($ex2)){
							echo"<option ";
							if($_GET["barangays"]==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<select class="thid swid spad bmargin btn btn-sm btn-outline-primary" onchange="jump('?page='+this.value+'<?php echo "&barangay=".$_GET["barangay"]; ?>')" >
					<option>Page</option>
					<?php
						for($j=1;$j<=mysqli_num_rows($ex1)/$rec+1;$j++){
							echo "<option ";
						if($_GET["page"]==$j)
							echo "selected";	
						echo" >$j</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-primary" type="button" onclick="printF()"><i class="fa fa-print tpad"></i> <x class="thid">Print</x></button>	
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='employees_add.php'><button class='bmargin btn btn-sm btn-outline-danger' type='button'><i class='fa fa-plus tpad'></i>  <x class='thid'>Add</x></button></a>";
				?>			
			</div>				
		</div>
	</div>
</div>

<div class="spid"></div>

<div class="list"></div>

<div align="center" style="width:100%;margin:0 auto">

<?php
	
	while($rs=mysqli_fetch_array($ex)){	
	$exsch=$link->query("select * from employees l where l.idn='$rs[0]' and l.idn=l.idn ");
	$ii=1;

	$exd=$link->query("select * from offices where ofcode='".$rs["department"]."'");
	while($row=mysqli_fetch_array($exd)){	
		$ofc = "".$row["ofcode"]."";
		$ofn = "".$row["ofname"]."";
	}

	$exp=$link->query("select * from positions where pscode='".$rs["position"]."'");
	while($row=mysqli_fetch_array($exp)){	
		$pcd = "".$row["pscode"]."";
		$psn = "".$row["psname"]."";
	}

	while($rs=mysqli_fetch_array($exsch)){
	
	echo"
		<div class='tombid'>
		
			<img align='center' ";
				
				if( ($rs["position"]=="DPM1") or 
					($rs["position"]=="DPM2") or 
					($rs["position"]=="DPM3")){
					
					echo"src='images/employees/idback/deputy.jpg? ".date("h.i.s.")."' width='353px' height='530px' style='border-radius:10px'/>";
					
				}else if( 
					($rs["position"]=="LCEO") or 
					($rs["position"]=="MVCM") or
					($rs["position"]=="SBMO") or			
					($rs["position"]=="SKFP") or 
					($rs["position"]=="BCAP") or  
					($rs["position"]=="BKAG") or
					($rs["position"]=="BSKC")){
					
					echo"src='images/employees/idback/official.jpg? ".date("h.i.s.")."' width='353px' height='530px' style='border-radius:10px'/>";
						
				}else if( 
					($rs["position"]!=="DPM1") or 
					($rs["position"]!=="DPM2") or 
					($rs["position"]!=="DPM3") or
					($rs["position"]!=="LCEO") or 
					($rs["position"]!=="MVCM") or
					($rs["position"]!=="SBMO") or			
					($rs["position"]!=="SKFP") or 
					($rs["position"]!=="BCAP") or  
					($rs["position"]!=="BKAG") or
					($rs["position"]!=="BSKC")){
				
					echo"src='images/employees/idback/employee.jpg? ".date("h.i.s.")."' width='353px' height='530px' style='border-radius:10px'/>";
				}
	
			echo"	
			<div style='position:absolute;left:8px;top:164px'>";			
			echo"<img ";
				if(file_exists("images/employees/photos/$rs[0].jpg")){
					echo" src='images/employees/photos/$rs[0].jpg?".date("h:i:s")."' style='background:#FFF;border-radius:4px;height:164px;width:164px'/>";
				} else
					echo" src='images/blank.jpg' style='border-radius:4px;height:164px;width:164px'/>";
			echo"</div>";
			
			echo"
				<div style='font-size:12px;position:absolute;right:10px;top:167px;height:152px;width:152px;border-radius:4px;padding:5px'>				
					THIS IS TO CERTIFY</b> that the bearer whose name and photo appear herein is ";
							
					if(in_array(strtolower($psn[0] ?? ''),array('a', 'e', 'i', 'o', 'u'))){
						echo " an <b>$psn</b> of ";
					}else{
						echo " a <b>$psn</b> of ";
					}

					if ($rs["department"]=="BLGU"){
						echo" $ofc-".$rs["barangay"].", ".$rs["city_mun"].", ZDS, Region IX.";
					}
					else if($rs["department"]=="MLGU"){
						
						echo" $ofn, LGU-".$rs["city_mun"].", ZDS, Region IX.";
					}
					else if(($rs["agency"]!=="MLGU") or ($rs["department"]!=="BLGU")){
						echo"".$ofn.", ".$rs["city_mun"].", Zamboanga del Sur, Region IX, Philippines.";
					}
											
			echo"</div>
			
				<div style='position:relative;top:-195px'>
					<div style='font-family:Airborne Regular;text-transform:uppercase;color:#FFF;font-size:24px'>
						".str_replace($val,"$rep",$rs["name_1st"])."";
						if($rs["name_mid"]==""){
							echo" ";
						}else{
							echo" ".substr($rs["name_mid"],0).".";
						}						
							echo" ".str_replace($val,"$rep",$rs["name_fam"])."			
					</div>
					
					<div style='font-size:14px;color:#FFF'>";
					if($rs["position"]=="DPM3"){
						echo"<i>Mun. Consultant on Legislative Matters<br>$psn</i>";
					}else
						echo"<i>$psn</i>";
					echo"
					</div>

				<div style='text-align:center;position:absolute;left:2px;top:77px'>";
					if(file_exists("images/employees/qrcodes/$rs[0].png")){
						echo"<div><img src='images/employees/qrcodes/$rs[0].png' style='height:78px' /></div>";
					}
				echo"
				</div>
				
				</div>
	
			</div>
			<div class='tomb' style='background:transparent;border:0;'></div>";
			if($i%3==0)
				echo "<div style='margin-bottom:-35px;width:99.3%;'></div>";
			$i++;
		}
	}
?>
</div>

<script>
	function printF(){
		$('.t_controls').css("display","none");
		$('.list').css("display","none");
	window.print(); 
		$('.t_controls').css("display","block");
		$('.list').css("display","block");
	}
</script>

</body>

</html>