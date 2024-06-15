<?php
include_once '../config/config.php';

class Doctor {
    public $doctor_id;
    public $user_id;
    public $full_name;
    public $specialties;
    public $availability;

    public function __construct($doctor_id, $user_id, $full_name, $specialties, $availability) {
        $this->doctor_id = $doctor_id;
        $this->user_id = $user_id;
        $this->full_name = $full_name;
        $this->specialties = $specialties;
        $this->availability = $availability;
    }

    public static function getProfile($doctor_id) {
        global $conn;
        $sql = "SELECT * FROM Doctors WHERE doctor_id='$doctor_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new self($row['doctor_id'], $row['user_id'], $row['full_name'], $row['specialties'], $row['availability']);
        }
        return null;
    }

    public static function updateProfile($doctor_id, $full_name, $specialties, $availability) {
        global $conn;
        $sql = "UPDATE Doctors SET full_name='$full_name', specialties='$specialties', availability='$availability' WHERE doctor_id='$doctor_id'";
        if ($conn->query($sql) === TRUE) {
            return "Profile updated successfully!";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public static function getAppointments($doctor_id) {
        global $conn;
        $sql = "SELECT * FROM Appointments WHERE doctor_id='$doctor_id'";
        $result = $conn->query($sql);
        $appointments = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }
}
?>
