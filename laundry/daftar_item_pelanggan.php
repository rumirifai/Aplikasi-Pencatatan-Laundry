<?php
include "config.php";

$query = "SELECT i.*, p.jenis_prioritas 
          FROM Item i 
          LEFT JOIN Prioritas p ON i.id_prioritas = p.id_prioritas";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Daftar Item</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Jenis Item</th>
                <th>Prioritas</th>
                <th>Harga per Item</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td><?php echo $row['id_item']; ?></td>
                  <td><?php echo $row['jenis_item']; ?></td>
                  <td><?php echo $row['jenis_prioritas']; ?></td>
                  <td><?php echo $row['harga_per_item']; ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
