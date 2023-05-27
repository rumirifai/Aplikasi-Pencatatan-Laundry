<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Laundry Index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span>LaundryKu</span>
      </a>

      <button class="menu-toggle">
        <i class="bi bi-list"></i>
      </button>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto " href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
          <li><a class="nav-link scrollto" href="pelanggan.php"><i class="fas fa-user"></i>Pelanggan</a></li>
          <li><a class="nav-link scrollto" href="daftar_item.php"><i class="fas fa-list"></i>Daftar Item</a></li>
          <li><a class="nav-link scrollto" href="prioritas.php"><i class="fas fa-star"></i>Prioritas Cucian</a></li>
          <li><a class="nav-link scrollto" href="permintaan_cucian.php"><i class="fas fa-clipboard"></i>Permintaan Cucian</a></li>
          <li><a class="nav-link scrollto" href="daftar_transaksi.php"><i class="fas fa-exchange-alt"></i>Transaksi</a></li>
          <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
        </ul>
      </nav>
    </div>
  </header><!-- End Header -->

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const menuToggle = document.querySelector(".menu-toggle");
      const navbar = document.querySelector("#navbar");

      menuToggle.addEventListener("click", function() {
        navbar.classList.toggle("open");
      });
    });
  </script>
</body>

</html>