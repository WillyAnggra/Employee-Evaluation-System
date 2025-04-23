<?php
include "../koneksi/config.php";
include "header.php";

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $disiplin = $_POST['disiplin'];
    $kerja_sama = $_POST['kerja_sama'];
    $tanggung_jawab = $_POST['tanggung_jawab'];
    $komunikasi = $_POST['komunikasi'];
    $kejujuran = $_POST['kejujuran'];
    $update_tgl_penilaian = date('Y-m-d H:i:s'); // Tanggal dan waktu update
    $update_waktu_penilaian = date('H:i:s'); // Waktu update saja

    $query = "UPDATE tbl_penilaian SET 
              disiplin = '$disiplin', 
              kerja_sama = '$kerja_sama', 
              tanggung_jawab = '$tanggung_jawab', 
              komunikasi = '$komunikasi', 
              kejujuran = '$kejujuran',
              update_tgl_penilaian = '$update_tgl_penilaian',
              update_waktu_penilaian = '$update_waktu_penilaian'
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'>Penilaian berhasil diubah.</div>";
        echo "<meta http-equiv='refresh' content='1;url=penilaian.php'>";
    } else {
        echo "<div class='alert alert-danger'>Gagal mengubah penilaian.</div>";
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data_penilaian = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_penilaian WHERE id='$id'"));
} else {
    header("location:penilaian.php");
}?>

<div class="shadow p-5 mt-5">
    <h2>Ubah Penilaian</h2>
    <hr>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $data_penilaian['id'];?>">

        <div class="mb-3">
            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?php echo $data_penilaian['nama_karyawan'];?>" disabled>
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

        // Mendapatkan informasi kolom dari tabel 'tbl_penilaian' (kecuali id, nama_karyawan, tgl_penilaian, waktu_penilaian, update_tgl_penilaian, dan update_waktu_penilaian)
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
                            $selected = ($data_penilaian[$field] == $nilai)? 'selected': '';
                            echo "<option value='$nilai' $selected>$deskripsi</option>";
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