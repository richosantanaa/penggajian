<?php

// Koneksi ke database (ganti dengan koneksi sesuai database Anda)
$servername = "localhost";
$username = "root";
$password = "";
$database = "penggajian";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan data dari permintaan POST dengan penanganan keamanan
$nip = isset($_POST['nip']) ? htmlspecialchars($_POST['nip']) : '';
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

// Menyiapkan pernyataan SQL untuk mencegah serangan SQL injection
$stmt = $conn->prepare("SELECT user_id, role FROM users WHERE nip = ? AND password = ?");
$stmt->bind_param("ss", $nip, $password);

// Menjalankan pernyataan SQL
$stmt->execute();

// Mengambil hasil dari pernyataan SQL
$result = $stmt->get_result();

// Memeriksa hasil query
if ($result->num_rows > 0) {
    // Kredensial benar, kirim respons berhasil
    $row = $result->fetch_assoc();
    $response = array(
        "status" => "success",
        "message" => "Login berhasil",
        "user_id" => $row['user_id'],
        "role" => $row['role']
    );
} else {
    // Kredensial salah, kirim respons gagal
    $response = array(
        "status" => "error",
        "message" => "Login gagal. NIP atau password salah."
    );
}

// Mengirim respons sebagai JSON
header('Content-Type: application/json');
echo json_encode($response);

// Menutup pernyataan dan koneksi database
$stmt->close();
$conn->close();

?>
