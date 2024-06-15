<?php
session_start();
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'es';
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="PrivacyPolicy.title">Política de Privacidad - Medical App</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100">
    <?php include './header.php'; ?>
    <div class="relative h-screen overflow-hidden">
        <div class="absolute inset-0 w-full h-full bg-gray-900 opacity-75"></div>
        <img class="absolute inset-0 w-full h-full object-cover opacity-25" src="../assets/background/001-backgroundHome.avif" alt="" />
        <div class="flex items-center justify-center h-full relative z-10">
            <div class="container mx-auto py-8 font-bold text-center">
                <h1 class="text-4xl font-bold mb-4 text-stone-950" data-translate="PrivacyPolicy.heading">Política de Privacidad</h1>
                <p class="mb-4 text-stone-950" data-translate="PrivacyPolicy.paragraph1">En Medical App, nos tomamos muy en serio la privacidad de nuestros usuarios. Esta política de privacidad describe cómo recopilamos, usamos y protegemos su información personal.</p>
                
                <h2 class="text-2xl font-bold mb-2 text-stone-950" data-translate="PrivacyPolicy.collection_heading">Recopilación de Información</h2>
                <p class="mb-4 text-stone-950" data-translate="PrivacyPolicy.collection_paragraph">Recopilamos información personal que usted nos proporciona directamente, como su nombre, dirección de correo electrónico y detalles de la cita médica. También podemos recopilar información automáticamente a través de su uso de nuestra plataforma.</p>
                
                <h2 class="text-2xl font-bold mb-2 text-stone-950" data-translate="PrivacyPolicy.usage_heading">Uso de la Información</h2>
                <p class="mb-4 text-stone-950" data-translate="PrivacyPolicy.usage_paragraph">Usamos la información recopilada para proporcionar y mejorar nuestros servicios, comunicar actualizaciones importantes y personalizar su experiencia en nuestra plataforma.</p>
                
                <h2 class="text-2xl font-bold mb-2 text-stone-950" data-translate="PrivacyPolicy.protection_heading">Protección de la Información</h2>
                <p class="mb-4 text-stone-950" data-translate="PrivacyPolicy.protection_paragraph">Implementamos medidas de seguridad adecuadas para proteger su información personal contra el acceso no autorizado, alteración, divulgación o destrucción.</p>
                
                <p class="text-stone-950" data-translate="PrivacyPolicy.paragraph2">Para más información, por favor lea nuestra política de privacidad completa o contáctenos si tiene alguna pregunta.</p>
            </div>
        </div>
    </div>
    <?php include '../views/layout/footer.php'; ?>
    <script src="../js/translation.js"></script>
</body>

</html>
