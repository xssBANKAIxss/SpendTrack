  <?= $this->extend('layouts/main') ?>

  <?= $this->section('content') ?>

  <?= $this->include('layouts/card') ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Reports</h1>
    <button onclick="window.print()" class="btn btn-primary">
      <i class="fas fa-print mr-2"></i>Print Report
    </button>
  </div>

  <div class="row">

    <!-- Expenses Table -->
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-coins mr-2"></i>Latest Expense
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
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($latestExpenses)): ?>
                  <?php foreach ($latestExpenses as $expense): ?>
                    <tr>
                      <td><?= esc($expense['title']) ?></td>
                      <td>₱<?= number_format($expense['amount'], 2) ?></td>
                      <td><?= esc($expense['category']) ?></td>
                      <td><?= esc($expense['expense_date']) ?></td>
                      <td><?= esc($expense['notes']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center">No expenses found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- card closed here ☝ -->

    <!-- Summary Expense -->
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-layer-group mr-2"></i>Category Report Table
          </h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead class="thead-light">
                <tr>
                  <th>Category</th>
                  <th>No. of Expenses</th>
                  <th>Total Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $grandTotal = 0;
                $grandCount = 0;  // ← add
                $icons = [
                  'Food'          => 'fa-utensils text-danger',
                  'Transport'     => 'fa-car text-primary',
                  'Utilities'     => 'fa-bolt text-warning',
                  'Entertainment' => 'fa-gamepad text-success',
                ];
                foreach ($categoryReport as $row):
                  $grandTotal += $row['total'];
                  $grandCount += $row['count'];  // ← add
                  $icon = $icons[$row['category']] ?? 'fa-tag text-secondary';
                ?>
                  <tr>
                    <td><i class="fas <?= $icon ?> mr-2"></i><?= esc($row['category']) ?></td>
                    <td class="text-center"><?= $row['count'] ?></td> <!-- ← add -->
                    <td class="font-weight-bold">₱<?= number_format($row['total'], 2) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr class="bg-light">
                  <th>Total</th>
                  <th class="text-center"><?= $grandCount ?> expenses</th> <!-- ← add -->
                  <th class="text-danger">₱<?= number_format($grandTotal, 2) ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- card closed here ☝ -->
    </div>


    <?= $this->endSection() ?>