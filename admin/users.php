<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: ../auth/login.php");
        exit;
    }

    include('../functions.php');
    list($result,$jumlahData,$dataAwal,$dataPerHalaman,$halamanAktif,$jumlahHalaman) = pagination(10,'users');
    if($dataAwal == 0) {$dataAwal = 1;}
    if($dataAwal == $dataPerHalaman) {$dataAwal = 1;$dataPerHalaman = $jumlahData-$dataPerHalaman;}
    if($jumlahData < $dataPerHalaman) {$dataPerHalaman = $jumlahData;}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>E-Perpus | Users</title>
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
                    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="books.php"><i class="fa fa-book"></i><span>Books</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="visitors.php"><i class="fa fa-eye"></i><span>Visitos</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="users.php"><i class="fa fa-users"></i><span>Users</span></a></li>
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
                    <h3 class="text-dark mb-4">Users</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">List Users</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 text-nowrap">
                                    <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable"></div>
                                    <a href="new.php?type=Users" class="btn btn-primary btn-icon-split btn-sm">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="text">Add Users</span>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-md-end dataTables_filter" id="dataTable_filter"><label class="form-label"><input type="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search"></label></div>
                                </div>
                            </div>
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result as $data): ?>
                                        <tr>
                                            <td><?=$data['username']?></td>
                                            <td><?=$data['email']?></td>
                                            <td><?=$data['roles']?></td>
                                            <td>
                                                <a href="edit.php?type=Users&id=<?= $data['id_user']; ?>" class="btn btn-warning btn-circle btn-sm">
                                                    <i class="fas fa-pen text-white"></i>
                                                </a>
                                                <a href="javascript:confirmBtn();" class="btn btn-danger btn-circle btn-sm">
                                                    <i class="fas fa-trash text-white"></i>
                                                </a>                                                
                                            </td>                       
                                        </tr>
                                        <script>
                                            function confirmBtn() {
                                                Swal.fire({
                                                title: 'Are you sure?',
                                                text: "You won't be able to revert this!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: 'primary',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes, delete it!'
                                                }).then((result) => {
                                                if (result.isConfirmed) {
                                                    location.href = "../admin/delete.php?type=Users&id=<?= $data['id_user']; ?>";
                                                }
                                                })
                                            }
                                        </script>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">Showing <?= $dataAwal ?> to <?= $dataPerHalaman ?> of <?= $jumlahData ?></p>
                                </div>
                                <div class="col-md-6">
                                    <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination">
                                            <?php if($halamanAktif > 1):?>
                                                <li class="page-item"><a class="page-link" href="?page=<?= $halamanAktif-1?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                            <?php else:?>
                                                <li class="page-item disabled"><a class="page-link" href="?page=<?= $halamanAktif-1?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                            <?php endif;?>
                                            <?php for($i = 1; $i <= $jumlahHalaman; $i++) :?>
                                                <?php if($i==$halamanAktif) :?>
                                                    <li class="page-item active"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                                                <?php else:?>
                                                    <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                                                <?php endif;?>
                                            <?php endfor; ?>
                                            <?php if($halamanAktif < $jumlahHalaman):?>
                                                <li class="page-item"><a class="page-link" href="?page=<?= $halamanAktif+1?>" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                            <?php else:?>
                                                <li class="page-item disabled"><a class="page-link" href="?page=<?= $halamanAktif-1?>" aria-label="Previous"><span aria-hidden="true">»</span></a></li>
                                            <?php endif;?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © Jagad Raya 2022</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.all.min.js" integrity="sha256-nk6ExuG7ckFYKC1p3efjdB14TU+pnGwTra1Fnm6FvZ0=" crossorigin="anonymous"></script>
</body>
</html>