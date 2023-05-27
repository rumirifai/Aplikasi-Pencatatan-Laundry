<?php
session_start();
include "config.php";

// Pastikan pelanggan telah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah parameter no_cucian tersedia dan tidak kosong
if (isset($_GET['no_cucian']) && !empty($_GET['no_cucian'])) {
    $noCucian = $_GET['no_cucian'];

    $query = "SELECT pc.*, p.nama, s.jenis_status_cucian
          FROM PermintaanCucian pc
          INNER JOIN Pelanggan p ON pc.id_pelanggan = p.id_pelanggan
          INNER JOIN StatusCucian s ON pc.id_status_cucian = s.id_status_cucian
          WHERE pc.no_cucian = '$noCucian'
          AND pc.id_pelanggan = '{$_SESSION['id_pelanggan']}'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        // Tidak ada data permintaan cucian yang sesuai, alihkan kembali ke halaman sebelumnya
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
} else {
    // Parameter no_cucian tidak tersedia atau kosong, alihkan kembali ke halaman sebelumnya
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}

// Membuat transaksi baru menggunakan prosedur
if (isset($_POST['submit'])) {
    $createTransactionQuery = "CALL CreateTransaction($noCucian)";
    mysqli_query($conn, $createTransactionQuery);

    header("Location: daftar_transaksi_pelanggan.php");
    exit;
}

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <h2>Transaksi Cucian</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Cucian</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Selesai</th>
                        <th>Status Cucian</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['no_cucian']; ?></td>
                        <td><?php echo $data['nama']; ?></td>
                        <td><?php echo $data['tgl_masuk']; ?></td>
                        <td><?php echo $data['tgl_selesai']; ?></td>
                        <td><?php echo $data['jenis_status_cucian']; ?></td>
                    </tr>
                </tbody>
            </table>
            <form action="" method="post">
                <input type="hidden" name="no_cucian" value="<?php echo $noCucian; ?>">
                <button type="submit" name="submit" class="btn btn-primary">Buat Transaksi</button>
                <a href="permintaan_cucian_pelanggan.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
