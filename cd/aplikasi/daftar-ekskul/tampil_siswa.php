<?php
// periksa apakah user sudah login, cek kehadiran session name 
// jika tidak ada, redirect ke login.php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
}

// buka koneksi dengan MySQL
include("koneksi.php");

// ambil pesan jika ada  
if (isset($_GET["pesan"])) {
  $pesan = $_GET["pesan"];
}

// cek apakah form telah di submit
// berasal dari form pencairan, siapkan query 
if (isset($_GET["submit"])) {

  // ambil nilai nama
  $nama = htmlentities(strip_tags(trim($_GET["nama"])));

  // filter untuk $nama untuk mencegah sql injection
  $nama = mysqli_real_escape_string($link, $nama);

  // buat query pencarian
  $query  = "SELECT * FROM mastersiswa WHERE nama LIKE '%$nama%' ";
  $query .= "ORDER BY nama ASC";

  // buat pesan
  $pesan = "Hasil pencarian untuk nama <b>\"$nama\" </b>:";
} else {
  // bukan dari form pencairan
  // siapkan query untuk menampilkan seluruh data dari tabel mastersiswa
  $query = "SELECT * FROM mastersiswa ORDER BY nama ASC";
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>YPJ School | Selamat datang</title>

  <style>
    ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-color: #E6E6FA;

    }

    li {
      float: left;
    }

    li a {
      display: block;
      color: black;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }

    li a:hover {
      background-color: #B0C4DE;
    }
  </style>

  <link href="style.css" rel="stylesheet">
  <link rel="icon" href="ypj2.png" type="image/png">
</head>

<body>
  <div class="container">
    <div id="header">
      <h1 id="logo">Sistem Informasi <span>Ekstrakurikuler</span></h1>
      <p id="tanggal"><?php echo date("d M Y"); ?></p>
    </div>
    <hr>
    <nav>
      <a href="tampil_siswa.php">MasterSiswa</a>
      <a href="tampil_ekskul.php">MasterEkskul</a>
      <a href="tambah_ekskul.php"><small>Tambah Ekskul</small></a>
      <a href="tambah_siswa.php"><small>Tambah Siswa</small></a>
      <ul>
        <li><a href="informasi.php">Informasi</a></li>
        <li><a href="daftar_siswa.php">Daftar</a></li>
        <li><a href="hapus_siswa.php">Edit</a></li>
        <li><a href="logout.php">Logout</a>
      </ul>
    </nav>
    <form id="search" action="tampil_siswa.php" method="get">
      <p>
        <label for="nama">Nama : </label>
        <input type="text" name="nama" id="nama" placeholder="search...">
        <input type="submit" name="submit" value="Search">
      </p>
    </form>
    <h2>DATA SISWA</h2>
    <?php
                      // tampilkan pesan jika ada
                      if (isset($pesan)) {
                        echo "<div class=\"pesan\">$pesan</div>";
                      }

                      ?>

    <table border="1">
      <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Asal</th>
      </tr>
      <?php
                      // jalankan query
                      $result = mysqli_query($link, $query);

                      if (!$result) {
                        die("Query Error: " . mysqli_errno($link) .
                          " - " . mysqli_error($link));
                      }

                      //buat perulangan untuk element tabel dari data mastersiswa
                      while ($data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>$data[nis]</td>";
                        echo "<td>$data[nama]</td>";
                        echo "<td>$data[kelas]</td>";
                        echo "<td>$data[asal]</td>";

                        echo "</tr>";
                      }

                      // bebaskan memory 
                      mysqli_free_result($result);

                      // tutup koneksi dengan database mysql
                      mysqli_close($link);
                      ?>
    </table>
    <div id="footer">
      Copyright © <?php echo date("Y"); ?> YPJ Kuala-Kencana
    </div>
  </div>
</body>

</html>