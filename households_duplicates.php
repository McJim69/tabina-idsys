<?php
	require("connect.php");
	require("header.php");
	require("menu.php");	
?>

<script> setActive("sap"); </script>
<script> setActive("social"); </script>

<!-- Print Function -->
<script>
	function printF(){
		getID('menu_').style.display='none';
		getID('head').style.display='block';
		$("#tab").css("width","100%");
		$(".t_controls").css("display","none");
		$(".hid").css("display","table-cell");
		$(".spacer").css("display","none");
		$(".footspace").css("display","none");	
		$(".footer").css("display","none");		
		$(".time1").css("display","none");		
		$(".version1").css("display","none");		
		
	window.print();
		getID('menu_').style.display='block';
		getID('head').style.display='none';
		$("#tab").css("width","40%");
		$(".t_controls").css("display","block");
		$(".hid").css("display","none");
		$(".spacer").css("display","block");
		$(".footspace").css("display","block");		
		$(".footer").css("display","block");		
		$(".time1").css("display","block");		
		$(".version1").css("display","block");		
	}
</script>

<div style="text-align:center;display:none" id="head">
	<img src="images/header.png" height="40px"/><br>
	<h2>LIST OF DUPLICATED ENTRY (SAP)
</div>	

<div class="spacer" style="margin-top:100px"></div>

<table id="tab" align="center" style="text-transform:uppercase">
	<tr style="background:gray;font-size:14px;">
		<th width="5%">NO.</th>
		<th width="40%">FULL NAME</th>
		<th width="15%">SAP ID</th>
		<th width="20%">BARANGAY</th>
		<th width="20%">REMARKS</th>
	</tr>

	<?php
		$rec=200;
		$p=$_GET['page'];
		if($p>1){
			$to=$p*$rec;
			$from=$to-$rec;
			$i=$to+1-$rec;
		}else{
			$to=$rec;
			$from=0;
			$i=1;
			$p=1;
		}
		
		$ex=$link->query("
			SELECT 
				name_fam, COUNT(name_fam),
				name_1st, COUNT(name_1st),
				name_mid, COUNT(name_mid)

			FROM households GROUP BY 
				name_fam , 
				name_1st , 
				name_mid 
				
			HAVING	COUNT(name_fam) > 1
				AND COUNT(name_1st) > 1
				AND COUNT(name_mid) > 1 ");
			
		if ($ex->num_rows > 0) {	
		
			while($rs=mysqli_fetch_array($ex)){

				$fname = $rs["name_1st"];
				$mname = $rs["name_mid"];
				$lname = $rs["name_fam"];

				$qry = "SELECT * FROM households WHERE name_1st='$fname' AND name_mid='$mname' AND name_fam='$lname' ";
				$res = $link->query($qry);
				$val = $res->fetch_array();
				
				$sbID = $val["idn"];
				$brgy = $val["barangay"];
				$rems = $val["remarks"];
				
				if($i%2==0)
					echo "<tr class='odd' id='tr_$rs[0]'>";
				else
					echo "<tr class='even' id='tr_$rs[0]'>";
															
					echo"
						<td class='tdcont'>$i.</td>	
						<td class='tdcont' style='text-align:left;padding-left:10px'>
						 <b>".$rs["name_fam"]."</b>, 
							".$rs["name_1st"]." ";
				
							if($rs["name_ext"]==""){
								echo"";
							}else{
								echo" ".$rs["name_ext"]." ";
							}											
								echo" ".$rs["name_mid"]."
						</td>						
						<td class='tdcont'>".$sbID."</td>
						<td class='tdcont'>".$brgy."</td>
						<td class='tdcont'>".$rems."</td>";
					
					echo"</tr>";	
				$i++;
			}
			
			$printD="<img class='hid image' onclick='javascript:history.back()' src='images/back1.png' height='25' title='Back to List'/> &nbsp; 
					 <img class='hid image' onclick='printF()' src='images/print_clean.png' height='25'/>";

		}else{

			$noData="<img src='images/no_duplicates.png' onclick='javascript:history.back()' height='25' class='image' title='Back to List'/>";
		}
	?>
</table>

<div align="center" id="t_controls" class="t_controls">
	<div>
		<?php echo $printD;?>
		<?php echo $noData;?>
	</div> 
</div>

<div class="footspace"><br><br><br></div>
	
</body>

<?php include("footer.php");?>

</html>