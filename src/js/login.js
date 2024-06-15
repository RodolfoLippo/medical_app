$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../../controllers/AuthController.php',
            data: $(this).serialize(),
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    if (res.role === 'patient') {
                        window.location.href = '../patient/index.php';
                    } else if (res.role === 'doctor') {
                        window.location.href = '../doctor/index.php';
                    }
                } else {
                    $('#loginResponse').html('<p class="text-red-500">' + res.message + '</p>');
                }
            },
            error: function() {
                $('#loginResponse').html('<p class="text-red-500">Error al iniciar sesión. Por favor, inténtelo de nuevo.</p>');
            }
        });
    });
});