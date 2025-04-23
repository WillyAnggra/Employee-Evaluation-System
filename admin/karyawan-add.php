<?php
include "../koneksi/config.php";
include "header.php";

if (isset($_POST['simpan'])) {
    // Tambah data baru
    $nama_kolom = "";
    $nilai_kolom = "";

    $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_karyawan");
    while ($row = mysqli_fetch_array($result)) {
        if ($row['Field'] != 'id') {
            $nama_kolom .= $row['Field'] . ", ";
            $nilai_kolom .= "'" . $_POST[$row['Field']] . "', ";
        }
    }

    $nama_kolom = rtrim($nama_kolom, ", ");
    $nilai_kolom = rtrim($nilai_kolom, ", ");

    if (mysqli_query($conn, "INSERT INTO tbl_karyawan ($nama_kolom) VALUES ($nilai_kolom)")) {
        // Jika berhasil menyimpan data
        echo "<div class='alert alert-success'>Data berhasil disimpan.</div>";
        echo "<meta http-equiv='refresh' content='1;url=karyawan.php'>"; // Redirect setelah 1 detik
    } else {
        // Jika gagal menyimpan data
        echo "<div class='alert alert-danger'>Gagal menyimpan data.</div>";
    }
} ?>

<div class="shadow p-5 mt-5">
    <h2>Tambah Data Karyawan</h2>
    <hr>
    <form method="post">
        <?php
        // Mendapatkan informasi kolom dari tabel 'tbl_karyawan' (kecuali id)
        $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_karyawan");
        while ($row = mysqli_fetch_array($result)) {
            if ($row['Field'] != 'id') {
                ?>
                <div class="mb-3">
                    <label for="<?php echo $row['Field']; ?>"
                        class="form-label"><?php echo ucwords(str_replace("_", " ", $row['Field'])); ?></label>
                    <?php if ($row['Field'] == 'jenis_kelamin') { ?>
                        <select class="form-select" id="<?php echo $row['Field']; ?>" name="<?php echo $row['Field']; ?>" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" id="<?php echo $row['Field']; ?>"
                            name="<?php echo $row['Field']; ?>" required>
                    <?php } ?>
                </div>
                <?php
            }
        }
        ?>
        <button type="submit" name="simpan" class="btn btn-dark">Simpan</button>
        <a href="karyawan.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include "footer.php"; ?>