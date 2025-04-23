<?php
include "../koneksi/config.php";
include "header.php";

if (isset($_GET['hapus'])) {
    // Hapus data
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tbl_karyawan WHERE id='$id'");
    header("location:karyawan.php");
} ?>

<style>
    .btn-dark {
        background-color: #705C53;
    }
</style>


<h2 class="mb-4">DATA KARYAWAN</h2>
<hr>

<div class="shadow p-5">
    <a href="karyawan-add.php" class="btn btn-success mb-3">Tambah Data Karyawan</a>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle">No</th>
                    <?php
                    // Mendapatkan informasi kolom dari tabel 'tbl_karyawan'
                    $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_karyawan");
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['Field'] != 'id') { // Menyembunyikan kolom 'id'
                            echo "<th class='text-center align-middle'>" . ucwords(str_replace("_", " ", $row['Field'])) . "</th>";
                        }
                    }
                    ?>
                    <th class="text-center align-middle">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data = mysqli_query($conn, "SELECT * FROM tbl_karyawan");
                while ($d = mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                        <td class="text-center align-middle"><?php echo $no++; ?></td>
                        <?php
                        // Mendapatkan informasi kolom dari tabel 'tbl_karyawan'
                        $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_karyawan");
                        while ($row = mysqli_fetch_array($result)) {
                            if ($row['Field'] != 'id') { // Menyembunyikan kolom 'id'
                                echo "<td class='text-center align-middle'>" . $d[$row['Field']] . "</td>";
                            }
                        }
                        ?>
                        <td class="text-center align-middle">
                            <a href="karyawan-update.php?id=<?php echo $d['id'];?>" class="btn btn-sm btn-dark">Edit</a>
                            <a href="#" onclick="confirmDelete(<?php echo $d['id'];?>)" class="btn btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "footer.php"; ?>