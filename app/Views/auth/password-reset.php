<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SpendTrack - Reset Password</title>
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
                    <h1 class="h4 text-gray-900 mb-2"><b>Set a New Password</b></h1>
                    <p class="mb-4">Use 8 or more characters with upper and lowercase letters, a number and a special character. No spaces.</p>
                  </div>

                  <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                  <?php endif; ?>

                  <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                      <?php foreach ($errors as $error): ?>
                        <div><?= esc($error) ?></div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                  <form class="user" action="<?= base_url('password/reset') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user"
                        placeholder="New password" autocomplete="new-password"
                        required minlength="8" maxlength="64"
                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9])\S{8,64}"
                        title="8-64 characters, no spaces, with a lowercase letter, an uppercase letter, a number and a special character.">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password_confirmation" class="form-control form-control-user"
                        placeholder="Confirm new password" autocomplete="new-password"
                        required maxlength="64">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Reset Password</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>