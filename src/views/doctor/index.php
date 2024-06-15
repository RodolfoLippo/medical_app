<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}

include_once '../../controllers/DoctorController.php';

$doctorController = new DoctorController();
$profile = $doctorController->getProfile($_SESSION['user_id']);
$appointments = $profile ? $doctorController->getPendingAppointments($profile['doctor_id']) : [];

?>
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="DoctorDashboard.title">Doctor Dashboard</title>
    <link href="../../output.css" rel="stylesheet">
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../js/translation.js"></script>
    <script src="../../js/doctorProfile.js"></script>
</head>
<body class="bg-gray-100">
    <?php include '../layout/header.php'; ?>
    
    <div class="container mx-auto mt-10">
        <div class="max-w-6xl mx-auto bg-white p-8 border border-gray-300 rounded shadow">
            <h2 class="text-2xl font-bold mb-5" data-translate="DoctorDashboard.welcome">Welcome, Doctor</h2>
            <p data-translate="DoctorDashboard.dashboard_text">This is your dashboard.</p>
            
            <h3 class="text-xl font-bold mt-10 mb-5" data-translate="DoctorProfile.scheduled_appointments">Scheduled Appointments</h3>
            <?php if ($appointments): ?>
                <ul class="list-disc pl-5">
                    <?php foreach ($appointments as $appointment): ?>
                        <li class="mb-2">
                            <strong data-translate="DoctorProfile.patient">Patient:</strong> <?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?><br>
                            <strong data-translate="DoctorProfile.date">Date:</strong> <?= htmlspecialchars($appointment['appointment_date']) ?><br>
                            <strong data-translate="DoctorProfile.time">Time:</strong> <?= htmlspecialchars($appointment['appointment_time']) ?><br>
                            <strong data-translate="DoctorProfile.contact_info">Contact Info:</strong> <?= htmlspecialchars($appointment['contact_info']) ?><br>
                            <strong data-translate="DoctorProfile.insurance_info">Insurance Info:</strong> <?= htmlspecialchars($appointment['insurance_info']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p data-translate="DoctorProfile.no_scheduled_appointments">No scheduled appointments.</p>
            <?php endif; ?>

        </div>
    </div>
    
    <?php include '../layout/footer.php'; ?>
</body>
</html>
