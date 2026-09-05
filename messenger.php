<?php
require("connect.php");

$me = $_SESSION['user'];
$me_esc = mysqli_real_escape_string($link, $me);

$emoticonArray = array(
	'Aa@' => '😊', 'Bb#' => '😘', 'Cc$' => '😡', 'Dd'  => '😑', 'Ee*' => '😊',
	'Ff(' => '😁', 'Gg)' => '😎', 'Hh+' => '😵', 'Ii-' => '😐', 'Jj:' => '😆',
	'Kk;' => '😍', 'Ll?' => '😢', 'Mm1' => '😲', 'Nn2' => '🤢', 'Oo3' => '😒',
	'Pp4' => '😜', 'Qq5' => '😛', 'Rr6' => '👍', 'Ss7' => '😟', 'Tt8' => '😮',
	'Uu9' => '🤔', 'Vv0' => '😗', 'Ww=' => '😉', 'Xx.' => '🤏', 'Yy?' => '❓',
	'Zz!' => '❗'
);

// Helper function to format message attachment and text
function formatMessageBody($message, $emoticonArray, $isSelf = false) {
	$msgBody = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
	foreach ($emoticonArray as $code => $location) {
		$msgBody = str_replace($code, $location, $msgBody);
	}
	
	$trimmedMsg = trim($message);
	if (preg_match('/^\[CALL:(.+?)\|(.+?)\]$/', $trimmedMsg, $matches)) {
		$callType = htmlspecialchars($matches[1]);
		$callRoom = htmlspecialchars($matches[2]);
		$icon = ($callType === 'video') ? 'fa-video' : 'fa-phone';
		$title = ($callType === 'video') ? 'Video Call' : 'Audio Call';
		
		$msgBody = "<div class='p-3 border mt-1 shadow-sm text-center' style='background: #111e2e; border-color: #1e3a5f; color: #fff; border-radius: 16px; min-width: 200px; max-width: 250px;'>"
				 . "  <div class='mb-2 text-primary animate-pulse' style='font-size: 20px; color: #3b82f6 !important;'><i class='fas " . $icon . "'></i></div>"
				 . "  <h6 class='font-weight-bold mb-1' style='font-size: 13px; color: #fff;'>" . $title . "</h6>"
				 . "  <p class='text-muted small mb-3' style='font-size: 9.5px;'>Click below to join the call room.</p>"
				 . "  <button class='btn btn-success btn-sm btn-block font-weight-bold shadow-xs py-1.5' style='border-radius: 8px;' onclick='joinJitsiCall(\"" . addslashes($callRoom) . "\", \"" . addslashes($callType) . "\"); return false;'><i class='fas fa-phone-volume mr-1'></i> Join Call</button>"
				 . "</div>";
		return $msgBody;
	}
	
	if (preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', $trimmedMsg, $matches)) {
		$fileName = htmlspecialchars($matches[1]);
		$filePath = htmlspecialchars($matches[2]);
		$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
		
		if (in_array($fileExt, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
			$msgBody = "<a href='" . $filePath . "' onclick='openLightbox(\"" . addslashes($filePath) . "\"); return false;'><img src='" . $filePath . "' class='img-fluid shadow-xs chat-attachment-img' style='max-width: min(100%, 250px); max-height: 250px; width: auto; height: auto; display: block; cursor: zoom-in; border-radius: 16px;' alt='Attached Image'/></a>";
		} elseif (in_array($fileExt, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'])) {
			$thumbPath = str_replace('.' . $fileExt, '_thumb.jpg', $filePath);
			if (!file_exists($thumbPath)) {
				$ffmpeg = getFFmpegPath();
				if ($ffmpeg && file_exists($filePath)) {
					$absVideo = realpath($filePath);
					$absThumb = __DIR__ . '/' . $thumbPath;
					$cmd = "\"$ffmpeg\" -y -i \"$absVideo\" -ss 00:00:00.500 -vframes 1 -f image2 \"$absThumb\" > NUL 2>&1";
					if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
						$cmd = "\"$ffmpeg\" -y -i \"$absVideo\" -ss 00:00:00.500 -vframes 1 -f image2 \"$absThumb\" > /dev/null 2>&1";
					}
					exec($cmd);
				}
			}
			$posterAttr = file_exists($thumbPath) ? " poster='" . $thumbPath . "'" : "";
			
			$msgBody = "<div class='position-relative shadow-xs video-preview-container' style='max-width: min(100%, 250px); display: inline-block; cursor: pointer; overflow: hidden; border-radius: 16px;' onclick='openLightbox(\"" . addslashes($filePath) . "\"); return false;'>"
					 . "  <video src='" . $filePath . "#t=0.1'" . $posterAttr . " style='width: 100%; max-width: 250px; aspect-ratio: 3/2; object-fit: cover; display: block; border-radius: 16px;' preload='auto' muted playsinline></video>"
					 . "  <div class='position-absolute d-flex align-items-center justify-content-center play-btn-overlay' style='top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); color: #fff; font-size: 28px;'>"
					 . "    <i class='fas fa-play-circle'></i>"
					 . "  </div>"
					 . "</div>";
		} elseif (in_array($fileExt, ['mp3', 'wav', 'ogg', 'm4a', 'aac'])) {
			$audioColorStyle = $isSelf ? 'background-color: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.25); color: #fff;' : 'background-color: #f1f3f5; border-color: #e2e8f0; color: #212529;';
			$msgBody = "<div class='p-2 border mt-1 shadow-xs text-left' style='$audioColorStyle border-radius: 12px; min-width: 220px; max-width: 260px;'>"
					 . "  <div class='small font-weight-bold mb-2' style='font-size:10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'><i class='fas fa-headphones mr-1'></i>" . htmlspecialchars(truncateFileName($fileName, 22)) . "</div>"
					 . "  <audio src='" . $filePath . "' controls style='width: 100%; height: 28px;' class='w-100'></audio>"
					 . "</div>";
		} else {
			$iconClass = 'fa-file';
			if (in_array($fileExt, ['pdf'])) $iconClass = 'fa-file-pdf text-danger';
			elseif (in_array($fileExt, ['zip', 'rar', '7z'])) $iconClass = 'fa-file-archive text-warning';
			elseif (in_array($fileExt, ['doc', 'docx'])) $iconClass = 'fa-file-word text-primary';
			elseif (in_array($fileExt, ['xls', 'xlsx'])) $iconClass = 'fa-file-excel text-success';
			
			$msgBody = "<a href='" . $filePath . "' download class='d-inline-flex align-items-center p-2 bg-light border text-decoration-none text-dark shadow-xs mt-1 hover-bg-light-dark' style='background-color:#f1f3f5; border-radius: 12px;'>"
					 . "  <i class='fas " . $iconClass . " mr-2 h5 mb-0' style='font-size:16px;'></i>"
					 . "  <div class='text-left' style='margin:0;line-height: 1.1; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>"
						 . "    <span class='font-weight-bold small d-block' style='font-size: 11px; color:#212529;'>" . $fileName . "</span>"
						 . "    <span class='text-muted' style='font-size: 9px;'>Click to download</span>"
					 . "  </div>"
					 . "</a>";
		}
	}
	return $msgBody;
}

// ----------------------------------------------------------------------
// AJAX ENDPOINTS
// ----------------------------------------------------------------------

// 1. Fetch Chat Feed Messages
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
	$client_me = isset($_GET['me']) ? $_GET['me'] : '';
	if ($_SESSION['user'] !== $client_me) {
		header('HTTP/1.1 401 Unauthorized');
		echo "SESSION_EXPIRED";
		exit;
	}
	
	$activeChat = isset($_GET['user']) ? $_GET['user'] : '';
	
	if ($activeChat !== '') {
		if ($activeChat === 'lobby') {
			// Query Group Chat messages
			$query = $link->query("SELECT * FROM chat_messages WHERE room_id IS NULL ORDER BY id ASC");
			$messageType = 'public';
		} else {
			// Query Private DM messages
			$activeChat_esc = mysqli_real_escape_string($link, $activeChat);
			$query = $link->query("SELECT * FROM private_messages WHERE (sender='$me_esc' AND receiver='$activeChat_esc') OR (sender='$activeChat_esc' AND receiver='$me_esc') ORDER BY id ASC");
			$messageType = 'private';
			
			// Mark incoming private messages as read
			$link->query("UPDATE private_messages SET is_read = 1 WHERE sender='$activeChat_esc' AND receiver='$me_esc' AND is_read = 0");
		}
		
		if ($query) {
			while ($msgRow = mysqli_fetch_array($query)) {
				$msgId = intval($msgRow['id']);
				$sender = $msgRow['sender'];
				$message = $msgRow['message'];
				$isUnsent = intval($msgRow['is_unsent']);
				
				$isSelf = (strtolower(trim($sender)) === strtolower(trim($me)));
				$timestamp = date('h:i A', strtotime($msgRow['sent_at']));
				
				$isOnlyImage = false;
				if (preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', trim($message), $m)) {
					$ext = strtolower(pathinfo($m[1], PATHINFO_EXTENSION));
					if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'])) {
						$isOnlyImage = true;
					}
				}
				
				$msgBody = $isUnsent ? "<i>This message was unsent</i>" : formatMessageBody($message, $emoticonArray, $isSelf);
				
				// Parse reply block
				$replyBlock = '';
				if ($msgRow['reply_to'] !== null && !$isUnsent) {
					$parentTable = ($activeChat === 'lobby') ? 'chat_messages' : 'private_messages';
					$parentQuery = $link->query("SELECT sender, message, is_unsent FROM $parentTable WHERE id = " . intval($msgRow['reply_to']));
					if ($parentQuery && mysqli_num_rows($parentQuery) > 0) {
						$parentRow = mysqli_fetch_array($parentQuery);
						$parentSender = htmlspecialchars($parentRow['sender']);
						$parentMsg = $parentRow['is_unsent'] ? '<i>This message was unsent</i>' : htmlspecialchars($parentRow['message']);
						if (preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', $parentRow['message'], $pm)) {
							$parentMsg = '📎 Attachment: ' . htmlspecialchars(truncateFileName($pm[1]));
						} else {
							foreach ($emoticonArray as $code => $location) {
								$parentMsg = str_replace($code, $location, $parentMsg);
							}
						}
						
						$replyColorStyle = $isSelf ? 'color: rgba(255, 255, 255, 0.85);' : 'color: #6c757d;';
						$replyBorderClass = $isSelf ? 'border-white' : 'border-primary';
						$replyBlock = "<div class='border-left $replyBorderClass pl-2 mb-1 text-left small' style='font-size:11px; border-width:3px !important; background:rgba(0,0,0,0.03); padding:2px 5px; border-radius:4px; $replyColorStyle'>"
									. "<strong>@$parentSender</strong>: $parentMsg"
									. "</div>";
					}
				}
				
				// Query Reactions
				$reactionsHtml = '';
				if (!$isUnsent) {
					$reactionsQuery = $link->query("SELECT reaction, COUNT(*), GROUP_CONCAT(username) FROM message_reactions WHERE message_type='$messageType' AND message_id=" . intval($msgId) . " GROUP BY reaction");
					if ($reactionsQuery && mysqli_num_rows($reactionsQuery) > 0) {
						$reactionsHtml = "<div class='reaction-bubble-badge d-flex align-items-center border rounded-pill shadow-xs px-2 py-0.5 position-absolute' style='bottom: -11px; " . ($isSelf ? "right: 12px;" : "left: 12px;") . " z-index: 5; height: 20px; line-height: 1;'>";
						while ($reactRow = mysqli_fetch_array($reactionsQuery)) {
							$emoji = htmlspecialchars($reactRow[0]);
							$count = intval($reactRow[1]);
							$usernames = htmlspecialchars($reactRow[2]);
							$hasReacted = strpos(strtolower($usernames), strtolower($me)) !== false ? 'text-primary' : 'text-muted';
							
							$reactionsHtml .= "<span class='reaction-pill-item d-inline-flex align-items-center $hasReacted' style='cursor:pointer; font-size:11px; margin-right:6px;' title='$usernames' onclick='sendReaction(\"$messageType\", $msgId, \"$emoji\"); return false;'>"
											. "  $emoji<span class='ml-1 font-weight-bold' style='font-size:9.5px; color:inherit;'>$count</span>"
											. "</span>";
						}
						$reactionsHtml .= "</div>";
					}
				}
				
				// Message Controls (reply, react, unsend)
				$controlsHtml = '';
				if (!$isUnsent) {
					$escapedSender = addslashes(htmlspecialchars($sender));
					$escapedMsg = addslashes(htmlspecialchars(substr($message, 0, 40)));
					
					$controlsHtml = "<div class='chat-msg-controls d-inline-flex align-items-center ml-2 mr-2' style='font-size: 13px; vertical-align: middle;'>"
								  . "<div class='dropdown d-inline-block position-relative'>"
								  . "  <button class='btn btn-xs btn-link text-muted p-0 hover-scale' onclick='toggleReactionMenu(event, this); return false;' title='React' style='font-size:14px; box-shadow:none; border:none; background:transparent;'><i class='far fa-smile'></i></button>"
								  . "  <div class='reaction-menu-popup d-none p-1 border shadow-sm bg-white position-absolute' style='bottom: 24px; left: 0; white-space: nowrap; border-radius: 20px; z-index: 1000;'>"
								  . "    <a class='d-inline-block p-1 hover-bounce' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"$messageType\", $msgId, \"👍\"); return false;'>👍</a>"
								  . "    <a class='d-inline-block p-1 hover-bounce' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"$messageType\", $msgId, \"❤️\"); return false;'>❤️</a>"
								  . "    <a class='d-inline-block p-1 hover-bounce' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"$messageType\", $msgId, \"😂\"); return false;'>😂</a>"
								  . "    <a class='d-inline-block p-1 hover-bounce' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"$messageType\", $msgId, \"😮\"); return false;'>😮</a>"
								  . "    <a class='d-inline-block p-1 hover-bounce' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"$messageType\", $msgId, \"😢\"); return false;'>😢</a>"
								  . "    <a class='d-inline-block p-1 hover-bounce' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"$messageType\", $msgId, \"😡\"); return false;'>😡</a>"
								  . "  </div>"
								  . "</div>"
								  . "<button class='btn btn-xs btn-link text-muted p-0 ml-2 hover-scale' title='Reply' onclick='setReply($msgId, \"$escapedSender\", \"$escapedMsg\"); return false;' style='font-size:14px; box-shadow:none; border:none; background:transparent;'><i class='fas fa-reply'></i></button>";
						  
					if ($isSelf) {
						$isFileMsg = preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', trim($message));
						if (!$isFileMsg) {
							$controlsHtml .= "<button class='btn btn-xs btn-link text-muted p-0 ml-2 hover-scale' title='Edit' onclick='startEditMessage(\"$messageType\", $msgId, this); return false;' style='font-size:14px; box-shadow:none; border:none; background:transparent;'><i class='far fa-edit'></i></button>";
						}
						$controlsHtml .= "<button class='btn btn-xs btn-link text-danger p-0 ml-2 hover-scale' title='Unsend' onclick='unsendMessage(\"$messageType\", $msgId); return false;' style='font-size:14px; box-shadow:none; border:none; background:transparent;'><i class='far fa-trash-alt'></i></button>";
					}
					$controlsHtml .= "</div>";
				}
				
				// Render Bubble Layout
				if ($isSelf) {
					$bubbleClass = $isOnlyImage ? "p-0 shadow-xs text-left d-inline-block position-relative" : "p-2 px-3 chat-bubble-self text-white shadow-xs text-left d-inline-block position-relative";
					$bubbleStyle = $isOnlyImage ? "background: transparent; border: none; border-radius: 18px; overflow: visible;" : "border-radius: 18px; font-size: 13.5px; line-height: 1.45;";
					
					echo "<div class='chat-msg-row d-flex justify-content-end flex-wrap mb-3' data-msg-id='{$msgId}'>";
					echo "  <div class='text-right' style='max-width: 75%;'>";
					echo "    <div class='small text-muted font-weight-bold mb-1' style='font-size: 10.5px; opacity: 0.85;'>" . htmlspecialchars($sender) . " <span class='font-weight-normal' style='font-size: 9px;'>$timestamp</span></div>";
					echo "    <div class='d-flex align-items-center justify-content-end'>";
					echo "      " . $controlsHtml;
					$editedHtml = '';
					if (isset($msgRow['is_edited']) && intval($msgRow['is_edited']) === 1) {
						$editedHtml = " <small class='text-muted font-italic' style='font-size: 9px; opacity: 0.7; color: rgba(255,255,255,0.7) !important;'>(edited)</small>";
					}
					$rawMsgEsc = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
					echo "      <div class='$bubbleClass' style='$bubbleStyle' data-raw-msg='{$rawMsgEsc}'>";
					echo "        " . $replyBlock;
					echo "        <span style='vertical-align: middle;'>" . $msgBody . $editedHtml . "</span>";
					echo "        " . $reactionsHtml;
					echo "      </div>";
					echo "    </div>";
					echo "  </div>";
					echo "</div>";
				} else {
					$bubbleClass = $isOnlyImage ? "p-0 shadow-xs text-left d-inline-block position-relative" : "p-2 px-3 chat-bubble-other text-dark border shadow-xs d-inline-block position-relative";
					$bubbleStyle = $isOnlyImage ? "background: transparent; border: none; border-radius: 18px; overflow: visible;" : "border-radius: 18px; font-size: 13.5px; line-height: 1.45;";
					
					$recentCallAttr = '';
					if (strpos($message, '[CALL:') !== false && (time() - strtotime($msgRow['sent_at'])) < 30) {
						$recentCallAttr = " data-incoming-call-recent='true'";
					}
					echo "<div class='chat-msg-row d-flex justify-content-start flex-wrap mb-3' data-msg-id='{$msgId}'{$recentCallAttr}>";
					echo "  <div class='text-left' style='max-width: 75%;'>";
					echo "    <div class='small text-muted font-weight-bold mb-1' style='font-size: 10.5px; opacity: 0.85;'>" . htmlspecialchars($sender) . " <span class='font-weight-normal' style='font-size: 9px;'>$timestamp</span></div>";
					echo "    <div class='d-flex align-items-center justify-content-start'>";
					$editedHtml = '';
					if (isset($msgRow['is_edited']) && intval($msgRow['is_edited']) === 1) {
						$editedHtml = " <small class='text-muted font-italic' style='font-size: 9px; opacity: 0.7;'>(edited)</small>";
					}
					$rawMsgEsc = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
					echo "      <div class='$bubbleClass' style='$bubbleStyle' data-raw-msg='{$rawMsgEsc}'>";
					echo "        " . $replyBlock;
					echo "        <span style='vertical-align: middle;'>" . $msgBody . $editedHtml . "</span>";
					echo "        " . $reactionsHtml;
					echo "      </div>";
					echo "      " . $controlsHtml;
					echo "    </div>";
					echo "  </div>";
					echo "</div>";
				}
			}
		}
	}
	exit;
}

// 2. Fetch Sidebar Conversations List
if (isset($_GET['ajax_users']) && $_GET['ajax_users'] === '1') {
	$conversations = [];
	
	// A. Query Lobby Last Message
	$lobby_last = $link->query("SELECT message, sent_at, sender FROM chat_messages WHERE room_id IS NULL ORDER BY id DESC LIMIT 1");
	$lobby_row = mysqli_fetch_array($lobby_last);
	
	$conversations[] = [
		'type' => 'group',
		'id' => 'lobby',
		'name' => 'Lobby Group Chat',
		'avatar' => 'images/users/lobby.png',
		'last_msg' => $lobby_row ? $lobby_row['message'] : 'No messages yet',
		'last_sender' => $lobby_row ? $lobby_row['sender'] : '',
		'time' => $lobby_row ? $lobby_row['sent_at'] : '1970-01-01 00:00:00',
		'unread' => 0,
		'is_online' => true
	];
	
	// B. Query Users & Last Exchanged Messages
	$users_query = $link->query("SELECT u.username AS user, u.imgUrl, u.fullname,
		(SELECT COUNT(*) FROM users_sessions WHERE username = u.username AND logout_time IS NULL AND last_active >= DATE_SUB(NOW(), INTERVAL 60 SECOND)) AS is_online,
		(SELECT MAX(id) FROM private_messages WHERE (sender='$me_esc' AND receiver=u.username) OR (sender=u.username AND receiver='$me_esc')) AS last_msg_id
		FROM users AS u WHERE u.username != '$me_esc'");
		
	while ($rs = mysqli_fetch_array($users_query)) {
		$last_msg = null;
		if ($rs['last_msg_id']) {
			$msg_q = $link->query("SELECT message, sent_at, sender FROM private_messages WHERE id = " . intval($rs['last_msg_id']));
			$last_msg = mysqli_fetch_array($msg_q);
		}
		
		// Count unread from this sender
		$sender_esc = mysqli_real_escape_string($link, $rs['user']);
		$unreadQuery = $link->query("SELECT COUNT(*) FROM private_messages WHERE sender='$sender_esc' AND receiver='$me_esc' AND is_read = 0");
		$unreadRow = mysqli_fetch_array($unreadQuery);
		$unreadCount = $unreadRow[0];
		
		$avatarPath = "images/users/" . $rs['imgUrl'];
		if (empty($rs['imgUrl']) || !file_exists($avatarPath)) {
			$avatarPath = "images/users/blank.jpg";
		}
		
		$conversations[] = [
			'type' => 'private',
			'id' => $rs['user'],
			'name' => $rs['fullname'],
			'avatar' => $avatarPath,
			'last_msg' => $last_msg ? $last_msg['message'] : 'No messages yet',
			'last_sender' => $last_msg ? $last_msg['sender'] : '',
			'time' => $last_msg ? $last_msg['sent_at'] : '1970-01-01 00:00:00',
			'unread' => $unreadCount,
			'is_online' => intval($rs['is_online']) > 0
		];
	}
	
	// C. Sort Conversations by last activity descending
	usort($conversations, function($a, $b) {
		return strtotime($b['time']) - strtotime($a['time']);
	});
	
	// D. Output HTML list
	foreach ($conversations as $conv) {
		$isActive = (isset($_GET['active']) && $_GET['active'] === $conv['id']) ? 'active-chat-item' : '';
		
		$displayTime = '';
		if ($conv['time'] !== '1970-01-01 00:00:00') {
			$msgTime = strtotime($conv['time']);
			if (date('Ymd', $msgTime) === date('Ymd')) {
				$displayTime = date('h:i A', $msgTime);
			} else {
				$displayTime = date('M d', $msgTime);
			}
		}
		
		// Truncate message preview
		$previewText = $conv['last_msg'];
		if (preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', trim($previewText), $m)) {
			$previewText = "📎 Attachment: " . truncateFileName($m[1]);
		}
		if ($conv['last_sender']) {
			$prefix = ($conv['last_sender'] === $me) ? "You: " : $conv['last_sender'] . ": ";
			$previewText = $prefix . $previewText;
		}
		if (strlen($previewText) > 42) {
			$previewText = substr($previewText, 0, 39) . "...";
		}
		$previewText = htmlspecialchars($previewText);
		
		$badgeHtml = '';
		if ($conv['unread'] > 0) {
			$badgeHtml = "<span class='badge badge-primary badge-pill ml-auto px-2 py-1 font-weight-bold animate-pulse' style='font-size: 10px; border-radius: 10px;'>" . $conv['unread'] . "</span>";
			$textWeight = 'font-weight-bold text-dark';
		} else {
			$textWeight = 'text-muted';
		}
		
		$statusBorder = $conv['is_online'] ? 'border-success' : 'border-secondary';
		$onlineDotHtml = $conv['is_online'] ? "<span class='online-status-dot'></span>" : "";
		
		$clickParam = $conv['id'];
		echo "<a href='messenger.php?user=" . urlencode($clickParam) . "' class='list-group-item list-group-item-action d-flex align-items-center py-2.5 px-3 border-0 rounded-lg mb-1 chat-sidebar-item " . $isActive . "' data-user-id='" . htmlspecialchars($conv['id']) . "'>";
		echo "  <div class='position-relative mr-2.5'>";
		echo "    <img src='" . htmlspecialchars($conv['avatar']) . "' class='rounded-circle border " . $statusBorder . "' style='width: 44px; height: 44px; object-fit: cover;' alt='Avatar'>";
		echo "    " . $onlineDotHtml;
		echo "  </div>";
		echo "  <div class='flex-grow-1 min-width-0 pr-2'>";
		echo "    <div class='d-flex justify-content-between align-items-center mb-0.5'>";
		echo "      <span class='font-weight-bold text-dark text-truncate' style='font-size: 13.5px;'>" . htmlentities($conv['name']) . "</span>";
		echo "      <small class='text-muted ml-2 font-weight-normal' style='font-size: 10px; white-space: nowrap;'>" . $displayTime . "</small>";
		echo "    </div>";
		echo "    <div class='d-flex align-items-center'>";
		echo "      <span class='text-truncate " . $textWeight . "' style='font-size: 12px; max-width: 100%;'>" . $previewText . "</span>";
		echo "      " . $badgeHtml;
		echo "    </div>";
		echo "  </div>";
		echo "</a>";
	}
	exit;
}

// ----------------------------------------------------------------------
// MESSAGE POST HANDLING
// ----------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$receiver = isset($_POST['receiver']) ? $_POST['receiver'] : '';
	$message = isset($_POST['message']) ? stripslashes($_POST['message']) : '';
	$replyTo = isset($_POST['reply_to']) && intval($_POST['reply_to']) > 0 ? intval($_POST['reply_to']) : 'NULL';
	
	// Handle File Upload
	if (isset($_FILES['chat_file']) && $_FILES['chat_file']['error'] == UPLOAD_ERR_OK) {
		$uploadDir = 'uploads/';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}
		
		$tmpName = $_FILES['chat_file']['tmp_name'];
		$originalName = basename($_FILES['chat_file']['name']);
		$fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
		
		$newName = time() . '_' . uniqid() . '.' . $fileExt;
		$targetPath = $uploadDir . $newName;
		
		if (move_uploaded_file($tmpName, $targetPath)) {
			$fileMarkup = "[FILE:" . $originalName . "|" . $targetPath . "]";
			$message = trim($message) !== '' ? $fileMarkup . " " . $message : $fileMarkup;
		}
	}
	
	if ($receiver != '' && trim($message) != '') {
		$message_esc = mysqli_real_escape_string($link, $message);
		
		if ($receiver === 'lobby') {
			$insQuery = "INSERT INTO chat_messages (room_id, sender, message, reply_to) VALUES (NULL, '$me_esc', '$message_esc', $replyTo)";
		} else {
			$receiver_esc = mysqli_real_escape_string($link, $receiver);
			$insQuery = "INSERT INTO private_messages (sender, receiver, message, reply_to) VALUES ('$me_esc', '$receiver_esc', '$message_esc', $replyTo)";
		}
		
		if ($link->query($insQuery)) {
			if (isset($_GET['ajax_send'])) {
				echo "OK";
				exit;
			}
			header("Location: messenger.php?user=" . urlencode($receiver));
			exit;
		} else {
			if (isset($_GET['ajax_send'])) {
				echo "ERROR: " . $link->error;
				exit;
			}
			echo "<div class='alert alert-danger no-print font-weight-bold'>There was an error saving your message: " . $link->error . "</div>";
		}
	} else {
		if (isset($_GET['ajax_send'])) {
			echo "EMPTY";
			exit;
		}
	}
}

// Automatically redirect to the public lobby if no active chat is specified
$other = isset($_GET['user']) ? $_GET['user'] : '';
if ($other === '') {
	header("Location: messenger.php?user=lobby");
	exit;
}

require("header.php");
require("menu.php");

$otherProfile = null;
$isGroup = false;

if ($other === 'lobby') {
	$isGroup = true;
	$otherProfile = [
		'fullname' => 'Lobby Group Chat',
		'username' => 'lobby',
		'imgUrl' => 'blank.jpg',
		'access' => 'Public Channel'
	];
	$is_other_online = true;
} else if ($other !== '') {
	$other_escaped = mysqli_real_escape_string($link, $other);
	$profileQuery = $link->query("SELECT * FROM users WHERE username = '$other_escaped'");
	if ($profileQuery && mysqli_num_rows($profileQuery) > 0) {
		$otherProfile = mysqli_fetch_array($profileQuery);
		
		// Check Online Status
		$other_online_query = $link->query("SELECT COUNT(*) FROM users_sessions WHERE username = '$other_escaped' AND logout_time IS NULL AND last_active >= DATE_SUB(NOW(), INTERVAL 60 SECOND)");
		$other_online_row = mysqli_fetch_array($other_online_query);
		$is_other_online = intval($other_online_row[0]) > 0;
	}
}
?>

<script>
if (typeof setActive === 'function') {
	setActive("chat");
}
</script>

<!-- Google Font Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ----------------------------------------------------------------------
   DESIGN SYSTEM & MODERN MESSENGER THEME
   ---------------------------------------------------------------------- */
body {
	font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
	background-color: #f0f2f5;
	overflow: hidden !important;
	height: 100vh;
}
.footer, .thid {
	display: none !important;
}

/* Glassmorphism Header elements */
.messenger-header {
	background: rgba(255, 255, 255, 0.95);
	backdrop-filter: blur(8px);
	border-bottom: 1px solid #e4e6eb;
}

/* Unified window container */
.messenger-container {
	margin-top: 56px;
	height: calc(100vh - 56px);
	height: calc(100dvh - 56px);
	display: flex;
	overflow: hidden;
	background: #ffffff;
}

/* Sidebar Styling */
.messenger-sidebar {
	width: 360px;
	border-right: 1px solid #f0f2f5;
	display: flex;
	flex-direction: column;
	background: #ffffff;
	flex-shrink: 0;
	z-index: 10;
	transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.sidebar-search-box {
	position: relative;
	width: 100%;
}
.sidebar-search-box i {
	position: absolute;
	left: 14px;
	top: 50%;
	transform: translateY(-50%);
	color: #8a8d91;
	font-size: 14px;
}
.sidebar-search-box input {
	padding-left: 36px;
	background-color: #f0f2f5;
	border: none;
	border-radius: 20px;
	font-size: 13.5px;
	font-weight: 500;
	outline: none;
	box-shadow: none !important;
	transition: background 0.15s ease;
}
.sidebar-search-box input:focus {
	background-color: #e4e6eb;
}

/* Sidebar Chat item */
.chat-sidebar-item {
	transition: background-color 0.2s ease, transform 0.1s ease;
	border-radius: 12px !important;
	margin-bottom: 4px;
	border: none !important;
	padding: 10px 12px !important;
}
.chat-sidebar-item:hover {
	background-color: #f2f3f5 !important;
}
.chat-sidebar-item.active-chat-item {
	background-color: #e7f3ff !important;
}
.chat-sidebar-item.active-chat-item span.text-dark {
	color: #0078ff !important;
}
.chat-sidebar-item.active-chat-item span.text-muted {
	color: #0084ff !important;
}

/* Online/Active Green Dot Indicator */
.position-relative {
	position: relative;
}
.online-status-dot {
	position: absolute;
	bottom: 2px;
	right: 2px;
	width: 11px;
	height: 11px;
	background-color: #31a24c;
	border: 2px solid #ffffff;
	border-radius: 50%;
}
.mr-2\.5 {
	margin-right: 10px;
}

/* Middle active chat panel */
.messenger-chat-pane {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	background: #ffffff;
	height: 100%;
	position: relative;
	min-width: 0;
}

/* Message feed */
.messenger-feed {
	flex-grow: 1;
	padding: 20px 24px;
	overflow-y: auto;
	background-color: #ffffff;
	scroll-behavior: smooth;
}

/* Chat bubble aesthetics */
.chat-bubble-self {
	background: linear-gradient(135deg, #0084ff 0%, #7b00ff 100%) !important;
	color: #ffffff !important;
	box-shadow: 0 2px 8px rgba(0, 132, 255, 0.2);
}
.chat-bubble-other {
	background: #e4e6eb !important;
	color: #050505 !important;
	border: none !important;
}

/* Attachment images/videos inside message panes */
.chat-attachment-img, .video-preview-container video {
	box-shadow: 0 2px 10px rgba(0,0,0,0.06);
	transition: opacity 0.2s ease, transform 0.2s ease;
}
.chat-attachment-img:hover, .video-preview-container:hover video {
	opacity: 0.95;
	transform: scale(1.01);
}

/* Message controls toolbar on hover */
.chat-msg-row {
	position: relative;
}
.chat-msg-controls {
	opacity: 0;
	visibility: hidden;
	transition: opacity 0.15s ease-in-out, visibility 0.15s ease-in-out;
}
.chat-msg-row:hover .chat-msg-controls {
	opacity: 1;
	visibility: visible;
}
.hover-scale {
	transition: transform 0.15s ease;
}
.hover-scale:hover {
	transform: scale(1.2);
}
.hover-bounce {
	transition: transform 0.1s ease;
}
.hover-bounce:hover {
	transform: scale(1.3) translateY(-4px);
}

/* Reaction Menu Popover popup */
.reaction-menu-popup {
	border-radius: 20px;
	box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
	background-color: #ffffff;
	border: 1px solid #e4e6eb !important;
	animation: slideUp 0.15s ease-out;
}
@keyframes slideUp {
	from { transform: translateY(8px); opacity: 0; }
	to { transform: translateY(0); opacity: 1; }
}

/* Footer & chat input bar styling */
.messenger-footer {
	background: #ffffff;
	border-top: 1px solid #f0f2f5;
	padding: 12px 20px;
}
.chat-input-area {
	background-color: #f0f2f5;
	border-radius: 20px;
	border: none;
	padding: 8px 16px;
	font-size: 13.5px;
	font-weight: 500;
	outline: none;
	width: 100%;
	max-height: 100px;
	overflow-y: auto;
	box-sizing: border-box;
	text-align: left;
}
.chat-input-area:empty::before {
	content: attr(placeholder);
	color: #8a8d91;
}

/* Right Recipient Profile pane styling */
.messenger-profile-pane {
	width: 320px;
	border-left: 1px solid #f0f2f5;
	display: flex;
	flex-direction: column;
	background: #ffffff;
	flex-shrink: 0;
	z-index: 5;
}

/* Custom Standalone Lightbox Styles */
.custom-lightbox {
	position: fixed;
	top: 0;
	left: 0;
	width: 100vw;
	height: 100vh;
	background: rgba(0, 0, 0, 0.95);
	z-index: 100000;
	display: none;
	align-items: center;
	justify-content: center;
	opacity: 0;
	transition: opacity 0.25s ease;
}
.custom-lightbox.show {
	display: flex;
	opacity: 1;
}
.custom-lightbox-close {
	position: absolute;
	top: max(20px, env(safe-area-inset-top));
	left: max(20px, env(safe-area-inset-left));
	width: 44px;
	height: 44px;
	border-radius: 50%;
	background: rgba(255, 255, 255, 0.15);
	border: 1px solid rgba(255, 255, 255, 0.3);
	color: #fff;
	font-size: 16px;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: background 0.2s, transform 0.15s;
	z-index: 100001;
	outline: none;
}
.custom-lightbox-close:hover {
	background: rgba(255, 255, 255, 0.3);
	transform: scale(1.1);
}
.custom-lightbox-content {
	max-width: 90vw;
	max-height: 90vh;
	border-radius: 8px;
	box-shadow: 0 5px 25px rgba(0,0,0,0.5);
	transform: scale(0.95);
	transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
	object-fit: contain;
}
.custom-lightbox.show .custom-lightbox-content {
	transform: scale(1);
}

/* Banner previews for reply & file upload */
.reply-preview-banner, .file-preview-banner {
	background: #f0f2f5;
	border-left: 4px solid #0084ff;
	border-radius: 6px;
	padding: 8px 14px;
	display: flex;
	align-items: center;
	justify-content: space-between;
}

/* Animations */
.animate-pulse {
	animation: pulse 1.5s infinite;
}
@keyframes pulse {
	0% { opacity: 0.5; }
	50% { opacity: 1; }
	100% { opacity: 0.5; }
}

/* Responsive Overrides */
@media (max-width: 991.98px) {
	.messenger-profile-pane {
		display: none !important;
	}
}
@media (max-width: 767.98px) {
	.container-fluid-chat-wrapper {
		margin-top: 50px !important;
	}
	.messenger-container {
		height: calc(100vh - 50px);
		height: calc(100dvh - 50px);
		position: relative;
	}
	.messenger-sidebar {
		width: 100%;
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		transform: translateX(0);
	}
	.messenger-sidebar.hide-sidebar {
		transform: translateX(-100%);
	}
	.messenger-chat-pane {
		width: 100%;
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		z-index: 5;
	}
}

/* Dark Mode Styles */
[data-theme="dark"] body {
	background-color: #18191a !important;
	color: #e4e6eb !important;
}
[data-theme="dark"] .messenger-container {
	background-color: #18191a !important;
	border-color: #2f3031 !important;
}
[data-theme="dark"] .messenger-sidebar {
	background-color: #18191a !important;
	border-right: 1px solid #2f3031 !important;
}
[data-theme="dark"] .chat-sidebar-item {
	background-color: transparent !important;
}
[data-theme="dark"] .chat-sidebar-item:hover {
	background-color: #2d2e30 !important;
}
[data-theme="dark"] .chat-sidebar-item.active-chat-item {
	background-color: #263951 !important;
}
[data-theme="dark"] .chat-sidebar-item.active-chat-item span.text-dark {
	color: #2e89ff !important;
}
[data-theme="dark"] .chat-sidebar-item.active-chat-item span.text-muted {
	color: #2e89ff !important;
}
[data-theme="dark"] span.text-dark, 
[data-theme="dark"] h4, 
[data-theme="dark"] h5, 
[data-theme="dark"] h6 {
	color: #e4e6eb !important;
}
[data-theme="dark"] .sidebar-search-box input {
	background-color: #242526 !important;
	color: #e4e6eb !important;
}
[data-theme="dark"] .sidebar-search-box input::placeholder {
	color: #b0b3b8 !important;
}
[data-theme="dark"] .messenger-chat-pane {
	background-color: #18191a !important;
}
[data-theme="dark"] .messenger-header {
	background: rgba(24, 25, 26, 0.95) !important;
	border-bottom: 1px solid #2f3031 !important;
}
[data-theme="dark"] .messenger-feed {
	background-color: #18191a !important;
}
[data-theme="dark"] .chat-bubble-other {
	background: #3a3b3c !important;
	color: #e4e6eb !important;
}
[data-theme="dark"] .chat-bubble-self {
	background: linear-gradient(135deg, #0078ff 0%, #7b00ff 100%) !important;
	color: #ffffff !important;
}
[data-theme="dark"] .messenger-footer {
	background-color: #18191a !important;
	border-top: 1px solid #2f3031 !important;
}
[data-theme="dark"] .chat-input-area {
	background-color: #242526 !important;
	color: #e4e6eb !important;
}
[data-theme="dark"] .chat-input-area:empty::before {
	color: #b0b3b8 !important;
}
[data-theme="dark"] .messenger-profile-pane {
	background-color: #18191a !important;
	border-left: 1px solid #2f3031 !important;
}
[data-theme="dark"] .messenger-profile-pane .bg-light {
	background-color: #242526 !important;
	border-color: #2f3031 !important;
}
[data-theme="dark"] .reaction-menu-popup {
	background-color: #242526 !important;
	border: 1px solid #3a3b3c !important;
}
[data-theme="dark"] .reply-preview-banner, 
[data-theme="dark"] .file-preview-banner {
	background: #242526 !important;
	border-color: #2e89ff !important;
}
[data-theme="dark"] .emoticon-tray {
	background-color: #242526 !important;
	border-color: #3a3b3c !important;
}
[data-theme="dark"] .btn-light {
	background-color: #3a3b3c !important;
	border-color: #4e4f50 !important;
	color: #e4e6eb !important;
}
[data-theme="dark"] .btn-light:hover {
	background-color: #4e4f50 !important;
}
</style>

<div class="container-fluid p-0 container-fluid-chat-wrapper">
	<div class="messenger-container">
		
		<!-- LEFT SIDEBAR: Conversation list -->
		<div class="messenger-sidebar <?php echo ($other !== '') ? 'hide-sidebar' : ''; ?>" id="sidebar-panel">
			<div class="p-3 d-flex justify-content-between align-items-center">
				<h4 class="font-weight-bold text-dark mb-0"><i class="fab fa-facebook-messenger text-primary mr-2"></i>Chats</h4>
				<a href="chat_rooms.php" class="btn btn-sm btn-light border rounded-circle shadow-xs" title="Chat Lobby" style="width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center;">
					<i class="fas fa-arrow-left"></i>
				</a>
			</div>
			
			<!-- Conversation Search -->
			<div class="px-3 pb-3">
				<div class="sidebar-search-box">
					<i class="fas fa-search"></i>
					<input type="text" class="form-control" id="conversation-search" placeholder="Search people..." onkeyup="filterSidebarConversations()">
				</div>
			</div>
			
			<!-- List of Users/Groups -->
			<div class="flex-grow-1 overflow-y-auto px-2 pb-3" style="overflow-y: auto;" id="sidebar-conversations-list">
				<!-- Injected dynamically via AJAX -->
				<div class="text-center py-4 text-muted small"><i class="fas fa-spinner fa-spin mr-1"></i>Loading conversations...</div>
			</div>
		</div>

		<!-- MIDDLE PANE: Active DM/Group Feed Workspace -->
		<div class="messenger-chat-pane">
			<?php if ($other !== '' && $otherProfile): ?>
				<!-- Active Chat Header -->
				<div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center messenger-header shadow-xs" style="z-index: 8;">
					<div class="d-flex align-items-center min-width-0">
						<!-- Back Arrow on Mobile view -->
						<a href="#" class="d-md-none mr-3 text-primary" onclick="showConversationsList(); return false;" style="font-size:18px;">
							<i class="fas fa-arrow-left"></i>
						</a>
						
						<?php
							$avatarPath = $isGroup ? "images/users/lobby.png" : ("images/users/" . $otherProfile['imgUrl']);
							if (!$isGroup && (empty($otherProfile['imgUrl']) || !file_exists($avatarPath))) {
								$avatarPath = "images/users/blank.jpg";
							}
						?>
						<div class="position-relative">
							<img src="<?php echo htmlspecialchars($avatarPath); ?>" class="rounded-circle mr-2 border <?php echo $is_other_online ? 'border-success' : 'border-secondary'; ?>" style="width: 40px; height: 40px; object-fit: cover;" alt="Avatar">
							<?php if ($is_other_online && !$isGroup): ?>
								<span class="online-status-dot"></span>
							<?php endif; ?>
						</div>
						<div class="text-left min-width-0 pr-2">
							<h6 class="font-weight-bold text-dark mb-0 text-truncate" style="font-size:14.5px;"><?php echo htmlentities($otherProfile['fullname']); ?></h6>
							<?php if ($isGroup): ?>
								<small class="text-muted" style="font-size: 11px;">Public Group Channel</small>
							<?php else: ?>
								<small class="font-weight-bold" style="font-size: 11px; color: <?php echo $is_other_online ? '#31a24c' : '#8a8d91'; ?>;">
									<?php echo $is_other_online ? 'Active now' : 'Offline'; ?>
								</small>
							<?php endif; ?>
						</div>
					</div>
					
					<!-- Call Options Header Toolbar -->
					<div class="d-flex align-items-center">
						<button class="btn btn-link text-primary p-2 mr-1 hover-scale" style="font-size: 17px; background: transparent; border: none;" title="Voice Call" onclick="startCall('audio'); return false;">
							<i class="fas fa-phone"></i>
						</button>
						<button class="btn btn-link text-primary p-2 mr-1 hover-scale" style="font-size: 17px; background: transparent; border: none;" title="Video Call" onclick="startCall('video'); return false;">
							<i class="fas fa-video"></i>
						</button>
						<button class="btn btn-link text-primary p-2 hover-scale" style="font-size: 17px; background: transparent; border: none;" title="Details" onclick="toggleDetailsPane()">
							<i class="fas fa-info-circle"></i>
						</button>
					</div>
				</div>

				<!-- Message feed workspace -->
				<div class="messenger-feed" id="chat-messages-scroll-pane">
					<div class="chat-messages-container" id="chat-messages-body-list">
						<!-- Loaded dynamically via AJAX -->
						<div class="text-center py-5 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i>Loading messages...</div>
					</div>
				</div>

				<!-- Input Form Footer -->
				<div class="messenger-footer shadow-xs">
					<form action="" method="post" name="postform" enctype="multipart/form-data" class="m-0" id="chat-form-element">
						<input type="hidden" name="receiver" value="<?php echo htmlentities($other); ?>"/>
						<input type="hidden" name="reply_to" id="reply-to-input" value="0"/>

						<!-- Reply Preview Box -->
						<div class="reply-preview-banner shadow-xs mb-2" id="reply-preview-container" style="display: none;">
							<div class="small text-muted text-left" style="font-size: 11px; line-height: 1.2;">
								<i class="fas fa-reply mr-1"></i>Replying to <span id="reply-preview-sender" class="font-weight-bold"></span>:
								<div id="reply-preview-text" class="text-truncate" style="max-width: 300px;"></div>
							</div>
							<button type="button" class="close font-weight-bold" onclick="cancelReply()" style="font-size: 16px; outline: none; border: none; background: transparent; color:#888;">&times;</button>
						</div>

						<!-- Attachment Preview Box -->
						<div class="file-preview-banner shadow-xs mb-2" id="file-preview-container" style="display: none; background: rgba(0, 132, 255, 0.06);">
							<div class="small text-primary text-left" style="font-size: 11px; line-height: 1.2;">
								<i class="fas fa-paperclip mr-1"></i>Attachment: <span id="file-preview-name" class="font-weight-bold"></span>
							</div>
							<button type="button" class="close text-primary font-weight-bold" onclick="cancelFile()" style="font-size: 16px; outline: none; border: none; background: transparent; opacity: 0.8;">&times;</button>
						</div>

						<!-- Input Box Wrapper -->
						<div class="d-flex align-items-center">
							<!-- Attachment clip button -->
							<button class="btn btn-link text-primary p-2 mr-2 hover-scale" type="button" onclick="document.getElementById('chat-file-input').click()" style="font-size: 17px; background: transparent; border: none;" title="Attach file">
								<i class="fas fa-plus-circle"></i>
							</button>
							<input type="file" name="chat_file" id="chat-file-input" style="display: none;" onchange="handleFileSelect(this);">

							<!-- Content editable input area -->
							<div class="flex-grow-1 position-relative mr-2">
								<div id="chat-message-editable" contenteditable="true" class="chat-input-area" placeholder="Aa" onkeydown="handleEnterKey(event)"></div>
								<input type="hidden" name="message" id="message-hidden-input">
							</div>

							<!-- Emoticon picker button -->
							<button class="btn btn-link text-primary p-2 mr-2 hover-scale" type="button" onclick="toggleEmojiTray()" style="font-size: 17px; background: transparent; border: none;" title="Pick Emoji">
								<i class="far fa-smile"></i>
							</button>

							<!-- Send message button -->
							<button class="btn btn-link text-primary p-2 hover-scale" type="submit" name="submit" style="font-size: 17px; background: transparent; border: none;" title="Send">
								<i class="fas fa-paper-plane"></i>
							</button>
						</div>

						<!-- Emojis panel drawer -->
						<div class="emoticon-tray flex-wrap align-items-center justify-content-center p-2 bg-light rounded-lg border mt-2" id="emoticon-tray-container" style="max-height: 100px; overflow-y: auto; display: none;">
							<?php
								$emojis = array(
									'😊', '😘', '😡', '😑', '😁', '😎', '😵', '😐', '😆', '😍', 
									'😢', '😲', '🤢', '😒', '😜', '😛', '👍', '😟', '😮', '🤔', 
									'😗', '😉', '🤏', '❓', '❗'
								);
								foreach ($emojis as $emoji) {
									echo "<a onclick='emo(\"" . addslashes($emoji) . "\")' class='m-1 p-1 d-inline-block rounded hover-scale' style='cursor:pointer; font-size:22px; text-decoration:none;'>" . $emoji . "</a>";
								}
							?>
						</div>
					</form>
				</div>
			<?php else: ?>
				<!-- Empty conversation state -->
				<div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-4 bg-light position-relative">
					<!-- Mobile Top Bar for Empty State -->
					<div class="p-3 bg-white border-bottom d-flex d-md-none justify-content-between align-items-center w-100" style="position: absolute; top: 0; left: 0; right: 0; z-index: 8;">
						<button class="btn btn-sm btn-light border font-weight-bold" onclick="showConversationsList()"><i class="fas fa-bars mr-1"></i> Chats</button>
						<span class="font-weight-bold text-dark">Messenger</span>
						<div style="width: 70px;">&nbsp;</div>
					</div>
					
					<div class="bg-white rounded-circle p-4 shadow-xs mb-3 text-primary animate-pulse" style="width: 80px; height: 80px; font-size: 32px; display: flex; align-items: center; justify-content: center;">
						<i class="fab fa-facebook-messenger"></i>
					</div>
					<h5 class="font-weight-bold text-dark">Welcome to Messenger</h5>
					<p class="text-secondary small max-width-350" style="max-width: 320px;">Select a direct user or the Lobby Group Chat from the sidebar list to start exchanging messages.</p>
				</div>
			<?php endif; ?>
		</div>

		<!-- RIGHT SIDE PANE: Recipient Details / Profile Info -->
		<div class="messenger-profile-pane <?php echo ($other !== '' && !$isGroup) ? '' : 'd-none'; ?>" id="details-panel">
			<?php if ($other !== '' && $otherProfile): ?>
				<div class="p-3 border-bottom d-flex align-items-center justify-content-between">
					<h6 class="font-weight-bold text-dark mb-0">Conversation Details</h6>
					<button class="btn btn-sm btn-light border rounded-circle" style="width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center;" onclick="toggleDetailsPane()">
						<i class="fas fa-times" style="font-size:10px;"></i>
					</button>
				</div>
				<div class="flex-grow-1 overflow-y-auto p-4 text-center d-flex flex-column align-items-center" style="overflow-y: auto;">
					<img src="<?php echo htmlspecialchars($avatarPath); ?>" class="rounded-circle mb-3 border <?php echo $is_other_online ? 'border-success' : 'border-secondary'; ?> shadow-sm" style="width: 80px; height: 80px; object-fit: cover;" alt="Avatar">
					<h6 class="font-weight-bold text-dark mb-1" style="font-size:15px;"><?php echo htmlentities($otherProfile['fullname']); ?></h6>
					
					<?php if ($isGroup): ?>
						<span class="badge badge-primary px-3 py-1 font-weight-bold rounded-pill mb-4" style="font-size:10.5px;">Public Group</span>
					<?php else: ?>
						<span class="badge <?php echo $is_other_online ? 'badge-success' : 'badge-secondary'; ?> px-3 py-1 font-weight-bold rounded-pill mb-4" style="font-size:10.5px; <?php echo !$is_other_online ? 'background-color:#adb5bd;' : ''; ?>">
							<?php echo $is_other_online ? 'Online' : 'Offline'; ?>
						</span>
					<?php endif; ?>

					<div class="w-100 text-left border rounded-lg p-3 bg-light shadow-xs">
						<div class="small text-muted font-weight-bold mb-1"><i class="fas fa-at mr-1.5 text-secondary" style="width:14px;"></i>Handle</div>
						<div class="font-weight-bold text-dark mb-3">@<?php echo htmlentities($otherProfile['username']); ?></div>

						<div class="small text-muted font-weight-bold mb-1"><i class="fas fa-shield-alt mr-1.5 text-secondary" style="width:14px;"></i>Account Role</div>
						<div class="font-weight-bold text-primary mb-3"><?php echo htmlentities($otherProfile['access']); ?></div>

						<?php
							if (!$isGroup) {
								// Fetch active session details for the chatmate
								$other_escaped = mysqli_real_escape_string($link, $otherProfile['username']);
								$session_query = $link->query("SELECT login_time, last_active, logout_time FROM users_sessions WHERE username = '$other_escaped' ORDER BY last_active DESC LIMIT 1");
								$session_row = ($session_query && mysqli_num_rows($session_query) > 0) ? mysqli_fetch_array($session_query) : null;
								
								if ($session_row) {
									$login_time = strtotime($session_row['login_time']);
									$last_active = strtotime($session_row['last_active']);
									
									if ($is_other_online) {
										$duration = time() - $login_time;
										if ($duration < 60) {
											$time_str = "Just now";
										} else if ($duration < 3600) {
											$time_str = floor($duration / 60) . " mins ago";
										} else {
											$time_str = floor($duration / 3600) . "h " . floor(($duration % 3600) / 60) . "m ago";
										}
										echo "<div class='small text-muted font-weight-bold mb-1'><i class='fas fa-sign-in-alt mr-1.5 text-secondary' style='width:14px;'></i>Logged In At</div>";
										echo "<div class='font-weight-bold text-success mb-3'>" . date('h:i A', $login_time) . " <span class='text-muted font-weight-normal' style='font-size:11px;'>($time_str)</span></div>";
										
										echo "<div class='small text-muted font-weight-bold mb-1'><i class='fas fa-pulse mr-1.5 text-secondary' style='width:14px;'></i>Last Active</div>";
										echo "<div class='font-weight-bold text-dark mb-0'>Active now</div>";
									} else {
										echo "<div class='small text-muted font-weight-bold mb-1'><i class='fas fa-history mr-1.5 text-secondary' style='width:14px;'></i>Last Active (Offline)</div>";
										echo "<div class='font-weight-bold text-secondary mb-0'>" . date('M d, h:i A', $last_active) . "</div>";
									}
								} else {
									echo "<div class='small text-muted font-weight-bold mb-1'><i class='fas fa-info-circle mr-1.5 text-secondary' style='width:14px;'></i>Status</div>";
									echo "<div class='font-weight-bold text-secondary mb-0'>No session history</div>";
								}
							}
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>

	</div>
</div>

<!-- Custom Standalone Lightbox Overlay -->
<div id="customMediaLightbox" class="custom-lightbox" onclick="hideLightboxModal()">
	<button type="button" class="custom-lightbox-close" onclick="hideLightboxModal()" title="Close Lightbox">
		<i class="fas fa-arrow-left"></i>
	</button>
	<img id="lightbox-image" class="custom-lightbox-content" src="" style="display: none;" alt="Preview" onclick="event.stopPropagation()">
	<video id="lightbox-video" class="custom-lightbox-content" controls preload="metadata" style="display: none; background: #000; outline: none;" onclick="event.stopPropagation()"></video>
</div>

<?php include("users_profile.php"); ?>

<!-- Client Javascript Handlers -->
<script language="JavaScript" type="text/javascript">
var myUsername = "<?php echo htmlspecialchars($me); ?>";
var activeChatId = "<?php echo htmlspecialchars($other); ?>";
var isChatActive = (activeChatId !== '');

// Close document reaction click handlers
document.addEventListener('click', function(e) {
	var menus = document.querySelectorAll('.reaction-menu-popup');
	menus.forEach(function(menu) {
		if (!menu.contains(e.target) && !menu.previousElementSibling.contains(e.target)) {
			menu.classList.add('d-none');
		}
	});
});

// React toggle drawer menu helper
function toggleReactionMenu(event, button) {
	event.stopPropagation();
	var menu = button.nextElementSibling;
	if (menu) {
		var isHidden = menu.classList.contains('d-none');
		// Hide all other menus
		document.querySelectorAll('.reaction-menu-popup').forEach(function(m) {
			m.classList.add('d-none');
		});
		if (isHidden) {
			menu.classList.remove('d-none');
		}
	}
}

// Mobile responsive UI navigation
function showConversationsList() {
	var sidebar = document.getElementById('sidebar-panel');
	if (sidebar) sidebar.classList.remove('hide-sidebar');
}

function showChatPane() {
	var sidebar = document.getElementById('sidebar-panel');
	if (sidebar) sidebar.classList.add('hide-sidebar');
}

// Right panel toggle details info
function toggleDetailsPane() {
	var pane = document.getElementById('details-panel');
	if (pane) {
		pane.classList.toggle('d-none');
	}
}

// Auto-Scroll message container to bottom
function scrollToBottom() {
	var pane = document.getElementById('chat-messages-scroll-pane');
	if (pane) {
		pane.scrollTop = pane.scrollHeight;
	}
}

// AJAX chat list update
function refreshConversationsList() {
	var xhr = new XMLHttpRequest();
	var ajaxUrl = "messenger.php?ajax_users=1" + (activeChatId ? "&active=" + encodeURIComponent(activeChatId) : "");
	xhr.open('GET', ajaxUrl, true);
	xhr.onload = function() {
		if (xhr.status === 200) {
			var listContainer = document.getElementById('sidebar-conversations-list');
			if (listContainer) {
				// Parse and keep scroll position if any
				var oldSearchVal = document.getElementById('conversation-search').value.trim().toLowerCase();
				listContainer.innerHTML = xhr.responseText;
				if (oldSearchVal !== '') {
					filterSidebarConversations();
				}
			}
		}
	};
	xhr.send();
}

// Filter conversations in sidebar dynamically
function filterSidebarConversations() {
	var input = document.getElementById('conversation-search');
	var filter = input.value.toLowerCase();
	var listItems = document.querySelectorAll('.chat-sidebar-item');
	listItems.forEach(function(item) {
		var name = item.querySelector('span.text-dark').textContent.toLowerCase();
		if (name.indexOf(filter) > -1) {
			item.style.display = "";
		} else {
			item.style.display = "none";
		}
	});
}

// AJAX message updates
function refreshChat() {
	if (!isChatActive) return;
	var xhr = new XMLHttpRequest();
	var ajaxUrl = "messenger.php?ajax=1&user=" + encodeURIComponent(activeChatId) + "&me=" + encodeURIComponent(myUsername);
	xhr.open('GET', ajaxUrl, true);
	xhr.onload = function() {
		if (xhr.status === 200) {
			if (xhr.responseText.trim() === "SESSION_EXPIRED") {
				window.location.href = "login.php";
				return;
			}
			var bodyList = document.getElementById('chat-messages-body-list');
			if (bodyList) {
				var scrollPane = document.getElementById('chat-messages-scroll-pane');
				var wasAtBottom = scrollPane ? (scrollPane.scrollTop + scrollPane.clientHeight >= scrollPane.scrollHeight - 50) : false;
				
				var newHtml = xhr.responseText;
				
				// 1. If HTML is identical, don't touch the DOM to prevent blinking/re-rendering
				if (bodyList.getAttribute('data-last-html') === newHtml) {
					return;
				}
				bodyList.setAttribute('data-last-html', newHtml);

				// 2. Perform smooth DOM-diffing updates
				var tempDiv = document.createElement('div');
				tempDiv.innerHTML = newHtml;

				var newRows = tempDiv.querySelectorAll('.chat-msg-row');
				var currentRows = bodyList.querySelectorAll('.chat-msg-row');

				// If there are no current rows, just set innerHTML directly once
				if (currentRows.length === 0) {
					bodyList.innerHTML = newHtml;
					scrollToBottom();
					return;
				}

				// Check and update rows
				var hasNewMessage = false;
				newRows.forEach(function(newRow) {
					var msgId = newRow.getAttribute('data-msg-id');
					var currentRow = bodyList.querySelector('.chat-msg-row[data-msg-id="' + msgId + '"]');
					if (!currentRow) {
						bodyList.appendChild(newRow);
						hasNewMessage = true;
						
						// Play ringtone and show incoming call overlay if the message is a CALL card and is incoming (justify-content-start)
						if (newRow.classList.contains('justify-content-start')) {
							var joinBtn = newRow.querySelector('button[onclick*="joinJitsiCall"]');
							if (joinBtn) {
								if (typeof showIncomingCallOverlay === 'function') {
									var senderEl = newRow.querySelector('.text-muted.font-weight-bold');
									var senderName = senderEl ? senderEl.textContent.trim().split(' ')[0] : 'LGU Member';
									
									var headerAvatarEl = document.querySelector('.messenger-header img');
									var senderAvatar = headerAvatarEl ? headerAvatarEl.getAttribute('src') : 'images/users/blank.jpg';
									
									var match = joinBtn.getAttribute('onclick').match(/joinJitsiCall\("(.+?)",\s*"(.+?)"\)/);
									if (match) {
										var roomName = match[1];
										var callType = match[2];
										showIncomingCallOverlay(senderName, roomName, callType, senderAvatar);
									}
								}
							} else {
								// Play chime for text message
								if (typeof playMessageNotificationSound === 'function') {
									playMessageNotificationSound();
								}
							}
						}
					} else {
						// Only update if innerHTML actually changed (e.g. new reaction or unsent)
						if (currentRow.innerHTML !== newRow.innerHTML) {
							currentRow.innerHTML = newRow.innerHTML;
						}
					}
				});

				// Clean up any deleted/unsent messages that are no longer in the feed
				currentRows.forEach(function(currentRow) {
					var msgId = currentRow.getAttribute('data-msg-id');
					var existsInNew = tempDiv.querySelector('.chat-msg-row[data-msg-id="' + msgId + '"]');
					if (!existsInNew) {
						currentRow.remove();
					}
				});

				if (hasNewMessage || wasAtBottom) {
					scrollToBottom();
				}
			}
		}
	};
	xhr.send();
}

// Post submission for message
if (isChatActive) {
	var form = document.getElementById('chat-form-element');
	var editable = document.getElementById('chat-message-editable');
	var hiddenInput = document.getElementById('message-hidden-input');
	
	form.addEventListener('submit', function(e) {
		e.preventDefault();
		var message = editable.innerText || editable.textContent || '';
		hiddenInput.value = emojiToCode(message);
		
		var fileInput = document.getElementById('chat-file-input');
		var hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
		
		if (message.trim() === '' && !hasFile) {
			return;
		}
		
		var submitBtn = form.querySelector('button[type="submit"]');
		if (submitBtn) submitBtn.disabled = true;
		editable.setAttribute('contenteditable', 'false');
		
		var formData = new FormData(form);
		var actionUrl = "messenger.php?ajax_send=1";
		
		var xhr = new XMLHttpRequest();
		xhr.open('POST', actionUrl, true);
		xhr.onload = function() {
			if (submitBtn) submitBtn.disabled = false;
			editable.setAttribute('contenteditable', 'true');
			
			if (xhr.status === 200 && xhr.responseText.trim() === 'OK') {
				editable.innerHTML = '';
				hiddenInput.value = '';
				cancelReply();
				cancelFile();
				editable.focus();
				refreshChat();
				refreshConversationsList();
			} else {
				alert("Failed to send message: " + (xhr.responseText || "Unknown error"));
			}
		};
		xhr.onerror = function() {
			if (submitBtn) submitBtn.disabled = false;
			editable.setAttribute('contenteditable', 'true');
			alert("Network error. Failed to send message.");
		};
		xhr.send(formData);
	});
	
	// Support sending by hitting Enter key
	function handleEnterKey(e) {
		if (e.keyCode === 13 && !e.shiftKey) {
			e.preventDefault();
			var chatForm = document.getElementById('chat-form-element');
			if (chatForm) {
				var submitBtn = chatForm.querySelector('button[type="submit"]');
				if (submitBtn && !submitBtn.disabled) {
					submitBtn.click();
				}
			}
		}
	}
}

// Reply preview handlers
function setReply(messageId, sender, text) {
	var input = document.getElementById('reply-to-input');
	var banner = document.getElementById('reply-preview-container');
	var previewSender = document.getElementById('reply-preview-sender');
	var previewText = document.getElementById('reply-preview-text');
	
	if (input && banner && previewSender && previewText) {
		input.value = messageId;
		previewSender.textContent = sender;
		
		var formattedText = text;
		var fileMatch = formattedText.match(/^\[FILE:(.+?)\|(.+?)\]$/);
		if (fileMatch) {
			formattedText = '📎 Attachment: ' + fileMatch[1];
		} else {
			// Escape html
			var tempDiv = document.createElement('div');
			tempDiv.textContent = formattedText;
			formattedText = tempDiv.innerHTML;
			
			for (var code in emoticonsMap) {
				if (emoticonsMap.hasOwnProperty(code)) {
					var escapedCode = code.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
					var regex = new RegExp(escapedCode, 'g');
					formattedText = formattedText.replace(regex, emoticonsMap[code]);
				}
			}
		}
		
		previewText.innerHTML = formattedText;
		banner.style.display = 'flex';
		
		var chatInput = document.getElementById('chat-message-editable');
		if (chatInput) chatInput.focus();
	}
}

function cancelReply() {
	var input = document.getElementById('reply-to-input');
	var banner = document.getElementById('reply-preview-container');
	if (input && banner) {
		input.value = 0;
		banner.style.display = 'none';
	}
}

// File preview handlers
function handleFileSelect(input) {
	var banner = document.getElementById('file-preview-container');
	var nameSpan = document.getElementById('file-preview-name');
	if (banner && nameSpan && input.files && input.files[0]) {
		nameSpan.textContent = input.files[0].name;
		banner.style.display = 'flex';
	}
}

function cancelFile() {
	var fileInput = document.getElementById('chat-file-input');
	var banner = document.getElementById('file-preview-container');
	if (fileInput && banner) {
		fileInput.value = '';
		banner.style.display = 'none';
	}
}

// Emoticon helper functions
function toggleEmojiTray() {
	var tray = document.getElementById('emoticon-tray-container');
	if (tray) {
		if (tray.style.display === 'none') {
			tray.style.display = 'flex';
		} else {
			tray.style.display = 'none';
		}
	}
}

var emoticonsMap = {
	"Aa@": "😊", "Bb#": "😘", "Cc$": "😡", "Dd": "😑", "Ee*": "😊",
	"Ff(": "😁", "Gg)": "😎", "Hh+": "😵", "Ii-": "😐", "Jj:": "😆",
	"Kk;": "😍", "Ll?": "😢", "Mm1": "😲", "Nn2": "🤢", "Oo3": "😒",
	"Pp4": "😜", "Qq5": "😛", "Rr6": "👍", "Ss7": "😟", "Tt8": "😮",
	"Uu9": "🤔", "Vv0": "😗", "Ww=": "😉", "Xx.": "🤏", "Yy?": "❓",
	"Zz!": "❗"
};

function emo(emoji) {
	var editable = document.getElementById('chat-message-editable');
	if (editable) {
		editable.focus();
		// Insert character
		var sel, range;
		if (window.getSelection) {
			sel = window.getSelection();
			if (sel.getRangeAt && sel.rangeCount) {
				range = sel.getRangeAt(0);
				range.deleteContents();
				var textNode = document.createTextNode(emoji);
				range.insertNode(textNode);
				range.setStartAfter(textNode);
				sel.removeAllRanges();
				sel.addRange(range);
			}
		} else if (document.selection && document.selection.createRange) {
			document.selection.createRange().text = emoji;
		} else {
			editable.innerHTML += emoji;
		}
	}
}

function emojiToCode(text) {
	if (!text) return "";
	var processed = text;
	for (var code in emoticonsMap) {
		var emoji = emoticonsMap[code];
		processed = processed.split(emoji).join(" " + code + " ");
	}
	return processed.replace(/\s+/g, ' ').trim();
}

function codeToEmoji(text) {
	if (!text) return "";
	var processed = text;
	for (var code in emoticonsMap) {
		var emoji = emoticonsMap[code];
		processed = processed.split(code).join(emoji);
	}
	return processed;
}

// Reaction triggers POST Actions handler
function sendReaction(type, messageId, reaction) {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'chat_actions.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onload = function() {
		if (xhr.status === 200) {
			try {
				var res = JSON.parse(xhr.responseText);
				if (res.success) {
					refreshChat();
				} else {
					alert(res.error);
				}
			} catch (e) {
				console.error(e);
			}
		}
	};
	xhr.send('action=react&type=' + encodeURIComponent(type) + '&message_id=' + messageId + '&reaction=' + encodeURIComponent(reaction));
}

// Unsend message handler
function unsendMessage(type, messageId) {
	if (confirm('Are you sure you want to unsend this message?')) {
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'chat_actions.php', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
			if (xhr.status === 200) {
				try {
					var res = JSON.parse(xhr.responseText);
					if (res.success) {
						refreshChat();
						refreshConversationsList();
					} else {
						alert(res.error);
					}
				} catch (e) {
					console.error(e);
				}
			}
		};
		xhr.send('action=unsend&type=' + encodeURIComponent(type) + '&message_id=' + messageId);
	}
}

// Edit message handler
function startEditMessage(type, messageId, button) {
	var chatRow = button.closest('.chat-msg-row');
	if (!chatRow) return;
	var bubble = chatRow.querySelector('.chat-bubble-self');
	if (!bubble) return;
	var rawMsg = bubble.getAttribute('data-raw-msg');
	if (!rawMsg) return;

	var editableMsg = codeToEmoji(rawMsg);
	var newMsg = prompt("Edit your message:", editableMsg);
	if (newMsg !== null && newMsg.trim() !== "" && newMsg.trim() !== editableMsg) {
		var codeMsg = emojiToCode(newMsg);
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'chat_actions.php', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
			if (xhr.status === 200) {
				try {
					var res = JSON.parse(xhr.responseText);
					if (res.success) {
						refreshChat();
						refreshConversationsList();
					} else {
						alert(res.error);
					}
				} catch (e) {
					console.error(e);
				}
			}
		};
		xhr.send('action=edit&type=' + encodeURIComponent(type) + '&message_id=' + messageId + '&message=' + encodeURIComponent(codeMsg.trim()));
	}
}

function startCall(type) {
	if (!isChatActive) return;
	
	var isGroup = (activeChatId === 'lobby');
	var roomSuffix = isGroup ? "Lobby_" + activeChatId : [myUsername, activeChatId].sort().join("_");
	var roomName = "TabinaLGU_Call_" + roomSuffix.replace(/[^a-zA-Z0-9_]/g, "");
	sessionStorage.setItem('processed_call_' + roomName, 'true');
	var callMsg = "[CALL:" + type + "|" + roomName + "]";
	
	var headerNameEl = document.querySelector('.messenger-header h6');
	var receiverName = headerNameEl ? headerNameEl.textContent.trim() : activeChatId;
	
	var headerAvatarEl = document.querySelector('.messenger-header img');
	var receiverAvatar = headerAvatarEl ? headerAvatarEl.getAttribute('src') : 'images/users/blank.jpg';
	
	// Open window synchronously to bypass popup blocker
	var callWindow = window.open('about:blank', '_blank');
	if (callWindow) {
		callWindow.document.write("<html><head><title>Connecting McJim Call...</title><style>body { background: #121212; color: #fff; font-family: -apple-system, BlinkMacSystemFont, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; } .card { text-align: center; background: #18191a; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid #2f3031; } .spinner { font-size: 32px; color: #38bdf8; margin-bottom: 20px; animation: spin 1s linear infinite; } @keyframes spin { 100% { transform: rotate(360deg); } }</style><link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'></head><body><div class='card'><img src='images/mcjimlogo.png' style='height: 45px; margin-bottom: 25px;' alt='McJim Logo'><div class='spinner'><i class='fas fa-circle-notch'></i></div><h3>Connecting Call Room...</h3><p style='color: #888; font-size: 14px;'>Establishing secure peer connection via McJim Server.</p></div></body></html>");
	}
	
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'messenger.php?ajax_send=1', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onload = function() {
		if (xhr.status === 200) {
			refreshChat();
			refreshConversationsList();
			// Launch call and redirect the opened tab
			joinJitsiCall(roomName, type, receiverName, receiverAvatar, callWindow);
		} else {
			if (callWindow) callWindow.close();
			alert("Failed to start call");
		}
	};
	xhr.onerror = function() {
		if (callWindow) callWindow.close();
		alert("Failed to start call due to network error");
	};
	xhr.send('receiver=' + encodeURIComponent(activeChatId) + '&reply_to=0&message=' + encodeURIComponent(callMsg));
}

// Custom Media Lightbox triggers
function openLightbox(src) {
	var lightbox = document.getElementById('customMediaLightbox');
	var img = document.getElementById('lightbox-image');
	var video = document.getElementById('lightbox-video');
	var isVideo = /\.(mp4|webm|ogg|mov|avi|mkv)(\?|$)/i.test(src);

	if (!lightbox || !img || !video) return;

	if (isVideo) {
		img.style.display = 'none';
		img.src = '';
		video.src = src;
		video.style.display = 'block';
		video.load();
		video.play().catch(function() {
			// Autoplay may be blocked by user agent
		});
	} else {
		video.pause();
		video.style.display = 'none';
		video.src = '';
		img.src = src;
		img.style.display = 'block';
	}

	lightbox.style.display = 'flex';
	// Force DOM reflow
	lightbox.offsetHeight;
	lightbox.classList.add('show');
}

function hideLightboxModal() {
	var lightbox = document.getElementById('customMediaLightbox');
	var img = document.getElementById('lightbox-image');
	var video = document.getElementById('lightbox-video');
	
	if (!lightbox) return;

	lightbox.classList.remove('show');
	setTimeout(function() {
		lightbox.style.display = 'none';
		if (video) {
			video.pause();
			video.src = '';
			video.style.display = 'none';
		}
		if (img) {
			img.src = '';
			img.style.display = 'none';
		}
	}, 250);
}

// Initialize polling and lists
$(document).ready(function() {
	refreshConversationsList();
	if (isChatActive) {
		refreshChat();
		scrollToBottom();
		setInterval(refreshChat, 2000);
	}
	setInterval(refreshConversationsList, 4000);
});
</script>
<?php include('footer.php'); ?>
</body>
</html>
