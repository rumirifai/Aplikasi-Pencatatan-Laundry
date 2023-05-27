<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Memeriksa apakah parameter id_item ada
if (!isset($_GET['id'])) {
  header("Location: daftar_item.php");
  exit;
}

$idItem = $_GET['id'];

// Mengambil data item berdasarkan id_item
$query = "SELECT * FROM Item WHERE id_item = '$idItem'";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

// Memeriksa apakah item ditemukan
if (!$item) {
  header("Location: daftar_item.php");
  exit;
}

// Mengambil daftar prioritas dari tabel Prioritas
$prioritasQuery = "SELECT * FROM Prioritas";
$prioritasResult = mysqli_query($conn, $prioritasQuery);

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
  // Mengambil data dari form
  $jenisItem = $_POST['jenis_item'];
  $hargaPerItem = $_POST['harga_per_item'];
  $idPrioritas = $_POST['id_prioritas'];

  // Validasi data
  if (empty($jenisItem) || empty($hargaPerItem) || empty($idPrioritas)) {
    $error = "Silakan lengkapi semua field.";
  } else {
    // Mengupdate data item di tabel Item
    $updateQuery = "UPDATE Item SET jenis_item = '$jenisItem', harga_per_item = '$hargaPerItem', id_prioritas = '$idPrioritas' WHERE id_item = '$idItem'";
    mysqli_query($conn, $updateQuery);

    // Mengalihkan kembali ke halaman daftar_item.php setelah item diperbarui
    header("Location: daftar_item.php");
    exit;
  }
}

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 70px;
    /* Adjust the value as needed */
  }
</style>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Edit Item</h2>
          <div class="text-right">
            <a href="daftar_item.php" class="btn btn-primary">Kembali ke Daftar Item</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="POST">
            <div class="form-group">
              <label for="jenis_item">Jenis Item</label>
              <input type="text" class="form-control" id="jenis_item" name="jenis_item" value="<?php echo $item['jenis_item']; ?>">
            </div>
            <div class="form-group">
              <label for="harga_per_item">Harga per Item</label>
              <input type="number" step="0.01" class="form-control" id="harga_per_item" name="harga_per_item" value="<?php echo $item['harga_per_item']; ?>">
            </div>
            <div class="form-group">
              <label for="id_prioritas">Prioritas</label>
              <select class="form-control" id="id_prioritas" name="id_prioritas">
                <?php while ($row = mysqli_fetch_assoc($prioritasResult)) : ?>
                  <option value="<?php echo $row['id_prioritas']; ?>" <?php if ($row['id_prioritas'] == $item['id_prioritas']) echo 'selected'; ?>><?php echo $row['jenis_prioritas']; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
            </div>
            <?php if ($error) : ?>
              <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
