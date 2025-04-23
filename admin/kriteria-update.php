<?php
include "../koneksi/config.php";

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'ubah') {
        $id = $_POST['id'];
        $nama_kriteria = $_POST['nama_kriteria'];
        $jenis = $_POST['jenis'];
        $bobot_kriteria = $_POST['bobot_kriteria'];

        // Cek duplikasi nama kriteria (kecuali untuk kriteria yang sedang diubah)
        $cek_nama = mysqli_query($conn, "SELECT * FROM tbl_kriteria WHERE nama_kriteria='$nama_kriteria' AND id != '$id'");
        if (mysqli_num_rows($cek_nama) > 0) {
            echo "<script>
                    alert('Nama kriteria sudah ada!');
                    window.location='kriteria-update.php?id=$id';
                  </script>";
            exit;
        }

        // Menghitung total bobot kriteria (setelah diubah)
        $query_total_bobot = "SELECT SUM(bobot_kriteria) AS total FROM tbl_kriteria WHERE id != '$id'";
        $result_total_bobot = mysqli_query($conn, $query_total_bobot);
        $row_total_bobot = mysqli_fetch_assoc($result_total_bobot);
        $total_bobot = $row_total_bobot['total'] + $bobot_kriteria;

        // Validasi total bobot
        if ($total_bobot > 100) {
            echo "<script>
                    alert('Bobot kriteria tidak boleh melebihi 100!');
                    window.location='kriteria-update.php?id=$id';
                  </script>";
            exit;
        }

        // Menghitung normalisasi 
        if ($total_bobot == 100) {
            // Update normalisasi untuk kriteria yang diubah
            $normalisasi = $bobot_kriteria / 100;
            mysqli_query($conn, "UPDATE tbl_kriteria SET normalisasi = '$normalisasi' WHERE id = '$id'");

            // Update normalisasi kriteria lainnya
            mysqli_query($conn, "UPDATE tbl_kriteria SET normalisasi = bobot_kriteria / 100 WHERE id != '$id'");
        } else {
            // Reset normalisasi semua kriteria menjadi 0 jika total bobot != 100
            mysqli_query($conn, "UPDATE tbl_kriteria SET normalisasi = 0");
        }

        // Update data kriteria
        mysqli_query($conn, "UPDATE tbl_kriteria SET 
                            nama_kriteria='$nama_kriteria',
                            jenis='$jenis',
                            bobot_kriteria='$bobot_kriteria'
                            WHERE id='$id'");

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


<h2 class="mb-4">KRITERIA/ Ubah Data Kriteria</h2>
<hr>
<div class="shadow p-5">
    <?php
    $id_kriteria = $_GET['id'];
    $data = mysqli_query($conn, "SELECT * FROM tbl_kriteria WHERE id='$id_kriteria'");
    while ($a = mysqli_fetch_array($data)) {
        ?>
        <form action="kriteria-update.php?aksi=ubah" method="post">
            <input name="id" type="hidden" value="<?= $a['id'] ?>">
            <?php
            // Mendapatkan informasi kolom dari tabel 'tbl_kriteria'
            $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_kriteria");
            while ($row = mysqli_fetch_array($result)) {
                if ($row['Field'] != 'id') { // Menyembunyikan kolom 'id'
                    $fieldName = $row['Field']; // Nama kolom (e.g., 'nama_kriteria', 'jenis')
        
                    echo "<div class='form-group'>";
                    echo "<label>" . ucwords(str_replace("_", " ", $fieldName)) . "</label>"; // Label input
        
                    // Input field berdasarkan tipe data kolom (disesuaikan dengan kebutuhan)
                    if ($fieldName == 'nama_kriteria') {
                        echo "<select name='$fieldName' class='form-control' required>";
                        echo "<option value=''>-- Pilih --</option>";

                        // Mendapatkan nama-nama kolom dari tabel 'tbl_data' (kecuali id dan nama_karyawan)
                        $result_kolom = mysqli_query($conn, "SHOW COLUMNS FROM tbl_penilaian");
                        while ($row_kolom = mysqli_fetch_array($result_kolom)) {
                            if ($row_kolom['Field'] != 'id' && $row_kolom['Field'] != 'nama_karyawan' && $row_kolom['Field'] != 'tgl_penilaian' && $row_kolom['Field'] != 'update_tgl_penilaian' && $row_kolom['Field'] != 'waktu_penilaian' && $row_kolom['Field'] != 'update_waktu_penilaian') {
                                // Mengubah tampilan nama kriteria menjadi lebih rapi
                                $namaKriteria = ucwords(str_replace("_", " ", $row_kolom['Field']));
                                $selected = ($a[$fieldName] == $row_kolom['Field']) ? 'selected' : '';
                                echo "<option value='" . $row_kolom['Field'] . "' $selected>" . $namaKriteria . "</option>";
                            }
                        }
                        echo "</select>";
                    } else if ($fieldName == 'jenis') {
                        echo "<select name='$fieldName' class='form-control' required>";
                        echo "<option value=''>-- Pilih --</option>";
                        echo "<option value='Benefit' " . (($a['jenis'] == 'Benefit') ? 'selected' : '') . ">Benefit</option>";
                        echo "<option value='Cost' " . (($a['jenis'] == 'Cost') ? 'selected' : '') . ">Cost</option>";
                        echo "</select>";
                    } else if ($fieldName == 'bobot_kriteria') {
                        echo "<input type='number' name='$fieldName' class='txt form-control' required value='" . $a[$fieldName] . "'>";
                        echo "<small class='form-text text-muted'>
                        Masukkan bobot parameter dengan angka dari besar ke kecil. 
                        Semakin besar angka, semakin baik parameter tersebut.
                      </small>";
                    } else {
                        // Kolom lainnya (misalnya: normalisasi) tidak perlu ditampilkan sebagai input
                    }

                    echo "</div>";
                }
            }
            ?>
            <input type="submit" value="Ubah" class="btn btn-dark">
            <a href="kriteria.php" class="btn btn-danger text-light">Batal</a>
        </form>
    <?php } ?>
</div>
</div>
</div>
</div>