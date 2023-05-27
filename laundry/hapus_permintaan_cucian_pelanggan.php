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

    $query = "SELECT pc.*, p.jenis_prioritas, s.jenis_status_cucian
          FROM PermintaanCucian pc
          INNER JOIN Prioritas p ON pc.id_prioritas = p.id_prioritas
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

// Proses penghapusan permintaan cucian dan transaksi terkait jika tombol "Hapus" ditekan
if (isset($_POST['hapus'])) {
    // Hapus item cucian terkait dari database
    $deleteItemCucianQuery = "DELETE FROM ItemCucian WHERE no_cucian = '$noCucian'";
    $resultDeleteItemCucian = mysqli_query($conn, $deleteItemCucianQuery);

    if ($resultDeleteItemCucian) {
        // Hapus permintaan cucian dari database
        $deletePermintaanCucianQuery = "DELETE FROM PermintaanCucian WHERE no_cucian = '$noCucian'";
        $resultDeletePermintaanCucian = mysqli_query($conn, $deletePermintaanCucianQuery);

        if ($resultDeletePermintaanCucian) {
            // Permintaan cucian dan item cucian terkait berhasil dihapus, alihkan ke halaman permintaan cucian pelanggan
            header("Location: permintaan_cucian_pelanggan.php");
            exit;
        } else {
            // Kesalahan saat menghapus permintaan cucian
            echo "Terjadi kesalahan saat menghapus permintaan cucian.";
        }
    } else {
        // Kesalahan saat menghapus item cucian terkait
        echo "Terjadi kesalahan saat menghapus item cucian terkait.";
    }
}

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <h2>Hapus Permintaan Cucian</h2>
            <p>Apakah Anda yakin ingin menghapus permintaan cucian ini?</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Cucian</th>
                        <th>Jenis Prioritas</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Selesai</th>
                        <th>Status Cucian</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['no_cucian']; ?></td>
                        <td><?php echo $data['jenis_prioritas']; ?></td>
                        <td><?php echo $data['tgl_masuk']; ?></td>
                        <td><?php echo $data['tgl_selesai']; ?></td>
                        <td><?php echo $data['jenis_status_cucian']; ?></td>
                    </tr>
                </tbody>
            </table>
            <form method="POST">
                <input type="submit" name="hapus" value="Hapus" class="btn btn-danger">
                <a href="permintaan_cucian_pelanggan.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
