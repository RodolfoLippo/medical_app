<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['appointment_id']) || !isset($_GET['consultation_id'])) {
    die('Error: appointment_id o consultation_id no proporcionado.');
}

$appointment_id = $_GET['appointment_id'];
$consultation_id = $_GET['consultation_id'];

?>

<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="OnlineConsultation.title">Consulta en Línea</title>
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <link href="../../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <?php include '../layout/header.php'; ?>

    <div class="container mx-auto mt-10">
        <div class="max-w-4xl mx-auto bg-white p-8 border border-gray-300 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800" data-translate="OnlineConsultation.header">Consulta en Línea</h2>
            <input type="hidden" id="consultation_id" value="<?= htmlspecialchars($consultation_id) ?>">
            <div id="chat" class="w-full p-4">
                <div id="messages" class="border border-gray-300 rounded p-4 h-64 overflow-y-scroll bg-gray-50 mb-4"></div>
                <div class="flex mb-4">
                    <input type="text" id="messageInput" placeholder="Escribe un mensaje..." class="flex-grow border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" data-translate-placeholder="OnlineConsultation.type_message">
                    <button id="sendMessage" class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600" data-translate="OnlineConsultation.send">Enviar</button>
                </div>
                <button id="startCall" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" data-translate="OnlineConsultation.start_video_call">Iniciar Videollamada</button>
            </div>
            <div id="video-call" class="hidden mt-4">
                <video id="localVideo" autoplay muted class="w-full border border-gray-300 rounded"></video>
                <video id="remoteVideo" autoplay class="w-full border border-gray-300 rounded mt-2"></video>
                <button id="endCall" class="bg-red-500 text-white px-4 py-2 rounded mt-2 hover:bg-red-600" data-translate="OnlineConsultation.end_call">Terminar Llamada</button>
            </div>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>
    <script src="../../js/translation.js"></script>
    <script src="../../js/patientConsultation.js"></script>
    <script src="../../js/video_call.js"></script>
</body>
</html>
