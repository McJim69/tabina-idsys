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
	<h2>LIST OF DUPLICATED ENTRY (4Ps)
</div>	

<div class="grid"></div>

<div class="container table-responsive">
<table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
	<thead class="thead-dark text-uppercase font-weight-bold">
		<tr>
			<th width="5%">NO.</th>
			<th width="40%">FULL NAME</th>
			<th width="15%">4Ps ID</th>
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
				SELECT fullname, COUNT(fullname)
				FROM indigents GROUP BY fullname 
				HAVING	COUNT(fullname) > 1 ");
				
			if ($ex->num_rows > 0) {	
			
				while($rs=mysqli_fetch_array($ex)){
					$fullname = $rs["fullname"];
					$qry = "SELECT * FROM indigents WHERE fullname='$fulname' ";
					$res = $link->query($qry);
					$val = $res->fetch_array();		
					$inID = $val["idn"];
					$brgy = $val["barangay"];
					$rema = $val["remarks"];
					
					if($i%2==0)
						echo "<tr class='odd' id='tr_$rs[0]'>";
					else
						echo "<tr class='even' id='tr_$rs[0]'>";
																
						echo"
							<td>$i.</td>	
							<td style='text-align:left;padding-left:10px'><b>".$rs["fullname"]."</b></td>						
							<td>".$inID."</td>
							<td>".$brgy."</td>
							<td>".$rema."</td>";
						
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
	
</body>

<?php include("footer.php");?>

</html>