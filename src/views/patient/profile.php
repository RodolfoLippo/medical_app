<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit();
}
include_once '../../controllers/PatientController.php';

$patientController = new PatientController();
$profile = $patientController->getProfile($_SESSION['user_id']);
$user = $patientController->getUser($_SESSION['user_id']);
$appointments = $patientController->getAppointments($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="PatientProfile.title">Perfil del Paciente</title>
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <link href="../../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/translation.js"></script>
    <script src="../../js/patientProfileManager.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include '../layout/header.php'; ?>
    <div class="container mx-auto mt-10">
        <div class="max-w-4xl mx-auto bg-white p-8 border border-gray-300 rounded shadow">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold mb-5" data-translate="PatientProfile.profile">Perfil</h2>
                <i class="fas fa-cog text-xl cursor-pointer" onclick="openEditModal()"></i>
            </div>
            <?php if ($profile): ?>
                <p class="mb-2"><strong data-translate="PatientProfile.full_name">Nombre Completo:</strong> <?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?></p>
                <p class="mb-2"><strong data-translate="PatientProfile.contact_info">Información de Contacto:</strong> <?= htmlspecialchars($profile['contact_info']) ?></p>
                <p class="mb-2"><strong data-translate="PatientProfile.insurance_info">Información del Seguro:</strong> <?= htmlspecialchars($profile['insurance_info']) ?></p>
                <p class="mb-2"><strong></strong> <?= nl2br(htmlspecialchars($profile['medical_history'])) ?></p>
            <?php else: ?>
                <p data-translate="PatientProfile.no_profile">No se encontró el perfil.</p>
            <?php endif; ?>
        </div>

            
     <!-- Modal para editar perfil -->
     <div id="editModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Editar Perfil</h2>
                <button class="text-gray-600 hover:text-gray-900" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editProfileForm">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Nombre de Usuario</label>
                    <input type="text" id="username" name="username" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($user['username']) ?>">
                </div>
                <div class="mb-4">
                    <label for="contact_info" class="block text-sm font-medium text-gray-700">Información de Contacto</label>
                    <input type="text" id="contact_info" name="contact_info" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($profile['contact_info']) ?>">
                </div>
                <div class="mb-4">
                    <label for="insurance_info" class="block text-sm font-medium text-gray-700">Información del Seguro</label>
                    <input type="text" id="insurance_info" name="insurance_info" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($profile['insurance_info']) ?>">
                </div>
                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                    <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteAccount()">Eliminar Cuenta</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    

    <?php include '../layout/footer.php'; ?>
</body>
</html>
