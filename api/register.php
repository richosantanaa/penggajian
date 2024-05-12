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

// Mendapatkan data dari permintaan POST
$nip = isset($_POST['nip']) ? $_POST['nip'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$role = isset($_POST['role']) ? $_POST['role'] : ''; // Asumsikan role disimpan dalam database
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';

// Validasi input (pastikan nilai tidak kosong)
if (empty($nip) || empty($password) || empty($role) || empty($name) || empty($email) || empty($phone)) {
    $response = array(
        "status" => "error",
        "message" => "Registrasi gagal. Data tidak lengkap."
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit(); // Keluar dari skrip setelah mengirim respons
}

// Query untuk memeriksa apakah NIP sudah terdaftar sebelumnya
$check_existing_user = $conn->prepare("SELECT * FROM users WHERE nip=?");
$check_existing_user->bind_param("s", $nip);
$check_existing_user->execute();
$result_existing_user = $check_existing_user->get_result();

if ($result_existing_user->num_rows > 0) {
    // Jika NIP sudah terdaftar, kirim respons gagal
    $response = array(
        "status" => "error",
        "message" => "Registrasi gagal. NIP sudah terdaftar."
    );
} else {
    // Jika NIP belum terdaftar, lakukan proses registrasi
    $insert_user = $conn->prepare("INSERT INTO users (nip, password, role, name, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_user->bind_param("ssssss", $nip, $password, $role, $name, $email, $phone);

    if ($insert_user->execute()) {
        // Registrasi berhasil
        $response = array(
            "status" => "success",
            "message" => "Registrasi berhasil"
        );
    } else {
        // Registrasi gagal karena kesalahan database
        $response = array(
            "status" => "error",
            "message" => "Registrasi gagal. Terjadi kesalahan pada server."
        );
    }
}

// Mengirim respons sebagai JSON
header('Content-Type: application/json');
echo json_encode($response);

// Menutup koneksi database
$conn->close();

?>
