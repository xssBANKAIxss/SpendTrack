<div class="row">

  <!-- Total Expenses -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Expenses</div>
            <!-- Display total expenses formatted with 2 decimal places -->
            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?= number_format($totalExpenses, 2) ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-dollar-sign fa-2x text-gray-400"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- This Month -->
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">This Month</div>
            <!-- Display this month's total expenses formatted with 2 decimal places -->
            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?= number_format($thisMonth, 2) ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-calendar fa-2x text-gray-400"></i>
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
            <div class="h5 mb-0  font-weight-bold text-gray-800"><?= esc($topCategory) ?></div>
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
            <!-- Display the total number of expense records for this user -->
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalRecords ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-clipboard-list fa-2x text-gray-400"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>