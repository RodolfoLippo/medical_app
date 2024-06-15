<?php
include_once '../config/config.php';

class Patient {
    public $patient_id;
    public $user_id;
    public $full_name;
    public $contact_info;
    public $insurance_info;
    public $medical_history;

    public function __construct($patient_id, $user_id, $full_name, $contact_info, $insurance_info, $medical_history) {
        $this->patient_id = $patient_id;
        $this->user_id = $user_id;
        $this->full_name = $full_name;
        $this->contact_info = $contact_info;
        $this->insurance_info = $insurance_info;
        $this->medical_history = $medical_history;
    }

    public static function getProfile($patient_id) {
        global $conn;
        $sql = "SELECT * FROM Patients WHERE patient_id='$patient_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new self($row['patient_id'], $row['user_id'], $row['full_name'], $row['contact_info'], $row['insurance_info'], $row['medical_history']);
        }
        return null;
    }

    public static function updateProfile($patient_id, $full_name, $contact_info, $insurance_info, $medical_history) {
        global $conn;
        $sql = "UPDATE Patients SET full_name='$full_name', contact_info='$contact_info', insurance_info='$insurance_info', medical_history='$medical_history' WHERE patient_id='$patient_id'";
        if ($conn->query($sql) === TRUE) {
            return "Profile updated successfully!";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public static function getAppointmentHistory($patient_id) {
        global $conn;
        $sql = "SELECT * FROM Appointments WHERE patient_id='$patient_id'";
        $result = $conn->query($sql);
        $appointments = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }

    public static function getTestResults($patient_id) {
        global $conn;
        $sql = "SELECT * FROM TestResults WHERE patient_id='$patient_id'";
        $result = $conn->query($sql);
        $testResults = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $testResults[] = $row;
            }
        }
        return $testResults;
    }
}
?>
