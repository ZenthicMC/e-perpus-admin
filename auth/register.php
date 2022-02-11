<?php 
    require '../functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>E-Perpus | Register</title>
    <link rel="stylesheet" href="../admin/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="../admin/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../admin/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../admin/assets/fonts/fontawesome5-overrides.min.css">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card shadow-lg o-hidden border-0 my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-flex">
                        <div class="flex-grow-1 bg-register-image" style="background-image: url(&quot;../admin/assets/img/libary.jpg&quot;);"></div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Create an Account!</h4>
                            </div>
                            <form class="user" action="register.php" method="post">
                                <div class="row mb-3">
                                    <div class="col-sm-12 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="exampleFirstName" placeholder="Username" name="username" required></div>
                                </div>
                                <div class="mb-3"><input class="form-control form-control-user" type="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email Address" name="email" required></div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="password" id="examplePasswordInput" placeholder="Password" name="password" required></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="password" id="exampleRepeatPasswordInput" placeholder="Repeat Password" name="password_repeat" required></div>
                                </div><button class="btn btn-primary d-block btn-user w-100" name="register" type="submit">Register Account</button>
                                <hr>
                            </form>
                            <div class="text-center"><a class="small" href="forgot-password.html">Forgot Password?</a></div>
                            <div class="text-center"><a class="small" href="login.php">Already have an account? Login!</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.all.min.js" integrity="sha256-nk6ExuG7ckFYKC1p3efjdB14TU+pnGwTra1Fnm6FvZ0=" crossorigin="anonymous"></script>
    <script src="../admin/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/assets/js/theme.js"></script>
    <?php if(isset($_POST['register'])) : ?>
        <?php if($_POST['password'] == $_POST['password_repeat']) : ?>
            <?php if(checkUsers($_POST['username'],$_POST['email']) < 1) : ?>
                <?php if(register($_POST,$_POST['password']) >= 1) : ?>
                    <script>
                        Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Akunmu berhasil didaftarkan!',
                        confirmButtonText: 'OK',
                        }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = "../auth/login.php";
                        }
                        })
                    </script>
                <?php endif; ?>
            <?php else: ?>
                <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Username atau password sudah pernah terdaftar!',
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
    <?php endif; ?>
</body>
</html>