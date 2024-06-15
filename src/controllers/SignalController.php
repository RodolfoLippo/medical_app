<?php
include_once 'C:\xampp\htdocs\medical_app\src\config\config.php';

class SignalController {
    /**
     * Undocumented function
     *
     * @return void
     */
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $data = json_decode($_POST['data'], true);
            $this->handleSignal($type, $data);
        }
    }
/**
 * Undocumented function
 *
 * @param [type] $type
 * @param [type] $data
 * @return void
 */
    private function handleSignal($type, $data) {
        global $conn;

        switch ($type) {
            case 'offer':
            case 'answer':
            case 'candidate':
                $consultation_id = $data['consultation_id'];
                $signalData = json_encode($data['signal']);
                $sql = "INSERT INTO signals (consultation_id, type, data) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $consultation_id, $type, $signalData);
                if ($stmt->execute()) {
                    echo json_encode(["status" => "success"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;

            case 'getSignals':
                $consultation_id = $data['consultation_id'];
                $sql = "SELECT type, data FROM signals WHERE consultation_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $consultation_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $signals = [];
                while ($row = $result->fetch_assoc()) {
                    $signals[] = $row;
                }
                echo json_encode($signals);
                break;
        }
    }
}

$signalController = new SignalController();
$signalController->handleRequest();
