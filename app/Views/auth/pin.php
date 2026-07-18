<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SpendTrack - Enter PIN</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

  <!-- SB Admin 2 CSS -->
  <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2"><b>Enter Your PIN</b></h1>
                    <p class="mb-4">
                      Welcome back<?= isset($name) ? ', ' . esc($name) : '' ?>.
                      Enter your PIN to continue.
                    </p>
                  </div>

                  <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger">
                      <?= session()->getFlashdata('error') ?>
                    </div>
                  <?php endif; ?>

                  <form class="user" method="post" action="<?= base_url('pin/verify') ?>">

                    <?= csrf_field() ?>

                    <div class="form-group">
                      <input type="password"
                        name="pin"
                        class="form-control form-control-user text-center"
                        id="pinCode"
                        placeholder="Enter your PIN"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        autocomplete="off"
                        autofocus
                        required>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Continue
                    </button>

                    <hr>

                  </form>

                  <div class="text-center">
                    <a class="small" href="<?= base_url('forgot-password') ?>">Forgot PIN?</a>
                  </div>
                  <div class="text-center mt-2">
                    <a class="small" href="<?= base_url('logout') ?>">Not you? Log out</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>

</body>

</html>