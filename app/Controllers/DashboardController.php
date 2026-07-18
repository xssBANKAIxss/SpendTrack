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
    $thisMonth = (new ExpenseModel())
      ->selectSum('amount')
      ->where('user_id', $userId)
      ->where('MONTH(expense_date)', date('m'))
      ->where('YEAR(expense_date)', date('Y'))
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
    $monthlyExpenses = (new ExpenseModel())
      ->select('MONTH(expense_date) as month, SUM(amount) as total')
      ->where('user_id', $userId)
      ->where('YEAR(expense_date)', date('Y'))
      ->groupBy('MONTH(expense_date)')
      ->orderBy('MONTH(expense_date)', 'ASC')
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

    // ---- PASS TO VIEW ----

    $data = [
      'totalExpenses'    => $totalExpenses['amount'] ?? 0,
      'thisMonth'        => $thisMonth['amount'] ?? 0,
      'topCategory'      => $topCategory['category'] ?? 'None',
      'totalRecords'     => $totalRecords,
      'monthlyData'      => array_values($monthlyData),
      'categoryExpenses' => $categoryExpenses,
    ];

    return view('dashboard/index', $data);
  }
}
