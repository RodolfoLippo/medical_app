<?php
session_start();
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'es';
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="Login.title">Iniciar Sesión</title>
    <link href="../../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/translation.js"></script>
    <script src="../../js/login.js"></script>
    <link href="../../home/css/estilos.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include '../../home/header.php'; ?>

    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 rounded shadow">
            <h2 class="text-2xl font-bold mb-5" data-translate="Login.heading">Iniciar Sesión</h2>
            <form id="loginForm">
                <div class="mb-4">
                    <label class="block text-gray-700" data-translate="Login.username_label">Nombre de Usuario:</label>
                    <input type="text" id="username" name="username" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" data-translate="Login.password_label">Contraseña:</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded" data-translate="Login.submit_button">Iniciar Sesión</button>
                <div id="loginResponse" class="mt-4"></div>
                <input type="hidden" name="action" value="login">
            </form>
            <p class="mt-4" data-translate="Login.register_prompt">¿No tienes una cuenta?</p>
            <a href="register.php" class="text-blue-500 hover:underline" data-translate="Login.register_link">Regístrate aquí</a>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>
</body>

</html>
