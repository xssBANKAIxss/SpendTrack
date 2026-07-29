<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SpendTrack - Reset PIN</title>
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
              <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2"><b>Reset Your PIN</b></h1>
                    <p class="mb-4">Enter your account email. We will send a verification code before you can create a new PIN.</p>
                  </div>

                  <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                  <?php endif; ?>
                  <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                  <?php endif; ?>

                  <form class="user" method="post" action="<?= base_url('forgot-pin') ?>">
                    <?= csrf_field() ?>
                    <div class="form-group">
                      <input type="email" name="email" class="form-control form-control-user" placeholder="Enter email address" autocomplete="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Send verification code</button>
                  </form>

                  <hr>
                  <div class="text-center">
                    <a class="small" href="<?= base_url('login') ?>">Back to login</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
</body>

</html>
