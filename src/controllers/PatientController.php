<?php
include_once __DIR__ . '/../config/config.php';

class PatientController {
    // Obtener el perfil del paciente
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @return void
     */
    public function getProfile($user_id) {
        global $conn;
        if ($conn === null) {
            die(json_encode(['status' => 'error', 'message' => 'Database connection not initialized.']));
        }
        $sql = "SELECT patient_id, user_id, first_name, last_name, contact_info, insurance_info, medical_history FROM Patients WHERE user_id = ?";
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

    // Obtener la información del usuario
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @return void
     */
    public function getUser($user_id) {
        global $conn;
        if ($conn === null) {
            error_log('Database connection not initialized.');
            return null;
        }

        $sql = "SELECT username FROM Users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Actualizar el perfil del paciente y el nombre de usuario
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @param [type] $username
     * @param [type] $contact_info
     * @param [type] $insurance_info
     * @return void
     */
    public function updateProfile($user_id, $username, $contact_info, $insurance_info) {
        global $conn;
        if ($conn === null) {
            error_log('Database connection not initialized.');
            return ['status' => 'error', 'message' => 'Database connection not initialized.'];
        }

        try {
            $conn->begin_transaction();

            $sql1 = "UPDATE Users SET username = ? WHERE user_id = ?";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("si", $username, $user_id);
            if (!$stmt1->execute()) {
                throw new Exception("Error updating Users: " . $stmt1->error);
            }

            $sql2 = "UPDATE Patients SET contact_info = ?, insurance_info = ? WHERE user_id = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ssi", $contact_info, $insurance_info, $user_id);
            if (!$stmt2->execute()) {
                throw new Exception("Error updating Patients: " . $stmt2->error);
            }

            $conn->commit();
            return ['status' => 'success'];
        } catch (Exception $e) {
            $conn->rollback();
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // Manejar la solicitud de actualización de perfil
    /**
     * Undocumented function
     *
     * @return void
     */
    public function handleUpdateProfileRequest() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
            echo json_encode(['status' => 'error', 'message' => 'User not authenticated or not a patient.']);
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        $username = $_POST['username'];
        $contact_info = $_POST['contact_info'];
        $insurance_info = $_POST['insurance_info'];

        $result = $this->updateProfile($user_id, $username, $contact_info, $insurance_info);
        echo json_encode($result);
    }

    // Eliminar la cuenta del usuario
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @return void
     */
    public function deleteAccount($user_id) {
        global $conn;
        if ($conn === null) {
            error_log('Database connection not initialized.');
            return ['status' => 'error', 'message' => 'Database connection not initialized.'];
        }

        try {
           
            $conn->begin_transaction();

            // Eliminar de users
            $sql = "DELETE FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting from users: " . $stmt->error);
            }

            // Confirmar transacción
            $conn->commit();
            return ['status' => 'success'];
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conn->rollback();
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // Obtener doctores por especialidad
    /**
     * Undocumented function
     *
     * @param [type] $specialty
     * @return void
     */
    public function getDoctorsBySpecialty($specialty) {
        global $conn;
        $sql = "SELECT doctor_id, first_name, last_name FROM Doctors WHERE specialties = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $specialty);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        return $doctors;
    }

    // Obtener espacios disponibles de un doctor en una fecha específica
    /**
     * Undocumented function
     *
     * @param [type] $doctor_id
     * @param [type] $appointment_date
     * @return void
     */
    public function getAvailableSlots($doctor_id, $appointment_date) {
        global $conn;
        $sql = "SELECT available_date, start_time, end_time FROM DoctorAvailability WHERE doctor_id = ? AND available_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $doctor_id, $appointment_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $slots = [];
        while ($row = $result->fetch_assoc()) {
            $slots[] = $row;
        }
        return $slots;
    }

    // Reservar una cita
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @param [type] $doctor_id
     * @param [type] $appointment_date
     * @param [type] $appointment_time
     * @param [type] $appointment_type
     * @return void
     */
    public function bookAppointment($user_id, $doctor_id, $appointment_date, $appointment_time, $appointment_type) {
        global $conn;
        $patient_id = $this->getPatientId($user_id);
        $sql = "INSERT INTO Appointments (patient_id, doctor_id, appointment_date, appointment_time, appointment_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $patient_id, $doctor_id, $appointment_date, $appointment_time, $appointment_type);
        
        if ($stmt->execute()) {
            echo "Appointment booked successfully!";
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
    }

    // Obtener el ID del paciente basado en el ID del usuario
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @return void
     */
    public function getPatientId($user_id) {
        global $conn;
        $sql = "SELECT patient_id FROM Patients WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['patient_id'];
        }
        return null;
    }

    // Obtener el historial de citas del paciente
    /**
     * Undocumented function
     *
     * @param [type] $patient_id
     * @return void
     */
    public function getAppointmentHistory($patient_id) {
        global $conn;
        $sql = "SELECT * FROM Appointments WHERE patient_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }

    // Obtener las citas pendientes del usuario
    /**
     * Undocumented function
     *
     * @param [type] $user_id
     * @return void
     */
    public function getAppointments($user_id) {
        global $conn;
        $patient_id = $this->getPatientId($user_id);
        $sql = "SELECT a.appointment_date, a.appointment_time, a.appointment_type, d.first_name, d.last_name, d.specialties 
                FROM Appointments a 
                JOIN Doctors d ON a.doctor_id = d.doctor_id 
                WHERE a.patient_id = ? AND a.appointment_date >= CURDATE()
                ORDER BY a.appointment_date, a.appointment_time";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        return $appointments;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        echo json_encode(['status' => 'error', 'message' => 'User not authenticated or not a patient.']);
        exit();
    }
    
    $patientController = new PatientController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'deleteAccount':
                $result = $patientController->deleteAccount($_SESSION['user_id']);
                if ($result['status'] === 'success') {
                    session_destroy();
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode($result);
                }
                break;
            case 'updateProfile':
                $patientController->handleUpdateProfileRequest();
                break;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    }
}
?>
