<?php
	require("connect.php");
	require("header.php");
	require("menu.php"); 

	$value=$_GET['value'];
		
		$bar="";
		if($_GET["barangay"]!="All barangays" && $_GET["barangay"]!="")
		$bar=" and l.barangay='".$_GET["barangay"]."'";
				
		if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
		}
		
		$rec=5;
		$p=$_GET['page'];
		if($p>1){
			$to=$p*$rec;
			$from=$to-$rec;
			$i=$to+1-$rec;
		}
		else{
			$to=$rec;
			$from=0;
			$i=1;
			$p=1;
		}
		
		$ex1=$link->query("select * from senior l where 
		   (l.name_fam like'%".$value."%' or
			l.name_1st like'%".$value."%' or
			l.name_mid like'%".$value."%' or
			l.position like'%".$value."%' or
			l.purok like'%".$value."%' or
			l.barangay like'%".$value."%' or
			l.city_mun like'%".$value."%' or
			l.date_birth like'%".$value."%' or
			l.sex like'%".$value."%') $bar ");
					
		$ex=$link->query("select * from senior l where 
		   (l.name_fam like'%".$value."%' or
			l.name_1st like'%".$value."%' or
			l.name_mid like'%".$value."%' or
			l.position like'%".$value."%' or
			l.purok like'%".$value."%' or
			l.barangay like'%".$value."%' or
			l.city_mun like'%".$value."%' or
			l.date_birth like'%".$value."%' or
			l.sex like'%".$value."%') 
		$bar order by name_fam LIMIT $from,$to ");			
	//
?>

<script>setActive("senior");</script>
<script>setActive("social");</script>
<script>setActive("seniorcard");</script>

<link href="style/idcard.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search" ><i class="fa fa-search tpad"></i> <x class='thid'>Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" value="List View" onclick="jump('senior_list.php')"><i class="fa fa-list tpad"></i> <x class='thid'>List View</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" value="Grid View" onclick="jump('senior_grid.php')"><i class="fa fa-th tpad"></i> <x class='thid'>Card View</x></button>
				<select class="swid spad bmargin btn btn-sm btn-outline-dark" onchange="jump('?barangay='+this.value+'&type=<?php echo $_GET["type"]; ?>')" >
					<option>All barangays</option>
					<?php
						$ex=$link->query("select barangay from senior group by barangay order by barangay")or die(mysqli_error($link));
						while($rs=mysqli_fetch_array($ex)){
							echo "<option ";
						if($_GET["barangay"]===$rs[0])
							echo "selected";
							echo" >$rs[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-dark" type="button" onclick="getID('t_search').value='';jump('senior_idcard.php')"><i class="fa fa-sync tpad"></i> <x class='thid'>Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='senior_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Senior</x></button></a>";
				?>	
				<select class="thid swid spad bmargin btn btn-sm btn-outline-primary" onchange="jump('?page='+this.value+'<?php echo "&barangay=".$_GET["barangay"]; ?>')" >
					<option>Page</option>
					<?php
						for($j=1;$j<=mysqli_num_rows($ex1)/$rec+1;$j++){
							echo "<option ";
						if($_GET["page"]==$j)
							echo "selected";	
						echo" >$j</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-secondary" type="button" onclick="printF()"><i class="fa fa-print tpad"></i> <x class='thid'>Print</x></button>						
			</div>				
		</div>
	</div>
</div>

<div class="idmarg"></div>
<div align="center" class="ipad" style="width:100%;margin:0 auto">
<?php
	$value=$_GET['value'];
		
		$bar="";
		if($_GET["barangay"]!="All barangays" && $_GET["barangay"]!="")
		$bar=" and l.barangay='".$_GET["barangay"]."'";
				
		if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
		}
		
		$rec=5;
		$p=$_GET['page'];
		if($p>1){
			$to=$p*$rec;
			$from=$to-$rec;
			$i=$to+1-$rec;
		}
		else{
			$to=$rec;
			$from=0;
			$i=1;
			$p=1;
		}
		
		$ex1=$link->query("select * from senior l where 
		   (l.name_fam like'%".$value."%' or
			l.name_1st like'%".$value."%' or
			l.name_mid like'%".$value."%' or
			l.position like'%".$value."%' or
			l.purok like'%".$value."%' or
			l.barangay like'%".$value."%' or
			l.city_mun like'%".$value."%' or
			l.date_birth like'%".$value."%' or
			l.sex like'%".$value."%') $bar ");
					
		$ex=$link->query("select * from senior l where 
		   (l.name_fam like'%".$value."%' or
			l.name_1st like'%".$value."%' or
			l.name_mid like'%".$value."%' or
			l.position like'%".$value."%' or
			l.purok like'%".$value."%' or
			l.barangay like'%".$value."%' or
			l.city_mun like'%".$value."%' or
			l.date_birth like'%".$value."%' or
			l.sex like'%".$value."%') $bar order by name_fam LIMIT $from,$to ");			

		$value=strtoupper($_POST["t_search"]);
		$rep="<b style='color:#0014d0;background:#ffa0a0'>$value</b>";

		while($rs=mysqli_fetch_array($ex)){
		
		$age=$rs["age"];
		
		$exs=$link->query("select * from senior l where l.idn='$rs[0]' and l.idn=l.idn ");
		$ii=1;
		
		while($rs=mysqli_fetch_array($exs)){
	
		echo "
			<div style='position:relative;width:930px;height:286px;' id='div_$rs[0]'>
				<img src='images/senior/idback/idback.jpg?".date("h.i.s")."' height='286px' align='center'/>
			
			<div style='color:#FFF;font-size:17px;border-radius:3px;text-align:center;width:92px;position:absolute;top:67px;right:482px;z-index:99'>
				<b>";$aid="".$rs["assoc_id_no"].""; printf("%04d", $aid); 
			echo"</b></div>
		
			<div style='border-radius:4px;position:absolute;left:12px;top:93px;height:150px;width:150px;overflow:hidden'>";
					
			if(file_exists("images/senior/$rs[0].jpg")){
				echo"<img onclick=\"jump('senior_pds.php?senior=$rs[0]')\" src='images/senior/$rs[0].jpg?".date("h.i.s")."'' height='150px' width='150px'/>";
			}else
				echo"<img onclick=\"jump('senior_pds.php?senior=$rs[0]')\" src='images/blank.jpg' height='150px' width='150px'/>";
			
			echo"</div>

			<div style='position:absolute;left:17px;top:246px;height:120px;width:120px;overflow:hidden'>";
			
			if(file_exists("images/senior/signatures/$aid.png")){
				echo"<img src='images/senior/signatures/$aid.png' height='35px'/><br/>";
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
					<div><i class='fa fa-birthday-cake' height='18px' width='18px'></i> Birthdate : <b>".(!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs["date_birth"])) : "N/A")."</b></div>
					<div><i class='fa fa-venus-mars' style='width:18px'></i> Sex : <b>".$rs["sex"]."</b> &nbsp; Age : <b>".$age." </b> Years Old</div>
					<div><i class='fa fa-registered' style='width:18px'></i> Issued On : <b>".$rs["assoc_reg_date"]."</b></div>
					<div><i class='fa fa-check' style='width:18px'></i> Valid Until: <b>June 30, "; echo date("Y")+3; echo"</b></div>
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
						echo"<div><img src='images/senior/qrcodes/".$rs["assoc_id_no"].".png' height='65' width='65' /></div>";
					}else{
						echo"<div><img src='images/no_qrcode.png' height='65' width='65px' /></div>";
					}
				echo"</div>
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