<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script> setActive("certclear"); </script>
<script> setActive("clear"); </script>

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
				
		$vis="";
		if($_GET["clearances"]!="")
			$vis=" and idn='".$_GET["clearances"]."' ";
															
		$ex=$link->query("select * from clearances where idn=idn $vis order by idn limit $from,$to ");
		
		while($rs=mysqli_fetch_array($ex)){
		$issued_time = !empty($rs['date_issued']) && $rs['date_issued'] !== '0000-00-00' ? strtotime($rs['date_issued']) : time();
		$rs['is_day'] = date('d', $issued_time);
		$rs['is_month'] = date('m', $issued_time);
		$rs['is_year'] = date('Y', $issued_time);
															
		$ex=$link->query("select * from clearances where clearances.idn='$rs[0]' and clearances.idn=clearances.idn ");
			
		while(mysqli_fetch_array($ex)){

			echo"<div id='hid' style='margin-top:53px;position:relative;width:625px;'>";
				
			include("print_close.php");
			
			echo"
			<img src='images/clearances/bg.jpg' width='625px' class='image4'>
			
			<div style='font-size:14px;padding:70px;position:absolute; top:250px'>
					
				<p style='text-align:justify'><b>TO WHOM IT MAY CONCERN</b>:</p>
					
				<p style='text-align:justify'><img src='images/indent.png'/>THIS IS TO CERTIFY that 
					<b style='text-transform:uppercase'>".$rs["name_1st"]."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
							}else{
								echo" ".$rs["name_mid"].".";
							}
							echo" ".$rs["name_fam"].",
						</b> of legal age and ".$rs["civil_status"].",
					is a resident of ".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ".$rs["province"].".
				</p>
						
				<p style='text-align:justify'><img src='images/indent.png'/>THIS IS TO CERTIFY FRURTHER that 
					<b style='text-transform:uppercase'>".$rs["name_1st"]."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
							echo" ".$rs["name_mid"].".";
						}
						echo" ".$rs["name_fam"]."
					</b> has no derogatory records in this office.
				</p>
						
				<p style='text-align:justify'><img src='images/indent.png'/>THIS IS TO CERTIFY FINALLY that 
					<b style='text-transform:uppercase'>".$rs["name_1st"]."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
							echo" ".$rs["name_mid"].".";
						}
						echo" ".$rs["name_fam"]."
					</b> is a person of good moral character.
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
				<div>
					<div align='left' width='100px'>
						<table>
							<td align='lef' width='80px'>O.R. Nunber</td>
							<td width='10px'>:</td>
							<td align='lef' width='150px'><b>".$rs["isorno"]."</b></td>
						</table>

						<table>
							<td align='lef' width='80px'>Date Issued</td>
							<td width='10px'>:</td>
							<td align='lef' width='150px'>
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
								echo" ".$rs["is_day"].", ".$rs["is_year"]."
							</b>
							</td>
						</table>
							
						<table>
							<td align='lef' width='80px'>Amount Paid</td>
							<td width='10px'>:</td>
							<td align='lef' width='150px'><b>&#8369;".$rs["oramount"].".00</b></td>
						</table>
					</div>
						
					<div align='left'>
						<div align='left' style='padding-left:-15px'>";
							if(file_exists("images/clearances/qrcodes/$rs[0].png")){
								echo"<img src='images/clearances/qrcodes/$rs[0].png' style='height:80px' />";
							}else{
								echo"<img src='images/no_qrcode.png' style='height:80px' />";
							}
							echo"
						</div>
						<div align='left' style='font-size:10px;opacity:.5'>
							<div>MC-";
								$cont = $rs[0];
								printf("%04d", $cont); echo"-";
								$day = "".$rs["is_day"]."";
								printf("%02d", $day); echo"-";
								$mos = "".$rs["is_month"]."";
								printf("%02d", $mos); echo"-".$rs["is_year"]."					
								<br>
								<b>AAD-KPR-RJFD-JAI</b>
							</div>
						</div>
						<div style='position:absolute;bottom:100px;right:100px;'>
							<img src='images/mayor_bader.png' height='80'>
						</div>
						<div align='center' style='position:absolute;bottom:100px;right:80px;'>
							<b>HON. JUHAINE ''BADER'' A. MALACO</b><br>
							Municipal Mayor							
						</div>						
					</div>			
				</div>
			</div>
		</div> ";	
	
	//TOPRINT
		echo"	
		<div id='toprint' align='center' style='margin-top:80px;display:none;position:relative'>
			<img src='images/clearances/bg.jpg' width='800px' align='center' />
								
			<div style='width:700px;text-align:justify;margin-top:-800px;'>
				<p>TO WHOM IT MAY CONCERN:</p>
				
				<p><img src='images/indent.png'/>THIS IS TO CERTIFY that 
					<b style='text-transform:uppercase'>
						".$rs["name_1st"]."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
							echo" ".$rs["name_mid"].".";
						}
						echo" ".$rs["name_fam"].",
					</b> 
					of legal age and ".$rs["civil_status"].",
					is a resident of ".$rs["purok"].", ".$rs["barangay"].", ".$rs["city_mun"].", ".$rs["province"].".
				</p>
					
				<p><img src='images/indent.png'/>THIS IS TO CERTIFY FRURTHER that 
					<b style='text-transform:uppercase'>
						".$rs["name_1st"]."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
							echo" ".$rs["name_mid"].".";
						}
						echo" ".$rs["name_fam"]."
					</b> has no derogatory records in this office.
				</p>
					
				<p><img src='images/indent.png'/>THIS IS TO CERTIFY FINALLY that 
					<b style='text-transform:uppercase'>
						".$rs["name_1st"]."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
							echo" ".$rs["name_mid"].".";
						}
						echo" ".$rs["name_fam"]."
					</b> is a person of good moral character.
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
				
				<div><div >&nbsp;</div>					
					<table style='font-size:14px'>
						<td align='lef' width='100px'>O.R. Nunber</td>
						<td width='10px'>:</td>
						<td align='lef' width='180px'><b>".$rs["isorno"]."</b></td>
					</table>
					<table style='font-size:14px'>
						<td align='lef' width='100px'>Date Issued</td>
						<td width='10px'>:</td>
						<td align='lef' width='180px'>
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
							echo" ".$rs["is_day"].", ".$rs["is_year"]."
						</b>
						</td>
					</table>
					<table style='font-size:14px'>
						<td align='lef' width='100px'>Amount Paid</td>
						<td width='10px'>:</td>
						<td align='lef' width='180px'><b>&#8369;".$rs["oramount"].".00</b></td>
					</table>
					
					<div align='left'>
						<div align='left'>";
							if(file_exists("images/clearances/qrcodes/$rs[0].png")){
								echo"<img src='images/clearances/qrcodes/$rs[0].png' style='height:120px' />";
							}else{
								echo"<img src='images/no_qrcode.png' style='height:120px' />";
							}
							echo"
						</div>
						<div style='font-size:12px;opacity:.5'>
							<div>MC-";
								$cont = $rs[0];
								printf("%04d", $cont); echo"-";
								$day = "".$rs["is_day"]."";
								printf("%02d", $day); echo"-";
								$mos = "".$rs["is_month"]."";
								printf("%02d", $mos); echo"-".$rs["is_year"]."					
							</div>
						</div>
						<div style='position:absolute;bottom:10px;right:180px;'>
							<img src='images/mayor_bader.png' height='100'>
						</div>
						<div align='center' style='position:absolute;bottom:0px;right:150px;'>
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
		getID('hid').style.display='none';
						
	window.print();
		getID('toprint').style.display='none';
		getID('hid').style.display='block';
	}
</script>

</body>

</html>
