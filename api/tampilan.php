<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Karyawan</title>
</head>

<body>
    <h2>Tambah Data Karyawan</h2>
    <form id="addEmployeeForm">
        <label for="bulan_tahun">Bulan Tahun:</label>
        <input type="date" id="bulan_tahun" name="bulan_tahun" required><br><br>

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required><br><br>

        <label for="nip">NIP:</label>
        <input type="text" id="nip" name="nip" required><br><br>

        <label for="jabatan">Jabatan:</label>
        <input type="text" id="jabatan" name="jabatan" required><br><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required><br><br>

        <label for="gaji_pokok">Gaji Pokok:</label>
        <input type="number" id="gaji_pokok" name="gaji_pokok" required><br><br>

        <label for="tunjangan_jabatan">Tunjangan Jabatan:</label>
        <input type="number" id="tunjangan_jabatan" name="tunjangan_jabatan" required><br><br>

        <label for="konsumsi">Konsumsi:</label>
        <input type="number" id="konsumsi" name="konsumsi" required><br><br>

        <label for="tunjangan_harian">Tunjangan Harian:</label>
        <input type="number" id="tunjangan_harian" name="tunjangan_harian" required><br><br>

        <label for="potongan_bpjs">Potongan BPJS:</label>
        <input type="number" id="potongan_bpjs" name="potongan_bpjs" required><br><br>

        <label for="jht">JHT:</label>
        <input type="number" id="jht" name="jht" required><br><br>

        <label for="pensiun">Pensiun:</label>
        <input type="number" id="pensiun" name="pensiun" required><br><br>

        <label for="pph21">PPH21:</label>
        <input type="number" id="pph21" name="pph21" required><br><br>

        <label for="total_pendapatan">Total Pendapatan:</label>
        <input type="number" id="total_pendapatan" name="total_pendapatan" required><br><br>

        <label for="total_potongan">Total Potongan:</label>
        <input type="number" id="total_potongan" name="total_potongan" required><br><br>

        <label for="jumlah_bersih">Jumlah Bersih:</label>
        <input type="number" id="jumlah_bersih" name="jumlah_bersih" required><br><br>

        <label for="divisi">Divisi:</label>
        <input type="text" id="divisi" name="divisi" required><br><br>

        <label for="user_id">User ID:</label>
        <input type="number" id="user_id" name="user_id" required><br><br>

        <button type="submit">Tambah Data</button>
    </form>

    <div id="responseMessage"></div>

    <script>
    document.getElementById('addEmployeeForm').addEventListener('submit', function(event) {
        event.preventDefault();

        // Ambil data dari form
        var formData = {
            bulan_tahun: document.getElementById('bulan_tahun').value,
            nama: document.getElementById('nama').value,
            nip: document.getElementById('nip').value,
            jabatan: document.getElementById('jabatan').value,
            status: document.getElementById('status').value,
            gaji_pokok: document.getElementById('gaji_pokok').value,
            tunjangan_jabatan: document.getElementById('tunjangan_jabatan').value,
            konsumsi: document.getElementById('konsumsi').value,
            tunjangan_harian: document.getElementById('tunjangan_harian').value,
            potongan_bpjs: document.getElementById('potongan_bpjs').value,
            jht: document.getElementById('jht').value,
            pensiun: document.getElementById('pensiun').value,
            pph21: document.getElementById('pph21').value,
            total_pendapatan: document.getElementById('total_pendapatan').value,
            total_potongan: document.getElementById('total_potongan').value,
            jumlah_bersih: document.getElementById('jumlah_bersih').value,
            divisi: document.getElementById('divisi').value,
            user_id: document.getElementById('user_id').value
        };

        // Kirim data ke API
        fetch('http://192.168.43.105/api/tambah.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                // Tampilkan pesan respons dari API
                var responseMessage = document.getElementById('responseMessage');
                responseMessage.innerHTML = data.message;
                responseMessage.style.color = data.status === 'success' ? 'green' : 'red';
            })
            .catch(error => {
                console.error('Error:', error);
                var messageDiv = document.getElementById('message');
                messageDiv.innerText = 'Terjadi kesalahan dalam mengirim data.';
                messageDiv.style.color = 'red';
            });
    });
    </script>
</body>

</html>