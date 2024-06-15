<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit();
}
include_once '../../controllers/PatientController.php';
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'es';


$patientController = new PatientController();
$profile = $patientController->getProfile($_SESSION['user_id']);
$user = $patientController->getUser($_SESSION['user_id']);
$appointments = $patientController->getAppointments($_SESSION['user_id']);

?>


<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="PatientDashboard.title">Panel del Paciente</title>
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <link href="../../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../js/translation.js"></script>
    <script src="../../js/patientProfile.js"></script>
</head>

<body class="bg-gray-100">
    <?php include '../layout/header.php'; ?>
    
    <div class="container mx-auto mt-10">
        <div class="max-w-6xl mx-auto bg-white p-8 border border-gray-300 rounded shadow">
            <h2 class="text-2xl font-bold mb-5" data-translate="PatientDashboard.welcome">Bienvenido</h2>
            <p data-translate="PatientDashboard.dashboard_text">Este es tu Perfil.</p>
            
            <h3 class="text-xl font-bold mt-10 mb-5" data-translate="PatientProfile.pending_appointments">Citas Pendientes</h3>
            <?php if ($appointments): ?>
                <ul class="list-disc pl-5">
                    <?php foreach ($appointments as $appointment): ?>
                        <li class="mb-2">
                            <strong data-translate="PatientProfile.date">Fecha:</strong> <?= htmlspecialchars($appointment['appointment_date']) ?><br>
                            <strong data-translate="PatientProfile.time">Hora:</strong> <?= htmlspecialchars($appointment['appointment_time']) ?><br>
                            <strong data-translate="PatientProfile.doctor">Doctor:</strong> <?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?><br>
                            <strong data-translate="PatientProfile.specialty">Especialidad:</strong> <?= htmlspecialchars($appointment['specialties']) ?><br>
                            <strong data-translate="PatientProfile.type">Tipo:</strong> <?= htmlspecialchars($appointment['appointment_type']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p data-translate="PatientProfile.no_pending_appointments">No hay citas pendientes.</p>
            <?php endif; ?>
        </div>
    </div>

        </div>
    </div>
    
    <?php include '../layout/footer.php'; ?>
</body>

</html>
