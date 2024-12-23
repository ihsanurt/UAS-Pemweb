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

// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];

// Query untuk mengambil data produk berdasarkan user_id
$query = "SELECT * FROM products WHERE user_id = :user_id";
$params = [':user_id' => $user_id];
$products = $database->executeQuery($query, $params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Table</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Products Table</h1>
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
            <h2>Product List</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['productName']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>