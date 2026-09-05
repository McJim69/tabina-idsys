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
		getID('head').style.display='block';
		$(".t_controls").css("display","none");
		$(".grid").css("display","none");
	window.print();
		getID('head').style.display='none';
		$(".t_controls").css("display","block");
		$(".grid").css("display","block");
	}
</script>

<div style="text-align:center;display:none;margin-top:50px" id="head">
	<img src="images/header.png" height="40px"/><br>
	<h2>LIST OF DUPLICATED ENTRY (SAP)
</div>	

<div class="grid">

<div class="container table-responsive">
<table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
	<thead class="thead-dark text-uppercase font-weight-bold">
		<tr>
			<th width="5%">NO.</th>
			<th width="40%">FULL NAME</th>
			<th width="15%">SAP ID</th>
			<th width="20%">BARANGAY</th>
			<th width="20%">REMARKS</th>
		</tr>
	</thead>
	<tbody>
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

				FROM sap_ben GROUP BY 
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

					$qry = "SELECT * FROM sap_ben WHERE name_1st='$fname' AND name_mid='$mname' AND name_fam='$lname' ";
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
							<td>$i.</td>	
							<td style='text-align:left;padding-left:10px'>
							 <b>".$rs["name_fam"]."</b>, 
								".$rs["name_1st"]." ";
					
								if($rs["name_ext"]==""){
									echo"";
								}else{
									echo" ".$rs["name_ext"]." ";
								}											
									echo" ".$rs["name_mid"]."
							</td>						
							<td>".$sbID."</td>
							<td>".$brgy."</td>
							<td>".$rems."</td>";
						
						echo"</tr>";	
					$i++;
					}
					
					$printD="<button class='btn btn-outline-dark rounded-pill hid' onclick='javascript:history.back()'><i class='fa fa-backward'></i> BACK</button>
						 <button class='btn btn-outline-dark rounded-pill hid' onclick='printF()'><i class='fa fa-print'></i> PRINT</button>";
				}else{
					$noData="<button class='btn btn-outline-dark rounded-pill hid' onclick='javascript:history.back()'><i class='fa fa-backward'></i> NO DUPLICATE FOUND</button>";
				}
			?>
		</tbody>
		</tfoot>
	</table>
</div>

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<?php echo $printD;?>
				<?php echo $noData;?>
			</div>
		</div>
	</div> 
</div>

<div class="footspace"><br><br><br></div>
	
</body>

<?php include("footer.php");?>

</html>