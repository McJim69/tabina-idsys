<?php
	require("connect.php");
	require("header.php");
	require("menu.php"); 

	$value = $_POST["t_search"] !== '' ? $_POST["t_search"] : $_GET['value'];

	$rec=5;
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
				
	$dep="";
	if($_GET["departments"]!="All departments" && $_GET["departments"]!="")
	$dep=" and department='".$_GET["departments"]."'";

	$pos="";
	if($_GET["positions"]!="All positions" && $_GET["positions"]!="")
	$pos=" and position='".$_GET["positions"]."'";

	$bar="";
	if($_GET["barangays"]!="All barangays" && $_GET["barangays"]!="")
	$bar=" and barangay='".$_GET["barangays"]."'";
			
	$search_value=explode("=>",$_POST["t_search"]);

	if(isset($_POST["b_search"])){
		$search_value=explode("=>",$_POST["t_search"]);
	}
							
	$ex=$link->query("select * from employees l where 
	   (l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or
		l.name_1st like'%".$value."%' or
		l.name_mid like'%".$value."%' or
		l.department like'%".$value."%' or		
		l.agency like'%".$value."%' or
		l.position like'%".$value."%' or
		l.purok like'%".$value."%' or
		l.barangay like'%".$value."%') $dep $pos $bar order by name_fam LIMIT $from,$to ");			

	$ex1=$link->query("select * from employees l where 
	   (l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or		
		l.purok like'%".$value."%' or			
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%') $dep $pos $bar order by idn DESC ");		

	$value=strtoupper($_POST["t_search"]);
	$rep="<b style='color:#0014d0;background:#ffa0a0'>$value</b>";
?>	

<script> setActive("employee"); </script>
<script> setActive("empcard"); </script>

<link href="style/idcard.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" size="27" /></td>
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="thid bmargin btn btn-sm btn-outline-danger" type="button" onclick="getID('t_search').value='';jump('employees_idcard.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
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

<div class="idmarg"></div>
<div align="center" class="ipad" style="width:100%;margin:0 auto">
<?php
	$ii = 1;
	while($rs=mysqli_fetch_array($ex)){
					
		$birthDate = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
		$birthDate = explode("-", $birthDate);
		$age=(date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0])))> date("md") ? ((date("Y")-$birthDate[0])-0):(date("Y")-$birthDate[0]));
					
		$exe=$link->query("select * from employees l where l.idn='".$rs[0]." ' and l.idn=l.idn ");
					
		$exp=$link->query("select * from positions where pscode='".$rs["position"]."'");
		while($row=mysqli_fetch_array($exp)){
			$pcd = "".$row["pscode"]."";
			$psn = "".$row["psname"]."";
		}
		
		$exo=$link->query("select * from offices where ofcode='".$rs["department"]."'");
		
		while($row=mysqli_fetch_array($exo)){			
			$ofc = "".$row["ofcode"]."";
			$ofn = "".$row["ofname"]."";
		}

		while($rs=mysqli_fetch_array($exe)){
			$app_time = !empty($rs['date_appointed']) && $rs['date_appointed'] !== '0000-00-00' ? strtotime($rs['date_appointed']) : time();
			$rs['app_day'] = date('d', $app_time);
			$rs['app_month'] = date('m', $app_time);
			$rs['app_year'] = date('Y', $app_time);
		// Front ID Card
		echo"
			<div style='position:relative;width:930px;height:286px;' id='div_$rs[0]'>
			
			<img align='center' ";
			
				if( ($rs["position"]=="DPM1") or 
					($rs["position"]=="DPM2") or 
					($rs["position"]=="DPM3")){
					
					echo"src='images/employees/idback/deputy.png? ".date("h.i.s.")."' height='286px'/>";
					
				}else if( 
					($rs["position"]=="LCEO") or 
					($rs["position"]=="MVCM") or
					($rs["position"]=="SBMO") or			
					($rs["position"]=="SKFP") or 
					($rs["position"]=="BCAP") or  
					($rs["position"]=="BKAG") or
					($rs["position"]=="BSKC")){
				
					echo"src='images/employees/idback/official.png? ".date("h.i.s.")."' height='286px'/>";
					
				}else if
					($rs["position"]=="PWDS"){
					echo"src='images/employees/idback/pwd.png? ".date("h.i.s.")."' height='286px'/>";

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
			
					echo"src='images/employees/idback/employee.png? ".date("h.i.s.")."' height='286px'/>";
				}
					
			echo"
		
			<div style='background:transparent;border-radius:5px;position:absolute;left:7px;top:100px;height:122px;width:122px;overflow:hidden'>";
			
			if(file_exists("images/employees/photos/$rs[0].jpg")){
				echo"<img onclick=\"jump('employees_pds.php?employees=$rs[0]')\" src='images/employees/photos/$rs[0].jpg?".date("h:i:s")."' height='122px' width='122px'/>";
			}else
				echo"<img onclick=\"jump('employees_pds.php?employees='$rs[0]')\" src='images/blank.jpg' height='122px' width='122px'/>";
			
			echo"</div>
							
			<div style='border-radius:4px;padding:1px;background:#FFF;position:absolute;left:7px;top:230px;height:32px;width:120px'>";
			
			if(file_exists("images/employees/signatures/$rs[0].png")){
				echo"<img src='images/employees/signatures/$rs[0].png?".date("h:is")."' height='30px'/><br/>";
			}else{
				echo"<img src='images/no_signature.png' height='30px'/><br/>";
			}
			
			echo"</div>";
															
			echo"
				<div style='font-size:14px;text-align:left;position:absolute;left:137px;top:90px;width:320px;height:143px;background:transparent;padding:5px;'>
					<div style='font-size:20px;font-weight:bold; text-transform:uppercase'>
						".str_replace($val,"$rep",$rs["name_1st"])."";
						if($rs["name_mid"]==""){
							echo" ";
						}else{
							echo" ".substr($rs["name_mid"],0)."";
						}						
							echo" ".str_replace($val,"$rep",$rs["name_fam"])."			
					</div>
					<div><i class='fa fa-home' style='width:18px'></i> Address: <b>".$rs["barangay"].", ".$rs["city_mun"].", ZDS</b></div>
					<div><i class='fas fa-birthday-cake' style='width:18px'></i> Birth: <b>".(!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs["date_birth"])) : "N/A")."</b> &nbsp; Age: <b>$age</b> Years Old</div>
					<div><i class='fas fa-tasks' style='width:18px'></i> Position: <b>".$psn."</b></div>
					<div><i class='fas fa-building' style='width:18px'></i> Division: <b>".$ofn."</b></div>
					<div><i class='far fa-address-card' style='width:18px'></i> ID No.: 
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
				</div>";
				// Back ID Card	
				echo"
				<div style='font-size:12px;background:transparent;position:absolute;top:21px;left:498px;font-size:12px;'>

				<div style='background:transparent;text-align:left;padding:2px;border-radius:5px'>
					PagIBIG No: <b style='color:#121a7e'> ".$rs["pagibig"]."</b>
				</div>
				<div style='margin-top:6px;background:transparent;text-align:left;padding:2px;border-radius:5px'>
					PhilHealth No: <b style='color:#121a7e'> ".$rs["philhealth"]."</b>
				</div>
				<div style='margin-top:6px;background:transparent;text-align:left;padding:2px;border-radius:5px'>";		
					$phone=$rs["contact"];								
						if(in_array(strtolower($phone[0] ?? ''),array('0','1','2','3','4','5','6','7','8','9'))){
							echo"Phone: <b style='color:#121a7e'> ".$phone." </b>";
						}else{
							echo"&nbsp;";									
						}
								
						$mail=$rs["emailadd"];
						if (in_array(strtolower($mail[0] ?? ''),array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
							echo" &nbsp; &bull;  &nbsp; Email: <b style='color:#121a7e'> ".$mail." </b>";
						}else{
							echo"&nbsp;";
						}
					echo"	
				</div>				
				<div style='position:absolute;top:0px;left:210px;background:transparent;text-align:left;padding:2px;border-radius:5px'>
					GSIS/SSS: <b style='color:#121a7e'> ".$rs["gsis"]."</b>
				</div>
				<div style='position:absolute;top:28px;left:210px;background:transparent;text-align:left;padding:2px;border-radius:5px'>
					TIN: <b style='color:#121a7e'> ".$rs["tin"]."</b>
				</div><div style='margin:1px'>&nbsp;</div>
				<div style='margin-top:-10px;margin-left:-15px;background:transparent; width:410px; text-align:justify;'>
					<small>IT IS HEREBY CERTIFIED THAT THE INDIVIDUAL WHOSE PHOTOGRAPH AND SIGNATURE APEAR ON THE REVERSE HEREOF IS AN EMPLOYEE OF THE MUNICIPALITY OF TABINA. IF FOUND, PLEASE RETURN TO THE MUNICIPALITY OF TABINA, DISTRICT HIBINO, POBLACION TABINA, ZAMBOANGA DEL SUR, PHILIPPINES, 7034.</small>
				</div><br>
				<div style='margin-top:-10px;background:transparent;width:425px; text-align:justify;'>
					<x style='color:red'>Incase of Emergency, Please Notify:</x>
					<div style='font-weight:bold;text-transform:uppercase;font-size:20px'>".$rs["contactperson"]."</div>
					<div>Relation : &nbsp;".$rs["relationship"]."</div>
					<div>Contact No : &nbsp;<b>".$rs["emergencyno"]."</b></div>
					<div style='text-transform:uppercase'>";
						$add=$rs["purok"];
						if(in_array(strtolower($add[0] ?? ''),array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
							echo"".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", Zamboanga del Sur";
						}else{
							echo"".$rs["barangay"].", ".$rs["city_mun"].", Zamboanga del Sur";
						}
					echo "
					</div>
				</div>";	
				
				echo"<div style='position:absolute;top:146px;right:2px'>";
					if(file_exists("images/employees/qrcodes/$rs[0].png")){
						echo"<div><img src='images/employees/qrcodes/$rs[0].png' style='height:100px' /></div>";
					}
				echo"</div>
				<!--
				<div style='background:#fff;position:absolute;left:2px;top:235px;font-family:CCode39'>
					".$rs["name_1st"]." ".$rs["name_mid"]." ".$rs["name_fam"]."-";
					$cont = "".$rs[0]."";
					printf("%04d", $cont); 
					echo"
				</div>
				-->
				</div>
				<div style='margin-bottom:12px;margin-top:2px'></div>";
			$ii++;
		}
	}
?>

</div><br>

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