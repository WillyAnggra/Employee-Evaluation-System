<?php
include "../koneksi/config.php";
include "header.php";

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $id = $_GET['id'];

        // Menghapus data kriteria
        mysqli_query($conn, "DELETE FROM tbl_kriteria WHERE id='$id'");

        // Mengupdate normalisasi menjadi 0 jika total bobot tidak lagi 100
        $query_total_bobot = "SELECT SUM(bobot_kriteria) AS total FROM tbl_kriteria";
        $result_total_bobot = mysqli_query($conn, $query_total_bobot);
        $row_total_bobot = mysqli_fetch_assoc($result_total_bobot);
        $total_bobot = $row_total_bobot['total'];

        if ($total_bobot!= 100) {
            mysqli_query($conn, "UPDATE tbl_kriteria SET normalisasi = 0");
        }

        header("location:kriteria.php");
    }
}

// Menghitung total bobot kriteria
$query_total_bobot = "SELECT SUM(bobot_kriteria) AS total FROM tbl_kriteria";
$result_total_bobot = mysqli_query($conn, $query_total_bobot);
$row_total_bobot = mysqli_fetch_assoc($result_total_bobot);
$total_bobot = $row_total_bobot['total'];?>

<style>
  .btn-dark {
        background-color: #705C53;
    }
</style>


<h2 class="mb-4">DATA KRITERIA</h2>
<hr>
<div class="shadow p-5">

    <?php if ($total_bobot < 100) {?>
        <a href="kriteria-add.php" class="btn btn-success mb-3"><span class="fa fa-plus">Tambah Kriteria</span></a>
    <?php } else {?>
        <button class="btn btn-success mb-3" disabled><span class="fa fa-plus">Tambah Kriteria</span></button>
        <span class="text-muted"> *Total nilai bobot kriteria sudah mencapai 100</span>
    <?php }?>

    <hr>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle">No</th>
                    <?php
                    // Mendapatkan informasi kolom dari tabel 'tbl_kriteria'
                    $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_kriteria");
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['Field']!= 'id') { // Menyembunyikan kolom 'id'
                            // Mengubah tampilan nama kriteria menjadi lebih rapi
                            $namaKriteria = ucwords(str_replace("_", " ", $row['Field']));
                            echo "<th class='text-center align-middle'>". $namaKriteria. "</th>";
                        }
                    }
                  ?>
                    <th class="text-center align-middle">Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data = mysqli_query($conn, "SELECT * FROM tbl_kriteria");
                while ($d = mysqli_fetch_array($data)) {
              ?>
                    <tr>
                        <td class="text-center align-middle"><?php echo $no++;?></td>
                        <?php
                        $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_kriteria");
                        while ($row = mysqli_fetch_array($result)) {
                            if ($row['Field']!= 'id') { // Menyembunyikan kolom 'id'
                                // Mengubah tampilan nama kriteria menjadi lebih rapi
                                $namaKriteria = ucwords(str_replace("_", " ", $d[$row['Field']]));
                                echo "<td class='text-center align-middle'>". $namaKriteria. "</td>";
                            }
                        }
                      ?>
                        <td class="text-center align-middle">
                            <a href="kriteria-update.php?id=<?php echo $d['id'];?>" class="btn btn-sm btn-dark">Edit</a>
                            <a href="?aksi=hapus&id=<?php echo $d['id'];?>" class="btn btn-sm btn-danger">Hapus</a> 
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<?php include "footer.php";?>