function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function deleteAccount() {
    if (confirm("¿Está seguro de que desea eliminar su cuenta?")) {
        $.post('../../controllers/PatientController.php', { action: 'deleteAccount' }, function(response) {
            try {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    window.location.href = 'http://localhost/medical_app/src/index.php';
                } else {
                    alert(result.message || 'Error al eliminar la cuenta.');
                }
            } catch (e) {
                alert('Error inesperado: ' + response);
            }
        });
    }
}

$(document).ready(function() {
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize() + '&action=updateProfile';
        $.post('../../controllers/PatientController.php', data, function(response) {
            try {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Perfil actualizado correctamente.');
                    location.reload();
                } else {
                    alert(result.message || 'Error al actualizar el perfil.');
                }
            } catch (e) {
                alert('Error inesperado: ' + response);
            }
        });
    });
});
