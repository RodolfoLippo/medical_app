<?php
include_once '../config/config.php';

class User {
    public $user_id;
    public $username;
    public $password;
    public $role;
    public $dni;
    public $birth_date;
    public $contact_info;
    public $insurance_info;

    public function __construct($user_id, $username, $password, $role, $dni, $birth_date, $contact_info, $insurance_info = null) {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->dni = $dni;
        $this->birth_date = $birth_date;
        $this->contact_info = $contact_info;
        $this->insurance_info = $insurance_info;
    }

    // Registrar un nuevo usuario
    public static function register($username, $password, $role, $dni, $birth_date, $contact_info, $insurance_info = null) {
        global $conn;
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO Users (username, password, role, dni, birth_date, contact_info, insurance_info) VALUES ('$username', '$hashed_password', '$role', '$dni', '$birth_date', '$contact_info', '$insurance_info')";
        if ($conn->query($sql) === TRUE) {
            return "Registration successful!";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Iniciar sesión de un usuario
    public static function login($username, $password) {
        global $conn;
        $sql = "SELECT * FROM Users WHERE username='$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['role'] = $row['role'];
                return true;
            } else {
                return "Invalid password.";
            }
        } else {
            return "No user found.";
        }
    }

    // Obtener perfil del usuario
    public static function getProfile($user_id) {
        global $conn;
        $sql = "SELECT * FROM Users WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new self($row['user_id'], $row['username'], $row['password'], $row['role'], $row['dni'], $row['birth_date'], $row['contact_info'], $row['insurance_info']);
        }
        return null;
    }

    // Actualizar perfil del usuario
    public static function updateProfile($user_id, $username, $password, $role, $dni, $birth_date, $contact_info, $insurance_info = null) {
        global $conn;
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE Users SET username='$username', password='$hashed_password', role='$role', dni='$dni', birth_date='$birth_date', contact_info='$contact_info', insurance_info='$insurance_info' WHERE user_id='$user_id'";
        if ($conn->query($sql) === TRUE) {
            return "Profile updated successfully!";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Cerrar sesión de un usuario
    public static function logout() {
        session_start();
        session_destroy();
        header("Location: ../views/auth/login.php");
        exit();
    }
}
?>
