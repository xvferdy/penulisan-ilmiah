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
  $nis = htmlentities(strip_tags(trim($_POST["nis"])));
  $nama = htmlentities(strip_tags(trim($_POST["nama"])));
  $kelas = htmlentities(strip_tags(trim($_POST["kelas"])));
  $asal = htmlentities(strip_tags(trim($_POST["asal"])));

  // siapkan variabel untuk menampung pesan error
  $pesan_error = "";

  // cek apakah "nis" sudah diisi atau tidak
  if (empty($nis)) {
    $pesan_error .= "NIS belum diisi <br>";
  }
  // NIS harus angka dengan 8 digit
  elseif (!preg_match("/^[0-9]{8}$/", $nis)) {
    $pesan_error .= "NIS harus berupa 8 digit angka <br>";
  }

  // cek ke database, apakah sudah ada nomor NIS yang sama    
  // filter data $nis
  $nis = mysqli_real_escape_string($link, $nis);
  $query = "SELECT * FROM mastersiswa WHERE nis='$nis'";
  $hasil_query = mysqli_query($link, $query);

  // cek jumlah record (baris), jika ada, $nis tidak bisa diproses
  $jumlah_data = mysqli_num_rows($hasil_query);
  if ($jumlah_data >= 1) {
    $pesan_error .= "NIS yang sama sudah digunakan <br>";
  }

  // cek apakah "nama" sudah diisi atau tidak
  if (empty($nama)) {
    $pesan_error .= "Nama belum diisi <br>";
  }

  // cek apakah "kelas" sudah diisi atau tidak
  if (empty($kelas)) {
    $pesan_error .= "Kelas belum diisi <br>";
  }

  // cek apakah "asal" sudah diisi atau tidak
  if (empty($asal)) {
    $pesan_error .= "Asal belum diisi <br>";
  }



  // jika tidak ada error, input ke database
  if ($pesan_error === "") {

    // filter semua data
    $nis = mysqli_real_escape_string($link, $nis);
    $nama = mysqli_real_escape_string($link, $nama);
    $kelas = mysqli_real_escape_string($link, $kelas);
    $asal = mysqli_real_escape_string($link, $asal);


    //buat dan jalankan query INSERT
    $query = "INSERT INTO mastersiswa VALUES ";
    $query .= "('$nis', '$nama', '$kelas', ";
    $query .= "'$asal')";

    $result = mysqli_query($link, $query);

    //periksa hasil query
    if ($result) {
      // INSERT berhasil, redirect ke tampil_siswa.php + pesan
      $pesan = "siswa dengan nama = \"<b>$nama</b>\" sudah berhasil di tambah";
      $pesan = urlencode($pesan);
      header("Location: tampil_siswa.php?pesan={$pesan}");
    } else {
      die("Query gagal dijalankan: " . mysqli_errno($link) .
        " - " . mysqli_error($link));
    }
  }
} else {
  // form belum disubmit atau halaman ini tampil untuk pertama kali 
  // berikan nilai awal untuk semua isian form
  $pesan_error = "";
  $nis = "";
  $nama = "";
  $kelas = "";
  $asal = "";
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
        <label for="nis">Nama : </label>
        <input type="text" name="nama" id="nama" placeholder="search...">
        <input type="submit" name="submit" value="Search">
      </p>
    </form>
    <h2>TAMBAH DATA SISWA</h2>
    <?php
                      // tampilkan error jika ada
                      if ($pesan_error !== "") {
                        echo "<div class=\"error\">$pesan_error</div>";
                      }
                      ?>
    <form id="form_siswa" action="tambah_siswa.php" method="post">
      <fieldset>
        <legend>Siswa Baru</legend>
        <p>
          <label for="nis">NIS : </label>
          <input type="text" name="nis" id="nis" value="<?php echo $nis ?>"> (8 digit angka)
        </p>
        <p>
          <label for="nama">Nama : </label>
          <input type="text" name="nama" id="nama" value="<?php echo $nama ?>">
        </p>
        <p>
          <label for="kelas">Kelas : </label>
          <input type="text" name="kelas" id="kelas" value="<?php echo $kelas ?>">
        </p>
        <p>
          <label for="asal">Asal : </label>
          <input type="text" name="asal" id="asal" value="<?php echo $asal ?>">
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