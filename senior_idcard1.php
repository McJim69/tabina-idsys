<?php
	require("connect.php");
	require("header.php");
	require("menu.php"); 
?>

<script>setActive("senior");</script>
<script>setActive("social");</script>
<script>setActive("seniorcard");</script>

<link href="style/idcard.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-sm btn-outline-primary" type="button" onclick="jump('senior_idcard.php')"><i class="fa fa-sync"></i> Refresh</button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="jump('senior_list.php')"><i class="fa fa-list"></i> List View</button>
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" onclick="jump('senior_grid.php')"><i class="fa fa-th"></i> Card View</button>
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
			if($_GET["senior"]!="")
				$mem=" and idn='".$_GET["senior"]."' ";
																
			$ex=$link->query("select * from senior where idn=idn $mem order by idn limit $from,$to ");
			
			while($rs=mysqli_fetch_array($ex)){

			$ex=$link->query("select * from senior where senior.idn='$rs[0]' and senior.idn=senior.idn ");
			$ii=1;
						
			while(mysqli_fetch_array($ex)){
						
			$age=$rs["age"];
			
			$exsch=$link->query("select * from senior l where l.idn='$rs[0]' and l.idn=l.idn ");
						
						
			while($rs=mysqli_fetch_array($exsch)){
		
			echo "
				<div style='position:relative;width:930px;height:286px;' id='div_$rs[0]'>
					<img src='images/senior/idback/idback.jpg?".date("h.i.s")."' height='286px' align='center'/>
				
				<div style='color:#FFF;font-size:17px;border-radius:3px;text-align:center;width:92px;position:absolute;top:67px;right:482px;z-index:99'>
					<b>"; $aid="".$rs["assoc_id_no"].""; printf("%04d", $aid); 
				echo"</b></div>
			
				<div style='border-radius:4px;position:absolute;left:12px;top:93px;height:150px;width:150px;overflow:hidden'>";
						
				if(file_exists("images/senior/$rs[0].jpg")){
					echo"<img onclick=\"jump('senior_pds.php?senior=$rs[0]')\" src='images/senior/$rs[0].jpg?".date("h.i.s")."'' height='150px' width='150px'/>";
				}else
					echo"<img onclick=\"jump('senior_pds.php?senior=$rs[0]')\" src='images/blank.jpg' height='150px' width='150px'/>";
				
				echo"</div>

				<div style='position:absolute;left:17px;top:246px;height:120px;width:120px;overflow:hidden'>";
				
				if(file_exists("images/senior/signatures/$rs[0].png")){
					echo"<img src='images/senior/signatures/$rs[0].png' height='35px'/><br/>";
				}else{
					echo"<img src='images/no_signature.png' height='35px'/><br/>";
				}
				echo"</div>
					<div style='font-size:14px;font-family:Myriad Pro;text-align:left;position:absolute;left:160px;top:85px;width:300px;height:143px;padding:5px'>
						<div style='padding-top:5px;font-size:20px;color:#000; text-transform:uppercase'><b>".$rs["name_1st"]." ".$rs["name_mid"]." ".$rs["name_fam"]."</b></div>
						<div>
							<i style='width:18px' class='fa fa-home'></i>";
								if($rs["purok"]==""){
									echo" ";
								}else{
									echo"<b> ".$rs["purok"].", ";
								}								
								echo"".$rs["barangay"].", ".$rs["city_mun"].", ZDS
							</b>
						</div>
						<div><i style='width:18px' class='fa fa-birthday-cake'></i> &nbsp;Birthdate : <b>".(!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs["date_birth"])) : "N/A")."</b></div>
						<div><i style='width:18px' class='fa fa-venus-mars'></i> Sex : <b>".$rs["sex"]."</b> &nbsp; Age : <b>".$age." </b> Years Old</div>
						<div><i style='width:18px' class='fa fa-registered'></i> Issued On : <b>".$rs["assoc_reg_date"]."</b></div>
						<div><i style='width:18px' class='fa fa-check'></i> Valid Until: <b>June 30, "; echo date("Y")+3; echo"</b></div>
					</div>	
					<div>
						<div style='font-size:12px;position:absolute;top:230px;right:315px'>
							<img src='images/senior/lumo_sign.png' height='50'/>
						</div>
						<div style='font-size:12px;position:absolute;top:250px;right:290px'>	
							<b>ROMULO V. LUMO</b>
							<div style='margin-top:-5px'>OSCA HEAD</div>
						</div>		
						<div style='position:absolute;top:220px;right:190px'>";
						if(file_exists("images/senior/qrcodes/".$rs["assoc_id_no"].".png")){
							echo"<div><img src='images/senior/qrcodes/".$rs["assoc_id_no"].".png?".date("h.i.s")."' height='65' width='65' /></div>";
						}else{
							echo"<div><img src='images/no_qrcode.png' height='65' width='65px' /></div>";
						}
					echo"
					</div>
					<div style='font-size:12px;position:absolute;top:230px;right:25px'>
						<img src='images/mayor_bader.png' height='50'/>
					</div>
					<div style='font-size:12px;position:absolute;top:250px;right:30px'>
						<b>HON. JUHAINE A. MALACO</b>
						<div style='margin-top:-5px'>Municipal Mayor</div>
					</div>
					<div style='margin-bottom:-4px;margin-top:-4px'>&nbsp;</div>";
				$ii++;
			}
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