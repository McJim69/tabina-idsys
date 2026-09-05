<style>@media screen and (max-width:720px){.navhid{display:none;}}</style>
<?php
	$get_municipality = isset($_GET["municipality"]) ? $_GET["municipality"] : '';
	$get_barangays = isset($_GET["barangays"]) ? $_GET["barangays"] : '';
	$get_page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
?>
<div class="navhid">
	<?php for($j=1;$j<=mysqli_num_rows($ex1)/$rec+1;$j++) ?>
	<div id="nav" class="d-flex justify-content-center align-items-center flex-wrap" style="margin-top:7px;margin-bottom:-5px">
		<div style='margin:3px;padding:10px' onclick="jump('?municipality=<?php echo htmlspecialchars($get_municipality, ENT_QUOTES); ?>&page=1&value=<?php echo urlencode($value)."&barangays=".htmlspecialchars($get_barangays, ENT_QUOTES); ?>')">&laquo; first</div>
		<div style='margin:3px;padding:10px' onclick="<?php if($get_page>1){echo "jump('?municipality=".htmlspecialchars($get_municipality, ENT_QUOTES)."&page=".($get_page-1)."&value=".urlencode($value)."&barangays=".htmlspecialchars($get_barangays, ENT_QUOTES)."')";} ?>">&laquo; prev</div>
		<div style="margin:3px;padding:10px;background:#fff;color:#888;" >Showing Page: <?php echo $p." of ".number_format($j-1,0);?> Pages &nbsp;&nbsp;(Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records)</div>
		<div style='margin:3px;padding:10px' onclick="<?php if($get_page<$ex1->num_rows/$rec){echo "jump('?municipality=".htmlspecialchars($get_municipality, ENT_QUOTES)."&page=";
		if($get_page<=1)
			echo"2";   
		else
			echo ($get_page+1);
			echo"&value=".urlencode($value)."&barangays=".htmlspecialchars($get_barangays, ENT_QUOTES)."');";} ?>" >&raquo; next
		</div>
		<div style='margin:3px;padding:10px' onclick="jump('?municipality=<?php echo htmlspecialchars($get_municipality, ENT_QUOTES); ?>&page=<?php echo (number_format($ex1->num_rows/$rec,0)); echo"&value=".urlencode($value)."&barangays=".htmlspecialchars($get_barangays, ENT_QUOTES); ?>')">&raquo; last</div>
		<div style='margin:3px'>Page #: 
			<select style='padding:0.15em;' id='s_pn' onchange="jump('?municipality=<?php echo htmlspecialchars($get_municipality, ENT_QUOTES); ?>&page='+getID('s_pn').value+'&value=<?php echo urlencode($value)."&barangays=".htmlspecialchars($get_barangays, ENT_QUOTES); ?>')" >
				<?php
					for($j=1;$j<=$ex1->num_rows/$rec+1;$j++){
					echo "<option ";
					if($get_page==$j)
					echo "selected";
					ECHO" >$j</option>";
					}
				?>
			</select>
		</div>
	</div>
</div>