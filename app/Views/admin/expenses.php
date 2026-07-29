<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$expenseList = $expenses ?? [];
$expenses = $expenseList;
$expenseCount = count($expenseList);
$totalAmount = 0;
$usersWithExpenses = [];
$expenseCategories = [];
$categories = [];

foreach ($expenseList as $expense) {
  $totalAmount += (float) ($expense['amount'] ?? 0);
  $usersWithExpenses[(int) ($expense['user_id'] ?? 0)] = true;

  $categoryName = trim((string) ($expense['category'] ?? ''));
  if ($categoryName !== '') {
    $expenseCategories[$categoryName] = true;
    $categories[$categoryName] = ['name' => $categoryName];
  }
}

$categories = array_values($categories);
$totalUsers = count($usersWithExpenses);
$totalCategories = count($expenseCategories);
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="h3 mb-1 text-gray-800">All Expenses</h1>
    <p class="mb-0 text-muted">View and monitor all recorded expenses from every user.</p>
  </div>

  <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary btn-sm shadow-sm">
    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i>
    Dashboard
  </a>
</div>

<div class="row">

  <!-- Total Records -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">

          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
              Total Records
            </div>

            <div class="h4 mb-0 font-weight-bold text-gray-800">
              <?= $expenseCount ?>
            </div>
          </div>

          <div class="col-auto">
            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Total Amount -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">

        <div class="row no-gutters align-items-center">

          <div class="col mr-2">

            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
              Total Amount
            </div>

            <div class="h4 mb-0 font-weight-bold text-gray-800">
              ₱<?= number_format($totalAmount, 2) ?>
            </div>

          </div>

          <div class="col-auto">
            <i class="fas fa-wallet fa-2x text-gray-300"></i>
          </div>

        </div>

      </div>
    </div>
  </div>

  <!-- Users -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">

      <div class="card-body">

        <div class="row no-gutters align-items-center">

          <div class="col mr-2">

            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
              Users with Expenses
            </div>

            <div class="h4 mb-0 font-weight-bold text-gray-800">
              <?= $totalUsers ?>
            </div>

          </div>

          <div class="col-auto">
            <i class="fas fa-users fa-2x text-gray-300"></i>
          </div>

        </div>

      </div>

    </div>
  </div>

  <!-- Categories -->
  <div class="col-xl-3 col-md-6 mb-4">

    <div class="card border-left-warning shadow h-100 py-2">

      <div class="card-body">

        <div class="row no-gutters align-items-center">

          <div class="col mr-2">

            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
              Categories
            </div>

            <div class="h4 mb-0 font-weight-bold text-gray-800">
              <?= $totalCategories ?>
            </div>

          </div>

          <div class="col-auto">
            <i class="fas fa-tags fa-2x text-gray-300"></i>
          </div>

        </div>

      </div>

    </div>

  </div>

</div>

<div class="card shadow mb-4">

  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">
      Filter Expenses
    </h6>
  </div>

  <div class="card-body">

    <div class="row">

      <div class="col-md-3">
        <input type="text" id="searchUser" class="form-control" placeholder="Search user...">
      </div>

      <div class="col-md-3">
        <input type="text" id="searchTitle" class="form-control" placeholder="Search title...">
      </div>

      <div class="col-md-3">
        <select id="categoryFilter" class="form-control">
          <option value="">All Categories</option>

          <?php foreach ($categories as $category): ?>

            <option value="<?= esc($category['name']) ?>">
              <?= esc($category['name']) ?>
            </option>

          <?php endforeach; ?>

        </select>
      </div>

      <div class="col-md-3">

        <button class="btn btn-primary btn-block">
          <i class="fas fa-search"></i>
          Search
        </button>

      </div>

    </div>

  </div>

</div>

<!-- Expense Table -->
<div class="card shadow mb-4">

  <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">
      Expense Records
    </h6>

    <span class="badge badge-primary px-3 py-2">
      <?= count($expenses) ?> Records
    </span>
  </div>

  <div class="card-body">

    <?php if (empty($expenses)): ?>

      <div class="text-center py-5">
        <i class="fas fa-receipt fa-4x text-gray-300 mb-3"></i>
        <h5 class="text-muted">No expenses found.</h5>
      </div>

    <?php else: ?>

      <div class="table-responsive">

        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">

          <thead class="thead-light">

            <tr>
              <th>User</th>
              <th>Title</th>
              <th>Amount</th>
              <th>Category</th>
              <th>Date</th>
            </tr>

          </thead>

          <tbody>

            <?php foreach ($expenses as $expense): ?>

              <tr>

                <td>
                  <i class="fas fa-user text-primary mr-2"></i>
                  <?= esc($expense['name']) ?>
                </td>

                <td>
                  <strong><?= esc($expense['title']) ?></strong>
                </td>

                <td class="font-weight-bold text-success">
                  ₱<?= number_format($expense['amount'], 2) ?>
                </td>

                <td>
                  <span class="badge badge-info">
                    <?= esc($expense['category']) ?>
                  </span>
                </td>

                <td>
                  <?= date('M d, Y', strtotime($expense['expense_date'])) ?>
                </td>

              </tr>

            <?php endforeach; ?>

          </tbody>

        </table>

      </div>

    <?php endif; ?>

  </div>

</div>

<?= $this->endSection() ?>