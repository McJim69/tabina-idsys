<?php
	require("connect.php");
	require("header.php");
	require("menu.php"); 
?>	

<script>setActive("solo_parent");</script>
<script>setActive("social");</script>

<link href="style/idcard.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-sm btn-outline-primary" type="button" onclick="jump('solo_parent_idcard.php')"><i class="fa fa-sync"></i> Refresh</button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="jump('solo_parent_list.php')"><i class="fa fa-list"></i> List View</button>
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" onclick="jump('solo_parent_grid.php')"><i class="fa fa-th"></i> Card View</button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" value='Print' onclick="printF()"><i class="fa fa-print"></i> Print</button>		
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
				if($_GET["solo_parent"]!="")
					$mem=" and idn='".$_GET["solo_parent"]."' ";
																	
				$ex=$link->query("select * from solo_parent where idn=idn $mem order by idn limit $from,$to ");
				
				include('qrlib/qrlib.php');

				while($rs=mysqli_fetch_array($ex)){

				$idn = $rs[0];
				$data = "".$rs["name_1st"]." ".$rs["name_mid"].". ".$rs["name_fam"]."\nID: ".$rs["assoc_id_no"];		
				$tempDir = "images/solo_parent/qrcodes/";
				$codeContents =  "".$data."";
				$fileName = "".$idn.".png";
				$pngAbsoluteFilePath = $tempDir.$fileName;
				QRcode::png($codeContents, $pngAbsoluteFilePath); 						
				
				$date_birth = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
				$age = "N/A";
				if (!empty($date_birth) && $date_birth !== '0000-00-00') {
					$birthDate_arr = explode("-", $date_birth);
					$birth_year = intval($birthDate_arr[0]);
					$birth_month = intval($birthDate_arr[1]);
					$birth_day = intval($birthDate_arr[2]);
					$age = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
				}
							
				$exs=$link->query("select * from solo_parent l where l.idn='$rs[0]' and l.idn=l.idn ");
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
						<img src='images/solo_parent/idback/idback.jpg?".date("h.i.s")."' height='286px' align='center'/>
					
					<div style='color:#FFF;font-size:17px;border-radius:3px;text-align:center;padding:2px;width:92px;position:absolute;top:65px;right:484px;z-index:99'>
						<b>"; echo date("Y"); echo"-"; $aid="".$rs["assoc_id_no"].""; printf("%04d", $aid); 
					echo"</b></div>
				
					<div style='border-radius:4px;position:absolute;left:12px;top:94px;height:150px;width:150px;overflow:hidden'>";
							
					if(file_exists("images/solo_parent/$rs[0].jpg")){
						echo"<img onclick=\"jump('solo_parent_pds.php?solo_parent=$rs[0]')\" src='images/solo_parent/$rs[0].jpg?".date("h.i.s")."'' height='150px' width='150px'/>";
					}else
						echo"<img onclick=\"jump('solo_parent_pds.php?solo_parent=$rs[0]')\" src='images/blank.jpg' height='150px' width='150px'/>";
					
					echo"</div>

					<div style='position:absolute;left:17px;top:246px;height:120px;width:120px;overflow:hidden'>";
					
					if(file_exists("images/solo_parent/signatures/$rs[0].png")){
						echo"<img src='images/solo_parent/signatures/$rs[0].png' height='35px'/><br/>";
					}else{
						echo"<img src='images/no_signature.png' height='35px'/><br/>";
					}
					
			echo"</div>";
															
			echo"<div style='font-size:14px;font-family:Myriad Pro;text-align:left;position:absolute;left:160px;top:85px;width:300px;height:143px;padding:5px'>
					<div style='padding-top:5px;font-size:22px;color:#000; text-transform:uppercase'><b>".$rs["name_1st"]." ".$rs["name_mid"].". ".$rs["name_fam"]."</b></div>
					<div><i class='fa fa-home' style='width:18px'></i> <b>".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS</b></div>
					<div><i class='fa fa-birthday-cake' style='width:18px'></i> Birthdate : <b>".(!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs["date_birth"])) : "N/A")."</b></div>
					<div><i class='fa fa-venus-mars' style='width:18px'></i> Sex : <b>".$rs["sex"]."</b> &nbsp; Age : <b>".$age." </b> y.o.</div>
					<div><i class='fa fa-registered' style='width:20px'></i> Issued On : <b>".$rs["assoc_reg_month"]."/".$rs["assoc_reg_day"]."/".$rs["assoc_reg_year"]."</b></div>
					<div><i class='fa fa-check' style='width:20px'></i> Valid Until: <b>December 31, "; echo date("Y")+1; echo"</b></div>";	
			echo"</div>";
						
			echo"<div>
					<div style='font-family:Myriad Pro;position:absolute;top:80px;left:520px;text-align:justify;padding-right:px'>	
						<small>THIS IS TO CERTIFY that the individual whose photograph and signature apear on the reverse hereof is a bonafide Social Benificiary as Solo Parent of the Municipality of Tabina.</small>
					</div>
			
					<div style='font-size:12px;font-family:Myriad Pro;position:absolute;top:140px;left:520px;;text-align:justify'>	
					<x style='color:red;font-size:14px;'>Incase of emergency, please notify:</x>
					<div style='font-weight:bold;text-transform:uppercase;font-size:20px'>".$rs["contactperson"]."</div>
					<div>Relationship : &nbsp;".$rs["relationship"]." Contact No : &nbsp;<b>".$rs["emergencyno"]."</b></div>
					<div style='text-transform:titled'>";
						$add=$rs["purok"];
						if(in_array(strtolower($add[0] ?? ''),array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
							echo"".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", Zamboanga del Sur";
						}else{
							echo"".$rs["barangay"].", ".$rs["city_mun"].", Zamboanga del Sur";
						}
				echo"</div>
				</div>
				<div style='position:absolute;top:120px;right:-12px'>";
					if(file_exists("images/solo_parent/qrcodes/$rs[0].png")){
						echo"<div><img src='images/solo_parent/qrcodes/$rs[0].png' height='90' width='90' /></div>";
					}else{
						echo"<div><img src='images/no_qrcode.png' height='65' width='65px' /></div>";
					}
				echo" 
				</div>
				<div style='margin-bottom:-4px;margin-top:-4px'>&nbsp;</div>";
			$ii++;
		}
	}
	echo"</div></div>";
?>

</div> 

<script>
	function printF(){
		$('.t_controls').css("display","none");
	window.print(); 
		$('.t_controls').css("display","block");
	}
</script>
		
</body>

</html>