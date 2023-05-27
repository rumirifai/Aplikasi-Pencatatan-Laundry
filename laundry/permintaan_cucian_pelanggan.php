<?php
session_start();
include "config.php";

// Pastikan pelanggan telah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];

$query = "SELECT pc.*, p.jenis_prioritas, s.jenis_status_cucian, t.no_transaksi
          FROM PermintaanCucian pc
          INNER JOIN Prioritas p ON pc.id_prioritas = p.id_prioritas
          INNER JOIN StatusCucian s ON pc.id_status_cucian = s.id_status_cucian
          LEFT JOIN Transaksi t ON pc.no_cucian = t.no_cucian
          WHERE pc.id_pelanggan = '$id_pelanggan'
          ORDER BY pc.tgl_masuk DESC";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">


<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Permintaan Cucian</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a href="tambah_permintaan_cucian_pelanggan.php" class="btn btn-primary mb-3">Tambah Permintaan
                        Cucian</a>
                </div>
            </div>
            <div class="row">
                <div class="owl-carousel owl-theme">
                    <?php
                    $counter = 1;
                    while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <div class="card">
                            <h5 class="card-title">
                                <?php echo $row['jenis_prioritas']; ?>
                            </h5>
                            <p class="card-text">
                                <?php echo $row['tgl_masuk']; ?>
                            </p>
                            <p class="card-text">
                                <?php echo $row['tgl_selesai']; ?>
                            </p>
                            <p class="card-text">
                                <?php echo $row['jenis_status_cucian']; ?>
                            </p>
                            <p class="card-text">
                                <?php echo "Nomor Cucian: " . $row['no_cucian']; ?>
                            </p>
                            <div class="button-group">
                                <a href="detail_permintaan_cucian_pelanggan.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                                    class="btn btn-primary">Detail</a>
                                <?php if ($row['jenis_status_cucian'] != 'Selesai' && $row['jenis_status_cucian'] != 'Sedang diproses' && !$row['no_transaksi']): ?>
                                    <div>
                                        <a href="edit_permintaan_cucian_pelanggan.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                                            class="btn btn-secondary">Edit</a>
                                        <a href="hapus_permintaan_cucian_pelanggan.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                                            class="btn btn-danger">Hapus</a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($row['total_item'] > 0): ?>
                                    <?php if ($row['no_transaksi']): ?>
                                        <span class="btn btn-success disabled">Sudah Masuk ke Transaksi</span>
                                    <?php else: ?>
                                        <a href="buat_transaksi_pelanggan.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                                            class="btn btn-success">Lanjutkan ke Transaksi</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="btn btn-success disabled">Total Item Kosong</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                        $counter++;
                    endwhile;
                    ?>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    $(document).ready(function () {
        $('.owl-carousel').owlCarousel({
            items: 3,
            loop: false,
            margin: 20,
            nav: true,
            dots: false,
            navText: ["<i class='bi bi-chevron-left'></i>", "<i class='bi bi-chevron-right'></i>"],
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });
    });
</script>

<?php include 'footer.php'; ?>
