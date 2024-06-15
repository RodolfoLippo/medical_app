<?php
include_once '../config/config.php';

class AuthController {
    /**
     * Undocumented function
     *
     * @param [type] $username
     * @param [type] $password
     * @param [type] $confirm_password
     * @param [type] $role
     * @param [type] $dni
     * @param [type] $birth_date
     * @param [type] $first_name
     * @param [type] $last_name
     * @param [type] $contact_info
     * @param [type] $email
     * @param [type] $insurance_info
     * @param [type] $specialties
     * @return void
     */
    public function register($username, $password, $confirm_password, $role, $dni, $birth_date, $first_name, $last_name, $contact_info, $email, $insurance_info = null, $specialties = null) {
        if ($password !== $confirm_password) {
            echo "Passwords do not match.";
            return;
        }

        global $conn;
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO Users (username, password, role, dni, birth_date) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $username, $hashed_password, $role, $dni, $birth_date);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            if ($role == 'patient') {
                $sql_patient = "INSERT INTO Patients (user_id, first_name, last_name, contact_info, insurance_info, email) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_patient = $conn->prepare($sql_patient);
                $stmt_patient->bind_param('isssss', $user_id, $first_name, $last_name, $contact_info, $insurance_info, $email);
                if (!$stmt_patient->execute()) {
                    echo "Error: " . $stmt_patient->error;
                    return;
                }
            } elseif ($role == 'doctor') {
                $sql_doctor = "INSERT INTO Doctors (user_id, first_name, last_name, specialties, email) VALUES (?, ?, ?, ?, ?)";
                $stmt_doctor = $conn->prepare($sql_doctor);
                $stmt_doctor->bind_param('issss', $user_id, $first_name, $last_name, $specialties, $email);
                if (!$stmt_doctor->execute()) {
                    echo "Error: " . $stmt_doctor->error;
                    return;
                }
            }
            header("Location: ../views/auth/login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
/**
 * Undocumented function
 *
 * @param [type] $username
 * @param [type] $password
 * @return void
 */
    public function login($username, $password) { 
        global $conn;
        $sql = "SELECT * FROM Users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                echo json_encode(['status' => 'success', 'role' => $user['role']]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
    }
/**
 * Undocumented function
 *
 * @return void
 */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: ../views/auth/login.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'register':
                $authController->register(
                    $_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['role'], $_POST['dni'], $_POST['birth_date'],
                    $_POST['first_name'], $_POST['last_name'], $_POST['contact_info'], $_POST['email'], $_POST['insurance_info'], $_POST['specialties']
                );
                break;
            case 'login':
                $authController->login($_POST['username'], $_POST['password']);
                break;
            case 'logout':
                $authController->logout();
                break;
        }
    }
}
?>
