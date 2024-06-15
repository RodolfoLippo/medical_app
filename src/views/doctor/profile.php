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

error_log("Profile: " . print_r($profile, true));
error_log("Appointments: " . print_r($appointments, true));

// Función para traducir los días
/**
 * Undocumented function
 *
 * @param [type] $days
 * @return void
 */
function translateDays($days) {
    $dayMap = [
        '1' => 'Lunes',
        '2' => 'Martes',
        '3' => 'Miércoles',
        '4' => 'Jueves',
        '5' => 'Viernes'
    ];
    
    $daysArray = explode(',', $days);
    $translatedDays = array_map(function($day) use ($dayMap) {
        return $dayMap[$day];
    }, $daysArray);
    
    return implode(', ', $translatedDays);
}
?>
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="DoctorProfile.title">Doctor Profile</title>
    <link href="../../output.css" rel="stylesheet">
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/translation.js"></script>
    <script src="../../js/doctorProfileManager.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include '../layout/header.php'; ?>
    <div class="container mx-auto mt-auto">
        <div class="max-w-4xl mx-auto bg-white p-8 border border-gray-300 rounded shadow">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold mb-5" data-translate="DoctorProfile.header">Profile</h2>
                <i class="fas fa-cog text-xl cursor-pointer" onclick="openEditModal()"></i>
            </div>
            <?php if ($profile): ?>
                <p class="mb-2"><strong data-translate="DoctorProfile.full_name">Full Name:</strong> <?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?></p>
                <p class="mb-2"><strong data-translate="DoctorProfile.specialty">Specialty:</strong> <?= htmlspecialchars($profile['specialties']) ?></p>
                <p class="mb-2"><strong data-translate="DoctorProfile.availability">Availability:</strong> <?= translateDays(htmlspecialchars($profile['availability_days'])) ?></p>
                <p class="mb-2"><strong data-translate="DoctorProfile.start_hour">Start Hour:</strong> <?= nl2br(htmlspecialchars($profile['start_hour'])) ?></p>
                <p class="mb-2"><strong data-translate="DoctorProfile.end_hour">End Hour:</strong> <?= nl2br(htmlspecialchars($profile['end_hour'])) ?></p>
            <?php else: ?>
                <p data-translate="DoctorProfile.no_profile">No profile found.</p>
            <?php endif; ?>

        </div>
    </div>
     <!-- Modal para editar disponibilidad y horas -->
     <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Editar Disponibilidad</h2>
                <button class="text-gray-600 hover:text-gray-900" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editProfileForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Días de Disponibilidad</label>
                    <div id="availability_days" class="flex flex-wrap gap-2">
                        <button type="button" class="day-btn bg-gray-200 text-gray-800 px-3 py-1 rounded" data-day="1">Lunes</button>
                        <button type="button" class="day-btn bg-gray-200 text-gray-800 px-3 py-1 rounded" data-day="2">Martes</button>
                        <button type="button" class="day-btn bg-gray-200 text-gray-800 px-3 py-1 rounded" data-day="3">Miércoles</button>
                        <button type="button" class="day-btn bg-gray-200 text-gray-800 px-3 py-1 rounded" data-day="4">Jueves</button>
                        <button type="button" class="day-btn bg-gray-200 text-gray-800 px-3 py-1 rounded" data-day="5">Viernes</button>
                    </div>
                </div>
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
                <div class="mb-4">
                    <label for="start_hour" class="block text-sm font-medium text-gray-700">Hora de Inicio</label>
                    <input type="time" id="start_hour" name="start_hour" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($profile['start_hour']) ?>">
                </div>
                <div class="mb-4">
                    <label for="end_hour" class="block text-sm font-medium text-gray-700">Hora de Finalización</label>
                    <input type="time" id="end_hour" name="end_hour" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($profile['end_hour']) ?>">
                </div>
                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <?php include '../layout/footer.php'; ?>
</body>
</html>
