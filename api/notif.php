<?php
// Koneksi ke database
$servername = "localhost"; // Ganti dengan nama server Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$database = "penggajian"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengecek apakah parameter user_id diberikan dalam URL
if (isset($_GET['user_id'])) {
    // Mendapatkan user_id yang aktif dari parameter URL
    $user_id = $_GET['user_id'];

    // Mengecek apakah user_id tidak kosong
    if (!empty($user_id)) {
        // Mendapatkan data dari tabel users berdasarkan user_id yang aktif
        $sql = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $conn->query($sql);

        // Mengecek jika terdapat data
        if ($result->num_rows > 0) {
            // Mendapatkan setiap baris data
            $row = $result->fetch_assoc();

            // Mengubah array response menjadi format JSON
            echo json_encode($row);
        } else {
            // Jika tidak ada data
            echo json_encode(array("message" => "Tidak ada data pengguna dengan user_id yang diberikan"));
        }
    } else {
        // Jika user_id kosong
        echo json_encode(array("message" => "Parameter user_id tidak boleh kosong"));
    }
} else {
    // Jika parameter user_id tidak diberikan dalam URL
    echo json_encode(array("message" => "Parameter user_id tidak diberikan"));
}

// Menutup koneksi
$conn->close();
?>
