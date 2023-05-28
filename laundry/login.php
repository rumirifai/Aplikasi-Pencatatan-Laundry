<?php
include "config.php";


// Inisialisasi variabel untuk menyimpan pesan error
$error = "";

// Memeriksa apakah form login telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Mengambil data dari form login
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Mencari data admin berdasarkan username
  $adminQuery = "SELECT * FROM Admin WHERE username='$username'";
  $adminResult = mysqli_query($conn, $adminQuery);

  // Mencari data pelanggan berdasarkan username
  $pelangganQuery = "SELECT * FROM Pelanggan WHERE username='$username'";
  $pelangganResult = mysqli_query($conn, $pelangganQuery);

  if (mysqli_num_rows($adminResult) == 1) {
    // Jika data ditemukan di tabel Admin
    $row = mysqli_fetch_assoc($adminResult);
    $adminPassword = $row["password"];

    // Memverifikasi password
    if ($password == $adminPassword) {
      // Jika password cocok, login berhasil sebagai admin
      session_start();
      $_SESSION["username"] = $username;
      $_SESSION["role"] = "admin";
      header("Location: dashboard_admin.php");
      exit();
    } else {
      // Jika password tidak cocok, tampilkan pesan error
      $error = "Username atau password salah.";
    }
  } elseif (mysqli_num_rows($pelangganResult) == 1) {
    // Jika data ditemukan di tabel Pelanggan
    $row = mysqli_fetch_assoc($pelangganResult);
    $pelangganPassword = $row["password"];

    // Memverifikasi password
    if (password_verify($password, $pelangganPassword)) {
      // Jika password cocok, login berhasil sebagai pelanggan
      session_start();
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
    // Jika username tidak ditemukan di kedua tabel
    $error = "Username atau password salah.";
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
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

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

  a.register-link {
    font-weight: bold;
    color: #4154f1;
    text-decoration: none;
  }

  a.register-link:hover {
    color: #2f6aff;
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
      <div class="mt-3 text-center">Belum punya akun? <a href="registrasi_pelanggan.php" class="register-link">Daftar
          di sini</a></div>
      <a href="index.php" class="btn btn-secondary mt-3">Kembali ke Halaman Awal</a>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="assets/vendor/js/bootstrap.min.js"></script>
</body>

</html>
<?php include 'footer.php'; ?>