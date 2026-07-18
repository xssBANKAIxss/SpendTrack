<?= $this->extend('layouts/main') ?>


<?= $this->section('content') ?>

<!-- Begin Page Content -->

<div class="row">

  <!-- Latest Expense Amount -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Latest Expense</div>
            <!-- Shows the most recent expense amount -->
            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?= number_format($latestAmount, 2) ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-dollar-sign fa-2x text-gray-400"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Highest Single Expense -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Highest Expense</div>
            <!-- Shows the single highest expense amount -->
            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?= number_format($highestExpense, 2) ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-dollar-sign fa-2x text-gray-400"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Top Category -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Top Category</div>
            <!-- Shows the category with the most spending -->
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($topCategory) ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-layer-group fa-2x text-gray-400"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Records -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Records</div>
            <!-- Shows total number of expense entries -->
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalRecords ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-clipboard-list fa-2x text-gray-400"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Expenses Table -->
  <div class="col-12">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">My Expenses</h1>
    <p class="mb-4">Manage and track all your recorded expenses. Click <strong>Edit</strong> to update an entry or <strong>Delete</strong> to remove it.</p>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-layer-group mr-2"></i>List of Expenses
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Title</th>
                <th>Amount</th>
                <th>Category</th>
                <th>Date</th>
                <th>Notes</th>
                <th style="width: 180px; white-space: nowrap; text-align: center;">Action</th> <!-- ✅ CHANGED: added text-align center -->
              </tr>
            </thead>
            <tbody>
              <?php foreach ($expenses as $expense): ?>
                <tr>
                  <td><?= esc($expense['title']) ?></td>
                  <td>₱<?= esc($expense['amount']) ?></td>
                  <td><?= esc($expense['category']) ?></td>
                  <td><?= esc($expense['expense_date']) ?></td>
                  <td><?= esc($expense['notes']) ?></td>
                  <td style="width: 180px; white-space: nowrap; text-align: center;"> <!-- ✅ CHANGED: added text-align center -->
                    <a href="<?= base_url('expenses/edit/' . $expense['id']) ?>" class="btn btn-primary btn-sm" style="width: 80px;">
                      <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="<?= base_url('expenses/delete/' . $expense['id']) ?>" method="post" style="display:inline;">
                      <?= csrf_field() ?>
                      <!-- Delete Button (triggers modal) -->
                      <button type="button" class="btn btn-danger btn-sm" style="width: 80px;"
                        data-toggle="modal"
                        data-target="#deleteModal"
                        data-id="<?= $expense['id'] ?>"> <!-- ✅ passes the id to modal -->
                        <i class="fas fa-trash"></i> Delete
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- card closed here ☝ -->

</div>
<!-- End Page Content -->

<?= $this->endSection() ?>