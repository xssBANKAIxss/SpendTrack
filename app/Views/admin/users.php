<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $currentUserId = (int) session()->get('user_id'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Manage Users</h1>
  <span class="badge badge-primary px-3 py-2"><?= count($users) ?> total</span>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= esc(session()->getFlashdata('success')) ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= esc(session()->getFlashdata('error')) ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  </div>
<?php endif; ?>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">User accounts</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <?php $isAdmin = $user['role'] === 'admin'; ?>
            <tr>
              <td><?= esc($user['id']) ?></td>
              <td><?= esc($user['name']) ?></td>
              <td><?= esc($user['email']) ?></td>
              <td>
                <span class="badge badge-<?= $isAdmin ? 'success' : 'secondary' ?> px-2 py-1">
                  <?= $isAdmin ? 'Admin' : 'User' ?>
                </span>
              </td>
              <td class="text-center">
                <?php if ((int) $user['id'] === $currentUserId): ?>
                  <span class="text-muted small"><i class="fas fa-user-check mr-1"></i>Current account</span>
                <?php elseif ($isAdmin): ?>
                  <button type="button" class="btn btn-sm btn-outline-warning role-action"
                    data-toggle="modal" data-target="#roleChangeModal"
                    data-action="<?= esc(base_url('admin/make-user/' . $user['id']), 'attr') ?>"
                    data-user-name="<?= esc($user['name'], 'attr') ?>"
                    data-role="User" data-verb="Demote">
                    <i class="fas fa-user-minus mr-1"></i>Make User
                  </button>
                <?php else: ?>
                  <button type="button" class="btn btn-sm btn-success role-action"
                    data-toggle="modal" data-target="#roleChangeModal"
                    data-action="<?= esc(base_url('admin/make-admin/' . $user['id']), 'attr') ?>"
                    data-user-name="<?= esc($user['name'], 'attr') ?>"
                    data-role="Admin" data-verb="Promote">
                    <i class="fas fa-user-shield mr-1"></i>Make Admin
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="roleChangeModal" tabindex="-1" role="dialog" aria-labelledby="roleChangeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="roleChangeForm" method="post" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title" id="roleChangeModalLabel">Confirm role change</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p id="roleChangeMessage" class="mb-0"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="roleChangeSubmit" type="submit" class="btn">Confirm</button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script>
  $(function () {
    $('#dataTable').DataTable({
      pageLength: 10,
      columnDefs: [{ orderable: false, targets: 4 }]
    });

    $('#roleChangeModal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const verb = button.data('verb');
      const userName = button.data('user-name');
      const role = button.data('role');
      const isPromotion = verb === 'Promote';

      $('#roleChangeForm').attr('action', button.data('action'));
      $('#roleChangeModalLabel').text(`${verb} user`);
      $('#roleChangeMessage').text(`${verb} ${userName} to ${role}? This change takes effect on their next sign-in.`);
      $('#roleChangeSubmit')
        .text(`${verb} to ${role}`)
        .toggleClass('btn-success', isPromotion)
        .toggleClass('btn-warning', !isPromotion);
    });
  });
</script>
<?= $this->endSection() ?>
