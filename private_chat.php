<?php
require("connect.php");

// Restrict private chat to registered logged-in users
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;
}

$me = $_SESSION['user'];

// Handle AJAX Chat Feed Refresh
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
	$client_me = isset($_GET['me']) ? $_GET['me'] : '';
	if (!isset($_SESSION['user']) || $_SESSION['user'] !== $client_me) {
		header('HTTP/1.1 401 Unauthorized');
		echo "SESSION_EXPIRED";
		exit;
	}
	$other = isset($_GET['user']) ? mysqli_real_escape_string($link, $_GET['user']) : '';
	
	if ($other !== '') {
		$emoticonArray = array(
			'Aa@' => '😊', 'Bb#' => '😘', 'Cc$' => '😡', 'Dd'  => '😑', 'Ee*' => '😊',
			'Ff(' => '😁', 'Gg)' => '😎', 'Hh+' => '😵', 'Ii-' => '😐', 'Jj:' => '😆',
			'Kk;' => '😍', 'Ll?' => '😢', 'Mm1' => '😲', 'Nn2' => '🤢', 'Oo3' => '😒',
			'Pp4' => '😜', 'Qq5' => '😛', 'Rr6' => '👍', 'Ss7' => '😟', 'Tt8' => '😮',
			'Uu9' => '🤔', 'Vv0' => '😗', 'Ww=' => '😉', 'Xx.' => '🤏', 'Yy?' => '❓',
			'Zz!' => '❗'
		);

		// Query private messages between me and other
		$me_esc = mysqli_real_escape_string($link, $me);
		$query = $link->query("SELECT * FROM private_messages WHERE (sender='$me_esc' AND receiver='$other') OR (sender='$other' AND receiver='$me_esc') ORDER BY id ASC");
		
		// Mark messages from other to me as read
		$link->query("UPDATE private_messages SET is_read = 1 WHERE sender='$other' AND receiver='$me_esc' AND is_read = 0");

		while ($msgRow = mysqli_fetch_array($query)) {
			$msgId = intval($msgRow['id']);
			$sender = $msgRow['sender'];
			$message = $msgRow['message'];
			$isUnsent = intval($msgRow['is_unsent']);

			$isOnlyImage = false;
			$msgBody = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
			foreach ($emoticonArray as $code => $location) {
				$msgBody = str_replace($code, $location, $msgBody);
			}

			if ($isUnsent) {
				$msgBody = "<i>This message was unsent</i>";
			} else {
				// Parse file downloads
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
			} elseif (preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', $trimmedMsg, $matches)) {
				$fileName = htmlspecialchars($matches[1]);
				$filePath = htmlspecialchars($matches[2]);
				$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
				
				if (in_array($fileExt, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
					$isOnlyImage = true;
					$msgBody = "<a href='" . $filePath . "' onclick='openLightbox(\"" . addslashes($filePath) . "\"); return false;'><img src='" . $filePath . "' class='img-fluid shadow-xs chat-attachment-img' style='max-width: min(100%, 300px); max-height: 300px; width: auto; height: auto; display: block; cursor: zoom-in; border-radius: 24px !important;' alt='Attached Image'/></a>";
				} elseif (in_array($fileExt, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'])) {
					$isOnlyImage = true;
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

					$msgBody = "<div class='position-relative shadow-xs' style='max-width: min(100%, 300px); display: inline-block; cursor: pointer; overflow: hidden; border-radius: 24px !important;' onclick='openLightbox(\"" . addslashes($filePath) . "\"); return false;'>"
							 . "  <video src='" . $filePath . "#t=0.1'" . $posterAttr . " style='width: 100%; max-width: 300px; aspect-ratio: 3/2; object-fit: cover; display: block; border-radius: 24px !important;' preload='auto' muted playsinline></video>"
							 . "  <div class='position-absolute d-flex align-items-center justify-content-center' style='top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.35); color: #fff; font-size: 32px;'>"
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
			}

			$senderDisplay = $isSelf ? $me : $other;
			$timestamp = date('h:i A', strtotime($msgRow['sent_at']));

			// Parse reply block
			$replyBlock = '';
			if ($msgRow['reply_to'] !== null && !$isUnsent) {
				$parentQuery = $link->query("SELECT sender, message, is_unsent FROM private_messages WHERE id = " . intval($msgRow['reply_to']));
				if (mysqli_num_rows($parentQuery) > 0) {
					$parentRow = mysqli_fetch_array($parentQuery);
					$parentSender = htmlspecialchars($parentRow['sender']);
					$parentMsg = $parentRow['is_unsent'] ? '<i>This message was unsent</i>' : htmlspecialchars($parentRow['message']);
					if (preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', $parentRow['message'], $m)) {
						$fileName = htmlspecialchars($m[1]);
						$filePath = htmlspecialchars($m[2]);
						$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
						if (in_array($fileExt, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
							$parentMsg = '📎 Attachment: <a href="' . $filePath . '" class="reply-attachment-link" onclick="openLightbox(\'' . addslashes($filePath) . '\'); return false;" style="text-decoration: underline; color: inherit; font-weight: bold;">' . truncateFileName($fileName) . '</a>';
						} elseif (in_array($fileExt, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'])) {
							$parentMsg = '📎 Attachment: <a href="' . $filePath . '" class="reply-attachment-link" onclick="openLightbox(\'' . addslashes($filePath) . '\'); return false;" style="text-decoration: underline; color: inherit; font-weight: bold;">' . truncateFileName($fileName) . '</a>';
						} else {
							$parentMsg = '📎 Attachment: <a href="' . $filePath . '" download class="reply-attachment-link" style="text-decoration: underline; color: inherit; font-weight: bold;">' . truncateFileName($fileName) . '</a>';
						}
					} else {
						foreach ($emoticonArray as $code => $location) {
							$parentMsg = str_replace($code, $location, $parentMsg);
						}
					}
					$replyColorStyle = $isSelf ? 'color: rgba(255, 255, 255, 0.85);' : 'color: #6c757d;';
					$replyBorderClass = $isSelf ? 'border-white' : 'border-primary';
					$replyBlock = "<div class='border-left $replyBorderClass pl-2 mb-1 text-left small' style='font-size:11px; border-width:3px !important; background:rgba(0,0,0,0.03); padding:2px 5px; border-radius:2px; $replyColorStyle'>"
								. "<strong>@$parentSender</strong>: $parentMsg"
								. "</div>";
				}
			}

			// Query reactions
			$reactionsHtml = '';
			if (!$isUnsent) {
				$reactionsQuery = $link->query("SELECT reaction, COUNT(*), GROUP_CONCAT(username) FROM message_reactions WHERE message_type='private' AND message_id=" . intval($msgId) . " GROUP BY reaction");
				if (mysqli_num_rows($reactionsQuery) > 0) {
					$reactionsHtml = "<div class='reaction-bubble-badge d-flex align-items-center border rounded-pill shadow-sm px-2 py-0.5 position-absolute' style='bottom: -11px; " . ($isSelf ? "left: 12px;" : "right: 12px;") . " z-index: 5; height: 20px; line-height: 1;'>";
					while ($reactRow = mysqli_fetch_array($reactionsQuery)) {
						$emoji = htmlspecialchars($reactRow[0]);
						$count = intval($reactRow[1]);
						$usernames = htmlspecialchars($reactRow[2]);
						$hasReacted = strpos(strtolower($usernames), strtolower($me)) !== false ? 'text-primary' : 'text-muted';
						
						$reactionsHtml .= "<span class='reaction-pill-item d-inline-flex align-items-center $hasReacted' style='cursor:pointer; font-size:11px; margin-right:6px;' title='$usernames' onclick='sendReaction(\"private\", " . $msgId . ", \"$emoji\"); return false;'>\n"
										. "                      $emoji<span class='ml-1 font-weight-bold' style='font-size:9.5px; color:inherit;'>$count</span>\n"
										. "                    </span>";
					}
					$reactionsHtml .= "</div>";
				}
			}

			// Message controls (reply, unsend, react dropdown)
			$controlsHtml = '';
			if (!$isUnsent) {
				$escapedSender = addslashes(htmlspecialchars($sender));
				$escapedMsg = addslashes(htmlspecialchars(substr($message, 0, 40)));
				
			$controlsHtml = "<div class='chat-msg-controls d-inline-flex align-items-center ml-2 mr-2' style='font-size: 13px; vertical-align: middle;'>"
						  . "<div class='dropdown d-inline-block position-relative'>"
						  . "  <button class='btn btn-xs btn-link text-muted p-0' onclick='toggleReactionMenu(event, this); return false;' title='React' style='font-size:14px; box-shadow:none;'><i class='far fa-smile'></i></button>"
						  . "  <div class='reaction-menu-popup d-none p-1 border shadow-sm bg-white position-absolute' style='bottom: 24px; left: 0; white-space: nowrap; border-radius: 20px; z-index: 1000;'>"
						  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"👍\"); return false;'>👍</a>"
						  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"❤️\"); return false;'>❤️</a>"
						  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"😂\"); return false;'>😂</a>"
						  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"😮\"); return false;'>😮</a>"
						  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"😢\"); return false;'>😢</a>"
						  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"😡\"); return false;'>😡</a>"
						  . "  </div>"
						  . "</div>"
						  . "<button class='btn btn-xs btn-link text-muted p-0 ml-2' title='Reply' onclick='setReply($msgId, \"$escapedSender\", \"$escapedMsg\"); return false;' style='font-size:14px; box-shadow:none;'><i class='fas fa-reply'></i></button>";
							  
				if ($isSelf) {
					$controlsHtml .= "<button class='btn btn-xs btn-link text-danger p-0 ml-2' title='Unsend' onclick='unsendMessage(\"private\", $msgId); return false;' style='font-size:14px; box-shadow:none;'><i class='far fa-trash-alt'></i></button>";
				}
				$controlsHtml .= "</div>";
			}

			if ($isSelf) {
				$bubbleClass = $isOnlyImage ? "p-0 shadow-sm text-left d-inline-block position-relative" : "p-2 px-3 bg-primary text-white shadow-sm text-left d-inline-block position-relative";
				$bubbleStyle = $isOnlyImage ? "background: transparent; border: none; border-radius: 20px; overflow: visible; line-height: 1;" : "border-radius: 20px; font-size: 13.5px; line-height: 1.5;";
				
				echo "<div class='chat-msg-row d-flex justify-content-end flex-wrap mb-3' data-msg-id='{$msgId}'>";
				echo "  <div class='text-right' style='max-width: 75%;'>";
				echo "    <div class='small text-muted font-weight-bold mb-1'>" . htmlentities($senderDisplay) . " <span class='font-weight-normal' style='font-size:10px;'>$timestamp</span></div>";
				echo "    <div class='d-flex align-items-center justify-content-end flex-wrap'>";
				echo "      " . $controlsHtml;
				echo "      <div class='$bubbleClass' style='$bubbleStyle'>";
				echo "        " . $replyBlock;
				echo "        <span style='vertical-align: middle;'>" . $msgBody . "</span>";
				echo "        " . $reactionsHtml;
				echo "      </div>";
				echo "    </div>";
				echo "  </div>";
				echo "</div>";
			} else {
				$bubbleClass = $isOnlyImage ? "p-0 shadow-sm text-left d-inline-block position-relative" : "p-2 px-3 bg-light text-dark border shadow-sm d-inline-block position-relative";
				$bubbleStyle = $isOnlyImage ? "background: transparent; border: none; border-radius: 20px; overflow: visible; line-height: 1;" : "border-radius: 20px; font-size: 13.5px; line-height: 1.5;";
				
				$recentCallAttr = '';
				if (strpos($message, '[CALL:') !== false && (time() - strtotime($msgRow['sent_at'])) < 30) {
					$recentCallAttr = " data-incoming-call-recent='true'";
				}
				echo "<div class='chat-msg-row d-flex justify-content-start flex-wrap mb-3' data-msg-id='{$msgId}'{$recentCallAttr}>";
				echo "  <div class='text-left' style='max-width: 75%;'>";
				echo "    <div class='small text-muted font-weight-bold mb-1'>" . htmlentities($senderDisplay) . " <span class='font-weight-normal' style='font-size:10px;'>$timestamp</span></div>";
				echo "    <div class='d-flex align-items-center justify-content-start flex-wrap'>";
				echo "      <div class='$bubbleClass' style='$bubbleStyle'>";
				echo "        " . $replyBlock;
				echo "        <span style='vertical-align: middle;'>" . $msgBody . "</span>";
				echo "        " . $reactionsHtml;
				echo "      </div>";
				echo "      " . $controlsHtml;
				echo "    </div>";
				echo "  </div>";
				echo "</div>";
			}
		}
	}
	exit;
}

// Handle AJAX Online Status Refresh
if (isset($_GET['ajax_users']) && $_GET['ajax_users'] === '1') {
	$client_me = isset($_GET['me']) ? $_GET['me'] : '';
	if (!isset($_SESSION['user']) || $_SESSION['user'] !== $client_me) {
		header('HTTP/1.1 401 Unauthorized');
		echo "SESSION_EXPIRED";
		exit;
	}
	$selectedOther = isset($_GET['user']) ? $_GET['user'] : '';
	$me_esc = mysqli_real_escape_string($link, $me);
	
	$query = $link->query("SELECT u.username AS user, u.imgUrl, u.fullname, (SELECT COUNT(*) FROM users_sessions WHERE username = u.username AND logout_time IS NULL AND last_active >= DATE_SUB(NOW(), INTERVAL 60 SECOND)) AS is_online FROM users AS u WHERE u.username != '$me_esc' ORDER BY u.fullname ASC");
	while ($rs = mysqli_fetch_array($query)){
		$avatarPath = "images/users/" . $rs['imgUrl'];
		if (empty($rs['imgUrl']) || !file_exists($avatarPath)) {
			$avatarPath = "images/users/blank.jpg";
		}
		
		$isOnline = intval($rs['is_online']) > 0;
		$isActiveChat = (strtolower(trim($rs['user'])) === strtolower(trim($selectedOther))) ? 'active bg-primary text-white' : '';
		$textClass = (strtolower(trim($rs['user']))    === strtolower(trim($selectedOther))) ? 'text-white' : 'text-dark';
		$mutedClass = (strtolower(trim($rs['user']))   === strtolower(trim($selectedOther))) ? 'text-white-50' : 'text-muted';

		// Count unread messages from this user
		$sender_esc = mysqli_real_escape_string($link, $rs['user']);
		$unreadQuery = $link->query("SELECT COUNT(*) FROM private_messages WHERE sender='$sender_esc' AND receiver='$me_esc' AND is_read = 0");
		$unreadRow = mysqli_fetch_array($unreadQuery);
		$unreadCount = $unreadRow[0];

		$borderClass = $isOnline ? 'border-success' : 'border-secondary';
		echo "<a href='private_chat.php?user=" . urlencode($rs['user']) . "' class='list-group-item list-group-item-action d-flex align-items-center py-2 px-3 border-0 rounded mb-1 " . $isActiveChat . "'>";
		echo "  <img src='" . htmlspecialchars($avatarPath) . "' class='rounded-circle mr-2 border " . $borderClass . "' style='width: 32px; height: 32px; object-fit: cover;' alt='Avatar'>";
		echo "  <div class='d-flex flex-column'>";
		echo "    <span class='font-weight-bold " . $textClass . "' style='font-size: 13px; line-height: 1.2;'>" . htmlentities($rs['fullname']) . "</span>";
		echo "    <span class='" . $mutedClass . "' style='font-size: 11px;'>@" . htmlentities($rs['user']) . "</span>";
		echo "  </div>";
		if ($unreadCount > 0) {
			echo "  <span class='badge badge-danger badge-pill ml-auto px-2 py-1 font-weight-bold' style='font-size: 10px;'>" . $unreadCount . "</span>";
		} else {
			if ($isOnline) {
				echo "  <span class='badge badge-success ml-auto p-1' style='width: 8px; height: 8px; border-radius: 50%;' title='Online'>&nbsp;</span>";
			} else {
				echo "  <span class='badge badge-secondary ml-auto p-1' style='width: 8px; height: 8px; border-radius: 50%; background-color: #adb5bd;' title='Offline'>&nbsp;</span>";
			}
		}
		echo "</a>";
	}
	exit;
}

// Handle Message Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$receiver = isset($_POST['receiver']) ? mysqli_real_escape_string($link, $_POST['receiver']) : '';
	$message = isset($_POST['message']) ? stripslashes($_POST['message']) : '';
	$replyTo = isset($_POST['reply_to']) && intval($_POST['reply_to']) > 0 ? intval($_POST['reply_to']) : 'NULL';
	
	// If a file is uploaded
	if (isset($_FILES['chat_file']) && $_FILES['chat_file']['error'] == UPLOAD_ERR_OK) {
		$uploadDir = 'uploads/';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}
		
		$tmpName = $_FILES['chat_file']['tmp_name'];
		$originalName = basename($_FILES['chat_file']['name']);
		$fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
		
		// Generate a unique name
		$newName = time() . '_' . uniqid() . '.' . $fileExt;
		$targetPath = $uploadDir . $newName;
		
		if (move_uploaded_file($tmpName, $targetPath)) {
			$fileMarkup = "[FILE:" . $originalName . "|" . $targetPath . "]";
			$message = trim($message) !== '' ? $fileMarkup . " " . $message : $fileMarkup;
		}
	}
	
	if ($receiver != '' && trim($message) != '') {
		$me_esc = mysqli_real_escape_string($link, $me);
		$message_esc = mysqli_real_escape_string($link, $message);
		
		$insQuery = "INSERT INTO private_messages (sender, receiver, message, reply_to) VALUES ('$me_esc', '$receiver', '$message_esc', $replyTo)";
		if ($link->query($insQuery)) {
			if (isset($_GET['ajax_send'])) {
				echo "OK";
				exit;
			}
			// Redirect to avoid form resubmission
			header("Location: private_chat.php?user=" . urlencode($receiver));
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

// Automatically redirect to the first contact if no user is selected
$other = isset($_GET['user']) ? $_GET['user'] : '';
if ($other === '') {
	$me_esc = mysqli_real_escape_string($link, $me);
	$firstUserQuery = $link->query("SELECT username FROM users WHERE username != '$me_esc' ORDER BY fullname ASC LIMIT 1");
	if ($firstUserQuery && mysqli_num_rows($firstUserQuery) > 0) {
		$firstUserRow = mysqli_fetch_array($firstUserQuery);
		$firstUser = $firstUserRow['username'];
		header("Location: private_chat.php?user=" . urlencode($firstUser));
		exit;
	}
}

require("header.php");
require("menu.php");

$other_escaped = mysqli_real_escape_string($link, $other);

// Fetch Recipient Profile
$otherProfile = null;
if ($other !== '') {
	$profileQuery = $link->query("SELECT * FROM users WHERE username = '$other_escaped'");
	$otherProfile = mysqli_fetch_array($profileQuery);
}
?>

<script> if (typeof setActive === 'function') { setActive("chat"); setActive("privatedm"); } </script>
<style>
	@media screen and (max-width:720px){
		.chid { display:none; }
	}
	html, body {
		height: 100%;
		overflow: hidden !important;
	}
	.messenger-window {
		height: calc(100vh - 55px) !important;
		height: calc(100dvh - 55px) !important;
	}
	@media (max-width: 767.98px) {
		.container-fluid-chat-wrapper {
			margin-top: 50px !important;
		}
		.messenger-window {
			height: calc(100vh - 50px) !important;
			height: calc(100dvh - 50px) !important;
			min-height: 0 !important;
		}
	}
</style>

<div class="container-fluid p-0 container-fluid-chat-wrapper" style="margin-top: 55px;">
	<!-- Unified Messenger Window -->
	<div class="d-flex overflow-hidden bg-white messenger-window" style="min-height: 500px; border: none; border-radius: 0;">
		
		<!-- Left Pane: Conversations / Direct Chat Lists -->
		<div class="col-md-3 d-none d-md-flex flex-column p-0 bg-light border-right h-100 mobile-pane-overlay mobile-left-pane">
			<div class="p-3 bg-white border-bottom">
				<h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-paper-plane text-primary mr-2"></i>Private Messages</h5>
			</div>
			<div class="px-3 py-2 bg-white border-bottom shadow-xs">
				<a href="chat_rooms.php" class="btn btn-block btn-sm btn-outline-primary font-weight-bold rounded-pill">
					<i class="fas fa-arrow-left mr-1"></i>Back to Group Chat
				</a>
			</div>
			<div class="flex-grow-1 overflow-y-auto p-2 list-users-container" style="overflow-y: auto;">
				<div class="list-group list-group-flush" id="online-users-pm-list">
					<?php
						$me_esc = mysqli_real_escape_string($link, $me);
						$query = $link->query("SELECT u.username AS user, u.imgUrl, u.fullname, (SELECT COUNT(*) FROM users_sessions WHERE username = u.username AND logout_time IS NULL AND last_active >= DATE_SUB(NOW(), INTERVAL 60 SECOND)) AS is_online FROM users AS u WHERE u.username != '$me_esc' ORDER BY u.fullname ASC");
						while ($rs = mysqli_fetch_array($query)){
							$avatarPath = "images/users/" . $rs['imgUrl'];
							if (empty($rs['imgUrl']) || !file_exists($avatarPath)) {
								$avatarPath = "images/users/blank.jpg";
							}

							$isOnline = intval($rs['is_online']) > 0;
							$isActiveChat = (strtolower(trim($rs['user'])) === strtolower(trim($other))) ? 'active bg-primary text-white' : '';
							$textClass = (strtolower(trim($rs['user'])) === strtolower(trim($other))) ? 'text-white' : 'text-dark';
							$mutedClass = (strtolower(trim($rs['user'])) === strtolower(trim($other))) ? 'text-white-50' : 'text-muted';

							// Count unread messages from this user
							$sender_esc = mysqli_real_escape_string($link, $rs['user']);
							$unreadQuery = $link->query("SELECT COUNT(*) FROM private_messages WHERE sender='$sender_esc' AND receiver='$me_esc' AND is_read = 0");
							$unreadRow = mysqli_fetch_array($unreadQuery);
							$unreadCount = $unreadRow[0];

							$borderClass = $isOnline ? 'border-success' : 'border-secondary';
							echo "<a href='private_chat.php?user=" . urlencode($rs['user']) . "' class='list-group-item list-group-item-action d-flex align-items-center py-2 px-3 border-0 rounded mb-1 " . $isActiveChat . "'>";
							echo "  <img src='" . htmlspecialchars($avatarPath) . "' class='rounded-circle mr-2 border " . $borderClass . "' style='width: 32px; height: 32px; object-fit: cover;' alt='Avatar'>";
							echo "  <div class='d-flex flex-column'>";
							echo "    <span class='font-weight-bold " . $textClass . "' style='font-size: 13px; line-height: 1.2;'>" . htmlentities($rs['fullname']) . "</span>";
							echo "    <span class='" . $mutedClass . "' style='font-size: 11px;'>@" . htmlentities($rs['user']) . "</span>";
							echo "  </div>";
							if ($unreadCount > 0) {
								echo "  <span class='badge badge-danger badge-pill ml-auto px-2 py-1 font-weight-bold' style='font-size: 10px;'>" . $unreadCount . "</span>";
							} else {
								if ($isOnline) {
									echo "  <span class='badge badge-success ml-auto p-1' style='width: 8px; height: 8px; border-radius: 50%;' title='Online'>&nbsp;</span>";
								} else {
									echo "  <span class='badge badge-secondary ml-auto p-1' style='width: 8px; height: 8px; border-radius: 50%; background-color: #adb5bd;' title='Offline'>&nbsp;</span>";
								}
							}
							echo "</a>";
						}
					?>
				</div>
			</div>
		</div>

		<!-- Middle Pane: Active Direct Conversation -->
		<div class="col-12 col-md-6 d-flex flex-column p-0 h-100 bg-white">
			<?php if ($other !== '' && $otherProfile): ?>
				<!-- Chat Header -->
				<div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
					<div class="d-flex align-items-center">
						<button class="btn btn-sm btn-light border d-md-none mr-2" onclick="toggleLeftPane()"><i class="fas fa-bars"></i></button>
						<?php
							$avatarPath = "images/users/" . $otherProfile['imgUrl'];
							if (empty($otherProfile['imgUrl']) || !file_exists($avatarPath)) {
								$avatarPath = "images/users/blank.jpg";
							}
							
							// Check online status of the other user
							$other_esc = mysqli_real_escape_string($link, $other);
							$other_online_query = $link->query("SELECT COUNT(*) FROM users_sessions WHERE username = '$other_esc' AND logout_time IS NULL AND last_active >= DATE_SUB(NOW(), INTERVAL 60 SECOND)");
							$other_online_row = mysqli_fetch_array($other_online_query);
							$is_other_online = intval($other_online_row[0]) > 0;
							$headerBorderClass = $is_other_online ? 'border-success' : 'border-secondary';
						?>
						<img id="chat-header-avatar" src="<?php echo htmlspecialchars($avatarPath); ?>" class="rounded-circle mr-2 border <?php echo $headerBorderClass; ?>" style="width: 35px; height: 35px; object-fit: cover;" alt="Avatar">
						<div>
							<h6 class="chid font-weight-bold text-dark mb-0"><?php echo htmlentities($otherProfile['fullname']); ?></h6>
							<?php if ($is_other_online): ?>
								<small id="chat-header-status" class="text-success font-weight-bold"><i class="fas fa-circle mr-1 animate-pulse" style="font-size: 8px;"></i><?php echo htmlentities($otherProfile['username']); ?></small>
							<?php else: ?>
								<small id="chat-header-status" class="text-secondary font-weight-bold"><i class="fas fa-circle mr-1" style="font-size: 8px; color: #adb5bd;"></i><?php echo htmlentities($otherProfile['username']); ?></small>
							<?php endif; ?>
						</div>
					</div>
					<div class="d-flex">
						<a href="chat_rooms.php" class="btn btn-sm btn-outline-primary font-weight-bold rounded-pill mr-2">
							<i class="fas fa-arrow-left mr-1"></i>Group
						</a>
						<a href="entrance.php" class="btn btn-sm btn-outline-secondary font-weight-bold rounded-pill d-none d-md-inline-block">
							<i class="fas fa-home"></i>
						</a>
						<button class="btn btn-sm btn-light border d-md-none ml-2" onclick="toggleRightPane()"><i class="fas fa-users"></i></button>
					</div>
				</div>

				<!-- Message Feed -->
				<div class="flex-grow-1 p-3 chat-feed-container" style="overflow-y: auto; background-color: #f7f9fa;">
					<!-- Message Bubble List -->
					<div class="chat-messages-container">
						<?php
							if ($other !== '') {
								$emoticonArray = array(
									'Aa@' => '😊', 'Bb#' => '😘', 'Cc$' => '😡', 'Dd'  => '😑', 'Ee*' => '😊',
									'Ff(' => '😁', 'Gg)' => '😎', 'Hh+' => '😵', 'Ii-' => '😐', 'Jj:' => '😆',
									'Kk;' => '😍', 'Ll?' => '😢', 'Mm1' => '😲', 'Nn2' => '🤢', 'Oo3' => '😒',
									'Pp4' => '😜', 'Qq5' => '😛', 'Rr6' => '👍', 'Ss7' => '😟', 'Tt8' => '😮',
									'Uu9' => '🤔', 'Vv0' => '😗', 'Ww=' => '😉', 'Xx.' => '🤏', 'Yy?' => '❓',
									'Zz!' => '❗'
								);

								$me_esc = mysqli_real_escape_string($link, $me);
								$other_esc = mysqli_real_escape_string($link, $other);
								$query = $link->query("SELECT * FROM private_messages WHERE (sender='$me_esc' AND receiver='$other_esc') OR (sender='$other_esc' AND receiver='$me_esc') ORDER BY id ASC");
								
								// Mark messages from other to me as read
								$link->query("UPDATE private_messages SET is_read = 1 WHERE sender='$other_esc' AND receiver='$me_esc' AND is_read = 0");

								if ($query) {
									while ($msgRow = mysqli_fetch_array($query)) {
										$msgId = intval($msgRow['id']);
										$sender = $msgRow['sender'];
										$isSelf = (strtolower(trim($sender)) === strtolower(trim($me)));
										$message = $msgRow['message'];
										$isUnsent = intval($msgRow['is_unsent']);

											$isOnlyImage = false;
											$msgBody = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
											foreach ($emoticonArray as $code => $location) {
												$msgBody = str_replace($code, $location, $msgBody);
											}

											if ($isUnsent) {
												$msgBody = "<i>This message was unsent</i>";
											} else {
												// Parse file downloads
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
												} elseif (preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', $trimmedMsg, $matches)) {
													$fileName = htmlspecialchars($matches[1]);
													$filePath = htmlspecialchars($matches[2]);
													$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
													
													if (in_array($fileExt, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
														$isOnlyImage = true;
														$msgBody = "<a href='" . $filePath . "' onclick='openLightbox(\"" . addslashes($filePath) . "\"); return false;'><img src='" . $filePath . "' class='img-fluid shadow-xs chat-attachment-img' style='max-width: min(100%, 300px); max-height: 300px; width: auto; height: auto; display: block; cursor: zoom-in; border-radius: 24px !important;' alt='Attached Image'/></a>";
													} elseif (in_array($fileExt, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'])) {
														$isOnlyImage = true;
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

														$msgBody = "<div class='position-relative shadow-xs' style='max-width: min(100%, 300px); display: inline-block; cursor: pointer; overflow: hidden; border-radius: 24px !important;' onclick='openLightbox(\"" . addslashes($filePath) . "\"); return false;'>"
																 . "  <video src='" . $filePath . "#t=0.1'" . $posterAttr . " style='width: 100%; max-width: 300px; aspect-ratio: 3/2; object-fit: cover; display: block; border-radius: 24px !important;' preload='auto' muted playsinline></video>"
																 . "  <div class='position-absolute d-flex align-items-center justify-content-center' style='top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.35); color: #fff; font-size: 32px;'>"
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
											}

										$senderDisplay = $isSelf ? $me : $other;
										$timestamp = date('h:i A', strtotime($msgRow['sent_at']));

										// Parse reply block
										$replyBlock = '';
										if ($msgRow['reply_to'] !== null && !$isUnsent) {
											$parentQuery = $link->query("SELECT sender, message, is_unsent FROM private_messages WHERE id = " . intval($msgRow['reply_to']));
											if (mysqli_num_rows($parentQuery) > 0) {
												$parentRow = mysqli_fetch_array($parentQuery);
												$parentSender = htmlspecialchars($parentRow['sender']);
												$parentMsg = $parentRow['is_unsent'] ? '<i>This message was unsent</i>' : htmlspecialchars($parentRow['message']);
												if (preg_match('/^\[FILE:(.+?)\|(.+?)\]$/', $parentRow['message'], $m)) {
													$fileName = htmlspecialchars($m[1]);
													$filePath = htmlspecialchars($m[2]);
													$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
													if (in_array($fileExt, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
														$parentMsg = '📎 Attachment: <a href="' . $filePath . '" class="reply-attachment-link" onclick="openLightbox(\'' . addslashes($filePath) . '\'); return false;" style="text-decoration: underline; color: inherit; font-weight: bold;">' . truncateFileName($fileName) . '</a>';
													}elseif (in_array($fileExt, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'])) {
														$parentMsg = '📎 Attachment: <a href="' . $filePath . '" class="reply-attachment-link" onclick="openLightbox(\'' . addslashes($filePath) . '\'); return false;" style="text-decoration: underline; color: inherit; font-weight: bold;">' . truncateFileName($fileName) . '</a>';
													} else {
														$parentMsg = '📎 Attachment: <a href="' . $filePath . '" download class="reply-attachment-link" style="text-decoration: underline; color: inherit; font-weight: bold;">' . truncateFileName($fileName) . '</a>';
													}
												} else {
													foreach ($emoticonArray as $code => $location) {
														$parentMsg = str_replace($code, $location, $parentMsg);
													}
												}
												$replyColorStyle = $isSelf ? 'color: rgba(255, 255, 255, 0.85);' : 'color: #6c757d;';
												$replyBorderClass = $isSelf ? 'border-white' : 'border-primary';
												$replyBlock = "<div class='border-left $replyBorderClass pl-2 mb-1 text-left small' style='font-size:11px; border-width:3px !important; background:rgba(0,0,0,0.03); padding:2px 5px; border-radius:2px; $replyColorStyle'>"
															. "<strong>@$parentSender</strong>: $parentMsg"
															. "</div>";
											}
										}

										// Query reactions
										$reactionsHtml = '';
										if (!$isUnsent) {
											$reactionsQuery = $link->query("SELECT reaction, COUNT(*), GROUP_CONCAT(username) FROM message_reactions WHERE message_type='private' AND message_id=" . intval($msgId) . " GROUP BY reaction");
											if (mysqli_num_rows($reactionsQuery) > 0) {
												$reactionsHtml = "<div class='reaction-bubble-badge d-flex align-items-center border rounded-pill shadow-sm px-2 py-0.5 position-absolute' style='bottom: -11px; " . ($isSelf ? "left: 12px;" : "right: 12px;") . " z-index: 5; height: 20px; line-height: 1;'>";
												while ($reactRow = mysqli_fetch_array($reactionsQuery)) {
													$emoji = htmlspecialchars($reactRow[0]);
													$count = intval($reactRow[1]);
													$usernames = htmlspecialchars($reactRow[2]);
													$hasReacted = strpos(strtolower($usernames), strtolower($me)) !== false ? 'text-primary' : 'text-muted';
													
													$reactionsHtml .= "<span class='reaction-pill-item d-inline-flex align-items-center $hasReacted' style='cursor:pointer; font-size:11px; margin-right:6px;' title='$usernames' onclick='sendReaction(\"private\", " . $msgId . ", \"$emoji\"); return false;'>\n"
																	. "                      $emoji<span class='ml-1 font-weight-bold' style='font-size:9.5px; color:inherit;'>$count</span>\n"
																	. "                    </span>";
												}
												$reactionsHtml .= "</div>";
											}
										}

										// Message controls (reply, unsend, react dropdown)
										$controlsHtml = '';
										if (!$isUnsent) {
											$escapedSender = addslashes(htmlspecialchars($sender));
											$escapedMsg = addslashes(htmlspecialchars(substr($message, 0, 40)));
											
											$controlsHtml = "<div class='chat-msg-controls d-inline-flex align-items-center ml-2 mr-2' style='font-size: 13px; vertical-align: middle;'>"
														  . "<div class='dropdown d-inline-block position-relative'>"
														  . "  <button class='btn btn-xs btn-link text-muted p-0' onclick='toggleReactionMenu(event, this); return false;' title='React' style='font-size:14px; box-shadow:none;'><i class='far fa-smile'></i></button>"
														  . "  <div class='reaction-menu-popup d-none p-1 border shadow-sm bg-white position-absolute' style='bottom: 24px; left: 0; white-space: nowrap; border-radius: 20px; z-index: 1000;'>"
														  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"👍\"); return false;'>👍</a>"
														  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"❤️\"); return false;'>❤️</a>"
														  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"😂\"); return false;'>😂</a>"
														  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"😮\"); return false;'>😮</a>"
														  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"😢\"); return false;'>😢</a>"
														  . "    <a class='d-inline-block p-1' href='#' style='font-size:16px; width:28px; text-align:center; box-shadow:none; text-decoration:none;' onclick='sendReaction(\"private\", $msgId, \"😡\"); return false;'>😡</a>"
														  . "  </div>"
														  . "</div>"
														  . "<button class='btn btn-xs btn-link text-muted p-0 ml-2' title='Reply' onclick='setReply($msgId, \"$escapedSender\", \"$escapedMsg\"); return false;' style='font-size:14px; box-shadow:none;'><i class='fas fa-reply'></i></button>";
												  
											if ($isSelf) {
												$controlsHtml .= "<button class='btn btn-xs btn-link text-danger p-0 ml-2' title='Unsend' onclick='unsendMessage(\"private\", $msgId); return false;' style='font-size:14px; box-shadow:none;'><i class='far fa-trash-alt'></i></button>";
											}
											$controlsHtml .= "</div>";
										}

										if ($isSelf) {
											$bubbleClass = $isOnlyImage ? "p-0 shadow-sm text-left d-inline-block position-relative" : "p-2 px-3 bg-primary text-white shadow-sm text-left d-inline-block position-relative";
											$bubbleStyle = $isOnlyImage ? "background: transparent; border: none; border-radius: 20px; overflow: visible; line-height: 1;" : "border-radius: 20px; font-size: 13.5px; line-height: 1.5;";
											
											echo "<div class='chat-msg-row d-flex justify-content-end flex-wrap mb-3' data-msg-id='{$msgId}'>";
											echo "  <div class='text-right' style='max-width: 75%;'>";
											echo "    <div class='small text-muted font-weight-bold mb-1'>" . htmlentities($senderDisplay) . " <span class='font-weight-normal' style='font-size:10px;'>$timestamp</span></div>";
											echo "    <div class='d-flex align-items-center justify-content-end flex-wrap flex-wrap'>";
											echo "      " . $controlsHtml;
											echo "      <div class='$bubbleClass' style='$bubbleStyle'>";
											echo "        " . $replyBlock;
											echo "        <span style='vertical-align: middle;'>" . $msgBody . "</span>";
											echo "        " . $reactionsHtml;
											echo "      </div>";
											echo "    </div>";
											echo "  </div>";
											echo "</div>";
										} else {
											$bubbleClass = $isOnlyImage ? "p-0 shadow-sm text-left d-inline-block position-relative" : "p-2 px-3 bg-light text-dark border shadow-sm d-inline-block position-relative";
											$bubbleStyle = $isOnlyImage ? "background: transparent; border: none; border-radius: 20px; overflow: visible; line-height: 1;" : "border-radius: 20px; font-size: 13.5px; line-height: 1.5;";
											
											$recentCallAttr = '';
											if (strpos($message, '[CALL:') !== false && (time() - strtotime($msgRow['sent_at'])) < 30) {
												$recentCallAttr = " data-incoming-call-recent='true'";
											}
											echo "<div class='chat-msg-row d-flex justify-content-start flex-wrap mb-3' data-msg-id='{$msgId}'{$recentCallAttr}>";
											echo "  <div class='text-left' style='max-width: 75%;'>";
											echo "    <div class='small text-muted font-weight-bold mb-1'>" . htmlentities($senderDisplay) . " <span class='font-weight-normal' style='font-size:10px;'>$timestamp</span></div>";
											echo "    <div class='d-flex align-items-center justify-content-start flex-wrap flex-wrap'>";
											echo "      <div class='$bubbleClass' style='$bubbleStyle'>";
											echo "        " . $replyBlock;
											echo "        <span style='vertical-align: middle;'>" . $msgBody . "</span>";
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
						?>
					</div>
				</div>

				<!-- Input Footer -->
				<div class="p-3 bg-white border-top" style="position: sticky; bottom: 0; z-index: 10;">
					<form action="" method="post" name="postform" enctype="multipart/form-data" class="m-0" id="chat-form-element">
						<input type="hidden" name="receiver" value="<?php echo htmlentities($other); ?>"/>
						<input type="hidden" name="reply_to" id="reply-to-input" value="0"/>

						<!-- Reply Preview Banner -->
						<div class="reply-preview-banner shadow-xs" id="reply-preview-container" style="display: none; margin-bottom: 8px;">
							<div class="small text-muted text-left" style="font-size: 11px; line-height: 1.2;">
								<i class="fas fa-reply mr-1"></i>Replying to <span id="reply-preview-sender" class="font-weight-bold"></span>:
								<div id="reply-preview-text" class="text-truncate" style="max-width: 250px;"></div>
							</div>
							<button type="button" class="close font-weight-bold" onclick="cancelReply()" style="font-size: 16px; outline: none; box-shadow: none;">&times;</button>
						</div>

						<!-- File Preview Banner -->
						<div class="file-preview-banner shadow-xs" id="file-preview-container" style="display: none; margin-bottom: 8px; background: rgba(0, 123, 255, 0.08); border-left: 4px solid #007bff; border-radius: 4px; padding: 6px 12px; align-items: center; justify-content: space-between;">
							<div class="small text-primary text-left" style="font-size: 11px; line-height: 1.2;">
								<i class="fas fa-paperclip mr-1"></i>Attachment: <span id="file-preview-name" class="font-weight-bold"></span>
							</div>
							<button type="button" class="close text-primary font-weight-bold" onclick="cancelFile()" style="font-size: 16px; outline: none; box-shadow: none; opacity: 0.8; border: none; background: transparent;">&times;</button>
						</div>

						<!-- Input Group -->
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('chat-file-input').click()" style="border-radius: 20px 0 0 20px; border-color: #ced4da;">
									<i class="fas fa-paperclip text-secondary" style="font-size: 15px; vertical-align: middle;"></i>
								</button>
							</div>
							<input type="file" name="chat_file" id="chat-file-input" style="display: none;" onchange="handleFileSelect(this);">
							<style>
								#chat-message-editable:empty::before {
									content: attr(placeholder);
									color: #6c757d;
									font-weight: bold;
									cursor: text;
								}
								.chat-messages-container .chat-msg-row span img:not(.chat-attachment-img) {
									width: 20px;
									height: 20px;
									vertical-align: middle;
									margin: 0 2px;
								}
								@media (max-width: 767.98px) {
									.container-fluid {
										padding-left: 0 !important;
										padding-right: 0 !important;
									}
									.d-flex.shadow-sm.rounded-lg.border {
										border: none !important;
										border-radius: 0 !important;
										box-shadow: none !important;
									}
									.p-3.bg-white.border-top {
										padding: 8px !important;
									}
									.input-group-prepend .btn,
									.input-group-append .btn {
										padding-left: 10px !important;
										padding-right: 10px !important;
									}
								}
							</style>
							<div id="chat-message-editable" contenteditable="true" class="form-control chat-input font-weight-bold" placeholder="Write a message..." style="border-radius: 0; padding-left: 10px; border-left: 0; overflow-y: auto; height: 38px; line-height: 24px; white-space: pre-wrap; cursor: text; text-align: left;"></div>
							<input type="hidden" name="message" id="message-hidden-input">
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="button" onclick="toggleEmojiTray()" style="border-radius: 0; border-color: #ced4da; border-left: 0;">
									<i class="far fa-smile text-secondary" style="font-size: 17px; vertical-align: middle;"></i>
								</button>
								<button class="btn btn-primary font-weight-bold px-4" type="submit" name="submit" style="border-radius: 0 20px 20px 0;">
									<i class="fas fa-paper-plane text-white"></i>
								</button>
							</div>
						</div>

						<!-- Emojis Tray -->
						<div class="emoticon-tray flex-wrap align-items-center justify-content-center p-2 bg-light rounded border" id="emoticon-tray-container" style="max-height: 100px; overflow-y: auto; display: none;">
							<?php
								$emojis = array(
									'😊', '😘', '😡', '😑', '😁', '😎', '😵', '😐', '😆', '😍', 
									'😢', '😲', '🤢', '😒', '😜', '😛', '👍', '😟', '😮', '🤔', 
									'😗', '😉', '🤏', '❓', '❗'
								);
								foreach ($emojis as $emoji) {
									echo "<a onclick='emo(\"" . addslashes($emoji) . "\")' class='m-1 p-1 d-inline-block rounded' style='cursor:pointer; font-size:22px; text-decoration:none; transition:transform 0.1s;' onmouseover='this.style.transform=\"scale(1.25)\"' onmouseout='this.style.transform=\"scale(1)\"'>" . $emoji . "</a>";
								}
							?>
						</div>
					</form>
				</div>
			<?php else: ?>
				<!-- Empty Conversation State -->
				<div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-4 bg-light position-relative">
					<!-- Mobile Top Bar for Empty State -->
					<div class="p-3 bg-white border-bottom d-flex d-md-none justify-content-between align-items-center w-100" style="position: absolute; top: 0; left: 0; right: 0;">
						<button class="btn btn-sm btn-light border font-weight-bold" onclick="toggleLeftPane()"><i class="fas fa-bars mr-1"></i> PMs</button>
						<span class="font-weight-bold text-dark">Private Chat</span>
						<div style="width: 70px;">&nbsp;</div>
					</div>
					<div class="bg-white rounded-circle p-4 shadow-sm mb-3 text-primary" style="width: 80px; height: 80px; font-size: 32px; display: flex; align-items: center; justify-content: center;">
						<i class="fas fa-paper-plane"></i>
					</div>
					<h5 class="font-weight-bold text-dark">Your Private Messages</h5>
					<p class="text-secondary small max-width-350">Select an active user from the sidebar to start a private conversation.</p>
					<a href="chat_rooms.php" class="btn btn-primary font-weight-bold px-4 rounded-pill mt-2">
						<i class="fas fa-comments mr-2"></i>Go to Group Chat
					</a>
				</div>
			<?php endif; ?>
		</div>

		<!-- Right Pane: Selected Recipient Details -->
		<div class="col-md-3 d-none d-md-flex flex-column p-0 bg-light border-left h-100 mobile-pane-overlay mobile-right-pane">
			<?php if ($other !== '' && $otherProfile): ?>
				<div class="p-3 bg-white border-bottom">
					<h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-user-circle text-primary mr-2"></i>User Profile</h5>
				</div>
				<div class="flex-grow-1 overflow-y-auto p-4 text-center d-flex flex-column align-items-center">
					<?php
						$avatarPath = "images/users/" . $otherProfile['imgUrl'];
						if (empty($otherProfile['imgUrl']) || !file_exists($avatarPath)) {
							$avatarPath = "images/users/blank.jpg";
						}
					?>
					<?php
						$profileBorderClass = $is_other_online ? 'border-success' : 'border-secondary';
					?>
					<img id="profile-pane-avatar" src="<?php echo htmlspecialchars($avatarPath); ?>" class="rounded-circle mb-3 border <?php echo $profileBorderClass; ?> shadow" style="width: 90px; height: 90px; object-fit: cover;" alt="Avatar">
					<h6 class="chid font-weight-bold text-dark mb-1"><?php echo htmlentities($otherProfile['fullname']); ?></h6>
					<?php if ($is_other_online): ?>
						<span id="profile-pane-status" class="badge badge-success px-3 py-1 font-weight-bold rounded-pill mb-3" style="font-size:11px;">
							<i class="fas fa-circle mr-1 animate-pulse" style="font-size: 7px;"></i>Online
						</span>
					<?php else: ?>
						<span id="profile-pane-status" class="badge badge-secondary px-3 py-1 font-weight-bold rounded-pill mb-3" style="font-size:11px; background-color: #adb5bd;">
							<i class="fas fa-circle mr-1" style="font-size: 7px; color: #ffffff;"></i>Offline
						</span>
					<?php endif; ?>

					<div class="w-100 text-left border rounded p-3 bg-white mt-2 shadow-xs">
						<div class="small text-muted font-weight-bold mb-1"><i class="fas fa-envelope mr-1 text-secondary"></i>Username</div>
						<div class="font-weight-bold text-dark mb-3">@<?php echo htmlentities($otherProfile['username']); ?></div>

						<div class="small text-muted font-weight-bold mb-1"><i class="fas fa-shield-alt mr-1 text-secondary"></i>Role / Access</div>
						<div class="font-weight-bold text-primary mb-3"><?php echo htmlentities($otherProfile['access']); ?></div>

						<?php
							// Fetch last active session details for chatmate
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
									echo "<div class='small text-muted font-weight-bold mb-1'><i class='fas fa-sign-in-alt mr-1 text-secondary'></i>Logged In At</div>";
									echo "<div class='font-weight-bold text-success mb-3'>" . date('h:i A', $login_time) . " <span class='text-muted font-weight-normal' style='font-size:11px;'>($time_str)</span></div>";
									
									echo "<div class='small text-muted font-weight-bold mb-1'><i class='fas fa-pulse mr-1 text-secondary'></i>Last Active</div>";
									echo "<div class='font-weight-bold text-dark mb-0'>Active now</div>";
								} else {
									echo "<div class='small text-muted font-weight-bold mb-1'><i class='fas fa-history mr-1 text-secondary'></i>Last Active (Offline)</div>";
									echo "<div class='font-weight-bold text-secondary mb-0'>" . date('M d, h:i A', $last_active) . "</div>";
								}
							} else {
								echo "<div class='small text-muted font-weight-bold mb-1'><i class='fas fa-info-circle mr-1 text-secondary'></i>Status</div>";
								echo "<div class='font-weight-bold text-secondary mb-0'>No session history</div>";
							}
						?>
					</div>
				</div>
			<?php else: ?>
				<div class="h-100 d-flex align-items-center justify-content-center text-center p-3 text-muted bg-light">
					<div>
						<i class="fas fa-info-circle fa-2x mb-2 text-secondary"></i>
						<div class="small">Profile information will load here when a chat is active.</div>
					</div>
				</div>
			<?php endif; ?>
		</div>

	</div>
</div>
<div class="pane-backdrop" onclick="closeMobilePanes()"></div>

<?php include("users_profile.php");?>

<script language="JavaScript" type="text/javascript">
var myUsername = "<?php echo htmlspecialchars($me); ?>";
function toggleLeftPane() {
	var left = document.querySelector('.mobile-left-pane');
	var right = document.querySelector('.mobile-right-pane');
	var backdrop = document.querySelector('.pane-backdrop');
	if (left) {
		left.classList.toggle('show-mobile');
		if (left.classList.contains('show-mobile')) {
			if (right) right.classList.remove('show-mobile');
			if (backdrop) backdrop.style.display = 'block';
		} else {
			if (backdrop) backdrop.style.display = 'none';
		}
	}
}
function toggleRightPane() {
	var left = document.querySelector('.mobile-left-pane');
	var right = document.querySelector('.mobile-right-pane');
	var backdrop = document.querySelector('.pane-backdrop');
	if (right) {
		right.classList.toggle('show-mobile');
		if (right.classList.contains('show-mobile')) {
			if (left) left.classList.remove('show-mobile');
			if (backdrop) backdrop.style.display = 'block';
		} else {
			if (backdrop) backdrop.style.display = 'none';
		}
	}
}
function closeMobilePanes() {
	var left = document.querySelector('.mobile-left-pane');
	var right = document.querySelector('.mobile-right-pane');
	var backdrop = document.querySelector('.pane-backdrop');
	if (left) left.classList.remove('show-mobile');
	if (right) right.classList.remove('show-mobile');
	if (backdrop) backdrop.style.display = 'none';
}

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

var emoticonsMap = {
	"Aa@": "😊", "Bb#": "😘", "Cc$": "😡", "Dd": "😑", "Ee*": "😊",
	"Ff(": "😁", "Gg)": "😎", "Hh+": "😵", "Ii-": "😐", "Jj:": "😆",
	"Kk;": "😍", "Ll?": "😢", "Mm1": "😲", "Nn2": "🤢", "Oo3": "😒",
	"Pp4": "😜", "Qq5": "😛", "Rr6": "👍", "Ss7": "😟", "Tt8": "😮",
	"Uu9": "🤔", "Vv0": "😗", "Ww=": "😉", "Xx.": "🤏", "Yy?": "❓",
	"Zz!": "❗"
};

function setReply(messageId, sender, text) {
	var input = document.getElementById('reply-to-input');
	var banner = document.getElementById('reply-preview-container');
	var previewSender = document.getElementById('reply-preview-sender');
	var previewText = document.getElementById('reply-preview-text');
	
	if (input && banner && previewSender && previewText) {
		input.value = messageId;
		previewSender.textContent = sender;
		
		var formattedText = text;
		
		// Parse [FILE:filename|filepath] markup in javascript
		var fileMatch = formattedText.match(/^\[FILE:(.+?)\|(.+?)\]$/);
		if (fileMatch) {
			var fileName = fileMatch[1];
			var filePath = fileMatch[2];
			var fileExt = fileName.split('.').pop().toLowerCase();
			
			if (['png', 'jpg', 'jpeg', 'gif', 'webp', 'mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'].indexOf(fileExt) !== -1) {
				formattedText = '📎 Attachment: <a href="' + filePath + '" onclick="openLightbox(\'' + filePath.replace(/'/g, "\\'") + '\'); return false;" style="text-decoration: underline; color: #007bff; font-weight: bold;">' + fileName + '</a>';
			} else {
				formattedText = '📎 Attachment: <a href="' + filePath + '" download style="text-decoration: underline; color: #007bff; font-weight: bold;">' + fileName + '</a>';
			}
		} else {
			// Escape and format the reply text preview with emoticons
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
		
		// Focus chat input
		var chatInput = document.querySelector('.chat-input');
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

function handleFileSelect(input) {
	var banner = document.getElementById('file-preview-container');
	var nameSpan = document.getElementById('file-preview-name');
	if (input.files && input.files.length > 0) {
		if (banner && nameSpan) {
			nameSpan.textContent = input.files[0].name;
			banner.style.display = 'flex';
		}
	} else {
		cancelFile();
	}
}

function cancelFile() {
	var input = document.getElementById('chat-file-input');
	var banner = document.getElementById('file-preview-container');
	if (input) input.value = '';
	if (banner) banner.style.display = 'none';
}

function emo($e) {
	insertHTMLAtCursor($e);
}

function insertHTMLAtCursor(html) {
	var sel, range;
	if (window.getSelection) {
		sel = window.getSelection();
		if (sel.getRangeAt && sel.rangeCount) {
			range = sel.getRangeAt(0);
			var editable = document.getElementById('chat-message-editable');
			if (editable && editable.contains(range.commonAncestorContainer)) {
				range.deleteContents();
				var el = document.createElement("div");
				el.innerHTML = html;
				var frag = document.createDocumentFragment(), node, lastNode;
				while ((node = el.firstChild)) {
					lastNode = frag.appendChild(node);
				}
				range.insertNode(frag);
				if (lastNode) {
					range = range.cloneRange();
					range.setStartAfter(lastNode);
					range.collapse(true);
					sel.removeAllRanges();
					sel.addRange(range);
				}
				return;
			}
		}
	}
	var editable = document.getElementById('chat-message-editable');
	if (editable) {
		editable.focus();
		var el = document.createElement("div");
		el.innerHTML = html;
		while (el.firstChild) {
			editable.appendChild(el.firstChild);
		}
	}
}

function getMessageFromEditable() {
	var editable = document.getElementById('chat-message-editable');
	if (!editable) return "";
	var message = "";
	editable.childNodes.forEach(function(node) {
		if (node.nodeType === Node.TEXT_NODE) {
			message += node.textContent;
		} else if (node.nodeType === Node.ELEMENT_NODE) {
			if (node.tagName === 'IMG' && node.hasAttribute('data-code')) {
				message += node.getAttribute('data-code');
			} else if (node.tagName === 'BR') {
				message += "\n";
			} else {
				message += getMessageFromNode(node);
			}
		}
	});
	return message;
}

function getMessageFromNode(parent) {
	var message = "";
	parent.childNodes.forEach(function(node) {
		if (node.nodeType === Node.TEXT_NODE) {
			message += node.textContent;
		} else if (node.nodeType === Node.ELEMENT_NODE) {
			if (node.tagName === 'IMG' && node.hasAttribute('data-code')) {
				message += node.getAttribute('data-code');
			} else if (node.tagName === 'BR') {
				message += "\n";
			} else {
				message += getMessageFromNode(node);
			}
		}
	});
	return message;
}

document.addEventListener('DOMContentLoaded', function() {
	var form = document.getElementById('chat-form-element');
	var editable = document.getElementById('chat-message-editable');
	var hiddenInput = document.getElementById('message-hidden-input');
	
	if (form && editable && hiddenInput) {
		editable.addEventListener('keydown', function(e) {
			if (e.keyCode === 13 && !e.shiftKey) {
				e.preventDefault();
				var submitBtn = form.querySelector('button[name="submit"]') || form.querySelector('button[type="submit"]');
				if (submitBtn && !submitBtn.disabled) {
					submitBtn.click();
				}
			}
		});

		form.addEventListener('submit', function(e) {
			e.preventDefault();
			var message = getMessageFromEditable();
			if (message.length > 500) {
				message = message.substring(0, 500);
			}
			hiddenInput.value = message;

			var fileInput = document.getElementById('chat-file-input');
			var hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
			if (message.trim() === '' && !hasFile) {
				return;
			}

			var submitBtn = form.querySelector('button[type="submit"]');
			if (submitBtn) submitBtn.disabled = true;
			editable.setAttribute('contenteditable', 'false');

			var formData = new FormData(form);
			var actionUrl = window.location.pathname + window.location.search + (window.location.search ? '&' : '?') + 'ajax_send=1';

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
		
		editable.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' && !e.shiftKey) {
				e.preventDefault();
				var submitBtn = form.querySelector('button[type="submit"]');
				if (submitBtn) {
					submitBtn.click();
				}
			}
		});
	}
});

// Scroll chat feed to the bottom automatically
function scrollToBottom() {
	var chatFeed = document.querySelector('.chat-feed-container');
	if (chatFeed) {
		chatFeed.scrollTop = chatFeed.scrollHeight;
	}
}

document.addEventListener("DOMContentLoaded", function() {
	scrollToBottom();
	if ("<?php echo $other; ?>" !== "") {
		refreshChat();
	}
	refreshUsers();
});

// Auto-refresh message list via AJAX every 2 seconds
function refreshChat() {
	// If a reaction menu is currently open, do not refresh the chat feed to prevent closing it
	var openMenus = document.querySelectorAll('.reaction-menu-popup:not(.d-none)');
	if (openMenus.length > 0) {
		return;
	}

	var otherUser = "<?php echo $other; ?>";
	if (otherUser === "") return;

	var xhr = new XMLHttpRequest();
	var ajaxUrl = "private_chat.php?ajax=1&user=" + encodeURIComponent(otherUser) + "&me=" + encodeURIComponent(myUsername);
	xhr.open('GET', ajaxUrl, true);
	xhr.onload = function() {
		if (xhr.status === 401 || xhr.responseText.trim() === "SESSION_EXPIRED") {
			window.location.reload();
			return;
		}
		if (xhr.status === 200) {
			var container = document.querySelector('.chat-messages-container');
			if (container) {
				var chatFeed = document.querySelector('.chat-feed-container');
				var wasAtBottom = chatFeed ? (chatFeed.scrollHeight - chatFeed.clientHeight <= chatFeed.scrollTop + 50) : false;
				
				// Compare normalized HTML to detect any changes (including emoticons/images/files)
				var html1 = container.innerHTML.replace(/\s+/g, ' ').trim();
				var html2 = xhr.responseText.replace(/\s+/g, ' ').trim();
				
				if (html1 !== html2) {
					var tempOld = document.createElement('div');
					tempOld.innerHTML = container.innerHTML;
					var tempNew = document.createElement('div');
					tempNew.innerHTML = xhr.responseText;
					
					var oldCalls = tempOld.querySelectorAll('button[onclick*="joinJitsiCall"]').length;
					var newCalls = tempNew.querySelectorAll('button[onclick*="joinJitsiCall"]').length;
					
					container.innerHTML = xhr.responseText;
					
					if (newCalls > oldCalls) {
						var callButtons = tempNew.querySelectorAll('button[onclick*="joinJitsiCall"]');
						if (callButtons.length > 0) {
							var latestBtn = callButtons[callButtons.length - 1];
							var parentRow = latestBtn.closest('.chat-msg-row');
							if (parentRow && parentRow.classList.contains('justify-content-start')) {
								if (typeof showIncomingCallOverlay === 'function') {
									var senderEl = parentRow.querySelector('.text-muted.font-weight-bold');
									var senderName = senderEl ? senderEl.textContent.trim().split(' ')[0] : 'LGU Member';
									
									var headerAvatarEl = document.querySelector('img[src*="images/users/"]');
									var senderAvatar = headerAvatarEl ? headerAvatarEl.getAttribute('src') : 'images/users/blank.jpg';
									
									var match = latestBtn.getAttribute('onclick').match(/joinJitsiCall\("(.+?)",\s*"(.+?)"\)/);
									if (match) {
										var roomName = match[1];
										var callType = match[2];
										var msgId = parentRow.getAttribute('data-msg-id');
										showIncomingCallOverlay(senderName, roomName, callType, senderAvatar, msgId);
									}
								}
							}
						}
					} else {
						// Only play text notification sound if it's a new incoming message (aligned left)
						var oldIncoming = tempOld.querySelectorAll('.chat-msg-row.justify-content-start').length;
						var newIncoming = tempNew.querySelectorAll('.chat-msg-row.justify-content-start').length;
						if (newIncoming > oldIncoming) {
							if (typeof playMessageNotificationSound === 'function') {
								playMessageNotificationSound();
							}
						}
					}
					
					if (wasAtBottom && chatFeed) {
						chatFeed.scrollTop = chatFeed.scrollHeight;
					}
				}
			}
		}
	};
	xhr.send();
}

// Update Chat Header Online/Offline status based on the selected user in the sidebar
function updateHeaderStatus() {
	var activeItem = document.querySelector('#online-users-pm-list a.active');
	if (!activeItem) return;
	
	// Check if the active item has a green border or a green dot badge indicating they are online
	var isOnline = activeItem.querySelector('.border-success') !== null || activeItem.querySelector('.badge-success') !== null;
	var avatar = document.getElementById('chat-header-avatar');
	var status = document.getElementById('chat-header-status');
	var profileAvatar = document.getElementById('profile-pane-avatar');
	var profileStatus = document.getElementById('profile-pane-status');
	
	if (isOnline) {
		if (avatar) {
			avatar.classList.remove('border-secondary');
			avatar.classList.add('border-success');
		}
		if (status) {
			status.className = 'text-success font-weight-bold';
			status.innerHTML = '<i class="fas fa-circle mr-1 animate-pulse" style="font-size: 8px;"></i>Online';
		}
		if (profileAvatar) {
			profileAvatar.classList.remove('border-secondary');
			profileAvatar.classList.add('border-success');
		}
		if (profileStatus) {
			profileStatus.className = 'badge badge-success px-3 py-1 font-weight-bold rounded-pill mb-3';
			profileStatus.style.backgroundColor = '';
			profileStatus.innerHTML = '<i class="fas fa-circle mr-1 animate-pulse" style="font-size: 7px;"></i>Online';
		}
	} else {
		if (avatar) {
			avatar.classList.remove('border-success');
			avatar.classList.add('border-secondary');
		}
		if (status) {
			status.className = 'text-secondary font-weight-bold';
			status.innerHTML = '<i class="fas fa-circle mr-1" style="font-size: 8px; color: #adb5bd;"></i>Offline';
		}
		if (profileAvatar) {
			profileAvatar.classList.remove('border-success');
			profileAvatar.classList.add('border-secondary');
		}
		if (profileStatus) {
			profileStatus.className = 'badge badge-secondary px-3 py-1 font-weight-bold rounded-pill mb-3';
			profileStatus.style.backgroundColor = '#adb5bd';
			profileStatus.innerHTML = '<i class="fas fa-circle mr-1" style="font-size: 7px; color: #ffffff;"></i>Offline';
		}
	}
}

// Auto-refresh online users status list via AJAX every 4 seconds
function refreshUsers() {
	var otherUser = "<?php echo $other; ?>";
	var xhr = new XMLHttpRequest();
	var ajaxUrl = "private_chat.php?ajax_users=1" + (otherUser ? "&user=" + encodeURIComponent(otherUser) : "") + "&me=" + encodeURIComponent(myUsername);
	xhr.open('GET', ajaxUrl, true);
	xhr.onload = function() {
		if (xhr.status === 401 || xhr.responseText.trim() === "SESSION_EXPIRED") {
			window.location.reload();
			return;
		}
		if (xhr.status === 200) {
			var container = document.querySelector('#online-users-pm-list');
			if (container) {
				var parser = new DOMParser();
				var doc = parser.parseFromString(xhr.responseText, 'text/html');
				var text1 = container.textContent.replace(/\s+/g, ' ').trim();
				var text2 = doc.body ? doc.body.textContent.replace(/\s+/g, ' ').trim() : '';
				
				if (text1 !== text2) {
					container.innerHTML = xhr.responseText;
				}
				updateHeaderStatus();
			}
		}
	};
	xhr.send();
}

window.addEventListener("load", function() {
	scrollToBottom();
});

if ("<?php echo $other; ?>" !== "") {
	setInterval(refreshChat, 2000);
}
setInterval(refreshUsers, 4000);

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
        video.play().catch(function() {});
    } else {
        video.pause();
        video.style.display = 'none';
        video.src = '';
        img.src = src;
        img.style.display = 'block';
    }

    lightbox.style.display = 'flex';
    lightbox.offsetHeight; // Force reflow
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
</script>

<!-- Custom Standalone Lightbox CSS -->
<style>
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
</style>

<!-- Custom Standalone Lightbox Overlay HTML -->
<div id="customMediaLightbox" class="custom-lightbox" onclick="hideLightboxModal()">
	<button type="button" class="custom-lightbox-close" onclick="hideLightboxModal()" title="Close Lightbox">
		<i class="fas fa-arrow-left"></i>
	</button>
	<img id="lightbox-image" class="custom-lightbox-content" src="" style="display: none;" alt="Preview" onclick="event.stopPropagation()">
	<video id="lightbox-video" class="custom-lightbox-content" controls preload="metadata" style="display: none; background: #000; outline: none;" onclick="event.stopPropagation()"></video>
</div>
<?php include('footer.php'); ?>
</body>
</html>
