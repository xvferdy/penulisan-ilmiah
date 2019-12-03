<?php
// buat koneksi dengan database mysql
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$link = mysqli_connect($dbhost, $dbuser, $dbpass);

//periksa koneksi, tampilkan pesan kesalahan jika gagal
if (!$link) {
  die("Koneksi dengan database gagal: " . mysqli_connect_errno() .
    " - " . mysqli_connect_error());
}

//buat database ypjkk jika belum ada
$query = "CREATE DATABASE IF NOT EXISTS ypjkk";
$result = mysqli_query($link, $query);

if (!$result) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Database <b>'ypjkk'</b> berhasil dibuat... <br>";
}

//pilih database ypjkk
$result = mysqli_select_db($link, "ypjkk");

if (!$result) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Database <b>'ypjkk'</b> berhasil dipilih... <br>";
}

// cek apakah tabel mastersiswa sudah ada. jika ada, hapus tabel
$query = "DROP TABLE IF EXISTS mastersiswa";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'mastersiswa'</b> berhasil dihapus... <br>";
}

// buat query untuk CREATE tabel mastersiswa
$query  = "CREATE TABLE mastersiswa (nis CHAR(8), nama VARCHAR(100), ";
$query .= "kelas CHAR(10), asal VARCHAR(50), ";
$query .= "PRIMARY KEY (nis))";

$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'mastersiswa'</b> berhasil dibuat... <br>";
}

// buat query untuk INSERT data ke tabel mastersiswa
$query  = "INSERT INTO mastersiswa VALUES ";
$query .= "('14405021', 'Sadrak Wamafma',     '4', 'Fakfak'),";
$query .= "('17081034', 'Benediktus Sidebang','4', 'Batak'),";
$query .= "('11013021', 'Anti',               '4', 'Bugis'),";
$query .= "('15612086', 'Reymond Timbuleng',  '4', 'Menado'),";
$query .= "('13033017', 'Pamela Rumpeday',    '4', 'Jayapura'),";
$query .= "('11044017', 'Dewi Lempoy',        '4', 'Menado'),";
$query .= "('15032012', 'Berlianto Ferdynand Pongbubun', '4', 'Toraja'),";
$query .= "('13089018', 'Adit Jayakusuma',    '4', 'Semarang'),";
$query .= "('13093419', 'Ayu Rahayu',         '4', 'Jakarta'),";
$query .= "('13093619', 'Hubert Kiwak',       '4', 'Biak'),";
$query .= "('13893518', 'Boy Dimara',         '4', 'Manokwari'),";
$query .= "('13663716', 'Dian',               '4', 'Palu'),";
$query .= "('13043414', 'Fauzi Fauzan',       '4', 'Bandung'),";
$query .= "('13493444', 'Patricia Magal',     '4', 'Merauke'),";
$query .= "('13056415', 'Hengki Kayame',      '4', 'Paniai'),";
$query .= "('13098417', 'Petrus Takimai',     '4', 'Asmat'),";
$query .= "('13093411', 'Bayu Skak',          '4', 'Semarang'),";
$query .= "('13063266', 'Kencana Masnandifu', '4', 'Biak') ";

$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'mastersiswa'</b> berhasil diisi... <br>";
}

// cek apakah tabel masterekskul sudah ada. jika ada, hapus tabel
$query = "DROP TABLE IF EXISTS masterekskul";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'masterekskul'</b> berhasil dihapus... <br>";
}

// buat query untuk CREATE tabel masterekskul
$query  = "CREATE TABLE masterekskul (kode CHAR(8),ekstrakurikuler VARCHAR(100), ";
$query .= "guru VARCHAR(100))";

$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'masterekskul'</b> berhasil dibuat... <br>";
}

// buat query untuk INSERT data ke tabel masterekskul
$query  = "INSERT INTO masterekskul VALUES ";
$query .= "(1,'Sepak Bola', 'Lodi'),";
$query .= "(2,'Bulutangkis',' Lodi'),";
$query .= "(3,'Renang', 'Gelis'),";
$query .= "(4,'Basket', 'Gelis'),";
$query .= "(5,'Tata Boga', 'Andreas'),";
$query .= "(6,'Bahasa Asing', 'Imam'),";
$query .= "(7,'Klub Matematika', 'Sri'),";
$query .= "(8,'Klub Sains', 'Pontoh'),";
$query .= "(9,'Komputer', 'Hendrikus'),";
$query .= "(10,'Catur', 'Pontoh')";

$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'masterekskul'</b> berhasil diisi... <br>";
}

// cek apakah tabel daftarsiswa sudah ada. jika ada, hapus tabel
$query = "DROP TABLE IF EXISTS daftarsiswa";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'daftarsiswa'</b> berhasil dihapus... <br>";
}

// buat query untuk CREATE tabel daftarsiswa
$query  = "CREATE TABLE daftarsiswa (nis CHAR(8), nama VARCHAR(100), ";
$query .= "kelas CHAR(10), asal VARCHAR(50), ";
$query .= "ekstrakurikuler VARCHAR(100))";

$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'daftarsiswa'</b> berhasil dibuat... <br>";
}

// cek apakah tabel admin sudah ada. jika ada, hapus tabel
$query = "DROP TABLE IF EXISTS admin";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'admin'</b> berhasil dihapus... <br>";
}

// buat query untuk CREATE tabel admin
$query  = "CREATE TABLE admin (username VARCHAR(50), password CHAR(40))";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'admin'</b> berhasil dibuat... <br>";
}

// buat username dan password untuk admin
$username = "alan";
$password = sha1("zz");

// buat query untuk INSERT data ke tabel admin
$query  = "INSERT INTO admin VALUES ('$username','$password')";

$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
  die("Query Error: " . mysqli_errno($link) .
    " - " . mysqli_error($link));
} else {
  echo "Tabel <b>'admin'</b> berhasil diisi... <br>";
}

// tutup koneksi dengan database mysql
mysqli_close($link);
