<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script> setActive("permit"); </script>
<script> setActive("business"); </script>

<style>
	.mother{
		height:800px;
		position: relative;
	}
	.child{
		top: 50%;
		left: 50%;
		margin: 0; 
		position: absolute;
		transform: translate(-50%, -50%);
	}
</style>

<div>&nbsp;</div>
<div style="width:500px;margin:0 auto;position:relative">
<div align='center' style='font-size:14px;font-family:Century Gothic; width:500px' id='div_$rs[0]'>

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
				
			$vis="";
			if($_GET["permit_business"]!="")
				$vis=" and idn='".$_GET["permit_business"]."' ";
																
			$ex=$link->query("select * from permit_business where idn=idn $vis order by idn limit $from,$to ");
			
			while($rs=mysqli_fetch_array($ex)){
		$issued_time = !empty($rs['date_issued']) && $rs['date_issued'] !== '0000-00-00' ? strtotime($rs['date_issued']) : time();
		$rs['is_day'] = date('d', $issued_time);
		$rs['is_month'] = date('m', $issued_time);
		$rs['is_year'] = date('Y', $issued_time);
		
		$or_time = !empty($rs['date_or']) && $rs['date_or'] !== '0000-00-00' ? strtotime($rs['date_or']) : time();
		$rs['isorday'] = date('d', $or_time);
		$rs['isormonth'] = date('m', $or_time);
		$rs['isoryear'] = date('Y', $or_time);

			$ex=$link->query("select * from permit_business where permit_business.idn='$rs[0]' and permit_business.idn=permit_business.idn ");
			$ii=1;
							
			while(mysqli_fetch_array($ex)){
			
			echo"
			<div id='toprint' style='display:none;position:relative;width:355px'>
				<img src='images/permit_business/sp.jpg' width='355px' align='center'/>";
				
				$eee=$link->query("select * from permit_business l where idn='".$rs["idn"]."'");
				$rsp=mysqli_fetch_array($eee);
					
				$ex=$link->query("select * from permit_business where permit_business.idn='$rs[0]' and permit_business.idn=permit_business.idn ");
				$ii=1;
				while($rsp=mysqli_fetch_array($ex)){
					
				echo"

				<div style='margin-top:-380px'>
					<div style='text-transform:uppercase;font-size:20px;width:330px'>
						<b>".$rs["tradename"]."</b>
					</div>
					<div style='margin-top:-5px;border-top:1px solid #000;color:red;width:330px'>
						Name of Business Establishment
					</div>						
					<div style='margin:-5px'>&nbsp;</div>
					<div style='text-transform:uppercase;font-size:20px;width:330px'>
						<b>".$rs["activity"]."</b>
					</div>
					<div style='margin-top:-5px;color:red;width:330px'>
						Nature of Business
					</div>
					<div style='margin:-5px'>&nbsp;</div>
					<div style='text-transform:uppercase;font-size:16px;width:330px'>
						<b>";
						$add=$rs["purok"];
							if(in_array(strtolower($add[0] ?? ''),array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
								echo"".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS";
							}else{
								echo"".$rs["barangay"].", ".$rs["city_mun"].", ZDS";
							}
						echo"
						</b>
					</div>
					<div style='margin-top:-5px;color:red;width:330px'>
						Address
					</div>
					<div style='margin:-5px'>&nbsp;</div>
					<div style='text-transform:uppercase;font-size:20px;width:330px'>
						<b>
						".str_replace($val,"$rep",$rs["name_1st"])."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
						echo" ".str_replace($value,$rep,$rs["name_mid"]).".";
						}						
						echo" ".str_replace($val,"$rep",$rs["name_fam"])."			
						</b>
					</div>
					<div style='margin-top:-5px;color:red;width:300px'>
						Name of Applicant						
					</div>
				</div>

				<div style='position:absolute;left:5px;bottom:-183px;'>
					<b>";
							if(file_exists("images/permit_business/qrcodes/$rs[0].png")){
								echo"<div><img src='images/permit_business/qrcodes/$rs[0].png' style='width:70px'/></div>";
							}else{
								echo"<div><img src='images/no_qrcode.png' style='width:70px' /></div>";
							}
						echo"
					</b>
					<br>
				</div>
			</div>";		
	
			echo"
			<div class='mother'>
				<div class='child' id='printbut' style='background:#eee;width:400px;height:200px;border-radius:5px;box-shadow:0 2px 5px #333'>
					<div align='right'>
						<a href='permit_business_grid.php' title='Close'><img src='images/close.png?".date("h:i:s")."' height='25' /></a><br>
					</div>
					<div>
						<b style='text-transform:uppercase;color:#2e4e8e'>".$rs["tradename"]."</b><br>
						<small style='font-size:12px'>".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS</small><br>
						<small style='font-size:12px'>Control No.: ";
							$cont = $rs[0];
							printf("%04d", $cont); echo"-";
							$day = "".$rs["is_day"]."";
							printf("%02d", $day); echo"-";
							$mos = "".$rs["is_month"]."";
							printf("%02d", $mos); echo"-".$rs["is_year"]."
						</small>
					</div>	
					<div onclick='printF()'><img src='images/printsp.png' style='height:110px;cursor:pointer'/></div>
				</div>
			</div>";
			$ii++;
			}
		}
	}
?>

</div>

</div>		

<script>
	function printF(){
		getID('toprint').style.display='block';
		$(".mother").css("display","none");				
	window.print();
		getID('toprint').style.display='none';
		$(".mother").css("display","block");				
	}
</script>

</body>

</html>
