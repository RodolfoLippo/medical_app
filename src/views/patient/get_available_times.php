<?php
include_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];

    $sql = "SELECT appointment_time FROM Appointments WHERE doctor_id = ? AND appointment_date = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["error" => $conn->error]);
        exit();
    }
    $stmt->bind_param('is', $doctor_id, $appointment_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $booked_times = [];
    while ($row = $result->fetch_assoc()) {
        $booked_times[] = substr($row['appointment_time'], 0, 5);
    }

    echo json_encode($booked_times);
}
?>
