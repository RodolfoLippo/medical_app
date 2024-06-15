<?php
session_start();
require '../../config/config.php';  

// Función para obtener doctor_id desde user_id
function getDoctorId($user_id, $conn) {
    $sql = "SELECT doctor_id FROM doctors WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['doctor_id'];
    }
    return null;
}

// Verificar si el usuario está autenticado y es un doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    echo 'Error: User not logged in or not a doctor.';
    exit;
}

$user_id = $_SESSION['user_id'];  
$doctor_id = getDoctorId($user_id, $conn); 

if ($doctor_id === null) {
    echo 'Error: Doctor ID not found.';
    exit;
}

// Conexión a la base de datos
global $conn;

// Obtener las citas del doctor y la información del paciente
$sql = "SELECT a.appointment_id, p.first_name, p.last_name, a.appointment_date, a.appointment_time, a.status, a.appointment_type, c.consultation_id
        FROM appointments a
        JOIN patients p ON a.patient_id = p.patient_id
        JOIN consultations c ON a.appointment_id = c.appointment_id
        WHERE a.doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="DoctorAppointments.title">Doctor Appointments</title>
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <link href="../../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="bg-gray-100">
    <?php include '../layout/header.php'; ?>

    <div class="container mx-auto mt-10">
        <div class="max-w-4xl mx-auto bg-white p-8 border border-gray-300 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800" data-translate="DoctorAppointments.header">Doctor Appointments</h2>
            <div class="appointments-container">
                <?php if (empty($appointments)): ?>
                    <p class="text-center text-gray-500" data-translate="DoctorAppointments.no_appointments">You have no appointments scheduled.</p>
                <?php else: ?>
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2" data-translate="DoctorAppointments.patient_name">Patient Name</th>
                                <th class="py-2" data-translate="DoctorAppointments.date">Date</th>
                                <th class="py-2" data-translate="DoctorAppointments.time">Time</th>
                                <th class="py-2" data-translate="DoctorAppointments.status">Status</th>
                                <th class="py-2" data-translate="DoctorAppointments.type">Type</th>
                                <th class="py-2" data-translate="DoctorAppointments.action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($appointment['status']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($appointment['appointment_type']) ?></td>
                                    <td class="border px-4 py-2">
                                        <a href="online_consultation.php?appointment_id=<?= $appointment['appointment_id'] ?>&consultation_id=<?= $appointment['consultation_id'] ?>" class="text-blue-500 hover:underline" data-translate="DoctorAppointments.join_consultation">Join Consultation</a>
                                    </td>
                                </tr>
                                <?php error_log("Generated URL: online_consultation.php?appointment_id=" . $appointment['appointment_id'] . "&consultation_id=" . $appointment['consultation_id']); ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>
    <script src="../../js/translation.js"></script>
</body>
</html>
