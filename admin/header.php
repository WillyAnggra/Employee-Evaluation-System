<?php
include "../koneksi/config.php";
include "header.php";

// Proses hapus data
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id'];

    // Menghapus data kriteria
    $delete = mysqli_query($conn, "DELETE FROM tbl_kriteria WHERE id='$id'");

    if ($delete) {
        // Jika data berhasil dihapus, update normalisasi
        $query_total_bobot = "SELECT SUM(bobot_kriteria) AS total FROM tbl_kriteria";
        $result_total_bobot = mysqli_query($conn, $query_total_bobot);
        $row_total_bobot = mysqli_fetch_assoc($result_total_bobot);
        $total_bobot = $row_total_bobot['total'];

        if ($total_bobot != 100) {
            mysqli_query($conn, "UPDATE tbl_kriteria SET normalisasi = 0");
        }

        // Tampilkan pesan sukses menggunakan SweetAlert2
        echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil dihapus.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'kriteria.php';
                    }
                });
              </script>";
        exit(); // Pastikan tidak ada output lain sebelum redirect
    } else {
        // Tampilkan pesan error jika gagal menghapus
        echo "<script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal menghapus data: " . mysqli_error($conn) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}

// Menghitung total bobot kriteria
$query_total_bobot = "SELECT SUM(bobot_kriteria) AS total FROM tbl_kriteria";
$result_total_bobot = mysqli_query($conn, $query_total_bobot);
$row_total_bobot = mysqli_fetch_assoc($result_total_bobot);
$total_bobot = $row_total_bobot['total'];
?>

<style>
    .btn-dark {
        background-color: #705C53;
    }
</style>

<script>
    // Fungsi untuk konfirmasi hapus menggunakan SweetAlert2
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL hapus data jika pengguna mengklik "Ya, hapus!"
                window.location.href = "kriteria.php?aksi=hapus&id=" + id;
            }
        });
    }
</script>

<h2 class="mb-4">DATA KRITERIA</h2>
<hr>
<div class="shadow p-5">
    <?php if ($total_bobot < 100) { ?>
        <a href="kriteria-add.php" class="btn btn-success mb-3"><span class="fa fa-plus">Tambah Kriteria</span></a>
    <?php } else { ?>
        <button class="btn btn-success mb-3" disabled><span class="fa fa-plus">Tambah Kriteria</span></button>
        <span class="text-muted"> *Total nilai bobot kriteria sudah mencapai 100</span>
    <?php } ?>

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
                        if ($row['Field'] != 'id') { // Menyembunyikan kolom 'id'
                            // Mengubah tampilan nama kriteria menjadi lebih rapi
                            $namaKriteria = ucwords(str_replace("_", " ", $row['Field']));
                            echo "<th class='text-center align-middle'>" . $namaKriteria . "</th>";
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
                        <td class="text-center align-middle"><?php echo $no++; ?></td>
                        <?php
                        $result = mysqli_query($conn, "SHOW COLUMNS FROM tbl_kriteria");
                        while ($row = mysqli_fetch_array($result)) {
                            if ($row['Field'] != 'id') { // Menyembunyikan kolom 'id'
                                // Mengubah tampilan nama kriteria menjadi lebih rapi
                                $namaKriteria = ucwords(str_replace("_", " ", $d[$row['Field']]));
                                echo "<td class='text-center align-middle'>" . $namaKriteria . "</td>";
                            }
                        }
                        ?>
                        <td class="text-center align-middle">
                            <a href="kriteria-update.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-dark">Edit</a>
                            <a href="#" onclick="confirmDelete(<?php echo $d['id']; ?>)" class="btn btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "footer.php"; ?>