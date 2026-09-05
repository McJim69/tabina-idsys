<?php
	require("connect.php");
	require("header.php");
	require("menu.php"); 
?>

<script>setActive("pwd");</script>
<script>setActive("social");</script>
<script>setActive("pwdcard");</script>

<link href="style/idcard.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-sm btn-outline-primary" type="button" onclick="jump('pwd_idcard.php')"><i class="fa fa-sync"></i> Refresh</button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="jump('pwd_list.php')"><i class="fa fa-list"></i> List View</button>
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" onclick="jump('pwd_grid.php')"><i class="fa fa-th"></i> Card View</button>
				<button class="bmargin btn-sm btn btn-outline-info" type="button" value='Print' onclick="printF()"><i class="fa fa-print"></i> Print</button>		
			</div>				
		</div>
	</div>
</div>

<div class="idmarg"></div>
<div align="center" class="ipad" style="width:100%;margin:0 auto">
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
				if($_GET["pwd"]!="")
					$mem=" and idn='".$_GET["pwd"]."' ";
																	
				$ex=$link->query("select * from pwd where idn=idn $mem order by idn limit $from,$to ");
				
				while($rs=mysqli_fetch_array($ex)){

				$ex=$link->query("select * from pwd where pwd.idn='$rs[0]' and pwd.idn=pwd.idn ");
							
				while(mysqli_fetch_array($ex)){
						
				$date_birth = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
				$age = "N/A";
				if (!empty($date_birth) && $date_birth !== '0000-00-00') {
					$birthDate_arr = explode("-", $date_birth);
					$birth_year = intval($birthDate_arr[0]);
					$birth_month = intval($birthDate_arr[1]);
					$birth_day = intval($birthDate_arr[2]);
					$age = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
				}
							
				$exsch=$link->query("select * from pwd l where l.idn='$rs[0]' and l.idn=l.idn ");
							
							
				while($rs=mysqli_fetch_array($exsch)){
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
					
					<div style='color:#FFF;font-size:17px;border-radius:3px;text-align:center;width:92px;position:absolute;top:67px;right:484px;z-index:99'>
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
						<div><i class='fa fa-home' style='width:18px'></i> <b>".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS</b></div>
						<div><i class='fa fa-birthday-cake' style='width:18px'></i> Birthdate: <b>".(!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs["date_birth"])) : "N/A")."</b></div>
						<div><i class='fa fa-venus-mars' style='width:18px'></i> Gender: <b>".$rs["sex"]."</b> &nbsp; Age : <b>".$age." </b> y.o.</div>
						<div><i class='fa fa-registered' style='width:18px'></i> Registered: <b>".$rs["assoc_reg_month"]."/".$rs["assoc_reg_day"]."/".$rs["assoc_reg_year"]."</b></div>
						<div><i class='fa fa-check' style='width:18px'></i> Valid Until: <b>December 31, "; echo date("Y")+2; echo"</b></div>
						<div><i class='fa fa-wheelchair' style='width:18px'></i> Disability: <b>".$rs["disability"]."</b></div>				
					</div>
							
					<div>
						<div style='font-family:Myriad Pro;font-size:12px;text-align:left;position:absolute;top:55px;left:525px'>
							<b style='font-size:20px;text-transform:uppercase'>".$rs["contactperson"]."</b><br>
							Relationship: <b>".$rs["relationship"]."</b><br>
							Contact  Number: <b>".$rs["emergencyno"]."</b><br>
							Address: <b>".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS</b>
						</div>

						<div style='font-family:Myriad Pro;font-size:11px;text-align:justify;position:absolute;top:140px;left:525px'>
							THE HOLDER OF THIS CARD IS A PERSON WITH DISABILITY AND IS ENTITLED TO DISCOUNTS 
							ON MEDICAL AND DENTAL SERVICES, PURCHASE OF EDICINES  AND BASIC COMMODITIES,
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
				}
			}
		}
	?>
</div>

<script>
	var table=0;
	function printF(){
		getID('menu_').style.display='none';	
		$('.spacer').css("display","none");
		$('.t_controls').css("display","none");
	window.print(); 
		getID('menu_').style.display='block';	
		$('.spacer').css("display","block");
		$('.t_controls').css("display","block");
	}
</script>

</body>

</html>