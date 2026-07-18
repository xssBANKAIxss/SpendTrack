<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="pt-2 pb-5 px-5">
      <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">Add New Expense</h1>
      </div>

      <form class="user" method="post" action="<?= base_url('expenses/store') ?>">

        <div class="form-group">
          <label for="title">Title</label>
          <input type="text"
            name="title"
            class="form-control"
            id="title"
            placeholder="e.g. Grocery Shopping">
        </div>

        <div class="form-group row">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <label for="amount">₱ Amount ₱</label>
            <input type="number"
              step="0.01"
              min="0"
              name="amount"
              class="form-control"
              id="amount"
              placeholder="0.00">
          </div>

          <div class="col-sm-6">
            <label for="category">Category</label>
            <input type="text"
              name="category"
              class="form-control"
              id="category"
              list="categoryOptions"
              placeholder="Select or type a category"
              autocomplete="off">
            <datalist id="categoryOptions">
              <option value="Food">
              <option value="Transportation">
              <option value="Utilities">
              <option value="Entertainment">
              <option value="Other">
            </datalist>
          </div>
        </div>

        <div class="form-group">
          <label for="expense_date">Date</label>
          <input type="date"
            name="expense_date"
            class="form-control"
            id="expense_date">
        </div>

        <div class="form-group">
          <label for="notes">Notes</label>
          <textarea name="notes"
            class="form-control"
            id="notes"
            rows="3"
            placeholder="Optional notes..."></textarea>
        </div>

        <hr>

        <button type="submit" class="btn btn-primary btn-user btn-block">
          Add Expense
        </button>

      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>