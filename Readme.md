Bagian 1: Client-side Programming 
1.1 Manipulasi DOM dengan JavaScript 
Form HTML:
    membuat form input di form.php dengan elemen input seperti teks, angka, dan tombol submit.
    Contoh:
    <form action="form.php" method="POST">
    <label for="productName">Product Name:</label>
    <input type="text" id="productName" name="productName" required placeholder="Enter product name">
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required placeholder="Enter price">
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required placeholder="Enter quantity">
    <input type="submit" value="Add Product">
    </form>

Tampilkan Data dari Server ke Tabel HTML:
    menampilkan data produk dari server ke dalam tabel HTML di table.php.
    Contoh:
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

1.2 Event Handling
Event Handling:
    menambahkan event listener untuk menangani form submission, validasi input, dan lain-lain.
    Contoh:
    <script>
    document.getElementById('add-product-form').addEventListener('submit', function(e) {
        e.preventDefault();
        // Validasi dan proses form
    });
    </script>

Form Validation:
    Anda dapat menambahkan validasi JavaScript untuk memastikan semua input diisi sebelum form dikirim.
    Contoh:
    <script>
    function validateForm() {
        var productName = document.getElementById("productName").value;
        var price = document.getElementById("price").value;
        var quantity = document.getElementById("quantity").value;
        if (productName == "" || price == "" || quantity == "") {
            alert("All fields must be filled out");
            return false;
        }
        return true;
    }
    </script>

Bagian 2: Server-side Programming 
2.1 Pengelolaan Data dengan PHP 
Metode POST atau GET:
    menggunakan metode POST pada form di form.php.
    Contoh:
    <form action="form.php" method="POST">

Parsing Data dan Validasi:
    melakukan parsing data dari variabel global dan melakukan validasi di sisi server di form.php.
    Contoh:
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    if (empty($productName) || empty($price) || empty($quantity)) {
        $error_message = "All fields are required.";
        }
    }

Simpan ke Basis Data:
    menyimpan data ke basis data termasuk jenis browser dan alamat IP pengguna.
    Contoh:
    <?php
    $query = "INSERT INTO products (user_id, productName, price, quantity) VALUES (:user_id,        :productName, :price, :quantity)";
    $params = [
    ':user_id' => $user_id,
    ':productName' => $productName,
    ':price' => $price,
    ':quantity' => $quantity
    ];
    $result = $database->executeQuery($query, $params);

2.2 Objek PHP Berbasis OOP
Class Mahasiswa:
    membuat sebuah class PHP berbasis OOP yang memiliki minimal dua metode dan menggunakan objek tersebut dalam skenario tertentu.
    Contoh:
    <?php
    class Mahasiswa {
    private $nama;
    private $nim;

    public function __construct($nama, $nim) {
        $this->nama = $nama;
        $this->nim = $nim;
    }

    public function getNama() {
        return $this->nama;
    }

    public function getNim() {
        return $this->nim;
    }
    }

    $mahasiswa = new Mahasiswa("Ihsan", "123456");
    echo $mahasiswa->getNama();

Bagian 3: Database Management (Bobot: 20%)
3.1 Pembuatan Tabel Database (5%)
Class Mahasiswa:
    Membuat tabel database untuk menyimpan data mahasiswa.
    Contoh:
    CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nim VARCHAR(20) NOT NULL
    );

3.2 Konfigurasi Koneksi Database (5%)
Koneksi DB:
    Mengkonfigurasi koneksi database di Database.php.
    Contoh:
    <?php
class Database {
    private $host = "localhost";
    private $db_name = "uas";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
       }
    }

3.3 Manipulasi Data pada Database (10%)
Class Mahasiswa:
    Melakukan manipulasi data pada database menggunakan class Mahasiswa.
    Contoh:
    <?php
class Mahasiswa {
    private $conn;
    private $table_name = "mahasiswa";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nama, $nim) {
        $query = "INSERT INTO " . $this->table_name . " (nama, nim) VALUES (:nama, :nim)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':nim', $nim);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    $database = new Database();
    $db = $database->getConnection();
    $mahasiswa = new Mahasiswa($db);
    $mahasiswa->create("Ihsan", "123456");
    $data = $mahasiswa->read();

Bagian 4: State Management (Bobot: 20%)
4.1 State Management dengan Session (10%)
Session PHP:
    Menggunakan session_start() untuk memulai session dan menyimpan informasi pengguna ke dalam session.
    Contoh:
    <?php
    session_start();
    $_SESSION['name'] = $name;
    $_SESSION['user_id'] = $user['id'];

4.2 Pengelolaan State dengan Cookie dan Browser Storage (10%)
Cookie JavaScript:
    Membuat fungsi untuk menetapkan, mendapatkan, dan menghapus cookie.
    Contoh:
    function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function eraseCookie(name) {
        document.cookie = name + '=; Max-Age=-99999999;';
    }

Browser Storage JavaScript:
    Menggunakan browser storage untuk menyimpan informasi secara lokal.
    Contoh:
    // Menyimpan data ke local storage
    localStorage.setItem('key', 'value');

    // Mengambil data dari local storage
    var value = localStorage.getItem('key');

    // Menghapus data dari local storage
    localStorage.removeItem('key');

Struktur Proyek
index.php: Halaman utama untuk registrasi pengguna.
login.php: Halaman login pengguna.
dashboard.php: Halaman dashboard setelah login.
profile.php: Halaman profil pengguna.
form.php: Halaman untuk menambahkan produk.
table.php: Halaman untuk menampilkan daftar produk.
logout.php: Halaman untuk logout pengguna.
Database.php: Kelas untuk mengelola koneksi dan query database.
styles.css: File CSS untuk styling halaman.
README.md: File ini yang berisi penjelasan proyek.

