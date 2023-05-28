<?php
include "config.php";

// Inisialisasi variabel untuk menyimpan pesan error
$error = "";

// Memeriksa apakah form pendaftaran telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Mengambil data dari form pendaftaran
  $nama = $_POST["nama"];
  $alamat = $_POST["alamat"];
  $telepon = $_POST["telepon"];
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Memeriksa apakah username sudah digunakan
  $query = "SELECT * FROM pelanggan WHERE username='$username'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Jika username sudah digunakan, tampilkan pesan error
    $error = "Username sudah digunakan. Silakan gunakan username lain.";
  } else {
    // Jika username belum digunakan, lakukan proses pendaftaran

    // Hash password menggunakan bcrypt
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Query SQL untuk menyimpan data pelanggan baru ke database
    $query = "INSERT INTO pelanggan (nama, alamat, telepon, username, password) VALUES ('$nama', '$alamat', '$telepon', '$username', '$hashedPassword')";
    $result = mysqli_query($conn, $query);

    if ($result) {
      // Jika pendaftaran berhasil, redirect ke halaman login
      header("Location: login.php");
      exit();
    } else {
      // Jika terjadi kesalahan saat menyimpan data, tampilkan pesan error
      $error = "Terjadi kesalahan. Silakan coba lagi.";
    }
  }
}

mysqli_close($conn);
?>

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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Pelanggan</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      max-width: 400px;
      margin: 0 auto;
      margin-top: 150px;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    p {
      text-align: center;
    }

    a {
      color: #007bff;
      text-decoration: none;
    }

    .error {
      color: #dc3545;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Registrasi Pelanggan Baru</h2>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required>
      </div>
      <div class="form-group">
        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" required>
      </div>
      <div class="form-group">
        <label for="telepon">Telepon</label>
        <input type="text" id="telepon" name="telepon" required>
      </div>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <?php
      // Menampilkan pesan error jika terdapat error
      if ($error !== "") {
        echo '<div class="error">' . $error . '</div>';
      }
      ?>
      <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
  </div>
</body>

</html>
<?php include 'footer.php'; ?>