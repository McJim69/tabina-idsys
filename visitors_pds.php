<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

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

<div style="width:100%px;margin:0 auto;position:relative;">
	<div align='center' style='font-size:18px;font-family:Bookman Old Style; width:100%;' id='div_$rs[0]'>

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
		if($_GET["visitors"]!="")
			$vis=" and idn='".$_GET["visitors"]."' ";
															
		$ex=$link->query("select * from visitors where idn=idn $vis order by idn limit $from,$to ");

		include('qrlib/qrlib.php');
		
		while($rs=mysqli_fetch_array($ex)){
											
		$ex=$link->query("select * from visitors where visitors.idn='$rs[0]' and visitors.idn=visitors.idn ");
		$ii=1;

		$spin=str_pad($rs[0], 5, '0', STR_PAD_LEFT); 	
			
		while(mysqli_fetch_array($ex)){

		echo"
			
			<div id='toprint' style='display:none;position:relative'>
				<img src='images/visitors/bg.jpg?".date("h:i:s")."' width='1000px' align='center' />";
				
				$eee=$link->query("select * from visitors l where idn='".$rs["idn"]."'");
				$rsvisitors=$eee->fetch_array();
					
				$ex=$link->query("select * from visitors where visitors.idn='$rs[0]' and visitors.idn=visitors.idn ");
				$ii=1;
				while($ex->fetch_array()){
					
				echo"
				<div style='width:750px;text-align:justify;margin-top:-900px;'>
					<p>TO WHOM IT MAY CONCERN:</p>
					
					<p><img src='images/indent.png'/>This Certificate of Apperance is issued as evidence of the official transaction with the Local Government Unit of Tabina and the personal presence of: 
					</p>
					
					<table width='100%' style='align:justify;margin-left:50px;font-size:18px'>
						<tr>
							<td align='left' width='130px'>Name</td>
							<td align='left' width='10px'>:</td>
							<td  align='left' style='text-transform:uppercase'><b>".$rs["name_1st"].""; 
								if($rs["name_mid"]==""){
									echo" ";
								}else{
									echo" ".substr($rs["name_mid"],0).".";
								}
								echo" ".$rs["name_fam"]."</b>
							</td>
						</tr>
						<tr>
							<td align='left' width='130px'>Designation</td>
							<td align='left' width='10px'>:</td>
							<td align='left' style='border-bottom:1px solid #545454'>".$rs["position"]."</td>
						</tr>
						<tr>
							<td align='left' width='130px'>Office</td>
							<td align='left' width='10px'>:</td>
							<td align='left' style='border-bottom:1px solid #545454'>".$rs["office"]."</td>
						</tr>
						<tr>
							<td align='left' width='130px'>Address</td>
							<td align='left' width='10px'>:</td>
							<td align='left' style='border-bottom:1px solid #545454'>".$rs["address"]."</td>
						</tr>
						<tr>
							<td align='left' width='130px'>Inclusive Date</td>
							<td align='left' width='10px'>:</td>
							<td align='left' style='border-bottom:1px solid #545454'>";
								if ($rs["visit_month"] == "01") echo"January";
								if ($rs["visit_month"] == "02") echo"February";
								if ($rs["visit_month"] == "03") echo"March";
								if ($rs["visit_month"] == "04") echo"April";
								if ($rs["visit_month"] == "05") echo"May";
								if ($rs["visit_month"] == "06") echo"June";
								if ($rs["visit_month"] == "07") echo"July";
								if ($rs["visit_month"] == "08") echo"August";
								if ($rs["visit_month"] == "09") echo"September";
								if ($rs["visit_month"] == "10") echo"October";
								if ($rs["visit_month"] == "11") echo"November";
								if ($rs["visit_month"] == "12") echo"December";
								
								echo"&nbsp;";
								if ($rs["visit_day_from"]!== $rs["visit_day_to"]){ 
									echo"".$rs["visit_day_from"]."-".$rs["visit_day_to"].", ".$rs["visit_year"]."";
								}else{
									echo"".$rs["visit_day_from"]." ".$rs["visit_year"]."";
								}
							echo"
							</td>
						</tr>
						<tr>
							<td align='left' width='130px'>Purpose</td>
							<td align='left' width='10px'>:</td>
							<td align='left' style='border-bottom:1px solid #545454'>".$rs["visit_purpose"]."</td>
						</tr>
					</table>
					<p><img src='images/indent.png'/>Issued on ";
						echo date("F d, Y");
					echo" pursuant to R.A. 3847 in relation to COA Circular No. 127.					
					</p>

				<div style='padding:45px'>&nbsp;</div>					
			
				<div style='padding:70px'>&nbsp;</div>

				<div align='left'>
					<div align='left' ";
						if(file_exists("images/visitors/qrcodes/$rs[0].png")){
							echo"<div><img src='images/visitors/qrcodes/$rs[0].png' style='height:120px' /></div>";
						}else{
							echo"<div><img src='images/no_qrcode.png' style='height:120px' /></div>";
						}
					echo"</div>
					<div style='font-size:12px;opacity:.5'>";
						echo"<div>CA-";					
						$cont = $rs[0];
						printf("%04d", $cont); echo"-";					
						echo date("m-d-Y");
						echo"<br>
						<b>AAD-KPR-RJFD-JAI</b>
					</div>
					
					</div>
					
					</div>
					
				</div>
				
			</div>";	
			
			$data = "".$rs["name_1st"]." ".$rs["name_mid"].". ".$rs["name_fam"]."\n".$rs["position"]."\n".$rs["office"]."\nCA Number: ".$spin."";		
			$tempDir = "images/visitors/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$rs[0].".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						

			echo"
			<div class='mother'>
				<div class='child text-center' id='printbut' style='background:#fff;width:400px;height:200px;border-radius:5px;box-shadow:0 2px 5px #333'>
					<div align='right'>
						<a href='visitors_grid.php' title='Close'><img src='images/close.png' height='25' /></a><br>
					</div>
					<div>
						<b style='text-transform:uppercase;color:#2e4e8e'>
							".str_replace($val,"$rep",$rs["name_1st"])."";
							if($rs["name_mid"]==""){
								echo" ";
							}else{
								echo" ".substr($rs["name_mid"],0).".";
							}						
							echo" ".str_replace($val,"$rep",$rs["name_fam"])."
						</b><br>
						<small style='font-size:12px'>".$rs["position"]."</small><br>
						<small style='font-size:12px'>CA-";
							$cont = $rs[0];
							printf("%04d", $cont); echo"-";					
							echo date("m-d-Y");
							echo"
						</small>
					</div>	
					<div onclick='printF()'><img src='images/print.png' style='height:110px;cursor:pointer'/></div>
				</div> 
			</div>
			";

			$ii++;

			}
		}
	}
?>
<br/><br/>

</div>

</div>		

<script>
	function printF(){
		getID('toprint').style.display='block';						
	window.print();
		getID('toprint').style.display='none';
	}
</script>

</body>

</html>
