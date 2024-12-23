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

// Menangani operasi CRUD untuk daftar To Buy
$todos = isset($_COOKIE['todos']) ? json_decode($_COOKIE['todos'], true) : [];

// Periksa apakah metode permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // Tambahkan item baru ke daftar To Buy
        $todos[] = $_POST['todo'];
    } elseif (isset($_POST['update'])) {
        // Perbarui item yang ada di daftar To Buy
        $index = $_POST['index'];
        $todos[$index] = $_POST['todo'];
    } elseif (isset($_POST['delete'])) {
        // Hapus item dari daftar To Buy
        $index = $_POST['index'];
        array_splice($todos, $index, 1);
    }
    // Simpan daftar To buy ke dalam cookie
    setcookie('todos', json_encode($todos), time() + (86400 * 30), "/"); // 30 hari
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Dashboard</h1>
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
            <h2>Welcome to the Dashboard, <?php echo htmlspecialchars($user['name']); ?></h2>
            <!-- Bagian Daftar To buy -->
            <h2>To buy List</h2>
            <form action="dashboard.php" method="POST">
                <input type="text" name="todo" placeholder="New To Buy" required>
                <input type="submit" name="add" value="Add">
            </form>
            <ul>
                <?php foreach ($todos as $index => $todo): ?>
                <li>
                    <?php echo htmlspecialchars($todo); ?>
                    <form action="dashboard.php" method="POST" style="display:inline;" class="todo-buttons">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="text" name="todo" value="<?php echo htmlspecialchars($todo); ?>" required>
                        <input type="submit" name="update" value="Update">
                        <input type="submit" name="delete" value="Delete">
                    </form>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>
</body>
</html>