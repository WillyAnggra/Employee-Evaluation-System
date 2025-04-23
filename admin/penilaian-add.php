<?php
include "../koneksi/config.php";
include "header.php";

if (isset($_POST['simpan'])) {
    $nama_karyawan = $_POST['nama_karyawan'];
    $tgl_penilaian = date('Y-m-d'); // Tanggal saat ini
    $waktu_penilaian = date('Y-m-d H:i:s'); // Tanggal dan waktu saat ini

    // Check apakah karyawan sudah pernah dinilai
    $check_query = mysqli_query($conn, "SELECT * FROM tbl_penilaian WHERE nama_karyawan='$nama_karyawan'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<div class='alert alert-danger'>Karyawan ini sudah pernah dinilai.</div>";
    } else {
        $disiplin = $_POST['disiplin'];
        $kerja_sama = $_POST['kerja_sama'];
        $tanggung_jawab = $_POST['tanggung_jawab'];
        $komunikasi = $_POST['komunikasi'];
        $kejujuran = $_POST['kejujuran'];

        $query = "INSERT INTO tbl_penilaian (nama_karyawan, tgl_penilaian, waktu_penilaian, disiplin, kerja_sama, tanggung_jawab, komunikasi, kejujuran, update_tgl_penilaian, update_waktu_penilaian) 
                  VALUES ('$nama_karyawan', '$tgl_penilaian', '$waktu_penilaian', '$disiplin', '$kerja_sama', '$tanggung_jawab', '$komunikasi', '$kejujuran', NULL, NULL)"; // update_tgl_penilaian dan update_waktu_penilaian diisi NULL

        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success'>Penilaian berhasil disimpan.</div>";
            echo "<meta http-equiv='refresh' content='1;url=penilaian.php'>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menyimpan penilaian.</div>";
        }
    }
}?>

<div class="shadow p-5 mt-5">
    <h2>Tambah Penilaian</h2>
    <hr>
    <form method="post">
        <div class="mb-3">
            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
            <select class="form-select" id="nama_karyawan" name="nama_karyawan" required>
                <option value="">-- Pilih Karyawan --</option>
                <?php
                $karyawan = mysqli_query($conn, "SELECT * FROM tbl_karyawan");
                while ($k = mysqli_fetch_array($karyawan)) {
                    // Check apakah karyawan sudah pernah dinilai
                    $check_query = mysqli_query($conn, "SELECT * FROM tbl_penilaian WHERE nama_karyawan='$k[nama_karyawan]'");
                    if (mysqli_num_rows($check_query) == 0) {
                        echo "<option value='$k[nama_karyawan]'>$k[nama_karyawan]</option>";
                    }
                }
              ?>
            </select>
        </div>

        <?php
        $kriteria = [
            "disiplin" => [
                "Tidak Hadir 0 Kali" => 5,
                "Tidak Hadir 1-2 Kali" => 4,
                "Tidak Hadir 3-4 Kali" => 3,
                "Tidak Hadir 5-6 Kali" => 2,
                "Tidak Hadir >6 Kali" => 1
            ],
            "kerja_sama" => [
                "Memberikan kontribusi yang sangat signifikan dalam tim" => 5,
                "Memberikan kontribusi yang baik dalam tim" => 4,
                "Memberikan kontribusi yang cukup dalam tim" => 3,
                "Memberikan kontribusi yang kurang dalam tim" => 2,
                "Memberikan kontribusi yang sangat kurang dalam tim" => 1
            ],
            "tanggung_jawab" => [
                "Selalu menyelesaikan tugas tepat waktu" => 5,
                "Hampir selalu menyelesaikan tugas tepat waktu" => 4,
                "Sering menyelesaikan tugas tepat waktu" => 3,
                "Jarang menyelesaikan tugas tepat waktu" => 2,
                "Tidak pernah menyelesaikan tugas tepat waktu" => 1
            ],
            "komunikasi" => [
                "Responsif - Sangat Respon (Membalas pesan dalam waktu kurang dari 1 jam)" => 5,
                "Responsif - Respon (Membalas pesan dalam waktu 1-3 jam)" => 4,
                "Responsif - Cukup Respon (Membalas pesan dalam waktu 3-6 jam)" => 3,
                "Responsif - Kurang Respon (Membalas pesan dalam waktu 6-12 jam)" => 2,
                "Responsif - Tidak Respon (Membalas pesan dalam waktu lebih dari 12 jam)" => 1
            ],
            "kejujuran" => [
                "Loyalitas - Sangat Loyal" => 5,
                "Loyalitas - Loyal" => 4,
                "Loyalitas - Cukup Loyal" => 3,
                "Loyalitas - Kurang Loyal" => 2,
                "Loyalitas - Tidak Loyal" => 1
            ]
        ];

        // Mendapatkan informasi kolom dari tabel 'tbl_penilaian' (kecuali id, nama_karyawan, dan tgl_penilaian)
        $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_penilaian");
        while ($row = mysqli_fetch_array($result)) {
            $field = $row['Field'];
            if ($field!= 'id' && $field!= 'nama_karyawan' && $field!= 'tgl_penilaian' && $field!= 'waktu_penilaian' && $field!= 'update_tgl_penilaian' && $field!= 'update_waktu_penilaian') {
                // Generate input select option secara dinamis
              ?>
                <div class="mb-3">
                    <label for="<?php echo $field;?>" class="form-label"><?php echo ucwords(str_replace("_", " ", $field));?></label>
                    <select class="form-select" id="<?php echo $field;?>" name="<?php echo $field;?>" required>
                        <option value="">-- Pilih Nilai --</option>
                        <?php
                        foreach ($kriteria[$field] as $deskripsi => $nilai) {
                            echo "<option value='$nilai'>$deskripsi</option>";
                        }
                      ?>
                    </select>
                </div>
                <?php
            }
        }
      ?>

        <button type="submit" name="simpan" class="btn btn-dark">Simpan</button>
        <a href="penilaian.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include "footer.php";?>