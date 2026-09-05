<?php
session_start();
	require("connect.php");
	include("header.php");
	include("menu.php");
?>

<script>setActive("forum");</script>
<script>setActive("msgboard");</script>

<div style="margin-top:60px"></div>
<div class="container mt-4 pt-3">
	<!-- Page Header / Header Card -->
	<div class="card shadow-sm border-0 bg-light mb-4">
		<div class="card-body py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center">
			<div class="mb-3 mb-sm-0">
				<a <?php if($_SESSION["user"]==""){ echo "onclick='chatRoom()'"; } else { echo "onclick=\"jump('chat_rooms.php')\""; } ?> title="Group Chat" class="btn btn-outline-primary font-weight-bold px-3 py-2 rounded-pill shadow-xs" style="cursor:pointer;">
					<i class="fas fa-comments mr-2"></i>Group Chat
				</a>
			</div>
			<div class="text-center mb-3 mb-sm-0">
				<a rel="facebox" href="post_message.php" class="btn btn-primary font-weight-bold px-4 rounded-pill shadow-sm" title="Post Message">
					<i class="fas fa-plus mr-2"></i>Post Message Board
				</a>
			</div>
			<div>
				<a <?php if($_SESSION["user"]==""){ echo "onclick='chatRoom()'"; } else { echo "onclick=\"jump('messenger.php')\""; } ?> title="Private Chat" class="btn btn-outline-primary font-weight-bold px-3 py-2 rounded-pill shadow-xs" style="cursor:pointer;">
					<i class="fas fa-comments mr-2"></i>Private Chat
				</a>
			</div>
		</div>
	</div>

	<!-- Messages Section -->
	<div class="row justify-content-center">
		<div class="col-lg-12">
			<?php
			$res = $link->query("SELECT * FROM message_board ORDER BY mbid DESC");
			if (!$res || $res->num_rows == 0) {
				echo '<div class="alert alert-info text-center shadow-xs border-0 font-weight-bold py-4" style="border-radius: 12px;">';
				echo '<i class="fas fa-info-circle fa-2x mb-2 text-primary d-block"></i>There are no messages posted.</div>';
			} else {
				$i = 0;
				while ($row = $res->fetch_assoc()) {
					$sender = htmlspecialchars($row['msgb_from'] ?? '');
					$email = htmlspecialchars($row['msgb_email'] ?? '');
					$attention = htmlspecialchars($row['msgb_attnto'] ?? '');
					
					$bmid = (int)$row['mbid']; // safe cast
					$combined = $row['msgb_content'] ?? '';
					$parts = explode(" ~ ", $combined, 2);
					$subject = htmlspecialchars($parts[0] ?? '');
					$msg = htmlspecialchars($parts[1] ?? '');
					
					$date = date('l d.M.Y h:i A', strtotime($row['msgb_date']));
					
					echo '<div id="div_'.$bmid.'" class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 12px;">';
					echo '  <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3 border-0">';
					echo '    <div class="d-flex align-items-center">';
					echo '      <div class="badge badge-primary mr-3 font-weight-bold rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 28px; height: 28px; font-size: 13px;">' . ($i + 1) . '</div>';
					echo '      <span class="text-secondary small font-weight-bold mr-1"><i class="fas fa-user-circle fa-lg mr-1 text-primary"></i>From:</span>';
					echo '      <span class="font-weight-bold text-dark">' . $sender . '</span>';
					echo '    </div>';
					echo '    <span class="text-muted small"><i class="far fa-clock mr-1"></i>' . $date . '</span>';
					echo '  </div>';
					
					echo '  <div class="card-body p-3">';
					echo '    <div class="mb-2 text-dark" style="font-size: 15px;">';
					echo '      <span class="text-secondary small mr-1"><i class="fas fa-heading mr-1 text-danger"></i>Subject:</span>';
					echo '      <span class="text-success">' . $subject . ' | </span>';
					echo '      <span class="text-secondary small mr-1"><i class="fa fa-bell mr-1 text-danger"></i>Attention:</span>';
					echo '      <span class="text-danger">' . $attention . '</span>';
					echo '    </div>';
					echo '    <div class="mb-3 text-secondary p-3 bg-light rounded" style="font-size: 14px; line-height: 1.6; border-left: 4px solid #17a2b8;">';
					echo '      <div class="small font-weight-bold text-info mb-1"><i class="fas fa-comment-alt mr-1"></i>MESSAGE:</div>';
					echo '      <div class="text-dark">' . nl2br($msg) . '</div>';
					echo '    </div>';
					echo '  </div>';
					
					echo '  <div class="card-footer bg-white border-0 pt-0 pb-3 px-3 d-flex flex-column flex-sm-row justify-content-between align-items-center">';
					echo '    <div class="mb-2 mb-sm-0">';
					echo '      <span class="text-secondary small font-weight-bold mr-1"><i class="fas fa-envelope mr-1 text-info"></i>Email:</span>';
					echo '      <a href="mailto:' . $email . '" class="font-weight-bold text-info small">' . $email . '</a>';
					echo '    </div>';
					echo '    <div>';
					echo '      <a rel="facebox" href="post_message.php?attn=' . urlencode($row['msgb_from']) . '&subject=' . urlencode('Re: ' . $parts[0]) . '" class="btn btn-sm btn-outline-success font-weight-bold px-3 rounded-pill shadow-xs">';
					echo '        <i class="fas fa-reply mr-1"></i>Reply';
					echo '      </a>';
					if($_SESSION["access"]=="Administrator") {
					echo '      <button onclick="deleteRecord(\'message_board\', '.$bmid.', \'div_'.$bmid.'\')" class="btn btn-sm btn-outline-danger font-weight-bold px-3 rounded-pill shadow-xs">';
					echo '        <i class="fas fa-trash mr-1 text-info"></i>Delete';
					echo '      </button>';
					}
					echo '    </div>';
					echo '  </div>';					
					echo '</div>';
					$i++;					
					}
				}
			?>
		</div>
	</div>
</div>

<?php require("crud_functionjs.php");?>

<script type='text/javascript'>
	function chatRoom(){
		if(confirm('It is recommended that you LOGIN before using LiveChat. Click OK to enter as Guest or Cancel to Login.')){
			window.location.href='chat_rooms';		
		}else{
			window.location.href='login.php';
		}
	}
</script>

</body>
</html>