<?php
session_start();
require_once 'Database.php';

// Buat instance dari kelas Database dan dapatkan koneksi
$database = new Database();
$db = $database->getConnection();

// Inisialisasi pesan kesalahan dan sukses
$error_message = "";
$success_message = "";

// Periksa apakah metode permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validasi data formulir
    if ($password !== $confirmPassword) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash kata sandi
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Periksa apakah koneksi database berhasil
        if ($db === null) {
            $error_message = "Database connection failed.";
        } else {
            // Masukkan pengguna ke dalam database
            $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $params = [
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword
            ];
            $result = $database->executeQuery($query, $params);

            // Periksa apakah query berhasil dijalankan
            if ($result === false) {
                $error_message = "Registration failed.";
            } else {
                $success_message = "Registration successful. You can now <a href='login.php'>login</a>.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            if (password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <main class="register-container">
        <div class="container">
            <h2>Register</h2>
            <?php
            // Tampilkan pesan kesalahan jika ada
            if (isset($error_message) && !empty($error_message)) {
                echo "<p style='color:red;'>$error_message</p>";
            }
            // Tampilkan pesan sukses jika ada
            if (isset($success_message) && !empty($success_message)) {
                echo "<p style='color:green;'>$success_message</p>";
            }
            ?>
            <form id="user-form" action="index.php" method="POST" onsubmit="return validateForm()">
                <!-- Input Nama -->
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required placeholder="M Ihsan Nur Tsaqib">

                <!-- Input Email -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="muhammad.122140176@student.itera.ac.id">

                <!-- Input Password -->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <!-- Konfirmasi Password -->
                <label for="confirmPassword">Konfirmasi Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required><br>

                <!-- Tombol Submit -->
                <input type="submit" value="Submit">

                <a href="login.php">Login</a> 
            </form>
        </div>
    </main>
</body>
</html>