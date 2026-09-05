<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script> setActive("permit"); </script>
<script> setActive("operate"); </script>

<style>
	.mother{
		height:850px;
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

<div align='center' style='width:100%;font-size:17px;font-family:Century Gothic'>

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
			if($_GET["permit_operate"]!="")
				$vis=" and idn='".$_GET["permit_operate"]."' ";
																
			$ex=$link->query("select * from permit_operate where idn=idn $vis order by idn limit $from,$to ");
			
			while($rs=mysqli_fetch_array($ex)){
		$issued_time = !empty($rs['date_issued']) && $rs['date_issued'] !== '0000-00-00' ? strtotime($rs['date_issued']) : time();
		$rs['is_day'] = date('d', $issued_time);
		$rs['is_month'] = date('m', $issued_time);
		$rs['is_year'] = date('Y', $issued_time);
		
		$or_time = !empty($rs['date_or']) && $rs['date_or'] !== '0000-00-00' ? strtotime($rs['date_or']) : time();
		$rs['isorday'] = date('d', $or_time);
		$rs['isormonth'] = date('m', $or_time);
		$rs['isoryear'] = date('Y', $or_time);
																	
			$ex=$link->query("select * from permit_operate where permit_operate.idn='$rs[0]' and permit_operate.idn=permit_operate.idn ");
			$ii=1;
							
			while(mysqli_fetch_array($ex)){
					
			echo"
			<div id='toprint' style='display:none;position:relative;width:1000px'>
				<img src='images/permit_operate/bg.jpg' width='1000px' align='center' />";
				
				$eee=$link->query("select * from permit_operate l where idn='".$rs["idn"]."'");
				$rsp=mysqli_fetch_array($eee);
					
				$ex=$link->query("select * from permit_operate where permit_operate.idn='$rs[0]' and permit_operate.idn=permit_operate.idn ");
				$ii=1;
				while($rsp=mysqli_fetch_array($ex)){
					
				echo"

				<div style='font-size:20px;position:absolute;right:80px;top:220px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["is_mode"]."</b>
					</span>
				</div>
				<div style='font-size:35px;position:absolute;left:0;right:0;top:435px;'>
					<span style='text-transform:uppercase'>
						<b>						
						".str_replace($val,"$rep",$rs["name_1st"])."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
						echo" ".str_replace($value,$rep,$rs["name_mid"]).".";
						}						
						echo" ".str_replace($val,"$rep",$rs["name_fam"])."			
						</b>
					</span>
				</div>
				<div style='font-size:30px;position:absolute;left:0;right:0;top:520px;'>
					<span style='text-transform:uppercase'>
						<b>DIST. ".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS</b>
					</span>
				</div>
				<div style='font-size:35px;position:absolute;left:0;right:0;top:715px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["tradename"]."</b>
					</span>
				</div>
				<div style='font-size:24px;position:absolute;left:0;right:0;top:810px;'>
					<span style='text-transform:uppercase'>
						<b>MOTORIZED ".$rs["activity"]."</b>
					</span>
				</div>
				<div style='font-size:20px;position:absolute;left:294px;top:955px;'>
					<span style='text-transform:uppercase'>
						".$rs["is_year"]."
					</span>
				</div>
				<div style='font-family:Bookman Old Style;font-size:14px;position:absolute;left:143px;bottom:252px'>
					<span>
						<b>".$rs["isorno"]."</b>
					</span>
				</div>																
				<div style='font-family:Bookman Old Style;font-size:13px;position:absolute;left:143px;bottom:238px'>
					<span>
						<b>";
							if ($rs["is_month"] == "01") echo"January";
							if ($rs["is_month"] == "02") echo"February";
							if ($rs["is_month"] == "03") echo"March";
							if ($rs["is_month"] == "04") echo"April";
							if ($rs["is_month"] == "05") echo"May";
							if ($rs["is_month"] == "06") echo"June";
							if ($rs["is_month"] == "07") echo"July";
							if ($rs["is_month"] == "08") echo"August";
							if ($rs["is_month"] == "09") echo"September";
							if ($rs["is_month"] == "10") echo"October";
							if ($rs["is_month"] == "11") echo"November";
							if ($rs["is_month"] == "12") echo"December";
						echo" ".$rs["isorday"].", ".$rs["isoryear"]." 
						</b>
					</span>
				</div>																

				<div style='font-family:Bookman Old Style;font-size:13px;position:absolute;left:143px;bottom:220px'>
					<span>
						<b>&#8369; ".number_format($rs["oramount"]).".00</b>
					</span>
				</div>																
				<div style='position:absolute;right:20px;bottom:200px;'>
					<span>
						<b>";
							if(file_exists("images/permit_operate/qrcodes/$rs[0].png")){
								echo"<div><img src='images/permit_operate/qrcodes/$rs[0].png' height='140' width='140' /></div>";
							}else{
								echo"<div><img src='images/no_qrcode.png' height='140' width='140' /></div>";
							}
						echo"
						</b>
					</span>
				</div>
				<div style='font-family:Haettenschweiler;font-size:190px;position:absolute;left:15px;bottom:15px;opacity:.1'>
					<span>
						<b>
							".$rs["is_year"]."						
						</b>
					</span>
				</div>		
				<div style='font-family:Bookman Old Style;position:absolute;left:100px;bottom:100px;opacity:.8'>
					<div style='padding-left:10px;font-size:12px'>PO-";
						$cont = $rs[0];
						printf("%04d", $cont); echo"-";
						$day = "".$rs["is_day"]."";
						printf("%02d", $day); echo"-";
						$mos = "".$rs["is_month"]."";
						printf("%02d", $mos); echo"-".$rs["is_year"]."
					</div>
					<div style='padding-left:10px;font-size:11.5px'>TCP-KPR-RJFD-JCM</div>
				</div>
			</div>";		

			echo"
			<div class='mother'>
				<div class='child' id='printbut' style='background:#eee;width:400px;height:200px;border-radius:5px;box-shadow:0 2px 5px #333'>
					<div align='right'>
						<a href='permit_operate_grid.php' title='Close'><img src='images/close.png' height='25' /></a><br>
					</div>
					<div>
						<b style='text-transform:uppercase;color:#2e4e8e'>".$rs["tradename"]."</b><br>
						<small style='font-size:12px'>".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS</small><br>
						<small style='font-size:12px'>Control No.:";
							$cont = $rs[0];
							printf("%04d", $cont);
							echo"-".$rs["is_day"]."-".$rs["is_month"]."-".$rs["is_year"]."
						</small>
					</div>	
					<div onclick='printF()'><img src='images/print.png' style='height:120px;cursor:pointer'/></div>
				</div>
			</div>";

			$ii++;
			}
		}
	}
?>

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
