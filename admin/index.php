<?php include "header.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pendukung Keputusan</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #FFA3B4, #FFCDD2);
            padding: 120px 0;
            color: #fff;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .dashboard-card {
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-top: -80px;
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        .feature-icon {
            font-size: 3rem;
            color: #B6A28E;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <h1>Penilaian Kinerja</h1>
            <p>Tari Bakery</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="dashboard-card" data-aos="zoom-in">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <i class="bi bi-table feature-icon"></i>
                            <h4>Manajemen Data</h4>
                            <p>Kelola data karyawan dengan mudah</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="bi bi-funnel feature-icon"></i>
                            <h4>Kriteria Penilaian</h4>
                            <p>Tentukan kriteria secara komprehensif</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="bi bi-bar-chart feature-icon"></i>
                            <h4>Analisis Mendalam</h4>
                            <p>Visualisasi hasil keputusan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>