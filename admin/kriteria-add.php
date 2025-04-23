<?php
include "../koneksi/config.php";

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'simpan') {
        $nama_kriteria = $_POST['nama_kriteria'];
        $jenis = $_POST['jenis'];
        $bobot_kriteria = $_POST['bobot_kriteria'];

        // Cek duplikasi nama kriteria
        $cek_nama = mysqli_query($conn, "SELECT * FROM tbl_kriteria WHERE nama_kriteria='$nama_kriteria'");
        if (mysqli_num_rows($cek_nama) > 0) {
            echo "<script>
                    alert('Nama kriteria sudah ada!');
                    window.location='kriteria-add.php';
                  </script>";
            exit;
        }

        // Menghitung total bobot kriteria
        $query_total_bobot = "SELECT SUM(bobot_kriteria) AS total FROM tbl_kriteria";
        $result_total_bobot = mysqli_query($conn, $query_total_bobot);
        $row_total_bobot = mysqli_fetch_assoc($result_total_bobot);
        $total_bobot = $row_total_bobot['total'] + $bobot_kriteria;

        // Mencegah input bobot melebihi 100
        if ($total_bobot > 100) {
            echo "<script>
                    alert('Bobot kriteria tidak boleh melebihi 100!');
                    window.location='kriteria-add.php';
                  </script>";
            exit;
        }

        // Menghitung normalisasi jika total bobot = 100
        if ($total_bobot == 100) {
            // Update normalisasi semua kriteria
            mysqli_query($conn, "UPDATE tbl_kriteria SET normalisasi = bobot_kriteria / 100");
            $normalisasi = $bobot_kriteria / 100; // Normalisasi untuk kriteria yang baru ditambahkan
        } else {
            $normalisasi = 0;
        }

        mysqli_query($conn, "INSERT INTO tbl_kriteria(nama_kriteria, jenis, bobot_kriteria, normalisasi) 
                            VALUES ('$nama_kriteria', '$jenis', '$bobot_kriteria', '$normalisasi')");

        header("location:kriteria.php");
    }
}

include "header.php";
?>

<style>
    .btn-dark {
        background-color: #705C53;
    }
</style>


<h2 class="mb-4">KRITERIA / Tambah Data Kriteria</h2>
<hr>
<div class="shadow p-5">
    <form action="kriteria-add.php?aksi=simpan" method="post">
        <div class="form-group">
            <label>Nama Kriteria</label>
            <select name="nama_kriteria" class="form-control" required>
                <option value="">-- Pilih --</option>
                <?php
                // Mendapatkan nama-nama kolom dari tabel 'tbl_penilaian' (kecuali id dan nama_karyawan)
                $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_penilaian");
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['Field'] != 'id' && $row['Field'] != 'nama_karyawan' && $row['Field'] != 'tgl_penilaian' && $row['Field'] != 'update_tgl_penilaian' && $row['Field'] != 'waktu_penilaian' && $row['Field'] != 'update_waktu_penilaian') {
                        // Mengubah tampilan nama kriteria menjadi lebih rapi
                        $namaKriteria = ucwords(str_replace("_", " ", $row['Field']));
                        echo "<option value='" . $row['Field'] . "'>" . $namaKriteria . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Jenis</label>
            <select name="jenis" class="form-control" required>
                <option value="">-- Pilih --</option>
                <option value="Benefit">Benefit</option>
                <option value="Cost">Cost</option>
            </select>
        </div>

        <div class="form-group">
            <label>Bobot Kriteria</label>
            <input type="number" name="bobot_kriteria" class="txt form-control" required>
            <small>
                Masukkan bobot kriteria dalam rentang 0-100.
                Pastikan total bobot kriteria tidak melebihi 100
                agar perhitungan normalisasi berjalan dengan benar.
            </small>
        </div>
        <input type="submit" value="Simpan" class="btn btn-dark">
        <a href="kriteria.php" class="btn btn-danger text-light">Batal</a>
    </form>
</div>
</div>
</div>
</div>