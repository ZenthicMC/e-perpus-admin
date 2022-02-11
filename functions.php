<?php 
error_reporting(E_ERROR | E_PARSE);
include 'db.php';
$conn = new mysqli($servername, $username, $password, $dbname);
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.all.min.js" integrity="sha256-nk6ExuG7ckFYKC1p3efjdB14TU+pnGwTra1Fnm6FvZ0=" crossorigin="anonymous"></script>';

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function pagination($dataPerHalaman,$tablename) {
    global $conn;

    // menghitung jumlah data
    $jumlahData = count(query("SELECT * FROM $tablename"));

    // menghitung jumlah halaman dgn cara membulatkan angka ke bilangan bulat keatas
    $jumlahHalaman = ceil($jumlahData / $dataPerHalaman);

    // menghitung halaman aktif
    $halamanAktif = (isset($_GET['page'])) ? $_GET['page'] : 1;
    if($halamanAktif <= 0) {
        $halamanAktif = 1;
        echo "<script>history.go(-1);</script>";
    }
    elseif($halamanAktif > $jumlahHalaman) {
        $halamanAktif = $jumlahHalaman;
        echo "<script>history.go(-1);</script>";
    }
   
    // menghitung data awal untuk setiap halaman
    $dataAwal = ($dataPerHalaman * $halamanAktif) - $dataPerHalaman;

    $result = $conn->query("SELECT * FROM $tablename LIMIT $dataAwal,$dataPerHalaman");

    return array($result,$jumlahData,$dataAwal,$dataPerHalaman,$halamanAktif,$jumlahHalaman);
}

function register($data,$pwd) {
    global $conn;

    $username = htmlspecialchars($data['username']);
    $email = htmlspecialchars($data['email']);
    $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users(username,email,pwd) VALUES('$username','$email','$hashed_pwd')";
    mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn);
}

function checkUsers($username, $email) {
    global $conn;
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $hasil = mysqli_query($conn, $sql);
    return mysqli_num_rows($hasil);
}

function login($data) {
    global $conn;

    $email = htmlspecialchars($data['email']);
    $pwd = htmlspecialchars($data['pwd']);
    // cek email
    $result = count(query("SELECT * FROM users WHERE email='$email'"));
    if($result > 0) {
        // cek password
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        $row = mysqli_fetch_assoc($result);
        if(password_verify($pwd,$row['pwd'])) {
             // sukses login
            session_start();
            $_SESSION['username'] = $row['username'];
            return 1;
        }
        else {
            // wrong password
            return 2;
        }
    }
    else {
        // no user
        return 3;
    }
}


function tambah($data, $type) {
    global $conn;

    if($type =='Users') {
        $username = htmlspecialchars($data['username']);
        $email = htmlspecialchars($data['email']);
        $hashed_pwd = password_hash($data['pwd'], PASSWORD_DEFAULT);
        $roles = htmlspecialchars($data['roles']);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO users(username,email,pwd,roles) VALUES('$username','$email','$hashed_pwd','$roles')";
        mysqli_query($conn, $sql);
        return mysqli_affected_rows($conn);
    } 
    elseif($type == 'Books') {
        date_default_timezone_set('Asia/Jakarta');

        $judul = htmlspecialchars($data['judul']);
        $penulis = htmlspecialchars($data['penulis']);
        $deskripsi = htmlspecialchars($data['deskripsi']);
        $tgl_input = date("d-m-Y H:i");

        $namaPDFBaru = uploadPDF();
        $namaCoverBaru = uploadCover();
        
        // upload ke database
        $sql = "INSERT INTO books(judul_buku,penulis_buku,deskripsi_buku,tgl_input,cover,file) VALUES('$judul','$penulis','$deskripsi','$tgl_input','$namaCoverBaru', '$namaPDFBaru')";
        mysqli_query($conn, $sql);

        // sukses di upload
        return 1;
    }
}

function edit($data,$type,$id) {
    global $conn;

    if($type =='Users') {
        $username = htmlspecialchars($data['username']);
        $email = htmlspecialchars($data['email']);
        $hashed_pwd = password_hash($data['pwd'], PASSWORD_DEFAULT);
        $roles = htmlspecialchars($data['roles']);
            
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE users SET username='$username', email='$email', pwd='$hashed_pwd', roles='$roles' WHERE id_user='$id'";
        mysqli_query($conn, $sql);
        return mysqli_affected_rows($conn);
    } 
    elseif($type == 'Books') {

        date_default_timezone_set('Asia/Jakarta');

        $judul = htmlspecialchars($data['judul']);
        $penulis = htmlspecialchars($data['penulis']);
        $deskripsi = htmlspecialchars($data['deskripsi']);
        $tgl_input = date("d-m-Y H:i");

        // mengambil nama lama
        $namaCoverLama = $data['coverLama'];
        $namaPdfLama = $data['pdfLama'];

        // cek apakah user mengupload cover, jika tidak gunakan cover lama
        if($_FILES['cover']['error'] === 4) {
            $namaCoverBaru = $namaCoverLama;
        }
        else {
            $namaCoverBaru = uploadCover();
        }

        // cek apakah user mengupload pdf, jika tidak gunakan cover pdf
        if($_FILES['pdf']['error'] === 4) {
            $namaPDFBaru = $namaPdfLama;
        }
        else {
            $namaPDFBaru = uploadPDF();
        }


        // upload ke database
        $sql = "UPDATE books SET judul_buku='$judul', penulis_buku='$penulis', deskripsi_buku='$deskripsi', tgl_input='$tgl_input', cover='$namaCoverBaru', file='$namaPDFBaru' WHERE id_buku='$id'";
        mysqli_query($conn, $sql);
        return true;
    }
}


function uploadCover() {
    // desklarasi variable cover
    $namaCover = $_FILES['cover']['name'];
    $ukuranCover = $_FILES['cover']['size'];
    $tmpCover = $_FILES['cover']['tmp_name'];

    // cek apakah cover & file di upload
    if($_FILES['cover']['error'] === 4) {
        // file tidak ditemukan
        echo "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Pilih cover terlebih dahulu!',
            })
        </script>
        ";
        return false;
    }

    //cek apakah file yang diupload adalah gambar atau pdf
    $ekstensiAllowed = ['jpg', 'png', 'jpeg'];
    $ekstensiCover = pathinfo($namaCover, PATHINFO_EXTENSION);
        
    if (!in_array($ekstensiCover, $ekstensiAllowed)) {
        // ekstensi tidak diizinkan
        echo "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Ekstensi covermu tidak valid!',
            })
        </script>
        ";
        return false;
    }

    //cek jika ukuran terlalu besar
    //ukuran = 10 mb = 10000000 bytes
    $maxUkuran = 10000000;
    if ($ukuranCover > $maxUkuran) {
        // ukuran melebihi batas maksimal
        echo "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Ukuran covermu terlalu besar!',
            })
        </script>
        ";
        return false;
    }

    //generate random nama Cover baru
    $namaCoverBaru = uniqid();
    $namaCoverBaru .= '.';
    $namaCoverBaru .= $ekstensiCover;

    // sukses di upload
    move_uploaded_file($tmpCover,'../assets/img/'. $namaCoverBaru);
    return $namaCoverBaru;
}
    

function uploadPDF() {
    // desklarasi variable file PDF
    $namaPDF = $_FILES['pdf']['name'];
    $ukuranPDF = $_FILES['pdf']['size'];
    $tmpPDF = $_FILES['pdf']['tmp_name'];

    // cek apakah cover & file di upload
    if($_FILES['pdf']['error'] === 4) {
        // file tidak ditemukan
        echo "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Pilih pdf terlebih dahulu!',
            })
        </script>
        ";
        return false;
    }

     //cek apakah file yang diupload adalah gambar atau pdf
    $ekstensiAllowed = ['pdf'];
    $ekstensiPDF = pathinfo($namaPDF, PATHINFO_EXTENSION);
        
    if (!in_array($ekstensiPDF, $ekstensiAllowed)) {
        echo "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Ekstensi pdfmu tidak valid!',
            })
        </script>
        ";
        // ekstensi tidak diizinkan
        return false;
    }

    //cek jika ukuran terlalu besar
    //ukuran = 10 mb = 10000000 bytes
    $maxUkuran = 10000000;
    if ($ukuranPDF > $maxUkuran) {
        // ukuran melebihi batas maksimal
        echo "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Ukuran pdfmu terlalu besar!',
            })
        </script>
        ";
        return false;
    }

    //generate random nama File PDF baru
    $namaPDFBaru = uniqid();
    $namaPDFBaru .= '.';
    $namaPDFBaru .= $ekstensiPDF; 

    // sukses di upload
    move_uploaded_file($tmpPDF,'../assets/books/'. $namaPDFBaru);
    return $namaPDFBaru;
}

function delete($type,$id) {
    global $conn;
    if ($type == 'Books') {
        $sql = "DELETE from books WHERE id_buku='$id'";
        mysqli_query($conn, $sql);
        header('location: ../admin/books.php');
    }
    elseif ($type == 'Users') {
        $sql = "DELETE from users WHERE id_user='$id'";
        mysqli_query($conn, $sql);
        header('location: ../admin/users.php');
    }
}

function countBook() {
    $jumlah = count(query("SELECT * FROM books"));
    return $jumlah;
}

function countAdmin() {
    $jumlah = count(query("SELECT * FROM users WHERE roles='admin'"));
    return $jumlah;
}

function countUser() {
    $jumlah = count(query("SELECT * FROM users"));
    return $jumlah;
}
?>