<h1>All Expenses</h1>

<table class="table table-bordered">
  <thead>
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
        <td><?= esc($expense['name']) ?></td>
        <td><?= esc($expense['title']) ?></td>
        <td>₱<?= esc($expense['amount']) ?></td>
        <td><?= esc($expense['category']) ?></td>
        <td><?= esc($expense['expense_date']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>