<?php
include_once 'C:\xampp\htdocs\medical_app\src\config\config.php';

class OnlineConsultationController {
    /**
     * Undocumented function
     *
     * @param [type] $consultation_id
     * @param [type] $message
     * @return void
     */
    public function sendMessage($consultation_id, $message) {
        global $conn;
        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "User not logged in."]);
            return;
        }
        $sender_id = $_SESSION['user_id'];

        $sql = "INSERT INTO consultationmessages (consultation_id, sender_id, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $consultation_id, $sender_id, $message);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Message sent successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
    }
/**
 * Undocumented function
 *
 * @param [type] $consultation_id
 * @return void
 */
    public function getMessages($consultation_id) {
        global $conn;
        $sql = "SELECT cm.message, u.username AS sender_name
                FROM consultationmessages cm
                JOIN users u ON cm.sender_id = u.user_id
                WHERE cm.consultation_id = ?
                ORDER BY cm.sent_at ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $consultation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        return json_encode($messages);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $onlineConsultationController = new OnlineConsultationController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'sendMessage':
                $onlineConsultationController->sendMessage($_POST['consultation_id'], $_POST['message']);
                break;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $onlineConsultationController = new OnlineConsultationController();

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getMessages':
                echo $onlineConsultationController->getMessages($_GET['consultation_id']);
                break;
        }
    }
}
?>
