<?php include 'config.php'; ?>

<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['no_cucian'])) {
    header("Location: permintaan_cucian.php");
    exit;
}

$noCucian = $_GET['no_cucian'];

$queryPermintaan = "SELECT pc.*, p.nama, pri.jenis_prioritas, sc.jenis_status_cucian
                    FROM PermintaanCucian pc
                    INNER JOIN Pelanggan p ON pc.id_pelanggan = p.id_pelanggan
                    LEFT JOIN Prioritas pri ON pc.id_prioritas = pri.id_prioritas
                    LEFT JOIN StatusCucian sc ON pc.id_status_cucian = sc.id_status_cucian
                    WHERE pc.no_cucian = $noCucian";
$resultPermintaan = mysqli_query($conn, $queryPermintaan);
$permintaan = mysqli_fetch_assoc($resultPermintaan);

$queryItem = "SELECT ic.*, i.jenis_item, i.harga_per_item
              FROM ItemCucian ic
              INNER JOIN Item i ON ic.id_item = i.id_item
              WHERE ic.no_cucian = $noCucian";
$resultItem = mysqli_query($conn, $queryItem);

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Detail Permintaan Cucian</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h4>No. Cucian: <?php echo $permintaan['no_cucian']; ?></h4>
                    <p>Nama Pelanggan: <?php echo $permintaan['nama']; ?></p>
                    <p>Prioritas: <?php echo $permintaan['jenis_prioritas']; ?></p>
                    <p>Tanggal Masuk: <?php echo $permintaan['tgl_masuk']; ?></p>
                    <p>Tanggal Selesai: <?php echo $permintaan['tgl_selesai']; ?></p>
                    <p>Total Item: <?php echo $permintaan['total_item']; ?></p>
                    <p>Status Cucian: <?php echo $permintaan['jenis_status_cucian']; ?></p>

                    <hr>

                    <h4>Daftar Item:</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Item</th>
                                <th>Jenis Item</th>
                                <th>Ukuran</th>
                                <th>Warna</th>
                                <th>Jumlah Item</th>
                                <th>Harga per Item</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($resultItem)): ?>
                                <tr>
                                    <td><?php echo $row['no_itemCucian']; ?></td>
                                    <td><?php echo $row['jenis_item']; ?></td>
                                    <td><?php echo $row['ukuran']; ?></td>
                                    <td><?php echo $row['warna']; ?></td>
                                    <td><?php echo $row['jumlah_item']; ?></td>
                                    <td><?php echo $row['harga_per_item']; ?></td>
                                    <td><?php echo $row['harga_itemCucian']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <div class="float-right">
                        <a href="permintaan_cucian_pelanggan.php" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
