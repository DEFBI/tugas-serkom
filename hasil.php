<?php
include 'server.php';
session_start();

// Konfigurasi pagination
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total data
$totalQuery = "SELECT COUNT(*) as total FROM data_beasiswa";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];

// Hitung total halaman
$totalPages = ceil($totalData / $limit);

// Query untuk mengambil data dengan limit dan offset
$query = "SELECT * FROM data_beasiswa LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Beasiswa</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <div class="menu-bar">
            <div class="menu-item">
                <a href="beasiswa.php">Pilih Beasiswa</a>
            </div>
            <div class="menu-item">
                <a href="index.php">Daftar</a>
            </div>
            <div class="menu-item">
                <a href="hasil.php">Hasil</a>
            </div>
        </div>

        <div class="black-bar"></div>

        <div class="card-title-2">Hasil Beasiswa</div>
        <div class="tabel-container">
        <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message">
                        <p style="color: green;"><?php echo $_SESSION['success']; ?></p>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Semester</th>
                    <th>IPK</th>
                    <th>Pilihan Beasiswa</th>
                    <th>Nama Berkas</th>
                    <th>Status Ajuan</th>
                </tr>
                <?php
                $no = $offset + 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['nomor_hp'] . "</td>";
                    echo "<td>" . $row['semester'] . "</td>";
                    echo "<td>" . $row['ipk'] . "</td>";
                    echo "<td>" . $row['beasiswa'] . "</td>";
                    echo "<td>";
                    $fileLink = 'berkas/' . $row['berkas'];
                    echo '<a href="' . $fileLink . '" class="berkas-btn" target="_blank">' . $row['berkas'] . '</a>';
                    echo "</td>";
                    echo "<td>" . ($row['status_ajuan'] ? $row['status_ajuan'] : 'Belum Diverifikasi') . "</td>";
                    echo "</tr>";
                    $no++;
                }
                ?>
            </table>
        </div>

        <!-- Navigasi Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="hasil.php?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="hasil.php?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="hasil.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
