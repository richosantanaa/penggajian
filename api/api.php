<?php

// Informasi koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$database = "penggajian";

// Membuat koneksi ke database
$koneksi = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Fungsi untuk mengambil semua data pengguna dari tabel "users"
function getUsers($koneksi) {
    $sql = "SELECT * FROM users";
    $result = $koneksi->query($sql);
    $users = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Fungsi untuk menambahkan pengguna baru ke dalam tabel "users"
function addUser($koneksi, $nama, $nip, $email, $phone, $role) {
    $sql = "INSERT INTO users (nama, nip, email, phone, role) VALUES ('$nama', '$nip', '$email', '$phone', '$role')";
    if ($koneksi->query($sql) === TRUE) {
        return "Pengguna berhasil ditambahkan";
    } else {
        return "Error: " . $sql . "<br>" . $koneksi->error;
    }
}

// Fungsi untuk memperbarui data pengguna yang ada dalam tabel "users"
function updateUser($koneksi, $id, $nama, $nip, $email, $phone, $role) {
    $sql = "UPDATE users SET nama='$nama', nip='$nip', email='$email', phone='$phone', role='$role' WHERE id=$id";
    if ($koneksi->query($sql) === TRUE) {
        return "Data pengguna berhasil diperbarui";
    } else {
        return "Error updating record: " . $koneksi->error;
    }
}

// Fungsi untuk menghapus data pengguna dari tabel "users" berdasarkan ID
function deleteUser($koneksi, $id) {
    $sql = "DELETE FROM users WHERE id=$id";
    if ($koneksi->query($sql) === TRUE) {
        return "Data pengguna berhasil dihapus";
    } else {
        return "Error deleting record: " . $koneksi->error;
    }
}

// Menangani permintaan berdasarkan metode HTTP
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch($requestMethod) {
    case 'GET':
        // Jika permintaan adalah GET, ambil data pengguna dan kirim sebagai respons JSON
        $response = getUsers($koneksi);
        echo json_encode($response);
        break;
    case 'POST':
        // Jika permintaan adalah POST, tambahkan pengguna baru
        $data = json_decode(file_get_contents('php://input'), true);
        $response = addUser($koneksi, $data['nama'], $data['nip'], $data['email'], $data['phone'], $data['role']);
        echo $response;
        break;
    case 'PUT':
        // Jika permintaan adalah PUT, perbarui data pengguna yang ada
        $data = json_decode(file_get_contents('php://input'), true);
        $response = updateUser($koneksi, $data['id'], $data['nama'], $data['nip'], $data['email'], $data['phone'], $data['role']);
        echo $response;
        break;
    case 'DELETE':
        // Jika permintaan adalah DELETE, hapus data pengguna berdasarkan ID
        $data = json_decode(file_get_contents('php://input'), true);
        $response = deleteUser($koneksi, $data['id']);
        echo $response;
        break;
    default:
        // Jika metode HTTP tidak diizinkan, kirim respons "Method Not Allowed"
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

// Menutup koneksi database
$koneksi->close();

?>
