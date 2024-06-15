<?php
include_once __DIR__ . '/../config/config.php';

class DoctorController {
    // Obtener el perfil del doctor
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @return void
     */
    public function getProfile($user_id) {
        global $conn;
        if ($conn === null) {
            die('Database connection not initialized.');
        }
        $sql = "SELECT doctor_id, user_id, first_name, last_name, specialties, availability_days, start_hour, end_hour FROM doctors WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $profile = $result->fetch_assoc();
            $profile['full_name'] = $profile['first_name'] . ' ' . $profile['last_name'];
            return $profile;
        } else {
            return null;
        }
    }

    // Actualizar disponibilidad y horas del doctor
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @param [type] $availability_days
     * @param [type] $start_hour
     * @param [type] $end_hour
     * @return void
     */
    public function updateAvailability($user_id, $availability_days, $start_hour, $end_hour) {
        global $conn;
        if ($conn === null) {
            error_log('Database connection not initialized.');
            return ['status' => 'error', 'message' => 'Database connection not initialized.'];
        }

        try {
            $sql = "UPDATE doctors SET availability_days = ?, start_hour = ?, end_hour = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $availability_days, $start_hour, $end_hour, $user_id);
            if ($stmt->execute()) {
                return ['status' => 'success'];
            } else {
                return ['status' => 'error', 'message' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // Manejar la solicitud de actualización de disponibilidad
    /**
     * Undocumented function
     *
     * @return void
     */
    public function handleUpdateAvailabilityRequest() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
            echo json_encode(['status' => 'error', 'message' => 'User not authenticated or not a doctor.']);
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $availability_days = $_POST['availability_days'];
        $start_hour = $_POST['start_hour'];
        $end_hour = $_POST['end_hour'];

        $result = $this->updateAvailability($user_id, $availability_days, $start_hour, $end_hour);
        echo json_encode($result);
    }

    // Obtener citas pendientes del doctor
    /**
     * Undocumented function
     *
     * @param [type] $doctor_id
     * @return void
     */
    public function getPendingAppointments($doctor_id) {
        global $conn;
        $sql = "SELECT a.appointment_date, a.appointment_time, p.first_name, p.last_name, p.contact_info, p.insurance_info 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                WHERE a.doctor_id = ? AND a.status = 'scheduled' AND a.appointment_date >= CURDATE()
                ORDER BY a.appointment_date, a.appointment_time";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        return $appointments;
    }

    // Obtener citas solicitadas del doctor
    /**
     * Undocumented function
     *
     * @param [type] $doctor_id
     * @return void
     */
    public function getRequestedAppointments($doctor_id) {
        global $conn;
        $sql = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, p.first_name, p.last_name, p.contact_info, p.insurance_info 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                WHERE a.doctor_id = ? AND a.status = 'scheduled'
                ORDER BY a.appointment_date, a.appointment_time";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        error_log("Requested Appointments: " . print_r($appointments, true));
        return $appointments;
    }

    // Obtener citas programadas del doctor
    /**
     * Undocumented function
     *
     * @param [type] $doctor_id
     * @return void
     */
    public function getScheduledAppointments($doctor_id) {
        global $conn;
        $sql = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, p.first_name, p.last_name, p.contact_info, p.insurance_info 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                WHERE a.doctor_id = ? AND a.status = 'scheduled' AND a.appointment_date >= CURDATE()
                ORDER BY a.appointment_date, a.appointment_time";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        return $appointments;
    }

    // Actualizar el estado de una cita
    /**
     * Undocumented function
     *
     * @param [type] $appointment_id
     * @param [type] $status
     * @return void
     */
    public function updateAppointmentStatus($appointment_id, $status) {
        global $conn;
        $sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $appointment_id);
        return $stmt->execute();
    }

    // Eliminar una cita
    /**
     * Undocumented function
     *
     * @param [type] $appointment_id
     * @return void
     */
    public function deleteAppointment($appointment_id) {
        global $conn;
        $sql = "DELETE FROM appointments WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $appointment_id);
        return $stmt->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        echo json_encode(['status' => 'error', 'message' => 'User not authenticated or not a doctor.']);
        exit();
    }

    $doctorController = new DoctorController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'updateAvailability':
                $doctorController->handleUpdateAvailabilityRequest();
                break;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    }
}
?>
