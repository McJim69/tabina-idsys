<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script> setActive("permit"); </script>
<script> setActive("business"); </script>

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

<div>&nbsp;</div>
<div style="width:100%px;margin:0 auto;position:relative">
<div align='center' style='font-size:18px;font-family:Century Gothic; width:100%' id='div_$rs[0]'>

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
							
			while($ex->fetch_array()){
			
			echo"
						
			<div id='toprint' style='display:none;position:relative;width:1000px'>
				<img src='images/permit_business/bg.jpg' width='1000px' align='center' />";
				
				$eee=$link->query("select * from permit_business l where idn='".$rs["idn"]."'");
				$rsp=mysqli_fetch_array($eee);
					
				$ex=$link->query("select * from permit_business where permit_business.idn='$rs[0]' and permit_business.idn=permit_business.idn ");
				$ii=1;
				while($rsp=mysqli_fetch_array($ex)){
					
				echo"
				<div style='color:red;font-size:22px;position:absolute;left:470px;top:309px;'>
					<span style='text-transform:uppercase'>
						<b>";
						$cont = $rs[0];
						printf("%04d", $cont); echo"-";
						$day = "".$rs["is_day"]."";
						printf("%02d", $day); echo"-";
						$mos = "".$rs["is_month"]."";
						printf("%02d", $mos); echo"-".$rs["is_year"]."
						</b>
					</span>
				</div>
				<div style='font-size:22px;position:absolute;left:715px;top:309px;'>
					<span style='text-transform:uppercase'>
						[X] <b style='color:red'> ".$rs["is_mode"]."</b>
					</span>
				</div>
				<div style='margin-left:100px;margin-right:100px;position:absolute;left:0;right:0;top:350px;'>
					<p style='text-transform:uppercase'>
						<b style='font-size:40px'>".$rs["tradename"]."</b><br>
						<b style='color:red;font-size:22'>Business Name</b>
					</p>

					<p style='text-transform:uppercase'>
						<b style='font-size:30px'>";
						if(in_array(strtolower($add[0] ?? ''),array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
							echo"".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS";
						}else{
							echo"".$rs["barangay"].", ".$rs["city_mun"].", ZDS";
						}
						echo"						
						</b><br>
						<b style='color:red;font-size:22'>Address</b>
					</p>

					<p style='text-transform:uppercase'>
						<b style='font-size:30px'>
						".str_replace($val,"$rep",$rs["name_1st"])."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
						echo" ".str_replace($value,$rep,$rs["name_mid"]).".";
						}						
						echo" ".str_replace($val,"$rep",$rs["name_fam"])."			
						</b><br>
						<b style='color:red;font-size:22'>Name of Applicant</b>						
					</p>

					<p style='text-transform:uppercase'>
						<b style='font-size:30px'>".$rs["activity"]."</b><br>
						<b style='color:red;font-size:22'>Nature of Business</b>						
					</p>

				</div>

				<div style='position:absolute;left:358px;top:855px;'>
					<span>
						<b>".$rs["is_day"]."</b>";
							if ($rs["is_day"] == "01") echo"<sup>st</sup>";
							if ($rs["is_day"] == "02") echo"<sup>nd</sup>";
							if ($rs["is_day"] == "03") echo"<sup>rd</sup>";
							if ($rs["is_day"] == "04") echo"<sup>th</sup>";
							if ($rs["is_day"] == "05") echo"<sup>th</sup>";
							if ($rs["is_day"] == "06") echo"<sup>th</sup>";
							if ($rs["is_day"] == "07") echo"<sup>th</sup>";
							if ($rs["is_day"] == "08") echo"<sup>th</sup>";
							if ($rs["is_day"] == "09") echo"<sup>th</sup>";
							if ($rs["is_day"] == "10") echo"<sup>th</sup>";
							if ($rs["is_day"] == "11") echo"<sup>th</sup>";
							if ($rs["is_day"] == "12") echo"<sup>nd</sup>";
							if ($rs["is_day"] == "13") echo"<sup>th</sup>";
							if ($rs["is_day"] == "14") echo"<sup>th</sup>";
							if ($rs["is_day"] == "15") echo"<sup>th</sup>";
							if ($rs["is_day"] == "16") echo"<sup>th</sup>";
							if ($rs["is_day"] == "17") echo"<sup>th</sup>";
							if ($rs["is_day"] == "18") echo"<sup>th</sup>";
							if ($rs["is_day"] == "19") echo"<sup>th</sup>";
							if ($rs["is_day"] == "20") echo"<sup>th</sup>";
							if ($rs["is_day"] == "21") echo"<sup>st</sup>";
							if ($rs["is_day"] == "22") echo"<sup>nd</sup>";
							if ($rs["is_day"] == "23") echo"<sup>rd</sup>";
							if ($rs["is_day"] == "24") echo"<sup>th</sup>";
							if ($rs["is_day"] == "25") echo"<sup>th</sup>";
							if ($rs["is_day"] == "26") echo"<sup>th</sup>";
							if ($rs["is_day"] == "27") echo"<sup>th</sup>";
							if ($rs["is_day"] == "28") echo"<sup>th</sup>";
							if ($rs["is_day"] == "29") echo"<sup>th</sup>";
							if ($rs["is_day"] == "30") echo"<sup>th</sup>";
							if ($rs["is_day"] == "31") echo"<sup>st</sup>";							
						echo"						
					</span>
				</div>
				<div style='position:absolute;left:470px;top:859px;'>
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
						echo", ".$rs["is_year"]."
						</b>
					</span>
				</div>				
				<div style='position:absolute;left:300px;top:928px;'>
					<span>
						<b>".$rs["isorno"]."</b>
					</span>
				</div>																
				<div style='position:absolute;left:300px;top:950px;'>
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

				<div style='position:absolute;left:300px;top:975px;'>
					<span>
						<b>&#8369;".number_format($rs["oramount"]).".00</b>
					</span>
				</div>																
				<div style='position:absolute;right:5px;top:5px;'>
					<span>
						<b>";
							if(file_exists("images/permit_business/qrcodes/$rs[0].png")){
								echo"<div><img src='images/permit_business/qrcodes/$rs[0].png' /></div>";
							}else{
								echo"<div><img src='images/no_qrcode.png' style='height:120px' /></div>";
							}
						echo"
						</b>
					</span>
				</div>
				<div style='font-family:Haettenschweiler;font-size:185px;position:absolute;left:65px;bottom:120px;opacity:.1'>
					<b>".$rs["is_year"]."</b>
				</div>			
				<div style='position:absolute;left:150px;bottom:200px;opacity:.8'>
					<div style='padding-left:10px;font-size:12px'>BP-";
						$cont = "$rs[0]";
						printf("%04d", $cont); echo"-";
						$day = "".$rs["is_day"]."";
						printf("%02d", $day); echo"-";
						$mos = "".$rs["is_month"]."";
						printf("%02d", $mos); echo"-".$rs["is_year"]."					
					</div>
					<div style='padding-left:10px;font-size:11.5px'>TCP-KPR-RJFD-JCM</div>
				</div>
				<div style='font-size:14;color:red;font-family:Century Gothic;position:absolute;left:330px;bottom:21px'>
					".$rs["is_year"]."
				</div>
			</div>";		

			echo"
			<div class='mother'>
				<div class='child' id='printbut' style='background:#eee;width:400px;height:200px;border-radius:5px;box-shadow:0 2px 5px #333'>
					<div align='right'>
						<a href='permit_business_grid.php' title='Close'><img src='images/close.png' height='25' /></a><br>
					</div>
					<div>
						<b style='text-transform:uppercase;color:#2e4e8e'>".$rs["tradename"]."</b><br>
						<small style='font-size:12px'>";
							$add=$rs["purok"];
							if(in_array(strtolower($add[0] ?? ''),array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
								echo"".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ZDS";
							}else{
								echo"".$rs["barangay"].", ".$rs["city_mun"].", ZDS";
							}
							echo"
						</small><br>
						<small style='font-size:12px'>Control No.: ";
							$cont = $rs[0];
							printf("%04d", $cont); echo"-";
							$day = "".$rs["is_day"]."";
							printf("%02d", $day); echo"-";
							$mos = "".$rs["is_month"]."";
							printf("%02d", $mos); echo"-".$rs["is_year"]."
						</small>
					</div>	
					<div onclick='printF()'><img src='images/print.png' style='height:110px;cursor:pointer'/></div>
				</div>
			</div>";
			
			$ii++;
			}
		}
	}
?>
<br/>

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
