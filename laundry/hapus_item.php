<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Periksa apakah parameter ID tersedia dan bukan kosong
if (isset($_GET['id']) && !empty($_GET['id'])) {
  // Mendapatkan ID item yang akan dihapus dari parameter URL
  $idItem = $_GET['id'];

  // Periksa apakah formulir dikirimkan dengan metode POST
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perbarui nilai id_item menjadi NULL dalam tabel itemcucian untuk item yang akan dihapus
    $updateItemCucianQuery = "UPDATE itemcucian SET id_item = NULL WHERE id_item = $idItem";
    $resultUpdateItemCucian = mysqli_query($conn, $updateItemCucianQuery);

    // Periksa apakah pembaruan itemcucian berhasil
    if ($resultUpdateItemCucian) {
      // Hapus item dari tabel Item berdasarkan ID
      $deleteQuery = "DELETE FROM Item WHERE id_item = $idItem";
      $resultDelete = mysqli_query($conn, $deleteQuery);

      if ($resultDelete) {
        // Item berhasil dihapus, alihkan ke halaman daftar_item.php
        header("Location: daftar_item.php");
        exit;
      } else {
        // Kesalahan saat menghapus item
        echo "Terjadi kesalahan saat menghapus item.";
      }
    } else {
      // Kesalahan saat memperbarui itemcucian
      echo "Terjadi kesalahan saat memperbarui itemcucian.";
    }
  }
} else {
  // Parameter ID tidak tersedia atau kosong, alihkan ke halaman daftar_item.php
  header("Location: daftar_item.php");
  exit;
}

mysqli_close($conn);
?>

<?php include 'header.php'; ?>
<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 80px; /* Adjust the value as needed */
  }
</style>
<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <h2>Hapus Item</h2>
      <p>Apakah Anda yakin ingin menghapus item ini?</p>
      <form method="POST">
        <input type="submit" name="hapus" value="Hapus" class="btn btn-danger">
        <a href="daftar_item.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
