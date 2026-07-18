<?= $this->include('layouts/header') ?>

<div id="wrapper">

  <?= $this->include('layouts/sidebar') ?>

  <div id="content-wrapper" class="d-flex flex-column">

    <div id="content" class="flex-grow-1">

      <?= $this->include('layouts/topbar') ?>

      <div class="container-fluid">

        <?= $this->renderSection('content') ?>

      </div>

    </div>

    <?= $this->include('layouts/footer') ?>

  </div>

</div>

<?= $this->include('layouts/modal') ?>