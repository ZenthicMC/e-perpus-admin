<?php 
    require '../functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>E-Perpus | Login</title>
    <link rel="stylesheet" href="../admin/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="../admin/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../admin/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../admin/assets/fonts/fontawesome5-overrides.min.css">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex">
                                <div class="flex-grow-1 bg-login-image" style="background-image: url(&quot;../admin/assets/img/libary.jpg&quot;);"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-4">Welcome Back!</h4>
                                    </div>
                                    <form class="user" action="login.php" method="post">
                                        <div class="mb-3"><input class="form-control form-control-user" type="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email" required></div>
                                        <div class="mb-3"><input class="form-control form-control-user" type="password" id="exampleInputPassword" placeholder="Password" name="pwd" required></div>
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox small">
                                                <div class="form-check"><input class="form-check-input custom-control-input" type="checkbox" id="formCheck-1"><label class="form-check-label custom-control-label" for="formCheck-1">Remember Me</label></div>
                                            </div>
                                        </div><button class="btn btn-primary d-block btn-user w-100" name="login" type="submit">Login</button>
                                        <hr>
                                    </form>
                                    <div class="text-center"><a class="small" href="forgot-password.html">Forgot Password?</a></div>
                                    <div class="text-center"><a class="small" href="register.php">Create an Account!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.all.min.js" integrity="sha256-nk6ExuG7ckFYKC1p3efjdB14TU+pnGwTra1Fnm6FvZ0=" crossorigin="anonymous"></script>
    <script src="../admin/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/assets/js/theme.js"></script>
</body>
</html>
<?php if(isset($_POST['login'])) : ?>
    <?php if(login($_POST) == 1) : ?>
        <script>location.href = "../admin/dashboard.php";</script>
    <?php elseif(login($_POST) == 3) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: 'Emailmu tidak terdaftar!',
            })
        </script>
    <?php elseif(login($_POST) == 2) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: 'Passwordmu salah!',
            })
        </script>
    <?php endif; ?>
<?php endif; ?>