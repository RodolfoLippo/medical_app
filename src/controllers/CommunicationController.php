<?php
include_once '../config/config.php';

class CommunicationController {
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @return void
     */
    public function getMessages($user_id) {
        global $conn;
        $sql = "SELECT sender, message, timestamp FROM Messages WHERE user_id='$user_id' ORDER BY timestamp ASC";
        $result = $conn->query($sql);
        $messages = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
        }
        return $messages;
    }
/**
 * Undocumented function
 *
 * @param [type] $user_id
 * @param [type] $message
 * @return void
 */
    public function sendMessage($user_id, $message) {
        global $conn;
        $sender = 'User';
        $timestamp = date('Y-m-d H:i:s');
        $sql = "INSERT INTO Messages (user_id, sender, message, timestamp) VALUES ('$user_id', '$sender', '$message', '$timestamp')";
        if ($conn->query($sql) === TRUE) {
            echo $message;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    session_start();
    $communicationController = new CommunicationController();
    $communicationController->sendMessage($_SESSION['user_id'], $_POST['message']);
}
?>
