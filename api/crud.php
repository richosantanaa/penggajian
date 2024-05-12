<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "penggajian");

// Fungsi untuk menangani request login
function login($nip, $password)
{
    global $koneksi;
    $query = "SELECT * FROM Users WHERE nip = '$nip' AND password = '$password'";
    $result = $koneksi->query($query);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Fungsi untuk menangani request register
function register($nip, $password, $role)
{
    global $koneksi;
    $query = "INSERT INTO Users (nip, password, role) VALUES ('$nip', '$password', '$role')";
    return $koneksi->query($query);
}

function handlePayrollRequest($method, $data, $userId)
{
    global $koneksi;
    if ($method == "GET") {
        // Mendapatkan parameter dari URL
        $divisi = isset($_GET['divisi']) ? $_GET['divisi'] : null;
        $nip = isset($_GET['nip']) ? $_GET['nip'] : null;

        // Menggunakan userId untuk membatasi data berdasarkan pengguna yang login
        if (isset($_GET['user_id'])) {
            // Jika ada parameter user_id yang diminta
            $requested_user_id = $_GET['user_id'];
            $query = "SELECT * FROM PayrollKaryawan WHERE user_id = '$requested_user_id'";
        } elseif ($userId !== null) {
            // Jika tidak ada parameter user_id yang diminta, maka ambil data berdasarkan userId yang login
            $query = "SELECT * FROM PayrollKaryawan WHERE user_id = '$userId'";
        } else {
            // Jika tidak ada userId yang login, maka kembalikan semua data karyawan
            $query = "SELECT * FROM PayrollKaryawan";
        }

        $result = $koneksi->query($query);
        $payroll_data = array();
        while ($row = $result->fetch_assoc()) {
            $payroll_data[] = $row;
        }
        echo json_encode($payroll_data);
    }
}

// Handler untuk request HTTP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "add_data") {
        // Handler untuk request menambah data
        $bulanTahun = $_POST["bulan_tahun"];
        $nama = $_POST["nama"];
        $nip = $_POST["nip"];
        $jabatan = $_POST["jabatan"];
        $status = $_POST["status"];
        $gajiPokok = $_POST["gaji_pokok"];
        $tunjanganJabatan = $_POST["tunjangan_jabatan"];
        $konsumsi = $_POST["konsumsi"];
        $tunjanganHarian = $_POST["tunjangan_harian"];
        $potonganBpjs = $_POST["potongan_bpjs"];
        $jht = $_POST["jht"];
        $pensiun = $_POST["pensiun"];
        $pph21 = $_POST["pph21"];
        $totalPendapatan = $_POST["total_pendapatan"];
        $totalPotongan = $_POST["total_potongan"];
        $jumlahBersih = $_POST["jumlah_bersih"];
        $divisi = $_POST["divisi"];

        // Proses penambahan data ke database
        $query = "INSERT INTO PayrollKaryawan (bulan_tahun, nama, nip, jabatan, status, gaji_pokok, tunjangan_jabatan, konsumsi, tunjangan_harian, potongan_bpjs, jht, pensiun, pph21, total_pendapatan, total_potongan, jumlah_bersih, divisi) VALUES ('$bulanTahun', '$nama', '$nip', '$jabatan', '$status', '$gajiPokok', '$tunjanganJabatan', '$konsumsi', '$tunjanganHarian', '$potonganBpjs', '$jht', '$pensiun', '$pph21', '$totalPendapatan', '$totalPotongan', '$jumlahBersih', '$divisi')";
        if ($koneksi->query($query)) {
            // Jika penambahan data berhasil
            echo json_encode(array("status" => "success", "message" => "Data added successfully"));
        } else {
            // Jika terjadi kesalahan saat menambah data
            echo json_encode(array("status" => "error", "message" => "Failed to add data"));
        }
    }
}




// Handler untuk request HTTP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handler untuk request login dan register
    if ($_POST["action"] == "login") {
        $nip = $_POST["nip"];
        $password = $_POST["password"];
        if (login($nip, $password)) {
            // Login berhasil
            echo json_encode(array("status" => "success", "message" => "Login successful"));
        } else {
            // Login gagal
            echo json_encode(array("status" => "error", "message" => "Invalid credentials"));
        }
    } elseif ($_POST["action"] == "register") {
        // Handler untuk request register
        $nip = $_POST["nip"];
        $password = $_POST["password"];
        $role = $_POST["role"];
        if (register($nip, $password, $role)) {
            // Registrasi berhasil
            echo json_encode(array("status" => "success", "message" => "Registration successful"));
        } else {
            // Registrasi gagal
            echo json_encode(array("status" => "error", "message" => "Registration failed"));
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Handler untuk request CRUD pada tabel PayrollKaryawan
    $request_method = $_SERVER["REQUEST_METHOD"];
    $request_data = json_decode(file_get_contents("php://input"), true);

    // Mendapatkan userId dari sesi atau dari permintaan yang dikirim
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$userId && isset($request_data['user_id'])) {
        $userId = $request_data['user_id'];
    }

    handlePayrollRequest($request_method, $request_data, $userId);
}
?>