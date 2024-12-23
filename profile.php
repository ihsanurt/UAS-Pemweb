<?php
session_start();

// Periksa apakah sesi 'name' sudah diset, jika tidak, arahkan ke halaman login
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

require_once 'Database.php';

// Buat instance dari kelas Database dan dapatkan koneksi
$database = new Database();
$db = $database->getConnection();

// Ambil nama pengguna dari sesi
$name = $_SESSION['name'];

// Query untuk mengambil data pengguna berdasarkan nama
$query = "SELECT * FROM users WHERE name = :name";
$params = [':name' => $name];
$user = $database->executeQuery($query, $params)[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Profile</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="table.php">Table</a></li>
                <li><a href="form.php">Form</a></li>
                <li><a href="local_storage.php">Pelanggan</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container">
            <h2>User Profile</h2>
            <table border="1">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
            </table>
            <!-- Tambahkan tombol logout di bawah tabel -->
            <form action="logout.php" method="POST">
                <input type="submit" value="Logout">
            </form>
        </div>
    </main>
</body>
</html>