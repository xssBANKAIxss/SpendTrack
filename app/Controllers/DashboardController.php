<?php

namespace App\Controllers;

use App\Models\ExpenseModel;

class DashboardController extends BaseController
{
  public function index()
  {
    $userId = session()->get('user_id');

    // Total Expenses
    $totalExpenses = (new ExpenseModel())
      ->selectSum('amount')
      ->where('user_id', $userId)
      ->first();

    // This Month
    $monthStart = date('Y-m-01');
    $nextMonthStart = date('Y-m-d', strtotime('first day of next month'));

    $thisMonth = (new ExpenseModel())
      ->selectSum('amount')
      ->where('user_id', $userId)
      ->where('expense_date >=', $monthStart)
      ->where('expense_date <', $nextMonthStart)
      ->first();

    // Top Category
    $topCategory = (new ExpenseModel())
      ->select('category, COUNT(*) as total')
      ->where('user_id', $userId)
      ->groupBy('category')
      ->orderBy('total', 'DESC')
      ->first();

    // Total Records
    $totalRecords = (new ExpenseModel())
      ->where('user_id', $userId)
      ->countAllResults();

    // ---- CHART DATA ----

    // Monthly Expenses (Area Chart)
    $database = db_connect();
    $monthExpression = $database->DBDriver === 'SQLite3'
      ? "CAST(strftime('%m', expense_date) AS INTEGER)"
      : 'MONTH(expense_date)';

    $monthlyExpenses = (new ExpenseModel())
      ->select($monthExpression . ' as month, SUM(amount) as total')
      ->where('user_id', $userId)
      ->where('expense_date >=', date('Y-01-01'))
      ->where('expense_date <', date('Y-01-01', strtotime('+1 year')))
      ->groupBy($monthExpression, false)
      ->orderBy($monthExpression, 'ASC', false)
      ->findAll();

    // Fill all 12 months with 0 (so missing months show as 0)
    $monthlyData = array_fill(1, 12, 0);
    foreach ($monthlyExpenses as $row) {
      $monthlyData[$row['month']] = (float) $row['total'];
    }

    // Category Expenses (Pie Chart)
    $categoryExpenses = (new ExpenseModel())
      ->select('category, SUM(amount) as total')
      ->where('user_id', $userId)
      ->groupBy('category')
      ->findAll();

    // Recent Expenses (last 5)
    $recentExpenses = (new ExpenseModel())
      ->where('user_id', $userId)
      ->orderBy('expense_date', 'DESC')
      ->orderBy('id', 'DESC')
      ->limit(5)
      ->findAll();

    // ---- PASS TO VIEW ----

    $data = [
      'totalExpenses'    => $totalExpenses['amount'] ?? 0,
      'thisMonth'        => $thisMonth['amount'] ?? 0,
      'topCategory'      => $topCategory['category'] ?? 'None',
      'totalRecords'     => $totalRecords,
      'monthlyData'      => array_values($monthlyData),
      'categoryExpenses' => $categoryExpenses,
      'recentExpenses'   => $recentExpenses,
    ];

    return view('dashboard/index', $data);
  }
}
