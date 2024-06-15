$(document).ready(function() {
    $('#role').on('change', function() {
        var role = $(this).val();
        if (role === 'doctor') {
            $('#patientFields').hide();
            $('#doctorFields').show();
        } else {
            $('#patientFields').show();
            $('#doctorFields').hide();
        }
    });

    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize() + '&action=register';
        $.ajax({
            type: 'POST',
            url: '../../controllers/AuthController.php',
            data: formData,
            success: function(response) {
                $('#registerResponse').html('<p class="text-green-500">' + response + '</p>');
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 100);
            },
            error: function() {
                $('#registerResponse').html('<p class="text-red-500">An error occurred. Please try again.</p>');
            }
        });
    });
});