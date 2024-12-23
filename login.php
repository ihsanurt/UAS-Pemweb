<?php
session_start();
require_once 'Database.php';

// Buat instance dari kelas Database dan dapatkan koneksi
$database = new Database();
$db = $database->getConnection();

// Periksa apakah metode permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Periksa apakah koneksi database berhasil
    if ($db === null) {
        $error_message = "Database connection failed.";
    } else {
        // Query untuk mengambil data pengguna berdasarkan nama
        $query = "SELECT * FROM users WHERE name = :name";
        $params = [':name' => $name];
        $result = $database->executeQuery($query, $params);

        // Periksa apakah query berhasil dijalankan
        if ($result === false) {
            $error_message = "Query execution failed.";
        } else {
            // Periksa apakah pengguna ditemukan
            if (count($result) == 1) {
                $user = $result[0];
                // Periksa apakah verifikasi kata sandi berhasil
                if (password_verify($password, $user['password'])) {
                    // Set sesi pengguna dan arahkan ke dashboard
                    $_SESSION['name'] = $name;
                    $_SESSION['user_id'] = $user['id']; // Simpan user_id dalam sesi
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error_message = "Invalid name or password.";
                }
            } else {
                $error_message = "Invalid name or password.";
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
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <script>
        // Fungsi untuk memvalidasi formulir
        function validateForm() {
            var name = document.getElementById("name").value;
            var password = document.getElementById("password").value;
            if (name == "" || password == "") {
                alert("Name and Password must be filled out");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <main class="login-container">
        <div class="container">
            <h2>Login</h2>
            <?php
            // Tampilkan pesan kesalahan jika ada
            if (isset($error_message)) {
                echo "<p style='color:red;'>$error_message</p>";
            }
            ?>
            <form action="login.php" method="POST" onsubmit="return validateForm()">
                <!-- Input Name -->
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter your name">

                <!-- Input Password -->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password"><br>

                <!-- Submit Button -->
                <input type="submit" value="Login">
            </form>
            <!-- Link to Register -->
            <a href="index.php">Register</a> 
        </div>
    </main>
</body>
</html>