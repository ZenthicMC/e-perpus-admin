<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: ../auth/login.php");
        exit;
    }
    
    require '../functions.php';

    $type = $_GET['type'];
    $id = $_GET['id'];
    $allowed = ['Users', 'Books'];
    if (!in_array($type, $allowed)) {
        echo "<script>history.go(-1);</script>";
    }
    elseif($type=='Books') {
        $backFile = 'books.php';
        $tableName = 'books';
        $identifier = 'id_buku';
    }
    elseif($type=='Users') {
        $backFile = 'users.php';
        $tableName = 'users';
        $identifier = 'id_user';
    }

    $result = query("SELECT * FROM $tableName WHERE $identifier=$id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>E-Perpus | Edit <?= $type;?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">

</head>
<body id="page-top">
<div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="color: var(--bs-red);">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon"><i class="fas fa-book"></i></div>
                    <div class="sidebar-brand-text mx-3"><span style="font-size: 20px;">E-PERPUS</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <?php if ($type == 'Books') : ?>
                    <li class="nav-item"><a class="nav-link active" href="books.php"><i class="fa fa-book"></i><span>Books</span></a></li>
                    <?php else :?>
                    <li class="nav-item"><a class="nav-link" href="books.php"><i class="fa fa-book"></i><span>Books</span></a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="visitors.php"><i class="fa fa-eye"></i><span>Visitos</span></a></li>
                    <?php if ($type == 'Users') : ?>
                    <li class="nav-item"><a class="nav-link active" href="users.php"><i class="fa fa-users"></i><span>Users</span></a></li>
                    <?php else :?>
                    <li class="nav-item"><a class="nav-link" href="users.php"><i class="fa fa-users"></i><span>Users</span></a></li>
                    <?php endif; ?>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"></div>
                        </form>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small"><?= $_SESSION['username']; ?></span></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a><a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity log</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <div class="align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-2 text-gray-800">Edit Existing <?= $type;?></h1>
                        <p class="mb-4">Lengkapi Form Ini Untuk Mengedit <?= $type;?>!</p>
                    </div>

                    <div class="card shadow mb-4 w-50 border-left-primary">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Masukkan Data Baru</h6>
                        </div>
                        <div class="card-body ">
                            <?php if ($type == 'Books') : ?>
                            <?php foreach ($result as $data) : ?>
                            <form class="user" action="edit.php?type=<?= $type;?>&id=<?= $id ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-7 mb-3 mb-sm-0">
                                        <label>Judul Buku</label>
                                        <input name="judul" type="text" class="form-control form-control-user"
                                            style="border-radius: 5px;" value="<?= $data['judul_buku']; ?>" required>
                                        <input name="id" type="hidden" class="form-control form-control-user"
                                            style="border-radius: 5px;" value="<?= $data['id_buku']; ?>">
                                    </div>
                                    <div class="col-sm-5">
                                        <label>Penulis Buku</label>
                                        <input name="penulis" type="text" class="form-control form-control-user"
                                                style="border-radius: 5px;" value="<?= $data['penulis_buku']; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Deskripsi Buku</label>
                                    <input name="deskripsi" type="text" class="form-control form-control-user"
                                        style="border-radius: 5px;" value="<?= $data['deskripsi_buku']; ?>" required>
                                </div>
                                <div class="form-group row mt-2">
                                    <div class="col-sm-5">
                                        <label>Foto Cover</label><br>
                                        <input type="file" name="cover">
                                        <input type="hidden" name="coverLama" value="<?= $data['cover']; ?>">
                                    </div>
                                    <div class="col-sm-5">
                                        <label>File PDF</label><br>
                                        <input type="file" name="pdf">
                                        <input type="hidden" name="pdfLama" value="<?= $data['file']; ?>">
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <input class="btn btn-primary btn-icon-split" type="submit" name="submit" value="Edit <?= $type;?>" style="width:30%;height: 40px;">
                                </div>
                            </form>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ($type == 'Users') : ?>
                            <?php foreach ($result as $data) : ?>
                            <form class="user" action="edit.php?type=<?= $type;?>&id=<?= $id ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label>Username</label>
                                        <input name="username" type="text" class="form-control form-control-user"
                                            style="border-radius: 5px;" value="<?= $data['username']; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Email</label>
                                    <input name="email" type="text" class="form-control form-control-user"
                                        style="border-radius: 5px;" value="<?= $data['email']; ?>" required>
                                </div>
                                <div class="form-group row mt-2">
                                    <div class="col-sm-6">
                                        <label>Password</label>
                                        <input name="password" type="password" class="form-control form-control-user"
                                            style="border-radius: 5px;" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Confirm Password</label>
                                        <input name="password_repeat" type="password" class="form-control form-control-user"
                                            style="border-radius: 5px;" required>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Select Role</label><br>
                                    <select name="roles" id="roles" style="width:100%;height:50px;border: 1px solid lightgrey;border-radius: 5px;font-size:14px;padding: 10px 10px;" value="<?= $data['roles']; ?>" required>
                                        <option value="member">Member</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="form-group mt-4">
                                    <input class="btn btn-primary btn-icon-split" type="submit" name="submit" value="Edit <?= $type;?>" style="width:30%;height: 40px;">
                                </div>
                            </form>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Jagad Raya 2022</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.all.min.js" integrity="sha256-nk6ExuG7ckFYKC1p3efjdB14TU+pnGwTra1Fnm6FvZ0=" crossorigin="anonymous"></script>

    <?php if(isset($_POST['submit'])) : ?>
        <?php if($type=='Users') : ?>
            <?php if($_POST['password'] == $_POST['password_repeat']) : ?>
                <?php if(edit($_POST,$type,$id) >= 1) : ?>
                    <script>
                        Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data berhasil diedit!',
                        confirmButtonText: 'OK',
                        }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = "../admin/<?= $backFile ?>";
                        }
                        })
                    </script>
                <?php else : ?>
                    <script>
                        Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Data gagal diedit!',
                        confirmButtonText: 'Ok',
                        })
                    </script>
                <?php endif; ?>
            <?php else: ?>
                <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'passwordmu tidak sama!',
                    })
                </script>
            <?php endif; ?>
        <?php elseif($type=='Books') : ?>
            <?php if(edit($_POST,$type,$_POST['id']) == true) : ?>
                <script>
                    Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Data berhasil diedit!',
                    confirmButtonText: 'OK',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "../admin/<?= $backFile ?>";
                    }
                    })
                </script>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>