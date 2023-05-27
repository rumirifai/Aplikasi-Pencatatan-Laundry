<?php
include "config.php";

if (isset($_POST['id_prioritas'])) {
    $idPrioritas = $_POST['id_prioritas'];

    // Mengambil durasi dari tabel Prioritas berdasarkan id_prioritas
    $durasiQuery = "SELECT durasi FROM Prioritas WHERE id_prioritas = '$idPrioritas'";
    $durasiResult = mysqli_query($conn, $durasiQuery);
    $durasi = mysqli_fetch_assoc($durasiResult)['durasi'];

    // Mengembalikan durasi dalam bentuk JSON
    echo json_encode(array('durasi' => $durasi));
}

mysqli_close($conn);
?>
