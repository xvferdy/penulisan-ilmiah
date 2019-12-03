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
  $daftar = htmlentities(strip_tags(trim($_GET["daftar"])));

  // filter untuk $nama untuk mencegah sql injection
  $daftar = mysqli_real_escape_string($link, $daftar);

  // buat query pencarian
  $query  = "SELECT * FROM daftarsiswa WHERE nama LIKE '%$daftar%' ";
  $query .= "ORDER BY nama ASC";

  // buat pesan
  $pesan = "Hasil pencarian untuk daftar <b>\"$daftar\" </b>:";
} else {
  // bukan dari form pencairan
  // siapkan query untuk menampilkan seluruh data dari tabel daftarsiswa
  $query = "SELECT * FROM daftarsiswa ORDER BY kelas ASC";
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
    <form id="search" action="informasi.php" method="get">
      <p>
        <label for="daftar">Nama : </label>
        <input type="text" name="daftar" id="daftar" placeholder="search...">
        <input type="submit" name="submit" value="Search">
      </p>
    </form>
    <h2>SISWA YANG MENGIKUTI EKSKUL</h2>
    <?php
    // tampilkan pesan jika ada
    if (isset($pesan)) {
      echo "<div class=\"pesan\">$pesan</div>";
    }

    ?>

    <p>Ekskul:
      <select name="namaSiswa" id="namaSiswa">
      </select>
    </p>
    <div id="hasil"></div>
    <script>
      var namaSiswaNode = document.getElementById("namaSiswa");
      var hasilNode = document.getElementById("hasil");

      function generateSiswa() {
        var request = new XMLHttpRequest();
        request.open("GET", "nama_siswa.php", false);
        request.send();
        namaSiswaNode.innerHTML = request.responseText;
      }

      function tabelSiswa() {
        var nama = namaSiswaNode.value;
        var request = new XMLHttpRequest();
        request.open("GET", "tabel_siswa.php?n=" + nama, false);
        request.send();
        hasilNode.innerHTML = request.responseText;
      }

      generateSiswa();
      //dropdown (jalankan fungsi generate siswa saat page di load)
      tabelSiswa();
      namaSiswaNode.addEventListener("change", tabelSiswa);
    </script>

    <div id="footer">
      Copyright © <?php echo date("Y"); ?> YPJ Kuala-Kencana
    </div>
  </div>
</body>

</html>