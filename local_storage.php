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
    <title>Local Storage Customers Example</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <script>
        // Fungsi untuk menyimpan data ke local storage
        function saveToLocalStorage(key, data) {
            localStorage.setItem(key, JSON.stringify(data));
        }

        // Fungsi untuk mengambil data dari local storage
        function getFromLocalStorage(key) {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : null;
        }

        // Fungsi untuk menghapus data dari local storage
        function removeFromLocalStorage(key) {
            localStorage.removeItem(key);
        }

        // Fungsi untuk menambahkan nama pelanggan
        function addCustomerName(name) {
            let customerNames = getFromLocalStorage('customerNames') || [];
            customerNames.push(name);
            saveToLocalStorage('customerNames', customerNames);
            displayCustomerNames();
        }

        // Fungsi untuk menghapus nama pelanggan
        function removeCustomerName(index) {
            let customerNames = getFromLocalStorage('customerNames') || [];
            customerNames.splice(index, 1);
            saveToLocalStorage('customerNames', customerNames);
            displayCustomerNames();
        }

        // Fungsi untuk menampilkan nama pelanggan
        function displayCustomerNames() {
            const customerList = document.getElementById('customer-list');
            customerList.innerHTML = '';
            const storedCustomerNames = getFromLocalStorage('customerNames');

            if (storedCustomerNames) {
                storedCustomerNames.forEach((name, index) => {
                    const listItem = document.createElement('li');
                    listItem.textContent = name;
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.onclick = () => removeCustomerName(index);
                    listItem.appendChild(deleteButton);
                    customerList.appendChild(listItem);
                });
            }
        }

        // Event listener untuk memuat nama pelanggan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            displayCustomerNames();

            // Event listener untuk menambahkan nama pelanggan saat formulir dikirim
            document.getElementById('add-customer-form').addEventListener('submit', (e) => {
                e.preventDefault();
                const customerName = document.getElementById('customer-name').value;
                addCustomerName(customerName);
                document.getElementById('customer-name').value = '';
            });
        });
    </script>
</head>
<body>
    <header>
        <h1>Local Storage Customers Example</h1>
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
            <h2>Customer Names</h2>
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
            <form id="add-customer-form">
                <!-- Input Nama Pelanggan -->
                <input type="text" id="customer-name" placeholder="Enter customer name" required>
                <input type="submit" value="Add Customer">
            </form>
            <ul id="customer-list">
                <!-- Nama pelanggan akan ditampilkan di sini -->
            </ul>
        </div>
    </main>
</body>
</html>