<?php
include "config.php";

$query = "SELECT * FROM Prioritas";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<link rel="stylesheet" href="assets/css/styles.css">

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Prioritas Cucian</h2>
        </div>
      </div>
      <div class="row">
        <div class="owl-carousel owl-theme">
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="card">
              <img src="<?php echo $row['image_url']; ?>" alt="Icon" class="card-image">
              <h5 class="card-title"><?php echo $row['jenis_prioritas']; ?></h5>
              <p class="card-text">
                <?php
                $keterangan = $row['keterangan'];
                $highlighted_keterangan = str_ireplace('prioritas', '<span class="highlight">prioritas</span>', $keterangan);
                echo $highlighted_keterangan;
                ?>
              </p>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </section>
</main>

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

<?php include 'footer.php'; ?>
