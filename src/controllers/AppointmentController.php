<?php
// Incluir el archivo de configuración
include_once '../config/config.php';

class AppointmentController {
    // Obtener citas disponibles
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getAvailableAppointments() {
        global $conn;
        $sql = "SELECT doctor_id, doctor_name, specialty FROM Doctors"; // Consulta para obtener doctores disponibles
        $result = $conn->query($sql);
        $appointments = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row; // Almacenar los resultados en un array
            }
        }
        return $appointments; // Devolver la lista de doctores disponibles
    }

    // Reservar una cita
    /**
     * Undocumented function
     *
     * @param [type] $patient_id
     * @param [type] $doctor_id
     * @param [type] $appointment_date
     * @return void
     */
    public function bookAppointment($patient_id, $doctor_id, $appointment_date) {
        global $conn;
        // Insertar una nueva cita en la base de datos
        $sql = "INSERT INTO Appointments (patient_id, doctor_id, appointment_date, status) VALUES ('$patient_id', '$doctor_id', '$appointment_date', 'scheduled')";
        if ($conn->query($sql) === TRUE) {
            // Enviar confirmación por correo electrónico o SMS
            $this->sendConfirmation($patient_id, $appointment_date);
            echo "Appointment booked successfully!"; // Mensaje de éxito
        } else {
            // Mensaje de error en caso de fallo
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    /* Enviar confirmación de la cita
    private function sendConfirmation($patient_id, $appointment_date) {
        global $conn;
        // Obtener la información de contacto del paciente
        $sql = "SELECT contact_info FROM Patients WHERE patient_id='$patient_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $patient = $result->fetch_assoc();
            $contact_info = $patient['contact_info'];
            // Lógica para enviar correo electrónico o SMS
            $subject = "Appointment Confirmation";
            $message = "Your appointment is scheduled for $appointment_date.";
            mail($contact_info, $subject, $message);
        }
    }
        */

    // Confirmar una cita
    /**
     * Undocumented function
     *
     * @param [type] $appointment_id
     * @return void
     */
    public function confirmAppointment($appointment_id) {
        global $conn;
        // Actualizar el estado de la cita a 'confirmed'
        $sql = "UPDATE Appointments SET status='confirmed' WHERE appointment_id='$appointment_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Appointment confirmed!";
        } else {
            // Mensaje de error en caso de fallo
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Cancelar una cita
    /**
     * Undocumented function
     *
     * @param [type] $appointment_id
     * @return void
     */
    public function cancelAppointment($appointment_id) {
        global $conn;
        // Actualizar el estado de la cita a 'canceled'
        $sql = "UPDATE Appointments SET status='canceled' WHERE appointment_id='$appointment_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Appointment canceled!"; // Mensaje de éxito
        } else {
            // Mensaje de error en caso de fallo
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Obtener citas del doctor /**
     * Undocumented function
     *
     * @param [type] $doctor_id
     * @return void
     */
    public function getAppointments($doctor_id) {
        global $conn;
        // Obtener todas las citas del doctor por su ID
        $sql = "SELECT * FROM Appointments WHERE doctor_id='$doctor_id'";
        $result = $conn->query($sql);
        $appointments = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row; // Almacenar los resultados en un array
            }
        }
        return $appointments; // Devolver la lista de citas del doctor
    }
}

// Manejar las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentController = new AppointmentController();

    if (isset($_POST['bookAppointment'])) {
        // Reservar una cita
        $appointmentController->bookAppointment($_POST['patient_id'], $_POST['doctor_id'], $_POST['appointment_date']);
    } elseif (isset($_POST['book_appointment'])) {
        // Reservar una cita usando la sesión del usuario
        session_start();
        $response = $appointmentController->bookAppointment($_SESSION['user_id'], $_POST['doctor'], $_POST['date']);
        echo $response;
    } elseif (isset($_POST['confirm_appointment'])) {
        // Confirmar una cita
        $appointmentController->confirmAppointment($_POST['appointment_id']);
    } elseif (isset($_POST['cancel_appointment'])) {
        // Cancelar una cita
        $appointmentController->cancelAppointment($_POST['appointment_id']);
    }
}
?>
