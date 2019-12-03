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
  // form telah disubmit, cek apakah berasal dari hapus_siswa.php 
  // atau update data dari form_edit.php

  if ($_POST["submit"] == "Edit") {
    //nilai form berasal dari halaman hapus_siswa.php

    // ambil nilai nis 
    $nis = htmlentities(strip_tags(trim($_POST["nis"])));
    // filter data
    $nis = mysqli_real_escape_string($link, $nis);

    // ambil semua data dari database untuk menjadi nilai awal form
    $query = "SELECT * FROM daftarsiswa WHERE nis='$nis'";
    $result = mysqli_query($link, $query);

    if (!$result) {
      die("Query Error: " . mysqli_errno($link) .
        " - " . mysqli_error($link));
    }

    // tidak perlu pakai perulangan while, karena hanya ada 1 record
    $data = mysqli_fetch_assoc($result);

    $nama = $data["nama"];
    $kelas = $data["kelas"];
    $asal = $data["asal"];
    $ekstrakurikuler = $data["ekstrakurikuler"];

    // bebaskan memory 
    mysqli_free_result($result);
  } else if ($_POST["submit"] == "Update Data") {

    // nilai form berasal dari halaman form_edit.php    
    // ambil nilai form 
    $nis = htmlentities(strip_tags(trim($_POST["nis"])));
    $nama = htmlentities(strip_tags(trim($_POST["nama"])));
    $kelas = htmlentities(strip_tags(trim($_POST["kelas"])));
    $asal = htmlentities(strip_tags(trim($_POST["asal"])));
    $ekstrakurikuler = htmlentities(strip_tags(trim($_POST["ekstrakurikuler"])));
  }

  // proses validasi form
  // siapkan variabel untuk menampung pesan error
  $pesan_error = "";

  // cek apakah "NIS" sudah diisi atau tidak
  if (empty($nis)) {
    $pesan_error .= "NIS belum diisi <br>";
  }
  // NIS harus angka dengan 8 digit
  elseif (!preg_match("/^[0-9]{8}$/", $nis)) {
    $pesan_error .= "nis harus berupa 8 digit angka <br>";
  }

  // cek apakah "nama" sudah diisi atau tidak
  if (empty($nama)) {
    $pesan_error .= "Nama belum diisi <br>";
  }

  // cek apakah "kelas lahir" sudah diisi atau tidak
  if (empty($kelas)) {
    $pesan_error .= "Kelas belum diisi <br>";
  }

  // cek apakah "asal" sudah diisi atau tidak
  if (empty($asal)) {
    $pesan_error .= "Asal belum diisi <br>";
  }

  // jika tidak ada error, input ke database
  if (($pesan_error === "") and ($_POST["submit"] == "Update Data")) {

    // buka koneksi dengan MySQL
    include("koneksi.php");

    // filter semua data
    $nis = mysqli_real_escape_string($link, $nis);
    $nama = mysqli_real_escape_string($link, $nama);
    $kelas = mysqli_real_escape_string($link, $kelas);
    $asal = mysqli_real_escape_string($link, $asal);
    $ekstrakurikuler = mysqli_real_escape_string($link, $ekstrakurikuler);

    //buat dan jalankan query UPDATE ke daftarsiswa
    $query  = "UPDATE daftarsiswa ";
    $query .= "SET nama = '$nama', kelas = '$kelas', ";
    $query .= "asal = '$asal', ekstrakurikuler='$ekstrakurikuler' ";
    $query .= "WHERE nis = '$nis'";

    $result = mysqli_query($link, $query);

    //buat dan jalankan query UPDATE ke mastersiswa
    $query  = "UPDATE mastersiswa ";
    $query .= "SET nama = '$nama', kelas = '$kelas', ";
    $query .= "asal = '$asal' ";
    $query .= "WHERE nis = '$nis'";

    $result = mysqli_query($link, $query);

    //periksa hasil query
    if ($result) {
      // INSERT berhasil, redirect ke hapus_siswa.php + pesan
      $pesan = "Siswa dengan nama = \"<b>$nama</b>\" sudah berhasil di update";
      $pesan = urlencode($pesan);
      header("Location: hapus_siswa.php?pesan={$pesan}");
    } else {
      die("Query gagal dijalankan: " . mysqli_errno($link) .
        " - " . mysqli_error($link));
    }
  }
} else {
  // form diakses secara langsung! 
  // redirect ke hapus_siswa.php
  header("Location: hapus_siswa.php");
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
    <h2>Edit Data Siswa</h2>
    <?php
    // tampilkan error jika ada
    if ($pesan_error !== "") {
      echo "<div class=\"error\">$pesan_error</div>";
    }
    ?>
    <form id="form_siswa" action="form_edit_siswa.php" method="post">
      <fieldset>
        <legend>Edit Siswa</legend>
        <p>
          <label for="nis">NIS : </label>
          <input type="text" name="nis" id="nis" value="<?php echo $nis ?>" readonly>
          (tidak bisa diubah di menu edit)
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


        <p>
          <label for="ekstrakurikuler">Ekstrakurikuler : </label>
          <input type="hidden" name="ekstrakurikuler" id="ekstrakurikuler" value="<?php echo $ekstrakurikuler ?>" readonly>

          <select name="ekstrakurikuler" id="ekstrakurikuler">
            <?php
            $query = "SELECT * FROM masterekskul";
            $result = mysqli_query($link, $query);
            while ($data = mysqli_fetch_assoc($result)) {
              echo "<option value='" . $data['ekstrakurikuler'] . "'>" . $data['ekstrakurikuler'] . "</option>";
            }
            ?>
          </select>
        </p>
      </fieldset>
      <br>
      <p>
        <input type="submit" name="submit" value="Update Data">
      </p>
    </form>

  </div>

  <script>
    // agar value dropdown sama dengan value label (karena saya tidak tau merubah value dropdown berdasarkan halaman form edit, sedangkan label bisa)
    var ganti = document.getElementById('ekstrakurikuler');

    var love = ganti.value;
    var drop = document.querySelector('select');
    drop.value = love;
  </script>
</body>

</html>
<?php
// tutup koneksi dengan database mysql
mysqli_close($link);
?>