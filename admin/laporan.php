<?php
include '../koneksi/config.php';

// Set timezone ke waktu lokal Anda
date_default_timezone_set('Asia/Jakarta');

// Ambil data dari form input tanda tangan (jika ada)
$namaPenandatangan = isset($_POST['nama']) ? $_POST['nama'] : '';
$jabatanPenandatangan = isset($_POST['jabatan']) ? $_POST['jabatan'] : '';
$tempatPenandatangan = isset($_POST['tempat']) ? $_POST['tempat'] : '';

// Dapatkan tanggal otomatis sesuai hari ini
$tanggalCetak = date("d F Y");  // Format: 09 September 2024
$waktuCetak = date("H:i:s");    // Format waktu: 04:10:32

include "header.php";
?>

<style>
    .btn-dark {
        background-color: #705C53;
    }
</style>

<style>
    @media print {

        /* Sembunyikan elemen-elemen yang tidak perlu dicetak */
        nav,
        .btn,
        footer,
        form,
        .tbl,
        .form-group {
            display: none !important;
        }
    }

    .signature-container {
        text-align: center;
        margin-right: 0;
        margin-top: 50px;
        position: relative;
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }

    .signature-content {
        text-align: center;
        width: 300px;
    }

    .signature-content .location-date {
        margin-bottom: 10px;
    }

    .signature-content .position {
        font-weight: bold;
        margin-bottom: 70px;
    }

    .signature-content .name {
        font-weight: bold;
        margin-bottom: 5px;
        /* Mengatur jarak antara nama dan garis agar lebih dekat */
    }

    .signature-content .signature-line {
        margin-top: 0px;
        /* Menghapus jarak antara nama dan garis tanda tangan */
        border-top: 1px solid black;
        width: 200px;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <img src="../assets/images/TLogo.png" alt="Logo" style="width: 180px; margin-right: 20px;">
                <div>
                    <h4>TARI BAKERY</h4>
                    <p>Telpon : (62) 822-7715-2966</p>
                    <p>Jl. Jenderal Sudirman, Pekan Tj. Pura, Kec. Tj. Pura, Kabupaten Langkat, Sumatera Utara 20853</p>
                    <p>Instagram : https://www.instagram.com/taribakery/</p>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12 text-center">
            <h4>Laporan Hasil</h4>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <?php
            // --- Fungsi untuk menampilkan tabel gabungan ---
            function tampilkanTabelGabungan($conn)
            {
                // Query untuk menggabungkan data dari tbl_penilaian, tbl_karyawan, dan tbl_hasil
                $sql = "
                    SELECT 
                        k.nama_karyawan, 
                        k.jenis_kelamin, 
                        k.profesi, 
                        p.tgl_penilaian, 
                        p.update_tgl_penilaian, 
                        p.waktu_penilaian, 
                        p.update_waktu_penilaian, 
                        h.total_nilai, 
                        h.rank
                    FROM 
                        tbl_hasil h
                    JOIN 
                        tbl_penilaian p ON h.nama_karyawan = p.nama_karyawan
                    JOIN 
                        tbl_karyawan k ON h.nama_karyawan = k.nama_karyawan
                    ORDER BY 
                        h.rank ASC
                ";

                $result = mysqli_query($conn, $sql);

                // Penanganan error jika query gagal
                if (!$result) {
                    echo "Error: " . mysqli_error($conn);
                    return;
                }

                // Tampilkan tabel
                echo "<table class='table table-bordered'>";
                echo "<thead><tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Jenis Kelamin</th>
                        <th>Profesi</th>
                        <th>Tanggal Penilaian</th>
                        <th>Update Tanggal Penilaian</th>
                        <th>Waktu Penilaian</th>
                        <th>Update Waktu Penilaian</th>
                        <th>Total Nilai</th>
                        <th>Rank</th>
                      </tr></thead>";
                echo "<tbody>";
                $no = 1; // Inisialisasi nomor
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>"; // Tampilkan nomor
                    echo "<td>" . $row['nama_karyawan'] . "</td>";
                    echo "<td>" . $row['jenis_kelamin'] . "</td>";
                    echo "<td>" . $row['profesi'] . "</td>";
                    echo "<td>" . $row['tgl_penilaian'] . "</td>";
                    echo "<td>" . $row['update_tgl_penilaian'] . "</td>";
                    echo "<td>" . $row['waktu_penilaian'] . "</td>";
                    echo "<td>" . $row['update_waktu_penilaian'] . "</td>";
                    echo "<td>" . $row['total_nilai'] . "</td>";
                    echo "<td>" . $row['rank'] . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }
            // --- Akhir fungsi tampilkanTabelGabungan ---

            // Tampilkan tabel gabungan
            tampilkanTabelGabungan($conn);
            ?>
        </div>
    </div>

    <hr>
    <form method="POST" class="mb-4">
        <div class="form-group mt-3">
            <label for="tempat">Tempat:</label>
            <input type="text" name="tempat" id="tempat" class="form-control" placeholder="Masukkan Tempat" value="<?php echo htmlspecialchars($tempatPenandatangan); ?>" required>
        </div>
        <div class="form-group mt-3">
            <label for="nama">Nama Penandatangan:</label>
            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama" value="<?php echo htmlspecialchars($namaPenandatangan); ?>" required>
        </div>
        <div class="form-group mt-2">
            <label for="jabatan">Jabatan Penandatangan:</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Masukkan Jabatan" value="<?php echo htmlspecialchars($jabatanPenandatangan); ?>" required>
        </div>

        <button type="submit" name="simpan" class="btn btn-success mt-3">Simpan Data Penandatangan</button>

        <button type="button" class="btn btn-dark mt-3" onclick="window.print()">Cetak Laporan</button>
    </form>

    <div class="signature-container">
        <div class="signature-content">
            <p class="location-date"><?php echo htmlspecialchars($tempatPenandatangan); ?>, <?php echo $tanggalCetak; ?></p>
            <p class="position"><?php echo htmlspecialchars($jabatanPenandatangan); ?></p>
            <p class="name"><?php echo htmlspecialchars($namaPenandatangan); ?></p>
            <div class="signature-line"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <p>Di cetak pada: <?php echo $tanggalCetak; ?> Jam: <?php echo $waktuCetak; ?></p>
        </div>
    </div>

    <?php include "footer.php"; ?>
</div>