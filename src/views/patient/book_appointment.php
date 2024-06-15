<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit();
}

include_once '../../config/config.php';
include_once '../../controllers/BookingController.php';

// Función para obtener patient_id desde user_id
/**
 * Undocumented function
 *
 * @param [type] $user_id
 * @param [type] $conn
 * @return void
 */
function getPatientId($user_id, $conn) {
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

$patient_id = getPatientId($_SESSION['user_id'], $conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_type = $_POST['appointment_type'];

    $bookingController = new BookingController();
    $response = $bookingController->bookAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $appointment_type);

    if ($response['status'] == 'success') {
        header("Location: profile.php");
        exit();
    } else {
        echo $response['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <title data-translate="BookAppointment.title">Book Appointment</title>
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <link href="../../output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../../js/appointment.js"></script>
    <script src="../../js/translation.js"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <?php include '../layout/header.php'; ?>
    <div class="container mx-auto mt-12">
        <div class="bg-white p-8 rounded shadow-lg w-full max-w-lg mx-auto">
            <h1 class="text-2xl font-bold mb-6" data-translate="BookAppointment.title">Book Appointment</h1>
            <form method="POST" action="book_appointment.php">
                <div class="mb-4">
                    <label for="specialties" class="block text-gray-700" data-translate="BookAppointment.choose_specialty">Choose Specialty:</label>
                    <select id="specialties" name="specialties" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" data-translate="BookAppointment.select_specialty">Select a Specialty</option>
                        <option value="Medicina General / Medicina de Familia" data-translate="Specialties.general_medicine">Medicina General / Medicina de Familia</option>
                        <option value="Pediatría" data-translate="Specialties.pediatrics">Pediatría</option>
                        <option value="Ginecología y Obstetricia" data-translate="Specialties.gynecology">Ginecología y Obstetricia</option>
                        <option value="Cardiología" data-translate="Specialties.cardiology">Cardiología</option>
                        <option value="Dermatología" data-translate="Specialties.dermatology">Dermatología</option>
                        <option value="Neurología" data-translate="Specialties.neurology">Neurología</option>
                        <option value="Psiquiatría" data-translate="Specialties.psychiatry">Psiquiatría</option>
                        <option value="Oftalmología" data-translate="Specialties.ophthalmology">Oftalmología</option>
                        <option value="Otorrinolaringología" data-translate="Specialties.otolaryngology">Otorrinolaringología</option>
                        <option value="Ortopedia" data-translate="Specialties.orthopedics">Ortopedia</option>
                        <option value="Gastroenterología" data-translate="Specialties.gastroenterology">Gastroenterología</option>
                        <option value="Endocrinología" data-translate="Specialties.endocrinology">Endocrinología</option>
                        <option value="Nefrología" data-translate="Specialties.nephrology">Nefrología</option>
                        <option value="Urología" data-translate="Specialties.urology">Urología</option>
                        <option value="Oncología" data-translate="Specialties.oncology">Oncología</option>
                        <option value="Hematología" data-translate="Specialties.hematology">Hematología</option>
                        <option value="Neumología" data-translate="Specialties.pulmonology">Neumología</option>
                        <option value="Reumatología" data-translate="Specialties.rheumatology">Reumatología</option>
                        <option value="Infectología" data-translate="Specialties.infectiology">Infectología</option>
                        <option value="Cirugía General" data-translate="Specialties.general_surgery">Cirugía General</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="doctor_id" class="block text-gray-700" data-translate="BookAppointment.choose_doctor">Choose Doctor:</label>
                    <select id="doctor_id" name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" data-translate="BookAppointment.select_doctor">Select a Doctor</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="appointment_date" class="block text-gray-700" data-translate="BookAppointment.choose_date">Choose Date:</label>
                    <input type="text" name="appointment_date" id="appointment_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="appointment_time" class="block text-gray-700" data-translate="BookAppointment.choose_time">Choose Time:</label>
                    <select name="appointment_time" id="appointment_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" data-translate="BookAppointment.select_time">Select a Time</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="appointment_type" class="block text-gray-700" data-translate="BookAppointment.choose_appointment_type">Choose Appointment Type:</label>
                    <select id="appointment_type" name="appointment_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="in_person" data-translate="BookAppointment.in_person">In-Person</option>
                        <option value="online" data-translate="BookAppointment.online">Online</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-600" data-translate="BookAppointment.book_appointment">Book Appointment</button>
            </form>
        </div>
    </div>
    <?php include '../layout/footer.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#appointment_date", {
                enableTime: false,
                dateFormat: "Y-m-d",
            });
        });
    </script>
</body>
</html>
