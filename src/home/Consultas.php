<?php
session_start();
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'es';
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas - Medical App</title>
    <link href="../output.css" rel="stylesheet">
    <link href="../home/css/estilos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100">
    <?php include '../home/header.php'; ?>
    <div class="relative h-screen overflow-hidden">
        <img class="absolute inset-0 w-full h-full object-cover opacity-50" src="../assets/background/001-backgroundHome.avif" alt="Nombre de la empresa" />
        <main class="relative container mx-auto flex flex-col items-center space-y-8 py-8">
            <section class="max-w-4xl w-full bg-white p-8 border border-gray-300 rounded shadow mb-8 z-10">
                <h2 class="text-3xl font-semibold mb-4 text-center" data-translate="Consultas.title">Consultas</h2>
                <p class="mb-5" data-translate="Consultas.description">Obtén una consulta profesional de nuestros doctores experimentados para resolver tus problemas de salud.</p>
                <a href="#consulta_presencial" class="text-blue-500 hover:underline" data-translate="Consultas.learnMore">Saber Más...</a>
            </section>
            <section class="max-w-4xl w-full bg-white p-8 border border-gray-300 rounded shadow mb-8 z-10">
                <h2 class="text-3xl font-semibold mb-4 text-center" data-translate="ConsultasOnline.title">Consulta en Línea</h2>
                <p class="mb-5" data-translate="ConsultasOnline.description">Consulta con nuestros doctores en línea desde la comodidad de tu hogar, usando nuestra plataforma de telemedicina.</p>
                <a href="#consulta_en_linea" class="text-blue-500 hover:underline" data-translate="ConsultasOnline.learnMore">Saber Más...</a>
            </section>
            <div id="consulta_presencial" class="max-w-4xl w-full bg-white p-8 border border-gray-300 rounded shadow z-10">
                <h2 class="text-2xl font-bold mb-4" data-translate="Consultas.sectionTitle">Consultas Presenciales</h2>
                <p data-translate="Consultas.sectionDescription">En nuestras consultas presenciales, recibirás atención personalizada de nuestros médicos expertos. Programar una cita es fácil y rápido.</p>
                <a href="../home/Consultas.php" class="text-blue-500 hover:underline" data-translate="Consultas.bookNow">Reservar Ahora</a>
            </div>
            <div id="consulta_en_linea" class="max-w-4xl w-full bg-white p-8 border border-gray-300 rounded shadow z-10">
                <h2 class="text-2xl font-bold mb-4" data-translate="ConsultasOnline.sectionTitle">Consulta en Línea</h2>
                <p data-translate="ConsultasOnline.sectionDescription">La consulta en línea te permite acceder a nuestros servicios médicos sin salir de casa. Conéctate con nuestros doctores a través de videollamadas.</p>
                <a href="../home/Consultas.php" class="text-blue-500 hover:underline" data-translate="ConsultasOnline.bookNow">Reservar Ahora</a>
            </div>
        </main>
    </div>
    <?php include '../views/layout/footer.php'; ?>
    <script src="../js/translation.js"></script>
</body>

</html>
