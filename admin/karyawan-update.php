<?php
include "../koneksi/config.php";
include "header.php";

if (isset($_POST['simpan'])) {
    // Ubah data yang ada
    $set_query = "";

    $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_karyawan");
    while ($row = mysqli_fetch_array($result)) {
        if ($row['Field'] != 'id') {
            $set_query .= $row['Field'] . " = '" . $_POST[$row['Field']] . "', ";
        }
    }

    $set_query = rtrim($set_query, ", ");

    if (mysqli_query($conn, "UPDATE tbl_karyawan SET $set_query WHERE id = '$_POST[id]'")) {
        // Jika berhasil mengubah data
        echo "<div class='alert alert-success'>Data berhasil diubah.</div>";
        echo "<meta http-equiv='refresh' content='1;url=karyawan.php'>"; // Redirect setelah 1 detik
    } else {
        // Jika gagal mengubah data
        echo "<div class='alert alert-danger'>Gagal mengubah data.</div>";
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data_karyawan = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_karyawan WHERE id='$id'"));
} else {
    header("location:karyawan.php");
} ?>

<div class="shadow p-5 mt-5">
    <h2>Ubah Data Karyawan</h2>
    <hr>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $data_karyawan['id']; ?>">
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
                            <option value="Laki-laki" <?php echo ($data_karyawan[$row['Field']] == 'Laki-laki') ? 'selected' : ''; ?>>
                                Laki-laki</option>
                            <option value="Perempuan" <?php echo ($data_karyawan[$row['Field']] == 'Perempuan') ? 'selected' : ''; ?>>
                                Perempuan</option>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" id="<?php echo $row['Field']; ?>"
                            name="<?php echo $row['Field']; ?>" value="<?php echo $data_karyawan[$row['Field']]; ?>" required>
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