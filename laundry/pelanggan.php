<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Mendapatkan nama pelanggan dari session atau sumber lainnya
$nama = $_SESSION['username'];

<<<<<<< HEAD
// Query untuk mendapatkan daftar pelanggan dari tabel Pelanggan
$query = "SELECT * FROM Pelanggan";
=======
// Mendapatkan kata kunci pencarian
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// Mendapatkan kolom pengurutan
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'id_pelanggan';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Mendapatkan halaman saat ini
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Batasan jumlah baris yang ditampilkan per halaman
$rowsPerPage = isset($_GET['rows']) ? $_GET['rows'] : 10;

// Menghitung offset untuk query
$offset = ($page - 1) * $rowsPerPage;

// Query untuk mendapatkan jumlah total baris
$totalRowsQuery = "SELECT COUNT(*) AS total_rows FROM Pelanggan WHERE 
                      id_pelanggan LIKE '%$searchKeyword%' OR
                      nama LIKE '%$searchKeyword%' OR
                      alamat LIKE '%$searchKeyword%' OR
                      telepon LIKE '%$searchKeyword%'";
$totalRowsResult = mysqli_query($conn, $totalRowsQuery);
$totalRowsData = mysqli_fetch_assoc($totalRowsResult);
$totalRows = $totalRowsData['total_rows'];

// Menghitung jumlah halaman
$totalPages = ceil($totalRows / $rowsPerPage);

// Query untuk mendapatkan daftar pelanggan dari tabel Pelanggan dengan filter pencarian dan pengurutan
$query = "SELECT * FROM Pelanggan WHERE 
              id_pelanggan LIKE '%$searchKeyword%' OR
              nama LIKE '%$searchKeyword%' OR
              alamat LIKE '%$searchKeyword%' OR
              telepon LIKE '%$searchKeyword%'
          ORDER BY $sortBy $sortOrder
          LIMIT $offset, $rowsPerPage";
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<link rel="stylesheet" href="assets/css/styles.css">
<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Daftar Pelanggan</h2>
          <div class="text-right">
<<<<<<< HEAD
            
=======
            <form action="" method="GET" class="form-inline">
              <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari" name="search" value="<?php echo $searchKeyword; ?>">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">Cari</button>
                </div>
              </div>
            </form>
            <a href="tambah_pelanggan.php" class="btn btn-primary">Tambah Pelanggan</a>
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No.</th>
<<<<<<< HEAD
                <th>ID Pelanggan</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
=======
                <th>
                  <a href="?sort=id_pelanggan&order=<?php echo $sortOrder == 'asc' ? 'desc' : 'asc'; ?>">ID <?php echo $sortBy == 'id_pelanggan' ? ($sortOrder == 'asc' ? '&#9650;' : '&#9660;') : ''; ?></a>
                </th>
                <th>
                  <a href="?sort=nama&order=<?php echo $sortOrder == 'asc' ? 'desc' : 'asc'; ?>">Nama <?php echo $sortBy == 'nama' ? ($sortOrder == 'asc' ? '&#9650;' : '&#9660;') : ''; ?></a>
                </th>
                <th>
                  <a href="?sort=alamat&order=<?php echo $sortOrder == 'asc' ? 'desc' : 'asc'; ?>">Alamat <?php echo $sortBy == 'alamat' ? ($sortOrder == 'asc' ? '&#9650;' : '&#9660;') : ''; ?></a>
                </th>
                <th>
                  <a href="?sort=telepon&order=<?php echo $sortOrder == 'asc' ? 'desc' : 'asc'; ?>">Telepon <?php echo $sortBy == 'telepon' ? ($sortOrder == 'asc' ? '&#9650;' : '&#9660;') : ''; ?></a>
                </th>
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
<<<<<<< HEAD
              $num = 1;
=======
              $num = ($page - 1) * $rowsPerPage + 1;
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
              while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                  <td><?php echo $num++; ?></td>
                  <td><?php echo $row['id_pelanggan']; ?></td>
                  <td><?php echo $row['nama']; ?></td>
                  <td><?php echo $row['alamat']; ?></td>
                  <td><?php echo $row['telepon']; ?></td>
                  <td>
                    <a href="edit_pelanggan.php?id=<?php echo $row['id_pelanggan']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="hapus_pelanggan.php?id=<?php echo $row['id_pelanggan']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
<<<<<<< HEAD
=======
          <div class="text-center">
            <ul class="pagination">
              <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sortBy; ?>&order=<?php echo $sortOrder; ?>&search=<?php echo $searchKeyword; ?>&rows=<?php echo $rowsPerPage; ?>">Previous</a></li>
              <?php endif; ?>
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&sort=<?php echo $sortBy; ?>&order=<?php echo $sortOrder; ?>&search=<?php echo $searchKeyword; ?>&rows=<?php echo $rowsPerPage; ?>"><?php echo $i; ?></a></li>
              <?php endfor; ?>
              <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sortBy; ?>&order=<?php echo $sortOrder; ?>&search=<?php echo $searchKeyword; ?>&rows=<?php echo $rowsPerPage; ?>">Next</a></li>
              <?php endif; ?>
            </ul>
            <div class="form-inline">
              <label for="rowsPerPage">Tampilkan baris:</label>
              <select class="form-control ml-2" id="rowsPerPage" onchange="changeRowsPerPage(this.value)">
                <option value="10" <?php echo $rowsPerPage == 10 ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo $rowsPerPage == 25 ? 'selected' : ''; ?>>25</option>
                <option value="50" <?php echo $rowsPerPage == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $rowsPerPage == 100 ? 'selected' : ''; ?>>100</option>
              </select>
            </div>
          </div>
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
<<<<<<< HEAD
=======

<script>
  // Fungsi untuk mengubah jumlah baris per halaman
  function changeRowsPerPage(rows) {
    var url = new URL(window.location.href);
    url.searchParams.set('rows', rows);
    window.location.href = url.toString();
  }
</script>
>>>>>>> 364a50b9e8a279b5cebdb5817e64cb6bb6c6448a
