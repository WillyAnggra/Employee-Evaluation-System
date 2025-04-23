<?php
include '../koneksi/config.php';
include "header.php";

// Query untuk mengambil data dari tbl_hasil
$sql = "SELECT nama_karyawan, total_nilai, rank FROM tbl_hasil";
$result = mysqli_query($conn, $sql);

// Ambil data nama karyawan, total nilai, dan rank
$namaKaryawan = array();
$totalNilai = array();
$ranks = array();
while ($row = mysqli_fetch_array($result)) {
    $namaKaryawan[] = $row['nama_karyawan'];
    $totalNilai[] = $row['total_nilai'];
    $ranks[] = $row['rank'];
}

// Logika bonus
$bonus = array();
// Inisialisasi bonus dengan nilai default
for ($i = 0; $i < count($namaKaryawan); $i++) {
    if ($ranks[$i] == 1) {
        $bonus[$i] = 500000;
    } else if ($ranks[$i] == 2) {
        $bonus[$i] = 300000;
    } else if ($ranks[$i] == 3) {
        $bonus[$i] = 200000;
    } else {
        $bonus[$i] = 0;
    }
}

// Kondisi jika total_nilai rank 1 dan 2 sama
if (count($totalNilai) > 1 && $totalNilai[0] == $totalNilai[1]) {
    $bonus[0] = 300000;
    $bonus[1] = 300000;
}

// Kondisi jika total_nilai rank 2 dan 3 sama
if (count($totalNilai) > 2 && $totalNilai[1] == $totalNilai[2]) {
    $bonus[1] = 200000;
    $bonus[2] = 200000;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Grafik SPK SMART</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
</head>
<body>

<div class="container mt-4">
    <canvas id="barChart"></canvas>
</div>

<script>
    var ctx = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($namaKaryawan); ?>,
            datasets: [{
                            label: 'Total Nilai',
                            data: <?php echo json_encode($totalNilai); ?>,
                            backgroundColor: '#FFDBE1', // Warna batang grafik
                            borderWidth: 1
                        }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y;
                            // Menampilkan bonus pada tooltip
                            label += ' (Bonus: Rp' + <?php echo json_encode($bonus); ?>[context.dataIndex] + ')';
                            return label;
                        }
                    }
                },
                datalabels: { // Plugin untuk menampilkan label data
                    anchor: 'end',
                    align: 'top',
                    formatter: function(value, context) {
                        return 'Rp' + <?php echo json_encode($bonus); ?>[context.dataIndex];
                    },
                    font: {
                        weight: 'bold'
                    }
                }
            }
        }
    });
</script>

<?php include "footer.php"; ?>

</body>
</html>