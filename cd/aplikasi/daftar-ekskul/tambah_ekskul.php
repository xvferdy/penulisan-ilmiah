<?php
// periksa apakah user sudah login, cek kehadiran session name 
// jika tidak ada, redirect ke login.php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
}

// buka koneksi dengan MySQL
include("koneksi.php");

// cek apakah form telah di submit
if (isset($_POST["submit"])) {
  // form telah disubmit, proses data

  // ambil semua nilai form
  $kode = htmlentities(strip_tags(trim($_POST["kode"])));
  $ekstrakurikuler = htmlentities(strip_tags(trim($_POST["ekstrakurikuler"])));
  $guru = htmlentities(strip_tags(trim($_POST["guru"])));

  // siapkan variabel untuk menampung pesan error
  $pesan_error = "";

  // cek apakah "kode" sudah diisi atau tidak
  if (empty($kode)) {
    $pesan_error .= "Kode belum diisi <br>";
  }
  if (empty($ekstrakurikuler)) {
    $pesan_error .= "Ekstrakurikuler belum diisi <br>";
  }

  if (empty($guru)) {
    $pesan_error .= "Guru belum diisi <br>";
  }
  // cek ke database, apakah sudah ada nomor kode yang sama    
  // filter data $kode
  $kode = mysqli_real_escape_string($link, $kode);
  $query = "SELECT * FROM masterekskul WHERE kode='$kode'";
  $hasil_query = mysqli_query($link, $query);

  // cek jumlah record (baris), jika ada, $nis tidak bisa diproses
  $jumlah_data = mysqli_num_rows($hasil_query);
  if ($jumlah_data >= 1) {
    $pesan_error .= "Kode yang sama sudah digunakan <br>";
  }

  // cek ke database, apakah sudah ada ekstrakurikuler yang sama    
  // filter data $ekstrakurikuler
  $kode = mysqli_real_escape_string($link, $kode);
  $query = "SELECT * FROM masterekskul WHERE ekstrakurikuler='$ekstrakurikuler'";
  $hasil_query = mysqli_query($link, $query);

  // cek jumlah record (baris), jika ada, $nis tidak bisa diproses
  $jumlah_data = mysqli_num_rows($hasil_query);
  if ($jumlah_data >= 1) {
    $pesan_error .= "Ekstrakurikuler yang sama sudah digunakan <br>";
  }
  // cek apakah "ekstrakurikuler" sudah diisi atau tidak

  // jika tidak ada error, input ke database
  if ($pesan_error === "") {

    // filter semua data
    $kode = mysqli_real_escape_string($link, $kode);
    $ekstrakurikuler = mysqli_real_escape_string($link, $ekstrakurikuler);
    $guru = mysqli_real_escape_string($link, $guru);

    //buat dan jalankan query INSERT
    $query = "INSERT INTO masterekskul VALUES ";
    $query .= "('$kode','$ekstrakurikuler','$guru')";

    $result = mysqli_query($link, $query);

    //periksa hasil query
    if ($result) {
      // INSERT berhasil, redirect ke tampil_siswa.php + pesan
      $pesan = "siswa dengan nama = \"<b>$kode</b>\" sudah berhasil di tambah";
      $pesan = urlencode($pesan);
      header("Location: tampil_ekskul.php?pesan={$pesan}");
    } else {
      die("Query gagal dijalankan: " . mysqli_errno($link) .
        " - " . mysqli_error($link));
    }
  }
} else {
  // form belum disubmit atau halaman ini tampil untuk pertama kali 
  // berikan nilai awal untuk semua isian form
  $pesan_error = "";
  $kode = "";
  $ekstrakurikuler = "";
  $guru = "";
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>YPJ School | Selamat datang</title>
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
    <h2>Tambah Ekstrakurikuler</h2>
    <?php
                      // tampilkan error jika ada
                      if ($pesan_error !== "") {
                        echo "<div class=\"error\">$pesan_error</div>";
                      }
                      ?>
    <form id="form_siswa" action="tambah_ekskul.php" method="post">
      <fieldset>
        <legend> Ekstrakurikuler</legend>
        <p>
          <label for="kode">KODE : </label>
          <input type="text" name="kode" id="kode" value="<?php echo $kode ?>">
        </p>
        <p>
          <label for="ekstrakurikuler">Ekstrakurikuler : </label>
          <input type="text" name="ekstrakurikuler" id="ekstrakurikuler" value="<?php echo $ekstrakurikuler ?>">
        </p>
        <p>
          <label for="guru">Guru : </label>
          <input type="text" name="guru" id="guru" value="<?php echo $guru ?>">
        </p>

      </fieldset>
      <br>
      <p>
        <input type="submit" name="submit" value="Tambah Data">
      </p>
    </form>

    <div id="footer">
      Copyright © <?php echo date("Y"); ?> YPJ Kuala-Kencana
    </div>

  </div>

</body>

</html>
<?php
                                                                                // tutup koneksi dengan database mysql
                                                                                mysqli_close($link);
                                                                                ?>