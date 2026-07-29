<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h3 mb-1 text-gray-800">Admin Dashboard</h1>
    <p class="mb-0 text-muted">An overview of SpendTrack activity and accounts.</p>
  </div>
  <a href="<?= base_url('admin/users') ?>" class="btn btn-primary btn-sm shadow-sm">
    <i class="fas fa-users fa-sm text-white-50 mr-1"></i>Manage Users
  </a>
</div>

<div class="row">
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800"><?= esc($totalUsers) ?></div>
          </div>
          <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Admins</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800"><?= esc($totalAdmins) ?></div>
          </div>
          <div class="col-auto"><i class="fas fa-user-shield fa-2x text-gray-300"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Expenses</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800"><?= esc($totalExpenses) ?></div>
          </div>
          <div class="col-auto"><i class="fas fa-receipt fa-2x text-gray-300"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Categories</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800"><?= esc($totalCategories) ?></div>
          </div>
          <div class="col-auto"><i class="fas fa-tags fa-2x text-gray-300"></i></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Quick Actions -->
  <div class="col-lg-4 mb-4">
    <div class="card shadow h-100">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
      </div>
      <div class="card-body d-flex flex-column">
        <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-primary mb-2">
          <i class="fas fa-users mr-1"></i> Manage Users
        </a>
        <a href="<?= base_url('admin/expenses') ?>" class="btn btn-outline-primary">
          <i class="fas fa-receipt mr-1"></i> View All Expenses
        </a>
      </div>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="col-lg-8 mb-4">
    <div class="card shadow h-100">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
      </div>
      <div class="card-body">
        <?php if (empty($recentActivity)): ?>
          <p class="text-center text-muted mb-0">No expenses recorded yet.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Title</th>
                  <th>Amount</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recentActivity as $item): ?>
                  <tr>
                    <td><?= esc($item['user_name']) ?></td>
                    <td><?= esc($item['title']) ?></td>
                    <td>₱<?= number_format($item['amount'], 2) ?></td>
                    <td><?= esc($item['expense_date']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="card shadow mb-4">
  <div class="card-body d-flex align-items-center">
    <div class="btn btn-success btn-circle mr-3"><i class="fas fa-user-check"></i></div>
    <div>
      <div class="font-weight-bold">Welcome back, <?= esc(session()->get('name')) ?>.</div>
      <div class="small text-muted">You are signed in with administrator access.</div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>