<?php
	require("connect.php");
	require("header.php");
	require("menu.php"); 	

		$value=$_GET['value'];
			
		$bar="";
		if($_GET["barangays"]!="All barangays" && $_GET["barangays"]!="")
		$bar=" and barangay='".$_GET["barangays"]."'";
				
		if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
		}
		
		$rec=5;
		$p=$_GET['page'];
		if($p>1){
			$to=$p*$rec;
			$from=$to-$rec;
			$i=$to+1-$rec;
		}
		else{
			$to=$rec;
			$from=0;
			$i=1;
			$p=1;
		}
		
		$ex1=$link->query("select * from pwd l where 
		   (l.name_fam like'%".$value."%' or
			l.name_1st like'%".$value."%' or
			l.name_mid like'%".$value."%' or
			l.position like'%".$value."%' or
			l.purok like'%".$value."%' or
			l.barangay like'%".$value."%' or
			l.city_mun like'%".$value."%' or
			l.date_birth like'%".$value."%' or
			l.sex like'%".$value."%') $bar ");
					
		$ex=$link->query("select * from pwd l where 
		   (l.name_fam like'%".$value."%' or
			l.name_1st like'%".$value."%' or
			l.name_mid like'%".$value."%' or
			l.position like'%".$value."%' or
			l.purok like'%".$value."%' or
			l.barangay like'%".$value."%' or
			l.city_mun like'%".$value."%' or
			l.date_birth like'%".$value."%' or
			l.sex like'%".$value."%')
		$bar order by name_fam LIMIT $from,$to ");			
	//
?>

<script>setActive("social");</script>
<script>setActive("pwd");</script>
<script>setActive("pwdcard");</script>

<link href="style/idcard.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" onclick="jump('pwd_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('pwd_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid">Card View</x></button>
				<select class="swid spad bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All barangays')jump('pwd_idcard.php'); else jump('pwd_idcard.php?barangays='+this.value+'')">
					<option>All Barangays</option>
					<?php
						$ex2=$link->query("select barangay from pwd group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('pwd_idcard.php')"><i class="fa fa-sync tpad"></i> </i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='pwd_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add PWD</x></button></a>";
				?>	
				<button class="bmargin btn btn-sm btn-outline-secondary" type="button" onclick="printF()"><i class="fa fa-print tpad"></i> <x class="thid">Print</x></button>						
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
			</div>				
		</div>
	</div>
</div>

<div class="idmarg"></div>
<div align="center" class="ipad" style="width:100%;margin:0 auto">
<?php
	$value=$_GET['value'];
		
		$bar="";
		if($_GET["barangays"]!="All barangays" && $_GET["barangays"]!="")
		$bar=" and barangay='".$_GET["barangays"]."'";
				
		if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
		}
		
		$rec=5;
		$p=$_GET['page'];
		if($p>1){
			$to=$p*$rec;
			$from=$to-$rec;
			$i=$to+1-$rec;
		}
		else{
			$to=$rec;
			$from=0;
			$i=1;
			$p=1;
		}
		
		$ex1=$link->query("select * from pwd l where 
		   (l.name_fam like'%".$value."%' or
			l.name_1st like'%".$value."%' or
			l.name_mid like'%".$value."%' or
			l.position like'%".$value."%' or
			l.purok like'%".$value."%' or
			l.barangay like'%".$value."%' or
			l.city_mun like'%".$value."%' or
			l.date_birth like'%".$value."%' or
			l.sex like'%".$value."%') $bar ");
					
		$ex=$link->query("select * from pwd l where 
		   (l.name_fam like'%".$value."%' or
			l.name_1st like'%".$value."%' or
			l.name_mid like'%".$value."%' or
			l.position like'%".$value."%' or
			l.purok like'%".$value."%' or
			l.barangay like'%".$value."%' or
			l.city_mun like'%".$value."%' or
			l.date_birth like'%".$value."%' or
			l.sex like'%".$value."%') $bar order by name_fam LIMIT $from,$to ");			

		$value=strtoupper($_POST["t_search"]);
		$rep="<b style='color:#0014d0;background:#ffa0a0'>$value</b>";

		while($rs=mysqli_fetch_array($ex)){
		
		$date_birth = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
		$age = "N/A";
		if (!empty($date_birth) && $date_birth !== '0000-00-00') {
			$birthDate_arr = explode("-", $date_birth);
			$birth_year = intval($birthDate_arr[0]);
			$birth_month = intval($birthDate_arr[1]);
			$birth_day = intval($birthDate_arr[2]);
			$age = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
		}
					
		$exs=$link->query("select * from pwd l where l.idn='$rs[0]' and l.idn=l.idn ");
		$ii=1;
		
		while($rs=mysqli_fetch_array($exs)){
				if (!empty($rs['date_assoc_reg']) && $rs['date_assoc_reg'] !== '0000-00-00') {
					$assoc_time = strtotime($rs['date_assoc_reg']);
					$rs['assoc_reg_month'] = date('m', $assoc_time);
					$rs['assoc_reg_day'] = date('d', $assoc_time);
					$rs['assoc_reg_year'] = date('Y', $assoc_time);
				} else {
					$rs['assoc_reg_month'] = '';
					$rs['assoc_reg_day'] = '';
					$rs['assoc_reg_year'] = '';
				}
				if (!empty($rs['date_interview']) && $rs['date_interview'] !== '0000-00-00') {
					$inter_time = strtotime($rs['date_interview']);
					$rs['inter_month'] = date('m', $inter_time);
					$rs['inter_day'] = date('d', $inter_time);
					$rs['inter_year'] = date('Y', $inter_time);
				} else {
					$rs['inter_month'] = '';
					$rs['inter_day'] = '';
					$rs['inter_year'] = '';
				}
	
		echo "
			<div style='position:relative;width:930px;height:286px;' id='div_$rs[0]'>
				<img src='images/pwd/idback/idback.jpg?".date("h.i.s")."' height='286px' align='center'/>
			
				<div style='color:#FFF;font-size:17px;border-radius:3px;text-align:center;width:92px;position:absolute;top:66px;right:484px;z-index:99'>
					<b>"; echo date("Y"); echo"-"; $aid="".$rs["assoc_id_no"].""; printf("%04d", $aid); 
				echo"</b></div>
		
				<div style='border-radius:4px;position:absolute;left:12px;top:93px;height:150px;width:150px;overflow:hidden'>";
					
				if(file_exists("images/pwd/$rs[0].jpg")){
					echo"<img onclick=\"jump('pwd_pds.php?pwd=$rs[0]')\" src='images/pwd/$rs[0].jpg?".date("h.i.s")."'' height='150px' width='150px'/>";
				}else
					echo"<img onclick=\"jump('pwd_pds.php?pwd=$rs[0]')\" src='images/blank.jpg' height='150px' width='150px'/>";
			
				echo"</div>

				<div style='position:absolute;left:17px;top:246px;height:120px;width:120px;overflow:hidden'>";
			
				if(file_exists("images/pwd/signatures/$rs[0].png")){
					echo"<img src='images/pwd/signatures/$rs[0].png' height='35px'/><br/>";
				}else{
					echo"<img src='images/no_signature.png' height='35px'/><br/>";
				}
				echo"</div>										
				<div style='font-size:14px;font-family:Myriad Pro;text-align:left;position:absolute;left:160px;top:85px;width:300px;height:143px;padding:5px'>
					<div style='padding-top:4px;font-size:18px;color:#000; text-transform:uppercase'><b>".$rs["name_1st"]." ".$rs["name_mid"].". ".$rs["name_fam"]."</b></div>
					<div><i style='width:18px' class='fa fa-home'></i> <b>".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS</b></div>
					<div><i style='width:18px' class='fa fa-birthday-cake'></i> Birthdate: <b>".(!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs["date_birth"])) : "N/A")."</b></div>
					<div><i style='width:18px' class='fa fa-venus-mars'></i> Gender: <b>".$rs["sex"]."</b> &nbsp; Age : <b>".$age." </b> y.o.</div>
					<div><i style='width:18px' class='fa fa-registered'></i> Registered: <b>".$rs["assoc_reg_month"]."/".$rs["assoc_reg_day"]."/".$rs["assoc_reg_year"]."</b></div>
					<div><i style='width:18px' class='fa fa-check'></i> Valid Until: <b>December 31, "; echo date("Y")+2; echo"</b></div>
					<div><i style='width:18px' class='fa fa-wheelchair'></i> Disability: <b>".$rs["disability"]."</b></div>				
				</div>
				
				<div>
					<div style='font-family:Myriad Pro;font-size:12px;text-align:left;position:absolute;top:55px;left:525px'>
						<b style='font-size:20px;text-transform:uppercase'>".$rs["contactperson"]."</b><br>
						Relationship: <b>".$rs["relationship"]."</b><br>
						Contact  Number: <b>".$rs["emergencyno"]."</b><br>
						Address: <b>".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS</b>
					</div>

					<div style='font-family:Myriad Pro;font-size:11px;text-align:justify;position:absolute;top:140px;left:525px;padding-right:5px'>
						THE HOLDER OF THIS CARD IS A PERSON WITH DISABILITY AND IS ENTITLED TO DISCOUNTS 
						ON MEDICAL AND DENTAL SERVICES, PURCHASE OF MEDICINES  AND BASIC COMMODITIES,
						TRANSPORTATION, ADMISSION FEES IN ALL ESTABLISHMENTS AND EDUCATIONAL ASSISTANCE 
						AS AUTHORIZED BY RA 7277 AND ITS IMPLEMENTING RULES AND REGULATIONS. 
						ANY VIOLATION THEREOF IS PUNISHABLE BY LAW. THIS CARD IS NON-TRANSFERABLE.
					</div>
				
					<div style='position:absolute;top:50px;right:-5px'>";
					if(file_exists("images/pwd/qrcodes/".$rs["assoc_id_no"].".png")){
						echo"<div><img src='images/pwd/qrcodes/".$rs["assoc_id_no"].".png' height='80' width='80' /></div>";
					}else{
						echo"<div><img src='images/no_qrcode.png' height='80' width='80px' /></div>";
					}
				echo"</div>
				<div style='margin-bottom:-4px;margin-top:-4px'>&nbsp;</div>";
			$ii++;
		}
	}
	echo"</div></div>";
?>

</div>

</form>

<script>
	function printF(){
		$('.t_controls').css("display","none");
	window.print(); 
		$('.t_controls').css("display","block");
	}
</script>

</body>

</html>