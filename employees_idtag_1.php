<?php
	require("connect.php");	
	require("header.php");
	require("menu.php"); 

	if(!$_SESSION["user"]=="Administrator"){
		header('location:index.php');
	}	
?>	

<script>setActive("employee")</script>
<script> setActive("empcard"); </script>

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('employees_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('employees_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid">Grid</x></button>
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

<div class="list spid"></div>
<center>
<div style="width:100%;margin:0 auto">

<?php
	$rec=1;
		$p=$_GET['page'];
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
			if($_GET["employees"]!="")
				$mem=" and idn='".$_GET["employees"]."' ";
																
			$ex=$link->query("select * from employees where idn=idn $mem order by idn limit $from,$to ");
			
			while($rs=mysqli_fetch_array($ex)){			

			$ex=$link->query("select * from employees where employees.idn='".$rs[0]."' and employees.idn=employees.idn ");
			$ii=1;
						
			while($ex->fetch_array()){

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
						
			$birthDate = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
			$birthDate = explode("-", $birthDate);
			$age=(date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0])))> date("md") ? ((date("Y")-$birthDate[0])-0):(date("Y")-$birthDate[0]));
						
			$exe=$link->query("select * from employees l where l.idn='$rs[0]' and l.idn=l.idn ");
						
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
		
		echo"
		<div class='tombid'>
		<img src='images/employees/idback/idtag.jpg?".date("h:i:s")."' width='353px' height='530px' style='border-radius:10px'/>			
			<div style='position:absolute;left:8px;top:164px'>";			
			echo"<img ";
				if(file_exists("images/employees/photos/$rs[0].jpg")){
					echo" src='images/employees/photos/$rs[0].jpg?".date("h:i:s")."' style='background:#FFF;border-radius:4px;height:164px;width:164px'/>";
				} else
					echo" src='images/blank.jpg' style='border-radius:4px;height:164px;width:164px'/>";
			echo"</div>";
			
			echo"<div style='position:absolute;left:0;right:0;top:96px'><img ";
				
			if(($rs["position"]=="DPM1") or ($rs["position"]=="DPM2") or ($rs["position"]=="DPM3")){			
				echo" src='images/deputy.png' height='58' />";

			}elseif
				(($rs["position"]=="LCEO") or 
				($rs["position"]=="MVCM") or
				($rs["position"]=="SBMO") or			
				($rs["position"]=="SKFP") or 
				($rs["position"]=="BCAP") or  
				($rs["position"]=="BKAG") or
				($rs["position"]=="BSKC")){
		
				echo" src='images/official.png' height='58' />";		
			}elseif
				(($rs["position"]!=="DPM1") or 
				($rs["position"]!=="DPM2") or 
				($rs["position"]!=="DPM3") or
				($rs["position"]!=="LCEO") or 
				($rs["position"]!=="MVCM") or
				($rs["position"]!=="SBMO") or			
				($rs["position"]!=="SKFP") or 
				($rs["position"]!=="BCAP") or  
				($rs["position"]!=="BKAG") or
				($rs["position"]!=="BSKC")){
			
				echo" src='images/employee.png' height='58' />";				
			}
			
			echo"</div>";
			
			echo"<div style='font-size:12px;position:absolute;right:10px;top:167px;height:152px;width:152px;border-radius:4px;padding:5px'>				
					THIS IS TO CERTIFY</b> that the bearer whose name and photo appear herein is ";

					if(in_array(strtolower($psn[0] ?? ''),array('a', 'e', 'i', 'o', 'u'))){
						echo " an <b>$psn</b> of ";
					}else{
						echo " a <b>$psn</b> of ";
					}

					if ($rs["department"]=="BLGU"){
						echo" $ofc-".$rs["barangay"].", ".$rs["city_mun"].", ZDS, Region IX.";
					}
					elseif($rs["department"]=="MLGU"){
						echo" $ofn, LGU-".$rs["city_mun"].", ZDS, Region IX.";
					}
					elseif(($rs["agency"]!=="MLGU") or ($rs["department"]!=="BLGU")){
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
			</div>";
			}
		}
	}
?>

</div>

<script>
	var table=0;
	function printF(){
		$('.list').css("display","none");
		$('.t_controls').css("display","none");
	window.print(); 
		$('.list').css("display","block");
		$('.t_controls').css("display","block");
	}
</script>

</body>

</html>