<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SpendTrack - Verify Code</title>

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
                    <h1 class="h4 text-gray-900 mb-2"><b>Verify Your Email</b></h1>
                    <p class="mb-4">
                      We sent a 6-digit code to
                      <strong><?= esc($email ?? 'your email') ?></strong>.
                      Enter it below to continue.
                    </p>
                  </div>

                  <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger">
                      <?= session()->getFlashdata('error') ?>
                    </div>
                  <?php endif; ?>

                  <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success">
                      <?= session()->getFlashdata('success') ?>
                    </div>
                  <?php endif; ?>

                  <form class="user" method="post" action="<?= base_url('otp/verify') ?>">

                    <?= csrf_field() ?>

                    <div class="form-group">
                      <input type="text"
                        name="otp_code"
                        class="form-control form-control-user text-center"
                        id="otpCode"
                        placeholder="Enter 6-digit code"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        autocomplete="one-time-code"
                        autofocus
                        required>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Verify Code
                    </button>

                    <hr>

                  </form>

                  <div class="text-center">
                    <form method="post" action="<?= base_url('otp/resend') ?>">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-link small p-0">
                        Didn't get a code? Resend
                      </button>
                    </form>
                  </div>
                  <div class="text-center mt-2">
                    <a class="small" href="<?= base_url('login') ?>">Back to Login</a>
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