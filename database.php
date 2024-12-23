<?php
class Database {
    // Properti untuk menyimpan informasi koneksi database
    private $host = "localhost";
    private $db_name = "uas";
    private $username = "root";
    private $password = "";
    public $conn;

    // Fungsi untuk mendapatkan koneksi database
    public function getConnection() {
        $this->conn = null;

        try {
            // Membuat koneksi PDO ke database
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Mengatur karakter set ke UTF-8
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            // Menangkap dan menampilkan pesan kesalahan jika koneksi gagal
            echo "Connection error: " . $exception->getMessage();
        }

        // Mengembalikan objek koneksi
        return $this->conn;
    }

    // Fungsi untuk menjalankan query dengan parameter
    public function executeQuery($query, $params = []) {
        // Mempersiapkan statement SQL
        $stmt = $this->conn->prepare($query);
        // Mengikat nilai parameter ke statement
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        // Menjalankan statement
        $stmt->execute();
        // Mengembalikan hasil sebagai array asosiatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>