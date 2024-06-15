<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../output.css" rel="stylesheet">
    <title data-translate="IndexRolsPatients.Header.title">Medical App</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100 text-gray-900">
    <header class="bg-gray-800 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Medical App</h1>
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['role'] == 'patient') {
                    echo '<nav class="flex space-x-4">
                            <a href="../patient/index.php" class="hover:underline">Inicio</a>
                            <a href="../patient/profile.php" class="hover:underline" data-translate="IndexRolsPatients.Patient.profile">Perfil</a>
                            <a href="../patient/book_appointment.php" class="hover:underline" data-translate="IndexRolsPatients.Patient.book_appointment">Reservar Cita</a>
                            <a href="../patient/chat-Patient.php" class="hover:underline" data-translate="IndexRolsPatients.Patient.online_consultations">Consultas en Línea</a>
                            <button id="languageDropdown" class="text-white hover:text-gray-300 px-4">
                                <img src="http://localhost/medical_app/src/assets/flag/Spanish.png" alt="Language" class="w-6 h-6" id="currentLanguage" data-lang="es">
                            </button>
                            <a href="../../controllers/logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" data-translate="IndexRolsPatients.General.logout">Logout</a>
                          </nav>';
                } elseif ($_SESSION['role'] == 'doctor') {
                    echo '<nav class="flex space-x-4">
                            <a href="../doctor/index.php" class="hover:underline">Inicio</a>
                            <a href="../doctor/profile.php" class="hover:underline" data-translate="IndexRolsPatients.Doctor.profile">Perfil</a>
                            <a href="../doctor/appointment-request.php" class="hover:underline" data-translate="IndexRolsPatients.Doctor.appointments">Citas</a>
                            <a href="../doctor/chat-Doctor.php" class="hover:underline" data-translate="IndexRolsPatients.Doctor.online_consultations">Consultas en Línea</a>
                            <button id="languageDropdown" class="text-white hover:text-gray-300 px-4">
                                <img src="http://localhost/medical_app/src/assets/flag/Spanish.png" alt="Language" class="w-6 h-6" id="currentLanguage" data-lang="es">
                            </button>
                            <a href="../../controllers/logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" data-translate="IndexRolsPatients.General.logout">Logout</a>
                          </nav>';
                }
            } else {
                echo '<a href="../../views/auth/login.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" data-translate="IndexRolsPatients.General.login">Login</a>';
            }
            ?>
        </div>
    </header>
