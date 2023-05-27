<?php
include "config.php";

session_start();

// Memeriksa apakah form login telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Query SQL untuk mendapatkan data admin dari database berdasarkan username
  $query = "SELECT * FROM admin WHERE username='$username'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $storedPassword = $row['password'];

    // Memeriksa kecocokan password
    if ($password === $storedPassword) {
      // Jika password cocok, set $_SESSION['username'] dan redirect ke halaman dashboard admin
      $_SESSION['username'] = $username;
      header("Location: dashboard_admin.php");
      exit();
    } else {
      // Jika password tidak cocok, tampilkan pesan error
      $error = "Username atau password salah.";
    }
  } else {
    // Jika username tidak ditemukan di admin, cari di tabel pelanggan
    $query = "SELECT * FROM pelanggan WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $storedPassword = $row['password'];

      // Memeriksa kecocokan password
      if ($password === $storedPassword) {
        // Jika password cocok, set $_SESSION['username'] dan $_SESSION['role'] sesuai data pelanggan
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'pelanggan';
        $_SESSION['id_pelanggan'] = $row['id_pelanggan'];
        header("Location: dashboard_pelanggan.php");
        exit();
      } else {
        // Jika password tidak cocok, tampilkan pesan error
        $error = "Username atau password salah.";
      }
    } else {
      // Jika username tidak ditemukan, tampilkan pesan error
      $error = "Username atau password salah.";
    }
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/laundry.png" rel="icon">
  <link href="assets/img/laundry.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<style>
  body {
    background-color: #B9EDDD;
  }

  .login-container {
    max-width: 400px;
    margin: 0 auto;
    margin-top: 150px;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .login-container h2 {
    text-align: center;
    margin-bottom: 20px;
  }

  .login-container .form-control {
    border-radius: 3px;
  }

  .login-container .btn {
    margin-top: 20px;
    width: 100%;
  }
</style>

<body>
  <div class="container">
    <div class="login-container">
      <h2>Login</h2>
      <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
            </div>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
          </div>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
            </div>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <?php
        // Menampilkan pesan error jika terdapat error
        if (isset($error)) {
          echo '<div class="alert alert-danger mt-3">' . $error . '</div>';
        }
        ?>
      </form>
      <div class="mt-3 text-center">Belum punya akun? <a href="registrasi_pelanggan.php">Daftar di sini</a></div>
      <a href="index.php" class="btn btn-secondary mt-3">Kembali ke Halaman Awal</a>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="assets/vendor/js/bootstrap.min.js"></script>
</body>
</html>
