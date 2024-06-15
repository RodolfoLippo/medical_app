<?php
include_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    
    // Obtener la disponibilidad del doctor
    $sql = "SELECT availability_days, start_hour, end_hour FROM Doctors WHERE doctor_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["error" => $conn->error]);
        exit();
    }
    $stmt->bind_param('i', $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $availability = [];
    if ($row = $result->fetch_assoc()) {
        $availability['days'] = explode(',', $row['availability_days']);
        $availability['start_hour'] = $row['start_hour'];
        $availability['end_hour'] = $row['end_hour'];
    }

    // Agregar depuración
    error_log("Doctor availability: " . print_r($availability, true));

    echo json_encode($availability);
}
?>
