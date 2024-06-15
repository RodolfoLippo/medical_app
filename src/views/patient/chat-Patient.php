<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit();
}

include_once '../../config/config.php';
include_once '../../controllers/PatientController.php';
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

$sql = "SELECT a.*, c.consultation_id, d.first_name, d.last_name, d.specialties FROM Appointments a
        JOIN Consultations c ON a.appointment_id = c.appointment_id
        JOIN Doctors d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'es'; ?>">
<head>
    <meta charset="UTF-8">
    <title data-translate="Profile.title">Profile</title>
    <link href="../../home/css/estilos.css" rel="stylesheet">
    <link href="../../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <?php include '../layout/header.php'; ?>
    <div class="container mx-auto mt-auto p-4 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold mb-8" data-translate="Profile.header">Citas del Paciente</h1>
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2" data-translate="Profile.doctor_name">Nombre del Doctor</th>
                    <th class="px-4 py-2" data-translate="Profile.specialty">Especialidad</th>
                    <th class="px-4 py-2" data-translate="Profile.date">Fecha</th>
                    <th class="px-4 py-2" data-translate="Profile.time">Hora</th>
                    <th class="px-4 py-2" data-translate="Profile.status">Estado</th>
                    <th class="px-4 py-2" data-translate="Profile.type">Tipo</th>
                    <th class="px-4 py-2" data-translate="Profile.action">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['specialties']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['appointment_date']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['appointment_time']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['status']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['appointment_type']) ?></td>
                        <td class="px-4 py-2">
                            <a class="text-blue-500 hover:underline" href="online_consultation.php?appointment_id=<?= $row['appointment_id'] ?>&consultation_id=<?= $row['consultation_id'] ?>" data-translate="Profile.join_consultation">Unirse a la Consulta</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include '../layout/footer.php'; ?>

</body>
</html>
