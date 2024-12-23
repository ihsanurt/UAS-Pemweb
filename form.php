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

// Inisialisasi pesan kesalahan dan sukses
$error_message = "";
$success_message = "";

// Periksa apakah metode permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Validasi data formulir
    if (empty($productName) || empty($price) || empty($quantity)) {
        $error_message = "All fields are required.";
    } else {
        // Ambil user_id dari sesi
        $user_id = $_SESSION['user_id'];

        // Query untuk memasukkan data produk ke dalam database
        $query = "INSERT INTO products (user_id, productName, price, quantity) VALUES (:user_id, :productName, :price, :quantity)";
        $params = [
            ':user_id' => $user_id,
            ':productName' => $productName,
            ':price' => $price,
            ':quantity' => $quantity
        ];
        $result = $database->executeQuery($query, $params);

        // Periksa apakah query berhasil dijalankan
        if ($result === false) {
            $error_message = "Failed to add product.";
        } else {
            $success_message = "Product added successfully.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Product Form</h1>
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
            <h2>Add Product</h2>
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
            <form action="form.php" method="POST">
                <!-- Input Nama Produk -->
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" required placeholder="Enter product name">

                <!-- Input Harga -->
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required placeholder="Enter price">

                <!-- Input Jumlah -->
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required placeholder="Enter quantity">

                <!-- Tombol Submit -->
                <input type="submit" value="Add Product">
            </form>
        </div>
    </main>
</body>
</html>