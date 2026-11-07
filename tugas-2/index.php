<?php

$host = "localhost";
$user = "root";
$pass = "1234";      
$db   = "db_tugas2";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id = 0;
$nim = "";
$nama = "";
$jurusan = "";
$is_edit = false;

if (isset($_POST['save'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);

    if (isset($_POST['id']) && $_POST['id'] != 0) {
        $id = $_POST['id'];
        $query = "UPDATE mahasiswa SET nim='$nim', nama='$nama', jurusan='$jurusan' WHERE id=$id";
    } else {
        $query = "INSERT INTO mahasiswa (nim, nama, jurusan) VALUES ('$nim', '$nama', '$jurusan')";
    }
    
    mysqli_query($conn, $query);
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id=$id");
    header("Location: index.php");
    exit();
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
    if ($row = mysqli_fetch_assoc($result)) {
        $nim = $row['nim'];
        $nama = $row['nama'];
        $jurusan = $row['jurusan'];
        $is_edit = true;
    }
}

$result = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas 2 - Aplikasi CRUD Mahasiswa</title>
</head>
<body>

    <h2>Data Mahasiswa</h2>

    <!-- Form Insert / Edit -->
    <form action="index.php" method="POST">
        <input type="hidden" name="id" value="<?= $id; ?>">
        
        <p>
            <label>NIM:</label><br>
            <input type="text" name="nim" value="<?= $nim; ?>" required>
        </p>

        <p>
            <label>Nama:</label><br>
            <input type="text" name="nama" value="<?= $nama; ?>" required>
        </p>

        <p>
            <label>Jurusan:</label><br>
            <input type="text" name="jurusan" value="<?= $jurusan; ?>" required>
        </p>

        <button type="submit" name="save">
            <?= $is_edit ? "Update Data" : "Tambah Data"; ?>
        </button>
        <?php if ($is_edit): ?>
            <a href="index.php">Batal</a>
        <?php endif; ?>
    </form>

    <hr>

    <!-- Tabel Read Data -->
    <h3>Daftar Mahasiswa</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nim']); ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= htmlspecialchars($row['jurusan']); ?></td>
                <td>
                    <a href="index.php?edit=<?= $row['id']; ?>">Edit</a> | 
                    <a href="index.php?delete=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($result) == 0): ?>
            <tr>
                <td colspan="5" align="center">Belum ada data.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
