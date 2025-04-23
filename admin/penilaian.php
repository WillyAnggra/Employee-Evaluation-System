<?php
include "../koneksi/config.php";
include "header.php";

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tbl_penilaian WHERE id='$id'");
    header("location:penilaian.php");
} ?>

<style>
    .btn-dark {
        background-color: #705C53;
    }
</style>


<h2 class="mb-4">DATA PENILAIAN</h2>
<hr>

<a href="penilaian-add.php" class="btn btn-success mb-3">Tambah Penilaian</a>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center align-middle">No</th>
                <?php
                // Mendapatkan informasi kolom dari tabel 'tbl_penilaian' (kecuali id)
                $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_penilaian");
                while ($row = mysqli_fetch_array($result)) {
                    $field = $row['Field'];
                    if ($field != 'id') {
                        echo "<th class='text-center align-middle'>" . ucwords(str_replace("_", " ", $field)) . "</th>"; // Tampilkan semua kolom (kecuali id)
                    }
                }
                ?>
                <th class="text-center align-middle" style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data = mysqli_query($conn, "SELECT * FROM tbl_penilaian");
            while ($d = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td class="text-center align-middle"><?php echo $no++; ?></td>
                    <?php
                    // Menampilkan data sesuai kolom yang ada di tabel (kecuali id)
                    $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_penilaian");
                    while ($row = mysqli_fetch_array($result)) {
                        $field = $row['Field'];
                        if ($field != 'id') {
                            // Format tanggal dan waktu
                            if ($field == 'tgl_penilaian') {
                                $value = date('d-m-Y', strtotime($d[$field])); // Tanggal saja
                            } else if ($field == 'waktu_penilaian') {
                                $value = date('H:i:s', strtotime($d[$field])); // Waktu saja
                            } else if ($field == 'update_tgl_penilaian' && $d[$field] != null) {
                                $value = date('d-m-Y', strtotime($d[$field])); // Tanggal Update
                            } else if ($field == 'update_waktu_penilaian' && $d[$field] != null) {
                                $value = date('H:i:s', strtotime($d[$field])); // Waktu update saja
                            } else {
                                $value = $d[$field];
                            }

                            echo "<td class='text-center align-middle'>" . $value . "</td>"; // Tampilkan semua kolom (kecuali id)
                        }
                    }
                    ?>
                    <td class="text-center align-middle">
                        <a href="penilaian-update.php?id=<?php echo $d['id'];?>" class="btn btn-sm btn-dark">Edit</a>
                        <a href="#" onclick="confirmDelete(<?php echo $d['id'];?>)" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>