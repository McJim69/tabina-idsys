<!--FooterNAV-->
<?php
	// Get all current query parameters
	$params = $_GET;
	$get_page = isset($params["page"]) ? intval($params["page"]) : 1;

	// Helper to generate the URL for a specific page preserving all other GET parameters
	$get_page_url = function($page_num) use ($params) {
		$new_params = $params;
		$new_params["page"] = $page_num;
		return "?" . http_build_query($new_params);
	};

	// For the dropdown select, we need a base query string without the page parameter
	$base_params = $params;
	unset($base_params["page"]);
	$base_query = http_build_query($base_params);
	if ($base_query !== "") {
		$base_query .= "&";
	}
?>
<div style="margin-top:50px;"></div>
	<div style="width:100%;position:fixed;left:0;bottom:0;height:50px;background:#bbb;z-index:666">
		<div style="padding:1px;text-align:center;margin-top:5px" >
			<table style="margin:0 auto;">
				<tr style="background:transparent" >
					<td><input style="height:35px" type=image value="Previous" src="images/prev.png" onclick="jump('<?php echo htmlspecialchars($get_page_url(max(1, $get_page - 1)), ENT_QUOTES); ?>')" /></td>
					<td>
						<select style='height:30px;padding:1px;text-align:center' id='s_pn' onchange="jump('?<?php echo htmlspecialchars($base_query, ENT_QUOTES); ?>page='+this.value)" >
							<option>Page</option>
							<?php
								$total_pages = 1;
								if (isset($ex1) && isset($rec) && $rec > 0) {
									$total_pages = ceil(mysqli_num_rows($ex1) / $rec);
									if ($total_pages < 1) $total_pages = 1;
								}
								for($j=1;$j<=$total_pages;$j++){
									echo "<option ";
									if($get_page==$j) {
										echo "selected";
									}
									echo " >$j</option>";
								}
							?>
						</select>
					</td>
					<td><input style="height:35px" type=image value="Next" src="images/next.png" onclick="jump('<?php echo htmlspecialchars($get_page_url($get_page + 1), ENT_QUOTES); ?>')" /></td>
				</tr>
			</table>
		</div>
	</div>
<!--EO FooterNAV-->
