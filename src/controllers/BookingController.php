<?php
include_once '../../config/config.php';

class BookingController {
    /**
     * Undocumented function
     *
     * @param [type] $patient_id
     * @param [type] $doctor_id
     * @param [type] $appointment_date
     * @param [type] $appointment_time
     * @param [type] $appointment_type
     * @return void
     */
    public function bookAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $appointment_type) {
        global $conn;

        // Insertar la nueva cita
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, appointment_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return ["status" => "error", "message" => "Error in query: " . $conn->error];
        }
        $stmt->bind_param('iisss', $patient_id, $doctor_id, $appointment_date, $appointment_time, $appointment_type);

        if ($stmt->execute()) {
            $appointment_id = $stmt->insert_id;

            // Si la cita es online, crear una consulta
            if ($appointment_type == 'online') {
                $sql = "INSERT INTO consultations (patient_id, doctor_id, appointment_id, consultation_date) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    return ["status" => "error", "message" => "Error in query: " . $conn->error];
                }
                $stmt->bind_param('iiis', $patient_id, $doctor_id, $appointment_id, $appointment_date);
                if ($stmt->execute()) {
                    return ["status" => "success", "message" => "Appointment and consultation booked successfully!"];
                } else {
                    return ["status" => "error", "message" => "Error: " . $stmt->error];
                }
            }

            return ["status" => "success", "message" => "Appointment booked successfully!"];
        } else {
            return ["status" => "error", "message" => "Error: " . $stmt->error];
        }
    }
}
?>
