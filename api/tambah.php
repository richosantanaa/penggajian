<?php

// Mendapatkan data dari permintaan POST
$data = json_decode(file_get_contents("php://input"), true);

// Jika data tidak kosong dan memiliki struktur yang sesuai
if (!empty($data) && isset($data['bulan_tahun'], $data['nama'], $data['nip'], $data['jabatan'], $data['status'], $data['gaji_pokok'], $data['tunjangan_jabatan'], $data['konsumsi'], $data['tunjangan_harian'], $data['potongan_bpjs'], $data['jht'], $data['pensiun'], $data['pph21'], $data['total_pendapatan'], $data['total_potongan'], $data['jumlah_bersih'], $data['divisi'], $data['user_id'])) {

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

    // Query untuk menambahkan data ke database
    $insert_user = $conn->prepare("INSERT INTO PayrollKaryawan (bulan_tahun, nama, nip, jabatan, status, gaji_pokok, tunjangan_jabatan, konsumsi, tunjangan_harian, potongan_bpjs, jht, pensiun, pph21, total_pendapatan, total_potongan, jumlah_bersih, divisi, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_user->bind_param("sssssssssssssssssi", $data['bulan_tahun'], $data['nama'], $data['nip'], $data['jabatan'], $data['status'], $data['gaji_pokok'], $data['tunjangan_jabatan'], $data['konsumsi'], $data['tunjangan_harian'], $data['potongan_bpjs'], $data['jht'], $data['pensiun'], $data['pph21'], $data['total_pendapatan'], $data['total_potongan'], $data['jumlah_bersih'], $data['divisi'], $data['user_id']);

    if ($insert_user->execute()) {
        // Jika penambahan data berhasil
        $response = array(
            "status" => "success",
            "message" => "Data berhasil ditambahkan"
        );
    } else {
        // Jika terjadi kesalahan saat menambah data
        $response = array(
            "status" => "error",
            "message" => "Gagal menambahkan data: " . $conn->error
        );
    }

    // Mengirim respons sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    // Menutup koneksi database
    $conn->close();
} else {
    // Jika data tidak lengkap
    $response = array(
        "status" => "error",
        "message" => "Tambah data gagal. Semua data harus diisi."
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>