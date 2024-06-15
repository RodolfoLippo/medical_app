<?php
session_start();
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'es';
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="Register.title">Register</title>
    <link href="../../output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../../home/css/estilos.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<?php include '../../home/header.php'; ?>
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 rounded shadow">
            <h2 class="text-2xl font-bold mb-5" data-translate="Register.heading">Register</h2>
            <form id="registerForm">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700" data-translate="Register.username_label">Username</label>
                    <input type="text" id="username" name="username" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700" data-translate="Register.password_label">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700" data-translate="Register.confirm_password_label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-gray-700" data-translate="Register.role_label">Role</label>
                    <select id="role" name="role" class="mt-1 block w-full" required>
                        <option value="patient" data-translate="Register.role_patient">Patient</option>
                        <option value="doctor" data-translate="Register.role_doctor">Doctor</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dni" class="block text-gray-700" data-translate="Register.dni_label">DNI</label>
                    <input type="text" id="dni" name="dni" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="birth_date" class="block text-gray-700" data-translate="Register.birth_date_label">Birth Date</label>
                    <input type="date" id="birth_date" name="birth_date" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="first_name" class="block text-gray-700" data-translate="Register.first_name_label">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-gray-700" data-translate="Register.last_name_label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700" data-translate="Register.email_label">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full" required>
                </div>

                <div class="mb-4">
                    <label for="contact_info" class="block text-gray-700" data-translate="Register.address">Dirección</label>
                    <input type="text" id="contact_info" name="contact_info" class="mt-1 block w-full" required>
                </div>


                <div class="mb-4" id="patientFields">
                    <label for="insurance_info" class="block text-gray-700" data-translate="Register.insurance_info_label">Insurance Info</label>
                    <input type="text" id="insurance_info" name="insurance_info" class="mt-1 block w-full">
                </div>
                <div class="mb-4" id="doctorFields" style="display: none;">
                    <label for="specialties" class="block text-gray-700" data-translate="Register.specialties_label">Specialties</label>
                    <select id="specialties" name="specialties" class="mt-1 block w-full">
                        <option value="Medicina General / Medicina de Familia" data-translate="Register.specialty_general">Medicina General / Medicina de Familia</option>
                        <option value="Pediatría" data-translate="Register.specialty_pediatrics">Pediatría</option>
                        <option value="Ginecología y Obstetricia" data-translate="Register.specialty_gynecology">Ginecología y Obstetricia</option>
                        <option value="Cardiología" data-translate="Register.specialty_cardiology">Cardiología</option>
                        <option value="Dermatología" data-translate="Register.specialty_dermatology">Dermatología</option>
                        <option value="Neurología" data-translate="Register.specialty_neurology">Neurología</option>
                        <option value="Psiquiatría" data-translate="Register.specialty_psychiatry">Psiquiatría</option>
                        <option value="Oftalmología" data-translate="Register.specialty_ophthalmology">Oftalmología</option>
                        <option value="Otorrinolaringología" data-translate="Register.specialty_ent">Otorrinolaringología</option>
                        <option value="Ortopedia" data-translate="Register.specialty_orthopedics">Ortopedia</option>
                        <option value="Gastroenterología" data-translate="Register.specialty_gastroenterology">Gastroenterología</option>
                        <option value="Endocrinología" data-translate="Register.specialty_endocrinology">Endocrinología</option>
                        <option value="Nefrología" data-translate="Register.specialty_nephrology">Nefrología</option>
                        <option value="Urología" data-translate="Register.specialty_urology">Urología</option>
                        <option value="Oncología" data-translate="Register.specialty_oncology">Oncología</option>
                        <option value="Hematología" data-translate="Register.specialty_hematology">Hematología</option>
                        <option value="Neumología" data-translate="Register.specialty_pulmonology">Neumología</option>
                        <option value="Reumatología" data-translate="Register.specialty_rheumatology">Reumatología</option>
                        <option value="Infectología" data-translate="Register.specialty_infectiology">Infectología</option>
                        <option value="Cirugía General" data-translate="Register.specialty_general_surgery">Cirugía General</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded" data-translate="Register.submit_button">Register</button>
                <div id="registerResponse" class="mt-4"></div>
            </form>
            <p class="mt-4" data-translate="Register.login_prompt">Already have an account?</p>
            <a href="login.php" class="text-blue-500 hover:underline" data-translate="Register.login_link">Log in</a>
        </div>
    </div>
    <?php include '../layout/footer.php'; ?>
    <script src="../../js/translation.js"></script>
    <script src="../../js/register.js"></script>
</body>
</html>
