<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}
include_once '../../controllers/DoctorController.php';

$doctorController = new DoctorController();
$profile = $doctorController->getProfile($_SESSION['user_id']);
$requestedAppointments = $profile ? $doctorController->getRequestedAppointments($profile['doctor_id']) : [];

// Handle appointment actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['appointment_id'])) {
        $appointment_id = $_POST['appointment_id'];
        switch ($_POST['action']) {
            case 'confirm':
                $doctorController->updateAppointmentStatus($appointment_id, 'scheduled');
                break;
            case 'cancel':
                $doctorController->updateAppointmentStatus($appointment_id, 'cancelled');
                break;
            case 'delete':
                $doctorController->deleteAppointment($appointment_id);
                break;
        }
        header("Location: appointment-request.php");
        exit();
    }
}

// Debugging line
error_log("Profile: " . print_r($profile, true));
error_log("Requested Appointments: " . print_r($requestedAppointments, true));
?>
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="AppointmentRequests.title">Appointment Requests</title>
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <link href="../../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/translation.js"></script>
</head>
<body class="bg-gray-100">
    <?php include '../layout/header.php'; ?>
    <div class="container mx-auto mt-10">
        <div class="max-w-4xl mx-auto bg-white p-8 border border-gray-300 rounded shadow">
            <h2 class="text-2xl font-bold mb-5" data-translate="AppointmentRequests.header">Requested Appointments</h2>
            <?php if ($requestedAppointments): ?>
                <ul class="list-disc pl-5">
                    <?php foreach ($requestedAppointments as $appointment): ?>
                        <li class="mb-2">
                            <strong data-translate="AppointmentRequests.patient">Patient:</strong> <?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?><br>
                            <strong data-translate="AppointmentRequests.date">Date:</strong> <?= htmlspecialchars($appointment['appointment_date']) ?><br>
                            <strong data-translate="AppointmentRequests.time">Time:</strong> <?= htmlspecialchars($appointment['appointment_time']) ?><br>
                            <strong data-translate="AppointmentRequests.contact_info">Contact Info:</strong> <?= htmlspecialchars($appointment['contact_info']) ?><br>
                            <strong data-translate="AppointmentRequests.insurance_info">Insurance Info:</strong> <?= htmlspecialchars($appointment['insurance_info']) ?><br>
                            <form method="post" action="appointment-request.php" class="inline">
                                <input type="hidden" name="appointment_id" value="<?= $appointment['appointment_id'] ?>">
                                <button type="submit" name="action" value="confirm" class="bg-green-500 text-white px-2 py-1 rounded" data-translate="AppointmentRequests.confirm">Confirm</button>
                                <button type="submit" name="action" value="cancel" class="bg-yellow-500 text-white px-2 py-1 rounded" data-translate="AppointmentRequests.cancel">Cancel</button>
                                <button type="submit" name="action" value="delete" class="bg-red-500 text-white px-2 py-1 rounded" data-translate="AppointmentRequests.delete">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p data-translate="AppointmentRequests.no_requested_appointments">No requested appointments.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include '../layout/footer.php'; ?>
</body>
</html>
