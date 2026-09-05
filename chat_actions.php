<?php
require("connect.php");

// Identify current user (registered or guest)
if (!isset($_SESSION['user'])) {
    $sid = session_id();
    $gex = $link->query("SELECT * FROM session s WHERE s.guest=1 AND s.sid='$sid'");
    if (mysqli_num_rows($gex) > 0) {
        $grow = $gex->fetch_array();
        $guest = $grow['user'];
        $gid = $grow['gid'];
        $currentUser = "$guest-$gid";
    } else {
        echo json_encode(array('success' => false, 'error' => 'Unauthorized'));
        exit;
    }
} else {
    $currentUser = $_SESSION['user'];
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : ''; // 'public' or 'private'
$messageId = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;

if ($messageId <= 0) {
    echo json_encode(array('success' => false, 'error' => 'Invalid message ID'));
    exit;
}

if ($action === 'react') {
    $reaction = isset($_POST['reaction']) ? trim($_POST['reaction']) : '';
    if ($reaction === '') {
        echo json_encode(array('success' => false, 'error' => 'Invalid reaction'));
        exit;
    }
    
    $type_esc = mysqli_real_escape_string($link, $type);
    $reaction_esc = mysqli_real_escape_string($link, $reaction);
    $user_esc = mysqli_real_escape_string($link, $currentUser);
    
    // Check if reaction already exists
    $check = $link->query("SELECT id, reaction FROM message_reactions WHERE message_type='$type_esc' AND message_id=$messageId AND username='$user_esc'");
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_array($check);
        $existingId = $row['id'];
        $existingReaction = $row['reaction'];
        
        if ($existingReaction === $reaction) {
            // Same reaction: toggle (remove) it
            $link->query("DELETE FROM message_reactions WHERE id=$existingId");
            echo json_encode(array('success' => true, 'status' => 'removed'));
        } else {
            // Different reaction: update it to the new one!
            $link->query("UPDATE message_reactions SET reaction='$reaction_esc' WHERE id=$existingId");
            echo json_encode(array('success' => true, 'status' => 'updated'));
        }
    } else {
        // Insert new reaction
        $link->query("INSERT INTO message_reactions (message_type, message_id, username, reaction) VALUES ('$type_esc', $messageId, '$user_esc', '$reaction_esc')");
        echo json_encode(array('success' => true, 'status' => 'added'));
    }
    exit;
}

if ($action === 'unsend') {
    $type_esc = mysqli_real_escape_string($link, $type);
    $user_esc = mysqli_real_escape_string($link, $currentUser);
    
    // Verify ownership
    if ($type === 'public') {
        $msgCheck = $link->query("SELECT sender FROM chat_messages WHERE id=$messageId");
    } else {
        $msgCheck = $link->query("SELECT sender FROM private_messages WHERE id=$messageId");
    }
    
    if (mysqli_num_rows($msgCheck) > 0) {
        $row = mysqli_fetch_array($msgCheck);
        if (strtolower(trim($row['sender'])) === strtolower(trim($currentUser))) {
            // Update to unsent
            if ($type === 'public') {
                $link->query("UPDATE chat_messages SET is_unsent=1, message='This message was unsent' WHERE id=$messageId");
            } else {
                $link->query("UPDATE private_messages SET is_unsent=1, message='This message was unsent' WHERE id=$messageId");
            }
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'error' => 'Permission denied'));
        }
    } else {
        echo json_encode(array('success' => false, 'error' => 'Message not found'));
    }
    exit;
}

if ($action === 'edit') {
    $newMessage = isset($_POST['message']) ? trim($_POST['message']) : '';
    if ($newMessage === '') {
        echo json_encode(array('success' => false, 'error' => 'Message cannot be empty'));
        exit;
    }
    
    $type_esc = mysqli_real_escape_string($link, $type);
    $user_esc = mysqli_real_escape_string($link, $currentUser);
    $msg_esc = mysqli_real_escape_string($link, $newMessage);
    
    // Verify ownership
    if ($type === 'public') {
        $msgCheck = $link->query("SELECT sender, is_unsent FROM chat_messages WHERE id=$messageId");
    } else {
        $msgCheck = $link->query("SELECT sender, is_unsent FROM private_messages WHERE id=$messageId");
    }
    
    if ($msgCheck && mysqli_num_rows($msgCheck) > 0) {
        $row = mysqli_fetch_array($msgCheck);
        if (intval($row['is_unsent']) === 1) {
            echo json_encode(array('success' => false, 'error' => 'Cannot edit an unsent message'));
            exit;
        }
        if (strtolower(trim($row['sender'])) === strtolower(trim($currentUser))) {
            // Check if column is_edited exists, if not, alter table
            $link->query("ALTER TABLE chat_messages ADD COLUMN is_edited TINYINT(1) DEFAULT 0");
            $link->query("ALTER TABLE private_messages ADD COLUMN is_edited TINYINT(1) DEFAULT 0");
            
            // Update the message
            if ($type === 'public') {
                $link->query("UPDATE chat_messages SET message='$msg_esc', is_edited=1 WHERE id=$messageId");
            } else {
                $link->query("UPDATE private_messages SET message='$msg_esc', is_edited=1 WHERE id=$messageId");
            }
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'error' => 'Permission denied'));
        }
    } else {
        echo json_encode(array('success' => false, 'error' => 'Message not found'));
    }
    exit;
}

echo json_encode(array('success' => false, 'error' => 'Invalid action'));
?>
