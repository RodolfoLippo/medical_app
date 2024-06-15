function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

$(document).ready(function() {
    // Manejar la selección de días
    $('.day-btn').on('click', function() {
        $(this).toggleClass('bg-gray-200 bg-blue-500 text-white');
    });

    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();

        // Obtener los días seleccionados
        let selectedDays = [];
        $('.day-btn.bg-blue-500').each(function() {
            selectedDays.push($(this).data('day'));
        });

        const data = $(this).serialize() + '&availability_days=' + selectedDays.join(',') + '&action=updateAvailability';
        $.post('../../controllers/DoctorController.php', data, function(response) {
            try {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Disponibilidad actualizada correctamente.');
                    location.reload();
                } else {
                    alert(result.message || 'Error al actualizar la disponibilidad.');
                }
            } catch (e) {
                alert('Error inesperado: ' + response);
            }
        });
    });
});
