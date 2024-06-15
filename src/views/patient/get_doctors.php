<?php
include_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $specialty = $_POST['specialty'];
    
    // Obtener doctores por especialidad
    $sql = "SELECT doctor_id, first_name, last_name FROM Doctors WHERE specialties = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "<option value=''>Error en la consulta</option>";
        exit();
    }
    $stmt->bind_param('s', $specialty);
    $stmt->execute();
    $result = $stmt->get_result();

    $options = "<option value=''>Select a Doctor</option>";
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['doctor_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
    }

    echo $options;
}
?>
