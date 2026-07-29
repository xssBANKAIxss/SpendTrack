<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

<a href="<?= base_url('expenses/create') ?>"
  class="btn btn-primary rounded-circle shadow"
  style="position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; z-index: 1000;"
  title="Add Expense">
  <i class="fas fa-plus fa-lg"></i>
</a>

<?= $this->include('layouts/card') ?>

<!-- Charts Row -->
<div class="row">

  <!-- Area Chart -->
  <div class="col-xl-8 col-lg-7">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Monthly Expenses</h6>
        <div class="dropdown no-arrow">
          <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="chart-area">
          <canvas id="myAreaChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Pie Chart -->
  <div class="col-xl-4 col-lg-5">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Expenses by Category</h6>
        <div class="dropdown no-arrow">
          <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="chart-pie pt-4 pb-2">
          <canvas id="myPieChart"></canvas>
        </div>
        <div class="mt-4 text-center small">
          <?php
          $legendColors = ['text-primary', 'text-success', 'text-info', 'text-warning', 'text-danger'];
          foreach ($categoryExpenses as $i => $cat):
          ?>
            <span class="mr-2">
              <i class="fas fa-circle <?= $legendColors[$i % count($legendColors)] ?>"></i>
              <?= esc($cat['category']) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>


</div>

<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Recent Expenses</h6>
      </div>
      <div class="card-body">
        <?php if (empty($recentExpenses)): ?>
          <p class="text-center text-muted mb-0">No expenses yet. Click the + button to add your first one.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Amount</th>
                  <th>Category</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recentExpenses as $expense): ?>
                  <tr>
                    <td><?= esc($expense['title']) ?></td>
                    <td>₱<?= number_format($expense['amount'], 2) ?></td>
                    <td><?= esc($expense['category']) ?></td>
                    <td><?= esc($expense['expense_date']) ?></td>
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

<script>
  window.dashboardData = {
    monthlyData: <?= json_encode($monthlyData) ?>,
    categoryExpenses: <?= json_encode($categoryExpenses) ?>
  };
</script>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/demo/chart-area-demo.js') ?>"></script>
<script src="<?= base_url('assets/js/demo/chart-pie-demo.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>