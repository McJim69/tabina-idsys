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
			}else{
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
		$issued_time = !empty($rs['date_issued']) && $rs['date_issued'] !== '0000-00-00' ? strtotime($rs['date_issued']) : time();
		$rs['is_day'] = date('d', $issued_time);
		$rs['is_month'] = date('m', $issued_time);
		$rs['is_year'] = date('Y', $issued_time);
		
		$or_time = !empty($rs['date_or']) && $rs['date_or'] !== '0000-00-00' ? strtotime($rs['date_or']) : time();
		$rs['isorday'] = date('d', $or_time);
		$rs['isormonth'] = date('m', $or_time);
		$rs['isoryear'] = date('Y', $or_time);
				$day = $rs["is_day"];
				$mos = $rs["is_month"];
												
			$ex=$link->query("select * from reg_fishing where reg_fishing.idn='$rs[0]' and reg_fishing.idn=reg_fishing.idn ");
			$ii=1;
							
			while(mysqli_fetch_array($ex)){
			
			echo"			
			<div id='toprint' style='margin-top:50px;display:none;width:1000px;position:relative;font-size:22px;font-family:Arial Narrow'>
				<img src='images/reg_fishing/bg.jpg?".date("h:i:s")."' width='1000px' align='center' />";
				
				$eee=$link->query("select * from reg_fishing l where idn='".$rs["idn"]."'");
				$rsr=mysqli_fetch_array($eee);
					
				$ex=$link->query("select * from reg_fishing where reg_fishing.idn='$rs[0]' and reg_fishing.idn=reg_fishing.idn ");
				$ii=1;
				while($rsr=mysqli_fetch_array($ex)){
					
				echo"
				<div style='font-family:Bookman Old Style;position:absolute;left:320px;top:348px;'>
					<span style='text-transform:uppercase'>
						<b>ZDS-14-";
							$cont = $rs[0];
							printf("%04d", $cont);
						echo"</b>
					</span>
				</div>
				<div style='font-family:Bookman Old Style;position:absolute;left:630px;top:348px;'>
					<span style='text-transform:uppercase'>
						<b>";
							printf("%02d", $day); echo"-";	
							printf("%02d", $mos); echo"-";								
							echo"".$rs["is_year"]."
						</b>
					</span>
				</div>				
				<div style='position:absolute;left:20px;top:425px;'>
					<span style='text-transform:uppercase'>
						<b>";
							if ($rs["regtype"] == "Initial") echo"Initial Registration";
							if ($rs["regtype"] == "New CN") echo"Issuance of New Certificate of Number (CN)";
							if ($rs["regtype"] == "Renew CN") echo"Re-Issuance of Certificate of Number (CN)";							
						echo"</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:490px;'>
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
				<div style='position:absolute;left:507;top:490px;'>
					<span style='text-transform:uppercase'>
						<b>
							".str_replace($value,$rep,$rs["barangay"]).",
							".str_replace($value,$rep,$rs["city_mun"]).",						
							".str_replace($value,$rep,$rs["province"])."						
						</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:555px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["homeport"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:507px;top:555px;'>
					<span style='text-transform:uppercase'>
						<b>F/B ".$rs["tradename"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:622px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["fvtype"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:410px;top:622px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["fvcolor"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:705px;top:622px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["build_hull"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:690px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["builder"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:410px;top:690px;'>
					<span style='text-transform:titled'>
						<b>".$rs["build_place"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:705px;top:690px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["build_year"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:750px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["lenght"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:208px;top:750px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["breadth"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:408px;top:750px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["depth"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:605px;top:750px;'>
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
				<div style='position:absolute;left:805px;top:750px;'>
					<span style='text-transform:uppercase'>
						<b>";
							$nt = $gt * .32;
							$nf = $gt -  $nt;
							echo round($nf,9);
						echo"
						</b>
					</span>
				</div>
				<div style='position:absolute;left:20px;top:810px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["enginemake"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:208px;top:810px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["enginesn"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:408px;top:810px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["enginehp"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:605px;top:810px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["engcylinder"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:805px;top:810px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["engineno"]."</b>
					</span>
				</div>

				<div style='position:absolute;left:20px;top:905px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["former_owner"]."</b>
					</span>
				</div>
				<div style='position:absolute;left:507px;top:905px;'>
					<span style='text-transform:uppercase'>
						<b>".$rs["former_vname"]."</b>
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

			echo"
			<div class='mother'>
			<div class='child' id='printbut' style='background:#eee;width:400px;height:200px;border-radius:5px;box-shadow:0 2px 5px #333'>
				<div align='right'>
					<a href='reg_fishing_grid.php' title='Close'><img src='images/close.png' height='25' /></a><br>
				</div>
				<div>
					<b style='text-transform:uppercase;color:#2e4e8e'>F/B ".$rs["tradename"]."</b><br>
					<small style='font-size:12px'>".$rs["barangay"].", ".$rs["city_mun"].", ".$rs["province"]."</small><br>
					<small style='font-size:12px'>Control No.: ";
						$cont = $rs[0];
						printf("%04d", $cont); echo"-";
						$day = "".$rs["is_day"]."";
						printf("%02d", $day); echo"-";
						$mos = "".$rs["is_month"]."";
						printf("%02d", $mos); echo"-".$rs["is_year"]."
					</small>
				</div>	
				<div onclick='printF()'><img src='images/printmfvr.png' style='height:110px;cursor:pointer'/></div>
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
