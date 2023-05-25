<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Periksa apakah data pelanggan yang akan diupdate sudah tersedia
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Query untuk mendapatkan data pelanggan berdasarkan ID
  $query = "SELECT * FROM Pelanggan WHERE id_pelanggan = $id";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $nama = $row['nama'];
    $alamat = $row['alamat'];
    $telepon = $row['telepon'];
  } else {
    // Jika data pelanggan tidak ditemukan, redirect ke halaman pelanggan
    header("Location: pelanggan.php");
    exit;
  }
}

// Proses update pelanggan saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $telepon = $_POST['telepon'];

  // Query untuk melakukan update data pelanggan
  $query = "UPDATE Pelanggan SET nama = '$nama', alamat = '$alamat', telepon = '$telepon' WHERE id_pelanggan = $id";
  mysqli_query($conn, $query);

  // Redirect kembali ke halaman pelanggan setelah update
  header("Location: pelanggan.php");
  exit;
}

mysqli_close($conn);
?>

<?php include 'head.php'; ?>
<?php include 'header.php'; ?>
<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 80px; /* Adjust the value as needed */
  }
</style>
<main id="main">
  <section id="edit-pelanggan" class="edit-pelanggan">
    <div class="container">
      <h4>Edit Pelanggan</h4>
      <form method="post" action="" onsubmit="saveChanges()">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
          <label for="nama">Nama:</label>
          <input type="text" name="nama" id="nama" value="<?php echo $nama; ?>" required>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat:</label>
          <textarea name="alamat" id="alamat" required><?php echo $alamat; ?></textarea>
        </div>
        <div class="form-group">
          <label for="telepon">Telepon:</label>
          <input type="text" name="telepon" id="telepon" value="<?php echo $telepon; ?>" required>
        </div>
        <div class="form-group">
          <input type="submit" name="simpan" value="Simpan">
          <a href="pelanggan.php">Batal</a>
        </div>
      </form>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

<script>
  // Mendeteksi perubahan pada form
  let formChanged = false;
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input, textarea').forEach(function(input) {
      input.addEventListener('input', function() {
        formChanged = true;
      });
    });
  });

  // Konfirmasi sebelum meninggalkan halaman jika perubahan belum disimpan
  window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
      e.preventDefault();
      e.returnValue = '';
    }
  });

  // Fungsi untuk menyimpan perubahan pada form sebelum meninggalkan halaman
  function saveChanges() {
    formChanged = false;
  }
</script>
