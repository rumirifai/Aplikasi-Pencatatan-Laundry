<?php
include "config.php";

<<<<<<< HEAD
=======
// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Query untuk mendapatkan daftar prioritas dari tabel Prioritas
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
$query = "SELECT * FROM Prioritas";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<<<<<<< HEAD
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

=======
<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 70px; /* Adjust the value as needed */
  }
</style>
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Daftar Prioritas</h2>
          <div class="text-right">
            <a href="tambah_prioritas.php" class="btn btn-primary">Tambah Prioritas</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
<<<<<<< HEAD
          <div class="owl-carousel owl-theme">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
              <div class="card">
                <img src="<?php echo $row['image_url']; ?>" alt="Icon" class="card-image">
                <div class="card-content">
                  <h5 class="card-title"><?php echo $row['jenis_prioritas']; ?></h5>
                  <p class="card-text">
                    <?php
                    $keterangan = $row['keterangan'];
                    $highlighted_keterangan = str_ireplace('prioritas', '<span class="highlight">prioritas</span>', $keterangan);
                    echo $highlighted_keterangan;
                    ?>
                  </p>
                  <p class="card-text">
                    Durasi: <?php echo $row['durasi']; ?> hari
                  </p>
                  <div class="text-right">
                    <a href="edit_prioritas.php?id=<?php echo $row['id_prioritas']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="hapus_prioritas.php?id=<?php echo $row['id_prioritas']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
=======
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Jenis Prioritas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td><?php echo $row['id_prioritas']; ?></td>
                  <td><?php echo $row['jenis_prioritas']; ?></td>
                  <td>
                    <a href="edit_prioritas.php?id=<?php echo $row['id_prioritas']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="hapus_prioritas.php?id=<?php echo $row['id_prioritas']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
        </div>
      </div>
    </div>
  </section>
</main>

<<<<<<< HEAD
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
  $(document).ready(function() {
    $(".owl-carousel").owlCarousel({
      items: 1,
      loop: true,
      nav: true,
      navText: ["<i class='fas fa-chevron-left'></i>", "<i class='fas fa-chevron-right'></i>"]
    });
  });
</script>

=======
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
<?php include 'footer.php'; ?>
