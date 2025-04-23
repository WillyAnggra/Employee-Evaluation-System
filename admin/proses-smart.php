<?php
// Koneksi Database
include '../koneksi/config.php';

// Header
include "header.php";

// ================================================
// AMBIL DATA KRITERIA
// ================================================
$kriteria = array();
$sql_kriteria = "SELECT * FROM tbl_kriteria";
$result_kriteria = mysqli_query($conn, $sql_kriteria);

if (!$result_kriteria) {
    die("Error mengambil data kriteria: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result_kriteria)) {
    $kriteria[$row['nama_kriteria']] = array(
        'jenis' => $row['jenis'],
        'normalisasi' => $row['normalisasi']
    );
}

// ================================================
// AMBIL DATA PENILAIAN
// ================================================
$data_penilaian = array();
$sql_penilaian = "SELECT * FROM tbl_penilaian";
$result_penilaian = mysqli_query($conn, $sql_penilaian);

if (!$result_penilaian) {
    die("Error mengambil data penilaian: " . mysqli_error($conn));
}

// Ambil nama kolom numerik
$columns = array();
if ($result_penilaian) {
    $fieldinfo = mysqli_fetch_fields($result_penilaian);
    foreach ($fieldinfo as $field) {
        // Hanya tampilkan kolom dengan tipe numerik (int, float, double) dan bukan 'id'
        if (in_array($field->type, [1, 2, 3, 8, 9]) && $field->name != 'id') {
            $columns[] = $field->name;
        }
    }
    mysqli_data_seek($result_penilaian, 0);
}

while ($row = mysqli_fetch_assoc($result_penilaian)) {
    $data_penilaian[] = $row;
}

// ================================================
// HITUNG NILAI UTILITAS
// ================================================
$data_utilitas = array();

foreach ($data_penilaian as $karyawan) {
    // Ambil semua nilai kriteria
    $nilai_karyawan = array();
    foreach ($kriteria as $nama_kriteria => $data) {
        $nilai_karyawan[] = $karyawan[$nama_kriteria];
    }
    
    // Hitung min dan max per karyawan
    $min = min($nilai_karyawan);
    $max = max($nilai_karyawan);
    
    $utilitas = array();
    foreach ($kriteria as $nama_kriteria => $data) {
        $nilai = $karyawan[$nama_kriteria];
        $range = $max - $min;
        
        // Handle division by zero
        if ($range == 0) {
            $utility = 0;
        } else {
            if ($data['jenis'] == 'Benefit') {
                $utility = ($nilai - $min) / $range;
            } else {
                $utility = ($max - $nilai) / $range;
            }
        }
        $utilitas[$nama_kriteria] = $utility;
    }
    $data_utilitas[$karyawan['nama_karyawan']] = $utilitas;
}

// ================================================
// HITUNG HASIL AKHIR
// ================================================
$hasil_akhir = array();

foreach ($data_utilitas as $nama => $utilitas) {
    $total = 0;
    foreach ($kriteria as $nama_kriteria => $data) {
        $total += $utilitas[$nama_kriteria] * $data['normalisasi'];
    }
    $hasil_akhir[$nama] = $total;
}

// ================================================
// SIMPAN HASIL KE TABEL tbl_hasil
// ================================================
// Hapus data lama
mysqli_query($conn, "TRUNCATE TABLE tbl_hasil");

// Insert data baru dengan ranking
$rank = 1;
arsort($hasil_akhir); // Urutkan dari nilai tertinggi ke terendah
foreach ($hasil_akhir as $nama => $total) {
    $nama = mysqli_real_escape_string($conn, $nama);
    $total = (float)$total;
    $sql = "INSERT INTO tbl_hasil (nama_karyawan, total_nilai, rank) VALUES ('$nama', $total, $rank)";
    mysqli_query($conn, $sql);
    $rank++;
}

// ================================================
// TAMPILKAN SEMUA TABEL DALAM DIV shadow p-5
// ================================================
echo '<div class="container mt-4">
        <h2 class="mb-4">Proses SPK SMART</h2>
        <hr>
        <div class="shadow p-5">';

// 1. TAMPILKAN TABEL DATA PENILAIAN
echo '<h3>Data Penilaian</h3>
      <div class="table-responsive">
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>Nama Karyawan</th>';

// Tampilkan kolom numerik
foreach ($columns as $col) {
    if (in_array($col, array_keys($kriteria))) {
        echo '<th>' . ucwords(str_replace('_', ' ', $col)) . '</th>';
    }
}

echo '          </tr>
          </thead>
          <tbody>';

foreach ($data_penilaian as $row) {
    echo '<tr>
            <td>' . $row['nama_karyawan'] . '</td>';
    
    // Tampilkan nilai kolom numerik
    foreach ($columns as $col) {
        if (in_array($col, array_keys($kriteria))) {
            echo '<td>' . $row[$col] . '</td>';
        }
    }
    
    echo '</tr>';
}

echo '</tbody></table></div>';

// 2. TAMPILKAN TABEL UTILITAS
echo '<h3 class="mt-5">Nilai Utilitas</h3>
      <div class="table-responsive">
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>Nama Karyawan</th>';

foreach ($kriteria as $nama_kriteria => $data) {
    echo '<th>' . ucwords(str_replace('_', ' ', $nama_kriteria)) . '</th>';
}

echo '          </tr>
          </thead>
          <tbody>';

foreach ($data_utilitas as $nama => $utilitas) {
    echo '<tr>
            <td>' . $nama . '</td>';
    foreach ($utilitas as $nilai) {
        echo '<td>' . number_format($nilai, 4) . '</td>';
    }
    echo '</tr>';
}

echo '</tbody></table></div>';

// 3. TAMPILKAN TABEL HASIL AKHIR (TIDAK DIURUTKAN)
echo '<h3 class="mt-5">Hasil Akhir</h3>
      <div class="table-responsive">
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>Nama Karyawan</th>';

foreach ($kriteria as $nama_kriteria => $data) {
    echo '<th>' . ucwords(str_replace('_', ' ', $nama_kriteria)) . '</th>';
}

echo '          <th>Total</th>
              </tr>
          </thead>
          <tbody>';

foreach ($hasil_akhir as $nama => $total) {
    echo '<tr>
            <td>' . $nama . '</td>';
    
    foreach ($kriteria as $nama_kriteria => $data) {
        $nilai = number_format($data_utilitas[$nama][$nama_kriteria] * $data['normalisasi'], 3);
        echo '<td>' . $nilai . '</td>';
    }
    
    echo '<td>' . number_format($total, 3) . '</td>
          </tr>';
}

echo '</tbody></table></div>';

// 4. TAMPILKAN TABEL PERANGKINGAN (DIURUTKAN)
echo '<h3 class="mt-5">Perangkingan</h3>
      <div class="table-responsive">
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>Nama Karyawan</th>
                  <th>Total Nilai</th>
                  <th>Rank</th>
              </tr>
          </thead>
          <tbody>';

// Ambil data dari tbl_hasil dan urutkan berdasarkan total_nilai DESC
$sql_ranking = "SELECT nama_karyawan, total_nilai, rank FROM tbl_hasil ORDER BY total_nilai DESC";
$result_ranking = mysqli_query($conn, $sql_ranking);

if (!$result_ranking) {
    die("Error mengambil data ranking: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result_ranking)) {
    echo '<tr>
            <td>' . $row['nama_karyawan'] . '</td>
            <td>' . number_format($row['total_nilai'], 3) . '</td>
            <td>' . $row['rank'] . '</td>
          </tr>';
}

echo '</tbody></table></div>
      </div> <!-- Tutup div shadow p-5 -->
    </div>'; // Tutup div container

// Footer
include "footer.php";
?>