<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script>setActive("certclear");</script>
<script>setActive("cert");</script>

<div class="container">
	<div class="row d-flex justify-content-center align-items-center flex-wrap">
		<div class="col-lg-7">
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
				
			$cert="";
			if($_GET["cert_indigency"]!="")
				$cert=" and idn='".$_GET["cert_indigency"]."' ";
																
			$ex = $link->query("select * from cert_indigency where idn=idn $cert order by idn limit $from,$to ");
			
			while($rs=mysqli_fetch_array($ex)){
		$issued_time = !empty($rs['date_issued']) && $rs['date_issued'] !== '0000-00-00' ? strtotime($rs['date_issued']) : time();
		$rs['is_day'] = date('d', $issued_time);
		$rs['is_month'] = date('m', $issued_time);
		$rs['is_year'] = date('Y', $issued_time);
												
			$ex = $link->query("select * from cert_indigency where cert_indigency.idn='".$rs[0]."' and cert_indigency.idn=cert_indigency.idn ");
			
			$i=1;
			
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
	
			echo"<div id='hidprint' style='margin-top:53px;position:relative;width:625px;'>";
				
			include("print_close.php");
			
			echo"
				<img src='images/cert_indigency/bg.jpg' width='625px' class='image4'>
			
				<div id='hidprint' style='font-size:14px;padding:70px;position:absolute; top:100px'>
					<br><br><br><br><br>
					<p align='center' style='font-size:18px'>
						<b>CERTIFICATE OF INDIGENCY</b>
					</p><br>
					
					<p style='text-align:left'>TO WHOM IT MAY CONCERN:</p>
					
					<p style='text-align:justify'><img src='images/indent.png'/>THIS IS TO CERTIFY that 
						<b style='text-transform:uppercase'>
							".$rs["name_1st"]."";
							if($rs["name_mid"]==""){
								echo" ";
							}else{
								echo" ".substr($rs["name_mid"],0)." ";
							}
							echo" ".$rs["name_fam"].",
						</b>";
						
						if ($age > 18){ 
							echo"of legal age, ";
						}else{
							echo"$age years old, ";
						}
					echo"".$rs["status"].",
						and a resident of ".$rs["address"].", ".$rs["barangay"].", ".$rs["city_mun"].", ".$rs["province"]." is an <i><b>indigent</b></i>.
					</p>

					<p style='text-align:justify'><img src='images/indent.png'/>Issued this ".$rs["is_day"]."";
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
						echo" day of ";
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
						echo", ".$rs["is_year"]." for all legal intents and purposes.
					</p>
					<div><br><br><br><br><br><br><br><br><br><br>	
						<div style='margin-left:-10px'>
							<div align='left'>";
								if(file_exists("images/cert_indigency/qrcodes/".$rs[0].".png")){
									echo"<img src='images/cert_indigency/qrcodes/".$rs[0].".png' style='height:100px' /> ";
								}else{
									echo"<img src='images/no_qrcode.png' style='height:100px' />";
								}
							  echo"
							</div>
						</div>
						<div style='text-align:left;font-size:10px;opacity:.5'>						
							<div>COI-";
								$cont = "".$rs[0]."";
								printf("%04d", $cont); echo"-";
								$day = "".$rs["is_day"]."";
								printf("%02d", $day); echo"-";
								$mos = "".$rs["is_month"]."";
								printf("%02d", $mos); echo"-".$rs["is_year"]."		
							</div>	
						</div>
						<div style='position:absolute;bottom:130px;right:50px;'>
							<img src='images/mayor_bader.png' height='80'>
						</div>
						<div style='position:absolute;bottom:130px;right:50px;'>
							<b>HON. JUHAINE ''BADER'' A. MALACO</b><br>
							Municipal Mayor
						</div>
					</div>
				</div>
			</div>
		";

		//TOPRINT	
		echo"
			<div id='toprint' align='center' style='margin-top:50px;display:none;position:relative'> 
		
				<img src='images/cert_indigency/bg.jpg?".date("h:i:s")."' width='800px' align='center' />";
		
			echo"
				<div id='toprint' style='width:700px;text-align:justify;margin-top:-800px;'>
				
					<p align='center' style='font-size:25px'>
						<b>CERTIFICATE OF INDIGENCY</b>
					</p><br><br>
					
					<p>TO WHOM IT MAY CONCERN:</p>
					
					<p><img src='images/indent.png'/>THIS IS TO CERTIFY that 
						<b style='text-transform:uppercase'>
							".$rs["name_1st"]."";
							if($rs["name_mid"]==""){
								echo" ";
							}else{
								echo" ".substr($rs["name_mid"],0)." ";
							}
							echo" ".$rs["name_fam"].",
						</b>";
						
						if ($age > 18){ 
							echo"of legal age, ";
						}else{
							echo"$age years old, ";
						}
							echo"".$rs["status"].",
						and a resident of ".$rs["address"].", ".$rs["barangay"].", ".$rs["city_mun"].", ".$rs["province"]." is an <i><b>indigent</b></i>.
					</p>

					<p><img src='images/indent.png'/>Issued this ".$rs["is_day"]."";
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
						echo" day of ";
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
						echo", ".$rs["is_year"]." for all legal intents and purposes.
					</p>
					<div><br><br><br><br><br><br><br><br><br>
						<div style='margin-left:-10px'>
							<div align='left'>";
								if(file_exists("images/cert_indigency/qrcodes/".$rs[0].".png")){
									echo"<img src='images/cert_indigency/qrcodes/".$rs[0].".png' style='height:100px' /> ";
								}else{
									echo"<img src='images/no_qrcode.png' style='height:100px' />";
								}
							  echo"
							</div>
						</div>			
						<div style='text-align:left;font-size:10px;opacity:.5'>COI-";
							$cont = "".$rs[0]."";
							printf("%04d", $cont); echo"-";
							$day = "".$rs["is_day"]."";
							printf("%02d", $day); echo"-";
							$mos = "".$rs["is_month"]."";
							printf("%02d", $mos); echo"-".$rs["is_year"]."		
						</div>	
						<div style='position:absolute;bottom:30px;right:180px;'>
							<img src='images/mayor_bader.png' height='100'>
						</div>
						<div align='center' style='font-size:18px;font-family:Bookman Old Style;position:absolute;bottom:30px;right:150px;'>
							<b>HON. JUHAINE ''BADER'' A. MALACO</b><br>
							Municipal Mayor							
						</div>
					</div>
				</div>
			</div>";
		}
	}
?>
</div>
</div>
</div>

<script>
	function printF(){
		getID('toprint').style.display='block';
		getID('hidprint').style.display='none';
		
	window.print();
		getID('toprint').style.display='none';
		getID('hidprint').style.display='block';
	}
</script>

</div>

</body>

</html>






