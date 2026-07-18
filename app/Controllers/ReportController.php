<?php

namespace App\Controllers;

use App\Models\ExpenseModel;

class ReportController extends BaseController
{
  public function index()
  {
    $model = new ExpenseModel();
    $userId = session()->get('user_id');

    // Total Expenses
    $totalExpenses = $model
      ->selectSum('amount')
      ->where('user_id', $userId)
      ->first();

    // This Month
    $thisMonth = $model
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
    $totalRecords = $model
      ->where('user_id', $userId)
      ->countAllResults();

    // Latest Expenses (Table)
    $latestExpenses = $model
      ->where('user_id', $userId)
      ->orderBy('expense_date', 'DESC')
      ->limit(1)
      ->findAll();

    // Category Report
    $categoryReport = $model
      ->select('category, COUNT(id) as count, SUM(amount) as total')
      ->where('user_id', $userId)
      ->groupBy('category')
      ->orderBy('total', 'DESC')
      ->findAll();

    $data = [
      'totalExpenses' => $totalExpenses['amount'] ?? 0,
      'thisMonth'     => $thisMonth['amount'] ?? 0,
      'topCategory'   => $topCategory['category'] ?? 'None',
      'totalRecords'  => $totalRecords,
      'latestExpenses' => $latestExpenses,
      'categoryReport' => $categoryReport,
    ];

    return view('reports/index', $data);
  }
}
