<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script> setActive("permit"); </script>
<script> setActive("fishing"); </script>

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

<div style="width:100%px;margin:0 auto;position:relative">
<div align='center' style='font-size:17px;font-family:Bookman Old Style; width:100%' id='div_$rs[0]'>

<?php		
		
	$rec=1;
		$p=$_GET['page'];
			if($p>1){
				$to=$rec;
				$from=($p*$rec)-$rec;
				$i=(($p-1)*$rec)+1;
			}
			else{
				$to=$rec;
				$from=0;
				$i=1;
				$p=1;
			}			
				
			$vis="";
			if($_GET["reg_fishing"]!="")
				$vis=" and idn='".$_GET["reg_fishing"]."' ";
																
			$ex=$link->query("select * from reg_fishing where idn=idn $vis order by idn limit $from,$to ");
			
			while($rs=mysqli_fetch_array($ex)){
												
			$ex=$link->query("select * from reg_fishing where reg_fishing.idn='$rs[0]' and reg_fishing.idn=reg_fishing.idn ");
			
			$ii=1;
			
			include('qrlib/qrlib.php');
				
			while(mysqli_fetch_array($ex)){
	
			echo"			
			<div id='toprint' style='margin-top:50px;display:none;position:relative;width:1000px;font-family:Arial Narrow;font-size:22px'>
				<img src='images/reg_fishing/permit_bg.jpg?".date("h:i:s")."' width='1000px' align='center' />";
				
				$eee=$link->query("select * from reg_fishing l where idn='".$rs["idn"]."'");
				$rsreg=mysqli_fetch_array($eee);
					
				$ex=$link->query("select * from reg_fishing where reg_fishing.idn='$rs[0]' and reg_fishing.idn=reg_fishing.idn ");
				$ii=1;
				while($rsreg=mysqli_fetch_array($ex)){
					
				echo"
				<div style='font-family:Bookman Old Style;position:absolute;left:472px;top:325px;'>
					<span style='text-transform:uppercase'>
						<b style='color:red'>";
						$cont = $rs[0];
						printf("%04d", $cont); echo"-</b><b>";
						$day = "".$rs["is_day"]."";
						printf("%02d", $day); echo"-";
						$mos = "".$rs["is_month"]."";
						printf("%02d", $mos); echo"-".$rs["is_year"]."
						</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:450px;'>
					<span style='text-transform:uppercase'>
						<b>
						".str_replace($val,"$rep",$rs["name_1st"])."";
						if($rs["name_mid"]==""){
							echo" &nbsp; ";
						}else{
						echo" ".str_replace($value,$rep,$rs["name_mid"])."";
						}						
						echo" ".str_replace($val,"$rep",$rs["name_fam"])."			
						</b>
					</span>
				</div>
				<div style='position:absolute;left:507;top:450px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["barangay"].", ".$rs["city_mun"].", ".$rs["province"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:515px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["homeport"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:507px;top:515px;'>
					<span style='text-transform:uppercase'>
						<b>F/B ".$rs["tradename"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:580px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["fvtype"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:208px;top:580px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["fvcolor"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:507px;top:580px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["service_type"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:705px;top:580px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["description"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:640px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["lenght"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:208px;top:640px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["breadth"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:408px;top:640px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["depth"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:605px;top:640px;'>
					<span style='text-transform:uppercase'>
						<b>";
							$tl = $rs["lenght"] ;
							$tb = $rs["breadth"] ;
							$td = $rs["depth"] ;
							$gt = $tl * $tb * $td * 0.70 / 2.83;
						echo round($gt,9);
						echo"
						</b>
					</span>
				</div>
				<div style='position:absolute;left:805px;top:640px;'>
					<span style='text-transform:uppercase'>
						<b>";
							$nt = $gt * .32;
							$nf = $gt -  $nt;
						echo round($nf,9);
						echo"
						</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:696px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["enginemake"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:208px;top:696px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["enginesn"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:408px;top:696px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["enginehp"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:605px;top:696px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["engcylinder"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:805px;top:696px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["engineno"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:755px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["crewno"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:205px;top:755px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["coastgno"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:408px;top:755px;'>
					<span style='text-transform:uppercase'>
						<b>ZDS-14-";
							$cont = $rs[0];
							printf("%04d", $cont);
					echo"</b>
					</span>
				</div>
				<div style='position:absolute;left:605px;top:755px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["gearused"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:825px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["isorno"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:206px;top:825px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["isormonth"]."-".$rs["isorday"]."-".$rs["isoryear"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:407px;top:825px;'>
					<span style='text-transform:uppercase'>
						<b>&#8369;".number_format($rs["oramount"]).".00</b>
					</span>
				</div>
				<div style='position:absolute;right:5px;bottom:20px;'>
					<span>
						<b>";
							if(file_exists("images/reg_fishing/qrcodes/$rs[0].png")){
								echo"<div><img src='images/reg_fishing/qrcodes/$rs[0].png' height='150' width='150' /></div>";
							}else{
								echo"<div><img src='images/no_qrcode.png' height='150' width='150' /></div>";
							}
						echo"
						</b>
					</span>
				</div>
				<div style='font-family:Haettenschweiler;font-size:370px;position:absolute;left:0;right:0;bottom:-20px;opacity:.1'>
					<b>".$rs["is_year"]."</b>
				</div>			
				<div style='font-size:22px;font-family:Bookman Old Style;position:absolute;left:0;right:0;bottom:100px'>
					<b>HON. JUHAINE A. MALACO</b><br>
					Municipal Mayor
				</div>			

				</div>";		

			$idn = $rs[0];
			$data = "".$rs["tradename"]."\nFB-$rs[0]-".$rs["is_day"]."-".$rs["is_month"]."-".$rs["is_year"]."";		
			$tempDir = "images/reg_fishing/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 

			echo"
			<div class='mother'>
				<div class='child' id='printbut' style='background:#eee;width:400px;height:200px;border-radius:5px;box-shadow:0 2px 5px #333'>
					<div align='right'>
						<a href='reg_fishing_grid.php' title='Close'><img src='images/close.png' height='25' /></a><br>
					</div>
					<div>
						<b style='text-transform:uppercase;color:#2e4e8e'>F/B ".$rs["tradename"]."</b><br>
						<small style='font-size:12px'>".$rs["barangay"].", ".$rs["city_mun"].", ZDS</small><br>
						<small style='font-size:12px'>Control No.: ";
							$cont = $rs[0];
							printf("%04d", $cont);
							echo"-".$rs["is_day"]."-".$rs["is_month"]."-".$rs["is_year"]."
						</small>
					</div>	
					<div onclick='printF()'><img src='images/printpermit.png' style='height:110px;cursor:pointer'/></div>
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
