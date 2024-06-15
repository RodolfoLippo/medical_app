<?php
session_start();
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'es';
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="AboutUs.title">Acerca de Nosotros - Medical App</title>
    <link href="../output.css" rel="stylesheet">
    <link href="./css/estilos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100">
    <?php include './header.php'; ?>
    <div class="relative h-screen overflow-hidden">
        <div class="absolute inset-0 w-full h-full bg-gray-900 opacity-75"></div>
        <img class="absolute inset-0 w-full h-full object-cover opacity-25" src="../assets/background/001-backgroundHome.avif" alt="" />
        <div class="flex items-center justify-center h-full relative z-10">
            <div class="container mx-auto py-8 font-bold text-center">
                <h1 class="text-4xl font-bold mb-4 text-stone-950" data-translate="AboutUs.heading"></h1>
                <div class="mx-auto py-8">
                <p class="mb-4" data-translate="AboutUs.paragraph1"></p>
                <p data-translate="AboutUs.paragraph2"></p>
        </div>
            </div>
        </div>
    </div>
    <?php include '../views/layout/footer.php'; ?>
    <script src="../js/translation.js"></script>
</body>

</html>
