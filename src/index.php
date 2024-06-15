<?php
session_start();
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'es';
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical App</title>
    <link href="./output.css" rel="stylesheet">
    <link href="./home/css/estilos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100">
    <?php include 'home/header.php'; ?>
    <div class="relative h-screen overflow-hidden">
        <img class="absolute inset-0 w-full h-full object-cover opacity-50" src="./assets/background/001-backgroundHome.avif" alt="Nombre de la empresa" />
        <main class="relative container mx-auto flex flex-col items-center space-y-8 py-8">
            <div class="max-w-4xl w-full bg-white p-8 border border-gray-300 rounded shadow text-center z-10">
                <h2 class="text-2xl font-bold mb-5" data-translate="Index.welcome_message"></h2>
                <p class="mb-5" data-translate="Index.description"></p>
            </div>
            <section class="max-w-4xl w-full bg-white p-8 border border-gray-300 rounded shadow mb-8 z-10">
                <h2 class="text-3xl font-semibold mb-4 text-center" data-translate="Index.services"></h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 shadow rounded">
                        <h3 class="text-xl font-bold mb-4" data-translate="Index.consultation"></h3>
                        <p class="mb-2" data-translate="Index.consultation_description"></p>
                        <a href="home/Consultas.php" class="text-blue-500 hover:underline" data-translate="Index.learnMore"></a>
                    </div>
                    <div class="bg-white p-4 shadow rounded">
                        <h3 class="text-xl font-bold mb-4" data-translate="Index.online_consultation"></h3>
                        <p class="mb-2" data-translate="Index.online_consultation_description"></p>
                        <a href="home/Consultas.php" class="text-blue-500 hover:underline" data-translate="Index.learnMore"></a>
                    </div>
                </div>
            </section>
            <div class="max-w-4xl w-full bg-white p-8 border border-gray-300 rounded shadow z-10">
                <h2 class="text-xl font-bold mb-4" data-translate="Index.about_us"></h2>
                <p data-translate="Index.about_us_description"></p>
            </div>
            <div class="max-w-4xl w-full bg-white p-8 border border-gray-300 rounded shadow z-10">
                <h2 class="text-xl font-bold mb-4" data-translate="Index.privacy_policy"></h2>
                <p data-translate="Index.privacy_policy_description"></p>
            </div>

        </main>
    </div>
    <?php include './views/layout/footer.php'; ?>
    <script src="js/translation.js"></script>
</body>

</html>
