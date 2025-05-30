<?php
session_start();
include "koneksi/config.php";

if (isset($_GET['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $data = mysqli_query($conn, "SELECT * FROM tbl_user WHERE username='$username' AND password='$password'");
    $row = mysqli_num_rows($data);
    if ($row > 0) {
        $a = mysqli_fetch_array($data);

        // Simpan level user di session
        $_SESSION['level'] = $a['level']; 

        if ($a['level'] == 'admin_level') {
            $_SESSION['username'] = $username;
            header("location:admin/index.php");
            exit();
        }
    } else {
        header("location:index.php?pesan=gagal");
        exit();
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css-login/fonts/icomoon/style.css">
    <link rel="stylesheet" href="assets/css-login/css/owl.carousel.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css-login/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css-login/css/style.css">

    <title>SISTEM PENDUKUNG KEPUTUSAN METODE SMART</title>

    <style>
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .contents {
            border: 2px solid #B6A28E;
            background-color: #FFFAEC;
            padding: 20px;
        }
        .btn {
            background-color: #FFA3B4;
            border-radius: 200px;
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 300px; /* Sesuaikan ukuran logo */
        }
        .mb-4.text-center > p { /* Selektor untuk teks judul */
        color: #B6A28E;
        }
        label {
        color: #B6A28E;
        }

        /* style untuk input username dan password */
        .form-control {
        border-bottom-color: #B6A28E; /* mengubah warna border bawah */
        color: #B6A28E; /* mengubah warna teks di dalam input */
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4 text-center">
                                <img src="assets/images/TBLogo.png" alt="" class="logo">
                                <p class="mb-4">Sistem Pendukung Keputusan Metode SMART</p>
                            </div>
                            <?php
                            if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') {
                                echo "<div class='alert alert-danger' role='alert'><span class='fa fa-times'></span> Login Anda Gagal !!! </div>";
                            }
                            ?>

                            <form action="index.php?login=admin" method="post">
                                <div class="form-group first">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="form-group last mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <input type="submit" value="Log In" class="btn text-white btn-block">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/css-login/js/jquery-3.3.1.min.js"></script>
    <script src="assets/css-login/js/popper.min.js"></script>
    <script src="assets/css-login/js/bootstrap.min.js"></script>
    <script src="assets/css-login/js/main.js"></script>

</body>

</html>